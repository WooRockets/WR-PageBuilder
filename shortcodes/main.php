<?php

/**
 * @version    $Id$
 * @package    WR PageBuilder
 * @author     WooRockets Team <support@www.woorockets.com>
 * @copyright  Copyright (C) 2012 www.woorockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.woorockets.com
 * Technical Support:  Feedback - http://www.www.woorockets.com
 */

/**
 * @todo : Define information of Buil-in Shortcodes of WR PageBuilder
 */

add_action( 'wr_pb_addon', 'wr_pb_builtin_sc_init' );

function wr_pb_builtin_sc_init() {

	/**
	 * Main class to init Shortcodes
	 * for WR PageBuilder
	 *
	 * @package  WR PageBuilder Shortcodes
	 * @since    1.0.0
	 */
	class WR_Pb_Builtin_Shortcode extends WR_Pb_Addon {

		public function __construct() {

			// Addon information
			$this->set_provider(
			array(
					'name'             => __( 'Standard Elements', WR_PBL ),
					'file'             => __FILE__,
					'shortcode_dir'    => dirname( __FILE__ ),
					'js_shortcode_dir' => 'assets/js/shortcodes',
			)
			);

			//$this->custom_assets();
			// call parent construct
			parent::__construct();

			add_filter( 'plugin_action_links', array( &$this, 'plugin_action_links' ), 10, 2 );
		}

		/**
		 * Regiter & enqueue custom assets
		 */
		public function custom_assets() {
			// register custom assets
			$this->set_assets_register(
			array(
					'wr-frontend-free-css' => array(
						'src' => plugins_url( 'assets/css/main.css', dirname( __FILE__ ) ),
						'ver' => '1.0.0',
			),
					'wr-frontend-free-js'  => array(
						'src' => plugins_url( 'assets/js/main.js', dirname( __FILE__ ) ),
						'ver' => '1.0.0',
			)
			)
			);
			// enqueue assets for WP Admin pages
			$this->set_assets_enqueue_admin( array( 'wr-frontend-free-css' ) );
			// enqueue assets for WR Modal setting iframe
			$this->set_assets_enqueue_modal( array( 'wr-frontend-free-js' ) );
			// enqueue assets for WP Frontend
			$this->set_assets_enqueue_frontend( array( 'wr-frontend-free-css', 'wr-frontend-free-js' ) );
		}

		/**
		 * Remove deactivate link
		 *
		 * @staticvar type $this_plugin
		 *
		 * @param type $links
		 * @param type $file
		 *
		 * @return type
		 */
		public function plugin_action_links( $links, $file ) {
			static $this_plugin;

			if ( ! $this_plugin ) {
				$this_plugin = plugin_basename( __FILE__ );
			}
			if ( $file == $this_plugin ) {
				unset ( $links['deactivate'] );
			}

			return $links;
		}

	}

	$this_ = new WR_Pb_Builtin_Shortcode();
}