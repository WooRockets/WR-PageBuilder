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

if ( ! class_exists( 'WR_Table' ) ) :

/**
 * Create Table element
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_Table extends WR_Pb_Shortcode_Parent {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Configure shortcode.
	 *
	 * @return  void
	 */
	public function element_config() {
		$this->config['shortcode']        = strtolower( __CLASS__ );
		$this->config['name']             = __( 'Table', WR_PBL );
		$this->config['cat']              = __( 'Typography', WR_PBL );
		$this->config['icon']             = 'wr-icon-table';
		$this->config['has_subshortcode'] = 'WR_Item_' . str_replace( 'WR_', '', __CLASS__ );
		$this->config['description']      = __( 'Simple table with flexible setting', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'admin_assets' => array(
				// Shortcode initialization
				'table.js',
			),
			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',
			),
		);

		// Do not use Ajax to load element settings modal because this element has sub-item
		$this->config['edit_using_ajax'] = true;
	}

	/**
	 * Define shortcode settings.
	 *
	 * @return  void
	 */
	public function element_items() {
		$this->items = array(
			'content' => array(

				array(
					'name'          => __( 'Table Content', WR_PBL ),
					'id'            => 'table_',
					'type'          => 'table',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => "[wr_item_table tagname='tr_start' ][/wr_item_table]" ),
						array( 'std' => '' ),
						array( 'std' => '' ),
						array( 'std' => "[wr_item_table tagname='tr_end' ][/wr_item_table]" ),
						array( 'std' => "[wr_item_table tagname='tr_start' ][/wr_item_table]" ),
						array( 'std' => '' ),
						array( 'std' => '' ),
						array( 'std' => "[wr_item_table tagname='tr_end' ][/wr_item_table]" ),
					),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => __( 'Style', WR_PBL ),
					'id'      => 'tb_style',
					'type'    => 'select',
					'class'   => 'input-sm',
					'options' => array( 'table-default' => __( 'Default', WR_PBL ), 'table-striped' => __( 'Striped', WR_PBL ), 'table-bordered' => __( 'Bordered', WR_PBL ), 'table-hover' => __( 'Hover', WR_PBL ) ),
					'std'     => 'default',
				),
				WR_Pb_Helper_Type::get_apprearing_animations(),
				WR_Pb_Helper_Type::get_animation_speeds(),
			)
		);
	}

	/**
	 * Generate HTML code from shortcode content.
	 *
	 * @param   array   $atts     Shortcode attributes.
	 * @param   string  $content  Current content.
	 *
	 * @return  string
	 */
	public function element_shortcode_full( $atts = null, $content = null ) {
		$arr_params    = ( shortcode_atts( $this->config['params'], $atts ) );

		$sub_shortcode = do_shortcode( $content );
		// seperate by cell
		$items_html    = explode( '<!--seperate-->', $sub_shortcode );

		// remove empty element
		$items_html    = array_filter( $items_html );
		$row           = 0;
		$not_empty     = 0;
		$updated_html  = array();
		
		foreach ( $items_html as $item ) {
			$cell_html = '';
			$cell_wrap = ( $row == 0 ) ? 'th' : 'td';

			if ( strpos( $item, 'CELL_WRAPPER' ) === false ) {
				$cell_html .= ( $item == 'tr_start' ) ? '<tr>' : '</tr>';
				if ( strip_tags( $item ) == 'tr_end' )
				$row++;
			}
			else {
				if ( strpos( $item, '<!--empty-->' ) !== false ) {
					$item = str_replace( '<!--empty-->', '', $item );
				} else {
					$not_empty++;
				}

				$cell_html .= str_replace( 'CELL_WRAPPER', $cell_wrap, $item );
			}
			$updated_html[] = $cell_html;
		}

		$sub_shortcode = ( $not_empty == 0 ) ? '' : implode( '', $updated_html );

		$html_element = "<table class='table {$arr_params['tb_style']}'>" . $sub_shortcode . '</table>';
		return $this->element_wrapper( $html_element, $arr_params );
	}
}

endif;
