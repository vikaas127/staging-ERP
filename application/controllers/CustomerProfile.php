<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerProfile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    public function submit_customer_profile_form() {
        // Validate form inputs
        $this->form_validation->set_rules('customer_name', 'Customer Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required|numeric|trim');
        $this->form_validation->set_rules('vat', 'VAT Number', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            // Return validation errors
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        // Get form data
        $data = [
            'customer_name' => $this->input->post('customer_name'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'vat' => $this->input->post('vat')
        ];

        // Insert into database
        $insert = $this->db->insert('customers', $data);

        if ($insert) {
            echo json_encode([
                'success' => true,
                'message' => 'Customer profile added successfully!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Database error: Could not save the data.'
            ]);
        }
    }
}
