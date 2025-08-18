<?php

# Version 1.0.0
$key = 'flexibackup';
$lang[$key]     = 'Flexi Backup';
$lang[$key.'_backup_restore']     = 'Flexi Backup and Restore';
$lang[$key.'_now']     = "Δημιουργία αντιγράφων ασφαλείας τώρα";
$lang[$key.'_perform_a_backup']     = 'Δημιουργία αντιγράφων ασφαλείας';
$lang[$key.'_take-a-new-backup']     = 'Δημιουργία νέου αντιγράφου ασφαλείας';
$lang[$key.'_include_database_in_the_backup']     = 'Συμπερίληψη της βάσης δεδομένων στο αντίγραφο ασφαλείας';
$lang[$key.'_database_backup_info']     = 'Η βάση δεδομένων σας θα δημιουργηθεί';
$lang[$key.'_include_file_in_the_backup']     = 'Συμπερίληψη των αρχείων στο αντίγραφο ασφαλείας';
$lang[$key.'_files_backup_info']     = 'Τα αρχεία σας θα δημιουργηθούν';
$lang[$key.'_existing_backups']     = 'Υπάρχοντα αντίγραφα ασφαλείας';
$lang[$key.'_settings']     = 'Ρυθμίσεις';
$lang[$key.'_next_scheduled_backup']     = 'Επόμενο προγραμματισμένο αντίγραφο ασφαλείας';
$lang[$key.'_files_backup_schedule']     = 'Πρόγραμμα αντιγράφων ασφαλείας αρχείων:';
$lang[$key.'_database_backup_schedule']     = 'Πρόγραμμα αντιγράφων ασφαλείας βάσης δεδομένων:';
$lang[$key.'_type_manual']     = 'Χειροκίνητο';
$lang[$key.'_type_every_two_hours']     = 'Κάθε δύο ώρες';
$lang[$key.'_type_every_four_hours']     = 'Κάθε τέσσερις ώρες';
$lang[$key.'_type_every_eight_hours']     = 'Κάθε οκτώ ώρες';
$lang[$key.'_type_every_twelve_hours']     = 'Κάθε δώδεκα ώρες';
$lang[$key.'_type_daily']     = 'Καθημερινά';
$lang[$key.'_type_weekly']     = 'Εβδομαδιαία';
$lang[$key.'_type_fortnightly']     = 'Κάθε δεκαπενθήμερο';
$lang[$key.'_type_monthly']     = 'Μηνιαία';
$lang[$key.'_choose_your_remote_storage']     = "Επιλέξτε τον απομακρυσμένο χώρο αποθήκευσης (πατήστε ένα εικονίδιο για να επιλέξετε ή να αποεπιλέξετε)";
$lang[$key.'_ftp_storage'] = 'FTP';
$lang[$key.'_s3_storage'] = 'Amazon S3';
$lang[$key.'_email'] = 'Email';
$lang[$key.'_email_address'] = 'Email Address';
$lang[$key.'_email_note'] = 'Λάβετε υπόψη ότι οι διακομιστές αλληλογραφίας τείνουν να έχουν όρια μεγέθους. Συνήθως περίπου 10-20 MB. Τα αντίγραφα ασφαλείας μεγαλύτερα από οποιαδήποτε όρια πιθανότατα δεν θα φτάσουν.';
$lang[$key.'_sftp_storage'] = 'SFTP/SCP';
$lang[$key.'_include_in_files_backup'] = 'Local';
$lang[$key.'_include_in_files_backup'] = 'Συμπερίληψη στο αντίγραφο ασφαλείας αρχείων';
$lang[$key.'_modules'] = 'Modules';
$lang[$key.'_application'] = 'Application';
$lang[$key.'_uploads'] = 'Uploads';
$lang[$key.'_assets'] = 'Assets';
$lang[$key.'_system'] = 'System';
$lang[$key.'_resources'] = 'Resources';
$lang[$key.'_media'] = 'Media';
$lang[$key.'-save-changes'] = 'Αποθήκευση αλλαγών';
$lang[$key.'_auto_backup_options_updated'] = "Οι ρυθμίσεις αποθηκεύτηκαν με επιτυχία";
$lang[$key.'_ftp_server'] = 'FTP Server';
$lang[$key.'_ftp_user'] = 'FTP User/Login';
$lang[$key.'_ftp_password'] = 'FTP Password';
$lang[$key.'_ftp_path'] = 'FTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_description'] = "Λάβετε το κλειδί πρόσβασης και το μυστικό κλειδί από την κονσόλα AWS και, στη συνέχεια, επιλέξτε ένα (μοναδικό παγκοσμίως - όλοι οι χρήστες του Amazon S3)
όνομα κάδου (γράμματα και αριθμοί) (και προαιρετικά μια διαδρομή) για χρήση για αποθήκευση.";
$lang[$key.'_sftp_server'] = 'SFTP/SCP Server';
$lang[$key.'_sftp_user'] = 'SFTP User';
$lang[$key.'_sftp_password'] = 'SFTP Password';
$lang[$key.'_sftp_path'] = 'SFTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_access_key'] = 'Amazon S3 Access Key';
$lang[$key.'_s3_secret_key'] = 'Amazon S3 Secret Key';
$lang[$key.'_s3_location'] = 'Amazon S3 Bucket Name';
$lang[$key.'_s3_region'] = 'Amazon S3 Region e.g us-east-1';
$lang[$key.'_back_up_now_note'] = "Your saved settings affect what is Backed up, you can update them <a href='".admin_url('flexibackup/settings')."'><span class='bold'> here </span></a>";
$lang[$key.'_time_now'] = "Ώρα τώρα";
$lang[$key.'_nothing_currently_scheduled'] = "Τίποτα δεν έχει προγραμματιστεί αυτή τη στιγμή";
$lang[$key.'_files'] = "Αρχεία";
$lang[$key.'_database'] = "Βάση δεδομένων";
$lang[$key.'_successful'] = "Η δημιουργία αντιγράφων ασφαλείας ήταν επιτυχής";
$lang[$key.'_unsuccessful'] = "Η δημιουργία αντιγράφων ασφαλείας απέτυχε, ελέγξτε τις ρυθμίσεις σας και δοκιμάστε ξανά";
$lang[$key.'_backup_name_prefix'] = "Πρόθεμα ονόματος αντιγράφου ασφαλείας";
$lang[$key.'_include_others'] = "Others (Root files such as index.php, .htaccess, robots.txt, package.xml e.t.c)";
$lang[$key.'_date'] = "Ημερομηνία";
$lang[$key.'_backup_data_click'] = "Κάντε κλικ στο αντίγραφο ασφαλείας για να το κατεβάσετε";
$lang[$key.'_log_file'] = "Αρχείο καταγραφής";
$lang[$key.'_download_log_file'] = "Κατεβάστε το αρχείο καταγραφής";
$lang[$key.'_download_to_your_computer'] = "Κατεβάστε στον υπολογιστή σας";
$lang[$key.'_delete_from_your_webserver'] = "Διαγραφή από τον web server σας ";
$lang[$key.'_browse_contents'] = "Περιήγηση στο περιεχόμενο";
$lang[$key.'_file_ready_actions'] = "Το αρχείο είναι έτοιμο για ενέργειες";
$lang[$key.'_webdav_storage'] = "WebDAV";
$lang[$key.'_webdav_password'] = "WebDAV Password";
$lang[$key.'_webdav_username'] = "WebDAV Username";
$lang[$key.'_webdav_base_uri'] = "WebDAV Base URI";
$lang[$key.'_upload_to_remote'] = "Upload to remote";
$lang[$key.'_uploaded_to_remote_storage'] = "Μεταφορτώθηκε σε απομακρυσμένο χώρο αποθήκευσης";
$lang[$key.'_restore'] = "Επαναφορά";
$lang[$key.'_restore_files_from'] = "Επαναφορά αρχείων από ";
$lang[$key.'_restore_warning'] = "Restoring will replace this site's Application, Modules, Uploads, Resources, System, Media, Assets, Database and/or Others content directories (according to what is contained in the backup set, and your selection).";
$lang[$key.'_view_log'] = "Προβολή καταγραφής";
$lang[$key.'_choose_componet_to_restore'] = "Επιλέξτε τουλάχιστον έναν τύπο αρχείου για επαναφορά ";
$lang[$key.'_restore_db_warning'] = "<b>Δημιουργία αντιγράφων ασφαλείας της τρέχουσας βάσης δεδομένων (Προαιρετικό, αλλά συνιστάται): </b> Πριν προχωρήσετε στην επαναφορά της βάσης δεδομένων, είναι καλή πρακτική να δημιουργήσετε αντίγραφα ασφαλείας της τρέχουσας βάσης δεδομένων σας σε περίπτωση που χρειαστεί να επαναφέρετε τυχόν αλλαγές.";
$lang[$key.'_log_file_not_found'] = "Το αρχείο καταγραφής δεν βρέθηκε";
$lang[$key.'_could_not_donwload_file'] = "Δεν ήταν δυνατή η λήψη του αρχείου";
$lang[$key.'_file_removed_successfully'] = "Το αρχείο αφαιρέθηκε με επιτυχία";
$lang[$key.'_could_not_remove_backup'] = "Δεν ήταν δυνατή η αφαίρεση του αντιγράφου ασφαλείας";
$lang[$key.'_files_uploaded_to'] = "Τα αρχεία μεταφορτώθηκαν σε";
$lang[$key.'_successfully'] = " με επιτυχία";
$lang[$key.'_could_not_complete_remote_backup'] = "Δεν ήταν δυνατή η ολοκλήρωση του απομακρυσμένου αντιγράφου ασφαλείας";
$lang[$key.'_no_remote_storage_selected'] = "Δεν επιλέχθηκε απομακρυσμένος χώρος αποθήκευσης";
$lang[$key.'_backup_restored_successfully'] = "Το αντίγραφο ασφαλείας επαναφέρθηκε με επιτυχία";
$lang[$key.'_could_not_restore_backup'] = "Δεν ήταν δυνατή η επαναφορά του αντιγράφου ασφαλείας ";
$lang[$key.'_at_least_one_file_type_to_restore'] = "Επιλέξτε τουλάχιστον έναν τύπο αρχείου για επαναφορά ";
$lang[$key.'_auto_backup_to_remote_enabled'] = "Αυτόματη μεταφόρτωση προγραμματισμένου αντιγράφου ασφαλείας σε απομακρυσμένη αποθήκευση";
$lang[$key.'_choose_the_time_of_your_scheduled_backup'] = "Επιλέξτε την ώρα του προγραμματισμένου αντιγράφου ασφαλείας";







