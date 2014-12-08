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

if ( ! class_exists( 'WR_Heading' ) ) :

/**
 * Heading element for WR PageBuilder.
 *
 * @since  1.0.0
 */
class WR_Heading extends WR_Pb_Shortcode_Element {
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
		$this->config['name']        = __( 'Heading', WR_PBL );
		$this->config['cat']         = __( 'Typography', WR_PBL );
		$this->config['icon']        = 'wr-icon-heading';
		$this->config['description'] = __( 'Heading tags for text', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'admin_assets' => array(
				// Shortcode initialization
				'heading.js',
			),

			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',
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
					'name'    => __( 'Tag', WR_PBL ),
					'id'      => 'tag',
					'type'    => 'select',
					'class'   => 'input-sm wr-heading-type',
					'std'     => 'h1',
					'options' => array( 'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6' ),
					'tooltip' => __( 'Support tags: H1, H2, H3, H4, H5, H6', WR_PBL )
				),
				array(
					'name'    => __( 'Text', WR_PBL ),
					'id'      => 'text',
					'type'    => 'text_field',
					'role'    => 'content',
					'class'   => 'input-sm',
					'std'     => __( 'Your heading text', WR_PBL ),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => __( 'Alignment', WR_PBL ),
					'id'      => 'text_align',
					'type'    => 'radio_button_group',
					'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_text_align() ),
					'options' => WR_Pb_Helper_Type::get_text_align(),
					'class'   => 'input-sm',
				),
				array(
					'name'            => __( 'Margin', WR_PBL ),
					'container_class' => 'combo-group',
					'id'              => 'heading_margin',
					'type'            => 'margin',
					'extended_ids'    => array( 'heading_margin_top', 'heading_margin_right', 'heading_margin_bottom', 'heading_margin_left' ),
					'heading_margin_top'    => array( 'std' => '5' ),
					'heading_margin_bottom' => array( 'std' => '25' ),
					'tooltip'               => __( 'External spacing with other elements', WR_PBL )
				),
				array(
					'name'       => __( 'Font', WR_PBL ),
					'id'         => 'font',
					'type'       => 'radio',
					'std'        => 'inherit',
					'options'    => array( 'inherit' => __( 'Inherit', WR_PBL ), 'custom' => __( 'Custom', WR_PBL ) ),
					'has_depend' => '1',
					//'class'      => 'input-sm',
				),
				array(
					'name' => __( 'Font Face', WR_PBL ),
					'id'   => 'font_family',
					'type' => array(
						array(
							'id'           => 'font_face_type',
							'type'         => 'jsn_select_font_type',
							'class'        => 'input-sm',
							'std'          => 'standard fonts',
							'options'      => WR_Pb_Helper_Type::get_fonts(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'font_face_value',
							'type'         => 'jsn_select_font_value',
							'class'        => 'input-sm',
							'std'          => 'Verdana',
							'options'      => '',
							'parent_class' => 'combo-item',
						),
					),
					'dependency'      => array( 'font', '=', 'custom' ),
					'container_class' => 'combo-group',
				),
				array(
					'name' => __( 'Font Attributes', WR_PBL ),
					'type' => array(
						array(
							'id'           => 'font_size_value_',
							'type'         => 'text_append',
							'type_input'   => 'number',
							'class'        => 'input-mini',
							'std'          => '18',
							'append'       => 'px',
							'validate'     => 'number',
							'parent_class' => 'combo-item input-append-inline',
						),
						array(
							'id'           => 'font_style',
							'type'         => 'select',
							'class'        => 'input-sm wr-mini-input',
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
					'container_class' => 'combo-group',
				),
				array(
					'name'       => __( 'Enable Underline', WR_PBL ),
					'id'         => 'enable_underline',
					'type'       => 'radio',
					'std'        => 'yes',
					'options'    => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
					'has_depend' => '1'
				),
				array(
					'name' => __( 'Underline Style', WR_PBL ),
					'type' => array(
						array(
							'id'           => 'border_bottom_width_value_',
							'type'         => 'text_append',
							'type_input'   => 'number',
							'class'        => 'input-mini',
							'std'          => '',
							'append'       => 'px',
							'validate'     => 'number',
							'parent_class' => 'combo-item input-append-inline',
						),
						array(
							'id'           => 'border_bottom_style',
							'type'         => 'select',
							'class'        => 'input-sm wr-border-type',
							'std'          => 'solid',
							'options'      => WR_Pb_Helper_Type::get_border_styles(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'border_bottom_color',
							'type'         => 'color_picker',
							'std'          => '',
							'parent_class' => 'combo-item',
						),
					),
					'container_class' => 'combo-group',
					'dependency'      => array( 'enable_underline', '=', 'yes' )
				),
				array(
					'name' => __( 'Underline Padding', WR_PBL ),
					'type' => array(
						array(
							'id'         => 'padding_bottom_value_',
							'type'       => 'text_append',
							'type_input' => 'number',
							'class'      => 'input-mini',
							'std'        => '',
							'append'     => 'px',
							'validate'   => 'number',
						),
					),
					'dependency' => array( 'enable_underline', '=', 'yes' )
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
		$script = '';

		if ( ! empty( $atts ) AND is_array( $atts ) ) {
			if ( ! isset( $atts['border_bottom_width_value_'] ) ) {
				$atts['border_bottom_width_value_'] = '';
				$atts['border_bottom_style']        = '';
				$atts['border_bottom_color']        = '';
			}

			if ( ! isset( $atts['padding_bottom_value_'] ) ) {
				$atts['padding_bottom_value_'] = '';
			}

			if ( ! isset( $attrs['font_size_value_'] ) ) {
				$attrs['font_size_value_'] = '';
			}
		}

		// Reload shortcode params: because we get Heading Text from "text" param
		WR_Pb_Helper_Shortcode::generate_shortcode_params( $this->items, NULL, $atts );

		$arr_params     = ( shortcode_atts( $this->config['params'], $atts ) );
		extract($arr_params);
		$style          = array();
		$exclude_params = array( 'tag', 'text', 'preview' );
		$stylesheet     = $font_style = '';

		// Override custom style
		if ( ! empty( $arr_params ) AND is_array( $arr_params ) ) {
			if ( $arr_params['font'] == 'inherit' || $arr_params['font'] == 'Inherit' ) {
				unset( $arr_params['font'] );
				unset( $arr_params['font_face_type'] );
				unset( $arr_params['font_face_value'] );
				unset( $arr_params['font_size_value_'] );
				unset( $arr_params['font_style'] );
				unset( $arr_params['color'] );
			}

			if ( isset( $arr_params['font'] ) && $arr_params['font'] == 'custom' ) {
				unset( $arr_params['font'] );
				if ( isset( $arr_params['font_style'] ) && strtolower( $arr_params['font_style'] ) == 'bold' ) {
					$arr_params['font_weight'] = '700';
					unset( $arr_params['font_style'] );
				}
				if ( isset( $arr_params['font_style'] ) && strtolower( $arr_params['font_style'] ) == 'normal' ) {
					$arr_params['font_weight'] = 'normal';
					unset( $arr_params['font_style'] );
				}
			}

			if ( isset( $arr_params['font_size_value_'] ) && $arr_params['font_size_value_'] == '' ) {
				unset( $arr_params['font_size_value_'] );
			}

			if ( $arr_params['border_bottom_width_value_'] == '' ) {
				unset( $arr_params['border_bottom_width_value_'] );
				unset( $arr_params['border_bottom_style'] );
				unset( $arr_params['border_bottom_color'] );
			}

			if ( $arr_params['padding_bottom_value_'] == '' ) {
				unset( $arr_params['padding_bottom_value_'] );
			}

			if ( $arr_params['text_align'] == 'inherit' || $arr_params['text_align'] == 'Inherit' ) {
				unset( $arr_params['text_align'] );
			}
		}

		foreach ( $arr_params as $key => $value ) {
			if ( $value != '' ) {
				if ( $key == 'font_face_type' ) {
					if ( $value == __( 'Standard fonts', WR_PBL ) || $value == 'standard fonts' ) {
						$font_style = 'font-family:' . $arr_params['font_face_value'];
					} elseif ( $value == __( 'Google fonts', WR_PBL ) || $value == 'google fonts' ) {
						$script     = WR_Pb_Helper_Functions::add_google_font_link_tag( $arr_params['font_face_value'] );
						$font_style = 'font-family:' . $arr_params['font_face_value'];
					}
				} elseif ( $key != 'font_face_value' ) {
					$key = WR_Pb_Helper_Functions::remove_tag( $key );
					if ( ! in_array( $key, $exclude_params ) ) {
						switch ( $key ) {
							case 'border_bottom_width_value_':
								$style[$key] = 'border-bottom-width:' . $value . 'px';
								break;

							case 'text_align':
								$style[$key] = 'text-align:' . $value;
								break;

							case 'font_size_value_':
								$style[$key] = 'font-size:' . $value . 'px';
								break;

							case 'font_style':
								$style[$key] = 'font-style:' . $value;
								break;

							case 'border_bottom_style':
								$style[$key] = 'border-bottom-style:' . $value;
								break;

							case 'border_bottom_color':
								$style[$key] = 'border-bottom-color:' . $value;
								break;

							case 'padding_bottom_value_':
								$style[$key] = 'padding-bottom:' . $value . 'px';
								break;

							case 'font_weight':
								$style[$key] = 'font-weight:' . $value;
								break;

							case 'color':
								$style[$key] = 'color:' . $value;
								break;
						}
					}
				}
			}
		}

		// Finalize style
		$style = implode( ';', $style ) . ';' . $font_style;

		if ( $style == ';' ) {
			$style = '';
		}
		
		extract( $arr_params );
		
		if ( $enable_underline == 'yes' ) {
			
		}

		// Process heading margins
		if ( isset( $arr_params['heading_margin_top'] ) )
			$arr_params['div_margin_top']    = $arr_params['heading_margin_top'];
		if ( isset( $arr_params['heading_margin_bottom'] ) )
			$arr_params['div_margin_bottom'] = $arr_params['heading_margin_bottom'];
		if ( isset( $arr_params['heading_margin_right'] ) )
			$arr_params['div_margin_right']  = $arr_params['heading_margin_right'];
		if ( isset( $arr_params['heading_margin_left'] ) )
			$arr_params['div_margin_left']   = $arr_params['heading_margin_left'];

		// Finalize HTML code
		$true_element = "<{$arr_params['tag']} style='{$style}'>" . do_shortcode( $content ) . "</{$arr_params['tag']}>";

		return $this->element_wrapper( $script . $stylesheet . $true_element, $arr_params );
	}
}

endif;
