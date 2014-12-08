/**
 * @version    $Id$
 * @package    WR_Library
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support: Feedback - http://www.woorockets.com/contact-us/get-support.html
 */

// Declare WooRockets Upgrade class
(function($) {
	WR_Upgrade = function(params) {
		// Object parameters
		this.params = $.extend({}, params);
		this.lang = this.params.language || {};

		$(document).ready($.proxy(function() {
			// Get update button object
			this.button = document.getElementById(this.params.button);

			// Set event handler to update product
			$(this.button).click($.proxy(function(event) {
				event.preventDefault();
				this.install();
			}, this));
		}, this));
	};

	// Declare methods for WooRockets Update class
	WR_Upgrade.prototype = {
		install: function() {
			// Mark installation step
			this.step = 1;

			// Hide form action
			$('#jsn-upgrade-action').hide();

			// Execute current installation step
			this.execute();
		},

		execute: function() {
			// Call appropriate method
			this['step' + this.step]();
		},

		step1: function() {
			// Show login form
			$('#jsn-upgrade-login').show();

			// Setup login form
			$(document.JSNUpgradeLogin).delegate('input[type="text"], input[type="password"]', 'keyup', $.proxy(function(event) {
				if (event.keyCode == 13) {
					return;
				}

				// Check if user can login
				var canLogin = true;

				$('input[type="text"], input[type="password"]', document.JSNUpgradeLogin).each(function() {
					canLogin = canLogin && this.value != '';
				});

				canLogin
					? $('button', document.JSNUpgradeLogin).removeAttr('disabled') 
					: $('button', document.JSNUpgradeLogin).attr('disabled', 'disabled');
			}, this));

			$('button', document.JSNUpgradeLogin).click($.proxy(function(event) {
				event.preventDefault();

				// Execute next upgrade step
				this.step++;
				this.execute();
			}, this));
		},

		step2: function () {
			// Hide any visible message
			$('#jsn-upgrade-message').empty().hide();

			// Disable button
			$('button', document.JSNUpgradeLogin).attr('disabled', 'disabled');

			// Request server-side to get purchased edition
			$.getJSON(
				$(this.button).attr('data-source') + '?action=wr-check-edition' + '&' + 'id=' + this.params.identified_name + '&' + 'edition=' + this.params.edition + '&' + $(document.JSNUpgradeLogin).serialize(),
				$.proxy(function(response) {
					if (response.type == 'error') {
						// Show error messge
						$('#jsn-upgrade-message').text(response.message).show();

						// Enable button
						$('button', document.JSNUpgradeLogin).removeAttr('disabled') ;

						this.step--;
						return;
					}

					// Generate edition select box
					var edition = $('select', document.JSNUpgradeLogin).empty();

					$.map(response, function (e) {
						edition.append($('<option />', { value: e, text: e }));
					});

					if (response.length == 1) {
						// Execute next upgrade step
						this.step++;
						this.execute();
					} else {
						$('#jsn-upgrade-editions').css('display', 'block');

						// Enable button
						$('button', document.JSNUpgradeLogin).removeAttr('disabled') ;
					}
				}, this)
			);
		},

		step3: function() {
			// Update indicators
			$('#jsn-upgrade-cancel').hide();
			$('#jsn-upgrade-login').hide();
			$('#jsn-upgrade-indicator').show();
			$('#jsn-upgrade-downloading-unsuccessful-message').hide();

			// Request server-side to download update package
			$.ajax({
				url: $(this.button).attr('data-source') + '?action=wr-download-update' + '&' + 'id=' + this.params.identified_name + '&' + 'edition=' + $('select', document.JSNUpgradeLogin).val(),
				type: document.JSNUpgradeLogin.method,
				data: $(document.JSNUpgradeLogin).serialize(),
				context: this
			}).done(function(data) {
				this.clearTimer('#jsn-upgrade-downloading-indicator');

				if (data.substr(0, 4) == 'DONE') {
					// Update indicators
					$('#jsn-upgrade-downloading-indicator').removeClass('jsn-icon-loading').addClass('jsn-icon-ok');

					// Update download link to install link
					$(this.button).attr('data-source', $(this.button).attr('data-source').replace('.download', '.install'));
					this.button.data = 'path=' + data.replace(/^DONE:(\s+)?/, '');

					// Execute next installation step
					this.step++;
					this.execute();
				} else {
					// Update indicators
					$('#jsn-upgrade-downloading-indicator').removeClass('jsn-icon-loading').addClass('jsn-icon-remove');
					$('#jsn-upgrade-downloading-unsuccessful-message').html(data.replace(/^FAIL:(\s+)?/, '')).show();
				}
			});

			this.setTimer('#jsn-upgrade-downloading-indicator');
		},

		step4: function() {
			// Update indicators
			$('#jsn-upgrade-installing').show();
			$('#jsn-upgrade-installing-unsuccessful-message').hide();
			$('#jsn-upgrade-installing-warnings').hide();

			// Request server-side to install dowmloaded package
			$.ajax({
				url: $(this.button).attr('data-source') + '?action=wr-install-update' + '&' + 'id=' + this.params.identified_name + '&' + 'edition=' + $('select', document.JSNUpgradeLogin).val(),
				context: this
			}).done(function(data) {
				this.clearTimer('#jsn-upgrade-installing-indicator');

				if (!data.match(/^(DONE|FAIL):?/)) {
					if (data = data.match(/(DONE|FAIL)([^\r\n]*)$/)) {
						data = data[0];
					}
				}

				if (data.substr(0, 4) == 'DONE') {
					// Update indicators
					$('#jsn-upgrade-installing-indicator').removeClass('jsn-icon-loading').addClass('jsn-icon-ok');

					// State that installation is completed successfully
					$('#jsn-upgrade-successfully').show();
				} else {
					// Update indicators
					$('#jsn-upgrade-installing-indicator').removeClass('jsn-icon-loading').addClass('jsn-icon-remove');

					// Displaying any error/warning message
					if (data.substr(0, 4) == 'FAIL') {
						$('#jsn-upgrade-installing-unsuccessful-message').html(data.replace(/^FAIL:(\s+)?/, '')).show();
					} else {
						$('#jsn-upgrade-installing-warnings').append(data).show();
					}
				}
			});

			this.setTimer('#jsn-upgrade-installing-indicator');
		},

		setTimer: function(element) {
			// Schedule still loading notice
			this.timer = setInterval($.proxy(function() {
				if ($(element).hasClass('jsn-icon-loading')) {
					var msg = $(element).next('.jsn-processing-message').html();
					if (msg == this.lang['STILL_WORKING']) {
						$(element).next('.jsn-processing-message').html(this.lang['PLEASE_WAIT']);
					} else {
						$(element).next('.jsn-processing-message').html(this.lang['STILL_WORKING']);
					}
				}
			}, this), 3000);
		},

		clearTimer: function(element) {
			clearInterval(this.timer);
			$(element).next('.jsn-processing-message').hide();
		}
	};
})(jQuery);
