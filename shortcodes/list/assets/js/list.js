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

/**
 * Custom script for List element
 */
( function ($)
{
	"use strict";

	$.WR_List = $.WR_List || {};

	$.IGSelectFonts	= $.IGSelectFonts || {};

	$.WR_List = function () {
		if (typeof $.IGSelectFonts != 'undefined') { new $.IGSelectFonts(); }
        
		$('#param-font').on('change', function () {
			if ($(this).val() == 'inherit') {
				$('#param-font_face_type').val('standard fonts');
				$('.jsn-fontFaceType').trigger('change');
				$('#param-font_size_value_').val('');
				$('#param-font_style').val('bold');
				$('#param-color').val('#000000');
				$('#color-picker-param-color').ColorPickerSetColor('#000000');
				$('#color-picker-param-color div').css('background-color', '#000000');
			}
		});
	}

	$(document).ready(function () {
		$.WR_List();
	});

})(jQuery);