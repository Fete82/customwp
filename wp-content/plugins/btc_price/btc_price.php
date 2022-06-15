<?php

/**
 * Plugin Name: BTC Price Extractor
 * Description: Plugin to extract price of bitcoin in USD.
 * Version: 1.0
 * Author: Edvin Lindahl
 **/

function gutenberg_examples_01_register_block() {
    register_block_type( __DIR__ );
}
add_action( 'init', 'gutenberg_examples_01_register_block' );



function plugin_btc_activate()
{
    $api_url = 'https://api.binance.com/api/v3/ticker/price';
    $json_data = file_get_contents($api_url);

    set_transient('binance_data', $json_data, 0);
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'plugin_btc_activate');

function plugin_btc_deactivate()
{
    delete_transient('binance_data');
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'plugin_btc_deactivate');


function cwp_set_transient()
{
    delete_transient('binance_data');

    $api_url = 'https://api.binance.com/api/v3/ticker/price';
    $json_data = file_get_contents($api_url);

    set_transient('binance_data', $json_data, 0);
}

function cwp_price_shortcode( $atts ) {
    $response_data = json_decode(get_transient('binance_data'));
    $data_one = $response_data[11];

    $ticker = substr_replace($data_one->symbol, "", -1);
    $price = number_format($data_one->price, 0, '.', ',');
    $satoshis = number_format(100000000, 0, '.', ',');
    
    $format = "The price of %s is %s USD or %s Satoshis.";
    return sprintf($format, $ticker, $price, $satoshis);
}
add_shortcode( 'btc_price', 'cwp_price_shortcode' );