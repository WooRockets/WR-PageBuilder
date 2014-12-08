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
	'use strict';
	
	$(document).ready(function () {
		$('.wr-element-tab ul.nav-tabs li a').on('click', function (e) {
			e.preventDefault();
			
			$(this).tab('show');
		});
	});
})(jQuery);