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
class WR_Pb_Helper_Html_Tag extends WR_Pb_Helper_Html {
	/**
	 * Tag
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element['exclude_class'] = array( 'form-control' );
		$element = parent::get_extra_info( $element );
		$label = parent::get_label( $element );
		$element['class'] = ( $element['class'] ) ? $element['class'] . ' select2' : 'select2';
		$output = "<input type='hidden' value='{$element['std']}' id='{$element['id']}' class='{$element['class']}' data-share='wr_share_data' DATA_INFO />";

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