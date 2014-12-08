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
class WR_Pb_Helper_Html_Select extends WR_Pb_Helper_Html {
	/**
	 * select box
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$selected_value = $element['std'];
		$options = $element['options'];
		$output = '';
		$label  = '';
		if ( is_array( $options ) && count( $options ) > 0 ) {
			$element  = parent::get_extra_info( $element );
			$label    = parent::get_label( $element );
			$multiple = ( isset( $element['multiple'] ) ) ? 'multiple="multiple"' : '';
			// Add default select2 for all select html type
			if ( ! isset( $element['disable_select2'] ) ) {
				$element['class'] .= ' select2-select';
			}
			$element['class'] = str_replace( 'form-control', '', $element['class'] );

			// Check selected value has exists in default options
			$new_option = '';
			if ( ! empty( $element['options'] ) && ! empty( $selected_value ) ) {
				if ( ! array_key_exists( $selected_value, $element['options'] ) ){
					$new_option = "<option value='$selected_value' selected>$selected_value</option>";
				}
			}

			$output = "<select id='{$element['id']}' name='{$element['id']}' class='{$element['class']}' {$multiple} >";
			foreach ( $options as $key => $value ) {
				if ( is_array( $value ) ) {
					if ( ( $value['type'] == 'optiongroup' ) )
					$output .= '<optgroup label="' . $value['text'] . '">';
				} else {
					$option_value = $key;
					$selected     = ( $option_value == $selected_value ) ? 'selected' : '';
					$output      .= "<option value='$option_value' $selected>$value</option>";
				}
			}
			$output .= $new_option;
			$output .= '</select>';
			if ( isset( $element['append_text'] ) ) {
				$output .= "<span class='add-on input-group-addon'>{$element['append_text']}</span>";
			}
			if ( isset( $element['multiple'] ) ) {
				$output .= "<input type='hidden' id='{$element['id']}_select_multi' value='{$element['std']}' />";
			}
		}

		add_filter( 'wr-edit-element-required-assets', array( __CLASS__, 'enqueue_assets_modal' ), 9 );

		return parent::final_element( $element, $output, $label );
	}

	/**
	 * Enqueue select2 assets
	 *
	 * @param array $scripts
	 * @return array
	 */
	static function enqueue_assets_modal( $scripts ){
		$scripts = array_merge( $scripts, array( 'wr-pb-jquery-select2-js', ) );

		return $scripts;
	}
}