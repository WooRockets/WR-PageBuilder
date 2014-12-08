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

/**
 * javascript for widget pagebuilder element
 */
(function ($) {
	'use strict';

	$.WR_Widget_PageBuilder = $.WR_Widget_PageBuilder || {};

	$.WR_Widget_Refresh = $.WR_Widget_Refresh || {};

	$.WR_Widget_PageBuilder = function () {
		$('.wr_shortcode_widget').each(function () {
			$(this).closest('.widget-content').find('.wr-pb-form-container').html('');
			if ( $(this).attr('value') ) {
				var shortcode_data = $(this).attr('value');
				shortcode_data = shortcode_data.replace(/--quote--/g, '"');
				shortcode_data = shortcode_data.replace(/--open_square--/g, '[');
				shortcode_data = shortcode_data.replace(/--close_square--/g, ']');
				var shortcode = $(this).closest('.widget-content').find('#wr_widget_edit_btn').attr('data-shortcode');
				var html = '';
				if ( shortcode ) {
					var title = shortcode.replace('wr_', '');
					title = title.replace('_', ' ');

					var html_preview = Wr_Preview_Html;
					html_preview = html_preview.replace(/WR_SHORTCODE_CONTENT/g, shortcode);
					html_preview = html_preview.replace(/WR_SHORTCODE_DATA/g, shortcode_data);

					html += '<input id="wr-select-media" type="hidden" value="">';
					html += html_preview;
					html += '<div class="shortcode-preview-container" style="display: none">';
					html += '<div class="shortcode-preview-fog"></div>';
					html += '<div class="jsn-overlay jsn-bgimage image-loading-24"></div>';
					html += '</div>';
					html += '</div> ';
				}

				$(this).closest('.widget-content').find('.wr-pb-form-container').html(html);
			}
		});
	}

	$.WR_Widget_Refresh = function () {
		$.WR_Widget_PageBuilder();
		$('.wr_widget_select_elm').each(function () {
			var selected = $(this).val();
			if ( selected ) {
				$(this).closest('.widget-content').find('#wr_widget_edit_btn').attr('data-shortcode', selected);
				$(this).closest('.widget-content').find('.wr-pb-form-container').html('<input type="hidden" id="wr-select-media" value="" />');
			}
		});
		$('body').delegate('.wr_widget_select_elm', 'change', function (e) {
			var selected = $(this).val();
			if ( selected ) {
				$(this).closest('.widget-content').find('#wr_widget_edit_btn').attr('data-shortcode', selected);
				$(this).closest('.widget-content').find('.wr-pb-form-container').html('<input type="hidden" id="wr-select-media" value="" />');
			}
			$(this).closest('.widget-content').find('.wr_shortcode_widget').attr('value', '');
		});

		$('body').delegate('.wr_widget_edit_btn', 'click', function () {
			var elm_title = '';
			elm_title = $(this).closest('.widget-content').find('.wr_widget_select_elm option:selected').text();
			$.WR_Widget_PageBuilder();
			// find parent for set active element
			$('.widget-content').removeClass('active_element');
			$(this).closest('.widget-content').addClass('active_element');
			$(this).closest('.widget-content').find('#wr-widget-loading').show();
			$(this).closest('.widget-content').find('.icon-pencil').hide();
			if ( $('.active_element .wr-pb-form-container .jsn-item').length ) {
				$('.active_element .wr-pb-form-container .jsn-item').addClass('wr-selected-element');
				var sc_html = $('.active_element .wr-pb-form-container').html();
				var $shortcode = $(this).attr('data-shortcode');
	            var $type = $(this).parent().attr('data-type');
				$.HandleElement.appendToHolder($shortcode, null, $type, sc_html, elm_title);
			} else {
	            var $shortcode = $(this).attr('data-shortcode');
	            var $type = $(this).parent().attr('data-type');
	            $.HandleElement.appendToHolder($shortcode, null, $type, '', elm_title);
			}
		});

		// set event update shortcode
		$('body').bind('on_update_shortcode_widget', function (e, shortcode_content) {
			if ( shortcode_content ) {
				if ( shortcode_content == 'is_cancel' ) {
					$('.active_element #form-design-content .wr-pb-form-container').html('');
				} else {
					$('.active_element #form-design-content .wr-pb-form-container').find("[data-sc-info^='shortcode_content']").text(shortcode_content);
					var json_shortcode = shortcode_content;
					json_shortcode = json_shortcode.replace(/"/g, '--quote--');
					json_shortcode = json_shortcode.replace(/\[/g, '--open_square--');
					json_shortcode = json_shortcode.replace(/\]/g, '--close_square--');
					$('.active_element .wr_shortcode_widget').val(json_shortcode);
					$('.active_element #form-design-content .wr-pb-form-container').html('');
				}
				$('.jsn-icon-loading').hide();
				$('.icon-pencil').show();
			}
		});
	}

	$(document).ready(function () {
		$.WR_Widget_Refresh();
	});

})(jQuery)