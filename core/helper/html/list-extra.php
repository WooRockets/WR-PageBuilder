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
class WR_Pb_Helper_Html_List_Extra extends WR_Pb_Helper_Html {
	/**
	 * List Extra Element
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$html  = "<div class='{$element['class']}'>";
		$html .= "<div id='{$element['id']}' class='jsn-items-list ui-sortable'>";

		if ( $element['std'] ) {

		}

		$html .= '</div>';
		$html .= "<a class='jsn-add-more add-more-extra-list' onclick='return false;' href='#'><i class='icon-plus'></i>" . __( 'Add Item', WR_PBL ) . '</a>';
		$html .= '</div>';
		return $html;
	}
}