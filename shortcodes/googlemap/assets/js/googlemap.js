/**
 * @version    $Id$
 * @package    IGPGBLDR
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support: Feedback - http://www.woorockets.com/contact-us/get-support.html
 */
( function ($) {
	"use strict";

	$.GoogleMapElement	= $.GoogleMapElement || {};

	$.GoogleMapElement = function(iframe) {
   		var html_options 	= '<option value="" > - '+Wr_Translate.shortcodes.googlemap1+' - </option>';
   		if ( $('#shortcode_name').val() == 'wr_googlemap' ) {
   			var select_destination 	= $(iframe).find('.activate-item #select_param-gmi_destination');
   			var exclude_title		= $(iframe).find('.activate-item #param-gmi_title').val();
   			var currentValue		= $(iframe).find('.activate-item #param-gmi_destination').val();

   			$('#modalOptions .jsn-item textarea[data-sc-info="shortcode_content"]').each(function () {
   	    		var html_str 	= $(this).html();
   	    		var title 		= html_str.match(/gmi_title="[^*!"]+"/g);
   	    		var value 		= title[0].replace('"', '');
   	    		value 			= value.replace('"', '');
   	    		value 			= value.replace('gmi_title=', '');
   	    		if ( exclude_title != '' && exclude_title == value ) {
   	    			html_options	+= '';
   				} else if ( currentValue ) {
   					var current_selected = '';
   	    			if ( currentValue == value ) {
   	    				current_selected 	= 'selected="selected"';
   	    			}

   	    			html_options 	+= '<option value="' + value + '" ' + current_selected + ' >' + value + '</option>';
   				} else {
   					html_options 	+= '<option value="' + value + '" >' + value + '</option>';
   				}

   	    	});
   			if ( html_options ) {
    			select_destination.html( html_options );
    			$(select_destination).attr('class', 'form-control input-sm');
    		}

			$(iframe).find('#select_param-gmi_destination').on( 'change', function () {
    			$(this).closest('.controls').find('#param-gmi_destination').val($(this).val());
            } );

   		}
    }

	$(document).ready(function () {
		$('body').on('wr_submodal_load', function (e, iframe) {
			$.GoogleMapElement(iframe);
		});
	});

})(jQuery);