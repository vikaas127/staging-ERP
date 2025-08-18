<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: AI-Powered Natural Language Reporting for Perfex CRM
Description: Transform reporting in Perfex CRM using our AINL Reports module. Seamlessly convert plain-language questions into interactive charts and detailed data tables without writing a single line of SQL. Streamline analysis and exports—download visuals as PNG or PDF, export raw data to CSV or PDF, and instantly recall your last queries. Elevate decision-making and team productivity with intuitive, AI-powered self-service analytics.
Version: 1.0.0
Author: Lenzcreative
Author URI: https://codecanyon.net/user/lenzcreativee/portfolio
Requires at least: 1.0.*
*/

define('AINLREPORTS_MODULE_NAME', 'ainlreports');

hooks()->add_action('admin_init', 'ainlreports_module_init_menu_items');
hooks()->add_action('admin_init', 'ainlreports_permissions');
hooks()->add_action('ainlreports_init', AINLREPORTS_MODULE_NAME . '_appint');
hooks()->add_action('pre_activate_module', AINLREPORTS_MODULE_NAME . '_preactivate');
hooks()->add_action('pre_deactivate_module', AINLREPORTS_MODULE_NAME . '_predeactivate');
hooks()->add_action('pre_uninstall_module', AINLREPORTS_MODULE_NAME . '_uninstall');

/**
 * Load the module helper
 */
$CI = &get_instance();
$CI->load->helper(AINLREPORTS_MODULE_NAME . '/ainlreports');

function ainlreports_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'generate' => _l('ainlreports_generate_queries')
    ];
    register_staff_capabilities('ainlreports', $capabilities, _l('ainlreports'));
}

/**
 * Register activation module hook
 */
register_activation_hook(AINLREPORTS_MODULE_NAME, 'ainlreports_module_activation_hook');

function ainlreports_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(AINLREPORTS_MODULE_NAME, [AINLREPORTS_MODULE_NAME]);

/**
 * Init module menu items in setup in admin_init hook
 * @return null
 */
function ainlreports_module_init_menu_items()
{
    $CI = &get_instance();

   /* if (has_permission('ainlreports', '', 'generate')) {
        $CI->app_menu->add_sidebar_menu_item('ainlreports', [
            'slug' => 'ainlreports',
            'name' => _l('ainlreports'),
            'position' => 6,
            'icon' => 'fa-solid fa-robot'
        ]);
    }*/
}

hooks()->add_action('app_admin_head', 'ainlreports_head_load_js');
function ainlreports_head_load_js()
{
    $mainStyleLink = module_dir_url(AINLREPORTS_MODULE_NAME, 'assets/ainl_css.css');
    echo '<link rel="stylesheet" href="'.$mainStyleLink.'"/>';

    $script = module_dir_url(AINLREPORTS_MODULE_NAME, 'assets/libraries/js/jspdf.umd.min.js');
    echo '<script src="'.$script.'"></script>';

    $script = module_dir_url(AINLREPORTS_MODULE_NAME, 'assets/libraries/js/jspdf.plugin.autotable.min.js');
    echo '<script src="'.$script.'"></script>';
}

hooks()->add_action('app_admin_footer', 'ainlreports_load_js');
function ainlreports_load_js() {

    include_once module_dir_path(AINLREPORTS_MODULE_NAME, 'views/ai-modal.php');

    $script = module_dir_url(AINLREPORTS_MODULE_NAME, 'assets/libraries/js/chart.js');
    echo '<script src="'.$script.'"></script>';

    $script = module_dir_url(AINLREPORTS_MODULE_NAME, 'assets/ainl_js.js');
    echo '<script src="'.$script.'"></script>';
}

hooks()->add_action('admin_navbar_start', 'ainlreports_add_in_navbar');
function ainlreports_add_in_navbar() {
    if (has_permission('ainlreports', '', 'generate')) {
    echo '
    <li class="icon header-newsfeed">
                    <a href="#" id="open-ai-reports" class="desktop" data-toggle="tooltip"
                        title="AI" data-placement="bottom">
                        <img
                    id="api-loader"
                    style="height: 30px; width: 30px;margin-top: -8px;"
                    src="'. module_dir_url(AINLREPORTS_MODULE_NAME, 'assets/robot-one-svgrepo-com.svg') .'"
                    alt="Loading..."
                />
                    </a>
                </li>
    ';
    }
}

hooks()->add_filter('module_' . AINLREPORTS_MODULE_NAME . '_action_links', 'ainlreports_add_action_links');
function ainlreports_add_action_links($action_links)
{
    $settingsUrl   = admin_url(AINLREPORTS_MODULE_NAME . '/settings');
    $settingsLabel = _l('settings');

    $action_links[] = '<a href="' . $settingsUrl . '">' . $settingsLabel . '</a>';

    $action_links[] = '<a href="https://lenzcreative.net/perfex-crm-development-maintenance-services/" target="_blank">CRM Support</a>';

    $action_links[] = '<a href="mailto:lenzcreativee@hotmail.com">Email Lenzcreative</a>';

    return $action_links;
}

function ainlreports_appint()
{
    $CI = &get_instance();
    require_once 'libraries/leclib.php';
    $module_api = new AinlreportsLic();
    $module_leclib = $module_api->verify_license(true);
    if (!$module_leclib || ($module_leclib && isset($module_leclib['status']) && !$module_leclib['status'])) {
        $CI->app_modules->deactivate(AINLREPORTS_MODULE_NAME);
        set_alert('danger', "One of your modules failed its verification and got deactivated. Please reactivate or contact support.");
        redirect(admin_url('modules'));
    }
}

function ainlreports_preactivate($module_name)
{
    if ($module_name['system_name'] == AINLREPORTS_MODULE_NAME) {
        require_once 'libraries/leclib.php';
        $module_api = new AinlreportsLic();
        $module_leclib = $module_api->verify_license();
        if (!$module_leclib || ($module_leclib && isset($module_leclib['status']) && !$module_leclib['status'])) {
            $CI = &get_instance();
            $data['submit_url'] = $module_name['system_name'] . '/lecverify/activate';
            $data['original_url'] = admin_url('modules/activate/' . AINLREPORTS_MODULE_NAME);
            $data['module_name'] = AINLREPORTS_MODULE_NAME;
            $data['title'] = "Module License Activation";
            echo $CI->load->view($module_name['system_name'] . '/activate', $data, true);
            exit();
        }
    }
}

function ainlreports_predeactivate($module_name)
{
    if ($module_name['system_name'] == AINLREPORTS_MODULE_NAME) {
        require_once 'libraries/leclib.php';
        $ainlreports_api = new AinlreportsLic();
        $ainlreports_api->deactivate_license();
    }
}

function ainlreports_uninstall($module_name)
{
    if ($module_name['system_name'] == AINLREPORTS_MODULE_NAME) {
        require_once 'libraries/leclib.php';
        $ainlreports_api = new AinlreportsLic();
        $ainlreports_api->deactivate_license();
    }
}