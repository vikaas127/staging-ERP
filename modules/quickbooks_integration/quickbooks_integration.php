<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Quickbooks integration
Description: Quickbooks integration
Version: 1.0.0
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
*/

define('QUICKBOOKS_INTEGRATION_MODULE_NAME', 'quickbooks_integration');
define('QUICKBOOKS_INTEGRATION_REVISION', 100);

hooks()->add_action('after_cron_run', 'cron_quickbooks_integrations');
hooks()->add_action('admin_init', 'quickbooks_integration_module_init_menu_items');
hooks()->add_action('admin_init', 'quickbooks_integration_permissions');
hooks()->add_action('app_admin_head', 'quickbooks_integration_head_components');
hooks()->add_action('app_admin_footer', 'quickbooks_integration_add_footer_components');
hooks()->add_action('quickbooks_integration_init',QUICKBOOKS_INTEGRATION_MODULE_NAME.'_appint');
hooks()->add_action('pre_activate_module', QUICKBOOKS_INTEGRATION_MODULE_NAME.'_preactivate');
hooks()->add_action('pre_deactivate_module', QUICKBOOKS_INTEGRATION_MODULE_NAME.'_predeactivate');
hooks()->add_filter('deprecated_hook_trigger_error', 'quickbooks_deprecated_hook_trigger_error');

/**
 * Register activation module hook
 */
register_activation_hook(QUICKBOOKS_INTEGRATION_MODULE_NAME, 'quickbooks_integration_module_activation_hook');

function quickbooks_integration_module_activation_hook() {
	$CI = &get_instance();
	require_once __DIR__ . '/install.php';
}
/**
* Load the module helper
*/
$CI = & get_instance();
$CI->load->helper(QUICKBOOKS_INTEGRATION_MODULE_NAME . '/quickbooks_integration');

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(QUICKBOOKS_INTEGRATION_MODULE_NAME, [QUICKBOOKS_INTEGRATION_MODULE_NAME]);

/**
 * Init quickbooks_integration module menu items in setup in admin_init hook
 * @return null
 */
function quickbooks_integration_module_init_menu_items() {
	if (has_permission('quickbooks_integration', '', 'view')) {
		$CI = &get_instance();
		$CI->app_menu->add_sidebar_menu_item('quickbooks_integration', [
			'name' => _l('quickbooks_integration'),
			'icon' => 'fa fa-calendar',
			'position' => 30,
		]);

		$CI->app_menu->add_sidebar_children_item('quickbooks_integration', [
			'slug' => 'quickbooks-integration-management',
			'name' => _l('management'),
			'icon' => 'fa fa-list',
			'href' => admin_url('quickbooks_integration/manage?group=customers'),
			'position' => 1,
		]);

		/*$CI->app_menu->add_sidebar_children_item('quickbooks_integration', [
			'slug' => 'quickbooks-integration-sync-logs',
			'name' => _l('sync_logs'),
			'icon' => 'fa fa-list',
			'href' => admin_url('quickbooks_integration/sync_logs'),
			'position' => 1,
		]);

		$CI->app_menu->add_sidebar_children_item('quickbooks_integration', [
			'slug' => 'quickbooks-integration-settings',
			'name' => _l('settings'),
			'icon' => 'fa fa-cog',
			'href' => admin_url('quickbooks_integration/setting'),
			'position' => 2,
		]);*/
	}
}


/**
 * resource workload permissions
 * @return
 */
function quickbooks_integration_permissions() {
	$capabilities = [];

	$capabilities['capabilities'] = [
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
	];

	register_staff_capabilities('quickbooks_integration', $capabilities, _l('quickbooks_integration'));
}

/**
 * add head components
 */
function quickbooks_integration_head_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, 'admin/quickbooks_integration/manage?group=expenses') === false)) {
		echo '<link href="' . module_dir_url(QUICKBOOKS_INTEGRATION_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . QUICKBOOKS_INTEGRATION_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/quickbooks_integration/manage?group=invoices') === false)) {
		echo '<link href="' . module_dir_url(QUICKBOOKS_INTEGRATION_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . QUICKBOOKS_INTEGRATION_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/quickbooks_integration/manage?group=payments') === false)) {
		echo '<link href="' . module_dir_url(QUICKBOOKS_INTEGRATION_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . QUICKBOOKS_INTEGRATION_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/quickbooks_integration/manage?group=customers') === false)) {
		echo '<link href="' . module_dir_url(QUICKBOOKS_INTEGRATION_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . QUICKBOOKS_INTEGRATION_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
}

/**
 * add footer components
 * @return
 */
function quickbooks_integration_add_footer_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, 'admin/quickbooks_integration/manage?group=expenses') === false)) {
		echo '<script src="' . module_dir_url(QUICKBOOKS_INTEGRATION_MODULE_NAME, 'assets/js/integrations/expenses.js') . '?v=' . QUICKBOOKS_INTEGRATION_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/quickbooks_integration/manage?group=invoices') === false)) {
		echo '<script src="' . module_dir_url(QUICKBOOKS_INTEGRATION_MODULE_NAME, 'assets/js/integrations/invoices.js') . '?v=' . QUICKBOOKS_INTEGRATION_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/quickbooks_integration/manage?group=payments') === false)) {
		echo '<script src="' . module_dir_url(QUICKBOOKS_INTEGRATION_MODULE_NAME, 'assets/js/integrations/payments.js') . '?v=' . QUICKBOOKS_INTEGRATION_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/quickbooks_integration/manage?group=customers') === false)) {
		echo '<script src="' . module_dir_url(QUICKBOOKS_INTEGRATION_MODULE_NAME, 'assets/js/integrations/customers.js') . '?v=' . QUICKBOOKS_INTEGRATION_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/quickbooks_integration/sync_logs') === false)) {
		echo '<script src="' . module_dir_url(QUICKBOOKS_INTEGRATION_MODULE_NAME, 'assets/js/sync_logs.js') . '?v=' . QUICKBOOKS_INTEGRATION_REVISION . '"></script>';
	}
}

function cron_quickbooks_integrations(){
	$CI = &get_instance();

    if(get_option('acc_integration_quickbooks_active') == 1 && get_option('acc_integration_quickbooks_initialized') == 1){
		$CI->load->model('quickbooks_integration/quickbooks_integration_model');
		$CI->quickbooks_integration_model->init_quickbook_config();

		if(get_option('acc_integration_quickbooks_sync_from_system') == 1){
			$CI->quickbooks_integration_model->create_quickbook_customer();
			$CI->quickbooks_integration_model->create_quickbook_invoice();
			$CI->quickbooks_integration_model->create_quickbook_payment();
			$CI->quickbooks_integration_model->create_quickbook_expense();
		}

		if(get_option('acc_integration_quickbooks_sync_to_system') == 1){
			$CI->quickbooks_integration_model->get_quickbook_customer();
			$CI->quickbooks_integration_model->get_quickbook_invoice();
			$CI->quickbooks_integration_model->get_quickbook_payment();
			$CI->quickbooks_integration_model->get_quickbook_expense();
		}
	}
}

function quickbooks_deprecated_hook_trigger_error(){
	return false;
}

function quickbooks_integration_appint(){

}

function quickbooks_integration_preactivate($module_name){
    if ($module_name['system_name'] == QUICKBOOKS_INTEGRATION_MODULE_NAME) {

    }
}

function quickbooks_integration_predeactivate($module_name){
    if ($module_name['system_name'] == QUICKBOOKS_INTEGRATION_MODULE_NAME) {

    }
}
