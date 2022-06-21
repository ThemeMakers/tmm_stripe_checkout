<?php
if ( !defined('ABSPATH') ) exit;
$config = StripeConfig::getInstance();
$sripePublishKey =get_option('stripe_publish_key');
$stripeSecretKey =get_option('stripe_secret_key');

if (!empty($stripeSecretKey) && empty(!$sripePublishKey)){
?>

<form method="post" action="<?php echo $config->getItem('plugin_form_handler_url'); ?>">

	<?php if (isset($atts['amount'])) { ?>
	<input type="hidden" name="AMT" value="<?php echo $atts['amount']; ?>" autocomplete="off" />
	<?php } ?>

	<?php if (isset($atts['packet_id'])) { ?>
	    <input type="hidden" name="STRIPE_PAYMENTREQUEST_0_CUSTOM" value="<?php echo $atts['packet_id']; ?>" autocomplete="off" />
	<?php } ?>

    <?php if (isset($atts['paymentType'])) { ?>
        <input type="hidden" name="PAYMENT_TYPE" value="<?php echo $atts['paymentType']; ?>" autocomplete="off" />
    <?php } ?>
	<input type="hidden" name="stripe_func" value="start" />
	<?php if (isset($atts['button_style'])) { ?>
		<?php if ($atts['button_style'] == 'buy_now') { ?>
			<input type="image" value="" src="<?php echo $config->getItem('buy_now_button_src'); ?>" alt="button" />
		<?php } elseif ($atts['button_style'] == 'checkout') { ?>
			<input type="image" value="" src="<?php echo $config->getItem('checkout_button_src'); ?>" alt="button" />
		<?php } ?>
	<?php } else { ?>
	    <input type="submit" value="<?php _e('Pay with Stripe', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>" />
	<?php } ?>
</form>
<?php } ?>