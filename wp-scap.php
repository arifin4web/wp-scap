<?php
/**
 * SCAP - Shutterstock Custom Affiliate Plugin.
 *
 * The Oficial Shutterstock Affiliate plugin for wordpress is great. However
 * I have found the Official Version of Shutterstock wordpress plugin somehow
 * not very suitable to use specially in case of adapting custom design needs. That 
 * is the primary reason to create this plugin i.e, to use the shutterstock 
 * Images to suit our custom design.You can get images with supplied keyword OR 
 * this plugins auto algorithm will generate the best suited images related to 
 * your current page/site content. Can be used anywhere. Provide Shortcode.
 * And there is also a template function in support of the Developer to make it easy 
 * to integrate according to their custom design needs.
 *
 * @package   SCAP
 * @author    Md. Arifin Ibne Matin<contact@arifinbinmatin.com>
 * @license   GPL-2.0+
 * @link      http://arifinbinmatin.com
 * @copyright 2014 Md. Arifin Ibne Matin
 *
 * @wordpress-plugin
 * Plugin Name:       SCAP-Shutterstock Custom Affiliate Plugin
 * Plugin URI:        http://arifinbinmatin.com/shutterstock-custom-affiliate-plugin/
 * Description:       This plugin will help you to show the shutterstock images Automatically related to your page content. You can get images with supplied Keyword OR this plugins auto algorithm will generate the best suited images related to your current page. Can be used anywhere. Provide both Shortcode and Template function to integrate according to your design needs. You must be a member of the <a href="http://affiliate.shutterstock.com/" title="Shutterstock Affiliate Program" target="_blank">Shutterstock Affiliate program</a> and have a Shutterstock API key to use this plugin.
 * Version:           1.0
 * Author:            Md. Arifin Ibne Matin<contact@arifinbinmatin.com>
 * Author URI:        http://arifinbinmatin,com/
 * Text Domain:       scap
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/arifin4web/wp-scap
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

//Shutterstock API Class
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-shutterstock-api.php' );
//Auto Keyword Selection Class
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-scap-autokeyword.php' );
//Define some functions for Template Use
require_once( plugin_dir_path( __FILE__ ) . 'includes/scap-template-functions.php' );

//Public facing plugin class
require_once( plugin_dir_path( __FILE__ ) . 'public/class-scap.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'SCAP', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'SCAP', 'deactivate' ) );

//Load Our Plugins main class
add_action( 'plugins_loaded', array( 'SCAP', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-scap-admin.php' );
	add_action( 'plugins_loaded', array( 'SCAP_Admin', 'get_instance' ) );

}