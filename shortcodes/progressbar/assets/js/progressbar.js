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
 * Custom script for ProgressBar element
 */
(function ($) {

	"use strict";

	$.WR_Progressbar = $.WR_Progressbar || {};

	$.WR_Progressbar = function () {
		$('#param-progress_bar_style').on('change', function () {
            var selectValue = $(this).val();

            if ( selectValue ) {
                var shortcodes = $('#group_elements .jsn-item textarea');
                var total = 0;

                shortcodes.each(function () {
                    var shortcode_str = $(this).html();
                    var result 	  = shortcode_str.replace(/pbar_group="[a-z\-]+"/g, 'pbar_group="' + selectValue + '"');
                    var arr_match = shortcode_str.match(/pbar_percentage="[0-9]+"/g);
                    var str_match = '';
                    if ( arr_match ) {
                    	for ( var i = 0; i < arr_match.length; i++ ) {
                        	if ( arr_match[i] ) {
                        		str_match = arr_match[i].toString();
                        		str_match 	  = str_match.match(/\b([0-9]+)\b/g);
                        		total += parseInt( str_match );
                        	}
                        }
                    }
                    $(this).html(result);
                });

                if ( selectValue == 'stacked' && total > 100 ) {
                	// Progress total percentage
                    shortcodes.each(function () {
                        var shortcode_str = $(this).html();
                        var arr_match     = shortcode_str.match(/pbar_percentage="[0-9]+"/g);
                        var str_match     = '';
                        if ( arr_match ) {
                        	for ( var i = 0; i < arr_match.length; i++ ) {
                            	if ( arr_match[i] ) {
                            		str_match     = arr_match[i].toString();
                            		str_match 	  = str_match.match(/\b([0-9]+)\b/g);
                            		var percent   = parseInt( str_match ) / (total / 100);
                                    var result 	  = shortcode_str.replace(/pbar_percentage="[0-9]+"/g, 'pbar_percentage="' + percent + '"');
                                    $(this).html(result);
                            	}
                            }
                        }
                    });
                }
            }

        });

		$('#param-progress_bar_style').trigger('change');
	}

	$(document).ready(function () {
		$.WR_Progressbar();
	});

})(jQuery)