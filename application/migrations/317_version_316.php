<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_317 extends CI_Migration
{
    public function up()
    {
        // Add new columns to gst_details table
        $fields = array(
            'eway_bill_no' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE
            ),
            'eway_bill_date' => array(
                'type' => 'DATE',
                'null' => TRUE
            ),
            'eway_bill_status' => array(
                'type' => "ENUM('Active', 'Cancelled', 'Expired')",
                'default' => 'Active'
            ),
            'irn' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE
            ),
            'irn_status' => array(
                'type' => "ENUM('Valid', 'Cancelled', 'Rejected')",
                'default' => 'Valid'
            )
        );

        $this->dbforge->add_column('gst_details', $fields);

        // Foreign key constraint
        $this->db->query('ALTER TABLE `gst_details` ADD CONSTRAINT `fk_gstin` FOREIGN KEY (`gstin`) REFERENCES `gst_auth_tokens`(`gstin`) ON DELETE CASCADE');
    }

    public function down()
    {
        // Drop added columns
        $this->dbforge->drop_column('gst_details', 'eway_bill_no');
        $this->dbforge->drop_column('gst_details', 'eway_bill_date');
        $this->dbforge->drop_column('gst_details', 'eway_bill_status');
        $this->dbforge->drop_column('gst_details', 'irn');
        $this->dbforge->drop_column('gst_details', 'irn_status');

        // Remove foreign key constraint
        $this->db->query('ALTER TABLE `gst_details` DROP FOREIGN KEY `fk_gstin`');
    }
}
