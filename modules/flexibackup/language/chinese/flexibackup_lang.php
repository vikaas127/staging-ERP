<?php

# Version 1.0.0
$key = 'flexibackup';
$lang[$key]     = 'Flexi Backup';
$lang[$key.'_backup_restore']     = 'Flexi Backup and Restore';
$lang[$key.'_now']     = '立即备份';
$lang[$key.'_perform_a_backup']     = '执行备份';
$lang[$key.'_take-a-new-backup']     = '执行备份';
$lang[$key.'_include_database_in_the_backup']     = '在备份中包含您的数据库';
$lang[$key.'_database_backup_info']     = '将备份所有数据库表';
$lang[$key.'_include_file_in_the_backup']     = '在备份中包含您的文件';
$lang[$key.'_files_backup_info']     = '将备份您的文件';
$lang[$key.'_existing_backups']     = '现有备份';
$lang[$key.'_settings']     = '设置';
$lang[$key.'_next_scheduled_backup']     = '下一个计划备份';
$lang[$key.'_files_backup_schedule']     = '文件备份计划：';
$lang[$key.'_database_backup_schedule']     = '数据库备份计划：';
$lang[$key.'_type_manual']     = '手动';
$lang[$key.'_type_every_two_hours']     = '每两小时';
$lang[$key.'_type_every_four_hours']     = '每四小时';
$lang[$key.'_type_every_eight_hours']     = '每八小时';
$lang[$key.'_type_every_twelve_hours']     = '每十二小时';
$lang[$key.'_type_daily']     = '每日';
$lang[$key.'_type_weekly']     = '每周';
$lang[$key.'_type_fortnightly']     = '每两周';
$lang[$key.'_type_monthly']     = '每月';
$lang[$key.'_choose_your_remote_storage']     = '选择您的远程存储（点击图标选择或取消选择）';
$lang[$key.'_ftp_storage'] = 'FTP';
$lang[$key.'_s3_storage'] = 'Amazon S3';
$lang[$key.'_email'] = '电子邮件';
$lang[$key.'_email_address'] = '电子邮件地址';
$lang[$key.'_email_note'] = '请注意，邮件服务器往往有大小限制; 通常在10-20 MB左右; 大于任何限制的备份可能不会到达。';
$lang[$key.'_sftp_storage'] = 'SFTP/SCP';
$lang[$key.'_include_in_files_backup'] = '本地';
$lang[$key.'_include_in_files_backup'] = '包含在文件备份中：';
$lang[$key.'_modules'] = 'Modules';
$lang[$key.'_application'] = 'Application';
$lang[$key.'_uploads'] = 'Uploads';
$lang[$key.'_assets'] = 'Assets';
$lang[$key.'_system'] = 'System';
$lang[$key.'_resources'] = 'Resources';
$lang[$key.'_media'] = 'Media';
$lang[$key.'-save-changes'] = '保存更改';
$lang[$key.'_auto_backup_options_updated'] = '设置已成功保存';
$lang[$key.'_ftp_server'] = 'FTP Server';
$lang[$key.'_ftp_user'] = 'FTP User/Login';
$lang[$key.'_ftp_password'] = 'FTP Password';
$lang[$key.'_ftp_path'] = 'FTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_description'] = "'从 AWS 控制台获取访问密钥和秘密密钥，然后选择一个（全局唯一 - 所有 Amazon S3 用户）
用于存储的存储桶名称（字母和数字）（以及可选的路径）。";
$lang[$key.'_sftp_server'] = 'SFTP/SCP Server';
$lang[$key.'_sftp_user'] = 'SFTP User';
$lang[$key.'_sftp_password'] = 'SFTP Password';
$lang[$key.'_sftp_path'] = 'SFTP Path (Needs to be exists and writable)';
$lang[$key.'_s3_access_key'] = 'Amazon S3 Access Key';
$lang[$key.'_s3_secret_key'] = 'Amazon S3 Secret Key';
$lang[$key.'_s3_location'] = 'Amazon S3 Bucket Name';
$lang[$key.'_s3_region'] = 'Amazon S3 Region e.g us-east-1';
$lang[$key.'_back_up_now_note'] = "您保存的设置会影响备份的内容，您可以更新它们 <a href='".admin_url('flexibackup/settings')."'><span class='bold'> 这里 </span></a>";
$lang[$key.'_time_now']  = '现在时间';
$lang[$key.'_nothing_currently_scheduled'] = '当前没有计划';
$lang[$key.'_files'] = "Files";
$lang[$key.'_database'] = "Database";
$lang[$key.'_successful'] = "备份成功";
$lang[$key.'_unsuccessful'] = "备份失败，请检查您的设置并重试";
$lang[$key.'_backup_name_prefix'] = "备份文件名前缀";
$lang[$key.'_include_others'] = "Others (Root files such as index.php, .htaccess, robots.txt, package.xml e.t.c)";
$lang[$key.'_date'] = "备份日期";
$lang[$key.'_backup_data_click'] = "备份数据（单击下载） ";
$lang[$key.'_log_file'] = "日志文件";
$lang[$key.'_download_log_file'] = "下载日志文件";
$lang[$key.'_download_to_your_computer'] = "下载到您的电脑";
$lang[$key.'_delete_from_your_webserver'] = "从您的网络服务器删除";
$lang[$key.'_browse_contents'] = "浏览内容";
$lang[$key.'_file_ready_actions'] = "文件就绪操作";
$lang[$key.'_webdav_storage'] = "WebDAV";
$lang[$key.'_webdav_password'] = "WebDAV Password";
$lang[$key.'_webdav_username'] = "WebDAV Username";
$lang[$key.'_webdav_base_uri'] = "WebDAV Base URI";
$lang[$key.'_upload_to_remote'] = "上传到远程";
$lang[$key.'_uploaded_to_remote_storage'] = "上传到远程存储";
$lang[$key.'_restore'] = "恢复";
$lang[$key.'_restore_files_from'] = "恢复 - 从恢复文件";
$lang[$key.'_restore_warning'] = "Restoring will replace this site's Application, Modules, Uploads, Resources, System, Media, Assets, Database and/or Others content directories (according to what is contained in the backup set, and your selection).";
$lang[$key.'_view_log'] = "查看日志";
$lang[$key.'_choose_componet_to_restore'] = "选择要恢复的组件：";
$lang[$key.'_restore_db_warning'] = "<b>备份当前数据库（可选，但建议）：</b>在继续恢复数据库之前，最好备份当前数据库，以防需要撤消任何更改。";
$lang[$key.'_log_file_not_found'] = "找不到日志文件";
$lang[$key.'_could_not_donwload_file'] = "无法下载文件";
$lang[$key.'_file_removed_successfully'] = "文件已成功删除";
$lang[$key.'_could_not_remove_backup'] = "无法删除备份。";
$lang[$key.'_files_uploaded_to'] = "文件已上传到 ";
$lang[$key.'_successfully'] = "成功";
$lang[$key.'_could_not_complete_remote_backup'] = "无法完成此备份的远程备份。 ";
$lang[$key.'_no_remote_storage_selected'] = "请在设置中选择远程存储选项。 ";
$lang[$key.'_backup_restored_successfully'] = "备份已成功恢复 ";
$lang[$key.'_could_not_restore_backup'] = "无法恢复备份。  ";
$lang[$key.'_at_least_one_file_type_to_restore'] = "请选择要恢复的文件类型。";
$lang[$key.'_auto_backup_to_remote_enabled'] = "自动将计划备份上传到远程存储";
$lang[$key.'_choose_the_time_of_your_scheduled_backup'] = "选择计划备份的时间";







