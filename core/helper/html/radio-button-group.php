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
class WR_Pb_Helper_Html_Radio_Button_Group extends WR_Pb_Helper_Html {
	/**
	 * Radio Button group
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );

		$output = "<div class='btn-group wr-btn-group' data-toggle='buttons'>";
		foreach ( $element['options'] as $key => $text ) {
			$active  = ( $key == $element['std'] ) ? 'active' : '';
			$checked = ( $key == $element['std'] ) ? 'checked' : '';
			$output .= "<label class='btn btn-default {$active}'>";
			$output .= "<input type='radio' name='{$element['id']}' $checked id='{$element['id']}' value='$key'/>";
			$output .= $text;
			$output .= "</label>";
		}
		$output .= '</div>';

		return parent::final_element( $element, $output, $label );
	}
}