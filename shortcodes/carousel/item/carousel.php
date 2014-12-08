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
if ( ! class_exists( 'WR_Item_Carousel' ) ) {

	/**
	 * Create Carousel element
	 *
	 * @package  WR PageBuilder Shortcodes
	 * @since    1.0.0
	 */
	class WR_Item_Carousel extends WR_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __( 'Carousel Item', WR_PBL ),

			);

			// Inline edit for sub item
			$this->config['edit_inline'] = true;
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
			array(
						'name'    => __( 'Image File', WR_PBL ),
						'id'      => 'image_file',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
			),
			array(
						'name'  => __( 'Heading', WR_PBL ),
						'id'    => 'heading',
						'type'  => 'text_field',
						'class' => 'input-sm',
						'role'  => 'title',
						'std'   => __( WR_Pb_Utils_Placeholder::add_placeholder( 'Carousel Item %s', 'index' ), WR_PBL ),
			),
			array(
						'name' => __( 'Body', WR_PBL ),
						'id'   => 'body',
						'role' => 'content',
						'type' => 'text_area',
						'container_class' => 'wr_tinymce_replace',
						'std'  => WR_Pb_Helper_Type::lorem_text(12) . '<a href="#"> link</a>',
			),
			array(
						'name'      => __( 'Icon', WR_PBL ),
						'id'        => 'icon',
						'type'      => 'icons',
						'std'       => '',
						'role'      => 'title_prepend',
						'title_prepend_type' => 'icon',
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
			extract( shortcode_atts( $this->config['params'], $atts ) );
			$content_class = ! empty( $image_file ) ? 'carousel-caption' : 'carousel-content';
			$img           = ! empty( $image_file ) ? "<img width='{WIDTH}' height='{HEIGHT}' src='$image_file' style='height : {HEIGHT}px;'>" : '';

			// remove image shortcode in content
			$content = WR_Pb_Helper_Shortcode::remove_wr_shortcodes( $content, 'wr_image' );

			$inner_content = WR_Pb_Helper_Shortcode::remove_autop( $content );
			WR_Pb_Helper_Functions::heading_icon( $heading, $icon, true );
			$heading       = trim( $heading );
			$inner_content = trim( $inner_content );

			if ( empty( $heading ) && empty( $inner_content ) ) {
				$html_content = "";
			} else {
				$html_content = "<div class='$content_class'>";
				$html_content .= ( ! empty( $heading ) ) ? "<h4><i class='$icon'></i>$heading</h4>" : '';
				$html_content .= ( ! empty( $inner_content ) ) ? "<p>{$inner_content}</p>" : '';
				$html_content .= "</div>";
			}

			return "<div class='{active} item'>{$img}{$html_content}</div><!--seperate-->";
		}

	}

}
