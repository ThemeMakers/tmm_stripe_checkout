<?php if ( !defined('ABSPATH') ) exit; ?>

<div class="wrap">
	<h2><?php _e('Auto Moto Scout Stripe Checkout - Configuration', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></h2>

	<?php if (isset($config_saved) && $config_saved === TRUE) { ?>
	    <div class="updated" id="message">
			<p><strong><?php _e('Configuration updated.', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></p>
		</div>
	<?php } ?>

	<form method="post" action="<?php echo $config->getItem('plugin_url'); ?>">
		<table class="form-table">
			<tbody>
				<tr class="form-field">
					<th scope="row"><label for="environment"><strong><?php _e('Stripe environment', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>:</strong></label></th>
					<td>
						<select id="environment" name="environment">
							<option value="sandbox" <?php echo get_option('stripe_environment') == 'sandbox' ? 'selected="selected"' : ''; ?>><?php _e('Sandbox (Testing)', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></option>

							<option value="live" <?php echo get_option('stripe_environment') == 'live' ? 'selected="selected"' : ''; ?>><?php _e('Live (Production)', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
        <hr>
        <h3 class="title"><?php _e('Stripe API credentials', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>:</h3>
		<table class="form-table">
			<tbody>
				<tr class="form-field form-required">
					<th scope="row"><label for="stripe_publish_key"><?php _e('Publishable key', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?> <span class="description">(<?php _e('required', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>)</span></label></th>
					<td><input type="text"  value="<?php echo get_option('stripe_publish_key'); ?>" id="stripe_publish_key" name="stripe_publish_key" autocomplete="off" placeholder="pk_test_FcSTw7yFOLJiy0e9o255555"></td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="stripe_secret_key"><?php _e('API Secret Key', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?><span class="description">(<?php _e('required', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>)</span></label></th>
					<td><input type="text"  value="<?php echo get_option('stripe_secret_key'); ?>" id="stripe_secret_key" name="stripe_secret_key" autocomplete="off" placeholder="sk_test_5W33LknCavirKAaIt54sad5ad"></td>
				</tr>
			</tbody>
		</table>
        <hr>

		<table class="form-table">
			<tbody>
				<tr class="form-field">
					<th scope="row"><label for="success_page"><strong><?php _e('Thank you page', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>:</strong></label></th>
					<td>
						<?php wp_dropdown_pages(array('name' => 'stripe_success_page', 'selected' => get_option('stripe_success_page'), 'show_option_none' => 'Please select')); ?>
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label for="stripe_cancel_page"><strong><?php _e('Error page', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>:</strong></label></th>
					<td>
						<?php wp_dropdown_pages(array('name' => 'stripe_cancel_page', 'selected' => get_option('stripe_cancel_page'), 'show_option_none' => __('Please select', TMM_STRIPE_PLUGIN_TEXTDOMAIN))); ?>
					</td>
				</tr>
			</tbody>
		</table>

		<hr>
		<?php if (!in_array($config->getItem('default_currency'), $config->getItem('supported_currencies'))) { ?>
		<hr>
		<h3 class="title"><?php _e('Stripe currency', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>:</h3>
		<table class="form-table">
			<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="stripe_currency"><strong><?php _e('Currency', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></label></th>
				<td>
					<select id="stripe_currency" name="stripe_currency">
					<?php foreach ( $config->getItem('supported_currencies') as $val ) {
						$selected = get_option('stripe_currency');
						if (!$selected) {
							$selected = 'USD';
						}
						?>
						<option value="<?php echo $val; ?>" <?php selected($selected, $val);?>><?php echo $val; ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
		<?php } ?>

		<p class="submit">
			<input type="submit" value="<?php _e('Save', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>" class="button-primary" />
		</p>
	</form>
</div><!-- .wrap -->
