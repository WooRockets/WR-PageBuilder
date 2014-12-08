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
class WR_Pb_Helper_Html_Preview extends WR_Pb_Helper_Html {
	/**
	 * Preview Box of shortcode
	 * @return type
	 */
	static function render() {
		$hide_preview = __( 'Hide Live Preview', WR_PBL );
		$show_preview = __( 'Show Live Preview', WR_PBL );
		return "<div class='wr-preview-resize'><div id='preview_container' class='wr-preview-container col-md-12'>
		<legend>" . __( 'Preview', WR_PBL ) . "</legend>
		<div id='wr_overlay_loading' class='jsn-overlay jsn-bgimage image-loading-24'></div>
		<iframe id='shortcode_preview_iframe' name='shortcode_preview_iframe' class='shortcode_preview_iframe'></iframe>
		</div></div>";
	}
}