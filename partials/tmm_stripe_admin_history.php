<?php
if ( !defined('ABSPATH') ) exit;

wp_enqueue_style("thememakers_stripe", TMM_STRIPE_PLUGIN_URL . '/css/styles.css');
?>

<div class="wrap">
	<h2><?php _e('Auto Moto Scout Stripe Checkout - Payments history', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></h2>

	<?php if (isset($config_saved) && $config_saved === TRUE) { ?>
		<div class="updated" id="message">
			<p><strong><?php _e('Payment updated.', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></strong></p>
		</div>
	<?php } ?>

	<p>
		<?php _e('Here you can see all stripe payment made on your website.', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>
	</p>


	<?php
	unset($_GET['page']);
	
	$orderby = '';
	$orderdir = '&order=' .  (isset($_GET['order']) ? $_GET['order'] : 'DESC');
	if(isset($_GET['orderby'])){
		$orderby .= '&orderby=' . $_GET['orderby'] . $orderdir;
	}
	
	$paged = '&paged=' . (isset($_GET['paged']) ? (int) $_GET['paged'] : 1);
			
	//***
	$links = array(
		'status_link' => 'status',
		'amount_link' => 'amount',
		'firstname_link' => 'firstname',
		'lastname_link' => 'lastname',
		'email_link' => 'email',
		'date_link' => 'created',
	);
	
	if(isset($order)){
        if ($order == 'DESC') {
            $_GET['order'] = 'ASC';
        } else {
            $_GET['order'] = 'DESC';
        }
    }
	
	foreach ($links as $k => $v) {
		$links[$k] = $config->getItem('plugin_history_url') . $paged. '&orderby=' . $v . '&order=' . $_GET['order'];
	}

	?>

	<div class="tablenav top">
		<div class="alignleft actions">
			<form method="post" action="<?php echo $config->getItem('plugin_history_url'); ?>" style="display: inline-block;">

                <select name="y">
                    <option value="-1" selected="selected"><?php _e('Show all years', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></option>
                    <?php for ($y = intval(date('Y')); $y >= 2014; $y--): ?>
                        <option <?php if ($year == $y): ?>selected=""<?php endif; ?> value="<?php echo $y ?>"><?php echo $y ?></option>
                    <?php endfor; ?>
                </select>

				<?php
				$monthes = array(
					0 => __('January', TMM_STRIPE_PLUGIN_TEXTDOMAIN),
					1 => __('February', TMM_STRIPE_PLUGIN_TEXTDOMAIN),
					2 => __('March', TMM_STRIPE_PLUGIN_TEXTDOMAIN),
					3 => __('April', TMM_STRIPE_PLUGIN_TEXTDOMAIN),
					4 => __('May', TMM_STRIPE_PLUGIN_TEXTDOMAIN),
					5 => __('June', TMM_STRIPE_PLUGIN_TEXTDOMAIN),
					6 => __('July', TMM_STRIPE_PLUGIN_TEXTDOMAIN),
					7 => __('August', TMM_STRIPE_PLUGIN_TEXTDOMAIN),
					8 => __('September', TMM_STRIPE_PLUGIN_TEXTDOMAIN),
					9 => __('October', TMM_STRIPE_PLUGIN_TEXTDOMAIN),
					10 => __('November', TMM_STRIPE_PLUGIN_TEXTDOMAIN),
					11 => __('December', TMM_STRIPE_PLUGIN_TEXTDOMAIN),
				);
				?>

                <select name="m">
                    <option value="-1" selected="selected"><?php _e('Show all months', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></option>
                    <?php for ($m = 0; $m < 12; $m++): ?>
                        <option <?php if ($month == $m): ?>selected=""<?php endif; ?> value="<?php echo $m ?>"><?php echo $monthes[$m] ?></option>
                    <?php endfor; ?>
                </select>

				<input type="submit" value="<?php _e('Filter', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>" class="button" id="post-query-submit" name="">
			</form>

		</div>

		<div class="tablenav-pages">

			<form method="post" action="<?php echo $config->getItem('plugin_history_url') ?>" style="display: inline-block;">
				<input type="text" value="<?php echo isset($user_email) ? $user_email : '' ?>" name="user_email" placeholder="<?php _e('enter user email', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>">
				<input type="submit" value="<?php _e('Search', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>" class="button">
			</form>

			<?php if (isset($rows_count) && $limit < $rows_count): ?>
				<span class="displaying-num"><?php echo $rows_count ?> <?php _e('items', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></span>
				<span class="pagination-links">
					<a href="<?php echo($pagenum > 1 ? $config->getItem('plugin_history_url').'&paged=1'.$orderby : '#') ?>" title="<?php _e('Go to the first page', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>" class="first-page <?php if ($pagenum == 1): ?>disabled<?php endif; ?>">«</a>
					<a href="<?php echo($pagenum > 1 ? $config->getItem('plugin_history_url').'&paged='.($pagenum>1 ? ($pagenum - 1) : 1).$orderby : '#') ?>" title="<?php _e('Go to the previous page', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>" class="prev-page <?php if ($pagenum == 1): ?>disabled<?php endif; ?>">‹</a>
					<span class="paging-input">
						<form style="display: inline-block;" method="post" action="<?php echo $config->getItem('plugin_history_url').$orderby; ?>">
							<input type="text" size="1" value="<?php echo $pagenum ?>" name="paged" title="<?php _e('Current page', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>" class="current-page"> <?php _e('of', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?> <span class="total-pages"><?php echo ceil($rows_count / $limit) ?></span>
						</form>
					</span>
					<a href="<?php echo($pagenum < ceil($rows_count / $limit) ? $config->getItem('plugin_history_url').'&paged='.($pagenum + 1).$orderby : '#') ?>" title="<?php _e('Go to the next page', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>" class="next-page <?php if ($pagenum >= ceil($rows_count / $limit)): ?>disabled<?php endif; ?>">›</a>
					<a href="<?php echo($pagenum < ceil($rows_count / $limit) ? $config->getItem('plugin_history_url').'&paged='.(ceil($rows_count / $limit)).$orderby : '#') ?>" title="<?php _e('Go to the last page', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>" class="last-page <?php if ($pagenum >= ceil($rows_count / $limit)): ?>disabled<?php endif; ?>">»</a>
				</span>
			<?php endif; ?>
		</div>

		<br class="clear">
	</div>

	<table cellspacing="0" class="wp-list-table widefat fixed">
		<thead>
			<tr>
				<th width="20" class="manage-column">
					<?php _e('ID', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>
				</th>
				<th class="manage-column">
					<a href="<?php echo $links['status_link'] ?>"><?php _e('Status', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
				</th>
				<th class="manage-column">
					<a href="<?php echo $links['amount_link'] ?>"><?php _e('Amount', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
				</th>
				<th class="manage-column">
					<?php _e('Currency', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>
				</th>
				<th class="manage-column">
					<?php _e('Package ID', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>
				</th>
				<th class="manage-column">
					<?php _e('Description', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>
				</th>
				<th class="manage-column">
					<a href="<?php echo $links['firstname_link'] ?>"><?php _e('Firstname', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
				</th>
				<th class="manage-column">
					<a href="<?php echo $links['lastname_link'] ?>"><?php _e('Lastname', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
				</th>
				<th class="manage-column">
					<a href="<?php echo $links['email_link'] ?>"><?php _e('E-mail', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
				</th>
				<th class="manage-column">
					<a href="<?php echo $links['date_link'] ?>"><?php _e('Date', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
				</th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<th width="20" class="manage-column">
					<?php _e('ID', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>
				</th>
				<th class="manage-column">
					<a href="<?php echo $links['status_link'] ?>"><?php _e('Status', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
				</th>
				<th class="manage-column">
					<a href="<?php echo $links['amount_link'] ?>"><?php _e('Amount', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
				</th>
				<th class="manage-column">
					<?php _e('Currency', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>
				</th>
				<th class="manage-column">
					<?php _e('Package ID', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>
				</th>
				<th class="manage-column">
					<?php _e('Description', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>
				</th>
				<th class="manage-column">
					<a href="<?php echo $links['firstname_link'] ?>"><?php _e('Firstname', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
				</th>
				<th class="manage-column">
					<a href="<?php echo $links['lastname_link'] ?>"><?php _e('Lastname', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
				</th>
				<th class="manage-column">
					<a href="<?php echo $links['email_link'] ?>"><?php _e('E-mail', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
				</th>
				<th class="manage-column">
					<a href="<?php echo $links['date_link'] ?>"><?php _e('Date', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
				</th>
			</tr>
		</tfoot>

		<tbody class="list:user" id="the-list">
			<?php
			$roles = TMM_Cardealer_User::get_user_roles();
			$feature_packets = TMM_Cardealer_User::get_features_packets();
			?>
			<?php
            if(isset($rows)){
            foreach ($rows as $kk => $row) { ?>
				<?php
				$is_price_red = false;
				$is_key_red = false;
				if ((float) @$roles[$row->packet_id]['packet_price'] !== (float) $row->amount) {
					if ((float) @$feature_packets[$row->packet_id]['packet_price'] !== (float) $row->amount) {
						$is_price_red = true;
					}
				}
				//***
				if (@!isset($roles[$row->packet_id])) {
					if (@!isset($feature_packets[$row->packet_id])) {
						$is_key_red = true;
					}
				}
				if(time() != current_time('timestamp')){
					$row->created += current_time('timestamp') - time();
				}
                $rowStatyle='';
                if ($row->status=='success'){
                    $rowStatyle='background:#00800021';
                }else if ($row->status=='failed'){
                    $rowStatyle='background:#ff00001f';
                }
				?>
				<tr class="alternate" style="<?=$rowStatyle?>">
					<td width="20"><?php echo $row->id; ?></td>
					<td class="username column-username">
						<strong style="<?php echo $row->status == 'success' ? 'color:#339900;' : ''; ?>"><?php echo strtoupper($row->status); ?></strong><br />
						<div class="row-actions">
							<span class="edit"><a href="<?php echo $config->getItem('plugin_history_url') . '&action=edit&id=' . $row->id; ?>"><?php _e('Edit', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a></span> |
							<span class="edit"><a href="<?php echo $config->getItem('plugin_history_url') . '&action=details&id=' . $row->id; ?>"><?php _e('View details', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a></span>
						</div>
					</td>
					<td class="role column-role" style="color:<?php if ($is_price_red): ?>red<?php else: ?>green<?php endif; ?>"><?php echo(number_format($row->amount, 2)); ?></td>
					<td class="role column-role"><?php echo $row->currency; ?></td>
					<td class="role column-role" style="color: <?php if ($is_key_red): ?>red<?php else: ?>green<?php endif; ?>;"><?php echo $row->packet_id; ?></td>
					<td class="role column-role"><?php echo $row->description; ?></td>
					<td class="role column-role"><?php echo $row->firstname; ?></td>
					<td class="role column-role"><?php echo $row->lastname; ?></td>
					<td class="role column-role"><?php echo $row->email; ?></td>
					<td class="role column-role"><?php echo date('Y-m-d H:i', $row->created); ?></td>
				<?php }
                }?>
		</tbody>
	</table>
</div><!-- .wrap -->
