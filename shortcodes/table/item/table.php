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
if ( ! class_exists( 'WR_Item_Table' ) ) {

	/**
	 * Create Table child element
	 *
	 * @package  WR PageBuilder Shortcodes
	 * @since    1.0.0
	 */
	class WR_Item_Table extends WR_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'item_text'        => __( '', WR_PBL ),
				'data-modal-title' => __( 'Table Item', WR_PBL ),
				'item_wrapper'     => 'div',
				'action_btn'       => 'edit',

				'admin_assets' => array(
					// Shortcode initialization
					'item_table.js',
				),
			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name' => __( 'Width', WR_PBL ),
						'type' => array(
							array(
								'id'           => 'width_value',
								'type'         => 'text_number',
								'std'          => '',
								'class'        => 'input-mini',
								'validate'     => 'number',
								'parent_class' => 'combo-item merge-data',
							),
							array(
								'id'           => 'width_type',
								'type'         => 'select',
								'class'        => 'input-mini',
								'options'      => array( 'percentage' => '%', 'px' => 'px' ),
								'std'          => 'percentage',
								'parent_class' => 'combo-item merge-data',
							),
						),
						'container_class' => 'combo-group',
					),
					array(
						'name'            => __( 'Tag Name', WR_PBL ),
						'id'              => 'tagname',
						'type'            => 'text_field',
						'std'             => 'td',
						'type_input'      => 'hidden',
						'container_class' => 'hidden',
                        'tooltip' => __( '', WR_PBL ),
					),
					array(
						'name'     => __( 'Row Span', WR_PBL ),
						'id'       => 'rowspan',
						'type'     => 'text_number',
						'std'      => '1',
						'class'    => 'input-mini positive-val',
						'validate' => 'number',
						'role'     => 'extract',
                        'tooltip' => __( 'Enable extending over multiple rows', WR_PBL ),
					),
					array(
						'name'     => __( 'Column Span', WR_PBL ),
						'id'       => 'colspan',
						'type'     => 'text_number',
						'std'      => '1',
						'class'    => 'input-mini positive-val',
						'validate' => 'number',
						'role'     => 'extract',
                        'tooltip' => __( 'Enable extending over multiple columns', WR_PBL ),
					),
					array(
						'name'    => __( 'Row Style', WR_PBL ),
						'id'      => 'rowstyle',
						'type'    => 'select',
						'class'   => 'input-sm',
						'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_table_row_color() ),
						'options' => WR_Pb_Helper_Type::get_table_row_color(),
					),
					array(
						'name'   => __( 'Content', WR_PBL ),
						'id'     => 'cell_content',
						'role'   => 'content',
						'role_2' => 'title',
						'type'   => 'tiny_mce',
						'std'    => __( '', WR_PBL ),
                        'tooltip' => __( 'Table content', WR_PBL ),
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
			$rowstyle = ( ! $rowstyle || strtolower( $rowstyle ) == 'default' ) ? '' : $rowstyle;
			if ( in_array( $tagname, array( 'tr_start', 'tr_end' ) ) ) {
				return "$tagname<!--seperate-->";
			}
			
			$width_type = $width_type == 'percentage' ? '%' : $width_type;
			$width = ! empty( $width_value ) ? "width='{$width_value}{$width_type}'" : '';
			$empty = empty( $content ) ? '<!--empty-->' : '';
			return "<CELL_WRAPPER class='$rowstyle' rowspan='$rowspan' colspan='$colspan' $width>" . WR_Pb_Helper_Shortcode::remove_autop( $content ) . "</CELL_WRAPPER>$empty<!--seperate-->";
		}

	}

}
