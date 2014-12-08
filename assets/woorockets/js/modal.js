/**
 * @version	$Id$
 * @package	IGPGBLDR
 * @author	 WooRockets Team <support@www.woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.woorockets.com
 * Technical Support: Feedback - http://www.www.woorockets.com/contact-us/get-support.html
 */
(function($) {
	"use strict";

	// Do not re-declare modal class
	if (typeof $.IGModal != 'undefined') {
		return;
	}

	var wr_pb_scrollTop = 0;

	$.IGModal = function(options) {
		this.options = $.extend({
			iframe: true,
			frameId: '',
			jParent : $,
			dialogClass: 'wr-dialog',
			url: 'about:blank',
			scrollable: false,
			width: 500,
			height: 300,
			modal: false,
			resizable: false,
			draggable: false,
			loaded: null,
			context: null,
			filter: null
		}, options);

		this.extendedOptions = ['iframe', 'scrollable', 'context', 'frameId', 'url'];

		this.overlay = this.options.jParent('.jsn-modal-overlay');

		if (this.overlay.size() == 0) {
			this.overlay = $('<div/>', { 'class': 'jsn-modal-overlay' });
		}

		this.indicator = this.options.jParent('.jsn-modal-indicator');

		if (this.indicator.size() == 0) {
			this.indicator = $('<div/>', { 'class': 'jsn-modal-indicator' });
		}

		this.container = this.options.jParent('<div/>', { 'class': 'jsn-modal' });

		this.body = this.options.jParent('body').append(this.overlay).append(this.indicator).append(this.container);

		if (this.options.iframe) {
			this.iframe = this.options.jParent('<iframe/>', {'src' : 'about:blank', 'scrolling': this.options.scrollable == false ? 'no' : 'yes', 'frameborder': '0' });
			this.iframe.appendTo(this.container);

			if (this.options.frameId != '') {
				this.iframe.attr('id', this.options.frameId);
			}

			this.iframe.css({
				width: '100%',
				height: '100%'
			});

			this.iframe.load(function (){
				if (options.filter){
					var search   = $('<input type="text"/>');
					var searchWrapper = $('<span class="jsn-bootstrap3 ui-window-searchbar-wrapper"/>');

					searchWrapper.appendTo($('.ui-dialog-titlebar'));
					search.appendTo(searchWrapper);

					var opSearch = $.extend({
						text: '',
						css: {},
						classSet: '',
						attrs: {},
						onChange: undefined,
						onKeyup: undefined,
						onKeydown: undefined,
						onBlur: undefined,
						onFocus: undefined,
						onClick: undefined,
						ondblClick: undefined,
						onKeypress: undefined,
						closeTextKeyword: false,
						defaultText: '',
						afterAddTextCloseSearch: function(obj) {},
						closeTextClick: function(obj, searchbox) {}
					}, options.filter);

					search.val(opSearch.text);
					search.css(opSearch.css);
					search.addClass(opSearch.classSet);

					for(name in opSearch.attrs){
						search.attr(name, opSearch.attrs[name]);
					}

					search.change(opSearch.onChange);
					search.keyup(opSearch.onKeyup);
					search.keydown(opSearch.onKeydown);
					search.blur(opSearch.onBlur);
					search.focus(opSearch.onFocus);
					search.click(opSearch.onClick);
					search.dblclick(opSearch.ondblClick);
					search.keypress(opSearch.onKeypress);

					search.mouseup(function (){
						search.focus();
					});

					if (opSearch.closeTextKeyword) {
						var closeTextKeyword = $('<a />', {
							'class': 'ui-window-closetext-keyword',
							'id': 'ui-window-closetext-keyword',
							'href': 'javascript:void(0);'
						}).click(function() {
							opSearch.closeTextClick($(this), search);
						});

						opSearch.afterAddTextCloseSearch(closeTextKeyword);

						search.after(closeTextKeyword);

						search.change(function() {
							if ($(this).val().trim() == opSearch.defaultText || $(this).val().trim() == '') {
								closeTextKeyword.hide();
							} else {
								closeTextKeyword.show();
							}
						});
					}
				}
			});
		} else if (this.options.scrollable) {
			this.container.css('overflow', 'auto');
		}

		// Initial modal box
		this.dialog = this.container.dialog($.extend({autoOpen: false}, this.options));

		// Register all needed events
		this.registerEvents();
	};

	$.IGModal.prototype = {
		/**
		 * Register events for all controls
		 * @return void
		 */
		registerEvents: function () {
			if (this.options.iframe) {
				// Frame loaded event
				this.iframe.load({modal: this}, this.onIFrameLoaded);
			}

			// Modal events
			this.container.bind('dialogclose', {modal: this}, this.onDialogClosed);
		},

		/**
		 * Method to display modal box on screen
		 * @return void
		 */
		show: function (callback) {
			wr_pb_scrollTop = $(window).scrollTop();

			this.overlay.css('z-index', 1000).show();

			this.indicator.show();

			if (this.options.iframe) {
				this.iframe.attr('src', this.options.url);
			}

			if (callback) {
				callback(this);
			}
		},

		/**
		 * Set size for opened modal window
		 * @param int width
		 * @param int height
		 * @return void
		 */
		setSize: function (width, height) {
			this.container.dialog('option', 'width', width);
			this.container.dialog('option', 'height', height);
			this.container.dialog('option', 'position', 'center');
		},

		/**
		 * This method use to change src property of the iframe
		 * @param string url
		 * @param function callback
		 * @return void
		 */
		navigateTo: function (url, callback) {
			if (callback !== undefined && $.isFunction(callback)) {
				this.options.loaded = callback;
			}

			this.container.parent().addClass('jsn-loading');

			if (this.options.iframe) {
				this.iframe.attr('src', url);
			}
		},

		/**
		 * Add buttons to existing modal window
		 * @param mixed buttons
		 * @return void
		 */
		addButtons: function (buttons) {
			this.container.dialog('option', 'buttons', buttons);
		},

		/**
		 * Set option for the modal window
		 * @param name
		 * @param value
		 * @return void
		 */
		setOption: function (name, value) {
			if (this.extendedOptions.indexOf(name) != -1) {
				this.options[name] = value;
				this.update();
			}
			else {
				this.container.dialog('option', name, value);
			}
		},

		/**
		 * Refresh UI for the modal window
		 * @return void
		 */
		update: function () {
			if (this.options.iframe) {
				(this.options.scrollable !== undefined && this.options.scrollable == true)
					? this.iframe.attr('scrolling', 'yes')
					: this.iframe.attr('scrolling', 'no');
			}
		},

		/**
		 * Submit a form inside iframe of the modal window
		 * @param selector
		 * @param params
		 * @return void
		 */
		submitForm: function (selector, params) {
			this.container.parent().addClass('jsn-loading');

			if (this.options.iframe) {
				this.frameDocument = this.iframe.contents();

				var form = $(selector, this.frameDocument), formAction = form.attr('action');

				if (params !== undefined && $.isPlainObject(params)) {
					$.map(params, function (value, key) {
						var input = form.find('[name="' + key + '"]');

						(input.size() > 0)
							? input.val(value)
							: formAction = formAction + (formAction.indexOf('?') !== -1 ? '&' : '?') + key + '=' + value;
					});
				}

				form.attr('action', formAction);
				form.submit();
			}
		},

		/**
		 * Closed the opened dialog
		 * @return void
		 */
		close: function () {
			this.container.dialog('close');
			$(window).scrollTop(wr_pb_scrollTop);
		},

		/**
		 * Scroll back to previous position
		 */
		scrollBack: function() {
			$(window).scrollTop(wr_pb_scrollTop);
		},

		/**
		 * Handle dialog close event to hide overlay layer
		 * @param Event e
		 * @return void
		 */
		onDialogClosed: function (e) {
			e.data.modal.overlay.hide();
		},

		/**
		 * Handle loaded event for iframe element
		 * @param Event e
		 * @return void
		 */
		onIFrameLoaded: function (e) {
			var modal = e.data.modal;

			// Remove loading class for modal window
			modal.container.parent().removeClass('jsn-loading');
			modal.indicator.hide();

			// Call loaded event
			if (modal.options.loaded !== undefined && $.isFunction(modal.options.loaded)) {
				modal.options.loaded(modal, this);
			}

			if(modal.options.fadeIn) {
				modal.container.dialog({show:function(){$(this).fadeIn(modal.options.fadeIn)}});
			}

			modal.container.dialog('open').dialog('moveToTop');
		}
	};
})(jQuery);
