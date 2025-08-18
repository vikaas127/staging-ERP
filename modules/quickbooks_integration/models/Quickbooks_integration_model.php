<?php

defined('BASEPATH') or exit('No direct script access allowed');

spl_autoload_register(function ($class) {
  $prefix = 'QuickBooksOnline\\API\\';
    $base_dir = module_dir_path(QUICKBOOKS_INTEGRATION_MODULE_NAME).'/assets/plugins/QuickBooks-V3-PHP-SDK-master/src/';
    $len = strlen($prefix);
    
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $filewithoutExtension = $base_dir . str_replace('\\', '/', $relative_class);
    $file =  $filewithoutExtension. '.php';
    //Below str_replace is for local testing. Remove it before putting on Composer.
    if (file_exists($file) ) {
        require ($file);
    }
});

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer as Quickbook_customer;
use QuickBooksOnline\API\Facades\Invoice as Quickbook_invoice;
use QuickBooksOnline\API\Facades\Payment as Quickbook_payment;
use QuickBooksOnline\API\Facades\Purchase as Quickbook_purchase;

/**
 * This class describes a quickbooks integration model.
 */
class Quickbooks_integration_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * update general setting
     *
     * @param      array   $data   The data
     *
     * @return     boolean
     */
    public function update_setting($data)
    {
        $affectedRows = 0;
        if (!isset($data['settings']['acc_integration_quickbooks_active'])) {
            $data['settings']['acc_integration_quickbooks_active'] = 0;
        }

        if (!isset($data['settings']['acc_integration_quickbooks_sync_from_system'])) {
            $data['settings']['acc_integration_quickbooks_sync_from_system'] = 0;
        }

        if (!isset($data['settings']['acc_integration_quickbooks_sync_to_system'])) {
            $data['settings']['acc_integration_quickbooks_sync_to_system'] = 0;
        }


        if (isset($data['settings']['acc_integration_quickbooks_client_id'])) {
            $data['settings']['acc_integration_quickbooks_client_id'] = $this->encryption->encrypt($data['settings']['acc_integration_quickbooks_client_id']);
        }

        if (isset($data['settings']['acc_integration_quickbooks_client_secret'])) {
            $data['settings']['acc_integration_quickbooks_client_secret'] = $this->encryption->encrypt($data['settings']['acc_integration_quickbooks_client_secret']);
        }

        foreach ($data['settings'] as $key => $value) {
            if (update_option($key, $value)) {
                $affectedRows++;
            }
        }

        if ($affectedRows > 0) {
            return true;
        }
        return false;
    }

    public function init_quickbook_config(){
        $client_id = get_option('acc_integration_quickbooks_client_id');
        $client_secret = get_option('acc_integration_quickbooks_client_secret');
        $code = get_option('acc_integration_quickbooks_code');
        $QBORealmID = get_option('acc_integration_quickbooks_realmId');
        $refreshTokenKey = get_option('acc_integration_quickbooks_refresh_token');
        $access_token_expires = get_option('acc_integration_quickbooks_access_token_expires');

        // Prep Data Services
        $this->dataService = DataService::Configure(array(
            'auth_mode'       => 'oauth2',
            'ClientID' => $this->encryption->decrypt($client_id), 
            'ClientSecret' => $this->encryption->decrypt($client_secret), 
            'accessTokenKey'  => get_option('acc_integration_quickbooks_access_token'),
            'refreshTokenKey' => $refreshTokenKey,
            'QBORealmID'      => $QBORealmID,
            'RedirectURI' => "https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl",
            'scope' => "com.intuit.quickbooks.accounting",
            'baseUrl' => "development"
        ));

        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        if($access_token_expires <= time()){
            $accessToken = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshTokenKey);

            update_option('acc_integration_quickbooks_access_token', $accessToken->getAccessToken());
            update_option('acc_integration_quickbooks_refresh_token', $accessToken->getRefreshToken());
            update_option('acc_integration_quickbooks_access_token_expires', strtotime($accessToken->getAccessTokenExpiresAt()));
            $this->dataService->updateOAuth2Token($accessToken);
            $this->dataService->throwExceptionOnError(true);
        }
    }

    public function create_quickbook_customer($customer_id = ''){
        $this->db->select('*, '.db_prefix() . 'clients.userid as userid');
        if($customer_id != ''){
            $this->db->where(db_prefix().'clients.userid', $customer_id);
        }

        $this->db->join(db_prefix() . 'contacts', db_prefix() . 'contacts.userid=' . db_prefix() . 'clients.userid AND '.db_prefix() . 'contacts.is_primary = "1"', 'left');
        $this->db->join(db_prefix() . 'acc_integration_logs', db_prefix() . 'acc_integration_logs.rel_id=' . db_prefix() . 'clients.userid AND '.db_prefix() . 'acc_integration_logs.rel_type = "customer" AND software = "quickbook"', 'left');
        $clients = $this->db->get(db_prefix().'clients')->result_array();

        $customer_data = [];
        $entities = $this->dataService->Query("SELECT * FROM Customer");
        $error = $this->dataService->getLastError();
        if ($error) {
            
        } else {
            foreach ($entities as $customer) {
                $customer_data[$customer->Id] = $customer;
            }
        }

        foreach ($clients as $client) {
            if($client['connect_id'] != '' && isset($customer_data[$client['connect_id']])){
                $DisplayName = $client['firstname'] .' '.$client['lastname'];
                if($DisplayName == ''){
                    $DisplayName = $client['company'];
                }

                $arr = [
                  "BillAddr" => [
                     "Line1"=>  $client['billing_street'],
                     "City"=>  $client['billing_city'],
                     "Country"=> $client['billing_country'],
                     "PostalCode"=>  $client['billing_zip'],
                 ],
                 "FullyQualifiedName"=>  $client['company'],
                 "CompanyName"=>  $client['company'],
                 "DisplayName"=>  $DisplayName,
                 "PrimaryPhone"=>  [
                     "FreeFormNumber"=>  $client['phonenumber'],
                 ],
                 "PrimaryEmailAddr"=>  [
                     "Address"=>  $client['email'],
                 ],
                ];

                $customerObj = Quickbook_customer::update($customer_data[$client['connect_id']], $arr);
                $resultingCustomerObj = $this->dataService->Update($customerObj);

            }else{
                if($client['connect_id'] != ''){
                    $this->delete_integration_log($client['userid'], 'customer', 'quickbook');
                }

                $DisplayName = $client['firstname'] .' '.$client['lastname'];
                if($DisplayName == ''){
                    $DisplayName = $client['company'];
                }

                $arr = [
                  "BillAddr" => [
                     "Line1"=>  $client['billing_street'],
                     "City"=>  $client['billing_city'],
                     "Country"=> $client['billing_country'],
                     "CountrySubDivisionCode"=>  "",
                     "PostalCode"=>  $client['billing_zip'],
                 ],
                 "Title"=>  "",
                 "GivenName"=>  "",
                 "MiddleName"=>  "",
                 "FamilyName"=>  "",
                 "Suffix"=>  "",
                 "FullyQualifiedName"=>  $client['company'],
                 "CompanyName"=>  $client['company'],
                 "DisplayName"=>  $DisplayName,
                 "PrimaryPhone"=>  [
                     "FreeFormNumber"=>  $client['phonenumber'],
                 ],
                 "PrimaryEmailAddr"=>  [
                     "Address" => $client['email'],
                 ]
                ];

                $customerObj = Quickbook_customer::create($arr);
                $resultingCustomerObj = $this->dataService->Add($customerObj);
            }

            $error = $this->dataService->getLastError();
            
            $this->delete_integration_error_log($client['userid'], 'customer', 'quickbook');
            if ($error) {
                $sync_status = 0;
                $connect_id = '';

                $this->db->insert(db_prefix().'acc_integration_error_logs', [
                    'rel_id' => $client['userid'],
                    'rel_type' => 'customer',
                    'software' => 'quickbook',
                    'error_detail' => $error->getIntuitErrorDetail(),
                    'date_updated' => date('Y-m-d H:i:s'),
                ]);

                $this->db->insert(db_prefix().'acc_integration_sync_logs', [
                    'rel_id' => $client['userid'],
                    'rel_type' => 'customer',
                    'software' => 'quickbook',
                    'type' => 'sync_up',
                    'status' => $sync_status,
                    'connect_id' => $connect_id,
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);

                if($customer_id != ''){
                    return $error->getIntuitErrorDetail();
                }
            } else {
                $sync_status = 1;
                $connect_id = $resultingCustomerObj->Id;

                if(!isset($customer_data[$client['connect_id']])){
                    $this->db->insert(db_prefix().'acc_integration_logs', [
                        'rel_id' => $client['userid'],
                        'rel_type' => 'customer',
                        'software' => 'quickbook',
                        'connect_id' => $resultingCustomerObj->Id,
                        'date_updated' => date('Y-m-d H:i:s'),
                    ]);
                }

                $this->db->insert(db_prefix().'acc_integration_sync_logs', [
                    'rel_id' => $client['userid'],
                    'rel_type' => 'customer',
                    'software' => 'quickbook',
                    'type' => 'sync_up',
                    'status' => $sync_status,
                    'connect_id' => $connect_id,
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);

                if($customer_id != ''){
                    return true;
                }
            }

            
        }
    }

    public function create_quickbook_invoice($invoice_id = ''){
        $this->db->select('*, ' . db_prefix() . 'currencies.id as currencyid, ' . db_prefix() . 'invoices.id as id, ' . db_prefix() . 'currencies.name as currency_name');
        if($invoice_id != ''){
            $this->db->where(db_prefix().'invoices.id', $invoice_id);
        }
        $this->db->join(db_prefix() . 'currencies', '' . db_prefix() . 'currencies.id = ' . db_prefix() . 'invoices.currency', 'left');
        $this->db->join(db_prefix() . 'acc_integration_logs', db_prefix() . 'acc_integration_logs.rel_id=' . db_prefix() . 'invoices.id AND '.db_prefix() . 'acc_integration_logs.rel_type = "invoice" AND software = "quickbook"', 'left');

        $invoices = $this->db->get(db_prefix().'invoices')->result_array();

        $invoice_data = [];
        $entities = $this->dataService->Query("SELECT * FROM Invoice");
        $error = $this->dataService->getLastError();
        if ($error) {
            
        } else {
            foreach ($entities as $invoice) {
                $invoice_data[$invoice->Id] = $invoice;
            }
        }

        foreach ($invoices as $invoice) {
            $items  = get_items_by_type('invoice', $invoice['id']);
            $item_array = [];

            $tax_amount = 0;
            foreach ($items as $item) {
                $data_tax = $this->get_invoice_item_tax($item, $invoice);

                foreach ($data_tax['tax_amount'] as $k => $amount) {
                    $tax_amount += $amount;
                }

                $item_array[] = [
                    'Amount' => $item['rate'] * $item['qty'],
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => [
                        "Qty" => $item['qty'],
                        "UnitPrice" => $item['rate'],
                    ],

                    'Description' => $item['description'],
                ];
            }

            $customer_connect_id = $this->get_connect_id($invoice['clientid'], 'customer');

            if($tax_amount > 0){
                $item_array[] = [
                    'Amount' => $tax_amount,
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => [
                        "Qty" => 1,
                        "UnitPrice" => $tax_amount,
                    ],

                    'Description' => 'Total Tax',
                ];
            }

            if($invoice['adjustment'] != 0){
                $item_array[] = [
                    'Amount' => $invoice['adjustment'],
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => [
                        "Qty" => 1,
                        "UnitPrice" => $invoice['adjustment'],
                    ],
                    'Description' => 'Adjustment',
                ];
            }

            if($invoice['discount_total'] > 0){
                $item_array[] = [
                    'Amount' => $invoice['discount_total'],
                    "DetailType" => "DiscountLineDetail",
                    "DiscountLineDetail" => [
                        "PercentBased"=> false,
                    ],
                ];
            }

            $discount_total = 0;
            if($invoice['discount_total'] > 0){
                $discount_total = $invoice['discount_total'];
            }

            $ApplyTaxAfterDiscount = true;


            if($invoice['connect_id'] != '' && isset($invoice_data[$invoice['connect_id']])){
                $arr = [
                    "Line" => $item_array,
                    "CustomerRef"=> [
                          "value"=> $customer_connect_id
                        ],
                    "TxnDate" => $invoice['date'],
                    "DueDate" => $invoice['duedate'],
                    "DiscountAmt" => $discount_total,
                    "ApplyTaxAfterDiscount" => $ApplyTaxAfterDiscount
                ];

                $invoiceObj = Quickbook_invoice::update($invoice_data[$invoice['connect_id']], $arr);
                $resultingCustomerObj = $this->dataService->Update($invoiceObj);

            }else{
                if($invoice['connect_id'] != ''){
                    $this->delete_integration_log($invoice['id'], 'invoice', 'quickbook');
                }

                $invoiceObj = Quickbook_invoice::create([
                    "Line" => $item_array,
                    "CustomerRef"=> [
                          "value"=> $customer_connect_id
                        ],
                    "TxnDate" => $invoice['date'],
                    "DueDate" => $invoice['duedate'],
                    "DiscountAmt" => $discount_total,
                    "ApplyTaxAfterDiscount" => $ApplyTaxAfterDiscount
                ]);

                $resultingInvoiceObj = $this->dataService->Add($invoiceObj);
            }
            $error = $this->dataService->getLastError();
            $this->delete_integration_error_log($invoice['id'], 'invoice', 'quickbook');
            if ($error) {
                $sync_status = 0;
                $connect_id = '';

                $this->db->insert(db_prefix().'acc_integration_error_logs', [
                    'rel_id' => $invoice['id'],
                    'rel_type' => 'invoice',
                    'software' => 'quickbook',
                    'error_detail' => $error->getIntuitErrorDetail(),
                    'date_updated' => date('Y-m-d H:i:s'),
                ]);

                $this->db->insert(db_prefix().'acc_integration_sync_logs', [
                    'rel_id' => $invoice['id'],
                    'rel_type' => 'invoice',
                    'software' => 'quickbook',
                    'type' => 'sync_up',
                    'status' => $sync_status,
                    'connect_id' => $connect_id,
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);

                if($invoice_id != ''){
                    return $error->getIntuitErrorDetail();
                }
            } else {
                $sync_status = 1;
                $connect_id = $resultingCustomerObj->Id;

                if(!isset($invoice_data[$invoice['connect_id']])){
                    $this->db->insert(db_prefix().'acc_integration_logs', [
                        'rel_id' => $invoice['id'],
                        'rel_type' => 'invoice',
                        'software' => 'quickbook',
                        'connect_id' => $resultingInvoiceObj->Id,
                        'date_updated' => date('Y-m-d H:i:s'),
                    ]);
                }

                $this->db->insert(db_prefix().'acc_integration_sync_logs', [
                    'rel_id' => $invoice['id'],
                    'rel_type' => 'invoice',
                    'software' => 'quickbook',
                    'type' => 'sync_up',
                    'status' => $sync_status,
                    'connect_id' => $connect_id,
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);

                if($invoice_id != ''){
                    return true;
                }
            }

            
        }
    }

    public function create_quickbook_payment($payment_id = ''){
        $this->db->select('*, ' . db_prefix() . 'invoicepaymentrecords.id as id');
        if($payment_id != ''){
            $this->db->where(db_prefix().'invoicepaymentrecords.id', $payment_id);
        }
        $this->db->join(db_prefix() . 'acc_integration_logs', db_prefix() . 'acc_integration_logs.rel_id=' . db_prefix() . 'invoicepaymentrecords.id AND '.db_prefix() . 'acc_integration_logs.rel_type = "payment" AND software = "quickbook"', 'left');

        $payments = $this->db->get(db_prefix().'invoicepaymentrecords')->result_array();

        $payment_data = [];
        $entities = $this->dataService->Query("SELECT * FROM Payment");
        $error = $this->dataService->getLastError();
        if ($error) {
            
        } else {
            foreach ($entities as $payment) {
                $payment_data[$payment->Id] = $payment;
            }
        }

        foreach ($payments as $payment) {
            $this->db->where('id', $payment['invoiceid']);
            $invoice = $this->db->get(db_prefix().'invoices')->row();
            $invoice_connect_id = $this->get_connect_id($payment['invoiceid'], 'invoice');
            $customer_connect_id = $this->get_connect_id($invoice->clientid, 'customer');

            if($payment['connect_id'] != '' && isset($payment_data[$payment['connect_id']])){
                $arr = [
                    "Line" => [
                        "Amount" => $payment['amount'],
                        "LinkedTxn" => [
                          [
                            "TxnId" => $invoice_connect_id, 
                            "TxnType" => "Invoice"
                          ]
                        ]
                    ],
                    "CustomerRef"=> [
                          "value"=> $customer_connect_id
                        ],
                    "TxnDate" => $payment['date'],
                    "TotalAmt" => $payment['amount']
                ];

                $paymentObj = Quickbook_payment::update($payment_data[$payment['connect_id']], $arr);
                $resultingCustomerObj = $this->dataService->Update($paymentObj);
            }else{
                if($payment['connect_id'] != ''){
                    $this->delete_integration_log($payment['id'], 'payment', 'quickbook');
                }

                $paymentObj = Quickbook_payment::create([
                    "Line" => [
                        "Amount" => $payment['amount'],
                        "LinkedTxn" => [
                          [
                            "TxnId" => $invoice_connect_id, 
                            "TxnType" => "Invoice"
                          ]
                        ]
                    ],
                    "CustomerRef"=> [
                          "value"=> $customer_connect_id
                        ],
                    "TxnDate" => $payment['date'],
                    "TotalAmt" => $payment['amount']
                ]);
                $resultingPaymentObj = $this->dataService->Add($paymentObj);
            }
            $error = $this->dataService->getLastError();
            $this->delete_integration_error_log($payment['id'], 'payment', 'quickbook');
            if ($error) {
                $sync_status = 0;
                $connect_id = '';

                $this->db->insert(db_prefix().'acc_integration_error_logs', [
                    'rel_id' => $payment['id'],
                    'rel_type' => 'payment',
                    'software' => 'quickbook',
                    'error_detail' => $error->getIntuitErrorDetail(),
                    'date_updated' => date('Y-m-d H:i:s'),
                ]);

                $this->db->insert(db_prefix().'acc_integration_sync_logs', [
                    'rel_id' => $payment['id'],
                    'rel_type' => 'payment',
                    'software' => 'quickbook',
                    'type' => 'sync_up',
                    'status' => $sync_status,
                    'connect_id' => $connect_id,
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);

                if($payment_id != ''){
                    return $error->getIntuitErrorDetail();
                }
            } else {
                $sync_status = 1;
                $connect_id = $resultingCustomerObj->Id;

                if(!isset($payment_data[$payment['connect_id']])){
                    $this->db->insert(db_prefix().'acc_integration_logs', [
                        'rel_id' => $payment['id'],
                        'rel_type' => 'payment',
                        'software' => 'quickbook',
                        'connect_id' => $resultingPaymentObj->Id,
                        'date_updated' => date('Y-m-d H:i:s'),
                    ]);
                }

                $this->db->insert(db_prefix().'acc_integration_sync_logs', [
                    'rel_id' => $payment['id'],
                    'rel_type' => 'payment',
                    'software' => 'quickbook',
                    'type' => 'sync_up',
                    'status' => $sync_status,
                    'connect_id' => $connect_id,
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);

                if($payment_id != ''){
                    return true;
                }
            }

            
        }
    }


    public function create_quickbook_expense($expenses_id = ''){
        $this->db->select('*, ' . db_prefix() . 'expenses.id as id');
        if($expenses_id != ''){
            $this->db->where(db_prefix().'expenses.id', $expenses_id);
        }
        $this->db->join(db_prefix() . 'acc_integration_logs', db_prefix() . 'acc_integration_logs.rel_id=' . db_prefix() . 'expenses.id AND '.db_prefix() . 'acc_integration_logs.rel_type = "expense" AND software = "quickbook"', 'left');

        $expenses = $this->db->get(db_prefix().'expenses')->result_array();

        $expense_data = [];
        $entities = $this->dataService->Query("SELECT * FROM Purchase");
        $error = $this->dataService->getLastError();
        if ($error) {
            
        } else {
            foreach ($entities as $expense) {
                $expense_data[$expense->Id] = $expense;
            }
        }

        foreach ($expenses as $expense) {
            $total_tax = 0;

            if($expense['tax'] > 0){
                $this->db->where('id', $expense['tax']);
                $tax = $this->db->get(db_prefix().'taxes')->row();
                if($tax){
                    $total_tax += ($tax->taxrate/100) * $expense['amount'];
                }
            }

            if($expense['tax2'] > 0){
                $this->db->where('id', $expense['tax2']);
                $tax = $this->db->get(db_prefix().'taxes')->row();
                if($tax){
                    $total_tax += ($tax->taxrate/100) * $expense['amount'];
                }
            }

            $line = [];
            $line[] = [
                "Amount" => $expense['amount'],
                "DetailType"=> "AccountBasedExpenseLineDetail",
                "Description" => $expense['expense_name'],
                "AccountBasedExpenseLineDetail"=> [
                    "AccountRef"=> [
                        "name"=> "Job Expenses",
                        "value"=> "58"
                    ]
                ]
            ];

            if($total_tax > 0){
                $line[] = [
                    "Amount" => $total_tax,
                    "DetailType"=> "AccountBasedExpenseLineDetail",
                    "Description" => 'Total Tax',
                    "AccountBasedExpenseLineDetail"=> [
                        "AccountRef"=> [
                            "name"=> "Job Expenses",
                            "value"=> "58"
                        ]
                    ]
                ];
            }

            if($expense['connect_id'] != '' && isset($expense_data[$expense['connect_id']])){
                $arr = [
                    "Line" => $line,
                    "TxnDate" => $expense['date'],
                    "TotalAmt" => round($expense['amount'] + $total_tax, 2)
                ];

                $expenseObj = Quickbook_purchase::update($expense_data[$expense['connect_id']], $arr);
                $resultingCustomerObj = $this->dataService->Update($expenseObj);
            }else{
                if($expense['connect_id'] != ''){
                    $this->delete_integration_log($expense['id'], 'expense', 'quickbook');
                }

                $expenseObj = Quickbook_purchase::create([
                    "PaymentType" => "Cash",
                    "AccountRef" => [
                        "value"=> "35",
                        "name"=> "Checking"
                    ],
                    "Line" => $line,
                    "TxnDate" => $expense['date'],
                    "TotalAmt" => round($expense['amount'] + $total_tax, 2)
                ]);

                $resultingObj = $this->dataService->Add($expenseObj);
            }

            $error = $this->dataService->getLastError();
            $this->delete_integration_error_log($expense['id'], 'expense', 'quickbook');
            if ($error) {
                $sync_status = 0;
                $connect_id = '';

                $this->db->insert(db_prefix().'acc_integration_error_logs', [
                    'rel_id' => $expense['id'],
                    'rel_type' => 'expense',
                    'software' => 'quickbook',
                    'error_detail' => $error->getIntuitErrorDetail(),
                    'date_updated' => date('Y-m-d H:i:s'),
                ]);

                $this->db->insert(db_prefix().'acc_integration_sync_logs', [
                    'rel_id' => $expense['id'],
                    'rel_type' => 'expense',
                    'software' => 'quickbook',
                    'type' => 'sync_up',
                    'status' => $sync_status,
                    'connect_id' => $connect_id,
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);

                if($expenses_id != ''){
                    return $error->getIntuitErrorDetail();
                }
            } else {
                $sync_status = 1;
                $connect_id = $resultingCustomerObj->Id;

                if(!isset($expense_data[$expense['connect_id']])){
                    $this->db->insert(db_prefix().'acc_integration_logs', [
                        'rel_id' => $expense['id'],
                        'rel_type' => 'expense',
                        'software' => 'quickbook',
                        'connect_id' => $resultingObj->Id,
                        'date_updated' => date('Y-m-d H:i:s'),
                    ]);
                }

                $this->db->insert(db_prefix().'acc_integration_sync_logs', [
                    'rel_id' => $expense['id'],
                    'rel_type' => 'expense',
                    'software' => 'quickbook',
                    'type' => 'sync_up',
                    'status' => $sync_status,
                    'connect_id' => $connect_id,
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);

                if($expenses_id != ''){
                    return true;
                }
            }

            
        }
    }

    public function get_quickbook_customer(){
            
        $entities = $this->dataService->Query("SELECT * FROM Customer");

        $customer_list = $this->clients_model->get();
     
        $customer_arr = [];
        foreach ($customer_list as $customer) {
            $customer_arr[$customer['userid']] = $customer;
        }

        $error = $this->dataService->getLastError();
        if ($error) {
            
        } else {
            foreach ($entities as $customer) {
                $customer_data = [];
                $customer_data['company'] = $customer->CompanyName ?? $customer->DisplayName;
                $customer_data['website'] = $customer->WebAddr ? $customer->WebAddr->URI : '';
                $customer_data['phonenumber'] = $customer->Mobile ? $customer->Mobile->FreeFormNumber : '';

                $customer_data['balance'] = $customer->Balance;
                $customer_data['balance_as_of'] = $customer->OpenBalanceDate;

                $billing_street = '';
                $billing_city = '';
                $billing_zip = '';
                if($customer->BillAddr){
                    $billing_street = $customer->BillAddr->Line1;
                    $billing_city = $customer->BillAddr->City;
                    $billing_zip = $customer->BillAddr->PostalCode;
                }

                $customer_data['billing_street'] = $billing_street;
                $customer_data['billing_city'] = $billing_city;
                $customer_data['billing_state'] = '';
                $customer_data['billing_zip'] = $billing_zip;
                $customer_data['billing_country'] = '';

                $shipping_street = '';
                $shipping_city = '';
                $shipping_zip = '';
                if($customer->ShipAddr){
                    $shipping_street = $customer->ShipAddr->Line1;
                    $shipping_city = $customer->ShipAddr->City;
                    $shipping_zip = $customer->ShipAddr->PostalCode;
                }

                $customer_data['shipping_street'] = $shipping_street;
                $customer_data['shipping_city'] = $shipping_city;
                $customer_data['shipping_state'] = '';
                $customer_data['shipping_zip'] = $shipping_zip;
                $customer_data['shipping_country'] = '';

                $currency = get_currency($customer->CurrencyRef);
                $customer_data['default_currency'] = $currency ? $currency->id : '';

                $check_connect_id = $this->check_connect_id($customer->Id, 'customer');
                if($check_connect_id != 0 && isset($customer_arr[$check_connect_id])){
                    $this->clients_model->update($customer_data, $check_connect_id);
                    $client_id = $check_connect_id;
                }else{
                    if($check_connect_id != 0){
                        $this->delete_integration_log($check_connect_id, 'customer', 'quickbook');
                    }

                    $client_id = $this->clients_model->add($customer_data);
                }
                
                $sync_status = 0;

                if($client_id){
                    $sync_status = 1;

                    if(!isset($customer_arr[$check_connect_id])){
                        if($customer->PrimaryEmailAddr){
                            $contact_data = [];
                            $contact_data['firstname'] = $customer->GivenName ?? $customer->DisplayName;
                            $contact_data['lastname'] = $customer->FamilyName ?? $customer->DisplayName;
                            $contact_data['phonenumber'] = $customer->PrimaryPhone ? $customer->PrimaryPhone->FreeFormNumber : '';
                            $contact_data['email'] = $customer->PrimaryEmailAddr ? $customer->PrimaryEmailAddr->Address : '';
                            $contact_data['title'] = '';
                            $contact_data['direction'] = '';
                            $contact_data['fakeusernameremembered'] = '';
                            $contact_data['fakepasswordremembered'] = '';
                            $contact_data['password'] = '123456@';
                            $contact_data['is_primary'] = 'on';
                            $contact_data['donotsendwelcomeemail'] = 'on';
                            $contact_data['permissions'] = ['1','2','3','4','5','6'];
                            $contact_data['invoice_emails'] = 'invoice_emails';
                            $contact_data['estimate_emails'] = 'estimate_emails';
                            $contact_data['credit_note_emails'] = 'credit_note_emails';
                            $contact_data['project_emails'] = 'project_emails';
                            $contact_data['ticket_emails'] = 'ticket_emails';
                            $contact_data['task_emails'] = 'task_emails';
                            $contact_data['contract_emails'] = 'contract_emails';
                            $this->clients_model->add_contact($contact_data, $client_id);
                        }

                        $this->db->insert(db_prefix().'acc_integration_logs', [
                            'rel_id' => $client_id,
                            'rel_type' => 'customer',
                            'software' => 'quickbook',
                            'connect_id' => $customer->Id,
                            'date_updated' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }

                $this->db->insert(db_prefix().'acc_integration_sync_logs', [
                        'rel_id' => $client_id,
                        'rel_type' => 'customer',
                        'software' => 'quickbook',
                        'type' => 'sync_down',
                        'status' => $sync_status,
                        'connect_id' => $customer->Id,
                        'datecreated' => date('Y-m-d H:i:s'),
                    ]);
            }
        }
    }

    public function get_quickbook_invoice(){
            
        $entities = $this->dataService->Query("SELECT * FROM Invoice");

        $this->load->model('invoices_model');
        $invoice_list = $this->invoices_model->get();
     
        $invoice_arr = [];
        foreach ($invoice_list as $invoice) {
            $invoice_arr[$invoice['id']] = $invoice;
        }

        $error = $this->dataService->getLastError();

        if ($error) {
            
        } else {
            $this->load->model('payment_modes_model');

            $payment_modes = $this->payment_modes_model->get();

            $payment_model_list = [];
            if ($payment_modes) {
                foreach($payment_modes as $payment_mode){
                    $payment_model_list[] = $payment_mode['id'];
                }
            }

            foreach ($entities as $invoice) {
                $check_connect_id = $this->check_connect_id($invoice->Id, 'invoice');

                $customer_connect_id = $this->check_connect_id($invoice->CustomerRef, 'customer');

                if($customer_connect_id == 0){
                    $this->get_quickbook_customer();
                    $customer_connect_id = $this->check_connect_id($invoice->CustomerRef, 'customer');
                }

                $invoice_data = [];

                $currency = get_currency($invoice->CurrencyRef);
                $invoice_data['currency'] = $currency ? $currency->id : '';

                $invoice_data['date'] = $invoice->TxnDate;
                $invoice_data['duedate'] = $invoice->DueDate;
                $invoice_data['clientid']         = $customer_connect_id;

                $invoice_data['include_shipping']         = 1;
                $invoice_data['show_shipping_on_invoice'] = 1;

                $invoice_data["allowed_payment_modes"] = $payment_model_list;

                $billing_street = '';
                $billing_city = '';
                $billing_zip = '';
                if($invoice->BillAddr){
                    $billing_street = $invoice->BillAddr->Line1 ?? '';
                    $billing_city = $invoice->BillAddr->City ?? '';
                    $billing_zip = $invoice->BillAddr->PostalCode ?? '';
                }

                $invoice_data['billing_street'] = $billing_street;
                $invoice_data['billing_city'] = $billing_city;
                $invoice_data['billing_state'] = '';
                $invoice_data['billing_zip'] = $billing_zip;
                $invoice_data['billing_country'] = '';

                $shipping_street = '';
                $shipping_city = '';
                $shipping_zip = '';
                if($invoice->ShipAddr){
                    $shipping_street = $invoice->ShipAddr->Line1 ?? '';
                    $shipping_city = $invoice->ShipAddr->City ?? '';
                    $shipping_zip = $invoice->ShipAddr->PostalCode ?? '';
                }

                $invoice_data['shipping_street'] = $shipping_street;
                $invoice_data['shipping_city'] = $shipping_city;
                $invoice_data['shipping_state'] = '';
                $invoice_data['shipping_zip'] = $shipping_zip;
                $invoice_data['shipping_country'] = '';

                

                $invoice_data['total']               = $invoice->TotalAmt;
                $subtotal = 0;
                $newitems = [];
                foreach ($invoice->Line as $key => $value) {
                    if($value == null){
                        continue;
                    }

                    if($value->DetailType == 'SalesItemLineDetail'){
                        if($value->SalesItemLineDetail != null){
                            array_push($newitems, array(
                                'order' => $value->LineNum, 
                                'description' => $value->Description ?? 'Description', 
                                'long_description' => '', 
                                'qty' => $value->SalesItemLineDetail->Qty ?? 1, 
                                'unit' => '', 
                                'rate' => $value->SalesItemLineDetail->UnitPrice ?? $value->SalesItemLineDetail->MarkupInfo->Value, 
                                'taxname' => ''));
                        }

                    }

                    if($value->DetailType == 'SubTotalLineDetail'){
                        $subtotal = $value->Amount;
                    }

                    if($value->DetailType == 'DiscountLineDetail'){
                        if($invoice->ApplyTaxAfterDiscount){
                            $invoice_data['discount_type'] = "before_tax";
                        }else{
                            $invoice_data['discount_type'] = "after_tax";
                        }

                        $invoice_data['discount_total']               = $value->Amount;
                    }
                }

                if($invoice->TxnTaxDetail){
                    array_push($newitems, array(
                                'order' => 100, 
                                'description' => 'Total Tax', 
                                'long_description' => '', 
                                'qty' => 1, 
                                'unit' => '', 
                                'rate' => $invoice->TxnTaxDetail->TotalTax, 
                                'taxname' => ''));
                }

                $invoice_data['subtotal']            = $subtotal;

                $invoice_data['newitems'] = $newitems;
                if($check_connect_id != 0 && isset($invoice_arr[$check_connect_id])){
                    $this->delete_invoice_item($check_connect_id);
                    $this->invoices_model->update($invoice_data, $check_connect_id);
                    $invoice_id = $check_connect_id;
                }else{
                    if($check_connect_id != 0){
                        $this->delete_integration_log($check_connect_id, 'invoice', 'quickbook');
                    }
                    $__number        = get_option('next_invoice_number');
                    $_invoice_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
                    $invoice_data['number']              = $_invoice_number;

                    $invoice_id = $this->invoices_model->add($invoice_data);
                }

                $sync_status = 0;
                if($invoice_id){
                    $sync_status = 1;

                    if(!isset($invoice_arr[$check_connect_id])){
                        $this->db->insert(db_prefix().'acc_integration_logs', [
                            'rel_id' => $invoice_id,
                            'rel_type' => 'invoice',
                            'software' => 'quickbook',
                            'connect_id' => $invoice->Id,
                            'date_updated' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }

                $this->db->insert(db_prefix().'acc_integration_sync_logs', [
                    'rel_id' => $invoice_id,
                    'rel_type' => 'invoice',
                    'software' => 'quickbook',
                    'type' => 'sync_down',
                    'status' => $sync_status,
                    'connect_id' => $invoice->Id,
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    public function get_quickbook_payment(){
        $entities = $this->dataService->Query("SELECT * FROM Payment");

        $this->load->model('payments_model');
        $payment_list = $this->db->get(db_prefix() . 'invoicepaymentrecords')->result_array();
     
        $payment_arr = [];
        foreach ($payment_list as $payment) {
            $payment_arr[$payment['id']] = $payment;
        }

        $error = $this->dataService->getLastError();

        if ($error) {
            
        } else {
            $this->load->model('payment_modes_model');

            $payment_modes = $this->payment_modes_model->get();

            $fisrt_payment_mode = 0;
            if (isset($payment_modes[0])) {
                $fisrt_payment_mode = $payment_modes[0]['id'];
            }

            foreach ($entities as $payment) {
                $check_connect_id = $this->check_connect_id($payment->Id, 'payment');
                if(!isset($payment->Line) || !isset($payment->Line->LinkedTxn)){
                    continue;
                }

                $invoice_id = $this->check_connect_id($payment->Line->LinkedTxn->TxnId, 'invoice');

                $this->db->where('id', $invoice_id);
                $invoice = $this->db->get(db_prefix().'invoices')->row();

                if($invoice_id == 0 || !$invoice){
                    continue;
                }

                $payment_data = [];

                $payment_data['invoiceid'] = $invoice_id;
                $payment_data['amount'] = $payment->Line->Amount;
                $payment_data['date'] = $payment->TxnDate;
                $payment_data['note'] = '';

                if($check_connect_id != 0 && isset($payment_arr[$check_connect_id])){
                    $this->payments_model->update($payment_data, $check_connect_id);
                    $payment_id = $check_connect_id;
                }else{
                    if($check_connect_id != 0){
                        $this->delete_integration_log($check_connect_id, 'payment', 'quickbook');
                    }

                    $payment_data['transactionid'] = '';
                    $payment_data['do_not_redirect'] = 'on';
                    $payment_data["paymentmode"] = $fisrt_payment_mode;
                    $payment_data['do_not_send_email_template'] = 'on';
                    $payment_id = $this->payments_model->add($payment_data);
                }
 
                $sync_status = 0;

                if($payment_id){
                    $sync_status = 1;
                    if(!isset($payment_arr[$check_connect_id])){
                        $this->db->insert(db_prefix().'acc_integration_logs', [
                            'rel_id' => $payment_id,
                            'rel_type' => 'payment',
                            'software' => 'quickbook',
                            'connect_id' => $payment->Id,
                            'date_updated' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }

                $this->db->insert(db_prefix().'acc_integration_sync_logs', [
                    'rel_id' => $payment_id,
                    'rel_type' => 'payment',
                    'software' => 'quickbook',
                    'type' => 'sync_down',
                    'status' => $sync_status,
                    'connect_id' => $payment->Id,
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    public function get_quickbook_expense(){
        $entities = $this->dataService->Query('SELECT * FROM Purchase');

        $this->load->model('expenses_model');
        $expense_list = $this->expenses_model->get();
     
        $expense_arr = [];
        foreach ($expense_list as $expense) {
            $expense_arr[$expense['id']] = $expense;
        }

        $error = $this->dataService->getLastError();
        if ($error) {
            
        } else {
            $this->load->model('payment_modes_model');
            $payment_modes = $this->payment_modes_model->get();

            $fisrt_payment_mode = 0;
            if (isset($payment_modes[0])) {
                $fisrt_payment_mode = $payment_modes[0]['id'];
            }

            foreach ($entities as $expense) {
                $check_connect_id = $this->check_connect_id($expense->Id, 'expense');

                $expense_data = [];

                $expense_data['vendor'] = '';
                $expense_data['expense_name'] = '';
                $expense_data['note'] = $expense->Memo ?? '';
                $expense_data['category'] = $this->init_expense_category('Quickbooks Expenses');
                $expense_data['date'] = $expense->TxnDate;
                $expense_data['amount'] = $expense->TotalAmt;
                $expense_data['clientid'] = '';
                $expense_data['project_id'] = '';

                $currency = get_currency($expense->CurrencyRef);
                $expense_data['currency'] = $currency ? $currency->id : '';

                $expense_data['tax'] = '';
                $expense_data['tax2'] = '';
                $expense_data['paymentmode'] = '';
                $expense_data['reference_no'] = '';
                $expense_data['repeat_every'] = '';
                $expense_data['repeat_every_custom'] = '1';
                $expense_data['repeat_type_custom'] = 'day';

                if($check_connect_id != 0 && isset($expense_arr[$check_connect_id])){
                    $this->expenses_model->update($expense_data, $check_connect_id);
                    $expense_id = $check_connect_id;
                }else{
                    if($check_connect_id != 0){
                        $this->delete_integration_log($check_connect_id, 'expense', 'quickbook');
                    }

                    $expense_id = $this->expenses_model->add($expense_data);
                }

                $sync_status = 0;

                if($expense_id){
                    $sync_status = 1;
                    if(!isset($expense_arr[$check_connect_id])){
                        $this->db->insert(db_prefix().'acc_integration_logs', [
                            'rel_id' => $expense_id,
                            'rel_type' => 'expense',
                            'software' => 'quickbook',
                            'connect_id' => $expense->Id,
                            'date_updated' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }

                $this->db->insert(db_prefix().'acc_integration_sync_logs', [
                    'rel_id' => $expense_id,
                    'rel_type' => 'expense',
                    'software' => 'quickbook',
                    'type' => 'sync_down',
                    'status' => $sync_status,
                    'connect_id' => $expense->Id,
                    'datecreated' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    public function init_expense_category($name)
    {
        $this->db->where('name', $name);

        $expenses_categorie = $this->db->get(db_prefix() . 'expenses_categories')->row();

        if($expenses_categorie){
            return $expenses_categorie->id;
        }
        
        $this->load->model('expenses_model');
        $id = $this->expenses_model->add_category(['name' => $name, 'description' => '']);

        return $id;
    }

    public function delete_integration_error_log($rel_id, $rel_type, $software){
        $this->db->where('rel_id', $rel_id);
        $this->db->where('rel_type', $rel_type);
        $this->db->delete(db_prefix().'acc_integration_error_logs');

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function check_connect_id($connect_id, $rel_type, $software = 'quickbook'){
        $this->db->where('connect_id', $connect_id);
        $this->db->where('rel_type', $rel_type);
        $this->db->where('software', $software);
        $log = $this->db->get(db_prefix().'acc_integration_logs')->row();

        if($log){
            return $log->rel_id;
        }

        return 0;
    }

    /**
     * [get_invoice_item_tax description]
     * @param  [type] $item
     * @return [type]      
     */
    public function get_invoice_item_tax($item, $invoice){
        $data_return = [];
        $data_return['tax_id'] = [];
        $data_return['tax_amount'] = [];
        $data_return['tax_rate'] = [];
        $item_total = $item['rate'] * $item['qty'];
        $this->db->where('itemid', $item['id']);
        $item_tax = $this->db->get(db_prefix().'item_tax')->result_array();

        foreach($item_tax as $tax){
            $this->db->where('taxrate', $tax['taxrate']);
            $this->db->where('name', $tax['taxname']);
            $_tax = $this->db->get(db_prefix().'taxes')->row();
            if($_tax){
                $data_return['tax_rate'][] = $tax['taxrate'];

                $data_return['tax_id'][] = $_tax->id;
                if($invoice['discount_type'] == 'before_tax'){
                    $total_tax = $item_total * $tax['taxrate'] / 100;
                    $t = ($invoice['discount_total'] / $invoice['subtotal']) * 100;

                    $data_return['tax_amount'][] = ($total_tax - $total_tax*$t/100);
                }else{
                    $data_return['tax_amount'][] = $item_total * $tax['taxrate'] / 100;
                }

            }
        }

        return $data_return;
    }

    public function delete_integration_log($rel_id, $rel_type, $software){
        $this->db->where('rel_id', $rel_id);
        $this->db->where('rel_type', $rel_type);
        $this->db->where('software', $software);
        $this->db->delete(db_prefix().'acc_integration_logs');

        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function get_connect_id($rel_id, $rel_type, $software = 'quickbook'){
        $this->db->where('rel_id', $rel_id);
        $this->db->where('rel_type', $rel_type);
        $this->db->where('software', $software);
        $log = $this->db->get(db_prefix().'acc_integration_logs')->row();

        if($log){
            return $log->connect_id;
        }

        return '';
    }

    public function delete_invoice_item($id){
        $items = get_items_by_type('invoice', $id);

        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'invoice');
        $this->db->delete(db_prefix() . 'itemable');

        foreach ($items as $item) {
            $this->db->where('item_id', $item['id']);
            $this->db->delete(db_prefix() . 'related_items');
        }
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'invoice');
        $this->db->delete(db_prefix() . 'item_tax');

        return true;
    }
}