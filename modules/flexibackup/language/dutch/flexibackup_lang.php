<?php

# Version 1.0.0
$key = 'flexibackup';
$lang[$key]     = 'Flexi Backup';
$lang[$key.'_backup_restore']     = 'Flexi Backup and Restore';
$lang[$key.'_now']     = "Nu backuppen";
$lang[$key.'_perform_a_backup']     = "Voer een back-up uit";
$lang[$key.'_take-a-new-backup']    = 'Voer een back-up uit';
$lang[$key.'_include_database_in_the_backup']     = 'Neem uw database op in de back-up';
$lang[$key.'_database_backup_info']     = 'Al uw databasetabellen worden geback-upt';
$lang[$key.'_include_file_in_the_backup']     = 'Neem uw bestanden op in de back-up';
$lang[$key.'_files_backup_info']     = 'Uw bestanden worden geback-upt';
$lang[$key.'_existing_backups']     = 'Bestaande back-ups';
$lang[$key.'_settings']     = 'Instellingen';
$lang[$key.'_next_scheduled_backup']     = 'Volgende geplande back-up';
$lang[$key.'_files_backup_schedule']     = 'Bestanden back-up schema:';
$lang[$key.'_database_backup_schedule']     = 'Database back-up schema:';
$lang[$key.'_type_manual']     = 'Handmatig';
$lang[$key.'_type_every_two_hours']     = 'Elke twee uur';
$lang[$key.'_type_every_four_hours']     = 'Elke vier uur';
$lang[$key.'_type_every_eight_hours']     = 'Elke acht uur';
$lang[$key.'_type_every_twelve_hours']     = 'Elke twaalf uur';
$lang[$key.'_type_daily']     = 'Dagelijks';
$lang[$key.'_type_weekly']     = 'Wekelijks';
$lang[$key.'_type_fortnightly']     = 'Tweewekelijks';
$lang[$key.'_type_monthly']     = 'Maandelijks';
$lang[$key.'_choose_your_remote_storage']     = 'Kies uw externe opslag (tik op een pictogram om te selecteren of deselecteren)';
$lang[$key.'_ftp_storage'] = 'FTP';
$lang[$key.'_s3_storage'] = 'Amazon S3';
$lang[$key.'_email'] = 'E-mail';
$lang[$key.'_email_address'] = 'E-mailadres';
$lang[$key.'_email_note'] = 'Houd er rekening mee dat mailservers de neiging hebben om limieten te hebben; typisch rond 10-20 MB; back-ups groter dan enige limieten zullen waarschijnlijk niet aankomen.';
$lang[$key.'_sftp_storage'] = 'SFTP/SCP';
$lang[$key.'_include_in_files_backup'] = 'Lokaal';
$lang[$key.'_include_in_files_backup'] = 'Opnemen in bestanden back-up:';
$lang[$key.'_modules'] = 'Modules';
$lang[$key.'_application'] = 'Application';
$lang[$key.'_uploads'] = 'Uploads';
$lang[$key.'_assets'] = 'Assets';
$lang[$key.'_system'] = 'System';
$lang[$key.'_resources'] = 'Resources';
$lang[$key.'_media'] = 'Media';
$lang[$key.'-save-changes'] = 'Wijzigingen opslaan';
$lang[$key.'_auto_backup_options_updated'] = 'Instellingen succesvol opgeslagen';
$lang[$key.'_ftp_server'] = 'FTP Server';
$lang[$key.'_ftp_user'] = 'FTP User/Login';
$lang[$key.'_ftp_password'] = 'FTP Password';
$lang[$key.'_ftp_path'] = 'FTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_description'] = 'Haal uw toegangssleutel en geheime sleutel op van uw AWS-console en kies vervolgens een (wereldwijd uniek - alle Amazon S3-gebruikers)
bucketnaam (letters en cijfers) (en optioneel een pad) om te gebruiken voor opslag.';
$lang[$key.'_sftp_server'] = 'SFTP/SCP Server';
$lang[$key.'_sftp_user'] = 'SFTP User';
$lang[$key.'_sftp_password'] = 'SFTP Password';
$lang[$key.'_sftp_path'] = 'SFTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_access_key'] = 'Amazon S3 Access Key';
$lang[$key.'_s3_secret_key'] = 'Amazon S3 Secret Key';
$lang[$key.'_s3_location'] = 'Amazon S3 Bucket Name';
$lang[$key.'_s3_region'] = 'Amazon S3 Region e.g us-east-1';
$lang[$key.'_back_up_now_note'] = "Uw opgeslagen instellingen zijn van invloed op waar een back-up van wordt gemaakt. U kunt deze bijwerken <a href='".admin_url('flexibackup/settings')."'><span class='bold'> hier </span></a>";
$lang[$key.'_time_now'] = 'Tijd nu';
$lang[$key.'_nothing_currently_scheduled'] = 'Momenteel niets gepland';
$lang[$key.'_files'] = "Bestanden";
$lang[$key.'_database'] = "Database";
$lang[$key.'_successful'] = "Back-up succesvol";
$lang[$key.'_unsuccessful'] = "Back-up mislukt, controleer uw instellingen en probeer het opnieuw";
$lang[$key.'_backup_name_prefix'] = "Back-up bestandsnaam voorvoegsel";
$lang[$key.'_include_others'] = "Others (Root files such as index.php, .htaccess, robots.txt, package.xml e.t.c)";
$lang[$key.'_date'] = "Back-up datum";
$lang[$key.'_backup_data_click'] = "Back-up gegevens (klik om te downloaden) ";
$lang[$key.'_log_file'] = "Logbestand";
$lang[$key.'_download_log_file'] = "Download logbestand";
$lang[$key.'_download_to_your_computer'] = "Download naar uw computer";
$lang[$key.'_delete_from_your_webserver'] = "Verwijder van uw webserver ";
$lang[$key.'_browse_contents'] = "Blader door inhoud";
$lang[$key.'_file_ready_actions'] = "Bestand gereed acties";
$lang[$key.'_webdav_storage'] = "WebDAV";
$lang[$key.'_webdav_password'] = "WebDAV Password";
$lang[$key.'_webdav_username'] = "WebDAV Username";
$lang[$key.'_webdav_base_uri'] = "WebDAV Base URI";
$lang[$key.'_upload_to_remote'] = "Upload to remote";
$lang[$key.'_uploaded_to_remote_storage'] = "Geüpload naar externe opslag";
$lang[$key.'_restore'] = "Herstellen";
$lang[$key.'_restore_files_from'] = "Herstel bestanden van ";
$lang[$key.'_restore_warning'] = "Restoring will replace this site's Application, Modules, Uploads, Resources, System, Media, Assets, Database and/or Others content directories (according to what is contained in the backup set, and your selection).";
$lang[$key.'_view_log'] = "Log bekijken";
$lang[$key.'_choose_componet_to_restore'] = "Kies de componenten om te herstellen:";
$lang[$key.'_restore_db_warning'] = "<b>Backup the Current Database (Optional, but recommended): </b> Before proceeding with the database restore, it's a good practice to backup your current database in case you need to revert any changes.";
$lang[$key.'_log_file_not_found'] = "Geen logbestand gevonden";
$lang[$key.'_could_not_donwload_file'] = "Kan bestand niet downloaden";
$lang[$key.'_file_removed_successfully'] = "Bestand succesvol verwijderd";
$lang[$key.'_could_not_remove_backup'] = "Kan back-up niet verwijderen.";
$lang[$key.'_files_uploaded_to'] = "Bestanden geüpload naar ";
$lang[$key.'_successfully'] = " met succes";
$lang[$key.'_could_not_complete_remote_backup'] = "Kon externe back-up van deze back-up niet voltooien. ";
$lang[$key.'_no_remote_storage_selected'] = "Geen externe opslag geselecteerd";
$lang[$key.'_backup_restored_successfully'] = "Back-up succesvol hersteld ";
$lang[$key.'_could_not_restore_backup'] = "Kan back-up niet herstellen.  ";
$lang[$key.'_at_least_one_file_type_to_restore'] = "Kies de componenten om te herstellen:";
$lang[$key.'_auto_backup_to_remote_enabled'] = "Automatisch geplande back-up uploaden naar externe opslag";
$lang[$key.'_choose_the_time_of_your_scheduled_backup'] = "Kies de tijd voor geplande back-up";







