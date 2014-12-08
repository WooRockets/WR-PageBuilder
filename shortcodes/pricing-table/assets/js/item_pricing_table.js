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

	$.WR_Pb_Pricing_Item = $.WR_Pb_Pricing_Item || {};

	var sending_request = 0;

	$.WR_Pb_Pricing_Item = function (options) {
		var self = this;
		// Object parameters
		this.options = $.extend({}, options);

		var options_ = this.options;

		this.wrapper = options_.wrapper;

		// Get action button
		this.action_btn = options_.action_btn;

		// Get edit button
		this.button = options_.button;

		$(this.button).attr('data-custom-action', 'popover');

		// Get selector for parameters to check for change event
		this.parameter = options_.parameter;

		// Show popover when click edit button
		$('body').on( 'click', this.button, function (event) {
			event.preventDefault();
			event.stopPropagation();
			$('.jsn-modal:last #modalAction').children().hide();

			self.show_popover($(this));
		});

		// Update shortcode content of a Pricing option
		$('#modalOptions').on('update_sub_shortcode_content', function(e, obj) {
			var pricing_item = obj.related_textarea.parent();

			// Get Attributes content of this Item
			var attributes_sc = '';
			pricing_item.find('.jsn-items-list').find("[name^='shortcode_content']").each(function(){
				attributes_sc += $(this).val();
			});

			obj.sc_content = attributes_sc;
		});

		// Remove Pricing Attribute Shortcode from Sub shortcode content of Pricing
		$('#modalOptions').on('wr_get_sub_sc', function(e, obj) {
			var patt = /^\[wr_item_pricing_table_attr_value/g;
			if (patt.test(obj.sc_content)) {
				obj.sc_content = '';
			}
		});

		// Update shortcode content when a parameter changes
		this.wrapper.on('change', this.parameter, function (event) {
			event.preventDefault();
			event.stopPropagation();

			self.update_attibutes();

			// Update value in Attributes box
			if ($(this).is('#param-prtbl_item_attr_value')) {
				$('.submodal_frame_2 .active-btn').closest('.jsn-item').first().find('.jsn-item-content').html($(this).val());
			}

			self.regenerate_sc();
		});

		// Trigger change for #prtbl_item_attr_type
		$('body').on('click', '.prtbl_item_attr_type input[type="radio"]', function (event) {

			event.stopPropagation();

			if ($(this).is(':checked')) {
				var textarea = $(this).closest('.jsn-item').first().find("[name^='shortcode_content']");
				var shortcode_content = textarea.text();
				var value = $(this).val();

				if (shortcode_content.indexOf('prtbl_item_attr_value="yes"') >= 0) {
					shortcode_content = shortcode_content.replace('prtbl_item_attr_value="yes"', 'prtbl_item_attr_value="' + value + '"');
				} else {
					shortcode_content = shortcode_content.replace(/(prtbl_item_attr_value=")([^\"]+)(")/, '$1' + value + '$3');
				}
				if ( shortcode_content.indexOf('prtbl_item_attr_value') < 0 ) {
					shortcode_content = shortcode_content.replace(']', 'prtbl_item_attr_value="' + value + '"]');
				}

				textarea.text(shortcode_content);

				self.regenerate_sc();
			}
		});
	}

	$.WR_Pb_Pricing_Item.prototype = {

		/**
		 * Regenerate shortcode for Pricing table and Preview
		 *
		 * @returns {undefined}
		 */
		regenerate_sc: function () {

			// Get active Item
			var pricing_item = $('#group_elements .activate-item');

			// Get Attributes content of this Item
			var attributes_sc = '';
			pricing_item.find('.jsn-items-list').find("[name^='shortcode_content']").each(function(){
				attributes_sc += $(this).val();
			});

			// Merge existed shortcode content with Attributes content
			var pricing_item_sc_obj = pricing_item.find("[name^='shortcode_content']").first();

			var pricing_item_sc = pricing_item_sc_obj.val();

			if ( pricing_item_sc.indexOf('wr_item_pricing_table_attr_value') > 0 ) {
				pricing_item_sc = pricing_item_sc.replace(/(\[wr_item_pricing_table_attr_value.*)(\[\/wr_item_pricing_table)/, attributes_sc + '$2' );
			} else {
				pricing_item_sc = pricing_item_sc.replace(/(\[\/wr_item_pricing_table)/, attributes_sc + '$1' );
			}

			// Update whole shortcode content of Pricing Item
			pricing_item_sc_obj.text( pricing_item_sc );

			// Re-generate shortcode of pricing table
			var pricing_sc_obj = $('#modalOptions #shortcode-content').children('textarea');
			var pricing_sc = pricing_sc_obj.val();

			// Get shortcode of all pricing item
			var sc_items = '';
			$('#modalOptions #group_elements').children('.jsn-item').each(function(){
				sc_items += $(this).find("[name^='shortcode_content']").first().val();
			});

			pricing_sc = pricing_sc.replace(/(\[wr_item_pricing_table.*)(\[\/wr_pricing_table)/, sc_items + '$2' );
			pricing_sc_obj.val(pricing_sc);

			// Recall preview
			$.HandleSetting.shortcodePreview(pricing_sc, 'wr_pricing_table');
		},

		// Update shortcode content when change parameter's value in Popover
		update_attibutes: function () {

			// Get parameters name & value
			var data = $.HandleSetting.traverseParam(this.wrapper.find('.control-list-action').last().find('#Notab'));

			var sc_content = data.sc_content;
			var params_arr = data.params_arr;

			// Generate shortcode content
			var shortcode_name = this.wrapper.find('#shortcode_name').val();
			var tmp_content = $.HandleSetting.generateShortcodeContent(shortcode_name, params_arr, sc_content);

			// Update shortcode content
			$('.submodal_frame_2 .active-btn').closest('.jsn-item').first().find("[name^='shortcode_content']").text(tmp_content);
		},

		// Show Popover
		show_popover    : function (btnInput) {
			if (sending_request)
				return;

			if (!sending_request)
				sending_request = 1;

			$('.submodal_frame_2 .active-btn').removeClass('active-btn');
			$('.wr-display-block').removeClass('wr-display-block');
			$(btnInput).addClass('active-btn');
			$(btnInput).closest('.jsn-iconbar').addClass('wr-display-block');

			// Get shortcode content
			var shortcode_content = $(btnInput).parents('td').find('textarea').val();

			// Extract shortcode parameters
			var shortcode_params = $.HandleElement.extractScParam(shortcode_content);

			var settings_html = $('#tmpl-wr-pb-hidden-setting').html();
			var settings_container = $("<div/>").append(settings_html);

			// Hide tab
			settings_container.find('#wr_option_tab').hide();
			settings_container.find('#shortcode_content').hide();

			// Show popover
			var $popover_options = new $.IGPopoverOptions();
			$popover_options.showPopover(settings_container.html(), {btnInput: btnInput, el_title: '', show_hidden: false}, function () {
				$('.wr-display-block').removeClass('wr-display-block');
				$('.jsn-modal:last #modalAction').empty();
			});

			// Update value of fields
			$.each(shortcode_params, function (param_id, value) {
				$('.jsn-modal:last #modalAction').find('.control-list-action').find('#param-' + param_id).val(value);
			});

			// Center popover
			var scroll_pos             = $('.jsn-modal:last').scrollTop() + $(btnInput).offset().top - 70;
			var current_height_popover = $('.jsn-modal:last #modalAction .control-list-action:last .popover').height();
			var current_left_popover   = $('.jsn-modal:last #modalAction .control-list-action:last').position().left + 10;
			var popover_top            = scroll_pos - ( current_height_popover / 2 );
			$('.jsn-modal:last #modalAction .control-list-action:last').css( {
				'top' : popover_top + 'px',
				'left' : current_left_popover + 'px'
			});

			// Reset
			sending_request = 0;
		}
	}

	$(document).ready(function () {
		new $.WR_Pb_Pricing_Item({
			wrapper  : $('#modalAction'),
			button   : '.group-table .element-edit-ct',
			parameter: '[id^="param"]'
		});
		$('.no-hover-subitem').closest('.jsn-item').css({
			'border': 'none',
			'background-color': '#FFFFFF'
		});
	});

})(jQuery);