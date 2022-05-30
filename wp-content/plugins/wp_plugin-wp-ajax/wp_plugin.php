<?php

/**
 * Plugin Name: WCM WP Plugin
 * Plugin URI: https://example.com/plugins/the-basics/
 * Description: Handle the basics with this plugin.
 * Version: 1.0.0
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * Author: John Smith
 * Author URI: https://author.example.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wcm-wp-plugin
 * Domain Path: /languages
 */


include 'class_GithubApi.php';
register_activation_hook( __FILE__, 'wcm_plugin_activated' );

//register_deactivation_hook(__FILE__, 'plugin_deactivated');

function wcm_plugin_activated() {
	// skapa custom posot types
	flush_rewrite_rules();
}

/**
 * Här läggs javscriptfilen för pluginet till. Vi registrerar den och sedan köar den.
 */
add_action( 'init', 'scripts_loader' );
function scripts_loader() {
	/* registrera scriptet */
	wp_register_script( 'wcm_plugin_script',
		plugins_url( '/js/wcm_plugin_script.js', __FILE__ ),
		['jquery'],
		'1.0.0',
		true );

	/* Läs här om wp_localize_script - https://developer.wordpress.org/reference/functions/wp_localize_script/
	 *
	 * Vi användder den här för att skapa en javascript variabel ("myAjax"),
	 * och ger den en array som value, med en key ("ajaxurl").
	 * Den vi kan använda i vår frontend js-kod för att lägga till rätt URL
	 * som vårt ajax anrop skickas till. Se wcm_plugin_script.js
	 *
	 * OBS! Man kan också välja att lägga action direkt på Formuläret, de behövs inte denna.
	 * Se exempel 2 i wcm_menu_page.php
	 * */
	wp_localize_script( 'wcm_plugin_script', 'myAjax',
		[
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		] );

	/* registrera scriptet */
	wp_enqueue_script( 'wcm_plugin_script' );
}

/**
 * För att kunna ta emot vårt Ajax request så måste vi ha en funktion som hanterar det.
 * Man lägger till dessa med två add_actions, den första gäller för inloggade användare,
 * och den andra för icke inloggade användare.
 */
add_action("wp_ajax_wcm_del_repos_action", "delete_repo_transients");
add_action("wp_ajax_nopriv_wcm_del_repos_action", "delete_repo_transients");
function delete_repo_transients() {

	// nonce check for an extra layer of security, the function will exit if it fails
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "wcm_repos_nonce")) {
		exit("Woof Woof Woof");
	}
	delete_transient('wcm_github_user_repos');

	echo json_encode( ['type' => 'success', 'message' => 'Transient deleted'] );
	wp_die();
}

// [wcm_hello]
/**
 * Här skapar vi en shortcode som går att använda i innehållet enligt: [wcm_hello]
 * Namnet inuti [] är fösta parametern till add_shortcode().
 */
add_shortcode( 'wcm_hello', 'wcm_says_hello' );
function wcm_says_hello( $atts = [], $content = null ) {
	//

	$content .= "<h2>Thing from shortcode function</h2>";

	//$latestPosts = new WP_Query();

	return $content;
}


/**
 * Här skapar vi en ny meny i admin.
 */
add_action( 'admin_menu', 'wcm_admin_menu' );
function wcm_admin_menu() {
	add_menu_page(
		'WCM Menu',
		'WCM Settings',
		'manage_options',
		'wcm_menu',
		'wcm_admin_menu_page',
		'dashicons-learn-more',
		20
	);
}

/* Vi har extraherat view koden för meny-sidan och inkludera det här */
function wcm_admin_menu_page() {
	$github = new class_GithubApi();
	$repos  = "";
	if ( isset( $_POST['delete_transients'] ) ) {
		delete_transient( 'wcm_github_user_repos' );
	}

	$repos = $github->getRepos();
	include plugin_dir_path( __FILE__ ) . 'admin/wcm_menu_page.php';
}

/**
 * Lägg till settings på vår nya admin sida
 */
function wcm_setttings_init() {
	register_setting( 'wcm_menu', 'wcm_github_token' );
	register_setting( 'wcm_menu', 'wcm_github_username' );

	/* Skapar en settings sektion */
	add_settings_section(
		'wcm_main_settings',
		'WCM Github Inställningar',
		'wcm_settings_sections_html',
		'wcm_menu'
	);

	/* Skapar fält för settings */
	add_settings_field(
		'wcm_settings_field',
		'API Nyckel',
		'wcm_api_field_html',
		'wcm_menu',
		'wcm_main_settings'
	);

	add_settings_field(
		'wcm_settings_field2',
		'Username',
		'wcm_api_field_html_username',
		'wcm_menu',
		'wcm_main_settings'
	);
}

add_action( 'admin_init', 'wcm_setttings_init' );

function wcm_settings_sections_html() {
	echo '<p>Här kommer lite inställningar</p>';
}

function wcm_api_field_html() {
	$api_key = get_option( 'wcm_github_token' );

	$output = '<input type="text" name="wcm_github_token" value="';
	$output .= isset( $api_key ) ? esc_attr( $api_key ) : '';
	$output .= '" />';

	echo $output;
}

function wcm_api_field_html_username() {
	$username = get_option( 'wcm_github_username' );

	$output = '<input type="text" name="wcm_github_username" value="';
	$output .= isset( $username ) ? esc_attr( $username ) : '';
	$output .= '" />';

	echo $output;
}


function get_github_user_data() {
	wp_remote_get( 'https://api.github.com/users/lauhakari' );
}

/**
 * Så här skapar man en ny post.
 */

/*
wp_insert_post(
	array(
		'post_title'   => 'Test',
		'post_content' => 'Test',
		'post_status'  => 'publish',
		'post_author'  => 1,
		'post_type'    => 'reviews',

		'meta_input'    => [
			'reviewer' => 'Namn från formulär.',
			'post_id' => ''
		]
	)
);*/



































