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
 * Custom script for pricing table item element
 */
(function ($) {
	'use strict';

	$.WR_Pb_Pricing_Tbl = $.WR_Pb_Pricing_Tbl || {};

	$.WR_Pb_Pricing_Tbl = function (options) {
		var self = this;
		// Object parameters
		this.options = $.extend({}, options);

		var options_ = this.options;

		// Get wrapper
		this.wrapper = options_.wrapper;

		// Get edit button
		this.button = options_.button;

		// Update Attributes list when click to edit an option
//		this.wrapper.on('click', this.button, function (event) {
//			event.preventDefault();
//
//			var attributes = self.get_attibutes();
//
//			$.HandleElement.removeCookie('wr_pb_data_for_modal');
//			$.HandleElement.setCookie('wr_pb_data_for_modal', attributes);
//		});

	}

	$.WR_Pb_Pricing_Tbl.prototype = {

		// Get Attributes list
		get_attibutes: function () {
			var attributes_content = [];

			if ($('#param-prtbl_attr').length) {
				$('#param-prtbl_attr').find("[name^='shortcode_content']").each(function (i) {
					attributes_content[i] = $(this).val();
				});
			}

			return attributes_content.join('--[wr_pb_seperate_sc]--');
		}
	}

	$(document).ready(function () {
		var $wr_pricing_item = new $.WR_Pb_Pricing_Tbl({
			wrapper: $('body'),
			button : '#param-prtbl_items .element-edit-sub'
		});

		$('body').on('filter_shortcode_data', function( e ){
			var attributes = $wr_pricing_item.get_attibutes();

			$.HandleElement.removeCookie('wr_pb_data_for_modal');
			$.HandleElement.setCookie('wr_pb_data_for_modal', attributes);
		});
	});

})(jQuery);