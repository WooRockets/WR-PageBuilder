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

(function ($) {
	$(document).ready(function () {
		if ( typeof( $.fn.lazyload ) == 'function' ) {
			$('.wr-element-progressbar .progress-bar').lazyload({
				effect: 'fadeIn'
			});
			$(".wr-element-progressbar .progress-bar").on("appear", function () {
				if ( ! $(this).hasClass('wr-lazyload-active') ) {
					bar_width = $(this).attr("aria-valuenow");
					$(this).width(bar_width + "%");
					$(this).addClass('wr-lazyload-active');
				}
			});
		} else {
			$(".wr-element-progressbar .progress-bar").each(function () {
				bar_width = $(this).attr("aria-valuenow");
				$(this).width(bar_width + "%");
			});
		}
	});
})(jQuery);