<?php if ( !defined('ABSPATH') ) exit; ?>

<div class="wrap">
	<h2><?php _e('Auto Moto Scout Stripe Checkout - Payment details', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></h2>

	<p>
		<a href="<?php echo $config->getItem('plugin_history_url'); ?>" title="Back to the payments history">&laquo; <?php _e('Back to the payments history', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
	</p>

	<table class="form-table">
		<tr>
			<th><strong><?php _e('Status', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></th>
			<td style="<?php echo $details->status == 'success' ? 'color:#339900;' : ''; ?>"><?php echo $details->status; ?></td>
		</tr>
		<tr>
			<th><strong><?php _e('Date', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></th>
			<td><?php echo date('Y-m-d H:i', $details->created); ?></td>
		</tr>
		<tr>
			<th><strong><?php _e('Amount', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></th>
			<td><?php echo number_format($details->amount, 2); ?></td>
		</tr>
		<tr>
			<th><strong><?php _e('Currency', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></th>
			<td><?php echo $details->currency; ?></td>
		</tr>
		<tr>
			<th><strong><?php _e('Package ID', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></th>
			<td><?php echo $details->packet_id; ?></td>
		</tr> 
		<tr>
			<th><strong><?php _e('Description', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></th>
			<td><?php echo $details->description; ?></td>
		</tr>
		<tr>
			<th><strong><?php _e('Firstname', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></th>
			<td><?php echo $details->firstname; ?></td>
		</tr>
		<tr>
			<th><strong><?php _e('Lastname', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></th>
			<td><?php echo $details->lastname; ?></td>
		</tr>
		<tr>
			<th><strong><?php _e('E-mail', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></th>
			<td><?php echo $details->email; ?></td>
		</tr>
		<tr>
			<th><strong><?php _e('API call result', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></th>
			<td>
				<?php
				if (is_serialized($details->summary)) {
					$summary = unserialize($details->summary);
					if (!empty($summary)) {
						foreach ($summary as $key => $value) {
							echo "$key: $value<br />";
						}
					}
				}
				?>
			</td>
		</tr>
	</table>

	<p>
		<a href="<?php echo $config->getItem('plugin_history_url'); ?>" title="<?php _e('Back to the payments history', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>">&laquo; <?php _e('Back to the payments history', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
	</p>
</div><!-- .wrap -->