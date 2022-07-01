<?php if ( !defined('ABSPATH') ) exit; ?>

<div class="wrap">
  <h2><?php esc_html_e('CarDealer Stripe Checkout - Edit payment status', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></h2>
  
  <p>
    <a href="<?php echo $config->getItem('plugin_history_url'); ?>" title="Back to the payments history">&laquo; <?php esc_html_e('Back to the payments history', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
  </p>
  
  <form method="post" action="<?php echo $config->getItem('plugin_history_url'); ?>">
    <table class="form-table">
      <tbody>
        <tr class="form-field">
          <th scope="row"><label for="status"><strong><?php esc_html_e('Payment status', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?>:</strong></label></th>
          <td>
            <select name="status">
              <option <?php echo $details->status == 'success' ? 'selected="selected"' : ''; ?> value="success"><?php esc_html_e('Success', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></option>
              <option <?php echo $details->status == 'pending' ? 'selected="selected"' : ''; ?> value="pending"><?php esc_html_e('Pending', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></option>
              <option <?php echo $details->status == 'failed' ? 'selected="selected"' : ''; ?> value="failed"><?php esc_html_e('Failed', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></option>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <input type="hidden" name="id" value="<?php echo (int)$_GET['id']; ?>" />
      <input type="submit" value="Save" class="button-primary" />
    </p>
  
  <p>
    <a href="<?php echo $config->getItem('plugin_history_url'); ?>" title="Back to the payments history">&laquo; <?php esc_html_e('Back to the payments history', TMM_STRIPE_PLUGIN_TEXTDOMAIN) ?></a>
  </p>
</div><!-- .wrap -->