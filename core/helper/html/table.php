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
class WR_Pb_Helper_Html_Table extends WR_Pb_Helper_Html {
	/**
	 * generate HTML in WR PageBuilder for Table type
	 * @param sub_item_type $element
	 * @return type
	 */
	static function render( $element ) {
		$label = parent::get_label( $element );
		$sub_items     = $element['sub_items'];
		$sub_item_type = $element['sub_item_type'];
		$items_html    = array();
		$sample_table_item = '';

		// Get HTML of Each Cell
		$shortcode_data_arr = array();
		// Add extra params to disable drag handle
		$extra_params = array(
			'drag_handle' => false
		);

		foreach ( $sub_items as $idx => $item ) {
			$element_ = new $sub_item_type();
			$element_->init_element();
			$shortcode_data = '';
			$content = '&nbsp;'; // don't leave it empty
			if ( ! empty( $item['std'] ) ) {
				// keep shortcode data as it is
				$shortcode_data = $item['std'];
				// reassign params for shortcode base on std string
				$extract_params = WR_Pb_Helper_Shortcode::extract_params( ( $item['std'] ) );
				$params = WR_Pb_Helper_Shortcode::generate_shortcode_params( $element_->items, NULL, $extract_params, TRUE, FALSE, $content );
				$element_->shortcode_data();
				if ( ! empty( $params['extract_title'] ) ) {
					$content = $params['extract_title'];
					$shortcode_data = $element_->config['shortcode_structure'];
				}
				$shortcode_data_arr[$idx] = $shortcode_data;
			}
			$element_type = $element_->element_in_pgbldr( $content, $shortcode_data, '', '', true, $extra_params );
				
			// Create sample table item data
			if ( ! $sample_table_item ) {
				$sample_table_item = $element_->element_in_pgbldr( null, null, '', '', true, $extra_params );
			}
				
			foreach ( $element_type as $element_structure ) {
				$items_html[] = $element_structure;
			}
		}

		// Wrap cell to a Table to display in WR PageBuilder
		$row = 0;
		$updated_html  = array();
		$columns_count = array();
		foreach ( $items_html as $idx => $cell ) {
			if ( ! isset( $columns_count[$row] ) )
			$columns_count[$row] = 0;
			else
			$columns_count[$row]++;

			$cell_html = '';
			$cell_wrap = ( $row == 0 ) ? 'th' : 'td';
			if ( strpos( $cell, "[wr_item_table tagname='tr_start' ][/wr_item_table]" ) !== false )
			$cell_html .= '<tr>';
			else if ( strpos( $cell, "[wr_item_table tagname='tr_end' ][/wr_item_table]" ) !== false ) {
				// Delete button on right side of table
				$action_html = ( $row == 0 ) ? '' : "<a href='#' title='".__( 'Delete', WR_PBL )."' onclick='return false;' data-target='row_table' class='element-delete'><i class='icon-trash'></i></a>";
				$cell_html  .= "<$cell_wrap valign='middle' class='wr-delete-column-td'><div class='jsn-iconbar'>$action_html</div></$cell_wrap>";
				$cell_html  .= '</tr>';
				$row++;
			} else {
				extract( shortcode_parse_atts( $shortcode_data_arr[$idx] ) );
				$width = ! empty( $width_value ) ? "width='$width_value$width_type'" : '';
				$cell_html .= "<$cell_wrap rowspan='$rowspan' colspan='$colspan' $width>$cell</$cell_wrap>";
			}
			$updated_html[] = $cell_html;
		}

		// Delete button below the table
		$bottom_row = "<tr class='wr-row-of-delete'>";
		for ( $i = 0; $i < max( $columns_count ) - 1; $i++ ) {
			$bottom_row .= "<td><div class='jsn-iconbar'><a href='#' title='".__( 'Delete', WR_PBL )."' onclick='return false;' data-target='column_table' class='element-delete'><i class='icon-trash'></i></a></div></td>";
		}
		$bottom_row    .= '</tr>';
		$updated_html[] = $bottom_row;

		$items_html = "<table class='table table-bordered igpb-table-exceprt' id='table_content'>" . implode( '', $updated_html ) . '</table>';
		// end Wrap

		$buttons = '<button class="btn btn-default table_action" data-target="table_row">'.__( 'Add Row', WR_PBL ).'</button>
					<button class="btn btn-default table_action" data-target="table_column">'.__( 'Add Column', WR_PBL ).'</button>';

		$output = "<div class='item-container has_submodal table_element'>
						<div class='jsn-fieldset-filter'><div class='btn-toolbar clearafter'>$buttons</div></div>
						<div class='ui-sortable item-container-content'>
						$items_html
						</div>
					</div>";
						$sample_tmpl_id = strtolower( $sub_item_type );
						$output .= ( isset( $sample_table_item[0] ) ) ? "<script id='tmpl-" . $sample_tmpl_id . "-sample' type='text/html'>" . $sample_table_item[0] . "</script>" : '';

						return parent::final_element( $element, $output, $label );
	}
}