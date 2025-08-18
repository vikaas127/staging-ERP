<?php

# Version 1.0.0
$key = 'flexibackup';
$lang[$key]     = 'Flexi Backup';
$lang[$key.'_backup_restore']     = 'Flexi Backup and Restore';
$lang[$key.'_now']     = '今すぐバックアップ';
$lang[$key.'_perform_a_backup']     = 'バックアップを実行する';
$lang[$key.'_take-a-new-backup']     = 'バックアップを実行する';
$lang[$key.'_include_database_in_the_backup']     = 'バックアップにデータベースを含める';
$lang[$key.'_database_backup_info']     = 'データベースがバックアップされます';
$lang[$key.'_include_file_in_the_backup']     = 'バックアップにファイルを含める';
$lang[$key.'_files_backup_info']     = 'ファイルがバックアップされます';
$lang[$key.'_existing_backups']     = '既存のバックアップ';
$lang[$key.'_settings']     = '設定';
$lang[$key.'_next_scheduled_backup']     = '次の予定されたバックアップ';
$lang[$key.'_files_backup_schedule']     = 'ファイルバックアップスケジュール：';
$lang[$key.'_database_backup_schedule']     = 'データベースバックアップスケジュール：';
$lang[$key.'_type_manual']     = '手動';
$lang[$key.'_type_every_two_hours']     = '2時間ごと';
$lang[$key.'_type_every_four_hours']     = '4時間ごと';
$lang[$key.'_type_every_eight_hours']     = '8時間ごと';
$lang[$key.'_type_every_twelve_hours']     = '12時間ごと';
$lang[$key.'_type_daily']     = '毎日';
$lang[$key.'_type_weekly']     = '毎週';
$lang[$key.'_type_fortnightly']     = '毎月';
$lang[$key.'_type_monthly']     = '毎月';
$lang[$key.'_choose_your_remote_storage']     = 'リモート ストレージを選択します (アイコンをタップして選択または選択を解除します)';
$lang[$key.'_ftp_storage'] = 'FTP';
$lang[$key.'_s3_storage'] = 'Amazon S3';
$lang[$key.'_email'] = 'Email';
$lang[$key.'_email_address'] = 'Email Address';
$lang[$key.'_email_note'] = 'メールサーバーにはサイズ制限がある傾向があることに注意してください。 通常は約 10 ～ 20 MB。 制限を超えるバックアップは到着しない可能性があります。';
$lang[$key.'_sftp_storage'] = 'SFTP/SCP';
$lang[$key.'_include_in_files_backup'] = '地元';
$lang[$key.'_include_in_files_backup'] = 'ファイルのバックアップに含める:';
$lang[$key.'_modules'] = 'Modules';
$lang[$key.'_application'] = 'Application';
$lang[$key.'_uploads'] = 'Uploads';
$lang[$key.'_assets'] = 'Assets';
$lang[$key.'_system'] = 'System';
$lang[$key.'_resources'] = 'Resources';
$lang[$key.'_media'] = 'Media';
$lang[$key.'-save-changes'] = '変更を保存する';
$lang[$key.'_auto_backup_options_updated'] = '設定が正常に保存されました';
$lang[$key.'_ftp_server'] = 'FTP Server';
$lang[$key.'_ftp_user'] = 'FTP User/Login';
$lang[$key.'_ftp_password'] = 'FTP Password';
$lang[$key.'_ftp_path'] = 'FTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_description'] = 'AWS コンソールからアクセスキーと秘密キーを取得し、(グローバルに一意 - すべての Amazon S3 ユーザー) を選択します。
ストレージに使用するバケット名 (文字と数字) (およびオプションでパス)。';
$lang[$key.'_sftp_server'] = 'SFTP/SCP Server';
$lang[$key.'_sftp_user'] = 'SFTP User';
$lang[$key.'_sftp_password'] = 'SFTP Password';
$lang[$key.'_sftp_path'] = 'SFTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_access_key'] = 'Amazon S3 Access Key';
$lang[$key.'_s3_secret_key'] = 'Amazon S3 Secret Key';
$lang[$key.'_s3_location'] = 'Amazon S3 Bucket Name';
$lang[$key.'_s3_region'] = 'Amazon S3 Region e.g us-east-1';
$lang[$key.'_back_up_now_note'] = "Your saved settings affect what is Backed up, you can update them <a href='".admin_url('flexibackup/settings')."'><span class='bold'> here </span></a>";
$lang[$key.'_time_now'] = '今の時間';
$lang[$key.'_nothing_currently_scheduled'] = '現在何も予定はありません';
$lang[$key.'_files'] = "ファイル";
$lang[$key.'_database'] = "データベース";
$lang[$key.'_successful'] = "バックアップ成功";
$lang[$key.'_unsuccessful'] = "バックアップに失敗しました。設定を確認してもう一度お試しください。";
$lang[$key.'_backup_name_prefix'] = "バックアップファイル名のプレフィックス";
$lang[$key.'_include_others'] = "Others (Root files such as index.php, .htaccess, robots.txt, package.xml e.t.c)";
$lang[$key.'_date'] = "バックアップ日";
$lang[$key.'_backup_data_click'] = "バックアップデータをクリックしてダウンロードします";
$lang[$key.'_log_file'] = "ログファイル";
$lang[$key.'_download_log_file'] = "ログファイルをダウンロードする";
$lang[$key.'_download_to_your_computer'] = "コンピュータにダウンロードする";
$lang[$key.'_delete_from_your_webserver'] = "Webサーバーから削除する ";
$lang[$key.'_browse_contents'] = "コンテンツを閲覧する";
$lang[$key.'_file_ready_actions'] = "ファイル準備完了アクション";
$lang[$key.'_webdav_storage'] = "WebDAV";
$lang[$key.'_webdav_password'] = "WebDAV Password";
$lang[$key.'_webdav_username'] = "WebDAV Username";
$lang[$key.'_webdav_base_uri'] = "WebDAV Base URI";
$lang[$key.'_upload_to_remote'] = "Upload to remote";
$lang[$key.'_uploaded_to_remote_storage'] = "リモートストレージにアップロード";
$lang[$key.'_restore'] = "復元する";
$lang[$key.'_restore_files_from'] = "復元 - ファイルを復元します ";
$lang[$key.'_restore_warning'] = "Restoring will replace this site's Application, Modules, Uploads, Resources, System, Media, Assets, Database and/or Others content directories (according to what is contained in the backup set, and your selection).";
$lang[$key.'_view_log'] = "ビュー・ログ";
$lang[$key.'_choose_componet_to_restore'] = "復元するコンポーネントを選択します。";
$lang[$key.'_restore_db_warning'] = "<b>Backup the Current Database (Optional, but recommended): </b> Before proceeding with the database restore, it's a good practice to backup your current database in case you need to revert any changes.";
$lang[$key.'_log_file_not_found'] = "ログファイルが見つかりません";
$lang[$key.'_could_not_donwload_file'] = "ファイルをダウンロードできませんでした";
$lang[$key.'_file_removed_successfully'] = "ファイルが正常に削除されました";
$lang[$key.'_could_not_remove_backup'] = "バックアップを削除できませんでした";
$lang[$key.'_files_uploaded_to'] = "ファイルがアップロードされました";
$lang[$key.'_successfully'] = " 正常に";
$lang[$key.'_could_not_complete_remote_backup'] = "リモートバックアップを完了できませんでした ";
$lang[$key.'_no_remote_storage_selected'] = "リモートストレージが選択されていません ";
$lang[$key.'_backup_restored_successfully'] = "バックアップが正常に復元されました";
$lang[$key.'_could_not_restore_backup'] = "バックアップを復元できませんでした  ";
$lang[$key.'_at_least_one_file_type_to_restore'] = "復元するコンポーネントを選択します。";
$lang[$key.'_auto_backup_to_remote_enabled'] = "スケジュールされたバックアップを自動的にリモートストレージにアップロードする";
$lang[$key.'_choose_the_time_of_your_scheduled_backup'] = "スケジュールされたバックアップの時間を選択します";







