<?php
/**
 * Plugin Name: WP Portfolio Showcase
 * Plugin URI:  https://princeboss.com
 * Description: Create Your Portfolio Showcase.
 * Version:     0.0.1
 * Author:      Prince Ahmed
 * Author URI:  http://princeboss.com
 * Text Domain: wp-portfolio-showcase
 * Domain Path: /languages/
 */

// don't call the file directly
defined( 'ABSPATH' ) || exit();


/**
 * Main initiation class
 *
 * @since 0.0.1
 */
final class WP_Portfolio_Showcase {
	/**
	 * WP_Portfolio_Showcase version.
	 *
	 * @var string
	 */
	public $version = '0.0.1';

	/**
	 * Minimum PHP version required
	 *
	 * @var string
	 */
	private $min_php = '5.6.0';

	/**
	 * The single instance of the class.
	 *
	 * @var WP_Portfolio_Showcase
	 * @since 0.0.1
	 */
	protected static $instance = null;

	public function __construct() {
		$this->check_environment();
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
		do_action( 'wp_portfolio_showcase_loaded' );
	}

	/**
	 * Ensure theme and server variable compatibility
	 */
	function check_environment() {
		if ( version_compare( PHP_VERSION, $this->min_php, '<=' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );

			wp_die( "Unsupported PHP version Min required PHP Version:{$this->min_php}" );
		}
	}

	/**
	 * Define Projects Constants.
	 *
	 * @return void
	 * @since 0.0.1
	 *
	 */
	private function define_constants() {
		define( 'WP_PORTFOLIO_SHOWCASE_VERSION', $this->version );
		define( 'WP_PORTFOLIO_SHOWCASE_FILE', __FILE__ );
		define( 'WP_PORTFOLIO_SHOWCASE_PATH', dirname( WP_PORTFOLIO_SHOWCASE_FILE ) );
		define( 'WP_PORTFOLIO_SHOWCASE_INCLUDES', WP_PORTFOLIO_SHOWCASE_PATH . '/includes' );
		define( 'WP_PORTFOLIO_SHOWCASE_URL', plugins_url( '', WP_PORTFOLIO_SHOWCASE_FILE ) );
		define( 'WP_PORTFOLIO_SHOWCASE_ASSETS_URL', WP_PORTFOLIO_SHOWCASE_URL . '/assets' );
		define( 'WP_PORTFOLIO_SHOWCASE_TEMPLATES_DIR', WP_PORTFOLIO_SHOWCASE_PATH . '/templates' );
	}


	/**
	 * Include required core files used in admin and on the frontend.
	 */
	function includes() {

		//core includes
		include_once WP_PORTFOLIO_SHOWCASE_INCLUDES . '/class-install.php';
		include_once WP_PORTFOLIO_SHOWCASE_INCLUDES . '/core-functions.php';
		include_once WP_PORTFOLIO_SHOWCASE_INCLUDES . '/hook-functions.php';
		include_once WP_PORTFOLIO_SHOWCASE_INCLUDES . '/class-cpt.php';
		include_once WP_PORTFOLIO_SHOWCASE_INCLUDES . '/enqueue.php';
		include_once WP_PORTFOLIO_SHOWCASE_INCLUDES . '/template-functions.php';
		include_once WP_PORTFOLIO_SHOWCASE_INCLUDES . '/class-shortcode.php';

		//admin includes
		if ( is_admin() ) {
			include_once WP_PORTFOLIO_SHOWCASE_INCLUDES . '/prince-settings/prince-loader.php';
			include_once WP_PORTFOLIO_SHOWCASE_INCLUDES . '/admin/class-admin.php';
			include_once WP_PORTFOLIO_SHOWCASE_INCLUDES . '/admin/class-metabox.php';
			include_once WP_PORTFOLIO_SHOWCASE_INCLUDES . '/admin/admin-settings.php';
		}

	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 2.3
	 */
	private function init_hooks() {
		add_action( 'admin_notices', [ $this, 'admin_notices' ], 15 );

		// Localize plugin
		add_action( 'init', [ $this, 'localization_setup' ] );

		//action_links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_action_links' ] );

		register_activation_hook( __FILE__, [ 'WP_Portfolio_Showcase_Install', 'activate' ] );
	}

	/**
	 * Initialize plugin for localization
	 *
	 * @return void
	 * @since 0.0.1
	 *
	 */
	function localization_setup() {
		load_plugin_textdomain( 'wp-portfolio-showcase', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Plugin action links
	 *
	 * @param array $links
	 *
	 * @return array
	 */
	function plugin_action_links( $links ) {
		$links[] = '<a href="' . admin_url( 'edit.php?post_type=portfolio&page=wp-portfolio-showcase-settings' ) . '">' . esc_html__( 'Settings', 'wp-portfolio-showcase' ) . '</a>';

		return $links;
	}

	function add_notice( $class, $message ) {

		$notices = get_option( sanitize_key( 'wp_portfolio_notices' ), [] );
		if ( is_string( $message ) && is_string( $class ) && ! wp_list_filter( $notices, array( 'message' => $message ) ) ) {

			$notices[] = [
				'message' => $message,
				'class'   => $class
			];

			update_option( sanitize_key( 'wp_portfolio_notices' ), $notices );
		}

	}

	function admin_notices() {
		$notices = get_option( sanitize_key( 'wp_portfolio_notices' ), [] );
		foreach ( $notices as $notice ) { ?>
            <div class="notice is-dismissible notice-<?php echo $notice['class']; ?>">
                <p><?php echo $notice['message']; ?></p>
            </div>
			<?php
			update_option( sanitize_key( 'wp_portfolio_notices' ), [] );
		}
	}


	/**
	 * Main WP_Portfolio_Showcase Instance.
	 *
	 * Ensures only one instance of WP_Portfolio_Showcase is loaded or can be loaded.
	 *
	 * @return WP_Portfolio_Showcase - Main instance.
	 * @since 0.0.1
	 * @static
	 */
	static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

function wp_portfolio_showcase() {
	return WP_Portfolio_Showcase::instance();
}

//fire off the plugin
wp_portfolio_showcase();