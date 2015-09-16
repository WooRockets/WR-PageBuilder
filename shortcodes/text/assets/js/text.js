/**
 * @version    $Id$
 * @package    WR PageBuilder
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 woorockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support:  Feedback - http://www.woorockets.com
 */

/**
 * Custom script for Textbox element
 */
var initContentEditor;

( function ($) {
    "use strict";

    $.IGSelectFonts = $.IGSelectFonts || {};

    $.IGColorPicker = $.IGColorPicker || {};

    $.WR_Text = $.WR_Text || {};

    $.WR_Text = function () {
        if (typeof $.IGSelectFonts != 'undefined') { new $.IGSelectFonts(); }
        if (typeof $.IGColorPicker != 'undefined') { new $.IGColorPicker(); }
    };

    $(document).ready(function () {        
        $.WR_Text();
        $('#content-html').click();        
        var intTimeout = 5000;
        var intAmount = 100;
        var isInit = false;
        $('#param-text-tmce').removeAttr('onclick');
        $('#param-text-tmce').off('click');
        var ifLoadedInt = setInterval(function(){
            if (iframe_load_completed || intAmount >= intTimeout) {                
                initContentEditor = tinyMCEPreInit.mceInit['param-text'];              
               intAmount += 100;
                initContentEditor.setup = function(ed){
                    ed.on('blur', function(){
                        if ( $('.mce-tinymce').length > 1) {
                            $('.mce-tinymce').first().hide();    
                        }                        
                         tinyMCE.triggerSave();
                         jQuery('.wr_pb_editor').first().trigger('change');
                    });
                }
                // Visual Tab
                $('#param-text-tmce').click(function(){                    
                    setTimeout( function(){
                         if ( $('.mce-tinymce').length > 1) {
                            $('.mce-tinymce').first().hide();    
                        }
                    },500);                   
                    tinymce.remove(tinymce.get('param-text'));
                    tinymce.init(initContentEditor);                     
                    isInit = true;
                    $('#wp-content-wrap').removeClass('html-active');
                    $('#wp-content-wrap').addClass('tmce-active');
                });

                // Text tab
                $('#param-text-html').click(function(){
                    setTimeout( function(){
                         if ( $('.mce-tinymce').length > 1) {
                            $('.mce-tinymce').first().hide();    
                        }
                    },500);                    
                    tinymce.remove(tinymce.get('param-text'));
                    tinymce.init(initContentEditor); 
                    $('#wp-content-wrap').removeClass('tmce-active');
                    $('#wp-content-wrap').addClass('html-active');
                });
                iframe_load_completed = false;
                window.clearInterval(ifLoadedInt);
            }                
        },
        intAmount
        );    


    });
})(jQuery);