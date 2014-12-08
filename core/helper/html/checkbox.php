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
class WR_Pb_Helper_Html_Checkbox extends WR_Pb_Helper_Html {
	/**
	 * Checkbox option
	 * @param type $element
	 * @return type
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );
		$type    = isset( $element['type_input'] ) ? $element['type_input'] : 'checkbox';

		$element['std'] = explode( '__#__', $element['std'] );
		$output = $add_class = $linebreak = '';
		$_class = !empty( $element['class'] ) ? $element['class'] : 'checkbox inline';
		$_class = str_replace( 'form-control', '', $_class );
		$_wr_has_depend = ( ! empty($element['has_depend'] ) && $element['has_depend'] == '1') ? ' wr_has_depend' : '';

		foreach ( $element['options'] as $key => $text ) {
			$checked     = ( in_array( $key, $element['std'] ) || $element['std'][0] == 'all' ) ? 'checked' : '';
			$action_item = '';
			if ( isset($element['popover_items'] ) && is_array( $element['popover_items'] ) )
			$action_item = in_array( $key, $element['popover_items'] ) ? "data-popover-item='yes'" : '';
			if ( isset ( $element['label_type'] ) ) {
				if ( $element['label_type'] == 'image' ){
					// hide radio button
					$add_class    = ' hidden';
					$option_html  = '';
					$dimension    = $element['dimension'];
					$width_height = "width:{$dimension[0]}px;height:{$dimension[1]}px;";
					if ( ! is_array( $text ) ) {
						$option_html .= "<span style='$width_height' class='radio_image'></span>";
					}
					else {
						$linebreak    = isset ( $text['linebreak'] ) ? '<br>' : '';
						$background   = isset( $text['img'] ) ? "background-image:url( {$text['img']} )" : '';
						$option_html .= "<span style='$width_height $background' title='{$text[0]}' class='radio_image'></span>";
					}
					$text = $option_html;
				}
			}
			$str = "<label class='" . $_class . "'><input class='{$_wr_has_depend}{$add_class}' type='" . $type . "' value='$key' id='{$element['id']}' name='{$element['id']}' $checked DATA_INFO $action_item/>$text</label>$linebreak";

			if ( isset( $element['wrapper_item_start'] ) )
			$str = $element['wrapper_item_start'] . $str;
			if ( isset( $element['wrapper_item_end'] ) )
			$str    = $str . $element['wrapper_item_end'];
			$output .= $str;
		}
		if ( $type == 'checkbox' ) {
			$output .= "<input type='hidden' value=' ' id='{$element['id']}' name='{$element['id']}' />";
		}

		return parent::final_element( $element, $output, $label );
	}
}