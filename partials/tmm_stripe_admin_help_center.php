<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">
    <h2><?php esc_html_e('CarDealer Stripe Checkout - Help Center', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></h2>
    <table class="form-table">
        <tbody>
            <tr class="form-field">
                <td scope="row"><strong><?php esc_html_e('About:', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong> <?php esc_html_e('For Stripe checkout intigrations. You need to put the short code on your page and pass the product name and price. Fill out the stripe fields with the correct secret key and publishing keys.', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></td>
            </tr>
            <tr class="form-field">
                <th scope="row"><strong><?php esc_html_e('Integration Steps:'); ?></strong></th>
            </tr>

            <tr class="form-field">
                <td scope="row"><?php esc_html_e('1. Shortcode Setup:'); ?>
                    <code><?php esc_html_e('do_shortcode([stripe packet_id="105645454" amount="50.39"  paymentType="stripe" button_style="checkout"])'); ?></code>
                </td>
            </tr>

            <tr class="form-field">
                <td scope="row"><?php esc_html_e('2. Success and failed page create and link up with the stripe configurations'); ?></td>
            </tr>

        </tbody>
    </table>
</div><!-- .wrap -->