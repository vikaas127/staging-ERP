<?php

# Version 1.0.0
$key = 'flexibackup';
$lang[$key] = 'Flexi Backup';
$lang[$key . '_backup_restore'] = 'Flexibackup and Restore';
$lang[$key . '_now'] = 'Архивиране сега';
$lang[$key . '_perform_a_backup'] = 'Извършете архивиране';
$lang[$key . '_take-a-new-backup'] = 'Извършете архивиране';
$lang[$key . '_include_database_in_the_backup'] = 'Включете вашата база данни в резервното копие';
$lang[$key . '_database_backup_info'] = 'Всичките ви таблици в базата данни ще бъдат архивирани';
$lang[$key . '_include_file_in_the_backup'] = 'Включете вашите файлове в резервното копие';
$lang[$key . '_files_backup_info'] = 'Вашите файлове ще бъдат архивирани';
$lang[$key . '_existing_backups'] = 'Съществуващи резервни копия';
$lang[$key . '_settings'] = 'Настройки';
$lang[$key . '_next_scheduled_backup'] = 'Следващо планирано архивиране';
$lang[$key . '_files_backup_schedule'] = 'График за архивиране на файлове:';
$lang[$key . '_database_backup_schedule'] = 'График за архивиране на база данни:';
$lang[$key . '_type_manual'] = 'Наръчник';
$lang[$key . '_type_every_two_hours'] = 'На всеки два часа';
$lang[$key . '_type_every_four_hours'] = 'На всеки четири часа';
$lang[$key . '_type_every_eight_hours'] = 'На всеки осем часа';
$lang[$key . '_type_every_twelve_hours'] = 'На всеки дванадесет часа';
$lang[$key . '_type_daily'] = 'Ежедневно';
$lang[$key . '_type_weekly'] = 'Ежеседмично';
$lang[$key . '_type_fortnightly'] = 'Всеки две седмици';
$lang[$key . '_type_monthly'] = 'Месечно';
$lang[$key . '_choose_your_remote_storage'] = 'Изберете вашето отдалечено хранилище (докоснете икона, за да изберете или премахнете избора)';
$lang[$key . '_ftp_storage'] = 'FTP';
$lang[$key . '_s3_storage'] = 'Amazon S3';
$lang[$key . '_email'] = 'електронна поща';
$lang[$key . '_email_address'] = 'Имейл адрес';
$lang[$key . '_email_note'] = 'Моля, имайте предвид, че пощенските сървъри обикновено имат ограничения на размера; обикновено около 10-20 MB; резервни копия, по-големи от всички ограничения, вероятно няма да пристигнат.';
$lang[$key . '_sftp_storage'] = 'SFTP/SCP';
$lang[$key . '_include_in_files_backup'] = 'Местен';
$lang[$key . '_include_in_files_backup'] = 'Включете в резервното копие на файловете:';
$lang[$key . '_modules'] = 'Modules';
$lang[$key . '_application'] = 'Application';
$lang[$key . '_uploads'] = 'Uploads';
$lang[$key . '_assets'] = 'Assets';
$lang[$key . '_system'] = 'System';
$lang[$key . '_resources'] = 'Resources';
$lang[$key . '_media'] = 'Media';
$lang[$key . '-save-changes'] = 'Запазите промените';
$lang[$key . '_auto_backup_options_updated'] = 'Настройките са запазени успешно';
$lang[$key . '_ftp_server'] = 'FTP Server';
$lang[$key . '_ftp_user'] = 'FTP User/Login';
$lang[$key . '_ftp_password'] = 'FTP Password';
$lang[$key . '_ftp_path'] = 'FTP Path (Трябва да съществува и да може да се пише)';
$lang[$key . '_s3_description'] = 'Вземете вашия ключ за достъп и секретен ключ от вашата AWS конзола, след което изберете (глобално уникален - всички потребители на Amazon S3)
име на кофа (букви и цифри) (и по желание път), което да използвате за съхранение.';
$lang[$key . '_sftp_server'] = 'SFTP/SCP Server';
$lang[$key . '_sftp_user'] = 'SFTP User';
$lang[$key . '_sftp_password'] = 'SFTP Password';
$lang[$key . '_sftp_path'] = 'SFTP Path (Трябва да съществува и да може да се пише)';
$lang[$key . '_s3_access_key'] = 'Amazon S3 Access Key';
$lang[$key . '_s3_secret_key'] = 'Amazon S3 Secret Key';
$lang[$key . '_s3_location'] = 'Amazon S3 Bucket Name';
$lang[$key . '_s3_region'] = 'Amazon S3 Region e.g us-east-1';
$lang[$key . '_back_up_now_note'] = "Вашите запазени настройки засягат това, което е архивирано, можете да ги актуализирате <a href='" . admin_url('flexibackup/settings') . "'><span class='bold'> тук </span></a>";
$lang[$key . '_time_now'] = 'Време сега';
$lang[$key . '_nothing_currently_scheduled'] = 'В момента няма нищо планирано';
$lang[$key . '_files'] = "Files";
$lang[$key . '_database'] = "Database";
$lang[$key . '_successful'] = "Архивирането е успешно";
$lang[$key . '_unsuccessful'] = "Неуспешно архивиране, моля, проверете настройките си и опитайте отново";
$lang[$key . '_backup_name_prefix'] = "Префикс за име на архивен файл";
$lang[$key . '_include_others'] = "Други (основни файлове като index.php, .htaccess, robots.txt, package.xml и т.н.)";
$lang[$key . '_date'] = "Дата на архивиране";
$lang[$key . '_backup_data_click'] = "Архивиране на данни (щракнете за изтегляне) ";
$lang[$key . '_log_file'] = "Регистрационен файл";
$lang[$key . '_download_log_file'] = "Изтегляне на лог файл";
$lang[$key . '_download_to_your_computer'] = "Изтеглете на вашия компютър";
$lang[$key . '_delete_from_your_webserver'] = "Изтриване от вашия уеб сървър";
$lang[$key . '_browse_contents'] = "Преглед на съдържанието";
$lang[$key . '_file_ready_actions'] = "Готови действия за файлове";
$lang[$key . '_webdav_storage'] = "WebDAV";
$lang[$key . '_webdav_password'] = "WebDAV Password";
$lang[$key . '_webdav_username'] = "WebDAV Username";
$lang[$key . '_webdav_base_uri'] = "WebDAV Base URI";
$lang[$key . '_upload_to_remote'] = "Качване на дистанционно";
$lang[$key . '_uploaded_to_remote_storage'] = "Качен в отдалечено хранилище";
$lang[$key . '_restore'] = "Възстанови";
$lang[$key . '_restore_files_from'] = "Възстановяване - Възстановяване на файлове от";
$lang[$key . '_restore_warning'] = "Restoring will replace this site's Application, Modules, Uploads, Resources, System, Media, Assets, Database and/or Others content directories (according to what is contained in the backup set, and your selection).";
$lang[$key . '_view_log'] = "Преглед на дневника";
$lang[$key . '_choose_componet_to_restore'] = "Изберете компонентите за възстановяване:";
$lang[$key . '_restore_db_warning'] = "<b>Архивирайте текущата база данни (по избор, но се препоръчва): </b> Преди да продължите с възстановяването на базата данни, добра практика е да направите резервно копие на текущата си база данни, в случай че се наложи да отмените някакви промени.";
$lang[$key . '_log_file_not_found'] = "Не е намерен лог файл";
$lang[$key . '_could_not_donwload_file'] = "Файлът не можа да бъде изтеглен";
$lang[$key . '_file_removed_successfully'] = "Файлът е премахнат успешно";
$lang[$key . '_could_not_remove_backup'] = "Не можа да се премахне резервното копие.";
$lang[$key . '_files_uploaded_to'] = "Файлове, качени в ";
$lang[$key . '_successfully'] = " успешно";
$lang[$key . '_could_not_complete_remote_backup'] = "Не може да се завърши отдалечено архивиране на това архивиране. ";
$lang[$key . '_no_remote_storage_selected'] = "Моля, изберете опция за отдалечено съхранение в настройките. ";
$lang[$key . '_backup_restored_successfully'] = "Резервното копие е възстановено успешно ";
$lang[$key . '_could_not_restore_backup'] = "Резервното копие не можа да се възстанови.  ";
$lang[$key.'_at_least_one_file_type_to_restore'] = "Моля, изберете поне един тип файл за възстановяване.";
$lang[$key.'_auto_backup_to_remote_enabled'] = "Автоматично качване на планирано резервно копие в отдалечено съхранение";
$lang[$key.'_choose_the_time_of_your_scheduled_backup'] = "Изберете времето за планирано архивиране";







