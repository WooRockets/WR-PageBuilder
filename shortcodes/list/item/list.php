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
if ( ! class_exists( 'WR_Item_List' ) ) {

	class WR_Item_List extends WR_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __( 'List Item', WR_PBL ),

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
						'name'    => __( 'Heading', WR_PBL ),
						'id'      => 'heading',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'role'    => 'title',
						'std'     => __( WR_Pb_Utils_Placeholder::add_placeholder( 'List Item %s', 'index' ), WR_PBL ),
			),
			array(
						'name'    => __( 'Body', WR_PBL ),
						'id'      => 'body',
						'role'    => 'content',
						'type'    => 'text_area',
						'container_class' => 'wr_tinymce_replace',
						'std'     => WR_Pb_Helper_Type::lorem_text(),
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
			WR_Pb_Helper_Functions::heading_icon( $heading, $icon, true );
			return "
			<li>
				[icon]<div class='wr-sub-icons' style='wr-styles'>
					<i class='$icon'></i>
				</div>[/icon]
				<div class='wr-list-content-wrap'>
					[heading]<h4 style='wr-list-title'>$heading</h4>[/heading]
					<p>$content</p>
				</div>
			</li><!--seperate-->";
		}

	}

}
