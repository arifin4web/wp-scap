<?php
/**
 * Fired when the plugin is uninstalled. We don Have anythig special to do here
 * for now.
 *
 * @package   Shutterstock Custom Affiliate Plugin
 * @author    Md. Arifin Ibne Matin<contact@arifinbinmatin.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Md. Arifin Ibne Matin
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}