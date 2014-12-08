/**
 * @version    $Id$
 * @package    WR_PageBuilder
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */

(function($) {
	$.WR_Data_Conversion = {
		init: function() {
			var self = this;

			// Get data conversion buttons
			this.buttons = $('.data-conversion-dialog button');

			this.buttons.each(function(i, e) {
				$(e).children().attr('data-default-text', $(e).text());
			});

			// Setup data conversion buttons
			this.buttons.click(function(event) {
				event.preventDefault();

				// Disable buttons
				self.buttons.addClass('disabled').attr('disabled', 'disabled');

				// Toggle button status
				$(this).children().addClass('wr-loading').text($(this).children().attr('data-working-text'));

				// Get current dialog
				var dialog = $(this).closest('.data-conversion-dialog');

				// Get current post ID
				var post_id = $('input#post_ID').val();

				// Get converter
				var converter = dialog.attr('data-target');

				// Get action
				var action = $(this).attr('data-action');

				// Request server-side script to copy post then convert data
				$.ajax({
					url: 'admin-ajax.php?action=wr-pb-convert-data&post=' + post_id + '&converter=' + converter + '&do=' + action,
					data: dialog.find('input').serializeArray(),
					complete: $.proxy(function(request, status) {
						// Parse response data
						if (response = request.responseText.match(/\{"success":[^,]+,"message":[^\}]+\}/)) {
							response = $.parseJSON(response[0]);
						} else {
							response = {success: false, message: ''};
						}

						// Hide form
						dialog.find('.action').hide();

						// Reset message bix style
						dialog.find('.alert').removeClass('alert-warning');

						if (response.success) {
							// Remove all unload event handler
							$(window).off('unload').unbind('unload');

							// Show success message for 3 seconds then reload page
							dialog.find('.alert').addClass('alert-success');
							dialog.find('.alert i').removeClass('icon-warning').addClass('icon-ok');

							setTimeout(function() {
								// Reload page
								window.location.reload();
							}, 3000);
						} else {
							// Show error message
							dialog.find('.alert').addClass('alert-danger');
							dialog.find('.alert i').removeClass('icon-warning').addClass('icon-remove');
						}

						dialog.find('.alert .message').html(response.message);
					}, this)
				});
			});
		},
	};

	$(document).ready(function() {
		$.WR_Data_Conversion.init();
	});
})(jQuery);
