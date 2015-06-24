<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Morsel
 * @author    Nishant <nishant.n@cisinlabs.com>
 * @license   GPL-2.0+
 * @link      eatmorsel
 * @copyright 2014 Nishant
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
print_r($_REQUEST);
// @TODO: Define uninstall functionality here
