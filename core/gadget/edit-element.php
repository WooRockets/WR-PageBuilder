<?php
/**
 * @version    $Id$
 * @package    WR_PageBuilder
 * @author     InnoThemes Team <support@innothemes.com>
 * @copyright  Copyright (C) 2012 InnoThemes.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innothemes.com
 */

/**
 * Gadget class for loading editor for WR PageBuilder element.
 *
 * @package  WR_PageBuilder
 * @since    2.0.2
 */
class WR_Gadget_Edit_Element extends WR_Pb_Gadget_Base {
	/**
	 * Gadget file name without extension.
	 *
	 * @var  string
	 */
	protected $gadget = 'edit-element';

	/**
	 * Load form for editing WR Page Builder element.
	 *
	 * @return  void
	 */
	public function form_action() {
		global $Wr_Pb;

		if ( ! $Wr_Pb ) {
			$Wr_Pb = new WR_Pb_Core();
		}

		// Use output buffering to capture HTML code for element editor
		ob_start();

		if ( isset( $_GET['wr_shortcode_preview'] ) && 1 == $_GET['wr_shortcode_preview'] ) {
			$Wr_Pb->shortcode_iframe_preview();
		} else {
			$Wr_Pb->modal_page_content();
		}

		// Set response for injecting into template file
		$this->set_response( 'success', ob_get_clean() );

		// Register action to remove unnecessary assets
		global $Wr_Pb_Preview_Class;
		if ( $Wr_Pb_Preview_Class != 'WR_Widget' ) {
			add_action( 'pb_admin_print_styles',  array( &$this, 'filter_assets' ), 0 );
			add_action( 'pb_admin_print_scripts', array( &$this, 'filter_assets' ), 0 );
		}
	}

	/**
	 * Load HTML code for inserting element into  WR Page Builder area.
	 *
	 * @return  void
	 */
	public function insert_action() {
		global $Wr_Pb;

		// Use output buffering to hold all un-wanted output
		ob_start();

		// Get raw shortcode
		$raw_shortcode = isset( $_POST['raw_shortcode'] ) ? $_POST['raw_shortcode'] : null;

		if ( empty( $raw_shortcode ) ) {
			exit;
		}

		// Process raw shortcode then echo HTML code for insertion
		exit( WR_Pb_Helper_Shortcode::do_shortcode_admin( $raw_shortcode ) );
	}

	/**
	 * Filter required assets.
	 *
	 * @return  void
	 */
	public function filter_assets() {
		static $executed;
		global $wp_styles, $wp_scripts;

		// Check if requesting form only
		$form_only = ( isset( $_GET['form_only'] ) && absint( $_GET['form_only'] ) ) ? TRUE : FALSE ;
		
		if ( ! isset( $executed ) ) {

			// Remove unnecessary assets
			foreach ( array( &$wp_styles, &$wp_scripts ) as $assets ) {
				if ( @count( $assets->queue ) ) {
					foreach ( $assets->queue as $k => $v ) {
						// Keep only required assets
						if ( $form_only ) {
							unset( $assets->queue[ $k ] );
						} elseif ( 'wr-' != substr( $v, 0, 3 ) ) {
							unset( $assets->queue[ $k ] );
						}
					}
				}
			}

			// Get response data
			$response = $this->get_response();

			// Allow required assets to be filterable
			$on_demand_assets = array();

			if ( ! $form_only ) {
				$on_demand_assets['jsn-tabs']    = 'jquery-ui-tabs';
				$on_demand_assets['ui-sortable'] = 'jquery-ui-sortable';
			}

			$on_demand_assets = apply_filters( 'wr-edit-element-required-assets', $on_demand_assets );

			// Detect and load required assets on demand
			foreach ( $on_demand_assets as $sign => $handle ) {
				if ( is_numeric( $sign ) ) {
					$this->load_asset( $handle );
				} elseif ( preg_match( '/\s(id|class)\s*=\s*[\'"][^\'"]*' . $sign . '[^\'"]*[\'"]/', $response['data'] ) ) {
					$this->load_asset( $handle );
				}
			}

			// State that this method is already executed
			$executed = true;
		} else {
			// Never load jQuery core when serving form only
			if ( $form_only ) {
				foreach ( $wp_scripts->queue as $k => $v ) {
					if ( 'jquery' == substr( $v, 0, 6 ) ) {
						unset( $wp_scripts->queue[ $k ] );
					}
				}
			}
		}

		// Prepare to re-trigger necessary action
		$action = substr( current_filter(), 3 );

		do_action( $action );
	}

	/**
	 * Method to load specified asset.
	 *
	 * @param   string  $handle  Asset handle.
	 *
	 * @return  void
	 */
	protected function load_asset( $handle ) {
		if ( is_array( $handle ) ) {
			foreach ( $handle as $h ) {
				$this->load_asset( $h );
			}

			return;
		}

		// Prepare asset handle
		if ( preg_match( '/\.(css|js)$/', $handle ) ) {
			$handle = WR_Pb_Init_Assets::file_to_handle( $handle );
		}

		// Load asset
		WR_Pb_Init_Assets::load( $handle );
	}
}
