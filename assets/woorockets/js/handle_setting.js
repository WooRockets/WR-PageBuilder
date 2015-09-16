/**
 * @version    $Id$
 * @package    IGPGBLDR
 * @author     WooRockets Team <support@www.woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.woorockets.com
 * Technical Support: Feedback - http://www.www.woorockets.com/contact-us/get-support.html
 */

(function($) {
	"use strict";

	$.HandleSetting 		= $.HandleSetting || {};
	$.IGModal				= $.IGModal || {};
	$.WR_ImageElement		= $.WR_ImageElement || {};
	$.WR_ImageElement.setImageSize	= $.WR_ImageElement.setImageSize || {};

    // Gradient picker for row
	$.HandleSetting.gradientPicker = function() {
		var gradientPicker = function() {
			var val = $('#param-background').val();

			if (val == 'gradient') {
				$("input.jsn-grad-ex").each(function(i, e) {
					$(e).next('.classy-gradient-box').first().ClassyGradient({
						gradient: $(e).val(),
						width : 218,
						orientation : $('#param-gradient_direction').val(),
						onChange: function(stringGradient, cssGradient, arrayGradient) {
							$(e).val() == stringGradient || $(e).val(stringGradient);

							$('#param-gradient_color_css').val(cssGradient);

							$.HandleSetting.shortcodePreview();
						}
					});
				});
			}
		};

		$(document).ready(function() {
			setTimeout(function() {
				gradientPicker();
			}, 500);
		});

		$('#param-background').change(function() {
			gradientPicker();
		});

		// Control orientation
		$('#param-gradient_direction').on('change', function() {
			var orientation = $(this).val();

			$('.classy-gradient-box').data('ClassyGradient').setOrientation(orientation);

			// Update gradient background
			if (orientation == 'horizontal') {
				$('#param-gradient_color_css').val($('#param-gradient_color_css').val().replace('left top, left bottom', 'left top, right top').replace(/\(top/g,'(left'));
			} else {
				$('#param-gradient_color_css').val($('#param-gradient_color_css').val().replace('left top, right top', 'left top, left bottom').replace(/\(left/g,'(top'));
			}
		});

	};

	// Check radio button when click button in btn-group
	$.HandleSetting.buttonGroup = function() {
		var data_value;

		$('.wr-btn-group .btn').click(function(i) {
			data_value = $(this).attr('data-value');

			$(this).parent().next('.wr-btn-radio').find('input:radio[value="'+data_value+'"]').prop('checked', true);

			$.HandleSetting.shortcodePreview();
		});
	};

	// Validate input of text field with regular expression
	$.HandleSetting.regexTestInput = function(event, regex_str) {
		var regex = new RegExp(regex_str), key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

		if (!regex.test(key) && key != ' ') {
			event.preventDefault();

			return false;
		}

		return true;
	};

	// Validator input field
	$.HandleSetting.inputValidator = function() {
		var input_action = 'change paste';

		// Disable special characters, limit length of title
		$("#param-el_title, input:text[name$='[title]'], [data-role='title'], .wr-pb-limit-length", '#modalOptions').each(function() {
			$(this).prop('maxlength', 50);
		});

		// Number field: allow typing number only
		$("#modalOptions input[type='number']").bind(input_action, function(event) {
			$.HandleSetting.regexTestInput(event, "^[0-9\b\-]+$");
		});

		// Doesn't allow 0 in items_per_ input field
		$("#modalOptions input[id*='items_per']").bind(input_action, function(event) {
			var regex = /^[1-9\b]+$/g, val = regex.test($(this).val());

			if (!val) {
				$(this).val('1');
			}
		});

		// Positive value
		$('.positive-val').bind(input_action, function(event) {
			var this_val = $(this).val();

			if (parseInt(this_val) <= 0) {
				$(this).val(1);
			}
		});
	};

	$.HandleSetting.selectImage = function() {
		var _custom_media = true;

		$('#modalOptions .select-media-remove').on('click', function() {
			var _input	= $(this).closest('div').find('input[type="text"]');

			_input.attr('value', '');
			_input.trigger('change');
		});

		$('body').on( 'click', '#modalOptions .select-media', function(e) {
			var	button = $(this),
				id = button.attr('id').replace('_button', ''),
				jqueryParent = window.parent.jQuery.noConflict(),
				filter_type = $(this).attr('filter_type'),
				object = {};

			if (typeof(filter_type) != undefined) {
				object.type = filter_type;
			} else {
				object.type = '';
			}

			jqueryParent('#wr-select-media').val(JSON.stringify(object));
			jqueryParent('#wr-select-media').trigger('change');

			var timer = setInterval(function() {
				var currentValue = jqueryParent('#wr-select-media').val();

				if (currentValue) {
					var jsonObject = JSON.parse( currentValue );

					switch ( jsonObject.type ) {
						case 'media_selected':
							if (typeof($.WR_ImageElement.setImageSize) == 'function') {
								$.WR_ImageElement.setImageSize(jsonObject.select_prop);
							}

							var input_img = button.prev("#"+id);
							input_img.val(jsonObject.select_url);
							input_img.trigger('change');

							clearInterval(timer);
						break;
					}
				}
			}, 500);
		});

		$('#modalOptions .add_media').on('click', function() {
			_custom_media = false;
		});
	};

	$.HandleSetting.updateState = function(state) {
		if (state != null) {
			$.HandleSetting.doing = state;
		} else {
			if ($.HandleSetting.doing == null || $.HandleSetting.doing) {
				$.HandleSetting.doing = 0;
			} else {
				$.HandleSetting.doing = 1;
			}
		}
	};

	$.HandleSetting.renderModal = function() {
		if ($( "#modalOptions" ).length == 0) {
			return false;
		}

		$("#modalOptions").modal('toggle');

		// Sortable sub-elements
		var group_elements = $("#group_elements");

		if (group_elements.length) {
			group_elements.sortable({
				handle: '.heading',
				sort: function( event, ui ) {
					var item = $(ui.item);
					item.css('height', '38px');
					item.children('.sub-element-settings').addClass('item-drag-hidden');

					group_elements.sortable( "refreshPositions" );
				},
				stop: function( event, ui ) {
					var item = $(ui.item);
					item.children('.sub-element-settings').removeClass('item-drag-hidden');
				},
				update: function( event, ui ) {
					$.HandleSetting.shortcodePreview();

					var item = $(ui.item);
					item.css('height', 'auto');
					item.children('.sub-element-settings').removeClass('item-drag-hidden');

					$('body').trigger('on_after_reorder_element');
				}
			});

			$('body').trigger('on_remove_handle_reorder');
		}

		$.HandleSetting.setColorPicker('#modalOptions .color-selector');
		$.HandleElement.initTooltip('#modalOptions [data-toggle="tooltip"]');

		$('body').trigger('toggleDependencyAll');

	};

	$.HandleSetting.setColorPicker = function(selector) {
		if (!selector) {
			return false;
		}

		$(selector).each(function() {
			var	self = $(this),
				colorInput = self.siblings('input').last(),
				inputId = colorInput.attr('id'),
				inputValue = inputId.replace(/_color/i, '') + '_value';

			if ($('#' + inputValue).length) {
				$('#' + inputValue).val($(colorInput).val());
			}

			self.ColorPicker({
				color: $(colorInput).val(),
				onShow: function(colpkr) {
					$(colpkr).fadeIn(500);

					return false;
				},
				onHide: function(colpkr) {
					$(colpkr).fadeOut(500);

					$.HandleSetting.shortcodePreview();

					return false;
				},
				onChange: function(hsb, hex, rgb) {
					$(colorInput).val('#' + hex);

					if ($('#' + inputValue).length) {
						$('#' + inputValue).val('#' + hex);
					}

					self.children().css('background-color', '#' + hex);
				}
			});
		});
	};

	$.HandleSetting.selector = function(curr_iframe, element) {
		var $selector = (curr_iframe != null &&  curr_iframe.contents() != null) ? curr_iframe.contents().find(element) : $(element);

		return $selector;
	};

	$.HandleSetting.shortcodePreview = function(params, shortcode, curr_iframe, callback, stop_reload_iframe, child_element, selector_group) {
		if (($.HandleSetting.selector(curr_iframe,"#modalOptions").length == 0 || $.HandleSetting.selector(curr_iframe,"#modalOptions").hasClass('submodal_frame')) && curr_iframe == null) {
			return true;
		}

		// Change state to ACTIVE
		$.HandleSetting.updateState(1);

		var tmp_content = '', shortcode_name, shortcode_type;

		if (params == null) {
			shortcode_name = $.HandleSetting.selector(curr_iframe, '#modalOptions #shortcode_name' ).val();
			shortcode_type = $.HandleSetting.selector(curr_iframe, '.wr-pb-form-container #shortcode_type' ).val();

			if (shortcode_type == 'widget') {
				// Widget
				var form_serialize = $.HandleSetting.selector(curr_iframe, '#modalOptions #wr-widget-form' ).serialize();

				$('input[type=checkbox]', '#modalOptions #wr-widget-form').each(function() {
					if (!this.checked) {
						form_serialize += '&'+this.name+'=0';
					}
				});

				tmp_content = '[wr_widget widget_id="'+shortcode_name+'"]' + form_serialize + '[/wr_widget]';
			} else {
				var data = $.HandleSetting.traverseParam( selector_group ? selector_group : $.HandleSetting.selector(curr_iframe, '#modalOptions .control-group' ), child_element );
                var sc_content = data.sc_content;
                var params_arr = data.params_arr;

				if ( child_element && $.HandleSetting.selector(curr_iframe, '.sub-element-settings:visible').length ) {
					var related_textarea = $.HandleSetting.selector(curr_iframe, '.sub-element-settings:visible').parent().children("[name^='shortcode_content']");

					var obj = {sc_content : sc_content, related_textarea : related_textarea};
					$('#modalOptions').trigger('update_sub_shortcode_content', [obj]);
					var tmp_content_ = $.HandleSetting.generateShortcodeContent(obj.related_textarea.attr('shortcode-name'), params_arr, obj.sc_content);
					related_textarea.text(tmp_content_).trigger('change');
					return false;
				}

				// Get tinymce content
				tinyMCE.triggerSave();
				$('.wr_pb_editor').each(function(){
					if( ! $(this).closest('.form-group').parent().hasClass('sub-element-settings') ) {
						var tinymce_content = $(this).val();
						sc_content += ( tinymce_content != null ) ? tinymce_content : '';

						return false;
					}
				});

				// Update TinyMCE content to #wr_share_data
				window.parent.jQuery.noConflict()('#jsn_view_modal').contents().find('#wr_share_data').text(sc_content);

				// For shortcode which has sub-shortcode
				if ($.HandleSetting.selector(curr_iframe,"#modalOptions").find('.has_submodal').length > 0 || $.HandleSetting.selector(curr_iframe, '#modalOptions').find('.submodal_frame_2').length > 0) {
					var sub_items_content = [];

					$.HandleSetting.selector(curr_iframe, "#modalOptions [name^='shortcode_content']").each(function() {

						var sc_content = $(this).text();
						var obj = {sc_content : sc_content};
						$('#modalOptions').trigger('wr_get_sub_sc', [obj]);
						if (obj.sc_content != '') {
							sub_items_content.push(obj.sc_content);
						}
					});
					sc_content += sub_items_content.join('');
				}

				tmp_content = $.HandleSetting.generateShortcodeContent(shortcode_name, params_arr, sc_content);
			}
		} else {
			shortcode_name = shortcode;
			tmp_content = params;
		}

		// step_to_track(5, tmp_content);
		// Update shortcode content
		$.HandleSetting.selector(curr_iframe, '#shortcode_content').text(tmp_content).trigger('change');

		if ( ! child_element ) {
			// Update content of Shortcode tab
			$('#shortcode_content', '#shortcode-content').text(tmp_content);
		} else {
			setTimeout( function() {
				// Rescan parent shortcode, to update content of "Shortcode" tab
				$.HandleElement.rescanShortcode();
			}, 300 );
		}

		if (callback) {
			callback();
		}

		// Change state to inactive
		$.HandleSetting.updateState(0);

		// Dont reload preview iframe
		if (stop_reload_iframe) {
			return false;
		}

		var url = Wr_Ajax.wr_modal_url + '&wr_shortcode_preview=1' + '&wr_shortcode_name=' + shortcode_name + '&wr_nonce_check=' + Wr_Ajax._nonce;

		if ($('#shortcode_preview_iframe').length > 0) {
			// Asign value to a variable (for show/hide preview)
			$.HandleSetting.previewData = {
				curr_iframe: curr_iframe,
				url : url,
				tmp_content: tmp_content
			};

			// Load preview iframe
			$.HandleSetting.loadIframe(curr_iframe, url, tmp_content);
		}

		return false;
	};

    /**
     * Traverse parameters, get theirs values
     */
    $.HandleSetting.traverseParam = function( $selector, child_element, get_data_tag ){
        var sc_content = '';
        var params_arr = {};

        $selector.each( function ()
        {
            if ( ! $(this).hasClass( 'wr_hidden_depend' ) )
            {
                $(this).find( '[id^="param-"]' ).each(function()
                {
                    // Bypass the Copy style group
                    if ( $(this).attr('id') == 'param-copy_style_from' ) {
                        return;
                    }

                    if(
						$(this).parents(".tmce-active").length == 0 && ! $(this).hasClass('tmce-active')
						&& $(this).parents(".html-active").length == 0 && ! $(this).hasClass('html-active')
						&& ! $(this).parents("[id^='parent-param']").hasClass( 'wr_hidden_depend' )
						&& ( child_element || ! $(this).closest('.form-group').parent().hasClass('sub-element-settings'))
						&& $(this).attr('id').indexOf('parent-') == -1
                    )
                    {
                        var id = $(this).attr('id');
                        if($(this).attr('data-role') == 'content'){
                            sc_content =  $(this).val();//.replace(/\[/g,"&#91;").replace(/\]/g,"&#93;");
                        }else{
                            if(($(this).is(":radio") || $(this).is(":checkbox")) && !$(this).is(":checked"));
                            else{
                                if(!params_arr[id.replace('param-','')] || id.replace('param-', '') == 'title_font_face_type' || id.replace('param-', '') == 'title_font_face_value' || id.replace('param-','') == 'font_face_type' || id.replace('param-','') == 'font_face_value' || id.replace('param-', '') == 'image_type_post' || id.replace('param-', '') == 'image_type_page' || id.replace('param-', '') == 'image_type_category' ) {
                                    params_arr[id.replace('param-','')] = $(this).val();
                                } else {
                                    params_arr[id.replace('param-','')] += '__#__' + $(this).val();
                                }

                            }
                        }

                        if( get_data_tag == null || get_data_tag ) {
                            // data-share
                            if($(this).attr('data-share')){
                                var share_element = $('#' + $(this).attr('data-share'));
                                var share_data = share_element.text();
                                if(share_data == "" || share_data == null)
                                    share_element.text($(this).val());
                                else{
                                    share_element.text(share_data + ',' + $(this).val());
                                    var arr = share_element.text().split(',');
                                    $.unique( arr );
                                    share_element.text(arr.join(','));
                                }

                            }

                            // data-merge
                            if($(this).parent().hasClass('merge-data')){
                                var wr_merge_data = window.parent.jQuery.noConflict()( '#jsn_view_modal').contents().find('#wr_merge_data');
                                wr_merge_data.text(wr_merge_data.text() + $(this).val());
                            }

                            // table
                            if($(this).attr("data-role") == "extract"){
                                var extract_holder = window.parent.jQuery.noConflict()( '#jsn_view_modal').contents().find('#wr_extract_data');
                                extract_holder.text(extract_holder.text()+$(this).attr("id")+':'+$(this).val()+'#');
                            }
                        }
                    }

                });
            }
        });

        return { sc_content : sc_content, params_arr : params_arr };
    }

    /**
     * Generate shortcode content
     */
    $.HandleSetting.generateShortcodeContent = function(shortcode_name, params_arr, sc_content){
        var tmp_content = [];

        tmp_content.push('['+ shortcode_name);
        // wrap key, value of params to this format: key = "value"
        $.each(params_arr, function(key, value){
            if ( value ) {
                if ( value instanceof Array ) {
                    value = value.toString();
                }
                tmp_content.push(key + '="' + value.replace(/\"/g,"&quot;").replace(/\[/g,"").replace(/\]/g,"") + '"');

            }
        });
		// step_to_track(6,tmp_content);
        tmp_content.push(']' + sc_content + '[/' + shortcode_name + ']');
        tmp_content	= tmp_content.join( ' ' );

        return tmp_content;
    }
    
	// Load preview iframe
	$.HandleSetting.loadIframe = function(curr_iframe, url, tmp_content) {
		$('#wr_preview_data').remove();

		var tmp_form = $(
			'<form action="' + url + '" id="wr_preview_data" name="wr_preview_data" method="post" target="shortcode_preview_iframe">' +
				'<input type="hidden" id="wr_preview_params" name="params" value="' + encodeURIComponent(tmp_content) + '">' +
			'</form>'
		).appendTo($('body'));

		$.HandleSetting.selector(curr_iframe, '#modalOptions #wr_overlay_loading').fadeIn('fast');

		$('#wr_preview_data').submit();

		$('#modalOptions #shortcode_preview_iframe').bind('load', function() {
			$('#modalOptions #shortcode_preview_iframe').fadeIn('fast');

			$.HandleSetting.selector(curr_iframe, '#modalOptions #wr_overlay_loading').fadeOut('fast');

			$('#wr_previewing').val('0');

			// Set global variable when iframe has loaded
			iframe_load_completed = true;
			//console.log(iframe_load_completed)
		});

		tmp_form.remove();
	};

	// Hide/show preview
	$.HandleSetting.togglePreview = function() {
		/*$('#previewToggle *').click(function() {
			if ($(this).attr('id') == 'hide_preview') {
				$(this).addClass('hidden');
				$('#show_preview').removeClass('hidden');

				// Remove iframe
				$('#preview_container iframe').remove();
			} else {
				$(this).addClass('hidden');
				$('#hide_preview').removeClass('hidden');

				$('#preview_container').append(
					"<iframe scrolling='no' id='shortcode_preview_iframe' name='shortcode_preview_iframe' class='shortcode_preview_iframe'></iframe>"
				);

				if ($.HandleSetting.previewData != null) {
					var data = $.HandleSetting.previewData;

					$.HandleSetting.loadIframe(data.curr_iframe, data.url, data.tmp_content);
				}
			}
		});*/
	};

	$.HandleSetting.toggleDependencyAll = function() {
		// Toggle dependency params
		var wr_HasDepend = $( '#modalOptions .wr_has_depend' );

		wr_HasDepend.each(function() {
			if (($(this).is(":radio") || $(this).is(":checkbox")) && !$(this).is(":checked")) {
				return;
			}

			var this_id  = $(this).attr('id'), this_val  = $(this).val();
			$.HandleSetting.toggleDependency(this_id, this_val);
		});
	}

	// Show/hide dependency params
	$.HandleSetting.toggleDependency = function(this_id, this_val) {
		if (!this_id) {
			return;
		}

		$('#modalOptions .wr_depend_other[data-depend-element="' + this_id + '"]').each(function() {
			var operator = $(this).attr('data-depend-operator'), compare_value = $(this).attr('data-depend-value');

			switch(operator) {
				case "=":
					var check_ = 0;

					if (compare_value.indexOf('__#__') > 0) {
						var values_ = compare_value.split('__#__');

						check_ = ($.inArray(this_val, values_) >= 0);
					} else {
						check_  = (this_val == compare_value);
					}
					if (check_) {
						$(this).removeClass('wr_hidden_depend');
					} else {
						$(this).addClass( 'wr_hidden_depend' );
					}
				break;

				case ">":
					if (this_val > compare_value) {
						$(this).removeClass('wr_hidden_depend');
					} else {
						$(this).addClass('wr_hidden_depend');
					}
				break;

				case "<":
					if (this_val < compare_value) {
						$(this).removeClass('wr_hidden_depend');
					} else {
						$(this).addClass('wr_hidden_depend');
					}
				break;

				case "!=":
					if (this_val != compare_value) {
						$(this).removeClass('wr_hidden_depend');
					} else {
						$(this).addClass('wr_hidden_depend');
					}
				break;
			}

			$.HandleSetting.secondDependency($(this).attr('id'), $(this).hasClass('wr_hidden_depend'), $(this).find('select').hasClass('no_plus_depend'));
		});
	};

	// Show/hide 2rd level dependency elements
	$.HandleSetting.secondDependency = function(this_id, hidden, allow) {
		if (!this_id) {
			return;
		}

		this_id = this_id.replace('parent-','');

		$('#modalOptions .wr_depend_other[data-depend-element="'+this_id+'"]').each(function() {
			if (hidden) {
				$(this).addClass('wr_hidden_depend2');
			} else {
				$(this).removeClass('wr_hidden_depend2');
			}
		});

		if (!allow) {
			$('#modalOptions .wr_depend_other[data-depend-element="'+this_id+'"]').each(function() {
				$(this).removeClass('wr_hidden_depend2');
			});
		}

		// Hide label if all options in .controls div have 'wr_hidden_depend' class
		$('#modalOptions .controls').each(function() {
			var hidden_div = 0;

			$(this).children().each(function() {
				if ($(this).hasClass('wr_hidden_depend')) {
					hidden_div++;
				}
			});

			if (hidden_div > 0 && hidden_div == $(this).children().length) {
				$(this).parent('.control-group').addClass('margin0');
				$(this).prev('.control-label').hide();
			} else {
				$(this).parent('.control-group').removeClass('margin0');
				$(this).prev('.control-label').show();
			}
		});
	};

	// Set change event of dependency elements
	$.HandleSetting.changeDependency = function(dp_selector) {
		if (!dp_selector) {
			return false;
		}

		$('#modalOptions').delegate(dp_selector, 'change', function() {
			var this_id = $(this).attr('id'), this_val	= $(this).val();

			$.HandleSetting.toggleDependency(this_id, this_val);
		});
	};

	// Show tab in Modal Options
	$.HandleSetting.tab = function() {
		if ($('.jsn-tabs').length && !$('.jsn-tabs').find("#Notab").length) {
			$('.jsn-tabs').tabs();

			$('#wr_option_tab a[data-toggle="tab"]').on('click', function() {
				var href = $(this).attr('href');

				if (href == '#shortcode-content') {
					$('#shortcode_content').attr('disabled', 'disabled');
				} else {
					$('#shortcode_content').removeAttr('disabled');
				}
			});

			if ($('#wr_previewing').val() == '1') {
				return;
			}
			$('#wr_previewing').val('1');

			$('#shortcode_content').removeAttr('disabled');
		}

		return true;
	};

	$.HandleSetting.select2 = function() {
		$(".select2").each(function() {
			var share_element = window.parent.jQuery.noConflict()( '#jsn_view_modal').contents().find('#' + $(this).attr('data-share')), share_data = [];

			if (share_element && share_element.text() != "") {
				share_data = share_element.text().split(',');
				share_data = $.unique(share_data);
			}

			$(this).css('width','300px');

			$(this).select2({
				tags: share_data,
				maximumInputLength: 10
			});
		});

		$('.select2-select').each(function() {
			if ( $(this).is( 'select' ) ) {
				var id = $(this).attr('id');

				if ($('#' + id + '_select_multi').val()) {
					var arr_select_multi = $('#' + id + '_select_multi').val().split('__#__');

					$(this).val(arr_select_multi).select2({
						minimumResultsForSearch: -1
					});
				} else {
					var parent_selector = '';
					parent_selector = $(this).parent('div[id^="parent-param-"]');
					// Only show select2 search textbox for post and page listing
					if ( $(parent_selector).attr('data-depend-value') == 'post' || $(parent_selector).attr('data-depend-value') == 'page' ) {
						$(this).select2();
					} else {
						$(this).select2({
							minimumResultsForSearch: -1
						});
					}
				}
			}
		});

		$.HandleSetting.select2_color();
	};

	$.HandleSetting.select2_color = function() {
		function format(state) {
			if (!state.id) {
				// optgroup
				return state.text;
			}

			var type = state.id.toLowerCase().split('-');

			type = type[type.length - 1];

			return "<img class='color_select2_item' src='" + Wr_Translate.asset_url + "images/icons-16/btn-color/" + type + ".png'/>" + state.text;
		}

		$('.color_select2').not('.hidden').each(function() {
			$(this).find('select').each(function() {
				$(this).select2({
					minimumResultsForSearch: -1,
					formatResult: format,
					formatSelection: format,
					escapeMarkup: function(m) {
						return m;
					}
				});
			});
		});
	};

	// Handle icon change action in Modal box
	$.HandleSetting.icons = function() {
		// Icon type: handle icon click
		$('body').on("click", ".wr-pb-form-container [data-type='wr-icon-item']", function() {
			$(".controls .icon-selected").each(function() {
				$(this).removeClass('icon-selected');
			});

			// Update selected icon
			var	$selected_icon = $(this).attr('class').replace('icon-selected', '').replace(' ',''),
				icon = $(this).parent('li').parent('ul').next("input[id^='param']");

			icon.val($selected_icon);
			icon.trigger('change');

			$(this).addClass('icon-selected');
		});
	};

	// Handle click action on Button in Modal: Convert action/ Add Row, Column / ...
	$.HandleSetting.actionHandle = function() {
		// Handle Convert To ... button
		$('body').on("click", ".wr-pb-form-container .wr_action_btn a", function(e) {
			e.preventDefault();

			var action_type = $(this).attr('data-action-type'), relation = $(this).attr('data-action');

			if (action_type && relation) {

				// Mark the element to be converted
				$('.active-shortcode').addClass('wr_to_convert');

				// Save data to convert
				$.PbDoing.action_data = {'action': action_type, 'relation': relation};

				$('button#selected', '.wr-dialog').trigger('click');
			}
		});
	};

	$.HandleSetting.closeAllSelect2WhenClickModal = function () {
		$('.wr-dialog').on("click", function(e) {
    		var el = $(e.target);
    		if (el.parents(".select2-container").length == 0) {
    			$(".select2-dropdown-open").select2("close");
    		}
		});
	};

	// Handle Copy to Clipboard action
	$.HandleSetting.copyToClipboard = function() {
		var textarea = $("#shortcode_content"), button = $("#copy_to_clipboard"), text_change = button.data('textchange');

		if (textarea.length && button.length) {
			ZeroClipboard.config({ moviePath: Wr_Ajax.assets_url + '/assets/3rd-party/zeroclipboard/ZeroClipboard.swf' });

			var client = new ZeroClipboard(button);

			client.on("datarequested", function() {
				// Copy shortcode content to clipboard
				client.setText(textarea.val());

				// Show message
				button.addClass("disabled").attr("disabled", "disabled");

				// get current text
				var cache_text = button.text();

				// Change text button
				button.text(text_change);

				// Schedule hiding the message
				setTimeout(function() {
					button.removeClass("disabled").removeAttr("disabled");
					$("i", button).animate({"opacity": "0"}, 500, function () { $(this).hide(); });
					button.text(cache_text);
				}, 1000);
			});
		}
	};

	/**
	 * Copy Style from other same-type element
	 */
	$.HandleSetting.initCopyStyle = function () {
		if ($('#modalOptions #param-copy_style_from').length <= 0) {
			return;
		}
		var copy_style_from = $('#modalOptions #param-copy_style_from').select2({minimumResultsForSearch: -1});
		var shortcode_name   = $('#modalOptions #shortcode_name').val();

		if (copy_style_from.length > 0) {
			// Append Copy action button

			var copy_button     = $('<button/>', {'class': 'btn btn-default btn-sm',
													'id': 'copy_style_button',
													'html': Wr_Translate.take_style,
													'style': 'margin-left: 10px'
												});
			copy_style_from.after(copy_button);
		}
		copy_style_from.parents('.control-group').after($('<hr/>'));
		// Init action for copy style button
		copy_button.on('click', function () {
			var shortcode_content  = $('#shortcode_content').val();
			var new_style_content  = copy_style_from.val();
			var setting_form       = $('#frm_shortcode_settings');
			var loading;
			// Init loading overlay inside modal
			loading = $('<div/>');
			loading.append($('<div/>', {
				'class': 'jsn-modal-overlay',
				'style': 'z-index: 1000; display: block;'
			}))
			.append(
				$('<div/>', {
					'class': 'jsn-modal-indicator',
					'style': 'display:block'
				})
			);

			if (typeof(wr_pb_modal_ajax) != 'undefined') {
				if (wr_pb_modal_ajax == 1) {
					setting_form.parents('#modalOptions').css('visibility', 'hidden');
					setting_form.parents('.jsn-modal').append(loading);
				}
			}else{
				$('body').append(loading);
			}

			$.post(
					Wr_Ajax.ajaxurl,
					{
						action : 'merge_style_params',
						content: shortcode_content,
						new_style_content: new_style_content,
						shortcode_name: shortcode_name,
						wr_nonce_check : Wr_Ajax._nonce
					},
					function( data ) {
						$('#frm_shortcode_settings #hid-params').val(data);
							if (typeof(wr_pb_modal_ajax) != 'undefined') {
								if (wr_pb_modal_ajax == 1) {
									// If the modal was initted from ajax
									// then load the modal content by ajax
									$.ajax({
										url: Wr_Ajax.wr_modal_url + '&wr_modal_type=' + shortcode_name + '&form_only=1',
										data: setting_form.serializeArray(),
										type: 'POST',
										dataType: 'html',
										complete: function(data, status) {
											if (status == 'success') {
												setting_form.parents('.jsn-modal').html(data.responseText);
											}
										}
									});
								}
							}else{
								// If the modal was initted by iframe
								// just only pass data by submitting the form
								setting_form.submit();
							}

					}
				);
		});
		// Get the same type elements list.
		$.post(
			Wr_Ajax.ajaxurl,
			{
				action : 'get_same_elements',
				content : window.parent.jQuery.HandleElement.getContent(),
				shortcode_name: shortcode_name,
				wr_nonce_check : Wr_Ajax._nonce
			},
			function( data ) {
				if (data) {
					copy_style_from.html(data);
				}else{
					copy_style_from.parents('.control-group').hide();
					copy_style_from.parents('.control-group').next('hr').hide();
				}

			}
		);
	}

	// Function to handling resize modal setting
	$.HandleSetting.handleResizeModal = function () {
		if ( typeof ( $('.wr-setting-resize').resizable) == 'function' ) {
			$('.wr-setting-resize').resizable({
			    handles: 'e',
			    minWidth: 400,
			    start: $.proxy(function (event, ui) {
			    	$('.wr-preview-resize #wr_overlay_loading').show();
			    }, this),
				resize: $.proxy(function (event, ui) {
					var resize_handle_width =  ui.element.find('.ui-resizable-e').first().width() + 5;
					var thisWidth           = ui.element.width() + resize_handle_width;
					var remain_width        = $('#modalOptions').width() - thisWidth - 3;
					$('.wr-preview-resize').css('width', remain_width + 'px');
				}, this),
				stop: $.proxy(function (event, ui) {
					$('.wr-preview-resize #wr_overlay_loading').fadeOut();
				}),
			});
		}

		// Only set width default on main modal
		if ( $('.jsn-modal').last().attr('id') == 'jsn_view_modal') {
			setTimeout( function() {
				$('.wr-setting-resize').css( { 'width': '50%' } );
			}, 100 );
		}

		$('.jsn-modal').on('resize', function () {
			if ( $('.jsn-modal').last().attr('id') == 'jsn_view_modal') {
				if ( $('.wr-preview-resize').length ) {
					$('#jsn_view_modal').css({'overflow': 'hidden'});
				}
				var modal_width = $('#modalOptions').width();
				var panel_width = $('.wr-setting-resize').first().width();
				$('.wr-preview-resize').width( modal_width - panel_width - 18 );
				var modal_content_height = $('#jsn_view_modal').height();

				// Fixed size for preview/settings panels.
				$('#jsn_view_modal #modalOptions .wr-setting-resize .tab-pane').height( modal_content_height - 70 );
				$('#jsn_view_modal #shortcode_preview_iframe').height( modal_content_height - 60 );

				// Fixed size for widget settings/preview panel.
				$('#jsn_view_modal #modalOptions .jsn-tabs .tab-pane').not('#Notab').height( modal_content_height - 70 );
			}
		});
	}

	// Use bootstrap js instead of use bootstrap markup html.
	$.HandleSetting.useBootstrapJS = function () {
		// For dropdown
		$('.wr-dropdown-toggle').each(function () {
			$(this).on('click', function (e) {
				e.preventDefault();
				$(this).dropdown('toggle');
			});
		});
	}

	// Add tooltip for element settings
	$.HandleSetting.addTooltip = function () {
		if ( typeof ( $.fn.tooltip ) == 'function' ) {
			$('.wr-tooltip-toggle').tooltip({
				placement: 'bottom'
			});
			$('[data-original-title]').not('.wr-tooltip-toggle').tooltip({
				placement: 'bottom'
			});
		}
	}

	// Styling for border type
	$.HandleSetting.addStyleBorderType = function () {
		if ( typeof( $.fn.select2 ) == 'function' ) {
			var format = function ( state ) {
				if ( ! state.id ) return state.text;

				var border_type = state.id.toLowerCase();
				if ( ! border_type )
					border_type = 'solid';
				return state.text + "<div class='wr-border-line' style='border-top: 4px " + border_type + " #C3C3C3'></div>";
			};
			$('.wr-border-type').select2('destroy');
			$('.wr-border-type').select2({
				formatResult: format,
				formatSelection: format,
				escapeMarkup: function (m) { return m; }
			});
		}
	}

	// Styling for heading type
	$.HandleSetting.addStyleHeadingType = function () {
		if ( typeof( $.fn.select2 ) == 'function' ) {
			var format = function ( state ) {
				if ( ! state.id ) return state.text;

				var type = state.id.toLowerCase();
				if ( ! type )
					type = 'h1';
				return "<" + type + " style='margin: 0px'>" + type + " example</" + type + ">";
			};
			$('.wr-heading-type:last').select2('destroy').select2({
				formatResult: format,
				//formatSelection: format,
				escapeMarkup: function (m) { return m; }
			});
		}
	}

	$.HandleSetting.addSelectEditor = function () {
		if ( typeof( $.fn.select2 ) == 'function' ) {

			var onSelectionItem = function ( current_id ) {
				var new_term = $('.select2-results:last .wr-new-term').text();
			  	$('<option value="' + new_term + '">' + new_term + '</option>').appendTo( '#' + current_id );
			    $( '#' + current_id ).select2( 'val', new_term );
			    $( '#' + current_id ).select2( 'close' );
			    $( '#' + current_id + ' option').removeAttr('selected');
			    $( '#' + current_id + ' option[value="' + new_term + '"]').attr('selected', 'selected');
			    $( '#' + current_id ).change();
			};

			$('.wr-select2-editor').each(function () {
				var current_id = $(this).attr('id');
				$(this).select2({
				    allowClear:true,
				    formatNoMatches: function(term) {
				    	term = parseInt( term );
				    	return "<div class='select2-result-label wr-new-term'><span class='select2-match'>"+term+"</span></div>";
				    },
				}).parent().find('.select2-with-searchbox').on('keyup', function(e) {
					if(e.keyCode === 13) {
						onSelectionItem( current_id );
					}
				}).on( 'click','.wr-new-term',function(){
					onSelectionItem( current_id );
				} );
			});
		}
	}

	$.HandleSetting.init = function() {
		//$.HandleSetting.togglePreview();

		$.HandleSetting.updateState();

		$.HandleSetting.tab();

		// Trigger action of element which has dependency elements
		$.HandleSetting.changeDependency('.wr_has_depend');

		// Update preview when change param in Modal Box/Styling
		$('#modalOptions').delegate('[id^="param"]', 'change', function() {
			if ($(this).attr('data-role') == 'no_preview') {
				return false;
			}
			if ($(this).attr('id') == 'param-copy_style_from' ) {
				return false;
			}

			//var	parent_tab = $(this).parents('.wr-pb-setting-tab'),
			//	stop_reload_iframe = ((parent_tab.length > 0 && parent_tab.is("#styling")) || (parent_tab.length > 0 && parent_tab.is("#modalAction"))) ? 0 : 1;
			var stop_reload_iframe = 0;

			$.HandleSetting.shortcodePreview(null, null, null, null, stop_reload_iframe);
		});

		// Change option of Inline-edit sub element
		$('#modalOptions').on( 'change', '.sub-element-settings [id^="param"]', function(e) {
			e.stopPropagation();
			$.HandleSetting.shortcodePreview(null, null, null, null, 0, 1, $('.control-group', '.sub-element-settings:visible'));
		} );

		// Trigger preview for parameters of Widget
		$('#modalOptions #wr-widget-form').delegate('input, select, textarea', 'change', function() {
			$.HandleSetting.shortcodePreview();
		});

		if ($('#shortcode_name').val() != 'wr_image') {
			$('select option[value="large_image"]').hide();
		}

		$('body').on( 'trigger_select2', function(e){
			$.HandleSetting.select2();
		} );

		$('body').on( 'toggleDependencyAll', function(e){
			$.HandleSetting.toggleDependencyAll();
		} );

		// Send Ajax request for loading shortcode html at first time
		$.HandleSetting.renderModal();

		$.HandleSetting.select2();

		$.HandleSetting.icons();

		$.HandleSetting.actionHandle();

		$.HandleSetting.selectImage();

		$.HandleSetting.gradientPicker();

		$.HandleSetting.buttonGroup();

		$.HandleSetting.inputValidator();

		$.HandleSetting.initCopyStyle();

		// Init Copy to Clipboard action
		$.HandleSetting.copyToClipboard();

		$.HandleSetting.closeAllSelect2WhenClickModal();

		$.HandleSetting.handleResizeModal();

		$.HandleSetting.useBootstrapJS();

		$.HandleSetting.addTooltip();

		$.HandleSetting.addStyleBorderType();

		$.HandleSetting.addStyleHeadingType();

		$.HandleSetting.addSelectEditor();

		$('body').on( 'trigger_inline_edit', function (e) {
			$.HandleSetting.addTooltip();
		} );
	};
})(jQuery);
