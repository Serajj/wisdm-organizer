<?php
/*
Plugin Name: WISDM Organizer

Plugin URI: https://www.wisdmlabs.com/

Description: A simple event and venue manager.

Author: Alpha Bots

Author URI: https://www.wisdmlabs.com

Text Domain: wsdm-organizer

Domain Path: /languages

Version: 1.0.0

Since: 1.0.0

Requires WordPress Version at least: 5

Copyright: 2022 Wisdm Event

License: GNU General Public License v3.0

License URI: http://www.gnu.org/licenses/gpl-3.0.html

**/

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

if ( ! defined( 'WSDM_ORG_FILE' ) ) {
	define( 'WSDM_ORG_FILE', __FILE__ );
}

if ( ! defined( 'WSDM_PLUGIN_VERSION' ) ) {
	define( 'WSDM_PLUGIN_VERSION', '1.0.0' );
}

if ( ! defined( 'WSDM_RECOMENDED_LDRP_PLUGIN_VERSION' ) ) {
	define( 'WSDM_RECOMENDED_LDRP_PLUGIN_VERSION', '1.5.0' );
}

// Constant for text domain.
if ( ! defined( 'WSDM_ORG_PATH' ) ) {
	define( 'WSDM_ORG_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'WSDM_ORG_SITE_URL' ) ) {
	/**
	 * The constant CSP_PLUGIN_SITE_URL contains the url path to the plugin directory
	 * eg. https://example.com/wp-content/plugins/block-sample/
	 */
	define( 'WSDM_ORG_SITE_URL', untrailingslashit( plugins_url( '/', WSDM_ORG_FILE ) ) );
}

include( 'classes/wsdm-register-post-types.php' );
//include( 'classes/wsdm-custom-meta-box.php' );
include( 'includes/wsdm-functions.php' );

include('classes/wsdm-shorcodes.php');

Wsdm_Register_Post_Types::instance();

