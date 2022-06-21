<?php
/**
 * Config class
 *
 * Usage:
 * $config = StripeConfig::getInstance();
 * $config->addItem('plugin_name', 'Test plugin');
 * echo $config->getItem('plugin_name');
 */

class StripeConfig {
    private static $c_instance;
    private $c_item = array();

    private function __construct() {}

    private function __clone() {}

    /**
     * Initialize the object one time
     * @return object
     */
    public static function getInstance() {
        if ( ! self::$c_instance ){
            self::$c_instance = new self;
        }
        return self::$c_instance;
    }

    /**
     * Add new item to configs
     * @param $item_name: name of the item
     * @param $item_value: item value
     */
    public function addItem($item_name, $item_value) {
        if ( !isset( $this->c_item[$item_name] ) ){
            $this->c_item[$item_name] = $item_value;
        }
    }

    /**
     * Get existing config item
     * @param $item_name: name of the item
     * @return void
     */
    public function getItem($item_name) {
        if ( isset( $this->c_item[$item_name] ) ){
            return $this->c_item[$item_name];
        }
	    return '';
    }
}