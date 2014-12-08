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
( function ($)
{
    $.IGPopoverOptions	= $.IGPopoverOptions || {};

    $.IGPopoverOptions = function () {};

    $.IGPopoverOptions.prototype = {
        init:function(){
            this.container = $(".jsn-items-list");
            this.addIconbar();
            this.actionIconbar(this);
            if(this.container.parents('.unsortable').length == 0){
                this.container.sortable({
                    placeholder: "ui-state-highlight",
                    stop: function( event, ui ) {
                        $.HandleSetting.shortcodePreview();
                    }
                });
//                this.container.disableSelection();
            }
        },
        addIconbar:function(){
            this.container.find(".jsn-item").find(":input[data-popover-item='yes']").each(function(){
                $(this).after('<div class="jsn-iconbar"><a class="element-action-edit" href="javascript:void(0)"><i class="icon-cog"></i></a></div>');
            })
        },
        actionIconbar:function(this_){
            this_.container.find(".element-action-edit").click(function (e) {
                this_.openActionSettings(this_, $(this));

                // Remove old select2 in Popover before re-initialize
                $('.jsn-modal:last #modalAction').find('.select2-container').remove();

                // Remove select2 drop mask
                $('#select2-drop-mask').remove();

                // Re-initialize select2
                if($('.select2').length > 0 || $('.select2-select').length > 0){
                    $.HandleSetting.select2();
                }

                // Fix font selector
                if($('.jsn-fontFaceType').length > 0){
                    if (typeof $.IGSelectFonts != 'undefined') { new $.IGSelectFonts(); }
                }

                // Fix color selector
                if($('.color-selector').length > 0){
                    if (typeof $.IGColorPicker != 'undefined') { new $.IGColorPicker(); }
                }

                e.stopPropagation();
            });
        },
        openActionSettings:function(this_, btnInput, specific, callback){
            this_.container.find(".jsn-item.ui-state-edit").removeClass("ui-state-edit");
            $(btnInput).parents(".jsn-item").addClass("ui-state-edit");
            $(".control-list-action").hide();
            var dialog, value, el_title, options = {};
            options.btnInput = btnInput ? btnInput : null;

            if(specific == null){
                value = $(btnInput).parents(".jsn-item").find(":input").val();
            }
            else{
                value = $(btnInput).parents(".jsn-item").find(":input#param-elements").val();
            }
            el_title = $(btnInput).parents(".jsn-item").find("label").text();

            var dialog_html = '';
            if($("#control-action-"+value).length == 0){
                $('body').find('[data-related-to="'+value+'"]').each(function(){
                    dialog_html += $("<div />").append($(this).clone()).html();
                    $(this).remove();
                })
                dialog = dialog_html;
                options.el_title = el_title;
                options.value = value;
            }
            else{
                dialog_html = $("#control-action-"+value);
            }

            // show dialog
            dialog = this_.showPopover(dialog_html, options);

            // update HTML DOM
            $( '.control-list-action' ).delegate( '[id^="param"]', 'change', function () {
                $(this).attr('value',$(this).val());
                if($(this).is('select')){
                    var html = $(this).html();
                    html = html.replace('selected=""','').replace('value="'+$(this).val()+'"', 'value="'+$(this).val()+'" selected=""');
                    $(this).html(html);
                }
            });

            if(callback)
                callback(dialog);

            $(document).click(function () {
                this_.container.find(".jsn-item.ui-state-edit").removeClass("ui-state-edit");
            });

            // fire hook event after insert popover html
            $('body').trigger('wr_after_popover');
        },
        // Get element's dimension
        getBoxStyle:function(element){
            var style = {
                width:element.width(),
                height:element.height(),
                outerHeight:element.outerHeight(),
                outerWidth:element.outerWidth(),
                offset:element.offset(),
                margin:{
                    left:parseInt(element.css('margin-left')),
                    right:parseInt(element.css('margin-right')),
                    top:parseInt(element.css('margin-top')),
                    bottom:parseInt(element.css('margin-bottom'))
                },
                padding:{
                    left:parseInt(element.css('padding-left')),
                    right:parseInt(element.css('padding-right')),
                    top:parseInt(element.css('padding-top')),
                    bottom:parseInt(element.css('padding-bottom'))
                }
            };

            return style;
        },

        /**
         * Show popover
         */
        showPopover:function(dialog_html, options, callback) {
            var this_ = this;
            var dialog;
            if (typeof dialog_html == 'object') {
                dialog = dialog_html;
            } else {
                dialog = $("<div/>", {
                    'class':'control-list-action jsn-bootstrap3',
                    'id':"control-action-" + options.value,
                    'style' : 'position: absolute; width: 300px;'
                }).append(
                    $("<div/>", {
                        //"class":"popover left"
                    	"class":"popover bottom"
                    }).css("display", "block").append($("<div/>", {
                        "class":"arrow"
                    })).append(
                        $("<h3/>", {
                            "class":"popover-title",
                            text: options.el_title + ' ' + Wr_Translate.settings
                        })
                    ).append(
                        $("<div/>", {
                            "class":"popover-content"
                        }).append(
                            dialog_html
                            )
                    )
                );
            }
            if (typeof dialog_html != 'object') {
                if(options.show_hidden == null || options.show_hidden){
                    dialog.find('.hidden').removeClass('hidden');
                }

                dialog.hide();
                dialog.appendTo('.jsn-modal:last #modalAction');
            }

            dialog.fadeIn(500);

            // Get position of popover
            var elmStyle = this_.getBoxStyle(dialog.find(".popover")),
            parentStyle = this_.getBoxStyle($(options.btnInput)),
            position = {};
            //position.left = parentStyle.offset.left - elmStyle.outerWidth - 11; // 11 is width of arrow of popover left
            //position.top = parentStyle.offset.top - (elmStyle.outerHeight / 2) + (parentStyle.outerHeight / 2) - 12 + ( options.offset ? options.offset : 0);
            position.top  = parentStyle.offset.top + 20;
            position.left = parentStyle.offset.left - ( elmStyle.outerWidth / 2 ) + 15;

            // if this element doesn't use Iframe
            var dialog_wrapper = $('.ui-dialog').last();
            if(dialog_wrapper.length){
                var dialog_wrapper_pos = this_.getBoxStyle(dialog_wrapper);
                position.left -= dialog_wrapper_pos.offset.left;
                position.top -= dialog_wrapper_pos.offset.top + 40;
                $('.jsn-bootstrap3 .popover.left > .arrow').css('left', 'auto');
            }
            position.top += $('.ui-dialog-content').scrollTop();

            // Update position for popover
            dialog.css(position);

            dialog.bind('click mousedown', function (e) {
                e.stopPropagation();
            });

            $(document).bind('click mousedown', function () {
                dialog.hide();
                if(callback){
                    callback();
                }
            });
        }
    }

    $(document).ready(function(){
        var Wr_Content = new $.IGPopoverOptions();
        Wr_Content.init();
    })

})(jQuery);