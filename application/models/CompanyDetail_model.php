<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class CompanyDetail_model extends App_Model
{
    private $contact_columns;

    public function __construct()
    {
        parent::__construct();

       
    }

    /**
     * Get client object based on passed clientid if not passed clientid return array of all clients
     * @param  mixed $id    client id
     * @param  array  $where
     * @return mixed
     */
    
    public function get() {
        log_message('info', 'companydata_get: API request received.');

        try {
            // Fetch all data from the 'options' table
            $options = $this->db->get(db_prefix() . 'options')->result_array();

            // Fetch custom fields for "company"
            $this->db->select('tblcustomfields.id, tblcustomfields.fieldto, tblcustomfields.name, tblcustomfieldsvalues.value');
            $this->db->from('tblcustomfields');
            $this->db->join('tblcustomfieldsvalues', 'tblcustomfields.id = tblcustomfieldsvalues.fieldid', 'inner');
            $this->db->where('tblcustomfields.fieldto', 'company'); // Filter for 'company' fields
            $this->db->order_by('tblcustomfields.name', 'ASC'); // Optional: Order by name
            $result = $this->db->get()->result_array();

        

            if ($options || $result) {
                log_message('info', 'companydata_get: Data retrieved successfully.');

                // Helper function to fetch values from arrays
                $get_option_value = function ($array, $key) {
                    foreach ($array as $item) {
                        if ($item['name'] === $key) {
                            return $item['value'];
                        }
                    }
                    return null;
                };

                $get_custom_field_value = function ($array, $key) {
                    foreach ($array as $item) {
                        if ($item['name'] === $key) {
                            return $item['value'];
                        }
                    }
                    return null;
                };

                // Calculate the current financial year
                $current_month = date('m');
                $current_year = date('Y');
                $financial_year_start = $current_month >= 4 ? $current_year : $current_year - 1;
                $financial_year_end = $current_month >= 4 ? $current_year + 1 : $current_year;

                // Map the data into a flat structure with name and value
                $company_detail = [
                    "invoice_company_name" => $get_option_value($options, 'invoice_company_name'),
                    "invoice_company_address" => $get_option_value($options, 'invoice_company_address'),
                    "area" => $get_custom_field_value($result, 'area'),
                    "invoice_company_city" => $get_option_value($options, 'invoice_company_city'),
                    "invoice_company_state" => $get_option_value($options, 'invoice_company_city'),
                    "email_signature" => $get_option_value($options, 'email_signature'),
                    "smtp_email" => $get_option_value($options, 'smtp_email'),
                    "invoice_company_country_code" => $get_option_value($options, 'invoice_company_country_code'),
                    "invoice_company_postal_code" => $get_option_value($options, 'invoice_company_postal_code'),
                    "invoice_company_phonenumber" => $get_option_value($options, 'invoice_company_phonenumber'),
                    "default_timezone" => $get_option_value($options, 'default_timezone'),
                    "company_logo" => $get_option_value($options, 'company_logo'),
                    "incomeTaxNumber" => $get_custom_field_value($result, 'incomeTaxNumber'),
                    "saleTaxNumber" => $get_custom_field_value($result, 'saleTaxNumber'),
                    "gstin" => $get_custom_field_value($result, 'gstin'),
                    "booksFrom" => $financial_year_start . '-' . ($financial_year_start + 1),
                    "startingFrom" => $financial_year_start . '-' . ($financial_year_start + 1),
                    "balance" => $get_custom_field_value($result, 'balance'),
                    "invoice_prefix" => $get_option_value($options, 'invoice_prefix'),
                    "next_invoice_number" => $get_option_value($options, 'next_invoice_number'),
                    "estimate_prefix" => $get_option_value($options, 'estimate_prefix'),
                    "next_estimate_number" => $get_option_value($options, 'next_estimate_number'),
                    "dateformat" => $get_option_value($options, 'dateformat')
                ];

                return ['status' => true, 'company_detail' => $company_detail];
            } else {
                log_message('warning', 'companydata_get: No data found in the tables.');
                return ['status' => false, 'message' => 'No data were found'];
            }
        } catch (Exception $e) {
            log_message('error', 'companydata_get: An error occurred. Message: ' . $e->getMessage());
            return ['status' => false, 'message' => 'An unexpected error occurred'];
        }
    }





private function get_option_value($data, $key) {
    foreach ($data as $item) {
        if ($item['name'] === $key) {
            return $item['value'];
        }
    }
    return null;
}

    public function get_company_by_id($id) {
        return $this->db->get_where(db_prefix() . 'options', ['id' => $id])->row_array();
    }

    public function add_company($data) {
        $this->db->insert(db_prefix() . 'options', $data);
        return $this->db->insert_id();
    }

    public function update_company($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'options', $data);
    }

    public function delete_company($id) {
        $this->db->where('id', $id);
        return $this->db->delete(db_prefix() . 'options');
    }   
}