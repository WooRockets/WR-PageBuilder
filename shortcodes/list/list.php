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

if ( ! class_exists( 'WR_List' ) ) :

/**
 * Create List of items element
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_List extends WR_Pb_Shortcode_Parent {
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
		$this->config['name']             = __( 'List', WR_PBL );
		$this->config['cat']              = __( 'Typography', WR_PBL );
		$this->config['icon']             = 'wr-icon-list';
		$this->config['has_subshortcode'] = 'WR_Item_' . str_replace( 'WR_', '', __CLASS__ );
		$this->config['description']      = __( 'List of free content with icons', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'admin_assets' => array(
				'wr-pb-joomlashine-iconselector-js',
				'list.js',
			),
			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',
				// Font IcoMoon
				'wr-pb-font-icomoon-css',
				// Shortcode style
				'list_frontend.css',
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
			'action' => array(
				array(
					'id'      => 'btn_convert',
					'type'    => 'button_group',
					'bound'   => 0,
					'actions' => array(
						array(
							'std'         => __( 'Tab', WR_PBL ),
							'action_type' => 'convert',
							'action'      => 'list_to_tab',
						),
						array(
							'std'         => __( 'Accordion', WR_PBL ),
							'action_type' => 'convert',
							'action'      => 'list_to_accordion',
						),
						array(
							'std'         => __( 'Carousel', WR_PBL ),
							'action_type' => 'convert',
							'action'      => 'list_to_carousel',
						),
					)
				),
			),
			'content' => array(
				array(
					'id'            => 'list_items',
					'type'          => 'group',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => '' ),
						array( 'std' => '' ),
					),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'       => __( 'Show Icon', WR_PBL ),
					'id'         => 'show_icon',
					'type'       => 'radio',
					'std'        => 'yes',
					'options'    => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
					'has_depend' => '1',
				),
				array(
					'name'       => __( 'Layout', WR_PBL ),
					'id'         => 'icon_position',
					'type'       => 'select',
					'class'      => 'input-sm',
					'std'        => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_icon_position() ),
					'options'    => WR_Pb_Helper_Type::get_icon_position(),
					'dependency' => array( 'show_icon', '=', 'yes' )
				),
				array(
					'name' => __( 'Icon Background', WR_PBL ),
					'type' => array(
						array(
							'id'           => 'icon_size_value',
							'type'         => 'select',
							'class'        => 'input-mini input-sm',
							'std'          => '32',
							'options'      => WR_Pb_Helper_Type::get_icon_sizes(),
							'parent_class' => 'combo-item input-append select-append input-group input-select-append',
							'append_text'  => 'px',
						),
						array(
							'id'           => 'icon_background_type',
							'type'         => 'select',
							'class'        => 'input-sm',
							'std'          => 'circle',
							'options'      => WR_Pb_Helper_Type::get_icon_background(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'icon_background_color',
							'type'         => 'color_picker',
							'std'          => '#0088CC',
							'parent_class' => 'combo-item',
						),
					),
					'container_class' => 'combo-group',
					'dependency'      => array( 'show_icon', '=', 'yes' )
				),
				array(
					'name' => __( 'Icon Color', WR_PBL ),
					'type' => array(
						array(
							'id'           => 'icon_c_value',
							'type'         => 'text_field',
							'std'          => '#FFFFFF',
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'icon_c_color',
							'type'         => 'color_picker',
							'std'          => '#ffffff',
							'parent_class' => 'combo-item',
						),
					),
					'container_class' => 'combo-group',
					'dependency'      => array( 'show_icon', '=', 'yes' )
				),
				array(
					'name'       => __( 'Show Heading', WR_PBL ),
					'id'         => 'show_heading',
					'type'       => 'radio',
					'std'        => 'yes',
					'options'    => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
					'has_depend' => '1',
				),
				array(
					'name'       => __( 'Heading Font', WR_PBL ),
					'id'         => 'font',
					'type'       => 'select',
					'std'        => 'inherit',
					'options'    => array( 'inherit' => __( 'Inherit', WR_PBL ), 'custom' => __( 'Custom', WR_PBL ) ),
					'has_depend' => '1',
					'class'      => 'input-medium',
					'dependency' => array( 'show_heading', '=', 'yes' )
				),
				array(
					'name' => __( 'Font Face', WR_PBL ),
					'id'   => 'font-family',
					'type' => array(
						array(
							'id'           => 'font_face_type',
							'type'         => 'jsn_select_font_type',
							'class'        => 'input-medium input-sm',
							'std'          => 'standard fonts',
							'options'      => WR_Pb_Helper_Type::get_fonts(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'font_face_value',
							'type'         => 'jsn_select_font_value',
							'class'        => 'input-medium input-sm',
							'std'          => 'Verdana',
							'options'      => '',
							'parent_class' => 'combo-item',
						),
					),
					'dependency'      => array( 'font', '=', 'custom' ),
					'tooltip'         => __( 'Select Font Face', WR_PBL ),
					'container_class' => 'combo-group',
				),
				array(
					'name' => __( 'Font Attributes', WR_PBL ),
					'type' => array(
						array(
							'id'           => 'font_size_value',
							'type'         => 'text_append',
							'type_input'   => 'number',
							'class'        => 'input-mini input-sm',
							'std'          => '',
							'append'       => 'px',
							'validate'     => 'number',
							'parent_class' => 'combo-item input-append-inline',
						),
						array(
							'id'           => 'font_style',
							'type'         => 'select',
							'class'        => 'input-medium wr-mini-input input-sm',
							'std'          => 'bold',
							'options'      => WR_Pb_Helper_Type::get_font_styles(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'color',
							'type'         => 'color_picker',
							'std'          => '#000000',
							'parent_class' => 'combo-item',
						),
					),
					'dependency'      => array( 'font', '=', 'custom' ),
					'tooltip'         => __( 'Set size and text style for your heading', WR_PBL ),
					'container_class' => 'combo-group',
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
		$arr_params = ( shortcode_atts( $this->config['params'], $atts ) );
		$link = '';
		$exclude_params = array( 'tag', 'text', 'preview' );

		$arr_styles = array();
		if ( strtolower( $arr_params['font_face_type'] ) == 'google fonts' AND $arr_params['font'] != 'inherit' ) {
			wp_enqueue_style( 'wr-google-fonts', "http://fonts.googleapis.com/css?family={$arr_params['font_face_value']}" );
		}

		if ( $arr_params['font'] != 'inherit' ) {
			if ($arr_params['font_face_value'])
			$arr_styles[] = 'font-family: ' . $arr_params['font_face_value'];
			if ($arr_params['font_size_value'])
			$arr_styles[] = 'font-size: ' . $arr_params['font_size_value'] . 'px';
			if ($arr_params['color'])
			$arr_styles[] = 'color: ' . $arr_params['color'];
			if ($arr_params['font_style'] == 'bold')
			$arr_styles[] = 'font-weight: 700 !important';
			else if ($arr_params['font_style'] == 'normal')
			$arr_styles[] = 'font-weight: normal !important';
			else
			$arr_styles[] = 'font-style: ' . $arr_params['font_style'];
		}

		$arr_icon_styles = array();
		$arr_icon_class = array();
		$arr_icon_class[] = '';
		if ( $arr_params['icon_position'] ) {
			$icon_position    = strtolower( $arr_params['icon_position'] );
			$arr_icon_class[] = ( $icon_position != 'inherit' ) ? "wr-position-{$icon_position}" : '';
		}
		if (strtolower( $arr_params['icon_background_type'] ) != '' )
		$arr_icon_class[] = "wr-shape-{$arr_params['icon_background_type']}";
		if ( $arr_params['icon_size_value'] ) {
			$arr_icon_class[] = "wr-icon-{$arr_params['icon_size_value']}";
		}

		if ($arr_params['icon_background_color'])
		$arr_icon_styles[] = 'background-color: ' . $arr_params['icon_background_color'];
		if ( $arr_params['icon_c_color'] ) {
			$arr_icon_styles[] = 'color: ' . $arr_params['icon_c_color'];
		}

		$html_elements = '';
		$sub_shortcode = WR_Pb_Helper_Shortcode::remove_autop( $content );
		$items         = explode( '<!--seperate-->', $sub_shortcode );
		// remove empty element
		$items         = array_filter( $items );
		$initial_open  = ( ! isset( $initial_open ) || $initial_open > count( $items ) ) ? 1 : $initial_open;
		foreach ( $items as $idx => $item ) {
			$open        = ( $idx + 1 == $initial_open ) ? 'in' : '';
			$items[$idx] = $item;
		}
		$sub_shortcode = implode( '', $items );
		$sub_shortcode = implode( '', $items );
		if ( ! empty( $sub_shortcode ) ) {
			$parent_class  = implode( ' ', $arr_icon_class );
			$html_elements = "<ul class='wr-list-icons {$parent_class}'>";
			$sub_htmls     = do_shortcode( $sub_shortcode );
			$sub_htmls     = str_replace( 'wr-sub-icons', 'wr-icon-base', $sub_htmls );
			$sub_htmls     = str_replace( 'wr-styles', implode( ';', $arr_icon_styles ), $sub_htmls );
			$sub_htmls     = str_replace( 'wr-list-title', implode( ';', $arr_styles ), $sub_htmls );

			if ( $arr_params['show_icon'] == 'no' ) {
				$pattern   = '\\[(\\[?)(icon)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
				$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
			} else {
				$sub_htmls = str_replace( '[icon]', '', $sub_htmls );
				$sub_htmls = str_replace( '[/icon]', '', $sub_htmls );
			}

			if ( $arr_params['show_heading'] == 'no' ) {
				$pattern   = '\\[(\\[?)(heading)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
				$sub_htmls = preg_replace( "/$pattern/s", '', $sub_htmls );
			} else {
				$sub_htmls = str_replace( '[heading]', '', $sub_htmls );
				$sub_htmls = str_replace( '[/heading]', '', $sub_htmls );
			}

			$html_elements .= $sub_htmls;
			$html_elements .= '</ul>';
		}

		return $this->element_wrapper( $link . $html_elements, $arr_params );
	}
}

endif;
