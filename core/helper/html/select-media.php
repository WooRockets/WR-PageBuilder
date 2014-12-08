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
class WR_Pb_Helper_Html_Select_Media extends WR_Pb_Helper_Html {
	/**
	 * Input field to select Media
	 * @param type $element
	 * @return type
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label = parent::get_label( $element );
		$_filter_type = isset( $element['filter_type'] ) ? $element['filter_type'] : 'image';
		$output = '<div class="input-append row-fluid input-group">
							<input type="text" class="' . $element['class'] . '" value="' . $element['std'] . '" id="' . $element['id'] . '">
							<span class="input-group-addon select-media btn btn-default" filter_type="' . $_filter_type . '" id="' . $element['id'] . '_button">...</span>
							<span class="input-group-addon select-media-remove btn btn-default"><i class="icon-remove"></i></span>
						</div>';
		return parent::final_element( $element, $output, $label );
	}
}