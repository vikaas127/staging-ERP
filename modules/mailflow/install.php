<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'mailflow_newsletter_history')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "mailflow_newsletter_history` (
  `id` int(11) NOT NULL,
  `sent_by` text,
  `email_subject` text,
  `email_content` text,
  `total_emails_to_send` text,
  `email_list` text,
  `emails_sent` text,
  `emails_failed` text,
  `created_at` datetime
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'mailflow_newsletter_history`
  ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'mailflow_newsletter_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

if (!$CI->db->table_exists(db_prefix() . 'mailflow_email_templates')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "mailflow_email_templates` (
  `id` int(11) NOT NULL,
  `template_name` text,
  `template_subject` text,
  `template_content` text,
  `created_at` datetime
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'mailflow_email_templates`
  ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'mailflow_email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

if (!$CI->db->table_exists(db_prefix() . 'mailflow_unsubscribed_emails')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "mailflow_unsubscribed_emails` (
  `id` int(11) NOT NULL,
  `email` text,
  `created_at` datetime
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'mailflow_unsubscribed_emails`
  ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'mailflow_unsubscribed_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}