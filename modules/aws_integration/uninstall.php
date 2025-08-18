<?php

defined('BASEPATH') or exit('No direct script access allowed');

delete_option('enable_aws_integration');
delete_option('aws_access_key_id');
delete_option('aws_secret_access_key');
delete_option('aws_region');
delete_option('aws_bucket');

if ($CI->db->table_exists(db_prefix() . 's3_files')) {
    $CI->db->query('DROP TABLE `' . db_prefix() . 's3_files`;');
}