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
if ( ! class_exists( 'WR_Item_Pricing_Table' ) ) {

	class WR_Item_Pricing_Table extends WR_Pb_Shortcode_Parent {

		public function __construct() {
			parent::__construct();

			// Increase index of pricing option
			WR_Pricing_Table::$index ++;
		}

		public function element_config() {
			$this->config['shortcode']        = strtolower( __CLASS__ );
			$this->config['has_subshortcode'] = 'WR_' . str_replace( 'WR_', '', __CLASS__ ) . '_Attr_Value';
			$this->config['exception']        = array(
				'admin_assets'     => array(
					'wr-linktype.js',
					'wr-popover.js',
					'item_pricing_table.js',
				),
				'item_text'        => __( 'Option', WR_PBL ),
				'data-modal-title' => __( 'Option', WR_PBL ),
			);

			add_filter( 'wr_pb_sub_items_filter', array( &$this, '_sub_items_filter' ), 10, 3 );

			// Inline edit for sub item
			$this->config['edit_inline'] = true;
		}

		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'    => __( 'Title', WR_PBL ),
						'id'      => 'prtbl_item_title',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'role'    => 'title',
						'std'     => '',
						'tooltip' => __( 'Title', WR_PBL )
					),
					array(
						'name'    => __( 'Description', WR_PBL ),
						'id'      => 'prtbl_item_desc',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => __( '', WR_PBL ),
						'tooltip' => __( 'Description Tooltip', WR_PBL )
					),
					array(
						'name'    => __( 'Image', WR_PBL ),
						'id'      => 'prtbl_item_image',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => __( 'Image File', WR_PBL )
					),
					array(
						'name'    => __( 'Currency', WR_PBL ),
						'id'      => 'prtbl_item_currency',
						'type'    => 'text_field',
						'std'     => __( '', WR_PBL ),
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => __( 'Currency', WR_PBL ),
					),
					array(
						'name'    => __( 'Price', WR_PBL ),
						'id'      => 'prtbl_item_price',
						'type'    => 'text_field',
						'std'     => __( '', WR_PBL ),
						'class'   => 'jsn-input-large-fluid wr_pb_price',
						'tooltip' => __( 'Price', WR_PBL )
					),
					array(
						'name'    => __( 'Time Limits', WR_PBL ),
						'id'      => 'prtbl_item_time',
						'type'    => 'text_field',
						'std'     => __( '', WR_PBL ),
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => __( 'Time Limits', WR_PBL ),
					),
					array(
						'name'    => __( 'Button Text', WR_PBL ),
						'id'      => 'prtbl_item_button_text',
						'type'    => 'text_field',
						'class'   => 'jsn-input-large-fluid wr-pb-limit-length',
						'std'     => __( 'Buy now', WR_PBL ),
						'tooltip' => __( 'Button Text', WR_PBL )
					),
					array(
						'name'       => __( 'Button Link', WR_PBL ),
						'id'         => 'link_type',
						'type'       => 'select',
						'std'        => __( 'url', WR_PBL ),
						'options'    => WR_Pb_Helper_Type::get_link_types(),
						'tooltip'    => __( 'Button Link', WR_PBL ),
						'has_depend' => '1',
					),
					array(
						'name'       => __( 'URL', WR_PBL ),
						'id'         => 'button_type_url',
						'type'       => 'text_field',
						'class'      => 'jsn-input-xxlarge-fluid',
						'std'        => 'http://',
						'tooltip'    => __( 'URL', WR_PBL ),
						'dependency' => array( 'link_type', '=', 'url' )
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
								'ul_wrap'      => false,
							)
						),
					),
					array(
						'name'       => __( 'Open in', WR_PBL ),
						'id'         => 'open_in',
						'type'       => 'select',
						'std'        => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_open_in_options() ),
						'options'    => WR_Pb_Helper_Type::get_open_in_options(),
						'dependency' => array( 'link_type', '!=', 'no_link' )
					),
					array(
						'id'              => 'prtbl_item_attributes',
						'type'            => 'text_field',
						'std'             => '',
						'input-type'      => 'hidden',
						'container_class' => 'hidden',
					),
					array(
						'name'          => __( 'Attributes', WR_PBL ),
						'id'            => 'prtbl_attr',
						'type'          => 'group_table',
						'class'         => 'has_childsubmodal unsortable',
						'shortcode'     => ucfirst( __CLASS__ ),
						'sub_item_type' => $this->config['has_subshortcode'],
						'sub_items'     => array(
							WR_Pricing_Table::get_option( 'max_domains', true ),
							WR_Pricing_Table::get_option( 'storage', true ),
							WR_Pricing_Table::get_option( 'ssl_support', true ),
						),
						'extract_title' => 'prtbl_item_attr_title',
					),
					array(
						'name'    => __( 'Featured', WR_PBL ),
						'id'      => 'prtbl_item_feature',
						'type'    => 'radio',
						'std'     => 'no',
						'options' => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
						'tooltip' => 'Featured',
					),
				)
			);
		}

		public function element_shortcode_full( $atts = null, $content = null ) {
			$arr_params = ( shortcode_atts( $this->config['params'], $atts ) );
			extract( $arr_params );

			$href = '';
			if ( ! empty( $link_type ) ) {
				$taxonomies = WR_Pb_Helper_Type::get_public_taxonomies();
				$post_types = WR_Pb_Helper_Type::get_post_types();
				// single post
				if ( array_key_exists( $link_type, $post_types ) ) {
					$permalink = home_url() . "/?p=$single_item";
					$href      = ( ! $single_item ) ? ' href="#"' : " href='{$permalink}'";
				} // taxonomy
				else if ( array_key_exists( $link_type, $taxonomies ) ) {
					$permalink = get_term_link( intval( $single_item ), $link_type );
					if ( ! is_wp_error( $permalink ) ) {
						$href = ( ! $single_item ) ? ' href="#"' : " href='{$permalink}'";
					}
				} else {
					switch ( $link_type ) {
						case 'url':
							$href = ( ! $button_type_url ) ? ' href="#"' : " href='{$button_type_url}'";
							break;
					}
				}
			}
			$target = $script = '';
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
						$cls_button_fancy = ' wr-prtbl-button-fancy';
						break;
				}
			}
			$button_type      = " type='button'";
			$cls_button_fancy = ( ! isset( $cls_button_fancy ) ) ? '' : $cls_button_fancy;
			$script           = ( ! isset( $script ) ) ? '' : $script;

			// Process col title
			$attr_title     .= '<div class="wr-prtbl-title">';
			// Process for image
			$attr_title     .= '[prtbl_item_image]<div class="wr-prtbl-image">';
			if ( $prtbl_item_image ) {
				$attr_title .= '<img src="' . $prtbl_item_image . '" />';
			}
			$attr_title     .= '</div>[/prtbl_item_image]';
			// Process for title
			$attr_title     .= '[prtbl_item_title]<h3>' . $prtbl_item_title . '</h3>[/prtbl_item_title]';
			$attr_title     .= '</div>';

			$attr_title     .= '[prtbl_item_meta]<div class="wr-prtbl-meta">';
			$attr_title     .= '[prtbl_item_price]<div class="wr-prtbl-price">';
			if ( $prtbl_item_currency ) {
				$attr_title .= '<sup class="wr-prtbl-currency">' . $prtbl_item_currency . '</sup>';
			}
			$attr_title     .= $prtbl_item_price;
			if ( $prtbl_item_time ) {
				$attr_title .= '<sub class="wr-prtbl-time">' . $prtbl_item_time . '</sub>';
			}
			$attr_title     .= '</div>[/prtbl_item_price]';
			// Process for description
			$attr_title     .= '[prtbl_item_desc]<p class="wr-prtbl-desc">' . $prtbl_item_desc . '</p>[/prtbl_item_desc]';
			$attr_title     .= '</div>[/prtbl_item_meta]';

			$featured = ( $prtbl_item_feature == 'yes' ) ? ' wr-prtbl-cols-featured' : '';
			$pr_tbl_col_html = "<div class='wr-prtbl-cols{$featured}'>";
			$pr_tbl_col_html .= '<div class="wr-prtbl-header">' . $attr_title . '</div>';
			if ( ! empty( $content ) ) {
				$pr_tbl_col_html .= '<ul class="wr-prtbl-features">';
				$pr_tbl_col_html .= do_shortcode( $content );
				$pr_tbl_col_html .= '</ul>';
			}
			$pr_tbl_col_html .= "<div class='wr-prtbl-footer'>[prtbl_item_button]<a class='btn btn-default {$cls_button_fancy}'{$href}{$target}{$button_type}>{$prtbl_item_button_text}</a>[/prtbl_item_button]</div>";
			$pr_tbl_col_html .= '</div>';

			return ( $disabled_el == 'no' ) ? $pr_tbl_col_html . $script : '';
		}

		/**
		 * Over write parent method, set this element as Child element
		 *
		 * @param string $content
		 * @param string $shortcode_data
		 * @param string $el_title
		 *
		 * @return string
		 */

		public function element_in_pgbldr( $content = '', $shortcode_data = '', $el_title = '', $index = '', $inlude_sc_structure = true, $extra_params = array() ) {
			$this->config['sub_element'] = true;

			return parent::element_in_pgbldr( $content, $shortcode_data, $el_title, $index, $inlude_sc_structure, $extra_params );
		}

		/**
		 * Filter sub shortcodes content
		 *
		 * @param array        $sub_sc_data        The array of (sub shortcodes content) attributes of a pricing option
		 * @param string       $shortcode          The shortcode name
		 * @param string|array $attributes_content The Attributes list of Pricing Table
		 */
		public static function _sub_items_filter( $sub_sc_data, $shortcode, $attributes_content ) {
			if ( $shortcode != 'wr_item_pricing_table' ) {
				return $sub_sc_data;
			}

			// Get array of "pricing Attributes"
			if ( is_string( $attributes_content ) ) {
				$attributes_content = stripslashes( $attributes_content );
				$attributes         = explode( '--[wr_pb_seperate_sc]--', $attributes_content );
			} else if ( is_array( $attributes_content ) ) {
				$attributes = $attributes_content;
			}

			// Key parameter to check relationship between "Attribute in Pricing Item" and "pricing Attribute"
			$key_parameter = 'prtbl_item_attr_id';

			// List of parameter id to sync between "Attribute in Pricing Item" and "pricing Attribute"
			$param_to_sync = array( $key_parameter, 'prtbl_item_attr_title', 'prtbl_item_attr_type' );

			// Store updated shortcode content
			$result = array();

			// Start updating shortcode content
			foreach ( $sub_sc_data as $sc_class => $sub_sc_data_ ) {

				// Update array of Attributes in this Pricing Item, add value of $key_parameter as key
				$sub_sc_data_new = array();
				foreach ( $sub_sc_data_ as $value ) {
					$params               = shortcode_parse_atts( $value );
					$id                   = $params[$key_parameter];
					$sub_sc_data_new[$id] = $value;
				}

				// Save all exist/new attributes
				$updated_sc_attrs = array();

				foreach ( $attributes as $attribute ) {
					$attr_params = shortcode_parse_atts( $attribute );

					$id          = $attr_params[$key_parameter];

					// Get "Attribute in Pricing Item" relates to this "pricing Attribute"
					$is_new_attr = 0;

					if ( isset ( $sub_sc_data_new[$id] ) ) {
						$related_attr = $sub_sc_data_new[$id];
					} else {
						// if attribute is not existed, get the first attribute in $sub_sc_data_
						$related_attr = reset( $sub_sc_data_ );
						$is_new_attr  = 1;
					}

					// Extract parameters
					$params_of_attr = shortcode_parse_atts( $related_attr );

					// Get real attributes ( remove first & last element in array: [0] => "[shortcode_tag" ; [1] => "][/shortcode_tag]" )
					$params_of_attr_real = $params_of_attr;
					if ( is_array( $params_of_attr_real ) ) {
						unset ( $params_of_attr_real[0] );
						unset ( $params_of_attr_real[1] );
					}

					// Update parameter's value from "pricing Attribute" to "Attribute in Pricing Item"
					foreach ( $param_to_sync as $parameter ) {
						$params_of_attr_real[$parameter] = isset ( $attr_params[$parameter] ) ? $attr_params[$parameter] : '';
					}

					// Reset parameters of new Attribute which is not in array $param_to_sync
					if ( $is_new_attr ) {
						foreach ( $params_of_attr_real as $parameter_name => $value ) {
							if ( ! in_array( $parameter_name, $param_to_sync ) ) {
								$params_of_attr_real[$parameter_name] = '';
							}
						}
					}

					// Join parametes to creating shortcode content
					$sc_content = $params_of_attr[0];

					foreach ( $params_of_attr_real as $parameter_name => $value ) {
						$sc_content .= " $parameter_name=\"$value\"";
					}

					$sc_content .= $params_of_attr[1];

					$updated_sc_attrs[] = $sc_content;
				}

				$result[$sc_class] = $updated_sc_attrs;
			}

			return $result;
		}

	}

}