<?php

defined('BASEPATH') or exit('No direct script access allowed');

add_option('enable_aws_integration', false);
add_option('aws_access_key_id', '');
add_option('aws_secret_access_key', '');
add_option('aws_region', 'us-east-1');
add_option('aws_bucket', '');

if (!$CI->db->table_exists(db_prefix() . 's3_files')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 's3_files` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `rel_id` int(11) NOT NULL,
        `rel_type` varchar(255) NOT NULL,
        `url` mediumtext NOT NULL,
        `s3_key` mediumtext NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}