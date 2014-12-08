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
		var audio_source		= $('#param-audio_sources', $('#modalOptions'));
		var soundcloud_file	= $('#param-audio_source_link', $('#modalOptions'));
		var local_file	= $('#param-audio_source_local', $('#modalOptions'));
		audio_source.select2({minimumResultsForSearch:-1});
		// Fix horizon scrollbar
		audio_source.css('display', 'none');
		$('.select2-offscreen', $('#parent-param-audio_sources')).css('display', 'none');

		$('#parent-param-audio_start_track', $('#modalOptions')).removeClass('wr_hidden_depend').addClass('wr_hidden_depend');

		if ($('option:selected', audio_source).length > 0) {
			var audio_source_value	= $('option:selected', audio_source)[0].value;
			if (audio_source_value == 0){
				$('#modalOptions .jsn-tabs').hide();
			}
		}

		audio_source.on('change', function (e){
			$.HandleSetting.updateState(0);
			if ($('option:selected', audio_source).length > 0) {
				var audio_source_value	= $('option:selected', audio_source)[0].value;

				if (audio_source_value == 0){
					$('#modalOptions .jsn-tabs').hide();
				}else{
					$('#modalOptions .jsn-tabs').show();
				}
			}else{
				$('#modalOptions .jsn-tabs').show();
			}
			$.HandleSetting.shortcodePreview(0);
		});


		if (soundcloud_file.val()){
			validate_file();
		}else{
			soundcloud_file.parent().removeClass('input-append');
		}

		soundcloud_file.on('change', function (){
			validate_file();
		});

		var audioxhr;
		function validate_file()
		{
			$('span.add-on', soundcloud_file.parent()).remove();
			if (!soundcloud_file.val()) {
				soundcloud_file.parent().removeClass('input-append');
				return;
			}
			if(audioxhr && audioxhr.readystate != 4){
				audioxhr.abort();
	        }
			soundcloud_file.parent().addClass('input-append');

			soundcloud_file.after($('<span class="add-on input-group-addon"></span'));
			var loading_icon	= $('<i class="audio-validate jsn-icon16 jsn-icon-loading" ></i>');
			var ok_icon			= $('<i class="audio-validate icon-ok" ></i>');
			var ban_icon		= $('<i class="audio-validate icon-warning" data-original-title="'+Wr_Translate.invalid_link+'"></i>');
			$('#modalOptions .audio-validate').remove();
			soundcloud_file.next('.add-on').append(loading_icon);
			audioxhr	= $.post(
	            Wr_Ajax.ajaxurl,
	            {
	                action 		: 'validate_file',
	                shortcode 	: 'audio',
	                file_url	: soundcloud_file.val(),
	                wr_nonce_check : Wr_Ajax._nonce
	            }
            ).done(function (data) {
            	if (data === 'false') {
            		$('#modalOptions .audio-validate').remove();
            		loading_icon.remove();
            		soundcloud_file.next('.add-on').append(ban_icon);
            	}else{
            		$('#modalOptions .audio-validate').remove();
            		loading_icon.remove();
            		soundcloud_file.next('.add-on').append(ok_icon);
            		var title	= '';
            		var res		= $.parseJSON(data);
            		$(ok_icon).attr('data-original-title', res.content);
            		if (res.type != 'list') {
            			$('#parent-param-audio_start_track', $('#modalOptions')).removeClass('wr_hidden_depend').addClass('wr_hidden_depend');
            		}else{
            			$('#parent-param-audio_start_track', $('#modalOptions')).removeClass('wr_hidden_depend');
            		}
            	}

            	$('#modalOptions .audio-validate').tooltip({
            		html: true,
            		placement: 'left'
            	});

            });
		}
	});
} );