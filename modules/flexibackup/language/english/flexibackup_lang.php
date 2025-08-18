<?php

# Version 1.0.0
$key = 'flexibackup';
$lang[$key]     = 'Flexi Backup';
$lang[$key.'_backup_restore']     = 'Flexi Backup and Restore';
$lang[$key.'_now']     = 'Backup Now';
$lang[$key.'_perform_a_backup']     = 'Perform a Backup';
$lang[$key.'_take-a-new-backup']     = 'Perform a Backup';
$lang[$key.'_include_database_in_the_backup']     = 'Include your database in the backup';
$lang[$key.'_database_backup_info']     = 'All your database tables will be backed up';
$lang[$key.'_include_file_in_the_backup']     = 'Include your files in the backup';
$lang[$key.'_files_backup_info']     = 'Your files will be backed up';
$lang[$key.'_existing_backups']     = 'Existing Backups';
$lang[$key.'_settings']     = 'Settings';
$lang[$key.'_next_scheduled_backup']     = 'Next Scheduled Backup';
$lang[$key.'_files_backup_schedule']     = 'Files backup schedule:';
$lang[$key.'_database_backup_schedule']     = 'Database backup schedule:';
$lang[$key.'_type_manual']     = 'Manual';
$lang[$key.'_type_every_two_hours']     = 'Every two hours';
$lang[$key.'_type_every_four_hours']     = 'Every four hours';
$lang[$key.'_type_every_eight_hours']     = 'Every eight hours';
$lang[$key.'_type_every_twelve_hours']     = 'Every twelve hours';
$lang[$key.'_type_daily']     = 'Daily';
$lang[$key.'_type_weekly']     = 'Weekly';
$lang[$key.'_type_fortnightly']     = 'Fortnightly';
$lang[$key.'_type_monthly']     = 'Monthly';
$lang[$key.'_choose_your_remote_storage']     = 'Choose your remote storage (tap on an icon to select or unselect)';
$lang[$key.'_ftp_storage'] = 'FTP';
$lang[$key.'_s3_storage'] = 'Amazon S3';
$lang[$key.'_email'] = 'Email';
$lang[$key.'_email_address'] = 'Email Address';
$lang[$key.'_email_note'] = 'Please Be aware that mail servers tend to have size limits; typically around 10-20 MB; backups larger than any limits will likely not arrive.';
$lang[$key.'_sftp_storage'] = 'SFTP/SCP';
$lang[$key.'_include_in_files_backup'] = 'Local';
$lang[$key.'_include_in_files_backup'] = 'Include in files backup:';
$lang[$key.'_modules'] = 'Modules';
$lang[$key.'_application'] = 'Application';
$lang[$key.'_uploads'] = 'Uploads';
$lang[$key.'_assets'] = 'Assets';
$lang[$key.'_system'] = 'System';
$lang[$key.'_resources'] = 'Resources';
$lang[$key.'_media'] = 'Media';
$lang[$key.'-save-changes'] = 'Save Changes';
$lang[$key.'_auto_backup_options_updated'] = 'Settings saved successfully';
$lang[$key.'_ftp_server'] = 'FTP Server';
$lang[$key.'_ftp_user'] = 'FTP User/Login';
$lang[$key.'_ftp_password'] = 'FTP Password';
$lang[$key.'_ftp_path'] = 'FTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_description'] = 'Get your access key and secret key from your AWS console, then pick a (globally unique - all Amazon S3 users) 
bucket name (letters and numbers) (and optionally a path) to use for storage.';
$lang[$key.'_sftp_server'] = 'SFTP/SCP Server';
$lang[$key.'_sftp_user'] = 'SFTP User';
$lang[$key.'_sftp_password'] = 'SFTP Password';
$lang[$key.'_sftp_path'] = 'SFTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_access_key'] = 'Amazon S3 Access Key';
$lang[$key.'_s3_secret_key'] = 'Amazon S3 Secret Key';
$lang[$key.'_s3_location'] = 'Amazon S3 Bucket Name';
$lang[$key.'_s3_region'] = 'Amazon S3 Region e.g us-east-1';
$lang[$key.'_back_up_now_note'] = "Your saved settings affect what is Backed up, you can update them <a href='".admin_url('flexibackup/settings')."'><span class='bold'> here </span></a>";
$lang[$key.'_time_now'] = 'Time Now';
$lang[$key.'_nothing_currently_scheduled'] = 'Nothing currently scheduled';
$lang[$key.'_files'] = "Files";
$lang[$key.'_database'] = "Database";
$lang[$key.'_successful'] = "Backup Successful";
$lang[$key.'_unsuccessful'] = "Backup Failed, please check your settings and try again";
$lang[$key.'_backup_name_prefix'] = "Backup FileName Prefix";
$lang[$key.'_include_others'] = "Others (Root files such as index.php, .htaccess, robots.txt, package.xml e.t.c)";
$lang[$key.'_date'] = "Backup Date";
$lang[$key.'_backup_data_click'] = "Backup Data (click to download) ";
$lang[$key.'_log_file'] = "Log File";
$lang[$key.'_download_log_file'] = "Download Log File";
$lang[$key.'_download_to_your_computer'] = "Download to your computer";
$lang[$key.'_delete_from_your_webserver'] = "Delete from your web server ";
$lang[$key.'_browse_contents'] = "Browse Contents";
$lang[$key.'_file_ready_actions'] = "File Ready Actions";
$lang[$key.'_webdav_storage'] = "WebDAV";
$lang[$key.'_webdav_password'] = "WebDAV Password";
$lang[$key.'_webdav_username'] = "WebDAV Username";
$lang[$key.'_webdav_base_uri'] = "WebDAV Base URI";
$lang[$key.'_upload_to_remote'] = "Upload to remote";
$lang[$key.'_uploaded_to_remote_storage'] = "Uploaded to remote storage";
$lang[$key.'_restore'] = "Restore";
$lang[$key.'_restore_files_from'] = "Restoration - Restore files from ";
$lang[$key.'_restore_warning'] = "Restoring will replace this site's Application, Modules, Uploads, Resources, System, Media, Assets, Database and/or Others content directories (according to what is contained in the backup set, and your selection).";
$lang[$key.'_view_log'] = "View Log";
$lang[$key.'_choose_componet_to_restore'] = "Choose the components to restore:";
$lang[$key.'_restore_db_warning'] = "<b>Backup the Current Database (Optional, but recommended): </b> Before proceeding with the database restore, it's a good practice to backup your current database in case you need to revert any changes.";
$lang[$key.'_log_file_not_found'] = "No log file found";
$lang[$key.'_could_not_donwload_file'] = "Could not download file";
$lang[$key.'_file_removed_successfully'] = "File removed successfully";
$lang[$key.'_could_not_remove_backup'] = "Could not remove backup.";
$lang[$key.'_files_uploaded_to'] = "Files uploaded to ";
$lang[$key.'_successfully'] = " successfully";
$lang[$key.'_could_not_complete_remote_backup'] = "Could not complete remote backup of this backup. ";
$lang[$key.'_no_remote_storage_selected'] = "Please select a remote storage option in settings. ";
$lang[$key.'_backup_restored_successfully'] = "Backup restored successfully ";
$lang[$key.'_could_not_restore_backup'] = "Could not restore backup.  ";
$lang[$key.'_at_least_one_file_type_to_restore'] = "Please select at least one file type to restore.";
$lang[$key.'_auto_backup_to_remote_enabled'] = "Automatically upload Scheduled Backup to remote storage";
$lang[$key.'_choose_the_time_of_your_scheduled_backup'] = "Choose the time for scheduled backup";







