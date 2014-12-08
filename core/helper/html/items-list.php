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
class WR_Pb_Helper_Html_Items_List extends WR_Pb_Helper_Html {
	/**
	 * List of other option types ( checkbox, select... )
	 * @param type $element
	 * @return type
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );

		$options_type = isset( $element['options_type'] ) ? $element['options_type'] : '';
		$ul_wrap = isset( $element['ul_wrap'] ) ? $element['ul_wrap'] : true;
		$output  = '';
		$element_clone = $element;
		$element_clone['wrapper_item_start'] = "<li class='jsn-item jsn-iconbar-trigger'>";
		$element_clone['wrapper_item_end']   = '</li>';
		$element_clone['blank_output'] = '1';
		$element['class'] = str_replace( 'form-control', '', $element['class'] );
		$element_clone['class'] = ( isset($element['class'] ) ? $element['class'] : '') . ' ' . $options_type;

		// re-arrange $element['options'] array by the order of value in $element['std']
		$element_clone['std'] = str_replace( ',', '__#__', $element_clone['std'] );
		if ( ! isset( $element_clone['no_order'] ) ) {
			$std_val = explode( '__#__', $element_clone['std'] );
			$std     = array();
			foreach ( $std_val as $value ) {
				if ( trim( $value ) != '' && isset ( $element_clone['options'][$value] ) )
				$std[$value] = $element_clone['options'][$value];
			}
			// other option value which is not defined in std
			foreach ( $element_clone['options'] as $key => $value ) {
				if ( ! in_array( $key, $std_val ) )
				$std[$key] = $value;
			}
			$element_clone['options'] = $std;
		}

		$output = WR_Pb_Helper_Shortcode::render_parameter( $options_type, $element_clone );
		$output = $ul_wrap ? "<ul class='jsn-items-list ui-sortable'>$output</ul>" : $output;
		return parent::final_element( $element, $output, $label );
	}
}