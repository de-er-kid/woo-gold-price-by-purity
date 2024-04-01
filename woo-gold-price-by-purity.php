<?php

/**
* Plugin Name: WooCommerce Gold Price Based by Purity
* Plugin URI:        #
* Description:       Add a global price per 1 gram of Gold based on 24 karat, 22 karat, 18 karat and 14 karat and then use the weight of each product to automatically calculate its price based on this rate.
* Version:           1.0
* Author:            Sinan
* Author URI:        #
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       gold-price-based-on-Purity
* Domain Path:       /languages
*/


define( 'WooGoldPBP__FILE__', __FILE__ );
define( 'WooGoldPBP_PATH', plugin_dir_path( WooGoldPBP__FILE__ ) );
define( 'WooGoldPBP_URL', plugins_url( '/', WooGoldPBP__FILE__ ) );

define( 'WooGoldPBP_ASSETS_PATH', WooGoldPBP_PATH . 'assets/' );
define( 'WooGoldPBP_ASSETS_URL', WooGoldPBP_URL . 'assets/' );

require WooGoldPBP_PATH . 'includes/plugin.php';
require WooGoldPBP_PATH . 'shortcode/price-breakup.php';

