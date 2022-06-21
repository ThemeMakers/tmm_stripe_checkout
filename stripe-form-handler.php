<?php

/**
 * Form posting handler
 */

require_once '../../../wp-load.php';
require_once TMM_STRIPE_PLUGIN_PATH . '/classes/StripeConfig.php';
require_once TMM_STRIPE_PLUGIN_PATH . '/classes/StripeApi.php';
require_once TMM_STRIPE_PLUGIN_PATH . '/stripe/init.php';

if (isset($_POST['stripe_func']) && $_POST['stripe_func'] === 'start') {

    StripeApi::AmsStartStripeCheckout();

} else if (isset($_GET['func']) && $_GET['func'] == 'confirm' && isset($_GET['token'])) {

    $message_num = 0;
    $stripeResponseData = StripeApi::StripePaymentConfirmCheckout();

    $config = StripeConfig::getInstance();

    if ($stripeResponseData=='success') {
        header('Location: ' . $config->getItem('success_page'));
    } else {
        header('Location: ' . $config->getItem('cancel_page'));
    }

} else {
    $config = StripeConfig::getInstance();
    header('Location: ' . $config->getItem('cancel_page'));

}
