<?php
/*
Plugin Name: Pico
Plugin URI: https://wordpress.org/plugins/pico
Description: Intelligent popups and landing pages to fully manage email signups, newsletters, subscriptions, donations, and memberships.
Version: 0.8.1
Author: Pico
Author URI: https://www.pico.tools
Network: false
*/

define( 'PICO_VERSION', '0.8.1' );
define( 'PICO__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PICO__MINIMUM_WP_VERSION', '3.7' );

register_activation_hook( __FILE__, array( 'Pico_Setup', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Pico_Setup', 'plugin_deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Pico_Setup', 'plugin_uninstall' ) );

require_once( PICO__PLUGIN_DIR . 'includes/class.pico.php' );
require_once( PICO__PLUGIN_DIR . 'includes/class.setup.php' );
require_once( PICO__PLUGIN_DIR . 'includes/class.widget.php' );
require_once( PICO__PLUGIN_DIR . 'includes/class.api.php' );
require_once( PICO__PLUGIN_DIR . 'includes/utils.php' );

add_action( 'init', array( 'Pico_Widget', 'init' ) );

if ( is_admin() ) {
    require_once( PICO__PLUGIN_DIR . 'includes/class.menu.php' );
	add_action( 'init', array( 'Pico_Menu', 'init' ) );
}
