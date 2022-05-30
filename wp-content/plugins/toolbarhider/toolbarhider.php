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

/* alternative method 
show_admin_bar(false);
*/


/* Change login message color to red */
add_action ('login_enqueue_scripts', 'wpc_add_style');

function wpc_add_style() { 
    ?>
    <style type="text/css">
        .message {
            color: red;
        }
    </style>
<?php 
}
