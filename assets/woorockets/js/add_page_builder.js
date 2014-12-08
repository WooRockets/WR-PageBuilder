/**
 * Add a new tab besides Visual, Text tab of content editor
 */
jQuery(function($) {
	// Move WR PageBuilder box to content of "Page Builder" tab
	$('#wr_page_builder')
		.insertAfter('#wr_before_pagebuilder')
		.addClass('jsn-bootstrap3')
		.removeClass('postbox')
		.find('.handlediv').remove()
		.end()
		.find('.hndle').remove();

	$("#wr_editor_tab2").append($("<div />").append($('#wr_page_builder').clone()).html());

	// Remove WR PageBuilder metabox
	$('#wr_page_builder').remove();

	// Show WR PageBuilder only when Click "Page Builder" tab
	$(document).ready(function() {
		$('#wr_page_builder').show();

		// Switch between "Classic Editor" & "Page Builder"
		$('#wr_editor_tabs a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
			$("#wr_active_tab").val($('#wr_editor_tabs a').index($(this)));
		})

		$(".wr-editor-wrapper").show();

		// Show WR PageBuilder if tab "Page Builder" is active
		if ($("#wr_active_tab").val() == "1") {
			$('#wr_editor_tabs a').eq('1').trigger('click',[true]);
		}

		// Hide WR PageBuilder UI if deactivated on this page
		if ($("#wr_deactivate_pb").val() == "1") {
			$(".switchmode-button[id='status-off']").trigger('click', [true]);
		} else {
			$(".switchmode-button[id='status-on']").addClass('btn-success');
		}

		// Preview Changes fix
		$('#preview-action').css('position', 'relative');

		// Add a overlay div of "Preview Changes" button
		$('<div />', {'id' : 'wr-preview-overlay'}).css({'position':'absolute', 'width' : '100%', 'height' : '24px'}).hide().appendTo($('#preview-action'));

		$('.wr-pb-form-container').bind('wr-pagebuilder-layout-changed', function() {
			// Prevent click "Preview Changes" button
			$('#wr-preview-overlay').show();
			$('#post-preview').attr('disabled', true);

			_update_content(function() {
				// Active "Preview Changes" button
				$('#wr-preview-overlay').hide();
				$('#post-preview').removeAttr('disabled');
			});

			function _update_content(callback) {
				// if this change doesn't come from Classic Editor tab
				if (!$('#TB_window #wr-shortcodes').is(':visible')) {

					// get current WR PageBuilder content
					var tab_content = '';

					$(".wr-pb-form-container textarea[name^='shortcode_content']").each(function() {
						tab_content += $(this).val();
					});

					// update content of WP editor
					if (tinymce.activeEditor) {
						if (tinymce.activeEditor.id == 'content') {
							tinymce.activeEditor.setContent(tab_content);
						}
					}

					$("#wr_editor_tab1 #content").val(tab_content);

					if (callback) {
						callback();
					}
				} else {
					if (callback) {
						callback();
					}
				}
			}
		});
	});

	/**
	 * outerHTML() plugin for jQuery.
	 *
	 * @return  string
	 */
	$.fn.outerHTML = function() {
		// IE, Chrome & Safari will comply with the non-standard outerHTML, all others (FF) will have a fall-back for cloning
		return (!this.length) ? this : (this[0].outerHTML || (
			function(el) {
				var div = document.createElement('div');
				div.appendChild(el.cloneNode(true));
				var contents = div.innerHTML;
				div = null;
				return contents;
		})(this[0]));
	};
});

// Add shortcode from Classic Editor
top.addInClassic = 0;
