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
if ( ! class_exists( 'WR_Item_Testimonial' ) ) {

	class WR_Item_Testimonial extends WR_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'item_text'        => __( 'Testimonial Item', WR_PBL ),
				'data-modal-title' => __( 'Testimonial Item', WR_PBL ),

			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'  => __( 'Title', WR_PBL ),
						'id'    => 'elm_title',
						'type'  => 'text_field',
						'role'  => 'title',
						'std'   => __( WR_Pb_Utils_Placeholder::add_placeholder( 'Testimonial Item %s', 'index' ), WR_PBL )
					),
					array(
						'name'    => __( 'Client\'s Name', WR_PBL ),
						'type'    => array(
							array(
								'id'          => 'name',
								'type'        => 'text_field',
								'std'         => '',
								'placeholder' => 'John Doe',
								'parent_class' => 'combo-item input-append-inline',
							),
							array(
								'id'           => 'name_height',
								'type'         => 'text_append',
								'type_input'   => 'number',
								'class'        => 'input-mini',
								'std'          => '12',
								'append'       => 'px',
								'validate'     => 'number',
								'parent_class' => 'combo-item input-append-inline',
							),
							array(
								'id'           => 'name_color',
								'type'         => 'color_picker',
								'std'          => '',
								'parent_class' => 'combo-item',
							),
						),
						'tooltip'         => __( 'Client\'s Name Description', WR_PBL ),
						'container_class' => 'combo-group',
					),
					array(
						'name'    => __( 'Client\'s Position', WR_PBL ),
						'id'      => 'job_title',
						'type'    => 'text_field',
						'std'     => __( '', WR_PBL ),
						'tooltip' => __( 'Client\'s Position Description', WR_PBL ),
						'placeholder' => 'CEO'
					),
					array(
						'name' => __( 'Feedback Content', WR_PBL ),
						'id'   => 'body',
						'role' => 'content',
						'type' => 'tiny_mce',
						'std'  => WR_Pb_Helper_Type::lorem_text()
					),
					array(
						'name'    => __( 'Country', WR_PBL ),
						'id'      => 'country',
						'type'    => 'text_field',
						'std'     => __( '', WR_PBL ),
						'tooltip' => __( 'Country Description', WR_PBL )
					),
					array(
						'name'    => __( 'Company', WR_PBL ),
						'id'      => 'company',
						'type'    => 'text_field',
						'std'     => __( '', WR_PBL ),
						'tooltip' => __( 'Company Description', WR_PBL )
					),
					array(
						'name'    => __( 'Website URL', WR_PBL ),
						'id'      => 'web_url',
						'type'    => 'text_field',
						'std'     => __( 'http://', WR_PBL ),
						'tooltip' => __( 'Website URL Description', WR_PBL )
					),
					array(
						'name'    => __( 'Avatar', WR_PBL ),
						'id'      => 'image_file',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => __( 'Image File for User', WR_PBL )
					),
				)
			);
		}

		/**
		 * DEFINE shortcode content
		 *
		 * @param type $atts
		 * @param type $content
		 */
		public function element_shortcode_full( $atts = null, $content = null ) {
			$atts['testimonial_content'] = $content;
			return serialize( $atts ) . '<!--seperate-->';

			extract( shortcode_atts( $this->config['params'], $atts ) );
			$img = ! empty( $image_file ) ? "<img class='wr-testimonial-image {style}' src='$image_file' />" : '';
			return "";
		}

	}

}
