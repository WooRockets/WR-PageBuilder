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

( function ($) {
	'use strict';
	$( document ).ready(function () {		
		// Set manual event previous for carousel left control
		$('.wr-element-carousel .left').on('click', function (e) {
			e.preventDefault();
			var parent_id = $(this).closest('.carousel').attr('id');
			if ( typeof( $('#' + parent_id ).carousel ) == 'function' ) {
				$('#' + parent_id ).carousel( 'prev' );
			}
		});
		
		// Set manual event next for carousel right control
		$('.wr-element-carousel .right').on('click', function (e) {
			e.preventDefault();
			var parent_id = $(this).closest('.carousel').attr('id');
			if ( typeof( $('#' + parent_id ).carousel ) == 'function' ) {
				$('#' + parent_id ).carousel( 'next' );
			}
		});
		
		// Set manual event for carousel indicator controls
		$('.wr-element-carousel .carousel-indicators li').each(function (index) {
			$(this).on('click', function (e) {
				e.preventDefault();
				var parent_id = $(this).closest('.carousel').attr('id');
				if ( typeof( $('#' + parent_id ).carousel ) == 'function' ) {
					$('#' + parent_id ).carousel( index );
				}
			});
		});
	});
} )(jQuery);