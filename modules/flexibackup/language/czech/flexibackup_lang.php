<?php

# Version 1.0.0
$key = 'flexibackup';
$lang[$key]     = 'Flexi Backup';
$lang[$key.'_backup_restore']     = 'Flexi Backup and Restore';
$lang[$key.'_now']     = 'zálohovat nyní';
$lang[$key.'_perform_a_backup']     = 'Provést zálohu';
$lang[$key.'_take-a-new-backup']     = 'Provést zálohu';
$lang[$key.'_include_database_in_the_backup']     = 'Zahrnout databázi do zálohy';
$lang[$key.'_database_backup_info']     = 'Všechny vaše databázové tabulky budou zálohovány';
$lang[$key.'_include_file_in_the_backup']     = 'Zahrnout soubory do zálohy';
$lang[$key.'_files_backup_info']     = 'Vaše soubory budou zálohovány';
$lang[$key.'_existing_backups']     = 'Existující zálohy';
$lang[$key.'_settings']     = 'Nastavení';
$lang[$key.'_next_scheduled_backup']     = 'Další naplánovaná záloha';
$lang[$key.'_files_backup_schedule']     = 'Plán zálohování souborů:';
$lang[$key.'_database_backup_schedule']     = 'Plán zálohování databáze:';
$lang[$key.'_type_manual']     = 'Ruční';
$lang[$key.'_type_every_two_hours']     = 'Každé dvě hodiny';
$lang[$key.'_type_every_four_hours']     = 'Každé čtyři hodiny';
$lang[$key.'_type_every_eight_hours']     = 'Každých osm hodin';
$lang[$key.'_type_every_twelve_hours']     = 'Každých dvanáct hodin';
$lang[$key.'_type_daily']     = 'Denně';
$lang[$key.'_type_weekly']     = 'Týdně';
$lang[$key.'_type_fortnightly']     = 'Každé čtrnáct dní';
$lang[$key.'_type_monthly']     = 'Měsíčně';
$lang[$key.'_choose_your_remote_storage']     = 'Vyberte vzdálené úložiště (klepnutím na ikonu vyberte nebo zrušte výběr)';
$lang[$key.'_ftp_storage'] = 'FTP';
$lang[$key.'_s3_storage'] = 'Amazon S3';
$lang[$key.'_email'] = 'E-mail';
$lang[$key.'_email_address'] = 'E-mailová adresa';
$lang[$key.'_email_note'] = 'Mějte prosím na paměti, že poštovní servery mají obvykle omezení velikosti; obvykle kolem 10-20 MB; zálohy větší než jakákoliv omezení pravděpodobně nepřijdou.';
$lang[$key.'_sftp_storage'] = 'SFTP/SCP';
$lang[$key.'_include_in_files_backup'] = 'Místní';
$lang[$key.'_include_in_files_backup'] = 'Zahrnout do zálohy souborů:';
$lang[$key.'_modules'] = 'Modules';
$lang[$key.'_application'] = 'Application';
$lang[$key.'_uploads'] = 'Uploads';
$lang[$key.'_assets'] = 'Assets';
$lang[$key.'_system'] = 'System';
$lang[$key.'_resources'] = 'Resources';
$lang[$key.'_media'] = 'Media';
$lang[$key.'-save-changes'] = 'Uložit změny';
$lang[$key.'_auto_backup_options_updated'] = 'Nastavení úspěšně uloženo';
$lang[$key.'_ftp_server'] = 'FTP Server';
$lang[$key.'_ftp_user'] = 'FTP User/Login';
$lang[$key.'_ftp_password'] = 'FTP Password';
$lang[$key.'_ftp_path'] = 'FTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_description'] = 'Získejte přístupový klíč a tajný klíč z konzoly AWS, poté vyberte (globálně jedinečný - všichni uživatelé Amazon S3)';
$lang[$key.'_sftp_server'] = 'SFTP/SCP Server';
$lang[$key.'_sftp_user'] = 'SFTP User';
$lang[$key.'_sftp_password'] = 'SFTP Password';
$lang[$key.'_sftp_path'] = 'SFTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_access_key'] = 'Amazon S3 Access Key';
$lang[$key.'_s3_secret_key'] = 'Amazon S3 Secret Key';
$lang[$key.'_s3_location'] = 'Amazon S3 Bucket Name';
$lang[$key.'_s3_region'] = 'Amazon S3 Region e.g us-east-1';
$lang[$key.'_back_up_now_note'] = "Your saved settings affect what is Backed up, you can update them <a href='".admin_url('flexibackup/settings')."'><span class='bold'> here </span></a>";
$lang[$key.'_time_now'] = "Čas nyní";
$lang[$key.'_nothing_currently_scheduled'] = "V současné době není naplánováno nic";
$lang[$key.'_files'] = "Files";
$lang[$key.'_database'] = "Database";
$lang[$key.'_successful'] = "Záloha úspěšná";
$lang[$key.'_unsuccessful'] = "Záloha neúspěšná";
$lang[$key.'_backup_name_prefix'] = "Prefix názvu zálohy";
$lang[$key.'_include_others'] = "Others (Root files such as index.php, .htaccess, robots.txt, package.xml e.t.c)";
$lang[$key.'_date'] = "Datum zálohy";
$lang[$key.'_backup_data_click'] = "Zálohovat data (kliknutím stáhnout) ";
$lang[$key.'_log_file'] = "Log soubor";
$lang[$key.'_download_log_file'] = "Stáhnout log soubor";
$lang[$key.'_download_to_your_computer'] = "Stáhněte si do počítače";
$lang[$key.'_delete_from_your_webserver'] = "Odstranit ze svého webového serveru";
$lang[$key.'_browse_contents'] = "Procházet obsah";
$lang[$key.'_file_ready_actions'] = "Akce souboru připraveny";
$lang[$key.'_webdav_storage'] = "WebDAV";
$lang[$key.'_webdav_password'] = "WebDAV Password";
$lang[$key.'_webdav_username'] = "WebDAV Username";
$lang[$key.'_webdav_base_uri'] = "WebDAV Base URI";
$lang[$key.'_upload_to_remote'] = "Upload to remote";
$lang[$key.'_uploaded_to_remote_storage'] = "Nahráno do vzdáleného úložiště";
$lang[$key.'_restore'] = "Obnovit";
$lang[$key.'_restore_files_from'] = "Obnovit soubory z";
$lang[$key.'_restore_warning'] = "Restoring will replace this site's Application, Modules, Uploads, Resources, System, Media, Assets, Database and/or Others content directories (according to what is contained in the backup set, and your selection).";
$lang[$key.'_view_log'] = "Zobrazit log";
$lang[$key.'_choose_componet_to_restore'] = "Vyberte alespoň jeden typ souboru k obnovení.";
$lang[$key.'_restore_db_warning'] = "<b>Backup the Current Database (Optional, but recommended): </b> Before proceeding with the database restore, it's a good practice to backup your current database in case you need to revert any changes.";
$lang[$key.'_log_file_not_found'] = "Log soubor nenalezen";
$lang[$key.'_could_not_donwload_file'] = "Soubor nelze stáhnout";
$lang[$key.'_file_removed_successfully'] = "Soubor úspěšně odstraněn";
$lang[$key.'_could_not_remove_backup'] = "Zálohu nelze odstranit.";
$lang[$key.'_files_uploaded_to'] = "Soubory nahrány do";
$lang[$key.'_successfully'] = "úspěšně";
$lang[$key.'_could_not_complete_remote_backup'] = "Nelze dokončit vzdálenou zálohu této zálohy. ";
$lang[$key.'_no_remote_storage_selected'] = "Vyberte možnost vzdáleného úložiště v nastavení. ";
$lang[$key.'_backup_restored_successfully'] = "Záloha byla úspěšně obnovena. ";
$lang[$key.'_could_not_restore_backup'] = "Zálohu nelze obnovit. ";
$lang[$key.'_at_least_one_file_type_to_restore'] = "Vyberte komponenty k obnovení:";
$lang[$key.'_auto_backup_to_remote_enabled'] = "Automatická záloha do vzdáleného úložiště povolena";
$lang[$key.'_choose_the_time_of_your_scheduled_backup'] = "Vyberte čas naplánované zálohy";







