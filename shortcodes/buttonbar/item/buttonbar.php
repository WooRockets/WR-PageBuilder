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
if ( ! class_exists( 'WR_Item_Buttonbar' ) ) {

	class WR_Item_Buttonbar extends WR_Pb_Shortcode_Child {

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
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __('Button Bar Item', WR_PBL),
				'admin_assets' => array(
					'wr-linktype.js',
				),
			);
			$this->config['use_wrapper'] = true;

			// Inline edit for sub item
			$this->config['edit_inline'] = true;
		}

		/**
		 * Define shortcode settings.
		 *
		 * @return  void
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'    => __( 'Text', WR_PBL ),
						'id'      => 'button_text',
						'type'    => 'text_field',
						'std'     => __( WR_Pb_Utils_Placeholder::add_placeholder( 'ButtonBar Item %s', 'index' ), WR_PBL ),
						'role'    => 'title',
					),
					array(
						'name'       => __( 'On Click', WR_PBL ),
						'id'         => 'link_type',
						'type'       => 'select',
						'class'      => 'input-sm',
						'std'        => 'url',
						'options'    => WR_Pb_Helper_Type::get_link_types(),
						'has_depend' => '1',
                        'tooltip' => __( 'Select link types: link to post, page, category...', WR_PBL ),
					),
					array(
						'name'       => __( 'URL', WR_PBL ),
						'id'         => 'button_type_url',
						'type'       => 'text_field',
						'class'      => 'input-sm',
						'std'        => 'http://',
						'dependency' => array( 'link_type', '=', 'url' ),
					),
					array(
						'name'  => __( 'Single Item', WR_PBL ),
						'id'    => 'single_item',
						'type'  => 'type_group',
						'std'   => '',
						'items' => WR_Pb_Helper_Type::get_single_item_button_bar(
							'link_type',
							array(
								'type'         => 'items_list',
								'options_type' => 'select',
								'class'        => 'select2-select',
								'ul_wrap'      => false,
							)
						),
					),
					array(
						'name'       => __( 'Open in', WR_PBL ),
						'id'         => 'open_in',
						'type'       => 'select',
						'class'      => 'input-sm',
						'std'        => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_open_in_options() ),
						'options'    => WR_Pb_Helper_Type::get_open_in_options(),
						'dependency' => array( 'link_type', '!=', 'no_link' ),
                        'tooltip' => __( 'Select type of opening action when click on element', WR_PBL ),
					),
					array(
						'name'      => __( 'Icon', WR_PBL ),
						'id'        => 'icon',
						'type'      => 'icons',
						'std'       => '',
						'role'      => 'title_prepend',
						'title_prepend_type' => 'icon',
					),
					array(
						'name'    => __( 'Size', WR_PBL ),
						'id'      => 'button_size',
						'type'    => 'select',
						'class'   => 'input-sm',
						'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_button_size() ),
						'options' => WR_Pb_Helper_Type::get_button_size(),
						'has_depend' => '1',
					),
					array(
						'name' => __('Custom dimensions', WR_PBL),
						'id' => 'custom_dimensions',
						'type' => array(
							array(
								'id' => 'button_width',
								'type' => 'text_append',
								'type_input' => 'number',
								'class' => 'jsn-input-number input-mini',
								'parent_class' => 'input-group-inline',
								'std' => '',
								'append_before' => '<i class="input-mini wr-label-prefix">' . __( 'Width', WR_PBL ) . '</i>',
								'append' => 'px',
								'validate' => 'number',
							),
							array(
								'id' => 'button_height',
								'type' => 'text_append',
								'type_input' => 'number',
								'class' => 'jsn-input-number input-mini',
								'parent_class' => 'input-group-inline',
								'std' => '',
								'append_before' => '<i class="input-mini wr-label-prefix">' . __( 'Height', WR_PBL ) . '</i>',
								'append' => 'px',
								'validate' => 'number',
							),
						),
						'container_class' => 'combo-group',
						'dependency' => array( 'button_size', '=', 'custom' ),
					),
					array(
						'name'    => __( 'Color', WR_PBL ),
						'id'      => 'button_color',
						'type'    => 'select',
						'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_button_color() ),
						'options' => WR_Pb_Helper_Type::get_button_color(),
						'container_class'   => 'color_select2',
                        'tooltip' => __( 'Select the color of the button', WR_PBL ),
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
			extract( $arr_params );
			$button_text  = ( ! $button_text ) ? '' : $button_text;
			$button_size  = ( ! $button_size || strtolower( $button_size ) == 'default' ) ? '' : $button_size;
			$button_color = ( ! $button_color || strtolower( $button_color ) == 'default' ) ? '' : $button_color;
			$button_icon  = ( ! $icon ) ? '' : "<i class='{$icon}'></i>";
			$tag          = 'a';
			$href         = '';
			$single_item  = explode( '__#__', $single_item );
			$single_item  = $single_item[0];
			if ( ! empty( $link_type ) ) {
				$taxonomies = WR_Pb_Helper_Type::get_public_taxonomies();
				$post_types = WR_Pb_Helper_Type::get_post_types();
				// single post
				if ( array_key_exists( $link_type, $post_types ) ) {
					$permalink = home_url() . "/?p=$single_item";
					$href      = ( ! $single_item ) ? ' href="#"' : " href='{$permalink}'";
				}
				// taxonomy
				else if ( array_key_exists( $link_type, $taxonomies ) ) {
					$permalink = get_term_link( intval( $single_item ), $link_type );
					if ( ! is_wp_error( $permalink ) )
					$href = ( ! $single_item ) ? ' href="#"' : " href='{$permalink}'";
				}
				else {
					switch ( $link_type ) {
						case 'no_link':
							$tag = 'button';
							break;
						case 'url':
							$href = ( ! $button_type_url ) ? ' href="#"' : " href='{$button_type_url}'";
							break;
					}
				}
			}
			$target = '';
			if ( $open_in ) {
				switch ( $open_in ) {
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
						$cls_button_fancy = 'wr-button-fancy';
						$script = WR_Pb_Helper_Functions::fancybox( ".$cls_button_fancy", array( 'type' => 'iframe', 'width' => '75%', 'height' => '75%' ) );
						break;
				}
			}
			$button_type      = ( $tag == 'button' ) ? " type='button'" : '';
			$cls_button_fancy = ( ! isset( $cls_button_fancy ) ) ? '' : $cls_button_fancy;
			$script           = ( ! isset( $script ) ) ? '' : $script;

			// custom style
			$cs_stype = array();
			if ( ! empty( $button_width ) ) {
				$cs_stype[] = "width: {$button_width}px;";
			}
			if ( ! empty( $button_height ) ) {
				$cs_stype[] = "height: {$button_height}px;";
				$padding_top = intval( $button_height )/2 - 12;
				$cs_stype[] = "padding-top: {$padding_top}px;";
			}

			$cs_stype = sprintf( 'style="%s [style]"', implode( '', $cs_stype ) );

			// Process CSS Class and CSS ID wrapper
			$extra_class  = ! empty ( $arr_params['css_suffix'] ) ? esc_attr( $arr_params['css_suffix'] ) : '';
			$extra_class  = ! empty ( $extra_class ) ? ' ' . ltrim( $extra_class, ' ' ) : '';
			$extra_id     = ! empty ( $arr_params['id_wrapper'] ) ? esc_attr( $arr_params['id_wrapper'] ) : '';
			$extra_id     = ! empty ( $extra_id ) ? "id='" . ltrim( $extra_id, ' ' ) . "'" : '';

			$html_result      = "<{$tag} {$extra_id} {$cs_stype} class='btn {$extra_class} {$button_size} {$button_color} {$cls_button_fancy}'{$href}{$target}{$button_type}>[icon]{$button_icon}[/icon][title]{$button_text}[/title]</{$tag}>";

			return $html_result . $script . '<!--seperate-->';
		}

	}

}