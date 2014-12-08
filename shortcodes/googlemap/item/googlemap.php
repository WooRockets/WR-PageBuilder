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
if ( ! class_exists( 'WR_Item_GoogleMap' ) ) {

	class WR_Item_GoogleMap extends WR_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'item_text'        => __( 'Marker', WR_PBL ),
				'data-modal-title' => __( 'Google Maps Item', WR_PBL ),

				'admin_assets' => array(
					// Shortcode initialization
					'item_googlemap.css',
				),
			);

			// Inline edit for sub item
			$this->config['edit_inline'] = true;
		}

		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'    => __( 'Title', WR_PBL ),
						'id'      => 'gmi_title',
						'type'    => 'text_field',
						'role'    => 'title',
						'std'     => __( WR_Pb_Utils_Placeholder::add_placeholder( 'Marker %s', 'index' ), WR_PBL ),
						'tooltip' => __( 'Title', WR_PBL )
					),
					array(
						'name'    => __( 'Description', WR_PBL ),
						'id'      => 'gmi_desc_content',
						'role'    => 'content',
						'type'    => 'text_area', // Edit inline supplement
						'std'     => __( 'Description of marker', WR_PBL ),
						'tooltip' => __( 'Description', WR_PBL ),
						'container_class' => 'wr_tinymce_replace',
					),
					array(
						'name'    => __( 'Link URL', WR_PBL ),
						'id'      => 'gmi_url',
						'type'    => 'text_field',
						'std'     => 'http://',
						'tooltip' => __( 'Link URL', WR_PBL )
					),
					array(
						'name'        => __( 'Image', WR_PBL ),
						'id'          => 'gmi_image',
						'type'        => 'select_media',
						'std'         => '',
						'class'       => 'jsn-input-large-fluid',
						'tooltip'     => __( 'Image', WR_PBL ),
						'filter_type' => 'image',
					),
					array(
						'name' => __( 'Location', WR_PBL ),
						'id'   => 'gmi_location',
						'type' => array(
							array(
								'id'            => 'gmi_long',
								'type'          => 'text_append',
								'input_type'    => 'number',
								'class'         => 'jsn-input-number input-small',
								'std'           => rand(0, 10),
								'parent_class'  => 'input-group-inline',
								'append_before' => __( 'Long', WR_PBL )
							),
							array(
								'id'            => 'gmi_lat',
								'type'          => 'text_append',
								'input_type'    => 'number',
								'class'         => 'jsn-input-number input-small',
								'std'           => rand(0, 10),
								'parent_class'  => 'input-group-inline',
								'append_before' => __( 'Lat', WR_PBL )
							),
						),
						'tooltip'         => __( 'Location', WR_PBL ),
						'container_class' => 'combo-group',
					),
					array(
						'name'       => __( 'Enable Direction', WR_PBL ),
						'id'         => 'gmi_enable_direct',
						'type'       => 'radio',
						'std'        => 'no',
						'options'    => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
						'tooltip'    => 'Enable Direction',
						'has_depend' => '1',
					),
					array(
						'name'        => __( 'Destination', WR_PBL ),
						'id'         => 'gmi_destination',
						'type'       => 'destination',
						'tooltip'    => __( 'Destination', WR_PBL ),
						'dependency' => array( 'gmi_enable_direct', '=', 'yes' )
					),
				)
			);
		}

		public function element_shortcode_full( $atts = null, $content = null ) {
			$params = shortcode_atts( $this->config['params'], $atts );
			// reassign value for description from content of shortcode
			$params['gmi_desc_content'] = $content;

			$html_element = "<input type='hidden' value='" . json_encode( $params ) . "' class='wr-gmi-lat-long' />";
			$html_element .= '<!--seperate-->';
			return $html_element;
		}

	}

}