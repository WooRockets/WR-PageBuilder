/**
 * @version    $Id$
 * @package    WR_Library
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2014 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */

(function($) {
	$.WR_Form = function(params) {
		var self = this;

		// Initialize parameters
		self.params = $.extend({
			form_id: '',
			mask_required: ''
		}, params);

		// Get associated form
		self.form = $('#' + self.params.form_id);

		$(window).load($.proxy(self.init, self));
	};

	$.WR_Form.prototype = {
		init: function(event, section) {
			var self = this;

			if (!section) {
				section = self.form;
			}

			// Get form elements
			self.tabs = section.find('ul.nav.nav-tabs a[data-toggle="tab"]');
			self.accordions = section.find('div[id^="wr-form-accordion-"]');

			// Init tool-tips if requested
			if (self.params.tips) {
				// Init tool-tips
				section.find('[data-toggle="tooltip"]').tooltip({
					placement: 'right',
					container: '.jsn-bootstrap3',
				});
			}

			// Init accordions if requested
			if (self.params.accordions && self.accordions.length) {
				// Check if form contains any accordion toggler?
				section.find('.wr-form-fieldset-accordion-toggler').each(function(i, e) {
					var accordion = $(e).closest('fieldset').find('div[id^="wr-form-accordion-"]');

					if (accordion.length) {
						$(e).children('.collapse-all').unbind('click').bind('click', function(event) {
							event.preventDefault();
							self.toggle_accordion(accordion);
						});

						$(e).children('.expand-all').unbind('click').bind('click', function(event) {
							event.preventDefault();
							self.toggle_accordion(accordion, true);
						});
					}
				});
			}

			// Init tabs inside form if requested
			if (self.params.tabs && self.tabs.length) {
				if (self.params.ajax) {
					// Handle tab show event to load content for tab being shown
					self.tabs.on('show.bs.tab', function(event) {
						var newPanel = $($(event.target).attr('href'));

						if (newPanel.html() < 10 && $.trim(newPanel.html()) == '') {
							// Load content for form section
							var	link = window.location.href.replace(/\?section=[0-9a-zA-Z\-_]+/, '').replace('&ajax=1', ''),
								section = newTab.children('a').attr('href').replace('#wr-form-section-', '');

							// Set loading state for tab panel
							newPanel.addClass('wr-loading');

							// Get content for new panel
							$.ajax({
								url: link + '?section=' + section + '&ajax=1',
								context: this,
								complete: function(jqXHR, textStatus) {
									if (textStatus == 'success') {
										var content = jqXHR.responseText.split(new RegExp('<div id="wr-form-section-' + section + '"[^>]*>'));

										// Finalize content then append to the newly created panel
										if (content.length > 1) {
											var sess = Math.random();

											// Prepare content
											content = content[1].replace(/<\/div>[\s\t\r\n]*$/, '');
											content = content.replace(/\$\(document\)\.ready\(/g, '$(document).on("wr-document-ready-' + sess + '", ');
											content = content.replace(/\$\(window\)\.load\(/g, '$(window).on("wr-window-load-' + sess + '", ');

											newPanel.removeClass('wr-loading').html(content);
											delete content;

											// Trigger necessary events
											$(document).trigger('wr-document-ready-' + sess);
											$(window).trigger('wr-window-load-' + sess);
										}

										// Re-initialize form
										self.init(null, newPanel);

										// Re-trigger tab activate event
										$(event.target).data('uiTabs')._trigger('activate', event, ui);
									} else {
										// Re-load the page using link to specified section
										window.location.href = link + '?section=' + section;

										window.location.reload();
									}
								}
							});
						}
					});
				}
			}

			// Check if form contains any form/fieldset state switcher?
			section.find('.wr-form-state-switcher, .wr-form-fieldset-state-switcher').each(function(i, e) {
				$(e).children('.turn-on').unbind('click').bind('click', function(event) {
					var fieldset = $(event.target).parent().hasClass('wr-form-fieldset-state-switcher') ? $(event.target).closest('fieldset') : section;

					event.preventDefault();

					self.toggle_state(fieldset, $(event.target));
				});

				$(e).children('.turn-off').unbind('click').bind('click', function(event) {
					var fieldset = $(event.target).parent().hasClass('wr-form-fieldset-state-switcher') ? $(event.target).closest('fieldset') : section;

					event.preventDefault();

					self.toggle_state(fieldset, $(event.target));
				});

				$(e).children('.disabled, .hide, .hidden').trigger('click');
			});
		},

		toggle_accordion: function(accordion, expand) {
			accordion.find('.panel-collapse').each(function(i, e) {
				var toggler = $(e).prev('.panel-heading').find('a[data-toggle="collapse"]');

				if (expand && !$(e).hasClass('in')) {
					toggler.removeClass('collapsed');
					$(e).addClass('in').css('height', 'auto');
				} else if (!expand && $(e).hasClass('in')) {
					toggler.addClass('collapsed');
					$(e).removeClass('in').css('height', 0);
				}
			});
		},

		toggle_state: function(fieldset, clicked_element) {
			var self = this, enable = clicked_element.hasClass('turn-on');

			if (enable) {
				// Enable all fields belong to this fieldset
				fieldset.find('input[class!="wr-form-state"], select, textarea, button').each(function(i, e) {
					$(e).removeAttr('disabled');

					if ($(e).hasClass('chosen-box')) {
						$(e).trigger('liszt:updated');
					}
				});

				// Enable all button links
				fieldset.find('a.btn').each(function(i, e) {
					if (
						!$(e).parent().hasClass('wr-form-state-switcher')
						&&
						!$(e).parent().hasClass('wr-form-fieldset-state-switcher')
						&&
						!$(e).parent().hasClass('wr-form-fieldset-accordion-toggler')
					) {
						$(e).removeClass('disabled');
					}
				});

				// Toggle button state
				if (clicked_element.next().hasClass('disabled')) {
					clicked_element.addClass('btn-success disabled').next().removeClass('btn-danger disabled');
				} else if (clicked_element.next().hasClass('hide hidden')) {
					clicked_element.addClass('btn-success hide hidden').next().removeClass('btn-danger hide hidden');
				}

				// Remove mask elements
				fieldset.find(self.params.mask_required).css('position', '').children('.wr-form-disabled-mask').remove();
			} else {
				// Disable all fields belong to this fieldset
				fieldset.find('input[class!="wr-form-state"], select, textarea, button').each(function(i, e) {
					$(e).attr('disabled', 'disabled');

					if ($(e).hasClass('chosen-box')) {
						$(e).trigger('liszt:updated');
					}
				});

				// Disable all button links
				fieldset.find('a.btn').each(function(i, e) {
					if (
						!$(e).parent().hasClass('wr-form-state-switcher')
						&&
						!$(e).parent().hasClass('wr-form-fieldset-state-switcher')
						&&
						!$(e).parent().hasClass('wr-form-fieldset-accordion-toggler')
					) {
						$(e).addClass('disabled');
					}
				});

				// Toggle button state
				if (clicked_element.prev().hasClass('disabled')) {
					clicked_element.addClass('btn-danger disabled').prev().removeClass('btn-success disabled');
				} else if (clicked_element.prev().hasClass('hide hidden')) {
					clicked_element.addClass('btn-danger hide hidden').prev().removeClass('btn-success hide hidden');
				}

				// Append mask elements
				fieldset.find(self.params.mask_required).css('position', 'relative').append($('<div class="wr-form-disabled-mask" />'));
			}

			if (clicked_element.parent().hasClass('wr-form-fieldset-state-switcher')) {
				// Update element contains fieldset state
				fieldset.find('input.wr-form-state').val(enable ? 1 : 0).trigger('change');
			} else {
				// Update element contains form state
				clicked_element.parent().prev().val(enable ? 1 : 0).trigger('change');
			}
		}
	};
})(jQuery);
