<?php

# Version 1.0.0
$key = 'flexibackup';
$lang[$key]     = 'Flexi Backup';
$lang[$key.'_backup_restore']     = 'Flexi Backup and Restore';
$lang[$key.'_now']     = "Sekarang";
$lang[$key.'_perform_a_backup']     = "Lakukan Pencadangan";
$lang[$key.'_take-a-new-backup']     = 'Lakukan Pencadangan';
$lang[$key.'_include_database_in_the_backup']     = 'Sertakan database Anda dalam pencadangan';
$lang[$key.'_database_backup_info']     = 'Semua tabel database Anda akan dicadangkan';
$lang[$key.'_include_file_in_the_backup']     = 'Sertakan file Anda dalam pencadangan';
$lang[$key.'_files_backup_info']     = 'File Anda akan dicadangkan';
$lang[$key.'_existing_backups']     = 'Cadangan yang ada';
$lang[$key.'_settings']     = 'Pengaturan';
$lang[$key.'_next_scheduled_backup']     = 'Pencadangan Berikutnya yang Diatur';
$lang[$key.'_files_backup_schedule']     = 'Jadwal pencadangan file:';
$lang[$key.'_database_backup_schedule']     = 'Jadwal pencadangan database:';
$lang[$key.'_type_manual']     = 'Manual';
$lang[$key.'_type_every_two_hours']     = 'Setiap dua jam';
$lang[$key.'_type_every_four_hours']     = 'Setiap empat jam';
$lang[$key.'_type_every_eight_hours']     = 'Setiap delapan jam';
$lang[$key.'_type_every_twelve_hours']     = 'Setiap dua belas jam';
$lang[$key.'_type_daily']     = 'Harian';
$lang[$key.'_type_weekly']     = 'Mingguan';
$lang[$key.'_type_fortnightly']     = 'Dua Mingguan';
$lang[$key.'_type_monthly']     = 'Bulanan';
$lang[$key.'_choose_your_remote_storage']     = 'Pilih penyimpanan jarak jauh Anda (ketuk ikon untuk memilih atau tidak memilih)';
$lang[$key.'_ftp_storage'] = 'FTP';
$lang[$key.'_s3_storage'] = 'Amazon S3';
$lang[$key.'_email'] = 'Email';
$lang[$key.'_email_address'] = 'Alamat Email';
$lang[$key.'_email_note'] = 'Harap perhatikan bahwa server email cenderung memiliki batas ukuran; biasanya sekitar 10-20 MB; cadangan yang lebih besar dari batas apa pun kemungkinan besar tidak akan tiba.';
$lang[$key.'_sftp_storage'] = 'SFTP/SCP';
$lang[$key.'_include_in_files_backup'] = 'Local';
$lang[$key.'_include_in_files_backup'] = 'Sertakan dalam pencadangan file:';
$lang[$key.'_modules'] = 'Modules';
$lang[$key.'_application'] = 'Application';
$lang[$key.'_uploads'] = 'Uploads';
$lang[$key.'_assets'] = 'Assets';
$lang[$key.'_system'] = 'System';
$lang[$key.'_resources'] = 'Resources';
$lang[$key.'_media'] = 'Media';
$lang[$key.'-save-changes'] = 'Simpan Perubahan';
$lang[$key.'_auto_backup_options_updated'] = 'Pengaturan berhasil disimpan';
$lang[$key.'_ftp_server'] = 'FTP Server';
$lang[$key.'_ftp_user'] = 'FTP User/Login';
$lang[$key.'_ftp_password'] = 'FTP Password';
$lang[$key.'_ftp_path'] = 'FTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_description'] = 'Dapatkan kunci akses dan kunci rahasia Anda dari konsol AWS Anda, lalu pilih nama bucket (unik secara global - semua pengguna Amazon S3) (dan opsional jalur) untuk digunakan sebagai penyimpanan.';
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
$lang[$key.'_nothing_currently_scheduled'] = 'Saat ini tidak ada yang dijadwalkan';
$lang[$key.'_files'] = "Files";
$lang[$key.'_database'] = "Basis data";
$lang[$key.'_successful'] = "Pencadangan Berhasil";
$lang[$key.'_unsuccessful'] = "Pencadangan Gagal, periksa pengaturan Anda dan coba lagi";
$lang[$key.'_backup_name_prefix'] = "Awalan Nama File Cadangan";
$lang[$key.'_include_others'] = "Others (Root files such as index.php, .htaccess, robots.txt, package.xml e.t.c)";
$lang[$key.'_date'] = "Tanggal Pencadangan";
$lang[$key.'_backup_data_click'] = "Data Cadangan (klik untuk mengunduh)";
$lang[$key.'_log_file'] = "Berkas Log";
$lang[$key.'_download_log_file'] = "Unduh Berkas Log";
$lang[$key.'_download_to_your_computer'] = "Unduh ke komputer Anda";
$lang[$key.'_delete_from_your_webserver'] = "Hapus dari server web Anda";
$lang[$key.'_browse_contents'] = "Telusuri Isi";
$lang[$key.'_file_ready_actions'] = "Tindakan File Siap";
$lang[$key.'_webdav_storage'] = "WebDAV";
$lang[$key.'_webdav_password'] = "WebDAV Password";
$lang[$key.'_webdav_username'] = "WebDAV Username";
$lang[$key.'_webdav_base_uri'] = "WebDAV Base URI";
$lang[$key.'_upload_to_remote'] = "Upload to remote";
$lang[$key.'_uploaded_to_remote_storage'] = "Diunggah ke penyimpanan jarak jauh";
$lang[$key.'_restore'] = "Pemulihan";
$lang[$key.'_restore_files_from'] = "Restorasi - Memulihkan file dari ";
$lang[$key.'_restore_warning'] = "Restoring will replace this site's Application, Modules, Uploads, Resources, System, Media, Assets, Database and/or Others content directories (according to what is contained in the backup set, and your selection).";
$lang[$key.'_view_log'] = "Melihat log";
$lang[$key.'_choose_componet_to_restore'] = "Pilih komponen yang akan dipulihkan:";
$lang[$key.'_restore_db_warning'] = "<b>Backup the Current Database (Optional, but recommended): </b> Before proceeding with the database restore, it's a good practice to backup your current database in case you need to revert any changes.";
$lang[$key.'_log_file_not_found'] = "Tidak ada berkas log yang ditemukan";
$lang[$key.'_could_not_donwload_file'] = "Tidak dapat mengunduh file";
$lang[$key.'_file_removed_successfully'] = "File berhasil dihapus";
$lang[$key.'_could_not_remove_backup'] = "Tidak dapat menghapus cadangan.";
$lang[$key.'_files_uploaded_to'] = "File diunggah ke ";
$lang[$key.'_successfully'] = " berhasil";
$lang[$key.'_could_not_complete_remote_backup'] = "Tidak dapat menyelesaikan pencadangan jarak jauh";
$lang[$key.'_no_remote_storage_selected'] = "Tidak ada penyimpanan jarak jauh yang dipilih";
$lang[$key.'_backup_restored_successfully'] = "Pencadangan berhasil dipulihkan";
$lang[$key.'_could_not_restore_backup'] = "Tidak dapat memulihkan cadangan";
$lang[$key.'_at_least_one_file_type_to_restore'] = "Pilih komponen yang akan dipulihkan:";
$lang[$key.'_auto_backup_to_remote_enabled'] = "Pencadangan otomatis ke penyimpanan jarak jauh diaktifkan";
$lang[$key.'_choose_the_time_of_your_scheduled_backup'] = "Pilih waktu pencadangan yang dijadwalkan Anda";







