<?php

// Define absolute path of plugin
define( 'WR_PB_PATH', plugin_dir_path( __FILE__ ) );

// Define absolute path of shortcodes folder
define( 'WR_PB_LAYOUT_PATH', WR_PB_PATH . 'core/shortcode/layout' );
define( 'WR_PB_ELEMENT_PATH', WR_PB_PATH . 'shortcodes' );

// Define premade layout folder
define( 'WR_PB_PREMADE_LAYOUT', WR_PB_PATH . 'templates/layout/pre-made' );
define( 'WR_PB_PREMADE_LAYOUT_URI', WR_PB_PATH . 'templates/layout/pre-made' );

// Define absolute path of templates folder
define( 'WR_PB_TPL_PATH', WR_PB_PATH . 'templates' );

// Define plugin uri
define( 'WR_PB_URI', plugin_dir_url( __FILE__ ) );

// Define plugin domain
define( 'WR_PBL', 'wrpagebuilder' );

// Define nonce ID
define( 'WR_NONCE', 'wr_nonce_check' );

// Define URL to load element editor
define( 'WR_EDIT_ELEMENT_URL', admin_url( 'admin.php?wr-gadget=edit-element&action=form' ) );

// Define product identification
define( 'WR_PAGEBUILDER_IDENTIFICATION', 'wr_pagebuilder' );

// Define product addons
define( 'WR_PAGEBUIDLER_ADDONS', null );

// Define folder in /wp-content/uploads stores user's template
define( 'WR_PAGEBUILDER_USER_LAYOUT', 'user' );

/**
 * Fix error warning of Woocommerce, when try to call Woocommerce in WP Admin
 */
if ( ! function_exists( 'woocommerce_reset_loop' ) ) {

	/**
	 * Reset the loop's index and columns when we're done outputting a product loop.
	 *
	 * @access public
	 * @subpackage	Loop
	 * @return void
	 */
	function woocommerce_reset_loop() {
		global $woocommerce_loop;
		// Reset loop/columns globals when starting a new loop
		$woocommerce_loop['loop'] = $woocommerce_loop['columns'] = '';
	}
}
