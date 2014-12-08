/**
 * @version    $Id$
 * @package    IGPGBLDR
 * @author     WooRockets Team <support@www.woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.woorockets.com
 * Technical Support: Feedback - http://www.www.woorockets.com/contact-us/get-support.html
 */

( function ($) {
	"use strict";

	$.IGSelectFonts	= $.IGSelectFonts || {};

    $.IGColorPicker = $.IGColorPicker || {};

    $.WR_Font_Color = $.WR_Font_Color || {};

	$.WR_Font_Color = function () {
		if (typeof $.IGSelectFonts != 'undefined') { new $.IGSelectFonts(); }
        if (typeof $.IGColorPicker != 'undefined') { new $.IGColorPicker(); }
	}

	$(document).ready(function () {
		$('body').bind('wr_after_popover', function (e) {
			$.WR_Font_Color();
		});
	});

})(jQuery);