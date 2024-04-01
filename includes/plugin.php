<?php

// Enqueue the admin styles
add_action('admin_enqueue_scripts', 'WooGoldPBP_init');
function WooGoldPBP_init() {
    wp_enqueue_style(
        'Admin-WooGoldPBP-style',
        WooGoldPBP_ASSETS_URL . 'css/admin-style-gold.css'
    );
}

// Enqueue the custom JavaScript file
add_action('admin_enqueue_scripts', 'enqueue_custom_script');
function enqueue_custom_script() {
    wp_enqueue_script('admin-WooGoldPBP-script', WooGoldPBP_ASSETS_URL . 'js/admin-script-gold.js', array('jquery'), '1.0', true);
}

/**
 * Add documentation link into plugin page
 */
add_filter('plugin_row_meta', 'WooGoldPBP_plugin_row_meta', 10, 2);
function WooGoldPBP_plugin_row_meta($links, $file) {
    if ('woo-gold-price-by-purity/woo-gold-price-by-purity.php' == $file) {
        $row_meta = array(
            'docs' => '<a href="' . esc_url('https://github.com/de-er-kid/woo-gold-price-by-purity') . '" target="_blank" aria-label="' . esc_attr__('Plugin Additional Links', 'gold-price-based-on-weight') . '" style="color:green;">' . esc_html__('Documentation', 'gold-price-based-on-weight') . '</a>'
        );

        return array_merge($links, $row_meta);
    }
    return (array) $links;
}

// Add the "Select Gold" field to the product meta box
add_action('woocommerce_product_options_general_product_data', 'woo_add_jewellery_field');
function woo_add_jewellery_field() {
    global $post;

    $product = wc_get_product($post->ID);
    $weight = $product->get_weight();
    $additional_charges = get_post_meta($post->ID, '_additional_charges', true);

    $field = array(
        'id' => 'jewellery_field',
        'label' => __('Select Gold', 'store'),
        'desc_tip' => true,
        'description' => __('Select an option.', 'ctwc'),
        'options' => array(
            '0' => __('Select Option', 'woocommerce'),
            '1' => __('Gold - 24k', 'woocommerce'),
            '2' => __('Gold - 22k', 'woocommerce'),
            '3' => __('Gold - 18k', 'woocommerce'),
            '4' => __('Gold - 14k', 'woocommerce'),
        ),
    );

    woocommerce_wp_select($field);

    // Display the additional fields after the jewellery field
    woocommerce_wp_text_input(array(
        'id' => '_weight',
        'label' => __('Product Weight (' . get_option('woocommerce_weight_unit') . ')', 'woocommerce'),
        'desc_tip' => true,
        'description' => __('Enter the product weight.', 'woocommerce'),
        'value' => $weight,
    ));

    woocommerce_wp_text_input(array(
        'id' => 'additional_charges_field',
        'label' => __('Additional Charges(' . get_woocommerce_currency_symbol() . ')', 'woocommerce'),
        'desc_tip' => true,
        'description' => __('Enter additional charges for the product.', 'woocommerce'),
        'value' => $additional_charges,
    ));
}

// Save product weight meta data
add_action('woocommerce_process_product_meta', 'woo_add_jewellery_field_save');
function woo_add_jewellery_field_save($post_id) {
    $jewellery_field = $_POST['jewellery_field'];
    if (!empty($jewellery_field)) {
        update_post_meta($post_id, 'jewellery_field', esc_attr($jewellery_field));
    } else {
        update_post_meta($post_id, 'jewellery_field', '');
    }

    $weight = $_POST['_weight'];
    // Debugging output
    error_log('Weight from _weight: ' . $weight);
    
    // Update the product weight meta field
    update_post_meta($post_id, '_weight', esc_attr($weight));

    // Debugging output to verify if the weight is updated in the meta field
    $updated_weight = get_post_meta($post_id, '_weight', true);
    error_log('Updated weight: ' . $updated_weight);

    $additional_charges = $_POST['additional_charges_field'];
    update_post_meta($post_id, '_additional_charges', esc_attr($additional_charges));
}

// Add a menu page for the Gold Price settings
function GoldPriceMenu() {
    add_menu_page(
        __('Gold Price', 'gold-price-based-on-Purity'),
        __('Gold Price', 'gold-price-based-on-Purity'),
        'manage_options',
        'gold-price-by-purity',
        'GoldPricePageMenu',
        'dashicons-money-alt',
        3
    );
}
add_action('admin_menu', 'GoldPriceMenu');

// Render the Gold Price settings page
function GoldPricePageMenu() {
    ?>
    <div class="gldprcprt-gold-price">
        <div class="gldprcprt-wrapper">
            <div class="gldprcprt-boxes gldprcprt-header">
                <div class="gldprcprt-box gldprcprt-page-title">
                    <h1>
                        <?php esc_html_e('Gold Price : Based On Weight & Purity'); ?>
                    </h1>
                </div>
            </div>
            
            <div class="gldprcprt form-wrap">
                <div id="gldprcprt-container" class="wp-clearfix">
                    <div class="gldprcprt-sub-title">
                        <p><?php esc_html_e("Establishing a global price per gram of Gold based on purity and utilizing the weight of each product, the price can be automatically calculated based on this rate, making the pricing process more efficient and accurate for all Woo-Commerce simple products and variable products."); ?></p>
                    </div>
                    <form method="POST" action="options.php" class="gldprcprt-form">
                        <?php
                            settings_fields('gold-price');
                            do_settings_sections('gold-price');
                            submit_button();
                        ?>
                    </form>
					<p>Use the shortcode <code>[gold_product_price_break_up]</code> to dispaly product price breakup in single product template, also compatible with theme builder plugins files like <strong>Elementor Pro</strong>.</p>
                </div>
            </div>
            <div class="gldprcprt-footer">
                <div class="gldprcprt-box gldprcprt-page-title">
                    <h3>
                        <?php esc_html_e('Gold Price : Based On Weight'); ?>
                    </h3>
                </div>
                <div class="gldprcprt-box gldprcprt-logo">
                    <label>Developed By - <a href="https://profiles.wordpress.org/sinaaan/" target="_blank">Sinan</a></label>
                </div>
            </div>
        </div>
    </div>
    <?php
}

// Initialize the Gold Price settings
add_action('admin_init', 'GoldPriceInit');
function GoldPriceInit() {
    add_settings_section(
        'gold_price_setting_section',
        __("", 'gold-price-based-on-Purity'),
        'GoldPriceCBFun',
        'gold-price'
    );
    add_settings_field(
        'gold_24k',
        'Gold 24k Price',
        'GoldPriceSetting',
        'gold-price',
        'gold_price_setting_section',
        array('gold_24k')
    );
    add_settings_field(
        'gold_22k',
        'Gold 22k Price',
        'GoldPriceSetting',
        'gold-price',
        'gold_price_setting_section',
        array('gold_22k')
    );
    add_settings_field(
        'gold_18k',
        'Gold 18k Price',
        'GoldPriceSetting',
        'gold-price',
        'gold_price_setting_section',
        array('gold_18k')
    );
    add_settings_field(
        'gold_14k',
        'Gold 14k Price',
        'GoldPriceSetting',
        'gold-price',
        'gold_price_setting_section',
        array('gold_14k')
    );
    register_setting('gold-price', 'gold_24k', 'esc_attr');
    register_setting('gold-price', 'gold_22k', 'esc_attr');
    register_setting('gold-price', 'gold_18k', 'esc_attr');
    register_setting('gold-price', 'gold_14k', 'esc_attr');
}
function GoldPriceCBFun() {
    esc_html_e('', 'gold-price-based-on-Purity');
}
function GoldPriceSetting($args) {
    $option = get_option($args[0]);
    echo '<input type="text" id="' . $args[0] . '" name="' . $args[0] . '" value="' . $option . '" />';
}

// Calculate the gold price based on purity
function GoldPriceCalc($metals) {
    if ($metals == '1') {
        return get_option('gold_24k');
    }
    if ($metals == '2') {
        return get_option('gold_22k');
    }
    if ($metals == '3') {
        return get_option('gold_18k');
    }
    if ($metals == '4') {
        return get_option('gold_14k');
    }
}

// Simple, grouped and external products
add_filter('woocommerce_product_get_price', 'GoldPrice', 99, 2);
add_filter('woocommerce_product_get_regular_price', 'GoldPrice', 99, 2);

// Variations
add_filter('woocommerce_product_variation_get_regular_price', 'GoldPrice', 99, 2);
add_filter('woocommerce_product_variation_get_price', 'GoldPrice', 99, 2);
function GoldPrice($price, $product) {
    $data_value = '';
    $data = $product->get_data();
    $meta_value = get_post_meta($data['id'], '', true);
    foreach ($meta_value as $key => $value) {
        if ($key == 'jewellery_field') {
            $data_value = $value[0];
        }
    }

    if ($data_value == 1 || $data_value == 2 || $data_value == 3 || $data_value == 4) {
		// Get the weight
		$weight = $product->get_weight();

		// Check the weight unit setting in WooCommerce
		$weight_unit = get_option('woocommerce_weight_unit');

		// Convert weight to grams if necessary
		if ($weight_unit === 'kg') {
			$weight_in_grams = $weight * 1000;
		} elseif ($weight_unit === 'lbs') {
			$weight_in_grams = $weight * 453.592;
		} elseif ($weight_unit === 'oz') {
			$weight_in_grams = $weight * 28.3495;
		} else {
			$weight_in_grams = $weight;
		}
        $additional_charges = get_post_meta($data['id'], '_additional_charges', true);
        $price = (float) $weight_in_grams * GoldPriceCalc($data_value) + (float) $additional_charges;
        return $price;
    } else {
        return (float) $price;
    }
}

// Add the "Select Gold" field to the variation meta box
add_action('woocommerce_variation_options_pricing', 'gold_price_add_custom_field_to_variations', 90, 3);
function gold_price_add_custom_field_to_variations($loop, $variation_data, $variation) {
    $jewellery_field = array(
        'id' => 'jewellery_field_variation[' . $loop . ']',
        'label' => __('Select any of this Gold', 'store'),
        'desc_tip' => true,
        'options' => array(
            '0' => __('Select Option', 'woocommerce'),
            '1' => __('Gold - 24k', 'woocommerce'),
            '2' => __('Gold - 22k', 'woocommerce'),
            '3' => __('Gold - 18k', 'woocommerce'),
            '4' => __('Gold - 14k', 'woocommerce'),
        ),
        'value' => get_post_meta($variation->ID, 'jewellery_field_variation', true)
    );
    woocommerce_wp_select($jewellery_field);
}

// Save the "Select Gold" field value for variations
add_action('woocommerce_save_product_variation', 'gold_price_save_custom_field_variations', 10, 2);
function gold_price_save_custom_field_variations($variation_id, $i) {
    $jewellery_field_variation = $_POST['jewellery_field_variation'][$i];
    if (isset($jewellery_field_variation)) update_post_meta($variation_id, 'jewellery_field_variation', esc_attr($jewellery_field_variation));
}

// Variable
add_filter('woocommerce_product_variation_get_regular_price', 'custom_price', 99, 2);
add_filter('woocommerce_product_variation_get_price', 'custom_price', 99, 2);

// Variations (of a variable product)
add_filter('woocommerce_variation_prices_price', 'custom_price', 99, 3);
add_filter('woocommerce_variation_prices_regular_price', 'custom_price', 99, 3);

// Calculate the price for variations
function custom_price($price, $product) {
    $data_value = '';
    $meta_value = get_post_meta($product->get_id(), '', true);
    foreach ($meta_value as $key => $value) {
        if ($key == 'jewellery_field_variation') {
            $data_value = $value[0];
        }
    }
	
	// Get the weight
	$weight = $product->get_weight();

	// Check the weight unit setting in WooCommerce
	$weight_unit = get_option('woocommerce_weight_unit');

	// Convert weight to grams if necessary
	if ($weight_unit === 'kg') {
		$weight_in_grams = $weight * 1000;
	} elseif ($weight_unit === 'lbs') {
		$weight_in_grams = $weight * 453.592;
	} elseif ($weight_unit === 'oz') {
		$weight_in_grams = $weight * 28.3495;
	} else {
		$weight_in_grams = $weight;
	}

    $additional_charges = get_post_meta($product->get_id(), '_additional_charges', true);

    if ($data_value == 1 || $data_value == 2 || $data_value == 3 || $data_value == 4) {
        $price = (float) GoldPriceCalc($data_value) * $weight_in_grams + (float) $additional_charges;
        return $price;
    } else {
        return (float) $price;
    }
}

// Custom CSS for variation meta box
add_action('admin_head', 'my_custom_css');
function my_custom_css() {
    echo '<style>
    .variable_pricing {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }
    .variable_pricing p {
        float: none !important;
        display: block;
        width: 100%;
    }
    .variable_pricing p select{
        width: 100%;
        max-width:100%;
        height: 44px;
        margin-top: 5px;
    }
    </style>';
}