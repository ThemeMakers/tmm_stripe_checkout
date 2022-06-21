<?php
/**
 * Shortcode class
 *
 * usage:
 * add_shortcode('your_shortcode_name', array('StripeShortcode', 'frontendIndex'));
 */

class StripeShortcode {

    public static function frontendIndex($atts) {

        require TMM_STRIPE_PLUGIN_PATH . '/partials/tmm_stripe_frontend_shortcode.php';
    }

}