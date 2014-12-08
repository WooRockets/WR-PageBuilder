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
 * Custom script for Audio element
 */
jQuery( function ($){
	$(document).ready(function () {
		var video_source		= $('#param-video_sources', $('#modalOptions'));
		var local_file	= $('#param-video_source_local', $('#modalOptions'));
		var youtube		= $('#param-video_source_link_youtube', $('#modalOptions'));
		var vimeo		= $('#param-video_source_link_vimeo', $('#modalOptions'));
		video_source.select2({minimumResultsForSearch:-1});

		// Fix horizon scrollbar
		video_source.css('display', 'none');
		$('.select2-offscreen', $('#parent-param-video_sources')).css('display', 'none');

		// Hide Show List param for youtube
		$('#parent-param-video_youtube_show_list', $('#modalOptions')).removeClass('wr_hidden_depend').addClass('wr_hidden_depend');

		// Trigger change then validate file when Youtube link changed.
		youtube.on('change', function (){
			validate_file();
		});

		// Trigger change then validate file when Vimdeo link changed.
		vimeo.on('change', function (){
			validate_file();
		});

		if (youtube.val()){
			validate_file();
		}else{
			youtube.parent().removeClass('input-append');
		}

		if (vimeo.val()){
			validate_file();
		}else{
			vimeo.parent().removeClass('input-append');
		}

		var audioxhr;
		function validate_file()
		{
			var _video_source	= video_source.val();
			var file_type	= 'youtube';
			var obj;
			if (_video_source == 'youtube') {
				obj		= youtube;
			}else if (_video_source == 'vimeo'){
				obj		= vimeo;
				file_type	= 'vimeo';
			}

			$('span.add-on', obj.parent()).remove();
			if (!obj.val()) {
				obj.parent().removeClass('input-append');
				return;
			}
			if(audioxhr && audioxhr.readystate != 4){
				audioxhr.abort();
	        }
			obj.parent().addClass('input-append');

			obj.after($('<span class="add-on input-group-addon"></span'));
			var loading_icon	= $('<i class="audio-validate jsn-icon16 jsn-icon-loading" ></i>');
			var ok_icon			= $('<i class="audio-validate icon-ok" ></i>');
			var ban_icon		= $('<i class="audio-validate icon-warning" data-original-title="'+Wr_Translate.invalid_link+'"></i>');
			$('#modalOptions .audio-validate').remove();
			obj.next('.add-on').append(loading_icon);
			audioxhr	= $.post(
	            Wr_Ajax.ajaxurl,
	            {
	                action 		: 'video_validate_file',
	                shortcode 	: 'video',
	                file_url	: obj.val(),
	                file_type	: file_type,
	                wr_nonce_check : Wr_Ajax._nonce
	            }
            ).done(function (data) {
            	if (data === 'false') {
            		$('#modalOptions .audio-validate').remove();
            		loading_icon.remove();
            		obj.next('.add-on').append(ban_icon);
            	}else{
            		$('#modalOptions .audio-validate').remove();
            		loading_icon.remove();
            		obj.next('.add-on').append(ok_icon);
            		var res		= $.parseJSON(data);
            		$(ok_icon).attr('data-original-title', res.content);
            		// unhide "Show List" parameter if detected video url had list param
            		if (res.type == 'list') {
            			$('#parent-param-video_youtube_show_list', $('#modalOptions')).removeClass('wr_hidden_depend');
            		}else{
            			$('#parent-param-video_youtube_show_list', $('#modalOptions')).removeClass('wr_hidden_depend').addClass('wr_hidden_depend');
            		}
            	}

            	$('#modalOptions .audio-validate').tooltip({
            		html: true,
            		placement: 'left'
            	});

            });
		}
	});
});