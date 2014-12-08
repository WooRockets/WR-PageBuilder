/**
 * @version    $Id$
 * @package    WR PageBuilder
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 woorockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support:  Feedback - http://www.woorockets.com
 */

/**
 * Custom script for QRCode element
 */
(function ($) {

	"use strict";

	$.WR_Tab = $.WR_Tab || {};

	$.WR_Tab = function () {
		$('a[href="#styling"]').on('click', function () {
			// Get list tab items
			var options = '',
				count = 0;

			$('#modalOptions #group_elements .jsn-element').each(function () {
				var text = $(this).find('.jsn-item-content').text();
				count++;
				options += '<option value="' + count + '">' + text + '</option>';
			});
			var initial_open = $('#param-initial_open');
			initial_open.html(options);
			initial_open.select2();
		});
	}

	$(document).ready(function () {
		// $.WR_Tab();
	});

})(jQuery)