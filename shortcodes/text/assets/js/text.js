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

        var intTimeout = 5000;
        var intAmount  = 100;

        var ifLoadedInt = setInterval(function(){
            if (iframe_load_completed || intAmount >= intTimeout) {

                var text_content = $( '#param-text.form-control' ).html();

                var wr_editor = $( '#tmpl-wr-editor' ).html();

                wr_editor = wr_editor.replace( '_WR_CONTENT_', text_content );

                $( '#param-text' ).after( wr_editor );

                $( '#param-text.form-control' ).remove();

                ( function() {
                    var init, id, $wrap;

                    // Render Visual Tab
                    for ( id in tinyMCEPreInit.mceInit ) {
                        if ( id != 'param-text' )
                            continue;

                        init  = tinyMCEPreInit.mceInit[id];
                        $wrap = tinymce.$( '#wp-' + id + '-wrap' );

                        tinymce.remove(tinymce.get('param-text'));
                        tinymce.init( init );

                        setTimeout( function(){
                            $( '#wp-param-text-wrap' ).removeClass( 'html-active' );
                            $( '#wp-param-text-wrap' ).addClass( 'tmce-active' );
                        }, 10 );

                        if ( ! window.wpActiveEditor )
                                window.wpActiveEditor = id;

                        break;
                    }

                    // Render Text tab
                    for ( id in tinyMCEPreInit.qtInit ) {
                        if ( id != 'param-text' )
                            continue;

                        quicktags( tinyMCEPreInit.qtInit[id] );

                        // Re call inset quicktags button
                        QTags._buttonsInit();

                        if ( ! window.wpActiveEditor )
                            window.wpActiveEditor = id;

                        break;
                    }
                }());

                iframe_load_completed = false;
                window.clearInterval(ifLoadedInt);
            }
        },
        intAmount
        );

    });
})(jQuery);