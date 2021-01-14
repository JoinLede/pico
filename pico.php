<?php
/**
 * Plugin Name: Pico
 * Plugin URI:  https://github.com/PicoNetworks/wordpress-plugin
 * Description: Signup and payment tools for the internet's most passionate communities
 * Version:     1.0.0
 * Author:      picoengineering
 * Author URI:  https://trypico.com
 * License:     GPL2
 */

define( 'PICO_VERSION', '1.0.0' );
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
