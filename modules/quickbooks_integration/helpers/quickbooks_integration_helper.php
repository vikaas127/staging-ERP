<?php
defined('BASEPATH') or exit('No direct script access allowed');

function get_acc_quickbook_config(){
    $CI = &get_instance();

    $client_id = get_option('acc_integration_quickbooks_client_id');
    $client_secret = get_option('acc_integration_quickbooks_client_secret');

    return array(
      'authorizationRequestUrl' => 'https://appcenter.intuit.com/connect/oauth2', 
      'tokenEndPointUrl' => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer', 
      'client_id' => $CI->encryption->decrypt($client_id), 
      'client_secret' => $CI->encryption->decrypt($client_secret), 
      'oauth_scope' => 'com.intuit.quickbooks.accounting', 
      'openID_scope' => 'openid profile email', 
      'oauth_redirect_uri' => admin_url('quickbooks_integration/connect'), 
      'openID_redirect_uri' => admin_url('quickbooks_integration/quickbook2'),
      'mainPage' => admin_url('quickbooks_integration/setting'),
      'refreshTokenPage' => admin_url('quickbooks_integration/quickbook_refreshToken'),
    );
}

/**
 * Gets the url by type identifier.
 */
if(!function_exists('sync_get_url_by_type_id')){
  function sync_get_url_by_type_id($rel_id, $rel_type){
    $url = '#';
    $name = '';
    switch ($rel_type) {
          case 'invoice':
              $name = format_invoice_number($rel_id);
              $url = admin_url('invoices/list_invoices/'.$rel_id);
          break;

          case 'expense':
              $name = _l('sync_expense').' #'.$rel_id;
              $url = admin_url('expenses/list_expenses/'.$rel_id);
          break;

          case 'payment':
              $name = _l('sync_payment').' #'.$rel_id;
              $url = admin_url('payments/payment/'.$rel_id);
          break;

          case 'customer':
              $name = get_company_name($rel_id);
              $url = admin_url('clients/client/'.$rel_id);
          break;
      }

      if($name == ''){
        $name = _l('sync_'.$rel_type).' #'.$rel_id;
      }

      return '<a href="'.$url.'">'.$name.'</a>';
  }
}