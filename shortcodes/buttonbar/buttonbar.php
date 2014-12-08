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

if ( ! class_exists( 'WR_Buttonbar' ) ) :

/**
 * Create a bar of buttons
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_Buttonbar extends WR_Pb_Shortcode_Parent {
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
		$this->config['name']             = __( 'Button Bar', WR_PBL );
		$this->config['cat']              = __( 'Typography', WR_PBL );
		$this->config['icon']             = 'wr-icon-button-bar';
		$this->config['has_subshortcode'] = 'WR_Item_' . str_replace( 'WR_', '', __CLASS__ );
		$this->config['description']      = __( 'Bar of buttons', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'default_content'  => __( 'Button Bar', WR_PBL ),
			'data-modal-title' => __( 'Button Bar', WR_PBL ),

			'admin_assets' => array(
				'wr-pb-joomlashine-iconselector-js',
				'wr-linktype.js',
			),
			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',

				// Font IcoMoon
				'wr-pb-font-icomoon-css',

				// Fancy Box
				'wr-pb-jquery-fancybox-css',
				'wr-pb-jquery-fancybox-js',

				// Shortcode style
				'buttonbar_frontend.css',
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
					'id' => 'buttonbar_items',
					'type' => 'group',
					'shortcode' => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items' => array(
						array( 'std' => '' ),
						array( 'std' => '' ),
						array( 'std' => '' ),
					)
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => __( 'Alignment', WR_PBL ),
					'id'      => 'buttonbar_alignment',
					'type'    => 'radio_button_group',
					'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_text_align() ),
					'options' => WR_Pb_Helper_Type::get_text_align(),
				),
				array(
					'name'            => __( 'Margin', WR_PBL ),
					'container_class' => 'combo-group',
					'id'              => 'buttonbar_margin',
					'type'            => 'margin',
					'extended_ids'    => array( 'buttonbar_margin_top', 'buttonbar_margin_right', 'buttonbar_margin_bottom', 'buttonbar_margin_left' ),
					'buttonbar_margin_top'    => array( 'std' => '0' ),
					'buttonbar_margin_right'  => array( 'std' => '0' ),
					'buttonbar_margin_bottom' => array( 'std' => '0' ),
					'buttonbar_margin_left'   => array( 'std' => '0' ),
					'tooltip'             => __( 'External spacing with other elements', WR_PBL ),
				),
				array(
					'name' => __( 'Distance between items', WR_PBL ),
					'type' => array(
						array(
							'id'         => 'distance_between',
							'type'       => 'text_append',
							'type_input' => 'number',
							'class'      => 'input-mini',
							'std'        => '0',
							'append'     => 'px',
							'validate'   => 'number',
						),
					),
				),
				array(
					'name'       => __( '', WR_PBL ),
					'container_class' => 'group-checkbox',
					'id'         => 'buttonbar_show_title',
					'type'       => 'checkbox',
					'std'        => 'yes',
					'options'    => array( 'yes' => __( 'Show Title', WR_PBL ) ),
				),
				array(
					'name'       => __( '', WR_PBL ),
					'container_class' => 'group-checkbox',
					'id'         => 'buttonbar_show_icon',
					'type'       => 'checkbox',
					'std'        => 'yes',
					'options'    => array( 'yes' => __( 'Show Icon', WR_PBL ) ),
				),
				array(
					'name'       => __( '', WR_PBL ),
					'container_class' => 'group-checkbox',
					'id'         => 'buttonbar_group',
					'type'       => 'checkbox',
					'std'        => '',
					'options'    => array( 'yes' => __( 'Show Group Buttons', WR_PBL ) ),
				),
				WR_Pb_Helper_Type::get_apprearing_animations(),
				WR_Pb_Helper_Type::get_animation_speeds(),
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
		$arr_params    = shortcode_atts( $this->config['params'], $atts );
		$html_element  = '';
		$sub_shortcode = WR_Pb_Helper_Shortcode::remove_autop( $content, false );
		$items = explode( '<!--seperate-->', $sub_shortcode );
		// remove empty element
		$items         = array_filter( $items );
		$initial_open  = ( ! isset($initial_open ) || $initial_open > count( $items ) ) ? 1 : $initial_open;
		foreach ( $items as $idx => $item ) {
			$open        = ( $idx + 1 == $initial_open ) ? 'in' : '';
			$items[$idx] = $item;
		}
		$sub_shortcode = implode( '', $items );
		$sub_htmls     = $sub_shortcode;

		$arr_params['buttonbar_show_title'] = explode( '__#__', $arr_params['buttonbar_show_title'] );
		if ( in_array( 'yes', $arr_params['buttonbar_show_title'] ) ) {
			$sub_htmls = str_replace( '[title]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/title]', '', $sub_htmls );
		} else {
			$pattern   = '\\[(\\[?)(title)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( '/' . $pattern . '/s', '', $sub_htmls );
		}

		$arr_params['buttonbar_show_icon'] = explode( '__#__', $arr_params['buttonbar_show_icon'] );
		if ( in_array( 'yes', $arr_params['buttonbar_show_icon'] ) ) {
			$sub_htmls = str_replace( '[icon]', '', $sub_htmls );
			$sub_htmls = str_replace( '[/icon]', '', $sub_htmls );
		} else {
			$pattern   = '\\[(\\[?)(icon)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$sub_htmls = preg_replace( '/' . $pattern . '/s', '', $sub_htmls );
		}

		// button margin between
		$distance_between = ( isset( $arr_params['distance_between'] ) ) ? intval( $arr_params['distance_between'] ) : '';
		$sub_htmls = str_replace( '[style]', $distance_between ? "margin-right:{$distance_between}px" : '', $sub_htmls );

		if ( $arr_params['buttonbar_group'] == 'yes__#__ ' ) {
			$html_element = "<div class='btn-group'>" . $sub_htmls . '</div>';
		} else {
			$html_element = $sub_htmls;
		}

		$cls_alignment = '';
		if ( strtolower( $arr_params['buttonbar_alignment'] ) != 'inherit' ) {
			if ( strtolower( $arr_params['buttonbar_alignment'] ) == 'left' )
				$cls_alignment = 'pull-left';
			if ( strtolower( $arr_params['buttonbar_alignment'] ) == 'right' )
				$cls_alignment = 'pull-right';
			if ( strtolower( $arr_params['buttonbar_alignment'] ) == 'center' )
				$cls_alignment = 'text-center';
		}
		$html_element = "<div class='btn-toolbar {$cls_alignment}'>{$html_element}</div>";

		// Set button bar margin
		if ( isset( $arr_params['buttonbar_margin_top'] ) )
			$arr_params['div_margin_top'] = $arr_params['buttonbar_margin_top'];
		if ( isset( $arr_params['buttonbar_margin_left'] ) )
			$arr_params['div_margin_left'] = $arr_params['buttonbar_margin_left'];
		if ( isset( $arr_params['buttonbar_margin_right'] ) )
			$arr_params['div_margin_right'] = $arr_params['buttonbar_margin_right'];
		if ( isset( $arr_params['buttonbar_margin_bottom'] ) )
			$arr_params['div_margin_bottom'] = $arr_params['buttonbar_margin_bottom'];

		return $this->element_wrapper( $html_element, $arr_params );
	}
}

endif;
