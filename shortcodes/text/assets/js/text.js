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

        // Fix conflict script when create new tinymce editor
        $('#content-html').click();
        tinymce.remove(tinymce.get('param-text'));

        // Re-init content editor
        if (initContentEditor == null) {
        	initContentEditor = tinyMCEPreInit.mceInit['content'];
        }
        $('#content-tmce').removeAttr('onclick');
        $('#content-tmce').off('click');
        $('#content-tmce').click(function() {
        	tinymce.remove(tinymce.get('content'));
        	tinymce.init(initContentEditor);
        	$('#wp-content-wrap').removeClass('html-active');
        	$('#wp-content-wrap').addClass('tmce-active');
        });
    });
})(jQuery);