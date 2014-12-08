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
if ( ! class_exists( 'WR_Item_Tab' ) ) {
	/**
	 * Create child Tab element
	 *
	 * @package  WR PageBuilder Shortcodes
	 * @since    1.0.0
	 */
	class WR_Item_Tab extends WR_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __( 'Tab Item', WR_PBL ),

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
						'name'  => __( 'Heading', WR_PBL ),
						'id'    => 'heading',
						'type'  => 'text_field',
						'class' => 'input-sm',
						'role'  => 'title',
						'std'   => __( WR_Pb_Utils_Placeholder::add_placeholder( 'Tab Item %s', 'index' ), WR_PBL ),
			),
			array(
						'name' => __( 'Body', WR_PBL ),
						'id'   => 'body',
						'role' => 'content',
						'type' => 'text_area',
						'container_class' => 'wr_tinymce_replace',
						'std'  => WR_Pb_Helper_Type::lorem_text(),
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
			$arr_params = ( shortcode_atts( $this->config['params'], $atts ) );
			extract( $arr_params );
			$inner_content = WR_Pb_Helper_Shortcode::remove_autop( $content );
			$custom_style  = WR_Pb_Utils_Placeholder::get_placeholder( 'custom_style' );
			return "$heading<!--heading-->$icon<!--icon--><div id='pane{index}' class='tab-pane {active} {fade_effect}' $custom_style>
			{$inner_content}
				</div><!--seperate-->";
		}

	}

}
