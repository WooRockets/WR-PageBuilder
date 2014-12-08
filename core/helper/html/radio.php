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
class WR_Pb_Helper_Html_Radio extends WR_Pb_Helper_Html {
	/**
	 * Radio
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element['class'] = isset( $element['class'] ) ? $element['class'] : 'radio-inline';
		$element['type_input'] = 'radio';
		return WR_Pb_Helper_Shortcode::render_parameter( 'checkbox', $element );
	}
}