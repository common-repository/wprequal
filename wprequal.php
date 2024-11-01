<?php
/*
Plugin Name: WPrequal
Plugin URI:  https://wprequal.com
Description: Mortgage and Real Estate Lead Capture System
Version:     8.2.10
Author:      WPrequal
Author URI:  https://wprequal.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wprequal
Domain Path: /languages

WPrequal is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
WPrequal is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with WPrequal. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

$uploads = wp_get_upload_dir();

/**
 * Constants.
 *
 * Use array() for older versions of PHP.
 */

define( 'WPREQUAL_VERSION', '8.2.10' );
define( 'WPREQUAL_PLUGIN', plugin_basename( __FILE__ ) );
define( 'WPREQUAL_OPTIONS', 'wprequal_options' );
define( 'WPREQUAL_CAP', 'manage_options' );
define( 'WPREQUAL_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPREQUAL_URL', plugin_dir_url( __FILE__ ) );
define( 'WPREQUAL_ASSETS', WPREQUAL_URL . 'assets/' );
define( 'WPREQUAL_ASSETS_PATH', WPREQUAL_PATH . 'assets/' );
define( 'WPREQUAL_VIEWS', WPREQUAL_PATH . 'views/' );
define( 'WPREQUAL_ERROR_LOG_DIR', trailingslashit( $uploads['basedir'] ) . 'wprequal-logs/' );
define( 'WPREQUAL_API_EP_VERSION', 'v3' );

if ( ! defined( 'WPREQUAL_API_EVN' ) ) {
    define( 'WPREQUAL_API_EVN', 'com' );
}

if ( ! defined( 'WPREQUAL_SSL_VERIFY' ) ) {
    define( 'WPREQUAL_SSL_VERIFY', TRUE );
}

if ( ! defined( 'WPREQUAL_REQUEST_TIMEOUT' ) ) {
    define( 'WPREQUAL_REQUEST_TIMEOUT', 60 );
}

// Start the engine.
add_action( 'plugins_loaded', function() {

    // Autoload scripts.
    foreach ( glob( plugin_dir_path( __FILE__ ) . 'includes/**/*.php' ) as $file ) {
        require_once $file;
    }

	Core::instance()->actions();

	// Load text domain
	add_action( 'init', function() {
		load_plugin_textdomain( 'wprequal', FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	} );

    // Register endpoints.
    add_action( 'rest_api_init', function() {

        // Nonce endpoint.
        Nonce::instance()->register_route();
        // Entry endpoint.
        Entry::instance()->register_route();

    } );

    // Activate the plugin.
    register_activation_hook( __FILE__, 'WPrequal\activate' );

    // Deactivate the plugin.
    register_deactivation_hook( __FILE__, 'WPrequal\deactivate' );

}, 1 );
