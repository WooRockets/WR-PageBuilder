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

		$('[data-original-title]').tooltip({
			placement: 'bottom'
		});
		$(".wr-prtbl-button-fancy").fancybox({
			"width"        : "75%",
			"height"       : "75%",
			"autoScale"    : false,
			"transitionIn" : "elastic",
			"transitionOut": "elastic",
			"type"         : "iframe"
		});

        var c_li_first = $('.wr-prtbl-cols.first .wr-prtbl-features li');
        c_col_not_first = $('.wr-prtbl-cols:not(.first)');
        avg_height = $('.wr-prtbl-cols:not(.first) .wr-prtbl-header').height();
        i = 0;
        c_li_first.each(function () {
            height = $(this).height();
            c_col_not_first.each(function(){
                $(this).find('.wr-prtbl-features li').eq(i).height(height);
            });
            i++;
        });

        c_col_not_first.each(function(){
            lgt = $(this).find('.wr-prtbl-header .wr-prtbl-meta p').length;
            if(lgt >0){
                $('.wr-prtbl-cols.first .wr-prtbl-header').height(avg_height);
                return false;
            }
        });

	});

})(jQuery);