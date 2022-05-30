<?php

/*
 * Pretty print when debugging is enabled in options
 */
if (!function_exists('wpgetapi_pp')) {
    function wpgetapi_pp( $array ) {
        echo '<pre style="white-space:pre-wrap;">';
            print_r( $array );
        echo '</pre>' . "\n";
    }
}


/**
 * Dropdown options for results_format
 * It is set up this way so we can easily add the filter to be able to add extra format options
 * 
 */
function wpgetapi_results_format_options( $field ) {
    $options = apply_filters( 'wpgetapi_results_format_options', array(
        'json_string' => 'JSON string',
        'json_decoded' => 'PHP array data',
    ) );
    return $options;
}


/**
 * Recursive sanitation for text or array
 * 
 * @param $array_or_string (array|string)
 * @since  1.0.0
 * @return mixed
 */
function wpgetapi_sanitize_text_or_array($array_or_string) {
    if( is_string($array_or_string) ){
        $array_or_string = sanitize_text_field($array_or_string);
    }elseif( is_array($array_or_string) ){
        foreach ( $array_or_string as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = wpgetapi_sanitize_text_or_array($value);
            }
            else {
                $value = sanitize_text_field( $value );
            }
        }
    }

    return $array_or_string;
}