<?php

defined('BASEPATH') or exit('No direct script access allowed');

class GSTAPI extends CI_Controller
{
    private $apiUrlEWayBill = 'https://ewaybill.nic.in/api/';
    private $apiUrlEInvoice = 'https://einvoice.nic.in/api/';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('contracts_model');
        $this->load->model('auth_model');
        $this->load->model('invoices_model');
        $this->load->helper('url');
        $this->load->library('logger');
    }


     // 📌 AUTHENTICATION ENDPOINTS
     public function authenticate($type)
     {
         $gstin = $this->input->post('gstin');
         $authUrl = ($type == 'eway') ? $this->apiUrlEWayBill . 'Auth' : $this->apiUrlEInvoice . 'eivital/v1.04/auth';
 
         $response = $this->apiRequest($authUrl, ['gstin' => $gstin]);
 
         if (isset($response['auth_token'])) {
             $this->gst_model->updateToken($gstin, $response['auth_token'], date('Y-m-d H:i:s', strtotime('+12 hours')), $type);
             return $response['auth_token'];
         } else {
             return false;
         }
     }
    /**
     * Get authentication token for GST APIs
     * @param string $gstin - GST Identification Number
     * @param string $type - API type ('eway' or 'einv')
     */
    public function getAuthToken($gstin, $type)
    {

        // Sanitize input
        $gstin = htmlspecialchars(strip_tags($gstin));
        
        // Check for existing valid token
        $tokenData = $this->auth_model->getValidToken($gstin, $type);

        if ($tokenData) {
            $this->outputJSON(['token' => $tokenData[$type . '_auth_token']]);
            return;
        }

        // Get new token from appropriate API
        $authUrl = ($type == 'eway') ? $this->apiUrlEWayBill . 'auth' : $this->apiUrlEInvoice . 'auth';
        $apiResponse = $this->apiRequest($authUrl, ['gstin' => $gstin]);

        if (isset($apiResponse['auth_token'])) {
            // Store token with 12-hour validity
            $this->auth_model->updateToken($gstin, $apiResponse['auth_token'], date('Y-m-d H:i:s', strtotime('+12 hours')), $type);
            $this->outputJSON(['token' => $apiResponse['auth_token']]);
        } else {
            // $this->outputJSON(['error' => 'Failed to fetch token'], 400);
            $this->auth_model->updateToken($gstin, "dummy_190734", date('Y-m-d H:i:s', strtotime('+12 hours')), $type);
            $this->outputJSON(['token' => "dummy_190734"]);
        }
    }

    /**
     * Generate E-Way Bill
     * Handles POST request with invoice and transport details
     */
    public function generateEWayBill()
    {
        $gstin = htmlspecialchars(strip_tags($this->input->post('gstin')));
        $token = $this->auth_model->getValidToken($gstin, 'eway')['eway_auth_token'] ?? null;

        if (!$token) {
            $this->outputJSON(['error' => 'Unauthorized access'], 401);
            return;
        }

        $ewayData = [
            "invoiceNumber" => htmlspecialchars(strip_tags($this->input->post('invoiceNumber'))),
            "transporterName" => htmlspecialchars(strip_tags($this->input->post('transporterName'))),
            "vehicleNumber" => htmlspecialchars(strip_tags($this->input->post('vehicleNumber')))
        ];

        $response = $this->apiRequest($this->apiUrlEWayBill . 'generateEWayBill', $ewayData, $token);
        $this->outputJSON($response);
    }

    /**
     * Get E-Way Bill details
     * @param string $ewayBillNo - E-Way Bill number
     */
    public function getEWayBill($ewayBillNo)
    {
        $ewayBillNo = htmlspecialchars(strip_tags($ewayBillNo));
        $gstin = htmlspecialchars(strip_tags($this->input->get('gstin')));
        $token = $this->auth_model->getValidToken($gstin, 'eway')['eway_auth_token'] ?? null;

        if (!$token) {
            $this->outputJSON(['error' => 'Unauthorized access'], 401);
            return;
        }

        $response = $this->apiRequest($this->apiUrlEWayBill . "getEWayBill/$ewayBillNo", [], $token, 'GET');
        $this->outputJSON($response);
    }

    /**
     * Cancel E-Way Bill
     * @param string $ewayBillNo - E-Way Bill number to cancel
     */
    public function cancelEWayBill($ewayBillNo)
    {
        $ewayBillNo = htmlspecialchars(strip_tags($ewayBillNo));
        $gstin = htmlspecialchars(strip_tags($this->input->get('gstin')));
        $token = $this->auth_model->getValidToken($gstin, 'eway')['eway_auth_token'] ?? null;

        if (!$token) {
            $this->outputJSON(['error' => 'Unauthorized access'], 401);
            return;
        }

        $response = $this->apiRequest($this->apiUrlEWayBill . "cancelEWayBill/$ewayBillNo", [], $token, 'DELETE');
        $this->outputJSON($response);
    }

    /**
     * Generate E-Invoice with enhanced validation and error handling
     */
    /*
     public function generateEInvoice()
     {
         $this->load->model('Invoice_model');
     
         // Fetch the next invoice number from database settings
         $next_invoice_number = get_option('next_invoice_number');
     
         // Generate dummy data
         $data = [
             'next_invoice_number' => $next_invoice_number,
             'e_invoice_number' => "EINV-" . rand(100000, 999999),
             'irn' => "IRN-" . rand(100000, 999999),
             'qr_code' => "Sample QR Code Data " . time(),
             'einvoice_status' => 'Pending',
             'eway_bill_number' => "EWB-" . rand(100000, 999999),
             'is_e_invoice' => 1
         ];
        
         $result = $this->Invoice_model->insertInvoice($data);
     
         if ($result) {
             // Increment next_invoice_number for the next entry
             update_option('next_invoice_number', $next_invoice_number + 1);
     
             echo json_encode(['status' => 'success', 'message' => 'E-Invoice generated successfully with dummy data.']);
         } else {
             echo json_encode(['status' => 'error', 'message' => 'Failed to generate E-Invoice.']);
         }
     }
    */
    
    public function generateEInvoice()
    {
        $this->load->model('Invoice_model');
    
        // Fetch the next invoice number from database settings
        $next_invoice_number = get_option('next_invoice_number');
    
        // Generate invoice data using a helper function
        $data = $this->generateDummyInvoiceData($next_invoice_number);
    
        // Insert invoice data into the database
        $result = $this->Invoice_model->insertInvoice($data);
    
        if ($result) {
            // Increment the next invoice number
            update_option('next_invoice_number', $next_invoice_number + 1);
    
            echo json_encode(['status' => 'success', 'message' => 'E-Invoice generated successfully with dummy data.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to generate E-Invoice.']);
        }
    }
    
    /**
     * Generates dummy invoice data.
     *
     * @param int $invoice_number The next invoice number.
     * @return array The generated invoice data.
     */
    private function generateDummyInvoiceData($invoice_number)
    {
        return [
            'next_invoice_number' => $invoice_number,
            'e_invoice_number' => "EINV-" . rand(100000, 999999),
            'irn' => "IRN-" . rand(100000, 999999),
            'qr_code' => "Sample QR Code Data " . time(),
            'einvoice_status' => 'Pending',
            'eway_bill_number' => "EWB-" . rand(100000, 999999),
            'is_e_invoice' => 1
        ];
    }
    

    public function saveEInvoice() {
        $this->load->model('EInvoice_model'); // Load Model
    
        // Get JSON input
        $input_data = json_decode(file_get_contents("php://input"), true);
    
        // Validate required fields
        if (!isset($input_data['einvoiceNumber']) || empty($input_data['einvoiceNumber'])) {
            $this->outputJSON(['success' => false, 'error' => "Missing required data"], 400);
            return;
        }
    
        // Prepare data for insertion
        $invoiceData = [
            'e_invoice_number' => $input_data['einvoiceNumber'],
            'irn' => $input_data['irn'] ?? null,
            'qr_code' => $input_data['qrCode'] ?? null,
            'einvoice_status' => 'Submitted',
            'acknowledgement_number' => $input_data['acknowledgementNumber'] ?? null,
            'acknowledgement_date' => date('Y-m-d H:i:s'),
            'is_e_invoice' => 1
        ];
    
        // Insert into database
        $result = $this->EInvoice_model->insertEInvoice($invoiceData);
    
        if ($result) {
            $this->outputJSON(['success' => true, 'message' => 'E-Invoice data saved successfully!']);
        } else {
            $this->outputJSON(['success' => false, 'error' => 'Database insertion failed.']);
        }
    }

    
    private function apiRequest($url, $data, $token) {
        $headers = [
            "Authorization: Bearer " . $token,
            "Content-Type: application/json"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($http_code == 200) ? json_decode($response, true) : false;
    }

   
    


    /**
     * Make API request to GST endpoints
     * @param string $url - API endpoint URL
     * @param array $postData - Request data
     * @param string $token - Authentication token
     * @param string $method - HTTP method
     * @return array Response data
     */
    private function apiRequest($url, $postData = [], $token = null, $method = 'POST')
    {
        $ch = curl_init($url);
        $headers = ['Content-Type: application/json'];
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        } elseif ($method === 'GET') {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            log_message('error', 'CURL Error: ' . $curlError);
            return ['error' => 'API request failed', 'details' => $curlError];
        }

        if ($httpCode !== 200) {
            log_message('error', "API Error: HTTP Code $httpCode");
            return ['error' => "API request failed with HTTP Code $httpCode"];
        }

        return json_decode($response, true);
    }

    /**
     * Output JSON response
     * @param mixed $data - Response data
     * @param int $statusCode - HTTP status code
     */
    private function outputJSON($data, $statusCode = 200)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($statusCode)
            ->set_output(json_encode($data));
    }
}