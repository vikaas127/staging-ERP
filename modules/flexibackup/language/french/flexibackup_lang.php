<?php

# Version 1.0.0
$key = 'flexibackup';
$lang[$key]     = 'Flexi Backup';
$lang[$key.'_backup_restore']     = 'Flexi Backup and Restore';
$lang[$key.'_now']     = 'sauvegarder maintenant';
$lang[$key.'_perform_a_backup']     = 'Effectuer une sauvegarde';
$lang[$key.'_take-a-new-backup']     = 'Prendre une nouvelle sauvegarde';
$lang[$key.'_include_database_in_the_backup']     = 'Inclure votre base de données dans la sauvegarde';
$lang[$key.'_database_backup_info']     = 'Votre base de données sera sauvegardée';
$lang[$key.'_include_file_in_the_backup']     = 'Inclure vos fichiers dans la sauvegarde';
$lang[$key.'_files_backup_info']     = 'Vos fichiers seront sauvegardés';
$lang[$key.'_existing_backups']     = 'Sauvegardes existantes';
$lang[$key.'_settings']     = 'Paramètres';
$lang[$key.'_next_scheduled_backup']     = 'Prochaine sauvegarde planifiée';
$lang[$key.'_files_backup_schedule']     = 'Planification de la sauvegarde des fichiers';
$lang[$key.'_database_backup_schedule']     = 'Planification de la sauvegarde de la base de données';
$lang[$key.'_type_manual']     = 'Manuel';
$lang[$key.'_type_every_two_hours']     = 'Toutes les deux heures';
$lang[$key.'_type_every_four_hours']     = 'Toutes les quatre heures';
$lang[$key.'_type_every_eight_hours']     = 'Toutes les huit heures';
$lang[$key.'_type_every_twelve_hours']     = 'Toutes les douze heures';
$lang[$key.'_type_daily']     = 'Quotidien';
$lang[$key.'_type_weekly']     = 'Hebdomadaire';
$lang[$key.'_type_fortnightly']     = 'Tous les quinze jours';
$lang[$key.'_type_monthly']     = 'Mensuel';
$lang[$key.'_choose_your_remote_storage']     = 'Choisissez votre stockage distant (appuyez sur une icône pour sélectionner ou désélectionner)';
$lang[$key.'_ftp_storage'] = 'FTP';
$lang[$key.'_s3_storage'] = 'Amazon S3';
$lang[$key.'_email'] = 'Email';
$lang[$key.'_email_address'] = 'Adresse e-mail';
$lang[$key.'_email_note'] = "Veuillez noter que les serveurs de messagerie ont tendance à avoir des limites de taille; généralement environ 10 à 20 Mo; les sauvegardes supérieures à toutes les limites n'arriveront probablement pas.";
$lang[$key.'_sftp_storage'] = 'SFTP/SCP';
$lang[$key.'_include_in_files_backup'] = 'Locale';
$lang[$key.'_include_in_files_backup'] = 'Inclure dans la sauvegarde des fichiers';
$lang[$key.'_modules'] = 'Modules';
$lang[$key.'_application'] = 'Application';
$lang[$key.'_uploads'] = 'Uploads';
$lang[$key.'_assets'] = 'Assets';
$lang[$key.'_system'] = 'System';
$lang[$key.'_resources'] = 'Resources';
$lang[$key.'_media'] = 'Media';
$lang[$key.'-save-changes'] = 'Enregistrer les modifications';
$lang[$key.'_auto_backup_options_updated'] = 'Paramètres enregistrés avec succès';
$lang[$key.'_ftp_server'] = 'FTP Server';
$lang[$key.'_ftp_user'] = 'FTP User/Login';
$lang[$key.'_ftp_password'] = 'FTP Password';
$lang[$key.'_ftp_path'] = 'FTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_description'] = "Obtenez votre clé d'accès et votre clé secrète depuis votre console AWS, puis choisissez-en une (unique au monde - tous les utilisateurs d'Amazon S3)
nom du compartiment (lettres et chiffres) (et éventuellement un chemin) à utiliser pour le stockage.";
$lang[$key.'_sftp_server'] = 'SFTP/SCP Server';
$lang[$key.'_sftp_user'] = 'SFTP User';
$lang[$key.'_sftp_password'] = 'SFTP Password';
$lang[$key.'_sftp_path'] = 'SFTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_access_key'] = 'Amazon S3 Access Key';
$lang[$key.'_s3_secret_key'] = 'Amazon S3 Secret Key';
$lang[$key.'_s3_location'] = 'Amazon S3 Bucket Name';
$lang[$key.'_s3_region'] = 'Amazon S3 Region e.g us-east-1';
$lang[$key.'_back_up_now_note'] = "Vos paramètres enregistrés affectent ce qui est sauvegardé, vous pouvez les mettre à jour <a href='".admin_url('flexibackup/settings')."'><span class='bold'> ici </span></a>";
$lang[$key.'_time_now'] = "C'est l'heure";
$lang[$key.'_nothing_currently_scheduled'] = "Rien n'est actuellement prévu";
$lang[$key.'_files'] = "Fichiers";
$lang[$key.'_database'] = "Base de données";
$lang[$key.'_successful'] = "Sauvegarde réussie";
$lang[$key.'_unsuccessful'] = "Échec de la sauvegarde, veuillez vérifier vos paramètres et réessayer";
$lang[$key.'_backup_name_prefix'] = "Préfixe du nom de sauvegarde";
$lang[$key.'_include_others'] = "Others (Root files such as index.php, .htaccess, robots.txt, package.xml e.t.c)";
$lang[$key.'_date'] = "Date de sauvegarde";
$lang[$key.'_backup_data_click'] = "Sauvegarde des données (cliquez pour télécharger)";
$lang[$key.'_log_file'] = "Fichier journal";
$lang[$key.'_download_log_file'] = "Télécharger le fichier journal";
$lang[$key.'_download_to_your_computer'] = "Télécharger sur votre ordinateur";
$lang[$key.'_delete_from_your_webserver'] = "Supprimer de votre serveur Web";
$lang[$key.'_browse_contents'] = "Parcourir le contenu";
$lang[$key.'_file_ready_actions'] = "Fichier prêt, actions";
$lang[$key.'_webdav_storage'] = "WebDAV";
$lang[$key.'_webdav_password'] = "WebDAV Password";
$lang[$key.'_webdav_username'] = "WebDAV Username";
$lang[$key.'_webdav_base_uri'] = "WebDAV Base URI";
$lang[$key.'_upload_to_remote'] = "Upload to remote";
$lang[$key.'_uploaded_to_remote_storage'] = "Téléchargé sur le stockage distant";
$lang[$key.'_restore'] = "Restaurer";
$lang[$key.'_restore_files_from'] = "Restauration - Restaurer des fichiers à partir de";
$lang[$key.'_restore_warning'] = "Restoring will replace this site's Application, Modules, Uploads, Resources, System, Media, Assets, Database and/or Others content directories (according to what is contained in the backup set, and your selection).";
$lang[$key.'_view_log'] = "Voir le journal";
$lang[$key.'_choose_componet_to_restore'] = "Choisissez au moins un type de fichier à restaurer";
$lang[$key.'_restore_db_warning'] = "<b>Sauvegarder la base de données actuelle (facultatif, mais recommandé) : </b> Avant de procéder à la restauration de la base de données, il est recommandé de sauvegarder votre base de données actuelle au cas où vous auriez besoin d'annuler des modifications.";
$lang[$key.'_log_file_not_found'] = "Fichier journal introuvable";
$lang[$key.'_could_not_donwload_file'] = "Impossible de télécharger le fichier";
$lang[$key.'_file_removed_successfully'] = "Fichier supprimé avec succès";
$lang[$key.'_could_not_remove_backup'] = "Impossible de supprimer la sauvegarde";
$lang[$key.'_files_uploaded_to'] = "Fichiers téléchargés sur";
$lang[$key.'_successfully'] = " avec succès";
$lang[$key.'_could_not_complete_remote_backup'] = "Impossible de terminer la sauvegarde à distance de cette sauvegarde.";
$lang[$key.'_no_remote_storage_selected'] = "Veuillez sélectionner une option de stockage à distance dans les paramètres ";
$lang[$key.'_backup_restored_successfully'] = "Sauvegarde restaurée avec succès";
$lang[$key.'_could_not_restore_backup'] = "Impossible de restaurer la sauvegarde  ";
$lang[$key.'_at_least_one_file_type_to_restore'] = "Veuillez sélectionner au moins un type de fichier à restaurer.";
$lang[$key.'_auto_backup_to_remote_enabled'] = "Télécharger automatiquement la sauvegarde planifiée vers le stockage distant";
$lang[$key.'_choose_the_time_of_your_scheduled_backup'] = "Choisissez l'heure de votre sauvegarde planifiée";







