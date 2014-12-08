/**
 * @version    $Id$
 * @package    IG_Library
 * @author     InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2014 InnoGears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 */

(function($) {
	$.IG_ProductAddons = function(params) {
		// Object parameters
		this.params = $.extend({
			base_url: '',
			core_plugin: '',
			has_saved_account: false,
			language: {
				CANCEL: 'Cancel',
				INSTALL: 'Install',
				UNINSTALL: 'Uninstall',
				INSTALLED: 'Installed',
				INCOMPATIBLE: 'Incompatible',
				UNINSTALL_CONFIRM: 'Are you sure you want to uninstall %s?',
				AUTHENTICATING: 'Verifying...',
				INSTALLING: 'Installing...',
				UPDATING: 'Updating...',
				UNINSTALLING: 'Uninstalling...',
			}
		}, params);

		// Shorten access to language object
		this.lang = this.params.language || {};

		// Initialize InnoGears addons management
		$(document).ready($.proxy(this.init, this));
	};

	$.IG_ProductAddons.prototype = {
		init: function() {
			var self = this;

			// Get necessary elements
			self.container = $('#' + self.params.core_plugin + '-addons');
			self.authentication = $('#' + self.params.core_plugin + '-authentication');
			self.username = self.authentication.find('input#username');
			self.password = self.authentication.find('input#password');
			self.addons = self.container.children();

			// Setup action to install addons
			self.addons.find('a[data-action]').unbind('click').bind('click', function(event) {
				// Prevent default event handler
				event.preventDefault();

				// Simply return if button is disabled
				if ($(this).hasClass('disabled') || $(this).attr('disabled')) {
					return false;
				}

				// Prepare necessary data for addon installation
				var	action = $(this).attr('data-action'),
					identification = $(this).attr('data-identification'),
					authentication = parseInt($(this).attr('data-authentication'));

				// If authentication is required, show customer login box
				if (action != 'uninstall' && authentication) {
					return self.authenticate(action, identification, authentication);
				}

				// Ask user to confirm if he/she want to uninstall an addon
				if (action == 'uninstall' && !confirm(self.lang.UNINSTALL_CONFIRM.replace('%s', $(this).closest('.caption').children('h3').html()))) {
					return false;
				}

				// Manipulate requested addon
				self.request(action, identification, authentication);
			});
		},

		authenticate: function(action, identification, authentication) {
			var	self = this;

			// Check if user has customer account saved
			if (self.params.has_saved_account) {
				// Request server to authenticate customer account
				return self.request('authenticate', identification, authentication, action);
			}

			// Setup modal for customer login
			if (!self.modal_initialized) {
				self.authentication.modal({
					show: false,
				}).on('show.bs.modal', function() {
					// Get `Install` button
					var button = $(this).find('.btn-primary').addClass('disabled').attr('disabled', 'disabled');
	
					// Clear previous data
					$(this).find('.alert').addClass('hidden').children('.message').html('');
					$(this).find('#remember').removeAttr('checked');
					self.username.val('');
					self.password.val('');
	
					// Track input fields
					$(this).find('input[type!="checkbox"]').unbind('keyup').bind('keyup', function(event) {
						// Prevent default event handler
						event.preventDefault();
	
						// Verify that username and password are not empty
						if (self.username.val() != '' && self.password.val() != '') {
							button.removeClass('disabled').removeAttr('disabled');
	
							// Check whether to submit form
							if (event.keyCode == 13) {
								button.trigger('click');
							}
						} else {
							button.addClass('disabled').attr('disabled', 'disabled');
						}
					});
	
					// Setup buttons
					$(this).find('.btn').unbind('click').bind('click', function() {
						if ($(this).hasClass('btn-primary')) {
							// Make sure username and password are not empty
							if (self.username.val() != '' && self.password.val() != '') {
								// Request server to authenticate customer account
								self.request('authenticate', identification, authentication, action);
							}
						} else {
							// Close authentication modal
							self.authentication.modal('hide');
						}
					});
				});

				self.modal_initialized = true;
			}
			// Show modal for customer login
			self.authentication.modal('show');
		},

		request: function(action, identification, authentication, secondary_action) {
			var self = this;

			// Get addon to be manipulated
			var addon = self.addons.find('a[data-identification="' + identification + '"]');

			if (addon.length) {
				addon = addon.filter('a[data-action="' + (action != 'authenticate' ? action : secondary_action) + '"]');
			}

			// Show processing status
			if (action == 'authenticate' && !self.params.has_saved_account) {
				self.authentication.find('.btn-primary').addClass('ig-loading disabled').attr('disabled', 'disabled').html(self.lang.AUTHENTICATING);
			} else {
				// Toggle status
				switch (action) {
					case 'authenticate':
						addon.text(self.lang.AUTHENTICATING);
					break;

					case 'install':
						addon.text(self.lang.INSTALLING);
					break;

					case 'update':
						addon.text(self.lang.UPDATING);
					break;

					case 'uninstall':
						addon.text(self.lang.UNINSTALLING);
					break;
				}

				addon.addClass('ig-loading').parent().children('a[data-action]').addClass('disabled').attr('disabled', 'disabled');
			}

			// Request server-side to do addon management action
			$.ajax({
				url: self.params.base_url + '&do=' + action + '&core=' + self.params.core_plugin + '&addon=' + identification + '&authentication=' + authentication,
				type: (authentication && !self.params.has_saved_account) ? 'POST' : 'GET',
				data: (authentication && !self.params.has_saved_account) ? self.authentication.find('form').serialize() : '',
			}).done(function(data) {
				// Get add-on name
				var addon_name = addon.closest('.caption').children('h3').html();

				// Parse response data
				if (data.indexOf('{') > -1) {
					data = $.parseJSON(data.substr(data.indexOf('{')));
				} else {
					data = {sucess: false, message: data};
				}

				if (data.success) {
					// Hide processing status
					if (action == 'authenticate') {
						// Close authentication modal if necessary
						if (!self.params.has_saved_account) {
							self.authentication.modal('hide');

							if (self.authentication.find('#remember').attr('checked')) {
								// We have customer account in server-side now
								self.params.has_saved_account = true;
							}
						}

						// Clear status
						addon.removeClass('ig-loading');

						// Execute secondary action
						self.request(secondary_action, identification, authentication);
					} else {
						// Toggle status
						addon.removeClass('ig-loading').parent().children('a[data-action]').removeClass('disabled').removeAttr('disabled');

						switch (action) {
							case 'install':
								// Swap button function
								addon.attr('data-action', 'uninstall').html(self.lang.UNINSTALL);

								// Append `Installed` sticker
								addon.closest('li').append('<span class="label label-success">' + self.lang.INSTALLED + '</span>');
							break;

							case 'uninstall':
								// Swap button function
								addon.addClass('btn-primary').attr('data-action', 'install').html(self.lang.INSTALL);

								// Remove `Installed` sticker
								addon.closest('li').find('.label-success').remove();

								// Remove `Update` button
								addon.parent().children('a[data-action="update"]').remove();

								// Check if addon is compatible with current core
								if (addon.hasClass('incompatible')) {
									addon.removeClass('incompatible').addClass('disabled').attr('disabled', 'disabled');

									for (var i in {'action':'', 'authentication':'', 'identification':''}) {
										addon.removeAttr('data-' + i);
									}
								}
							break;

							case 'update':
								// Make `Uninstall` button primary
								addon.next().addClass('btn-primary');

								// Remove `Update` button
								addon.remove();
							break;
						}
					}

					// Show message if has any
					if (data.message) {
						alert(data.message.replace('%s', addon_name));
					}
				} else {
					// Toggle status
					addon.removeClass('ig-loading').parent().children('a[data-action]').removeClass('disabled').removeAttr('disabled');

					// Verify action
					if (action == 'authenticate') {
						// Check if user has customer account saved
						if (self.params.has_saved_account) {
							// Restore button label
							switch (secondary_action) {
								case 'install':
									addon.html(self.lang.INSTALL);
								break;

								case 'update':
									addon.html(self.lang.UPDATE);
								break;
							}

							// Reset saved customer account state
							self.params.has_saved_account = false;

							// Show authentication modal
							self.authenticate(secondary_action, identification, authentication);
						}

						// Show error message in authentication modal
						if (data.message) {
							self.authentication.find('.alert').removeClass('hidden').children('.message').html(data.message);
						}

						// Show authentication buttons
						self.authentication.find('.btn-primary').removeClass('ig-loading').html(self.lang.INSTALL);
					} else {
						// Disable button because add-on cannot be installed
						addon.addClass('disabled').attr('disabled', 'disabled');

						for (var i in {'action':'', 'authentication':'', 'identification':''}) {
							addon.removeAttr('data-' + i);
						}

						// Restore button label
						switch (action) {
							case 'install':
								addon.html(self.lang.INSTALL);
							break;

							case 'uninstall':
								addon.html(self.lang.UNINSTALL);
							break;

							case 'update':
								addon.html(self.lang.UPDATE);
							break;
						}

						// Append `Incompatible` sticker
						addon.closest('li').append('<span class="label label-danger">' + self.lang.INCOMPATIBLE + '</span>');

						// Show error message
						alert(data.message.replace('%s', addon_name));
					}
				}
			});
		},
	};
})(jQuery);
