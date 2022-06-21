<?php

/**
 * Class StripeApi
 */
class StripeApi
{

	/**
	 * Start express checkout
	 */
	static function TmmStartStripeCheckout()
	{
		/* define options */
		$config = StripeConfig::getInstance();
		/* check account or packet price */
		$user_roles = TMM_Cardealer_User::get_user_roles();

        $featured_packets   = TMM_Cardealer_User::get_features_packets();
		$currency           = TMM_Ext_Car_Dealer::$default_currency['name'];
        $getCurrentUser     =wp_get_current_user();

		$user_meta = get_user_meta( $getCurrentUser->data->ID);
        $firstName=$getCurrentUser->data->user_login;
        if (isset($user_meta['first_name'][0]) && !empty($user_meta['first_name'][0])){
            $firstName=$user_meta['first_name'][0];
        }
        
        $last_name='';
        if (isset($user_meta['last_name'][0]) && !empty($user_meta['last_name'][0])){
            $last_name=$user_meta['last_name'][0];
       }
		
		if(isset($user_roles[$_POST['STRIPE_PAYMENTREQUEST_0_CUSTOM']])){
			$amount = $user_roles[$_POST['STRIPE_PAYMENTREQUEST_0_CUSTOM']]['packet_price'];
			$role_name = $user_roles[$_POST['STRIPE_PAYMENTREQUEST_0_CUSTOM']]['name'];
			$desc = __('Account Status Upgrade', TMM_STRIPE_PLUGIN_TEXTDOMAIN);

		}else if(isset($featured_packets[$_POST['STRIPE_PAYMENTREQUEST_0_CUSTOM']])){
			$amount = $featured_packets[$_POST['STRIPE_PAYMENTREQUEST_0_CUSTOM']]['packet_price'];
			$role_name = $featured_packets[$_POST['STRIPE_PAYMENTREQUEST_0_CUSTOM']]['name'];
			$desc = __('Featured Cars Bundle', TMM_STRIPE_PLUGIN_TEXTDOMAIN);
		}else{
            header('Location: ' . get_permalink(get_option('stripe_cancel_page')));
            exit();
		}

        $getStripeCurrecy=get_option('stripe_currency');
        $currency=($getStripeCurrecy)?$getStripeCurrecy:$currency;

		$title          =$desc;
        $title .= ': `' . $role_name . '`, ' . $amount . ' ' . $currency;

		$desc .= ': `' . $role_name . '`, ' . $amount . ' ' . $currency;
        $desc .= ', ' . home_url();
		

        $accessToken   =self::generateRandomString();
        $cancelUrl=$config->getItem('cancel_page');
        $successUrl=$config->getItem('plugin_form_handler_url') . '?func=confirm&token='.$accessToken;

        $sripePublishKey =get_option('stripe_publish_key');
        $stripeSecretKey =get_option('stripe_secret_key');


        if (empty($stripeSecretKey) && empty($sripePublishKey)){
            header('Location: ' . $config->getItem('cancel_page'));
            exit();
        }

		if (isset($_POST['STRIPE_PAYMENTREQUEST_0_CUSTOM'])) {
			$packet_key = $_POST['STRIPE_PAYMENTREQUEST_0_CUSTOM'];
			$fields['STRIPE_PAYMENTREQUEST_0_CUSTOM'] = $packet_key;
			//*** check if packet key exists
			$packets = TMM_Cardealer_User::get_user_roles();
			if (!isset($packets[$packet_key])) {
				$packets = TMM_Cardealer_User::get_features_packets();
				if (!isset($packets[$packet_key])) {
					header('Location: ' . $config->getItem('cancel_page'));
				}
			}
		} else {
			header('Location: ' . $config->getItem('cancel_page'));
            exit();
		}


        \Stripe\Stripe::setApiKey($stripeSecretKey);
        $session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' =>$currency,
                    'product_data' => [
                        'name' =>$title,
                    ],
                    'unit_amount' =>100*$amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' =>$cancelUrl,
        ]);

        if ($session){

            global $wpdb;

            $insert_data = array(
                'token' => $accessToken,
                'amount' => $amount,
                'currency' => $currency,
                'status' => 'pending',
                'firstname' =>$firstName,
                'lastname' => $last_name,
                'email' => $getCurrentUser->data->user_email,
                'description' => $desc,
                'packet_id' => $_POST['STRIPE_PAYMENTREQUEST_0_CUSTOM'],
                'summary' => '',
                'created' => time()
            );

            $insert_format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d');

            $wpdb->insert('tmm_stripe_checkout_history', $insert_data, $insert_format);

            update_option('session_key_id',$session->id);
            header('location:' . $session->url);
            exit();

        }else {
            header('Location: ' . get_permalink(get_option('cancel_page')));
            exit();
        }
	}
    /**
     * Generated Random Key
     */
    public static function generateRandomString($length = 25) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


	/**
	 * Validate payment
	 */
	static function StripePaymentConfirmCheckout()
	{
        $config      = StripeConfig::getInstance();

        $sessionKey  =get_option('session_key_id');
        $apiKey =get_option('stripe_secret_key');

        \Stripe\Stripe::setApiKey($apiKey);
        $checkout_session = \Stripe\Checkout\Session::retrieve($sessionKey);


        if ($checkout_session->payment_status=='paid'){
            global $wpdb;

            $update_data = array(
                'transaction_id' => $checkout_session->payment_intent,
                'status' => 'success',
                'summary' =>json_encode($checkout_session),
            );

            $where = array('token' => $_GET['token']);

            $update_format = array('%s', '%s','%s');

            $wpdb->update('tmm_stripe_checkout_history', $update_data, $where, $update_format);

            $packet_key  =self::getSingleRowDataByToken($_GET['token']);
            $message_num    =self::user_paid_money($packet_key);

            return 'success';
        }elseif ($checkout_session->payment_status=='cancel' || $checkout_session->payment_status=='failed'){
            global $wpdb;

            $update_data = array(
                'transaction_id' => $checkout_session->payment_intent,
                'status' => 'failed',
                'summary' =>json_encode($checkout_session),
            );

            $where = array('token' => $_GET['token']);

            $update_format = array('%s', '%s','%s');

            $wpdb->update('tmm_stripe_checkout_history', $update_data, $where, $update_format);

            return 'cancel';

        }else{
            header('Location: ' . get_permalink(get_option('cancel_page')));
        }
	}

    public static function user_paid_money($packet_key) {
        $message_num = 1;
        $key =$packet_key;
        //check is packet was buy
        $roles = TMM_Cardealer_User::get_user_roles();
        if (!isset($roles[$key])) {
            $message_num = self::buy_features($packet_key);
        } else {
            $user_id = get_current_user_id();
            $wp_user_object = new WP_User($user_id);
            $wp_user_object->set_role($packet_key);
            $message_num = 1;
        }

        return $message_num;
    }

    //user buy features adv for self
    public static function buy_features($packet_key) {
        $message_num = 1;
        $packets = TMM_Cardealer_User::get_features_packets();
        if (!isset($packets[$packet_key])) {
            $message_num = 2;
            return $message_num;
        }

        //***
        global $wpdb;
        $user_id = get_current_user_id();
        $time_length = intval($packets[$packet_key]['life_period']);
        if($time_length != 0){
            $time_length =  $time_length * 86400;
        }
        if ($packets[$packet_key]['count'] > 0) {
            for ($i = 0; $i < $packets[$packet_key]['count']; $i++) {
                $wpdb->query($wpdb->prepare("INSERT INTO tmm_cars_features (`user_id`, `time_length`) VALUES (%d, %d)", $user_id, $time_length));
            }
        }

        /* Send email notification */
        if (tmm_allow_user_email($user_id, 'account_emails')) {

            global $tmm_config;
            $user_obj = get_userdata($user_id);
            $email = $user_obj->user_email;

            $subject = esc_html__( TMM::get_option('purchase_bundle_subject', TMM_APP_CARDEALER_PREFIX), 'cardealer' );
            $message = esc_html__( TMM::get_option('purchase_bundle_message', TMM_APP_CARDEALER_PREFIX), 'cardealer' );

            if (empty($subject)) {
                $subject = $tmm_config['emails']['purchase_bundle']['subject'];
            }

            if (empty($message)) {
                $message = $tmm_config['emails']['purchase_bundle']['message'];
            }

            $message = str_replace(
                array('__USER__', '__BR__', '__FEATURES_NUM__', '__SITENAME__'),
                array($user_obj->display_name, "<br>", TMM_Cardealer_User::get_user_free_features_count($user_id), get_bloginfo('name')),
                $message );

           // TMM_Cardealer_User::send_email($email, $subject, $message);
        }

        return $message_num;
    }


    public static function getSingleRowDataByToken($token){
        global $wpdb;
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
                                tmm_stripe_checkout_history.token ="'.$token.'"');

        return $details->packet_id;
    }



	/**
	 * @param $result
	 * @param $status
	 */
	static function updatePayment($result, $status)
	{
		global $wpdb;

		if (!isset($result['PAYMENTINFO_0_TRANSACTIONID']) || !isset($result['TOKEN'])) {
			header('Location: ' . get_permalink(get_option('cancel_page')));
		}

		$update_data = array(
			'transaction_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
			'status' => $status
		);

		$where = array('token' => $result['TOKEN']);

		$update_format = array('%s', '%s');

		$wpdb->update('tmm_stripe_checkout_history', $update_data, $where, $update_format);
	}
}