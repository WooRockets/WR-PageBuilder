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
class WR_Pb_Helper_Html_Text_Field extends WR_Pb_Helper_Html {
	/**
	 * Simple Input text
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );
		$type    = ! empty( $element['type_input'] ) ? $element['type_input'] : 'text';
		$placeholder = isset( $element['placeholder'] ) ? "placeholder='{$element['placeholder']}'" : '';
		$output  = "<input type='$type' class='{$element['class']}' value='{$element['std']}' id='{$element['id']}' name='{$element['id']}' DATA_INFO {$placeholder} />";

		return parent::final_element( $element, $output, $label );
	}
}