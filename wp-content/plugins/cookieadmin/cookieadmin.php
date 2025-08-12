<?php
/*
Plugin Name: CookieAdmin - Cookie Consent and Banner
Plugin URI: https://cookieadmin.net
Description: CookieAdmin provides easy to configure cookie consent banner with GDPR and CCPA law support.
Version: 1.0.2
Author: Softaculous
Author URI: https://www.softaculous.com
License: LGPL v2.1
License URI: https://www.gnu.org/licenses/old-licenses/lgpl-2.1.en.html
Text Domain: cookieadmin
*/

/*
 * This file belongs to the CookieAdmin plugin.
 *
 * (c) Softaculous <sales@softaculous.com>
 *
 * You can view the LICENSE file that was distributed with this source code
 * for copywright and license information.
 */
 
if (!defined('ABSPATH')){
    exit;
}

if(!function_exists('add_action')){
	echo 'You are not allowed to access this page directly.';
	exit;
}

// If COOKIEADMIN_VERSION exists then the plugin is loaded already !
if(defined('COOKIEADMIN_VERSION')) {
	return;
}

define('COOKIEADMIN_FILE', __FILE__);

//we need to load textdomain for language translation
function cookieadmin_load_textdomain() {
    load_plugin_textdomain( 'cookieadmin', false, dirname(plugin_basename( __FILE__ ) ) . '/languages/');
}

add_action( 'plugins_loaded', 'cookieadmin_load_textdomain' );

include_once(dirname(__FILE__).'/init.php');

// Activation & Deactivation Hooks
register_activation_hook(__FILE__, 'cookieadmin_activate');
register_deactivation_hook(__FILE__, 'cookieadmin_deactivate');

function cookieadmin_activate() {
	
	add_option('cookieadmin_version', COOKIEADMIN_VERSION);
	
	include_once(COOKIEADMIN_DIR . 'includes/database.php');
	
	\CookieAdmin\Database::activate();
	
	return true;
}

function cookieadmin_deactivate() {
	
	
	return true;
}

