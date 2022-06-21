<?php

/**
 * Our main class
 */

class StripeAdmin {

    public static function includeView($file, $params=array()) {
        $config = StripeConfig::getInstance();
        $path = $config->getItem('views_path') . $file . '.php';
        if(file_exists($path)){
            extract($params);
            require $path;
        }
    }

    /**
     * Admin interface > configuration
     */
    public  function StripeAdminConfiguration() {

        /* Save configuration */
        $config_saved = false;
        if (count($_POST)) {

            $environment_values = array('sandbox', 'live');
            if (isset($_POST['environment']) && in_array($_POST['environment'], $environment_values)) {
                update_option('stripe_environment', $_POST['environment']);
                $config_saved = TRUE;
            }

            if (isset($_POST['stripe_publish_key']) && isset($_POST['stripe_secret_key'])) {
                update_option('stripe_publish_key', trim($_POST['stripe_publish_key']));
                update_option('stripe_secret_key', trim($_POST['stripe_secret_key']));
                $config_saved = TRUE;
            }

            if (isset($_POST['stripe_success_page']) && is_numeric($_POST['stripe_success_page']) && $_POST['stripe_success_page'] > 0) {
                update_option('stripe_success_page', $_POST['stripe_success_page']);
                $config_saved = TRUE;
            }

            if (isset($_POST['stripe_cancel_page']) && is_numeric($_POST['stripe_cancel_page']) && $_POST['stripe_cancel_page'] > 0) {
                update_option('stripe_cancel_page', $_POST['stripe_cancel_page']);
                $config_saved = TRUE;
            }

            if (isset($_POST['stripe_currency'])) {
                update_option('stripe_currency', $_POST['stripe_currency']);
                $config_saved = TRUE;
            }
        }

        self::includeView('tmm_stripe_admin_configuration', array('config_saved' => $config_saved));
    }

    /**
     * Admin interface > payments history
     */
    public static function StripeIntigrationsHelpCenter() {
        self::includeView('tmm_stripe_admin_help_center', array('config_saved' => ''));
    }
     /**
     * Admin interface > payments history
     */
    public static function StripeAdminHistory() {
        global $wpdb;
		$config = StripeConfig::getInstance();
        $params = array();
        $config_saved = false;
        $allowed_statuses = array('success', 'pending', 'failed');
        if (count($_POST) && isset($_POST['status']) && in_array($_POST['status'], $allowed_statuses) && isset($_POST['id']) && is_numeric($_POST['id']) && $_POST['id'] > 0) {
            $config_saved = TRUE;

            $update_data = array('status' => $_POST['status']);
            $where = array('id' => $_POST['id']);

            $update_format = array('%s');

            $wpdb->update('tmm_stripe_checkout_history', $update_data, $where, $update_format);
        }

        if (isset($_GET['action']) && $_GET['action'] == 'details' && is_numeric($_GET['id']) && $_GET['id'] > 0) {
            $details = $wpdb->get_row('SELECT tmm_stripe_checkout_history.id,
                                tmm_stripe_checkout_history.amount,
                                tmm_stripe_checkout_history.currency,
                                tmm_stripe_checkout_history.packet_id,
                                tmm_stripe_checkout_history.status,
                                tmm_stripe_checkout_history.firstname,
                                tmm_stripe_checkout_history.lastname,
                                tmm_stripe_checkout_history.email,
                                tmm_stripe_checkout_history.description,
                                tmm_stripe_checkout_history.summary,
                                tmm_stripe_checkout_history.created
                              FROM
                                tmm_stripe_checkout_history
                              WHERE
                                tmm_stripe_checkout_history.id = ' . (int) $_GET['id']);

            $path = 'tmm_stripe_admin_history_details';
            $params['details'] = $details;
        } elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && is_numeric($_GET['id']) && $_GET['id'] > 0) {
            $details = $wpdb->get_row('SELECT
                                tmm_stripe_checkout_history.status
                              FROM
                                tmm_stripe_checkout_history
                              WHERE
                                tmm_stripe_checkout_history.id = ' . (int) $_GET['id']);

            $path = 'tmm_stripe_admin_history_edit';
            $params['details'] = $details;

        } else {
            $limit = $config->getItem('history_page_pagination_limit');
            $pagenum = 0;
            if (isset($_REQUEST['paged'])) {
                $pagenum = (int) $_REQUEST['paged'] - 1;
				if($pagenum < 0){
					$pagenum = 0;
				}
            }
            $order = 'DESC';
            if (isset($_REQUEST['order'])) {
                $order = $_REQUEST['order'];
            }
            $orderby = 'created';
            if (isset($_REQUEST['orderby'])) {
                $orderby = $_REQUEST['orderby'];
            }
            $user_email = '';
            if (isset($_REQUEST['user_email'])) {
                $user_email = $_REQUEST['user_email'];
                $_GET['user_email'] = $user_email;
            }
            $year = -1;
            if (isset($_REQUEST['y'])) {
                $year = $_REQUEST['y'];
                $_GET['y'] = $year;
            }
            $month = -1;
            if (isset($_REQUEST['m'])) {
                $month = $_REQUEST['m'];
                $_GET['m'] = $month;
            }

            //***
            $time_from = 0;
            $time_to = 0;
            if ($year > -1 OR $month > -1) {
                if ($month > -1 AND $year == -1) {
                    $year = intval(date('Y'));
                }
            }

            if ($month == -1) {
                //see for full year
                $time_from = mktime(0, 0, 0, 1, 1, $year);
                $time_to = mktime(0, 0, 0, 12, 31, $year);
            }

            if ($month != -1) {
                //see for full year
                $time_from = mktime(0, 0, 0, $month + 1, 1, $year);
                $time_to = mktime(0, 0, 0, $month + 1, 31, $year);
            }

            $rows_count = $wpdb->get_var(
				'SELECT COUNT(*)
                   FROM tmm_stripe_checkout_history
				   WHERE 1=1 ' . ($time_from > 0 ? ' ' . 'AND created>=' . $time_from . ' ' . 'AND created<=' . $time_to : '') . ' ' . (!empty($user_email) ? 'AND email LIKE "%' . $user_email . '%"' : '')
				);

            $rows = $wpdb->get_results('SELECT tmm_stripe_checkout_history.id,
                                tmm_stripe_checkout_history.amount,
                                tmm_stripe_checkout_history.currency,
                                tmm_stripe_checkout_history.packet_id,
                                tmm_stripe_checkout_history.status,
                                tmm_stripe_checkout_history.firstname,
                                tmm_stripe_checkout_history.lastname,
                                tmm_stripe_checkout_history.email,
                                tmm_stripe_checkout_history.description,
                                tmm_stripe_checkout_history.summary,
                                tmm_stripe_checkout_history.created
                              FROM
                                tmm_stripe_checkout_history WHERE 1=1 ' . ($time_from > 0 ? ' ' . 'AND created>=' . $time_from . ' ' . 'AND created<=' . $time_to : '') . ' ' . (!empty($user_email) ? 'AND email LIKE "%' . $user_email . '%"' : '') . '
                              ORDER BY
                                tmm_stripe_checkout_history.' . $orderby . ' ' . $order . ' LIMIT ' . $pagenum * $limit . ',' . $limit);
            
            $path = 'tmm_stripe_admin_history';
            
            if(isset($details)){
                $params['details'] = $details;
            }
            
            $params['limit'] = $limit;
            $params['pagenum'] = $pagenum + 1;
            $params['order'] = $order;
            $params['rows_count'] = $rows_count;
            $params['rows'] = $rows;
            $params['user_email'] = $user_email;
            $params['year'] = $year;
            $params['month'] = $month;
        }
        $params['config_saved'] = $config_saved;
        if(isset($path)){
            self::includeView($path, $params);
        }
    }

    /**
     * Create table for payment history
     */
    public static function AmsPluginInstall() {
        global $wpdb;
        $wpdb->query('
			CREATE TABLE IF NOT EXISTS `tmm_stripe_checkout_history` (
			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `transaction_id` text NOT NULL,
			  `token` text NOT NULL,
			  `amount` float unsigned NOT NULL,
			  `currency` varchar(3) NOT NULL,
			  `packet_id` varchar(24) NOT NULL,
			  `status` text NOT NULL,
			  `firstname` text NOT NULL,
			  `lastname` text NOT NULL,
			  `email` text NOT NULL,
			  `description` text NOT NULL,
			  `summary` text NOT NULL,
			  `created` int(4) unsigned NOT NULL,
			  PRIMARY KEY (`id`)
			);
        ');
    }

}