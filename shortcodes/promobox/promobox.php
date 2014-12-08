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

if ( ! class_exists( 'WR_Promobox' ) ) :

/**
 * Create Promobox element
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_Promobox extends WR_Pb_Shortcode_Element {
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
		$this->config['shortcode']   = strtolower( __CLASS__ );
		$this->config['name']        = __( 'Promotion Box', WR_PBL );
		$this->config['cat']         = __( 'Typography', WR_PBL );
		$this->config['icon']        = 'wr-icon-promotion-box';
		$this->config['description'] = __( 'Styled box for promotion', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'default_content'  => __( 'Promotion Box', WR_PBL ),
			'data-modal-title' => __( 'Promotion Box', WR_PBL ),

			'admin_assets' => array(
				// Shortcode initialization
				'wr-popover.js',
				'wr-colorpicker.js',
				'wr-linktype.js',
				'wr-font-color.js'
			),

			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',

				// Fancy Box
				'wr-pb-jquery-fancybox-css',
				'wr-pb-jquery-fancybox-js',

				// Shortcode style and script initialization
				'promobox_frontend.css',
				'promobox_frontend.js',
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
					'id'      => 'pb_title',
					'name'    => __( 'Promotion Title', WR_PBL ),
					'type'    => 'text_field',
					'class'   => 'input-sm',
					'std'     => __( 'Promotion Box Title', WR_PBL ),
				),
				array(
					'id'      => 'pb_content',
					'role'    => 'content',
					'name'    => __( 'Promotion Content', WR_PBL ),
					'type'    => 'tiny_mce',
					'rows'    => '12',
					'std'     => WR_Pb_Helper_Type::lorem_text(12),
				),
				array(
					'name'    => __( 'Button Title', WR_PBL ),
					'id'      => 'pb_button_title',
					'type'    => 'text_field',
					'class'   => 'input-sm',
					'std'     => 'Button Title',
				),
				array(
					'name'       => __( 'Button Link', WR_PBL ),
					'id'         => 'link_type',
					'type'       => 'select',
					'class'      => 'input-sm',
					'std'        => 'url',
					'options'    => WR_Pb_Helper_Type::get_link_types(),
					'has_depend' => '1',
				),
				array(
					'name'       => __( 'URL', WR_PBL ),
					'id'         => 'pb_button_url',
					'type'       => 'text_field',
					'class'      => 'input-sm',
					'std'        => 'http://',
					'dependency' => array( 'link_type', '=', 'url' )
				),
				array(
					'name'  => __( 'Single Item', WR_PBL ),
					'id'    => 'single_item',
					'type'  => 'type_group',
					'std'   => '',
					'items' => WR_Pb_Helper_Type::get_single_item_button_bar(
						'link_type', array(
							'type'         => 'items_list',
							'options_type' => 'select',
							'class'        => 'select2-select',
							'ul_wrap'      => false,
						)
					),
				),
				array(
					'name'       => __( 'Open in', WR_PBL ),
					'id'         => 'pb_button_open_in',
					'type'       => 'select',
					'class'      => 'input-sm',
					'std'        => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_open_in_options() ),
					'options'    => WR_Pb_Helper_Type::get_open_in_options(),
					'dependency' => array( 'link_type', '!=', 'no_link' )
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name' => __( 'Background Color', WR_PBL ),
					'type' => array(
						array(
							'id'           => 'pb_bg_value',
							'type'         => 'text_field',
							'class'        => 'input-small',
							'std'          => '#F6F6F6',
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'pb_bg_color',
							'type'         => 'color_picker',
							'std'          => '#F6F6F6',
							'parent_class' => 'combo-item',
						),
					),
					'container_class' => 'combo-group',
				),
				array(
					'name'             => __( 'Border Width', WR_PBL ),
					'container_class'  => 'combo-group',
					'id'               => 'pb_border',
					'type'             => 'margin',
					'extended_ids'     => array( 'pb_border_top', 'pb_border_right', 'pb_border_bottom', 'pb_border_left' ),
					'pb_border_top'    => array( 'std' => '5' ),
					'pb_border_right'  => array( 'std' => '5' ),
					'pb_border_bottom' => array( 'std' => '5' ),
					'pb_border_left'   => array( 'std' => '5' ),
				),
				array(
					'name' => __( 'Border Color', WR_PBL ),
					'type' => array(
						array(
							'id'           => 'pb_border_value',
							'type'         => 'text_field',
							'class'        => 'input-small',
							'std'          => '#A0CE4E',
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'pb_border_color',
							'type'         => 'color_picker',
							'std'          => '#A0CE4E',
							'parent_class' => 'combo-item',
						),
					),
					'container_class' => 'combo-group',
				),
				array(
					'name'    => __( 'Show Shadow', WR_PBL ),
					'id'      => 'pb_show_drop',
					'type'    => 'radio',
					'std'     => 'yes',
					'options' => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
					'tooltip' => __( 'Shadow of Promotion Box', WR_PBL )
				),
				array(
					'name'    => __( 'Elements', WR_PBL ),
					'id'      => 'elements',
					'type'    => 'items_list',
					'std'     => 'title__#__content__#__button',
					'options' => array(
						'title'   => __( 'Title', WR_PBL ),
						'content' => __( 'Content', WR_PBL ),
						'button'  => __( 'Button', WR_PBL )
					),
					'options_type'    => 'checkbox',
					'popover_items'   => array( 'title', 'button' ),
					'tooltip'         => __( 'Elements to display on promotion box', WR_PBL ),
					'style'           => array( 'height' => '200px' ),
					'container_class' => 'unsortable content-elements',
				),
				// Popup settings for Title
				array(
					'name'              => __( 'Font', WR_PBL ),
					'id'                => 'title_font',
					'type'              => 'select',
					'std'               => 'inherit',
					'options'           => array( 'inherit' => __( 'Inherit', WR_PBL ), 'custom' => __( 'Custom', WR_PBL ) ),
					'has_depend'        => '1',
					'class'             => 'input-medium',
					'tooltip'           => __( 'Select Font Type', WR_PBL ),
					'container_class'   => 'hidden',
					'data_wrap_related' => 'title',
				),
				array(
					'name' => __( 'Font Face', WR_PBL ),
					'id'   => 'title_font_family',
					'type' => array(
						array(
							'id'           => 'title_font_face_type',
							'type'         => 'jsn_select_font_type',
							'class'        => 'input-medium input-sm',
							'std'          => 'standard fonts',
							'options'      => WR_Pb_Helper_Type::get_fonts(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'title_font_face_value',
							'type'         => 'jsn_select_font_value',
							'class'        => 'input-medium input-sm',
							'std'          => 'Verdana',
							'options'      => '',
							'parent_class' => 'combo-item',
						),
					),
					'dependency'        => array( 'title_font', '=', 'custom' ),
					'tooltip'           => __( 'Select Font Face', WR_PBL ),
					'container_class'   => 'combo-group hidden',
					'data_wrap_related' => 'title',
				),
				array(
					'name' => __( 'Font Attributes', WR_PBL ),
					'type' => array(
						array(
							'id'           => 'title_font_size',
							'type'         => 'text_append',
							'type_input'   => 'number',
							'class'        => 'input-mini',
							'std'          => '',
							'append'       => 'px',
							'validate'     => 'number',
							'parent_class' => 'combo-item input-append-inline',
						),
						array(
							'id'           => 'title_font_style',
							'type'         => 'select',
							'class'        => 'input-medium wr-mini-input input-sm',
							'std'          => 'bold',
							'options'      => WR_Pb_Helper_Type::get_font_styles(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'title_font_color',
							'type'         => 'color_picker',
							'std'          => '#000000',
							'parent_class' => 'combo-item',
						),
					),
					'dependency'        => array( 'title_font', '=', 'custom' ),
					'tooltip'           => __( 'Set Font Attribute', WR_PBL ),
					'container_class'   => 'combo-group hidden',
					'data_wrap_related' => 'title',
				),
				array(
					'name' => __( 'Bottom Padding', WR_PBL ),
					'type' => array(
						array(
							'id'         => 'title_padding_bottom',
							'type'       => 'text_append',
							'type_input' => 'number',
							'class'      => 'input-mini',
							'std'        => '',
							'append'     => 'px',
							'validate'   => 'number',
						),
					),
					'tooltip'           => __( 'Set Bottom Padding', WR_PBL ),
					'container_class'   => 'hidden',
					'data_wrap_related' => 'title',
				),
				array(
					'name' => __( 'Bottom Margin', WR_PBL ),
					'type' => array(
						array(
							'id'         => 'title_margin_bottom',
							'type'       => 'text_append',
							'type_input' => 'number',
							'class'      => 'input-mini',
							'std'        => '',
							'append'     => 'px',
							'validate'   => 'number',
						),
					),
					'tooltip'           => __( 'Set Bottom Margin', WR_PBL ),
					'container_class'   => 'hidden',
					'data_wrap_related' => 'title',
				),
				array(
					'name'              => __( 'Size', WR_PBL ),
					'id'                => 'pb_button_size',
					'type'              => 'select',
					'std'               => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_button_size() ),
					'options'           => WR_Pb_Helper_Type::get_button_size(),
					'tooltip'           => __( 'Set Button Size', WR_PBL ),
					'container_class'   => 'hidden',
					'data_wrap_related' => 'button',
				),
				array(
					'name'              => __( 'Button Color', WR_PBL ),
					'id'                => 'pb_button_color',
					'type'              => 'select',
					'std'               => 'btn-danger',
					'options'           => WR_Pb_Helper_Type::get_button_color(),
					'tooltip'           => __( 'Set Button Color', WR_PBL ),
					'container_class'   => 'hidden color_select2',
					'data_wrap_related' => 'button',
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
		$html_element = '';
		$arr_params   = shortcode_atts( $this->config['params'], $atts );
		extract( $arr_params );
		$styles       = array();
		if ( $pb_bg_color ) {
			$styles[] = 'background-color:' . $pb_bg_color;
		}
		if ( intval( $pb_border_top ) > 0 ) {
			$styles[] = 'border-top-width:' . ( int ) $pb_border_top . 'px';
			$styles[] = 'border-top-style: solid';
		}
		if ( intval( $pb_border_left ) > 0 ) {
			$styles[] = 'border-left-width:' . ( int ) $pb_border_left . 'px';
			$styles[] = 'border-left-style: solid';
		}
		if ( intval( $pb_border_bottom ) > 0 ) {
			$styles[] = 'border-bottom-width:' . ( int ) $pb_border_bottom . 'px';
			$styles[] = 'border-bottom-style: solid';
		}
		if ( intval( $pb_border_right ) > 0 ) {
			$styles[] = 'border-right-width:' . ( int ) $pb_border_right . 'px';
			$styles[] = 'border-right-style: solid';
		}
		if ( $pb_border_color ) {
			$styles[] = 'border-color:' . $pb_border_color;
		}

		$elements = explode( '__#__', $elements );
		$class    = '';
		if ( $pb_show_drop == 'yes' ) {
			$class .= 'promo-box-shadow';
		}
		$single_item = explode( '__#__', $single_item );
		$single_item = $single_item[0];
		$script      = $cls_button_fancy = $target = $button = '';
		if ( in_array( 'button', $elements ) ) {
			$taxonomies = WR_Pb_Helper_Type::get_public_taxonomies();
			$post_types = WR_Pb_Helper_Type::get_post_types();
			// single post
			if ( array_key_exists( $link_type, $post_types ) ) {
				$permalink   = home_url() . "/?p=$single_item";
				$button_href = "href='{$permalink}'";
			}
			// taxonomy
			else if ( array_key_exists( $link_type, $taxonomies ) ) {
				$permalink = get_term_link( intval( $single_item ), $link_type );
				if ( ! is_wp_error( $permalink ) )
				$button_href = "href='{$permalink}'";
			}
			else {
				switch ( $link_type ) {
					case 'no_link':
						$button_href = '';
						break;
					case 'url':
						$button_href = "href='{$pb_button_url}'";
						break;
				}
			}

			if ( $pb_button_open_in AND $link_type != 'no_link' ) {
				switch ( $pb_button_open_in ) {
					case 'current_browser':
						$target = '';
						break;
					case 'new_browser':
						$target = ' target="_blank"';
						break;
					case 'new_window':
						$cls_button_fancy = 'wr-button-new-window';
						$script = WR_Pb_Helper_Functions::new_window( ".$cls_button_fancy", array( 'width' => '75%', 'height' => '75%' ) );
						break;
					case 'lightbox':
						$cls_button_fancy = 'wr-pb-button-fancy';
						$script = WR_Pb_Helper_Functions::fancybox( ".$cls_button_fancy", array( 'type' => 'iframe', 'width' => '75%', 'height' => '75%' ) );
						break;
				}
			}

			$pb_button_size = ( isset( $pb_button_size ) && $pb_button_size != 'default' ) ? $pb_button_size : '';
			$pb_button_color = ( isset( $pb_button_color ) && $pb_button_color != 'default' ) ? $pb_button_color : '';
			$button = "<a class='pull-right btn {$pb_button_size} {$pb_button_color} {$cls_button_fancy}' {$target} {$button_href}>{$pb_button_title}</a>";
		}

		$styles = implode( ';', $styles );
		$styles = ( $styles ) ? "style='{$styles}'" : '';
		$html_element .= "<div class='wr-promobox'>";
		$html_element .= "<section class='{$class}' {$styles}>";
		$html_element .= $button;
		if ( in_array( 'title', $elements ) ) {
			$style_title = array();
			if ( $title_font == 'custom' ) {
				if ( $title_font_face_type == 'google fonts' ) {
					$script .= WR_Pb_Helper_Functions::add_google_font_link_tag( $title_font_face_value );
					$style_title[] = 'font-family:' . $title_font_face_value;
				} elseif ( $title_font_face_value ) {
					$style_title[] = 'font-family:' . $title_font_face_value;
				}
				if ( intval( $title_font_size ) > 0 ) {
					$style_title[] = 'font-size:' . intval( $title_font_size ) . 'px';
				}
				switch ( $title_font_style ) {
					case 'bold':
						$style_title[] = 'font-weight:700';
						break;
					case 'italic':
						$style_title[] = 'font-style:italic';
						break;
					case 'normal':
						$style_title[] = 'font-weight:normal';
						break;
				}
				if ( strpos( $title_font_color, '#' ) !== false ) {
					$style_title[] = 'color:' . $title_font_color;
				}
			}
			if ( $title_padding_bottom ) {
				$style_title[] = 'padding-bottom:' . $title_padding_bottom . 'px';
			}
			if ( $title_margin_bottom ) {
				$style_title[] = 'margin-bottom:' . $title_margin_bottom . 'px';
			}
			if ( count( $style_title ) ) {
				$style_title = 'style="' . implode( ';', $style_title ) . '"';
			} else
			$style_title = '';
			$html_element .= "<h2 {$style_title}>{$pb_title}</h2>";
		}
		$content = ( ! $content ) ? '' : $content;
		if ( in_array( 'content', $elements ) )
		$html_element .= "<p>{$content}</p>";
		$html_element .= '</section>';
		$html_element .= '</div>';

		return $this->element_wrapper( $html_element . $script, $arr_params );
	}
}

endif;
