<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Aws_integration extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * AWS S3 Settings.
     * 
     * @return void
     */
    public function index()
    {
        // If the user not an admin deny the access
        if (!is_admin()) {
            access_denied('aws_integration');
        }

        // If we click submit button
        if ($this->input->post()) {
            if (!is_admin()) {
                access_denied('aws_integration');
            }
            update_option('enable_aws_integration', $this->input->post('settings[enable_aws_integration]'));
            update_option('aws_access_key_id', $this->input->post('settings[aws_access_key_id]'));
            update_option('aws_secret_access_key', $this->input->post('settings[aws_secret_access_key]'));
            update_option('aws_region', $this->input->post('settings[aws_region]'));
            update_option('aws_bucket', $this->input->post('settings[aws_bucket]'));

            set_alert('success', _l('updated_successfully', _l('aws_integration')));
            redirect(admin_url('aws_integration'));
        }

        // Set the page title
        $data['title'] = _l('aws_integration');

        $this->load->view('settings', $data);
    }
}