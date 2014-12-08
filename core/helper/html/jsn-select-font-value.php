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
class WR_Pb_Helper_Html_Jsn_Select_Font_Value extends WR_Pb_Helper_Html {
	/**
	 * Selectbox to select font
	 * @param type $element
	 * @return type
	 */
	static function render( $element ) {
		$selected_value = $element['std'];
		$element['exclude_class'] = array( 'form-control' );
		$element = parent::get_extra_info( $element );
		$label = parent::get_label( $element );

		$output  = "<select id='{$element['id']}' class='jsn-fontFace {$element['class']}' data-selected='{$selected_value}' value='{$selected_value}'>";
		$output .= "<option value='{$selected_value}' selected='selected'>{$selected_value}</option>";
		$output .= '</select>';

		return parent::final_element( $element, $output, $label );
	}
}