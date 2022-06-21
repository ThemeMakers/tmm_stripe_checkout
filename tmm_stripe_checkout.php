<?php

/**
 * Plugin Name: ThemeMakers Stripe Checkout
 * Plugin URI: http://webtemplatemasters.com
 * Description: Integration of Stripe Checkout
 * Author: ThemeMakers
 * Version: 1.0.0
 * Author URI: http://themeforest.net/user/ThemeMakers
 * Text Domain: tmm_stripe_checkout
 */

ob_start();
define('TMM_STRIPE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TMM_STRIPE_PLUGIN_PATH', dirname(__FILE__));
define('TMM_STRIPE_PLUGIN_TEXTDOMAIN', 'tmm_stripe_checkout');

require_once TMM_STRIPE_PLUGIN_PATH . '/classes/StripeConfig.php';
require_once TMM_STRIPE_PLUGIN_PATH . '/classes/StripeShortcode.php';
require_once TMM_STRIPE_PLUGIN_PATH . '/classes/StripeAdmin.php';

function tmm_stripe_init () {

    /* Set base configuration */
    $config = StripeConfig::getInstance();

    $config->addItem('tmm_plugin_name', 'Stripe Payment');

    // plugin menu pages - admin
    $config->addItem('tmm_plugin_id', 'tmm-stripe-checkout');
    $config->addItem('tmm_plugin_folder', 'tmm_stripe_checkout');
    $config->addItem('tmm_plugin_history_id', 'tmm-stripe-checkout-history');
    $config->addItem('tmm_plugin_help_center_id', 'tmm-stripe-help-center');

    // plugin paths and urls
    $config->addItem('plugin_url', admin_url('admin.php?page=' . $config->getItem('tmm_plugin_id')));
    $config->addItem('plugin_history_url', admin_url('admin.php?page=' . $config->getItem('tmm_plugin_history_id')));
    $config->addItem('plugin_form_handler_url', TMM_STRIPE_PLUGIN_URL . 'stripe-form-handler.php');
    $config->addItem('views_path', TMM_STRIPE_PLUGIN_PATH . '/partials/');

    // supported currencies
    $config->addItem('supported_currencies',
        array(
            'AUD',
            'BRL',
            'CAD',
            'CZK',
            'DKK',
            'EUR',
            'HKD',
            'HUF',
            'ILS',
            'JPY',
            'MYR',
            'MXN',
            'NOK',
            'NZD',
            'PHP',
            'PLN',
            'GBP',
            'RUB',
            'SGD',
            'SEK',
            'CHF',
            'TWD',
            'THB',
            'TRY',
            'USD',
        )
    );
    // success and cancel pages - front
    if (get_option('stripe_cancel_page')) {
        $config->addItem('cancel_page', get_permalink(get_option('stripe_cancel_page')));
    } else {
        $config->addItem('cancel_page', home_url());
    }
    if (get_option('stripe_success_page')) {
        $config->addItem('success_page', get_permalink(get_option('stripe_success_page')));
    } else {
        $config->addItem('success_page', home_url());
    }

    $config->addItem('history_page_pagination_limit', 20);

    $config->addItem('buy_now_button_src', TMM_STRIPE_PLUGIN_URL . '/images/stripeImage.png');
    $config->addItem('checkout_button_src', TMM_STRIPE_PLUGIN_URL . '/images/stripeImage.png');


    /* create shortcode for stripe*/
    $objectStripeShortCode=new StripeShortcode();
    add_shortcode('stripe', array($objectStripeShortCode, 'frontendIndex'));

}

add_action('init', 'tmm_stripe_init', 2);


/**
 * Create admin menus
 */
function TmmStripeAdminMenu() {

    $config = StripeConfig::getInstance();
    $objectStripeAdmin=new StripeAdmin();

    add_menu_page($config->getItem('tmm_plugin_name'), $config->getItem('tmm_plugin_name'), 'level_5', $config->getItem('tmm_plugin_id'), array($objectStripeAdmin, 'StripeAdminConfiguration'), TMM_STRIPE_PLUGIN_URL . '/images/stripeicon.png');
    add_submenu_page($config->getItem('tmm_plugin_id'), __('Payments history', $config->getItem('tmm_plugin_id')), __('Payments history', $config->getItem('tmm_plugin_id')), 'level_5', $config->getItem('tmm_plugin_history_id'), array($objectStripeAdmin, 'StripeAdminHistory'));
    add_submenu_page($config->getItem('tmm_plugin_id'), __('Help Center', $config->getItem('tmm_plugin_id')), __('Help Center', $config->getItem('tmm_plugin_id')), 'level_5', $config->getItem('tmm_plugin_help_center_id'), array($objectStripeAdmin, 'StripeIntigrationsHelpCenter'));
}

add_action('admin_menu', 'TmmStripeAdminMenu');


/**
 * Create table for payment history on plugin activation
 */
$objectStripe=new StripeAdmin();
register_activation_hook(__FILE__, array($objectStripe, 'TmmPluginInstall'));

ob_clean();