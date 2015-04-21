<?php
/**
 * Plugin Name: WR PageBuilder
 * Plugin URI:  http://www.woorockets.com
 * Description: Awesome content builder for Wordpress websites
 * Version:     2.4.12
 * Author:      WooRockets Team <support@www.woorockets.com>
 * Author URI:  http://www.wordpress.org/plugins/wr-pagebuilder
 * License:     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

// Set custom error reporting level
error_reporting( E_ALL ^ E_NOTICE );

// Define path to this plugin file
define( 'WR_PB_FILE', __FILE__ );

// Load WordPress plugin functions
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( ! class_exists( 'WR_Pb_Init' ) ) :

/**
 * Initialize WR PageBuilder.
 *
 * @package  WR PageBuilder
 * @since    1.0.0
 */
class WR_Pb_Init {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	public function __construct() {
		// Load core functionalities
		$this->includes();
		$this->autoload();

		// Initialize assets management and loader
		WR_Pb_Assets_Register::init();
		WR_Pb_Init_Assets::hook();
		// Initialize WR Library
		WR_Pb_Init_Plugin::hook();

		// Register necessary actions
		add_action( 'widgets_init', array(                 &$this, 'init'          ), 100 );
		add_action( 'admin_init'  , array(       'WR_Pb_Gadget_Base', 'hook'          ), 100 );
		add_action( 'admin_init'  , array( 'WR_Pb_Product_Plugin', 'settings_form' )      );
		
		// Check update
		if ( ( get_option( 'wr_pb_settings_auto_check_update', 'enable' ) == 'enable' ) && ( time() > get_option( 'wr_pagebuilder_update_schedule', 0 ) ) ) {
			update_option( 'wr_pagebuilder_update_schedule', time() + ( 2 * 7 * 24 * 60 * 60 ) );
			add_action( 'init', array( 'WR_Pb_Helper_Update_Checker', 'check_by_curl' ) );
			add_action( 'wp_enqueue_scripts', array( 'WR_Pb_Helper_Update_Checker', 'check_by_ajax' ) );
			add_action( 'admin_enqueue_scripts', array( 'WR_Pb_Helper_Update_Checker', 'check_by_ajax' ) );
		}

		// Activate plugin
		register_activation_hook( WR_PB_FILE, array( $this, 'do_activate' ) );
		// Redirect after plugin activation
		add_action( 'admin_init' , array( $this, 'do_activation_redirect' ) );

		// Initialize built-in shortcodes
		include dirname( __FILE__ ) . '/shortcodes/main.php';
	}

	/**
	 * Initialize core functionalities.
	 *
	 * @return  void
	 */
	function init(){
		global $Wr_Pb, $Wr_Pb_Widgets;

		// Initialize WR PageBuilder
		$Wr_Pb = new WR_Pb_Core();
		new WR_Pb_Utils_Plugin();

		do_action( 'wr_pagebuilder_init' );

		// Initialize productivity functions
		WR_Pb_Product_Plugin::init();

		// Initialize widget support
		$Wr_Pb_Widgets = ! empty( $Wr_Pb_Widgets ) ? $Wr_Pb_Widgets : WR_Pb_Helper_Functions::widgets();
	}

	/**
	 * Include required files.
	 *
	 * @return  void
	 */
	function includes() {
		// include core files
		include_once 'core/loader.php';
		include_once 'defines.php';
	}

	/**
	 * Register autoloader.
	 *
	 * @return  void
	 */
	function autoload() {
		WR_Pb_Loader::register( WR_PB_PATH . 'core'       , 'WR_Pb_'     );
		WR_Pb_Loader::register( WR_PB_PATH . 'core/gadget', 'WR_Gadget_' );

		// Allow autoload registration from outside
		do_action( 'wr_pb_autoload' );
	}

	/**
	 * Activate handle.
	 *
	 * @return  void
	 */
	public function do_activate() {
		update_option( 'wr_pagebuilder_do_activation_redirect', 'Yes' );
	}

	/**
	 * Activation redirect handle.
	 *
	 * @return  void
	 */
	public function do_activation_redirect() {
		if ( get_option( 'wr_pagebuilder_do_activation_redirect', 'No' ) == 'Yes' ) {
			update_option( 'wr_pagebuilder_do_activation_redirect', 'No' );
			wp_redirect( admin_url( 'admin.php?page=wr-pb-about-us' ) );
		}
	}
}

// Instantiate WR PageBuilder initialization class
$GLOBALS['wr_pagebuilder'] = new WR_Pb_Init();

endif;
