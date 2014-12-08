/**
 * @version    $Id$
 * @package    IGPGBLDR
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support: Feedback - http://www.woorockets.com/contact-us/get-support.html
 */

( function ($) {
	"use strict";
	
	$(document).ready(function () {
		// Update preview when select icon
		$( '#modalOptions' ).delegate( '.jsn-iconselector .jsn-items-list .jsn-item', 'click', function () {
			var parent_tab = $(this).parents('.wr-pb-setting-tab');
            var stop_reload_iframe = ((parent_tab.length > 0 && parent_tab.is("#styling")) || (parent_tab.length > 0 && parent_tab.is("#modalAction"))) ? 0 : 1;

            $.HandleSetting.shortcodePreview(null, null, null, null, stop_reload_iframe);
		});
		
		// Limit percent input
		$('#wr-element-progresscircle #modalOptions #param-percent').on('change', function () {
			var percent_value = parseInt($(this).val());
			if ( percent_value > 100 ) {
				$(this).val('100')
			}
		});
	});

})(jQuery);
