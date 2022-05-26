<?php

/**
 * Plugin Name:       Toolbar Hider
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Hide the Admin Toolbar
 * Version:           1.0
 * Author:            Edvin Lindahl
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

 /* Disable WordPress Admin Bar for all users */
add_filter( 'show_admin_bar', '__return_false' );