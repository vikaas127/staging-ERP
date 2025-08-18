<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: AWS S3 Integration
Description: Sync all Perfex files to Amazon AWS S3.
Version: 1.0.0
Requires at least: 2.3.*
*/

require(__DIR__ . '/vendor/autoload.php');

/**
 * Define module name
 */
define('AWS_INTEGRATION_MODULE_NAME', 'aws_integration');

/**
 * Module hooks
 */
hooks()->add_action('admin_init', 'aws_integration_module_init_setup_menu_items');
hooks()->add_action('admin_init', 'aws_check_and_delete_s3_files');

hooks()->add_filter('before_handle_estimate_request_attachment', 'aws_handle_estimate_request_attachment');
hooks()->add_filter('before_handle_project_file_uploads', 'aws_handle_project_file_uploads');
hooks()->add_filter('before_handle_contract_attachment', 'aws_handle_contract_attachment');
hooks()->add_filter('before_handle_lead_attachment', 'aws_handle_lead_attachment');
hooks()->add_filter('before_handle_task_attachments_array', 'aws_handle_task_attachments_array');
hooks()->add_filter('before_handle_sales_attachments', 'aws_handle_sales_attachments');
hooks()->add_filter('before_handle_client_attachment', 'aws_handle_client_attachment');
hooks()->add_filter('before_handle_expense_attachment', 'aws_handle_expense_attachment');
hooks()->add_filter('before_handle_ticket_attachment', 'aws_handle_ticket_attachment');
hooks()->add_filter('download_file_path', 'aws_download_file_path', 10, 2);


/**
 * Register module activation hook
 */
register_activation_hook(AWS_INTEGRATION_MODULE_NAME, 'aws_integration_module_activation_hook');

function aws_integration_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register module deactivation hook
 */
register_deactivation_hook(AWS_INTEGRATION_MODULE_NAME, 'aws_integration_module_deactivation_hook');

function aws_integration_module_deactivation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/uninstall.php');
}

/**
 * Register language files
 */
register_language_files(AWS_INTEGRATION_MODULE_NAME, [AWS_INTEGRATION_MODULE_NAME]);

/**
 * Initialize setup menu items.
 * 
 * @return void
 */
function aws_integration_module_init_setup_menu_items()
{
    $CI = &get_instance();

    if (is_admin()) {
       $CI->app_menu->add_setup_children_item('Developer',  [
            'name' => _l('aws_integration'),
            'href' => admin_url('aws_integration'),
            'position' => 60,
        ]);
    }
}

/**
 * Check and delete files from S3.
 * 
 * @return void
 */
function aws_check_and_delete_s3_files()
{
    // Only run the function is AWS S3 integration is enabled
    if (get_option('enable_aws_integration')) {
        $CI = &get_instance();
        $CI->load->library(AWS_INTEGRATION_MODULE_NAME . '/' . 'aws_client');

        // Get all file names, rel_type, and rel_id from the files table
        $files = $CI->db->select('file_name, rel_type, rel_id')->get(db_prefix() . 'files')->result_array();
        $files_keys = array_map(function ($file) {
            return $file['file_name'] . '|' . $file['rel_type'] . '|' . $file['rel_id'];
        }, $files);

        // Get all file names, project id from the project files table
        $project_files = $CI->db->select('id, original_file_name, project_id')->get(db_prefix() . 'project_files')->result_array();
        $project_files_keys = array_map(function ($project_file) {
            return $project_file['original_file_name'] . '|project|' . $project_file['project_id'];
        }, $project_files);

        // Get all file names, ticketid from the ticket attachments table
        $ticket_attachments = $CI->db->select('id, file_name, ticketid')->get(db_prefix() . 'ticket_attachments')->result_array();
        $ticket_attachments_keys = array_map(function ($ticket_attachment) {
            return $ticket_attachment['file_name'] . '|ticket|' . $ticket_attachment['ticketid'];
        }, $ticket_attachments);


        // Get all file names, rel_type, rel_id, and s3_key from the s3_files table
        $s3files = $CI->db->select('name, rel_type, rel_id, s3_key')->get(db_prefix() . 's3_files')->result_array();
        $s3files_keys = array_map(function ($s3file) {
            return $s3file['name'] . '|' . $s3file['rel_type'] . '|' . $s3file['rel_id'];
        }, $s3files);

        // Combine all existing file keys from files, project_files, and ticket_attachments tables
        $all_existing_files_keys = array_merge($files_keys, $project_files_keys, $ticket_attachments_keys);

        // Find files that are in s3_files but not in any of the three tables
        $files_to_delete_keys = array_diff($s3files_keys, $all_existing_files_keys);

        // Loop through S3 files and delete the file from S3 and database
        foreach ($s3files as $s3file) {
            $s3file_key = $s3file['name'] . '|' . $s3file['rel_type'] . '|' . $s3file['rel_id'];
            if (in_array($s3file_key, $files_to_delete_keys)) {
                // Delete the file from AWS S3
                $CI->aws_client->delete($s3file['s3_key']);

                // Also, delete the file record from tbls3_files
                $CI->db->where('name', $s3file['name'])
                    ->where('rel_type', $s3file['rel_type'])
                    ->where('rel_id', $s3file['rel_id'])
                    ->delete(db_prefix() . 's3_files');
            }
        }
    }
}

/**
 * Handle estimate request attachment upload.
 * 
 * @param array $data
 * @return mixed
 */
function aws_handle_estimate_request_attachment($data)
{
    if (get_option('enable_aws_integration')) {
        $CI = &get_instance();
        $CI->load->model('estimate_request_model');

        // Define S3 data
        $s3Data = [
            'files' => $data['files'],
            'index_name' => $data['index_name'],
            'rel_id' => $data['estimate_request_id'],
            'rel_type' => 'estimate_request',
        ];

        // Perform S3 upload
        $uploadedFiles = aws_upload_file_to_s3($s3Data);

        // Loop through each uploaded file
        foreach ($uploadedFiles as $uploadedFile) {
            // Database data
            $dbData = [];
            $dbData[] = [
                'name' => $uploadedFile['originalName'],
                'link' => $uploadedFile['url'],
                'mime' => $uploadedFile['filetype'],
                'thumbnailLink' => is_image($uploadedFile['url']) ? $uploadedFile['url'] : '',
            ];
            // Add estimate request attachment to database
            $CI->estimate_request_model->add_attachment_to_database($data['estimate_request_id'], $dbData, true);
        }

        // Upload handled externally
        $data['handled_externally'] = true;
        $data['handled_externally_successfully'] = true;

        // Return the data
        return $data;
    }
}

/**
 * Handle project files upload.
 * 
 * @param array $data
 * @return mixed
 */
function aws_handle_project_file_uploads($data)
{
    if (get_option('enable_aws_integration')) {
        $CI = &get_instance();

        // Define S3 data
        $s3Data = [
            'files' => $data['files'],
            'index_name' => $data['index_name'],
            'rel_id' => $data['project_id'],
            'rel_type' => 'project',
        ];

        // Perform S3 upload
        $uploadedFiles = aws_upload_file_to_s3($s3Data);

        $filesIDS = [];

        // Loop through each uploaded file
        foreach ($uploadedFiles as $uploadedFile) {
            // Database data
            if (is_client_logged_in()) {
                $contact_id = get_contact_user_id();
                $staffid = 0;
            } else {
                $staffid = get_staff_user_id();
                $contact_id = 0;
            }
            $dbData = [];
            $dbData = [
                'project_id' => $data['project_id'],
                'file_name' => $uploadedFile['filename'],
                'original_file_name' => $uploadedFile['originalName'],
                'filetype' => $uploadedFile['filetype'],
                'dateadded' => date('Y-m-d H:i:s'),
                'staffid' => $staffid,
                'contact_id' => $contact_id,
                'subject' => $uploadedFile['originalName'],
                'external' => 'aws',
                'external_link' => $uploadedFile['url'],
                'thumbnail_link' => is_image($uploadedFile['url']) ? $uploadedFile['url'] : '',
            ];

            // If client is logged in make the file visible to customer
            if (is_client_logged_in()) {
                $data['visible_to_customer'] = 1;
            } else {
                $data['visible_to_customer'] = ($CI->input->post('visible_to_customer') == 'true' ? 1 : 0);
            }

            // Add project files to database
            $CI->db->insert(db_prefix() . 'project_files', $dbData);

            $insert_id = $CI->db->insert_id();
            if ($insert_id) {
                array_push($filesIDS, $insert_id);
            }
        }

        // Add project file notification
        if (count($filesIDS) > 0) {
            $CI->load->model('projects_model');
            end($filesIDS);
            $lastFileID = key($filesIDS);
            $CI->projects_model->new_project_file_notification($filesIDS[$lastFileID], $data['project_id']);
        }

        // Upload handled externally
        $data['handled_externally'] = true;
        $data['handled_externally_successfully'] = true;

        // Return the data
        return $data;
    }
}

/**
 * Handle contract attachment upload.
 * 
 * @param array $data
 * @return mixed
 */
function aws_handle_contract_attachment($data)
{
    if (get_option('enable_aws_integration')) {
        $CI = &get_instance();
        $CI->load->model('misc_model');

        // Define S3 data
        $s3Data = [
            'files' => $data['files'],
            'index_name' => $data['index_name'],
            'rel_id' => $data['contract_id'],
            'rel_type' => 'contract',
        ];

        // Perform S3 upload
        $uploadedFiles = aws_upload_file_to_s3($s3Data);

        // Loop through each uploaded file
        foreach ($uploadedFiles as $uploadedFile) {
            // Database data
            $dbData = [];
            $dbData[] = [
                'name' => $uploadedFile['originalName'],
                'link' => $uploadedFile['url'],
                'mime' => $uploadedFile['filetype'],
                'thumbnailLink' => is_image($uploadedFile['url']) ? $uploadedFile['url'] : '',
            ];
            // Add contract attachment to database
            $CI->misc_model->add_attachment_to_database($data['contract_id'], 'contract', $dbData, true);
        }

        // Upload handled externally
        $data['handled_externally'] = true;
        $data['handled_externally_successfully'] = true;

        // Return the data
        return $data;
    }
}

/**
 * Handle lead attachment upload.
 * 
 * @param array $data
 * @return mixed
 */
function aws_handle_lead_attachment($data)
{
    if (get_option('enable_aws_integration')) {
        $CI = &get_instance();
        $CI->load->model('leads_model');

        // Define S3 data
        $s3Data = [
            'files' => $data['files'],
            'index_name' => $data['index_name'],
            'rel_id' => $data['lead_id'],
            'rel_type' => 'lead',
        ];

        // Perform S3 upload
        $uploadedFiles = aws_upload_file_to_s3($s3Data);

        // Loop through each uploaded file
        foreach ($uploadedFiles as $uploadedFile) {
            // Database data
            $dbData = [];
            $dbData[] = [
                'name' => $uploadedFile['originalName'],
                'link' => $uploadedFile['url'],
                'mime' => $uploadedFile['filetype'],
                'thumbnailLink' => is_image($uploadedFile['url']) ? $uploadedFile['url'] : '',
            ];
            // Add lead attachment to database
            $CI->leads_model->add_attachment_to_database($data['lead_id'], $dbData, true, $data['form_activity']);
        }

        // Upload handled externally
        $data['handled_externally'] = true;
        $data['handled_externally_successfully'] = true;

        // Return the data
        return $data;
    }
}

/**
 * Handle task attachments upload.
 * 
 * @param array $data
 * @return mixed
 */
function aws_handle_task_attachments_array($data)
{
    // Pass uploaded files as an empty array to fix task add issue.
    if (empty($data['files'])) {
        $data['uploaded_files'] = [];

        return $data;
    }

    if (get_option('enable_aws_integration')) {
        $CI = &get_instance();
        $CI->load->model('tasks_model');

        // Define S3 data
        $s3Data = [
            'files' => $data['files'],
            'index_name' => $data['index_name'],
            'rel_id' => $data['task_id'],
            'rel_type' => 'task',
        ];
        // Perform S3 upload
        $uploadedFiles = aws_upload_file_to_s3($s3Data);

        // Loop through each uploaded file
        foreach ($uploadedFiles as $uploadedFile) {
            // Database data
            $dbData = [];
            $fileData = [
                'name' => $uploadedFile['originalName'],
                'link' => $uploadedFile['url'],
                'mime' => $uploadedFile['filetype'],
                'thumbnailLink' => is_image($uploadedFile['url']) ? $uploadedFile['url'] : '',
            ];
            if (is_client_logged_in()) {
                $dbData[] = array_merge($fileData, ['contact_id' => get_contact_user_id()]);
            } else {
                $dbData[] = array_merge($fileData, ['staffid' => get_staff_user_id()]);
            }
            // Add task attachment to database
            $CI->tasks_model->add_attachment_to_database($data['task_id'], $dbData, true);
        }

        // Upload handled externally
        $data['handled_externally'] = true;

        // Return the data
        return $data;
    }
}

/**
 * Handle sales attachment upload.
 * 
 * @param array $data
 * @return mixed
 */
function aws_handle_sales_attachments($data)
{
    if (get_option('enable_aws_integration')) {
        $CI = &get_instance();
        $CI->load->model('misc_model');

        // Define S3 data
        $s3Data = [
            'files' => $data['files'],
            'index_name' => $data['index_name'],
            'rel_id' => $data['rel_id'],
            'rel_type' => $data['rel_type'],
        ];

        // Perform S3 upload
        $uploadedFiles = aws_upload_file_to_s3($s3Data);

        // Loop through each uploaded file
        foreach ($uploadedFiles as $uploadedFile) {
            // Database data
            $dbData = [];
            $dbData[] = [
                'name' => $uploadedFile['originalName'],
                'link' => $uploadedFile['url'],
                'mime' => $uploadedFile['filetype'],
                'thumbnailLink' => is_image($uploadedFile['url']) ? $uploadedFile['url'] : '',
            ];
            // Add sale attachment to database
            $CI->misc_model->add_attachment_to_database($data['rel_id'], $data['rel_type'], $dbData, true);

            // Add log activity
            if ($data['rel_type'] == 'invoice') {
                $CI->load->model('invoices_model');
                $CI->invoices_model->log_invoice_activity($data['rel_id'], 'invoice_activity_added_attachment');
            } elseif ($data['rel_type'] == 'estimate') {
                $CI->load->model('estimates_model');
                $CI->estimates_model->log_estimate_activity($data['rel_id'], 'estimate_activity_added_attachment');
            }
        }

        // Upload handled externally
        $data['handled_externally'] = true;
        $data['handled_externally_successfully'] = true;

        // Return the data
        return $data;
    }
}

/**
 * Handle client attachment upload.
 * 
 * @param array $data
 * @return mixed
 */
function aws_handle_client_attachment($data)
{
    if (get_option('enable_aws_integration')) {
        $CI = &get_instance();
        $CI->load->model('misc_model');
        $totalUploaded = 0;

        // Define S3 data
        $s3Data = [
            'files' => $data['files'],
            'index_name' => $data['index_name'],
            'rel_id' => $data['customer_id'],
            'rel_type' => 'customer',
        ];

        // Perform S3 upload
        $uploadedFiles = aws_upload_file_to_s3($s3Data);

        // Loop through each uploaded file
        foreach ($uploadedFiles as $uploadedFile) {
            // Database data
            $dbData = [];
            $dbData[] = [
                'name' => $uploadedFile['originalName'],
                'link' => $uploadedFile['url'],
                'mime' => $uploadedFile['filetype'],
                'thumbnailLink' => is_image($uploadedFile['url']) ? $uploadedFile['url'] : '',
            ];
            if ($data['customer_upload'] == true) {
                $dbData[0]['staffid'] = 0;
                $dbData[0]['contact_id'] = get_contact_user_id();
                $dbData['visible_to_customer'] = 1;
            }

            // Add client attachment to database
            $CI->misc_model->add_attachment_to_database($data['customer_id'], 'customer', $dbData, true);
            $totalUploaded++;
        }

        // Upload handled externally
        $data['handled_externally'] = true;
        $data['total_uploaded'] = $totalUploaded;

        // Return the data
        return $data;
    }
}

/**
 * Handle client attachment upload.
 * 
 * @param array $data
 * @return mixed
 */
function aws_handle_expense_attachment($data)
{
    if (get_option('enable_aws_integration')) {
        $CI = &get_instance();
        $CI->load->model('misc_model');

        // Define S3 data
        $s3Data = [
            'files' => $data['files'],
            'index_name' => $data['index_name'],
            'rel_id' => $data['expense_id'],
            'rel_type' => 'expense',
        ];

        // Perform S3 upload
        $uploadedFiles = aws_upload_file_to_s3($s3Data);

        // Loop through each uploaded file
        foreach ($uploadedFiles as $uploadedFile) {
            // Database data
            $dbData = [];
            $dbData[] = [
                'name' => $uploadedFile['originalName'],
                'link' => $uploadedFile['url'],
                'mime' => $uploadedFile['filetype'],
                'thumbnailLink' => is_image($uploadedFile['url']) ? $uploadedFile['url'] : '',
            ];
            // Add expense attachment to database
            $CI->misc_model->add_attachment_to_database($data['expense_id'], 'expense', $dbData, true);

            // Upload a dummy folder (empty one) locally to solve delete file problem
            $path = get_upload_path_by_type('expense') . $data['expense_id'] . '/';
            _maybe_create_upload_path($path);
        }

        // Upload handled externally
        $data['handled_externally'] = true;
        $data['handled_externally_successfully'] = true;

        // Return the data
        return $data;
    }
}

/**
 * Handle ticket attachment upload.
 * 
 * @param array $data
 * @return mixed
 */
function aws_handle_ticket_attachment($data)
{
    if (get_option('enable_aws_integration')) {
        $CI = &get_instance();
        $uploaded_files = [];

        // Define S3 data
        $s3Data = [
            'files' => $data['files'],
            'index_name' => $data['index_name'],
            'rel_id' => $data['ticket_id'],
            'rel_type' => 'ticket',
        ];

        // Perform S3 upload
        $uploadedFiles = aws_upload_file_to_s3($s3Data);

        // Loop through each uploaded file
        foreach ($uploadedFiles as $uploadedFile) {
            if ($uploadedFile['number'] <= get_option('maximum_allowed_ticket_attachments')) {
                // Getting file extension
                $extension = strtolower(pathinfo($uploadedFile['originalName'], PATHINFO_EXTENSION));

                $allowed_extensions = explode(',', get_option('ticket_attachments_file_extensions'));
                $allowed_extensions = array_map('trim', $allowed_extensions);
                // Check for all cases if this extension is allowed
                if (!in_array('.' . $extension, $allowed_extensions)) {
                    continue;
                }

                // Add the file to uploaded_files
                array_push($uploaded_files, [
                    'file_name' => $uploadedFile['filename'],
                    'filetype' => $uploadedFile['filetype'],
                ]);
            }

            // Upload a dummy file (empty one) locally to solve delete file problem
            $path = get_upload_path_by_type('ticket') . $data['ticket_id'] . '/';
            _maybe_create_upload_path($path);
            file_put_contents("{$path}{$uploadedFile['filename']}", "");
        }

        // Upload handled externally
        $data['handled_externally'] = true;
        $data['uploaded_files'] = $uploaded_files;

        // Return the data
        return $data;
    }
}

/**
 * Download file from S3.
 * 
 * @param string $path
 * @param array $data
 * @return void
 */
function aws_download_file_path($path, $data)
{
    if (get_option('enable_aws_integration')) {
        $CI = &get_instance();

        // Get the attachment from files based on the rel_id and rel_type
        $CI->db->where('rel_id', $data['attachmentid'])->where('rel_type', $data['folder']);
        $attachment = $CI->db->get(db_prefix() . 'files')->row();

        // Get the attachment from ticket attachments based on the attachmentid
        $CI->db->where('id', $data['attachmentid']);
        $ticket_attachment = $CI->db->get(db_prefix() . 'ticket_attachments')->row();

        // Get the S3 Key
        $s3_key = '';
        if (get_option('aws_bucket')) {
            if ($data['folder'] === 'ticket') {
                $s3_key = aws_get_upload_path_by_type('ticket') . $ticket_attachment->ticketid . '/' . $ticket_attachment->file_name;
            } else {
                $s3_key = str_replace('https://' . get_option('aws_bucket') . '.s3.amazonaws.com/', '', $attachment->external_link);
            }
        }

        // Custom function to download S3 files.
        aws_download_s3_file($s3_key);
    }
}

/**
 * Custom function to download S3 files.
 * 
 * @param string $file_url
 * @param string $filename
 * @param bool $set_mime
 * @return never
 */
function aws_download_s3_file($key, $filename = '')
{
    $CI = &get_instance();
    $CI->load->library(AWS_INTEGRATION_MODULE_NAME . '/' . 'aws_client');

    try {
        // Download the file from S3
        $result = $CI->aws_client->download($key);

        // Get the file name from S3 key if no filename provided
        if ($filename === '') {
            $filename = basename($key);
        }

        // Set headers to force download
        header('Content-Type: ' . $result['ContentType']);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $result['ContentLength']);

        // Output the file content directly
        echo $result['Body'];
    } catch (Aws\S3\Exception\S3Exception $e) {
        // Catch an S3 specific exception.
        echo "There was an error downloading the file.\n";
        echo $e->getMessage();
    }
    exit;
}

/**
 * Handle uploading files to S3
 * 
 * @param array $data
 * @return mixed
 */
function aws_upload_file_to_s3($data)
{
    $CI = &get_instance();
    $CI->load->library(AWS_INTEGRATION_MODULE_NAME . '/' . 'aws_client');
    $uploadedFiles = [];

    if (
        (isset($data['files'][$data['index_name']]['name']) && !empty($data['files'][$data['index_name']]['name'])) ||
        (isset($data['files'][$data['index_name']]) && is_array($data['files'][$data['index_name']]['name']) && count($data['files'][$data['index_name']]['name']) > 0)
    ) {
        if (!is_array($data['files'][$data['index_name']]['name'])) {
            $data['files'][$data['index_name']]['name'] = [$data['files'][$data['index_name']]['name']];
            $data['files'][$data['index_name']]['type'] = [$data['files'][$data['index_name']]['type']];
            $data['files'][$data['index_name']]['tmp_name'] = [$data['files'][$data['index_name']]['tmp_name']];
            $data['files'][$data['index_name']]['error'] = [$data['files'][$data['index_name']]['error']];
            $data['files'][$data['index_name']]['size'] = [$data['files'][$data['index_name']]['size']];
        }

        for ($i = 0; $i < count($data['files'][$data['index_name']]['name']); $i++) {
            if (isset($data['files'][$data['index_name']]) && empty($data['files'][$data['index_name']]['name'][$i])) {
                continue;
            }

            if (isset($data['files'][$data['index_name']][$i]) && _perfex_upload_error($data['files'][$data['index_name']]['error'][$i])) {
                header('HTTP/1.0 400 Bad error');
                echo _perfex_upload_error($data['files'][$data['index_name']]['error'][$i]);
                die;
            }

            if (isset($data['files'][$data['index_name']]['name'][$i]) && $data['files'][$data['index_name']]['name'][$i] != '') {
                // Get the temp file path
                $tmpFilePath = $data['files'][$data['index_name']]['tmp_name'][$i];

                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    if (!_upload_extension_allowed($data['files'][$data['index_name']]['name'][$i])) {
                        continue;
                    }

                    // Get the original file name and the file type
                    $originalName = $data['files'][$data['index_name']]['name'][$i];
                    $filetype = $data['files'][$data['index_name']]['type'][$i];

                    // Get the path
                    $path = aws_get_upload_path_by_type($data['rel_type']) . $data['rel_id'] . '/';

                    // Get the file name
                    $filename = app_generate_hash() . '.' . get_file_extension($originalName);

                    // Define S3 key
                    $s3Key = "{$path}{$filename}";

                    // Upload the file to S3
                    $fileUrl = $CI->aws_client->upload($tmpFilePath, $s3Key);

                    // Set the file name based on type
                    $file_name = '';
                    if ($data['rel_type'] === 'ticket') {
                        $file_name = $filename;
                    } else {
                        $file_name = $originalName;
                    }

                    // Add S3 file to database
                    $CI->db->insert('tbls3_files', [
                        'name' => $file_name,
                        'rel_id' => $data['rel_id'],
                        'rel_type' => $data['rel_type'],
                        'url' => $fileUrl,
                        's3_key' => $s3Key,
                    ]);

                    // Add uploaded files to array
                    $uploadedFiles[] = [
                        'number' => $i,
                        'filename' => $filename,
                        'originalName' => $originalName,
                        'filetype' => $filetype,
                        'url' => $fileUrl
                    ];
                }
            }
        }

        // Return uploadedFiles array so we can use it.
        return $uploadedFiles;
    }
}

/**
 * Function that return full path for upload based on passed type
 * 
 * @param  string $type
 * @return string
 */
function aws_get_upload_path_by_type($type)
{
    $path = match ($type) {
        'lead' => 'uploads/leads/',
        'expense' => 'uploads/expenses/',
        'project' => 'uploads/projects/',
        'proposal' => 'uploads/proposals/',
        'estimate' => 'uploads/estimates/',
        'invoice' => 'uploads/invoices/',
        'credit_note' => 'uploads/credit_notes/',
        'task' => 'uploads/tasks/',
        'contract' => 'uploads/contracts/',
        'customer' => 'uploads/clients/',
        'ticket' => 'uploads/ticket_attachments/',
        'newsfeed' => 'uploads/newsfeed/',
        'estimate_request' => 'uploads/newsfeed/',
        'discussion' => 'uploads/discussions/',
        default => 'uploads/'
    };
    return $path;
}
