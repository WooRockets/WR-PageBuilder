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
class WR_Pb_Helper_Html_Jsn_Select_Font_Type extends WR_Pb_Helper_Html {
	/**
	 * jsn select fonts element
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$selected_value = $element['std'];
		$options = $element['options'];
		$output = '';
		$label = '';
		if ( is_array( $options ) && count( $options ) > 0 ) {
			$element['exclude_class'] = array( 'form-control' );
			$element = parent::get_extra_info( $element );
			$label   = parent::get_label( $element );

			$output = "<select id='{$element['id']}' name='{$element['id']}' class='jsn-fontFaceType {$element['class']}' data-selected='{$selected_value}' value='{$selected_value}' >";
			foreach ( $options as $key => $value ) {
				if ( ! is_numeric( $key ) ) {
					$option_value = $key;
				} else {
					$option_value = $value;
				}
				$selected = ( $option_value == $selected_value ) ? 'selected' : '';
				$output  .= "<option value='$option_value' $selected>$value</option>";
			}
			$output .= '</select>';
		}

		add_filter( 'wr-edit-element-required-assets', array( __CLASS__, 'enqueue_assets_modal' ), 9 );

		return parent::final_element( $element, $output, $label );
	}

	/**
	 * Enqueue font selector assets
	 *
	 * @param array $scripts
	 * @return array
	 */
	static function enqueue_assets_modal( $scripts ){
		$scripts = array_merge( $scripts, array( 'wr-pb-joomlashine-fontselector-js', ) );

		return $scripts;
	}
}