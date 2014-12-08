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
class WR_Pb_Helper_Html_Dimension extends WR_Pb_Helper_Html {
	/**
	 * Dimension type, which defines Width, Height of element
	 * @param type $element
	 * @param type $input_params
	 * @return type
	 */
	static function render( $element, $input_params ) {
		$element = parent::get_extra_info( $element );
		$label = parent::get_label( $element );
		$element['dimension_elements'] = isset( $element['dimension_elements'] ) ? explode( ',', str_replace( ' ', '', ($element['dimension_elements']) ) ) : array( 'w', 'h' );
		$_no_prefix_id = str_replace( 'param-', '', $element['id'] );


		$output = '';
		if ( in_array( 'w', $element['dimension_elements'] ) ) {
			$_idx_width = $_no_prefix_id . '_width';
			$_idx_width_unit = $_no_prefix_id . '_width_unit';

			$element['width_std'] = isset( $element[$_idx_width] ) ? $element[$_idx_width]['std'] : '';
			$element['width_std'] = isset( $input_params[$_idx_width] ) ? $input_params[$_idx_width] : $element['width_std'];

			// Width and Width unit
			$_width = array(
				'id' => $element['id'] . '_width',
				'type' => 'text_append',
				'type_input' => 'number',
				'class' => 'jsn-input-number input-mini input-sm',
				'parent_class' => 'input-group-inline',
				'std' => $element['width_std'],
				'append_before' => 'Width',
				'validate' => 'number',
				'bound' => '0',
			);

			if ( isset( $element[$_idx_width_unit] ) ) {
				$element['width_unit_std'] = isset( $element[$_idx_width_unit] ) ? $element[$_idx_width_unit]['std'] : '';
				$element['width_unit_std'] = isset( $input_params[$_idx_width_unit] ) ? $input_params[$_idx_width_unit] : $element['width_unit_std'];

				$_w_unit = array(
					'id' => $element['id'] . '_width_unit',
					'type' => 'select',
					'class' => 'input-mini input-sm',
					'bound' => '0',
				);

				$_w_unit = array_merge( $_w_unit, $element[$_idx_width_unit] );
				$_w_unit['std'] = $element['width_unit_std'];
				$_append = '';
			} else {
				$_width = array_merge( $_width, array( 'append' => 'px' ) );
			}

			$output .= WR_Pb_Helper_Shortcode::render_parameter( 'text_append', $_width );
			$output .= isset( $element[$_idx_width_unit] ) ? WR_Pb_Helper_Shortcode::render_parameter( 'select', $_w_unit ) : '';
		}


		// Height and Height Unit
		if ( in_array( 'h', $element['dimension_elements'] ) ) {
			$_idx_height = $_no_prefix_id . '_height';
			$_idx_height_unit = $_no_prefix_id . '_height_unit';

			$element['height_std'] = isset( $element[$_idx_height] ) ? $element[$_idx_height]['std'] : '';
			$element['height_std'] = isset( $input_params[$_idx_height] ) ? $input_params[$_idx_height] : $element['height_std'];
			$_append = 'px';

			$_height = array(
				'id' => $element['id'] . '_height',
				'type_input' => 'number',
				'class' => 'jsn-input-number input-mini input-sm',
				'parent_class' => 'input-group-inline',
				'std' => $element['height_std'],
				'append_before' => 'Height',
				'validate' => 'number',
				'bound' => '0',
			);

			if ( isset( $element[$_idx_height_unit] ) ) {
				$element['height_unit_std'] = isset( $element[$_idx_width_unit] ) ? $element[$_idx_width_unit]['std'] : '';
				$element['height_unit_std'] = isset( $input_params[$_idx_width_unit] ) ? $input_params[$_idx_width_unit] : $element['width_unit_std'];
				$_h_unit = array(
					'id' => $element['id'] . '_height_unit',
					'type' => 'select',
					'class' => 'input-mini input-sm',
					'bound' => '0',
				);
				$_h_unit = array_merge( $_h_unit, $element[$_idx_height_unit] );
				$_h_unit['std'] = $element['height_unit_std'];
				$_append = '';
			} else {
				$_height = array_merge( $_height, array( 'append' => 'px' ) );
			}
			$output .= WR_Pb_Helper_Shortcode::render_parameter( 'text_append', $_height );
			$output .= isset( $element[$_idx_height_unit] ) ? WR_Pb_Helper_Shortcode::render_parameter( 'select', $_h_unit ) : '';
		}

		return parent::final_element( $element, $output, $label );
	}
}