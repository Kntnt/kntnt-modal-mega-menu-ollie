<?php
/**
 * Plugin Name:       Kntnt Modal Mega Menu for Ollie
 * Plugin URI:        https://github.com/Kntnt/kntnt-modal-mega-menu-ollie
 * Description:       Makes the Dropdown Menu from Ollie Menu Designer behave like a modal: it locks the page behind an open menu and scrolls a tall menu internally instead of running off the bottom of the screen.
 * Version:           0.1.1
 * Requires at least: 6.5
 * Requires PHP:      8.3
 * Requires Plugins:  ollie-menu-designer
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       kntnt-modal-mega-menu-ollie
 * Domain Path:       /languages
 *
 * @package Kntnt\Modal_Mega_Menu_Ollie
 * @since   0.1.0
 */

declare( strict_types = 1 );

// Prevent direct file access outside WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// The PHP floor, mirroring the `Requires PHP` header above. WordPress reads the
// header, but PHP itself cannot, so the guard below needs its own copy.
const KNTNT_MODAL_MEGA_MENU_OLLIE_MINIMUM_PHP = '8.3';

/**
 * Guards against running on a PHP version older than the declared floor.
 *
 * The plugin header already makes WordPress block activation on older
 * installs. This is a second line of defence for environments that load the
 * plugin outside the normal activation path: it shows an admin notice and
 * deactivates the plugin so it never reaches code that would fatally error.
 *
 * @since 0.1.0
 *
 * @return bool True when PHP meets the floor; false when the guard fires.
 */
function kntnt_modal_mega_menu_ollie_requirements_check(): bool {

	// Nothing to do when the runtime meets the requirement.
	if ( version_compare( PHP_VERSION, KNTNT_MODAL_MEGA_MENU_OLLIE_MINIMUM_PHP, '>=' ) ) {
		return true;
	}

	// Surface the problem as an admin notice.
	add_action(
		'admin_notices',
		static function (): void {
			$message = sprintf(
				/* translators: 1: required PHP version, 2: current PHP version. */
				__( 'Kntnt Modal Mega Menu for Ollie requires PHP %1$s or later. This server runs PHP %2$s. The plugin has been deactivated.', 'kntnt-modal-mega-menu-ollie' ),
				KNTNT_MODAL_MEGA_MENU_OLLIE_MINIMUM_PHP,
				PHP_VERSION,
			);
			printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html( $message ) );
		},
	);

	// Deactivate the plugin so WordPress does not try to load it again.
	add_action(
		'admin_init',
		static function (): void {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		},
	);

	return false;

}

// Abort before loading anything else if the runtime cannot support the plugin.
if ( ! kntnt_modal_mega_menu_ollie_requirements_check() ) {
	return;
}

// Load the PSR-4 autoloader for the plugin's own classes.
require_once __DIR__ . '/autoloader.php';

// Bootstrap the plugin singleton.
\Kntnt\Modal_Mega_Menu_Ollie\Plugin::get_instance( __FILE__ );
