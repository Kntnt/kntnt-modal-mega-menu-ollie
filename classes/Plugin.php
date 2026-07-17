<?php
/**
 * Plugin singleton – bootstrap and asset wiring.
 *
 * Holds the absolute path to the main plugin file and registers the hooks that
 * load the stylesheet and script giving the mega menu its modal behaviour.
 *
 * @package Kntnt\Modal_Mega_Menu_Ollie
 * @since   0.1.0
 */

declare( strict_types = 1 );

namespace Kntnt\Modal_Mega_Menu_Ollie;

/**
 * Singleton entry point for the Kntnt Modal Mega Menu for Ollie plugin.
 *
 * Constructed once by get_instance(), only after the main plugin file has
 * established that the runtime can support the plugin. The constructor
 * registers every WordPress hook, so it stays the single authoritative place to
 * trace the hook graph.
 *
 * @since 0.1.0
 */
final class Plugin {

	/**
	 * Handle used for both the stylesheet and the script.
	 *
	 * @since 0.1.0
	 */
	private const string HANDLE = 'kntnt-modal-mega-menu-ollie';

	/**
	 * The sole instance of this class.
	 *
	 * @since 0.1.0
	 *
	 * @var self|null
	 */
	private static ?self $instance = null;

	/**
	 * Absolute path to the main plugin file.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	private static string $plugin_file = '';

	/**
	 * Cached return value of get_file_data().
	 *
	 * Populated lazily on the first call to get_plugin_data().
	 *
	 * @since 0.1.0
	 *
	 * @var array{Name: string, PluginURI: string, Version: string, RequiresWP: string, RequiresPHP: string}|null
	 */
	private static ?array $plugin_data = null;

	/**
	 * Returns (and on the first call, creates) the singleton instance.
	 *
	 * The first call must pass the absolute path to the main plugin file so
	 * the asset helpers can resolve URLs without globals. Subsequent calls
	 * ignore the argument and return the existing instance.
	 *
	 * @since 0.1.0
	 *
	 * @param string $plugin_file Absolute path to the main plugin file. Ignored
	 *                            on calls after the first.
	 * @return self
	 */
	public static function get_instance( string $plugin_file ): self {

		// Return early when already bootstrapped.
		if ( self::$instance !== null ) {
			return self::$instance;
		}

		// Capture the plugin file path and initialise the singleton.
		self::$plugin_file = $plugin_file;
		self::$instance = new self();

		return self::$instance;

	}

	/**
	 * Returns the absolute path to the main plugin file.
	 *
	 * @since 0.1.0
	 *
	 * @return string Absolute path to the main plugin file.
	 */
	public static function get_plugin_file(): string {
		return self::$plugin_file;
	}

	/**
	 * Returns the parsed plugin header, cached after the first call.
	 *
	 * Uses get_file_data() rather than get_plugin_data(): the latter lives in
	 * an admin-only include that is absent on the front end, and it would
	 * translate the header – triggering a just-in-time textdomain load
	 * before `init`.
	 *
	 * @since 0.1.0
	 *
	 * @return array{Name: string, PluginURI: string, Version: string, RequiresWP: string, RequiresPHP: string}
	 *         Header fields, each '' when absent.
	 */
	public static function get_plugin_data(): array {

		// Return the cached result to avoid repeated file reads.
		if ( self::$plugin_data !== null ) {
			return self::$plugin_data;
		}

		$headers = get_file_data(
			self::$plugin_file,
			[
				'Name' => 'Plugin Name',
				'PluginURI' => 'Plugin URI',
				'Version' => 'Version',
				'RequiresWP' => 'Requires at least',
				'RequiresPHP' => 'Requires PHP',
			],
		);

		// Restate the five keys literally. get_file_data() returns exactly the
		// keys it was handed, but its declared type is a bare `string[]`, so the
		// shape this method promises is only a claim until it is rebuilt here.
		self::$plugin_data = [
			'Name' => $headers['Name'],
			'PluginURI' => $headers['PluginURI'],
			'Version' => $headers['Version'],
			'RequiresWP' => $headers['RequiresWP'],
			'RequiresPHP' => $headers['RequiresPHP'],
		];

		return self::$plugin_data;

	}

	/**
	 * Registers the plugin's WordPress hooks.
	 *
	 * @since 0.1.0
	 */
	private function __construct() {

		// Wire the GitHub-release update checker into the WordPress update
		// transient so installs can self-update from the project's releases.
		// The plugin is distributed from GitHub rather than wordpress.org, so
		// this filter is the only way an install ever learns a new version
		// exists.
		$updater = new Updater();
		add_filter( 'pre_set_site_transient_update_plugins', [ $updater, 'check_for_updates' ] );

		// Load the modal stylesheet and script. No theme gate: the Dropdown Menu
		// block ships with Ollie Menu Designer, which the `Requires Plugins`
		// header makes a hard dependency, and it is not bound to any one theme.
		// The assets match nothing until a mega menu is on the page, so loading
		// them everywhere costs only a stylesheet and a script that stay inert
		// otherwise – and there is no per-page signal for a template-part menu to
		// gate on server-side anyway.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

	}

	/**
	 * Enqueues the frontend stylesheet and script.
	 *
	 * The stylesheet needs no dependency: its scroll-lock rule outranks Ollie
	 * Menu Designer's own `body:has()` rule on specificity, so source order does
	 * not decide it. The script goes in the footer, where the DOM it observes
	 * already exists; it acts only when a menu opens, so first-paint timing does
	 * not matter.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function enqueue_assets(): void {

		wp_enqueue_style(
			self::HANDLE,
			plugins_url( 'css/modal.css', self::$plugin_file ),
			[],
			self::asset_version( 'css/modal.css' ),
		);

		wp_enqueue_script(
			self::HANDLE,
			plugins_url( 'js/modal.js', self::$plugin_file ),
			[],
			self::asset_version( 'js/modal.js' ),
			[ 'in_footer' => true ],
		);

	}

	/**
	 * Returns a cache-busting version for an asset.
	 *
	 * Uses the file's modification time, so edits take effect without a version
	 * bump; falls back to the plugin version if the file cannot be stat'ed.
	 *
	 * @since 0.1.0
	 *
	 * @param string $relative_path Asset path, relative to the plugin root.
	 * @return string
	 */
	private static function asset_version( string $relative_path ): string {

		// A readable file dates itself; anything else falls back to the
		// header's version, which at least changes on release.
		$path = plugin_dir_path( self::$plugin_file ) . $relative_path;
		$mtime = is_readable( $path ) ? filemtime( $path ) : false;

		return $mtime !== false ? (string) $mtime : self::get_version();

	}

	/**
	 * Returns the plugin version from the plugin header.
	 *
	 * Read from the header rather than duplicated in a constant, so the version
	 * has exactly one authoritative source.
	 *
	 * @since 0.1.0
	 *
	 * @return string The version string, or '' when the header is unreadable.
	 */
	private static function get_version(): string {
		return self::get_plugin_data()['Version'];
	}

}
