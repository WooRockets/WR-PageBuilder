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
if ( ! class_exists( 'WR_Item_Pricing_Table_Attr_Value' ) ) {

	class WR_Item_Pricing_Table_Attr_Value extends WR_Pb_Shortcode_Child {

		/**
		 * element constructor
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * element config
		 * @see    WR_Element::element_config()
		 * @access public
		 * @return void
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'item_text'                 => __( 'Attribute', WR_PBL ),
				'data-modal-title'          => __( 'Attribute', WR_PBL ),
				'disable_preview_container' => '1',
				'action_btn'                => 'edit',
			);

			add_filter( 'wr_pb_content_in_pagebuilder', array( &$this, 'content_in_pagebuilder' ), 10, 3 );
			add_filter( 'wr_pb_button_in_pagebuilder', array( &$this, 'button_in_pagebuilder' ), 10, 3 );
			add_filter( 'edit_btn_class', array( &$this, 'filter_edit_btn_class' ), 10, 2 );
		}

		/**
		 * element items
		 *
		 * @see    WR_Element::element_items()
		 * @access public
		 * @return array
		 */
		public function element_items() {
			//$random_id = WR_Pb_Utils_Common::random_string( 8, true );
			$this->items = array(
				'Notab' => array(
					array(
						'id'              => 'prtbl_item_attr_id',
						'type'            => 'text_field',
						'std'             => '__default_id__',
						'input-type'      => 'hidden',
						'container_class' => 'hidden',
					),
					array(
						'name'            => __( 'Title', WR_PBL ),
						'id'              => 'prtbl_item_attr_title',
						'type'            => 'text_field',
						'class'           => 'jsn-input-xxlarge-fluid',
						'std'             => '',
						'container_class' => 'hidden',
						'tooltip'         => __( 'Title', WR_PBL )
					),
					array(
						'name'    => __( 'Value', WR_PBL ),
						'id'      => 'prtbl_item_attr_value',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'role'    => 'title',
						'std'     => '',
						'tooltip' => __( 'Value', WR_PBL )
					),
					array(
						'name'    => __( 'Description', WR_PBL ),
						'id'      => 'prtbl_item_attr_desc',
						'type'    => 'text_area',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => '',
						'tooltip' => __( 'Description', WR_PBL ),
					),
					array(
						'name'            => __( 'Type', WR_PBL ),
						'id'              => 'prtbl_item_attr_type',
						'type'            => 'select',
						'std'             => '',
						'class'           => 'input-sm',
						'options'         => WR_Pb_Helper_Type::get_sub_item_pricing_type(),
						'container_class' => 'hidden',
						'tooltip'         => __( 'Type', WR_PBL )
					),
				)
			);
		}

		/**
		 * element shortcode
		 *
		 * @see    WR_Element::element_shortcode( $atts, $content )
		 * @access public
		 * @return string
		 */
		public function element_shortcode_full( $atts = null, $content = null ) {
			$pricingtable_attrs = json_decode( get_transient( 'pricingtable_attrs' ), true );
			$arr_params = ( shortcode_atts( $this->config['params'], $atts ) );
			extract( $arr_params );

			if ( is_array( $pricingtable_attrs ) ) {
				if ( isset( $arr_params['prtbl_item_attr_id'] ) && in_array( $arr_params['prtbl_item_attr_id'], $pricingtable_attrs ) ) {
					return '';
				}
			}

			switch( $prtbl_item_attr_type ) {
				case 'text':
					$html_element = '<li><label data-original-title="' . $prtbl_item_attr_desc . '" class="wr-prtbl-tipsy">' . $prtbl_item_attr_value . '</label></li>';
					break;
				case 'checkbox':
					$html_element = ( $prtbl_item_attr_value == 'yes' ) ? '<li><i class="icon-checkmark"></i></li>' : '<li></li>';
					break;
			}

			return $html_element;
		}

		/**
		 * Filter Content when output HTML for shortcode inside PageBuilder Admin
		 *
		 * @param string $content
		 *
		 * @return string
		 */
		public function content_in_pagebuilder( $content, $shortcode_data, $shortcode ) {
			if ( $shortcode == $this->config['shortcode'] ) {
				$params = shortcode_parse_atts( $shortcode_data );
				if ( isset ( $params['prtbl_item_attr_type'] ) && $params['prtbl_item_attr_type'] == 'checkbox' ) {
					$check_value = isset( $params['prtbl_item_attr_value'] ) ? $params['prtbl_item_attr_value'] : 'no';
					$option                              = array(
						'id'      => 'prtbl_item_attr_type_' . $params['prtbl_item_attr_id'],
						'type'    => 'radio',
						'std'     => $check_value,
						'options' => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
						'parent_class'   => 'no-hover-subitem prtbl_item_attr_type'
					);
					$content = WR_Pb_Helper_Shortcode::render_parameter( 'radio', $option );
				}

				if ( $content == '(Untitled)' ) {
					$content = '';
				}
			}

			return $content;
		}

		/**
		 * Filter button in HTML output of shortcode inside PageBuilder Admin
		 *
		 * @param string $button
		 *
		 * @return string
		 */
		public function button_in_pagebuilder( $buttons, $shortcode_data, $shortcode ) {
			if ( $shortcode == $this->config['shortcode'] ) {
				$params = shortcode_parse_atts( $shortcode_data );
				if ( isset ( $params['prtbl_item_attr_type'] ) && $params['prtbl_item_attr_type'] == 'checkbox' ) {
					$buttons = '';
				}
			}

			return $buttons;

		}

		public function filter_edit_btn_class( $class, $shortcode ) {
			return ($shortcode == $this->config['shortcode']) ? 'element-edit-ct' : $class;
		}

	}

}