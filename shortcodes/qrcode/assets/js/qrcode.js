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
	
	$.WR_QRCode = $.WR_QRCode || {};
	
	$.WR_QRCode = function () {
		// QR Code element process
        $('#param-qr_content_area').on('change', function () {
        	var html = $(this).val();
        	html = html.replace(/&/g, '');
        	html = html.replace(/$/g, '');
        	html = html.replace(/#/g, '');
        	var encode_html = html.replace(/"/g, '<wr_quote>');
        	$('#param-qr_content_area').val(html.substring(0, 1200));
        	$('#param-qr_content').val(encode_html);
        });
        $('#param-qr_content_area').trigger('change');
	}
	
	$(document).ready(function () {
		$.WR_QRCode();
	});
	
})(jQuery)