<?php
/**
 * @version    $Id$
 * @package    WR PageBuilder
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 woorockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support:  Feedback - http://www.woorockets.com
 */

if ( ! class_exists( 'WR_Progressbar' ) ) :

/**
 * Create Progress Bar Element
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_Progressbar extends WR_Pb_Shortcode_Parent {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Configure shortcode.
	 *
	 * @return  void
	 */
	public function element_config() {
		$this->config['shortcode']        = strtolower( __CLASS__ );
		$this->config['name']             = __( 'Progress Bar', WR_PBL );
		$this->config['cat']              = __( 'Typography', WR_PBL );
		$this->config['icon']             = 'wr-icon-progress-bar';
		$this->config['has_subshortcode'] = 'WR_Item_' . str_replace( 'WR_', '', __CLASS__ );
		$this->config['description']      = __( 'Animated progress bar', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'default_content'  => __( 'Progress Bar', WR_PBL ),
			'data-modal-title' => __( 'Progress Bar', WR_PBL ),

			'admin_assets' => array(
				// Shortcode initialization
				'progressbar.js',
				'wr-pb-joomlashine-iconselector-js',
			),

			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',

				// Font IcoMoon
				'wr-pb-font-icomoon-css',

				// Lazy Load
				'wr-pb-jquery-lazyload-js',
				
				// Shortcode style
				'progressbar_frontend.css',
				'progressbar_frontend.js'
			),
		);

		// Use Ajax to speed up element settings modal loading speed
		$this->config['edit_using_ajax'] = true;
	}

	/**
	 * Define shortcode settings.
	 *
	 * @return  void
	 */
	public function element_items() {
		$this->items = array(
			'content' => array(
		
				array(
					'id'            => 'progress_bar_items',
					'type'          => 'group',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => __( '', WR_PBL ) ),
						array( 'std' => __( '', WR_PBL ) ),
						array( 'std' => __( '', WR_PBL ) ),
					),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => __( 'Presentation', WR_PBL ),
					'id'      => 'progress_bar_style',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_progress_bar_style() ),
					'options' => WR_Pb_Helper_Type::get_progress_bar_style(),
				),
				array(
					'name'    => __( 'Show Icon', WR_PBL ),
					'id'      => 'progress_bar_show_icon',
					'type'    => 'radio',
					'std'     => 'yes',
					'options' => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
				),
				array(
					'name'    => __( 'Show Title', WR_PBL ),
					'id'      => 'progress_bar_show_title',
					'type'    => 'radio',
					'std'     => 'yes',
					'options' => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
				),
				array(
					'name'    => __( 'Show Percentage', WR_PBL ),
					'id'      => 'progress_bar_show_percent',
					'type'    => 'radio',
					'std'     => 'yes',
					'options' => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
				),
				array(
					'name'       => __( '', WR_PBL ),
					'container_class' => 'group-checkbox',
					'id'         => 'progress_bar_stack_active',
					'type'       => 'checkbox',
					'std'        => 'yes',
					'options'    => array( 'yes' => __( 'Animated Progress Bar', WR_PBL ) ),
				),				
				
			)
		);
	}

	/**
	 * Generate HTML code from shortcode content.
	 *
	 * @param   array   $atts     Shortcode attributes.
	 * @param   string  $content  Current content.
	 *
	 * @return  string
	 */
	public function element_shortcode_full( $atts = null, $content = null ) {
		$arr_params   = shortcode_atts( $this->config['params'], $atts );
		$html_element = '';
		if ( isset( $arr_params['progress_bar_stack_active'] ) ) {
			$arr_params['progress_bar_stack_active'] = trim( str_replace( '__#__', '', $arr_params['progress_bar_stack_active'] ) );
		}
		
		if ( $arr_params['progress_bar_stack_active'] == 'yes' ) {
			$content = str_replace( 'pbar_item_style="solid"', 'pbar_item_style="striped"', $content );
		}

		$sub_shortcode = do_shortcode( $content );
		$items         = explode( '<!--seperate-->', $sub_shortcode );
		// remove empty element
		$items         = array_filter( $items );
		$initial_open  = ( ! isset( $initial_open ) || $initial_open > count( $items ) ) ? 1 : $initial_open;
		foreach ( $items as $idx => $item ) {
			$open        = ( $idx + 1 == $initial_open ) ? 'in' : '';
			$items[$idx] = $item;
		}
		$sub_shortcode = implode( '', $items );

		$sub_htmls     = $sub_shortcode;
		if ( $arr_params['progress_bar_show_icon'] == 'no' ) {
			$pattern   = '\\[(\\[?)(icon)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
		} else {
			$sub_htmls = str_replace( '[icon]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/icon]', '', $sub_htmls );
		}
		if ( $arr_params['progress_bar_show_title'] == 'no' ) {
			$pattern   = '\\[(\\[?)(text)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
		} else {
			$sub_htmls = str_replace( '[text]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/text]', '', $sub_htmls );
		}
		if ( $arr_params['progress_bar_show_percent'] == 'no' ) {
			$pattern   = '\\[(\\[?)(percentage)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
		} else {
			$sub_htmls = str_replace( '[percentage]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/percentage]', '', $sub_htmls );
		}
		if ( $arr_params['progress_bar_show_percent'] == 'no' AND $arr_params['progress_bar_show_title'] == 'no' AND $arr_params['progress_bar_show_icon'] == 'no' ) {
			$pattern   = '\\[(\\[?)(sub_content)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
		}
		if ( $arr_params['progress_bar_style'] == 'stacked' ) {
			$sub_htmls   = str_replace( '{active}', '', $sub_htmls );
			$active      = ( $arr_params['progress_bar_stack_active'] == 'yes' ) ? ' progress-striped active' : '';
			$stacked 	 = ' stacked';
			$html_titles = '';
			$pattern     = '\\[(\\[?)(sub_content)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			preg_match_all( "/$pattern/s", $sub_htmls, $matches );
			$sub_htmls   = preg_replace( "/$pattern/s", '', $sub_htmls );
			foreach ( $matches as $i => $items ) {
				if ( is_array( $items ) ) {
					foreach ( $items as $j => $item ) {
						if ( $item != '' AND strpos( $item, '[sub_content]' ) !== false ) {
							$item        = str_replace( '[sub_content]', '', $item );
							$item        = str_replace( '[/sub_content]', '', $item );
							$html_titles .= $item;
						}
					}
				}
			}
			$html_element = $html_titles;
			$html_element .= "<div class='progress{$active}{$stacked}'>";
			$html_element .= $sub_htmls;
			$html_element .= '</div>';
		} else {
			$sub_htmls = str_replace( '[sub_content]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/sub_content]', '', $sub_htmls );
			if ( $arr_params['progress_bar_stack_active'] == 'yes' ) {
				$sub_htmls = str_replace( '{active}', ' active', $sub_htmls );
			} else {
				$sub_htmls = str_replace( '{active}', '', $sub_htmls );
			}
			$html_element = $sub_htmls;
		}

		return $this->element_wrapper( $html_element, $arr_params );
	}
}

endif;
