/**
 * @version    $Id$
 * @package    WR PageBuilder
 * @author     WooRockets Team <support@www.woorockets.com>
 * @copyright  Copyright (C) 2012 www.woorockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.woorockets.com
 * Technical Support:  Feedback - http://www.www.woorockets.com
 */

/**
 * Custom script for Row
 */
( function ($) {
	"use strict";

	$(document).ready(function () {
		$('body').on("click", ".wr-dialog .radio_image", function (e) {
			e.stopPropagation();
		});

        // toggle Position box
        $('#param-background').change(function(){
            var value = $(this).val();
            if(value == 'image'){
                value = $('#parent-param-stretch button.active').attr('data-value');
                if(value == 'full'){
                    $('#parent-param-position').addClass('wr_hidden_depend2');
                }else{
                    $('#parent-param-position').removeClass('wr_hidden_depend2');
                }
            }
        });

        // toggle Padding left, right when Width = Full
        var fn_toggle_padding = function() {
            var $val = $("[name='param-width']:checked").val();
            if ( $val == 'full' ) {
                $('#parent-param-div_padding').children('.controls').children('.combo-item:odd').hide();
            } else {
                $('#parent-param-div_padding').children('.controls').children('.combo-item:odd').show();
            }
        }

        // on load
        fn_toggle_padding();

        // on change
        $("[name='param-width']").change(function(){
            fn_toggle_padding();
        });
	});

})(jQuery)