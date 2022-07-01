<?php
if ( !defined('ABSPATH') ) exit;

wp_enqueue_style("thememakers_stripe", TMM_STRIPE_PLUGIN_URL . '/css/styles.css');
?>

<div class="wrap">
	<h2><?php esc_html_e('CarDealer Stripe Checkout - Help Center', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></h2>
    <table class="form-table">
        <tbody>
        <tr class="form-field">
            <th scope="row"><label for="environment"><strong><?php esc_html_e('About : For Stripe checkout intigrations. You need to put the short code on your page and pass the product name and price. Give the stripe perfectly
                The secret key and publishing key. ', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>:</strong></label></th>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="environment"><strong>
                        <?php esc_html_e('Integrations Step :'); ?>
                    </strong></label></th>
        </tr>

        <tr class="form-field">
            <th scope="row"><label for="environment"><strong>
                        <?php esc_html_e('1. Setup Short Code. Example : do_shortcode([stripe packet_id="105645454" amount="50.39"  paymentType="stripe" button_style="checkout"]'); ?>
            </strong></label></th>
        </tr>

        <tr class="form-field">
            <th scope="row"><label for="environment"><strong>
                        <?php esc_html_e('2. Success and failed page create and link up with the stripe configurations'); ?>
                    </strong></label></th>
        </tr>

        </tbody>
    </table>
</div><!-- .wrap -->
