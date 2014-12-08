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
if ( ! class_exists( 'WR_Item_Pricing_Table_Attr' ) ) {

	class WR_Item_Pricing_Table_Attr extends WR_Pb_Shortcode_Child {

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
				'edit_using_ajax'           => '1'
			);

			// Inline edit for sub item
			$this->config['edit_inline'] = true;
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
						'std'             => 'attr_' . WR_Pb_Utils_Common::random_string(),
						'input-type'      => 'hidden',
						'container_class' => 'hidden',
					),
					array(
						'name'    => __( 'Title', WR_PBL ),
						'id'      => 'prtbl_item_attr_title',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'role'    => 'title',
						'std'     => '',
						'tooltip' => __( 'Title', WR_PBL ),
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
						'name'    => __( 'Type', WR_PBL ),
						'id'      => 'prtbl_item_attr_type',
						'type'    => 'select',
						'class'   => 'input-sm',
						'std'     => '',
						'options' => WR_Pb_Helper_Type::get_sub_item_pricing_type(),
						'tooltip' => __( 'Type', WR_PBL )
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
			$arr_params = ( shortcode_atts( $this->config['params'], $atts ) );
			extract( $arr_params );

			$html_element = '<li>
				<label data-original-title="' . $prtbl_item_attr_desc . '" class="wr-prtbl-tipsy">' . $prtbl_item_attr_title . '</label>
			</li>';

			return $html_element;
		}

	}

}