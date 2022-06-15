<?php

/**
 * Plugin Name:       Toolbar Hider
 * Description:       This plugin hides the admin toolbar.
 * Version:           1.0
 * Author:            Edvin Lindahl
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

/* Disable WordPress Admin Bar On Site */

add_filter('show_admin_bar', 'wpc_hide_toolbar');

function wpc_hide_toolbar()
{
    return false;
}


function wpc_admin_menu()
{
    add_menu_page(
        'Toolbar Hider Page',
        'WPC Toolbar Hider',
        'manage_options',
        'toolbarhider',
        'menu_page_text',
        'dashicons-hidden',
        20,

    );
}
add_action('admin_menu', 'wpc_admin_menu');

function menu_page_text()
{
    echo "<h1>Toolbar Hider</h1>";
    echo "<h4>This plugin hides the admin toolbar.</h4>";
}




//GARBAGE CODE
/*
add_action('wpc_hide_it', 'wpc_hide_toolbar');
*/


/* alternative method 
show_admin_bar(false);
*/


/* Change login message color to red 

function wpc_add_style()
{
?>
    <style type="text/css">
        .message {
            color: red;
        }
    </style>
<?php
}

add_action('login_enqueue_scripts', 'wpc_add_style');

*/