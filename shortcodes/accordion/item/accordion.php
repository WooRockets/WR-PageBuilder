<?php
/**
 * @version    $Id$
 * @package    WR PageBuilder Shortcodes
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 woorockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support:  Feedback - http://www.woorockets.com
 */
if ( ! class_exists( 'WR_Item_Accordion' ) ) {

	/**
	 * Create accordion child element.
	 *
	 * @package  WR PageBuilder Shortcodes
	 * @since    1.0.0
	 */
	class WR_Item_Accordion extends WR_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __( 'Accordion Item', WR_PBL ),

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
								'std'   => __( WR_Pb_Utils_Placeholder::add_placeholder( 'Accordion Item %s', 'index' ), WR_PBL ),
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
					array(
								'name' => __( 'Tag Name', WR_PBL ),
								'id'   => 'tag',
								'type' => 'tag',
								'std'  => '',
                        'tooltip' => __( 'Used for items filtering', WR_PBL ),
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

			// tag1,tag2 => tag1 tag2 , to filter
			$tag = str_replace( ' ', '_', $tag );
			$tag = str_replace( ',', ' ', $tag );
			$inner_content = WR_Pb_Helper_Shortcode::remove_autop( $content );
			WR_Pb_Helper_Functions::heading_icon( $heading, $icon );
			return "
			<div class='panel panel-default' data-tag='$tag'>
				<div class='panel-heading'>
					<h4 class='panel-title'>
						<a data-toggle='collapse' href='#collapse{index}'>
							<i class='$icon'></i>$heading
							<i class='wr-icon-accordion'></i>
						</a>
					</h4>
				</div>
				<div id='collapse{index}' class='panel-collapse collapse {show_hide}'>
				  <div class='panel-body'>
				  {$inner_content}
				  </div>
				</div>
			</div><!--seperate-->";
		}

	}

}
