<?php
if (!defined('ABSPATH')) exit;

$config = StripeConfig::getInstance();

// Sanitize options retrieved from the database
$stripePublishKey = sanitize_text_field(get_option('stripe_publish_key'));
$stripeSecretKey = sanitize_text_field(get_option('stripe_secret_key'));

if (!empty($stripeSecretKey) && !empty($stripePublishKey)) {
?>

	<form method="post" action="<?php echo esc_url($config->getItem('plugin_form_handler_url')); ?>">

		<?php if (!empty($atts['amount'])) { ?>
			<input type="hidden" name="AMT" value="<?php echo esc_attr($atts['amount']); ?>" autocomplete="off" />
		<?php } ?>

		<?php if (!empty($atts['packet_id'])) { ?>
			<input type="hidden" name="STRIPE_PAYMENTREQUEST_0_CUSTOM" value="<?php echo esc_attr($atts['packet_id']); ?>" autocomplete="off" />
		<?php } ?>

		<?php if (!empty($atts['paymentType'])) { ?>
			<input type="hidden" name="PAYMENT_TYPE" value="<?php echo esc_attr($atts['paymentType']); ?>" autocomplete="off" />
		<?php } ?>

		<input type="hidden" name="stripe_func" value="start" />

		<?php if (!empty($atts['button_style'])) { ?>
			<?php if ($atts['button_style'] === 'buy_now') { ?>
				<input type="image" value="" src="<?php echo esc_url($config->getItem('buy_now_button_src')); ?>" alt="button" />
			<?php } elseif ($atts['button_style'] === 'checkout') { ?>
				<input type="image" value="" src="<?php echo esc_url($config->getItem('checkout_button_src')); ?>" alt="button" />
			<?php } ?>
		<?php } else { ?>
			<input type="submit" value="<?php esc_html_e('Pay with Stripe', TMM_STRIPE_PLUGIN_TEXTDOMAIN); ?>" />
		<?php } ?>

	</form>

<?php } ?>