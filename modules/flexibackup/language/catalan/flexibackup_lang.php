<?php

# Version 1.0.0
$key = 'flexibackup';
$lang[$key]     = 'Flexi Backup';
$lang[$key.'_backup_restore']     = 'Flexi Backup and Restore';
$lang[$key.'_now']     = 'Còpia de seguretat ara';
$lang[$key.'_perform_a_backup']     = 'Realitzeu una còpia de seguretat';
$lang[$key.'_take-a-new-backup']     = 'Realitzeu una còpia de seguretat';
$lang[$key.'_include_database_in_the_backup']     = 'Incloeu la vostra base de dades a la còpia de seguretat';
$lang[$key.'_database_backup_info']     = 'Es farà una còpia de seguretat de totes les taules de la base de dades';
$lang[$key.'_include_file_in_the_backup']     = 'Incloeu els vostres fitxers a la còpia de seguretat';
$lang[$key.'_files_backup_info']     = 'Es farà una còpia de seguretat dels vostres fitxers';
$lang[$key.'_existing_backups']     = 'Còpies de seguretat existents';
$lang[$key.'_settings']     = 'Configuració';
$lang[$key.'_next_scheduled_backup']     = 'Següent còpia de seguretat programada';
$lang[$key.'_files_backup_schedule']     = 'Programació de còpies de seguretat dels fitxers:';
$lang[$key.'_database_backup_schedule']     = 'Programació de còpies de seguretat de la base de dades:';
$lang[$key.'_type_manual']     = 'Manual';
$lang[$key.'_type_every_two_hours']     = 'Cada dues hores';
$lang[$key.'_type_every_four_hours']     = 'Cada quatre hores';
$lang[$key.'_type_every_eight_hours']     = 'Cada vuit hores';
$lang[$key.'_type_every_twelve_hours']     = 'Cada dotze hores';
$lang[$key.'_type_daily']     = 'Diàriament';
$lang[$key.'_type_weekly']     = 'Setmanalment';
$lang[$key.'_type_fortnightly']     = 'Quinzenalment';
$lang[$key.'_type_monthly']     = 'Mensual';
$lang[$key.'_choose_your_remote_storage']     = 'Trieu el vostre emmagatzematge remot (toqueu una icona per seleccionar o desmarcar)';
$lang[$key.'_ftp_storage'] = 'FTP';
$lang[$key.'_s3_storage'] = 'Amazon S3';
$lang[$key.'_email'] = 'Email';
$lang[$key.'_email_address'] = 'Email Address';
$lang[$key.'_email_note'] = 'Tingueu en compte que els servidors de correu solen tenir límits de mida; normalment uns 10-20 MB; És probable que no arribin còpies de seguretat més grans que qualsevol límit.';
$lang[$key.'_sftp_storage'] = 'SFTP/SCP';
$lang[$key.'_include_in_files_backup'] = 'Local';
$lang[$key.'_include_in_files_backup'] = 'Inclou a la còpia de seguretat dels fitxers:';
$lang[$key.'_modules'] = 'Modules';
$lang[$key.'_application'] = 'Application';
$lang[$key.'_uploads'] = 'Uploads';
$lang[$key.'_assets'] = 'Assets';
$lang[$key.'_system'] = 'System';
$lang[$key.'_resources'] = 'Resources';
$lang[$key.'_media'] = 'Media';
$lang[$key.'-save-changes'] = 'Guardar canvis';
$lang[$key.'_auto_backup_options_updated'] = "La configuració s'ha desat correctament";
$lang[$key.'_ftp_server'] = 'FTP Server';
$lang[$key.'_ftp_user'] = 'FTP User/Login';
$lang[$key.'_ftp_password'] = 'FTP Password';
$lang[$key.'_ftp_path'] = 'FTP Path (Necessita que existeixi i es pugui escriure)';
$lang[$key.'_s3_description'] = "Obteniu la vostra clau d'accés i clau secreta de la vostra consola AWS i, a continuació, trieu una (única a nivell mundial: tots els usuaris d'Amazon S3)
nom del dipòsit (lletres i números) (i, opcionalment, un camí) per utilitzar-lo per a l'emmagatzematge.";
$lang[$key.'_sftp_server'] = 'SFTP/SCP Server';
$lang[$key.'_sftp_user'] = 'SFTP User';
$lang[$key.'_sftp_password'] = 'SFTP Password';
$lang[$key.'_sftp_path'] = 'SFTP Path (Necessita que existeixi i es pugui escriure)';
$lang[$key.'_s3_access_key'] = 'Amazon S3 Access Key';
$lang[$key.'_s3_secret_key'] = 'Amazon S3 Secret Key';
$lang[$key.'_s3_location'] = 'Amazon S3 Bucket Name';
$lang[$key.'_s3_region'] = 'Amazon S3 Region e.g us-east-1';
$lang[$key.'_back_up_now_note'] = "La vostra configuració desada afecta el que es fa una còpia de seguretat, podeu actualitzar-los <a href='".admin_url('flexibackup/settings')."'><span class='bold'> aquí </span></a>";
$lang[$key.'_time_now'] = 'Temps Ara';
$lang[$key.'_nothing_currently_scheduled'] = 'No hi ha res programat actualment';
$lang[$key.'_files'] = "Files";
$lang[$key.'_database'] = "Database";
$lang[$key.'_successful'] = "Còpia de seguretat correcta";
$lang[$key.'_unsuccessful'] = "La còpia de seguretat ha fallat, comproveu la vostra configuració i torneu-ho a provar";
$lang[$key.'_backup_name_prefix'] = "Prefix del nom del fitxer de còpia de seguretat";
$lang[$key.'_include_others'] = "Altres (fitxers root com ara index.php, .htaccess, robots.txt, package.xml, etc.)";
$lang[$key.'_date'] = "Data de còpia de seguretat";
$lang[$key.'_backup_data_click'] = "Còpia de seguretat de dades (feu clic per descarregar)";
$lang[$key.'_log_file'] = "Fitxer de registre";
$lang[$key.'_download_log_file'] = "Descarrega el fitxer de registre";
$lang[$key.'_download_to_your_computer'] = "Descarrega al teu ordinador";
$lang[$key.'_delete_from_your_webserver'] = "Suprimeix del teu servidor web ";
$lang[$key.'_browse_contents'] = "Examinar continguts";
$lang[$key.'_file_ready_actions'] = "Accions a punt per a fitxers";
$lang[$key.'_webdav_storage'] = "WebDAV";
$lang[$key.'_webdav_password'] = "WebDAV Password";
$lang[$key.'_webdav_username'] = "WebDAV Username";
$lang[$key.'_webdav_base_uri'] = "WebDAV Base URI";
$lang[$key.'_upload_to_remote'] = "Carrega al control remot";
$lang[$key.'_uploaded_to_remote_storage'] = "S'ha penjat a l'emmagatzematge remot";
$lang[$key.'_restore'] = "Restaurar";
$lang[$key.'_restore_files_from'] = "Restauració: restaura fitxers des de";
$lang[$key.'_restore_warning'] = "La restauració substituirà el d'aquest lloc Application, Modules, Uploads, Resources, System, Media, Assets, Database and/or Others content directories (segons el que conté el conjunt de còpies de seguretat i la vostra selecció).";
$lang[$key.'_view_log'] = "Veure el registre";
$lang[$key.'_choose_componet_to_restore'] = "Trieu els components a restaurar:";
$lang[$key.'_restore_db_warning'] = "<b>Feu una còpia de seguretat de la base de dades actual (opcional, però recomanable): </b> Abans de continuar amb la restauració de la base de dades, és una bona pràctica fer una còpia de seguretat de la base de dades actual per si necessiteu revertir els canvis.";
$lang[$key.'_log_file_not_found'] = "No s'ha trobat cap fitxer de registre";
$lang[$key.'_could_not_donwload_file'] = "No s'ha pogut descarregar el fitxer";
$lang[$key.'_file_removed_successfully'] = "File removed successfully";
$lang[$key.'_could_not_remove_backup'] = "No s'ha pogut eliminar la còpia de seguretat.";
$lang[$key.'_files_uploaded_to'] = "Fitxers penjats a ";
$lang[$key.'_successfully'] = " amb èxit";
$lang[$key.'_could_not_complete_remote_backup'] = "No s'ha pogut completar la còpia de seguretat remota d'aquesta còpia de seguretat.";
$lang[$key.'_no_remote_storage_selected'] = "Seleccioneu una opció d'emmagatzematge remot a la configuració. ";
$lang[$key.'_backup_restored_successfully'] = "La còpia de seguretat s'ha restaurat correctament";
$lang[$key.'_could_not_restore_backup'] = "No s'ha pogut restaurar la còpia de seguretat. ";
$lang[$key.'_at_least_one_file_type_to_restore'] = "Seleccioneu almenys un tipus de fitxer per restaurar.";
$lang[$key.'_auto_backup_to_remote_enabled'] = "Carrega automàticament la còpia de seguretat programada a l'emmagatzematge remot";
$lang[$key.'_choose_the_time_of_your_scheduled_backup'] = "Trieu l'hora de la còpia de seguretat programada";







