<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The main class
 *
 * @since 1.0.0
 */
class WpGetApi_Api {

    public $encryption = ''; // the encryption class

    public $setup = ''; // store the setup data from the admin page
    public $api = ''; // store the api's from the admin page

    public $api_id = ''; // the api_id that we are wanting to get
    public $endpoint_id = ''; // the endpoint_id that we are wanting to get
    public $args = ''; // the args
    public $keys = ''; // the keys we want to retrieve

    public $base_url = ''; // base url of the API
    public $method = 'GET'; // the endpoint method GET POST etc
    public $cache_time = ''; // the time to cache
    public $endpoint = ''; // the endpoint that we will get
    public $query_parameters = ''; // the query paramaters appended to url
    public $header_parameters = ''; // the header paramaters
    public $body_parameters = ''; // the body paramaters
    public $body_json_encode = ''; // body_json_encode body
    public $body_url_encode = ''; // urlencode body
    
    public $response = ''; // the returned response

    public $final_url = ''; // final URL
    public $final_request_args = array(); // final request_args

    public $results_format = 'json_string'; // reseults format

    public $timeout = 10; // the timeout
    public $sslverify = 1; // the paramaters
    

    /**
     * Main constructor
     *
     * @since 1.0.0
     *
     */
    public function __construct( $api_id = '', $endpoint_id = '', $args = array(), $keys = array() ) {

        $this->setup        = get_option( 'wpgetapi_setup' );
        $this->api          = get_option( 'wpgetapi_' . $api_id );

        $this->encryption   = new WpGetApi_Encryption();
        $this->api_id       = sanitize_text_field( $api_id );
        $this->endpoint_id  = sanitize_text_field( $endpoint_id );
        $this->keys         = $keys;
        $this->args         = $args;

        add_filter( 'wpgetapi_raw_data', array( $this, 'maybe_add_debug_info' ), 5, 2 );

    }


    /**
     * Init our api data
     */
    public function init() {

        // do some checks 
        if( empty( $this->setup ) || ! isset( $this->setup['apis'] ) || empty( $this->setup['apis'] ) )
            return 'No API found.';

        // do some checks 
        if( ! $this->api || ! isset( $this->api['endpoints'] ) || empty( $this->api['endpoints'] ))
            return 'API not found.';

        // loop through api's
        // set base_url
        foreach ( $this->setup['apis'] as $index => $api_item ) {
            if( $this->api_id == $api_item['id'] ) {
                $this->base_url = isset( $api_item['base_url'] ) && ! empty( $api_item['base_url'] ) ? esc_url_raw( $api_item['base_url'] ) : '';
            }
        }

        // loop through endpoints
        foreach ( $this->api['endpoints'] as $index => $endpoint ) {

            if( $this->endpoint_id == $endpoint['id'] ) {
                
                $this->method = isset( $endpoint['method'] ) && $endpoint['method'] != '' ? sanitize_text_field( $endpoint['method'] ) : 'GET';
                $this->cache_time = isset( $endpoint['cache_time'] ) && ! empty( $endpoint['cache_time'] ) ? absint( $endpoint['cache_time'] ) : '';
                $this->endpoint = isset( $endpoint['endpoint'] ) && ! empty( $endpoint['endpoint'] ) ? sanitize_text_field( $endpoint['endpoint'] ) : '';

                $this->query_parameters = isset( $endpoint['query_parameters'] ) && ! empty( $endpoint['query_parameters'] ) ? $this->do_parameters( $endpoint['query_parameters'] ) : array();
                
                $this->header_parameters = isset( $endpoint['header_parameters'] ) && ! empty( $endpoint['header_parameters'] ) ? $this->do_parameters( $endpoint['header_parameters'] ) : array();
                
                $this->body_parameters = isset( $endpoint['body_parameters'] ) && ! empty( $endpoint['body_parameters'] ) ? $this->do_parameters( $endpoint['body_parameters'] ) : array();

                $this->body_json_encode = isset( $endpoint['body_json_encode'] ) && $endpoint['body_json_encode'] == 'true' ? true : false;
                $this->body_url_encode = isset( $endpoint['body_json_encode'] ) && $endpoint['body_json_encode'] == 'url' ? true : false;

                $this->results_format = isset( $endpoint['results_format'] ) ? sanitize_text_field( $endpoint['results_format'] ) : $this->results_format;
            } 
        }

        $this->timeout = isset( $this->api['timeout'] ) && ! empty( $this->api['timeout'] ) ? $this->api['timeout'] : $this->timeout;
        $this->sslverify = isset( $this->api['sslverify'] ) ? absint( $this->api['sslverify'] ) : $this->sslverify;
        
    }


    /**
     * The main function to output the data
     *
     * @param  string  $field Field to retrieve
     */
    public function endpoint_output() {
        return $this->do_the_call();
    }


    /**
     * Sort out the parameters if they exist
     */
    public function do_parameters( $parameters ) {
        
        $new_array = array();
        $parameters = $this->decrypt($parameters);

        if( is_array( $parameters ) ) {
            foreach ( $parameters as $key => $parameter ) {
                if( ! empty( $parameter['name'] ) && ! empty( $parameter['value'] ) ) {
                    $parameter['value'] = stripslashes( $parameter['value'] );
                    $new_array[ sanitize_text_field( $parameter['name'] ) ] = $parameter['value'];
                }
            }
        }

        // look for array or json string and sort these out - for using such things in shortcode
        if( is_array( $new_array ) ) {

            foreach ( $new_array as $key => $value ) {

                // checking if the parameters have a json string
                if ( substr( $value, 0, 1 ) === '{' && substr( $value, -1 ) === '}' ) {
                    $new_array[ $key ] = json_decode( $value, true);
                }

                // checking if the parameters have an array within the string
                // if they do, create them as an actual array
                if ( substr( $value, 0, 1 ) === '[' && substr( $value, -1 ) === ']' ) {
                    $new_array[ $key ] = array( $value );
                }

            }

        }

        return $new_array;

    }

    /**
     * Send the request and return our data
     */
    public function do_the_call() {
        
        $this->init();

        // build the URL
        $this->final_url = apply_filters( 'wpgetapi_final_url', $this->build_url(), $this );

        // build the request args
        $this->final_request_args = apply_filters( 'wpgetapi_final_request_args', $this->build_request_args(), $this );

        // POST request
        if( $this->method == 'POST' )
            $this->response = $this->POST_request();

        // GET request 
        if( $this->method == 'GET' )
            $this->response = $this->GET_request();

        // wp error
        if( is_wp_error( $this->response ) ) {
            return apply_filters( 'wpgetapi_raw_error_data', json_encode( $this->response ), $this );
        }

        $data = wp_remote_retrieve_body( $this->response );

        // returning in JSON format
        if( $this->results_format == 'json_string' || $this->results_format == 'json_decoded' )
            $data = $this->return_data( $data );

        return apply_filters( 'wpgetapi_raw_data', $data, $this );

    }


    /**
     * Build the request url with the ability to add args
     */
    public function build_url() {

        // first make sure our endpoint starts with a slash
        if ( substr( $this->endpoint, 0, 1 ) != '/' )
            $this->endpoint = '/' . $this->endpoint;

        $url = untrailingslashit( $this->base_url ) . $this->endpoint;

        // add empty array - was getting errors if it was just null or false
        $params = empty( $this->query_parameters ) ? array(''=>'') : $this->query_parameters;

        return add_query_arg( $params, $url );

    }


    /**
     * build the header with the ability to add args
     */
    public function build_request_args() {

        // add our header parameters to the headers
        $headers = apply_filters( 'wpgetapi_header_parameters', array( 
            'headers' => $this->header_parameters,
        ), $this );


        // if doing POST request, include body paramters
        if( $this->method == 'POST' ) {
                
            $this->body_parameters = apply_filters( 'wpgetapi_body_parameters', $this->body_parameters, $this );

            // do we json_encode the entire body as a string
            if( $this->body_json_encode == true )
                $this->body_parameters = json_encode( $this->body_parameters );

            
            // do we urlencode the entire body as a string
            if( $this->body_url_encode == true ) {
                $data_raw = '';
                foreach ($this->body_parameters as $key => $value){
                    $data_raw = $data_raw . $key . "=" . urlencode($value) . "&";
                }
                $this->body_parameters = $data_raw;
                
            }

            // add our body parameters
            if( $this->body_parameters && ! empty( $this->body_parameters ) )
                $headers['body'] = $this->body_parameters;  

        }

        // add headers to our final request args
        $this->final_request_args = wp_parse_args( $headers, $this->final_request_args );

        // build the args taht are sent to the request
        $default_args = apply_filters( 'wpgetapi_default_request_args_parameters', array( 
            'timeout' => $this->timeout,
            'sslverify' => $this->sslverify,
            'redirection' => 5,
            'blocking' => true,
            'cookies' => array(),
        ) );

        return wp_parse_args( $this->final_request_args, $default_args );

    }


    /**
     * Do a GET request
     */
    public function GET_request() {
        $response = '';
        // so we can so something before the remote_get (caching)
        $response = apply_filters( 'wpgetapi_before_get_request', $response, $this );
        if( empty( $response ) )
            $response = wp_remote_get( $this->final_url, $this->final_request_args );

        return $response;
    }


    /**
     * Do a POST request
     */
    public function POST_request() {
        $response = wp_remote_post( $this->final_url, $this->final_request_args );
        return $response;
    }


    /**
     * return data
     */
    public function return_data( $data ) {

        // converts all data to arrays, removing objects
        $data = json_decode( $data, true );
        $data = apply_filters( 'wpgetapi_json_response_body', $data, $this->keys );
        
        if( $this->results_format == 'json_string' )
            return trim( json_encode( $data ),'"');
      
        if( $this->results_format == 'json_decoded' )
            return $data;

    }


    /**
     * decrypt our parameters from the DB
     */
    public function decrypt( $array_or_string ) {

        if( is_string($array_or_string) ){
            $array_or_string = $this->encryption->decrypt($array_or_string);
        }elseif( is_array($array_or_string) ){
            foreach ( $array_or_string as $key => &$value ) {
                if( $key === 'name' )
                    continue;
                if ( is_array( $value ) ) {
                    $value = $this->decrypt($value);
                }
                else {
                    $value = $this->encryption->decrypt($value);

                }
            }
        }
        return $array_or_string;
    }


    /**
     * debug
     */
    public function maybe_add_debug_info( $data, $this_data ) {

        if( isset( $this->args['debug'] ) && ( $this->args['debug'] === true || $this->args['debug'] === 'true' ) ) {

            ob_start(); ?>

<style>

.wpgetapi-debug {
    background: #f4f4f4;
    padding: 20px;
    border: 1px solid #ccc;
    margin: 20px;
    font-size: 15px;
}
.wpgetapi-debug
.wpgetapi-debug p
.wpgetapi-debug h5
.wpgetapi-debug h6 {
    color: #000;
    line-height: 1 !important;
    text-transform: none !important;
    font-family: sans-serif !important;
    letter-spacing: 0px !important;  
}
.wpgetapi-debug p.small {
    font-size: 85%;
    color: #999;
    margin-bottom: 15px !important
}
.wpgetapi-debug h5 {
    font-weight: bold;
    font-size: 18px;
    margin: 0 0 3px;
    text-transform: none !important;
}
.wpgetapi-debug .debug-item {
    margin: 10px 0;
    background: #fff;
    padding: 15px 20px;
    display: inline-block !important;;
    width: 100% !important;
    box-shadow: 0 0 5px rgba(0,0,0,0.1)
}
.wpgetapi-debug .debug-item h6 {
    font-weight: bold;
    font-size: 16px;
    margin: 0 0 3px;
    text-transform: none !important;
}
.wpgetapi-debug .debug-item .debug-result, 
.wpgetapi-debug .debug-item .debug-result pre {
    font-family: monospace !important;
    font-size: 15px !important;
    margin: 0 !important;
    padding: 10px 12px;
    background: #f8f8f8;
}
.wpgetapi-debug .debug-item .debug-result pre {
    border: none;
    padding: 5px
}

</style>

            <div class="wpgetapi-debug">
                
                <h5><strong>WPGetAPI, Debug Mode - Plugin Version <?php esc_html_e( WpGetApi()->version ); ?></strong></h5>
                <p class="small">Below you will find debug info for the current API call. To turn this off, set debug to false in your shortcode or template tag.<br>Connecting to API's can be tricky - if you require help, please see the <a target="_blank" href="https://wpgetapi.com/docs/">Documentation</a> and if you can't find the answer then please use the contact form on our <a target="_blank" title="Connect WordPress with external API" href="https://wpgetapi.com/">website</a> to get assistance.</p>

                <div class="debug-item">
                    <h6>Full URL</h6>
                    <p class="small">The full URL that is being called.</p>
                    <div class="debug-result"><?php esc_html_e( $this->final_url ); ?></div>
                </div>

                <div class="debug-item">
                    <h6>Data Output</h6>
                    <p class="small">The resulting output that has been returned from the API.</p>
                    <div class="debug-result"><?php wpgetapi_pp( $data ); ?></div>
                </div>

                <div class="debug-item">
                    <h6>Arguments</h6>
                    <p class="small">The arguments set within your shortcode or template tag.</p>
                    <div class="debug-result"><?php wpgetapi_pp( $this->args ); ?></div>
                </div>

                <div class="debug-item">
                    <h6>Query String</h6>
                    <p class="small">The query string parameters (if any) that you have set within the endpoint settings.</p>
                    <div class="debug-result"><?php wpgetapi_pp( $this->query_parameters ); ?></div>
                </div>

                <div class="debug-item">
                    <h6>Headers</h6>
                    <p class="small">The headers parameters (if any) that you have set within the endpoint settings.</p>
                    <div class="debug-result"><?php wpgetapi_pp( $this->header_parameters ); ?></div>
                </div>

                <div class="debug-item">
                    <h6>Body</h6>
                    <p class="small">The body parameters (if any) that you have set within the endpoint settings.</p>
                    <div class="debug-result"><?php wpgetapi_pp( $this->body_parameters ); ?></div>
                </div>

                <div class="debug-item">
                    <h6>Final Request Arguments</h6>
                    <p class="small">The final request arguments sent in the API request. Includes headers and body arguments.</p>
                    <div class="debug-result"><?php wpgetapi_pp( $this->final_request_args ); ?></div>
                </div>

                <div class="debug-item">
                    <h6>Response</h6>
                    <p class="small">The full response from the API request.</p>
                    <div class="debug-result"><?php wpgetapi_pp( $this->response); ?></div>
                </div>

            </div>

            <?php 

            return ob_get_clean();

        } else {

            return $data;

        }

    }


}