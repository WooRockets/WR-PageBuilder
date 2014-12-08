<?php
/**
 * @version    $Id$
 * @package    WR PageBuilder
 * @author     WooRockets Team <support@www.woorockets.com>
 * @copyright  Copyright (C) 2012 www.woorockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.woorockets.com
 * Technical Support:  Feedback - http://www.www.woorockets.com
 */
class WR_Pb_Helper_Html_Group extends WR_Pb_Helper_Html {
	/**
	 * Group items
	 *
	 * @param type $element
	 *
	 * @return string
	 */
	static function render( $element ) {
		$_element                 = $element;

		$add_item                 = isset( $element['add_item_text'] ) ? $element['add_item_text'] : __( 'Add Item', WR_PBL );
		$sub_items                = $_element['sub_items'];
		$overwrite_shortcode_data = isset( $element['overwrite_shortcode_data'] ) ? $element['overwrite_shortcode_data'] : true;
		$sub_item_type            = $element['sub_item_type'];
		$items_html               = array();
		$shortcode_name           = str_replace( 'WR_', '', $element['shortcode'] );

		if ( $sub_items ) {
			foreach ( $sub_items as $idx => $item ) {
				$element = new $sub_item_type();
				$element->init_element();
				$label_item = ( isset( $element->config['exception']['item_text'] ) ) ? $element->config['exception']['item_text'] : '';

				// check if $item['std'] is empty or not
				$shortcode_data = '';

				if ( ! $label_item ) {
					$content = __( $shortcode_name, WR_PBL ) . ' ' . __( 'Item', WR_PBL ) . ' ' . ( $idx + 1 );
				} else {
					$content = rtrim( $label_item ) . ' ' . ( $idx + 1 );
				}
				if ( isset( $_element['no_title'] ) ) {
					$content = $_element['no_title'];
				}
				if ( ! empty( $item['std'] ) ) {
					// keep shortcode data as it is
					$shortcode_data = $item['std'];

					// reassign params for shortcode base on std string
					$extract_params = WR_Pb_Helper_Shortcode::extract_params( ( $item['std'] ) );

					$params         = WR_Pb_Helper_Shortcode::generate_shortcode_params( $element->items, NULL, $extract_params, TRUE, FALSE, $content );

					$element->shortcode_data();
					$params['extract_title'] = empty ( $params['extract_title'] ) ? __( '(Untitled)', WR_PBL ) : $params['extract_title'];
					$content                 = $params['extract_title'];
					if ( $overwrite_shortcode_data ) {
						$shortcode_data = $element->config['shortcode_structure'];
					}
				}

				$element_type = (array) $element->element_in_pgbldr( $content, $shortcode_data, '', $idx + 1 );
				foreach ( $element_type as $element_structure ) {
					$items_html[] = $element_structure;
				}
			}
		}

		$style        = ( isset( $_element['style'] ) ) ? 'style="' . $_element['style'] . '"' : '';
		$items_html   = implode( '', $items_html );
		$element_name = ( isset( $_element['name'] ) ) ? $_element['name'] : __( ucwords( ( ! $label_item ) ? $shortcode_name : $label_item ), WR_PBL );
		$element_name = str_replace( 'Item', '', $element_name ) . ' ' . __( 'Items', WR_PBL );
		$group_heading = WR_Pb_Helper_Html_Fieldset::render( array( 'name'    => $element_name, 'id'      => '', 'type'    => 'fieldset', ) );
		$html_element = "<div id='{$_element['id']}' class='form-group control-group clearfix'>$group_heading
				<div class='item-container has_submodal controls'>
					<ul $style class='ui-sortable jsn-items-list item-container-content jsn-rounded-medium' id='group_elements'>
					$items_html
					</ul>
					<a href='javascript:void(0);' class='jsn-add-more wr-more-element in-modal' item_common_title='" . __( $shortcode_name, WR_PBL ) . ' ' . __( 'Item', WR_PBL ) . "' data-shortcode-item='" . strtolower( $sub_item_type ) . "'><i class='icon-plus'></i>" . __( $add_item, WR_PBL ) . '</a>
				</div></div>';

					return $html_element;
	}
}