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
class WR_Pb_Helper_Html_Icons extends WR_Pb_Helper_Html {
	/**
	 * Icons
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );
		$output  = "<div id='icon_selector' class='icon_selector'>
			<input type='hidden' value='{$element['std']}' id='{$element['id']}' name='{$element['id']}'  DATA_INFO />
		</div>";

		add_filter( 'wr-edit-element-required-assets', array( __CLASS__, 'enqueue_assets_modal' ), 0 );

		return parent::final_element( $element, $output, $label );
	}

	/**
	 * Enqueue icon selector assets
	 *
	 * @param array $scripts
	 * @return array
	 */
	static function enqueue_assets_modal( $scripts ){
		$scripts = array_merge( $scripts, array( 'wr-pb-joomlashine-iconselector-js', ) );

		return $scripts;
	}
}