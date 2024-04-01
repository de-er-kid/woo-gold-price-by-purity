<?php

function display_product_price_breakup_shortcode() {
    global $product;

    // Check if the product exists and is a WooCommerce product
    if ( ! is_a( $product, 'WC_Product' ) ) {
        return;
    }

    // Get the product price
    $product_price = wc_get_price_to_display( $product );

    // Replace the placeholder values with your actual values
    $product_name = $product->get_name();
	
	$product_gold_purity = get_post_meta($product->get_id(), 'jewellery_field', true);
	if ($product_gold_purity === '1') {
		$product_gold_purity_text = '24KT';
	} elseif ($product_gold_purity === '2') {
		$product_gold_purity_text = '22KT';
	} elseif ($product_gold_purity === '3') {
		$product_gold_purity_text = '18KT';
	} elseif ($product_gold_purity === '4') {
		$product_gold_purity_text = '14KT';
	}
	
	if ($product_gold_purity == '1') {
        $gold_rate = get_option('gold_24k');
    } elseif ($product_gold_purity == '2') {
        $gold_rate = get_option('gold_22k');
    } elseif ($product_gold_purity == '3') {
        $gold_rate = get_option('gold_18k');
    } elseif ($product_gold_purity == '4') {
        $gold_rate = get_option('gold_14k');
    }
	
	// Get the weight
	$weight = $product->get_weight();

	// Check the weight unit setting in WooCommerce
	$weight_unit = get_option('woocommerce_weight_unit'); // This will return 'kg' or 'lbs' or 'oz'

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
	
	$product_gold_price = $weight_in_grams * $gold_rate;
	
    $additional_charges = ($additional_charges = get_post_meta($product->get_id(), '_additional_charges', true)) ? $additional_charges : 0;
    $grand_total = wc_price($product->get_price());

    // HTML code to be displayed
    $output = '<div class="gold-product-price-breakup lg">
    
		<div class="heading-class">
		<div class="float-left">
		Component
		</div>
		<div class="ft-none">
		Gold Rate(' . $product_gold_purity_text . ')
		</div>
		<div class="ft-none">
		Weight (g)
		</div>
		<div class="ft-none">
		Discount
		</div>
		<div class="float-right ">
		Final Value
		</div>
		</div>

		<hr class="line-color">

		<div class="col-values">
		<div class="float-left">
		' . $product_name . '
		</div>
		<div class="ft-none">
		' . get_woocommerce_currency_symbol(). ''. $gold_rate .'/g
		</div>
		<div class="ft-none">
		' . $weight_in_grams . 'g
		</div>
		<div class="ft-none">
		-
		</div>
		<div class="float-right">
		' . get_woocommerce_currency_symbol(). '' . $product_gold_price . '
		</div>
		</div>

		<div class="col-values">
		<div class="float-left">
		Additional Charges
		</div>
		<div class="ft-none">
		-
		</div>
		<div class="ft-none">
		-
		</div>
		<div class="ft-none">
		-
		</div>
		<div class="float-right">
		' . get_woocommerce_currency_symbol(). '' . $additional_charges . '
		</div>
		</div>

		<hr class="line-color">

		<div class="col-values">
		<div class="float-left font-b">
		Grand Total
		</div>
		<div></div>
		<div></div>
		<div></div>
		<div class="float-right font-b">
		' . $grand_total . '
		</div>
		</div>

		</div>


		<div class="gold-product-price-breakup sm">
		<p class="prd-rate"> ' . $product_gold_purity_text . ' Gold Rate/Gram
		=
		' . get_woocommerce_currency_symbol(). ''. $gold_rate .'/g
		</p>
		<div class="heading-class">
		<div class="float-left">
		Component
		</div>
		<div class="float-right mob-rgt-cls">
		Final Value
		</div>
		</div>
		<hr class="line-color">
		<div class="col-values">
		<div class="float-left">
		' . $product_name . '
		</div>
		<div class="float-right mob-rgt-cls">
		' . get_woocommerce_currency_symbol(). '' . $product_gold_price . '
		</div>
		</div>
		<div class="col-values">
		<div class="float-left">
		Additional Charges
		</div>
		<div class="float-right mob-rgt-cls">
		' . get_woocommerce_currency_symbol(). '' . $additional_charges . '
		</div>
		</div>
		<hr class="line-color">
		<div class="col-values">
		<div class="float-left font-b">
		Grand Total
		</div>
		<div class="float-right mob-rgt-cls font-b">
		' . $grand_total . '
		</div>
		</div>
		</div>';

    return $output;
}
add_shortcode( 'gold_product_price_break_up', 'display_product_price_breakup_shortcode' );