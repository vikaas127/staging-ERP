<?php

defined('BASEPATH') or exit('No direct script access allowed');

add_option('ainlreports_ainlreports_sqlenz_api', '');

if (!$CI->db->table_exists(db_prefix() . 'ainlreports_query_history')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "ainlreports_query_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11),
  `user_query` text,
  `created_at` datetime
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'ainlreports_query_history`
  ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'ainlreports_query_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}