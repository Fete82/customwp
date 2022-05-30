<?php

/**
 * Return the endpoint data
 */
function wpgetapi_endpoint( $api_id = '', $endpoint_id = '', $args = array(), $keys = array() ) {

    if( ! $api_id || ! $endpoint_id )
        return false;

    $api = new WpGetApi_Api( $api_id, $endpoint_id, $args, $keys );

    return $api->endpoint_output();

}

/**
 * Return the endpoint data via shortcode
 */
function wpgetapi_endpoint_shortcode( $atts = array() ) {
	
	$a = shortcode_atts( array(
		'api_id' => '',
		'endpoint_id' => '',
		'debug' => false,
		'args' => array(),
		'keys' => array(),
		'endpoint_variables' => array(),
		'query_variables' => array(),
		'format' => '',
	), $atts );

	if( ! isset( $a['api_id'] ) || $a['api_id'] == '' )
		return 'api_id shortcode attribute is not set.';

	if( ! isset( $a['endpoint_id'] ) || $a['endpoint_id'] == '' )
		return 'endpoint_id shortcode attribute is not set.';

	// sort out our keys if using them
	if( ! empty( $a['keys'] ) ) {
		// Create our array of values for keys
	    // First, sanitize the data and remove white spaces
	    $no_whitespaces_keys = preg_replace( '/\s*,\s*/', ',', filter_var( $a['keys'], FILTER_SANITIZE_STRING ) );
	    $a['keys'] = explode( ',', $no_whitespaces_keys );
	}

	// sort out our endpoint_variables if using them
	if( ! empty( $a['endpoint_variables'] ) ) {
		// Create our array of values for endpoint_variables
	    // First, sanitize the data and remove white spaces
	    $no_whitespaces_vars = preg_replace( '/\s*,\s*/', ',', filter_var( $a['endpoint_variables'], FILTER_SANITIZE_STRING ) ); 
	    $a['endpoint_variables'] = explode( ',', $no_whitespaces_vars );
	}

	// add our shortcode args to the actual 'args' within the endpoint call
	$a['args']['debug'] = $a['debug'];
	$a['args']['endpoint_variables'] = $a['endpoint_variables'];
	$a['args']['query_variables'] = $a['query_variables'];
	$a['args']['format'] = $a['format'];
	$a['args']['shortcode'] = true;

	return wpgetapi_endpoint( $a['api_id'], $a['endpoint_id'], $a['args'], $a['keys'] );

}
add_shortcode( 'wpgetapi_endpoint', 'wpgetapi_endpoint_shortcode' );
