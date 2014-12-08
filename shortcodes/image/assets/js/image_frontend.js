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
		if ( typeof($.fn.lazyload) == "function" ) {
			$(".image-scroll-fade").lazyload({
				effect       : "fadeIn"
			});	
		}
		if (typeof($.fancybox) == "function") {
			$(".wr-image-fancy").fancybox({
				"autoScale"	: true,
				"transitionIn"	: "elastic",
				"transitionOut"	: "elastic",
				"type"		: "iframe"
			});
		}
	});
	
})(jQuery);