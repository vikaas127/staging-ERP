<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Eway_bills_view extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Eway_bills_model');
    }

    public function save_eway_account() {
        $this->save_api_account('eway_bills_api_accounts');
    }

    public function save_einvoice_account() {
        $this->save_api_account('einvoice_api_accounts');
    }

    private function save_api_account($table) {
        $gst_number = $this->input->post('gst_number');
        $api_username = $this->input->post('api_username');
        $api_password = $this->input->post('api_password');

        if (!$gst_number || !$api_username || !$api_password) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            return;
        }

        $data = [
            'gst_number' => $gst_number,
            'api_username' => $api_username,
            'api_password' => password_hash($api_password, PASSWORD_BCRYPT)
        ];

        $inserted = $this->Eway_bills_model->insert_api_account($table, $data);

        echo json_encode(['success' => $inserted, 'message' => $inserted ? 'API account added successfully.' : 'Failed to add API account.']);
    }
}
