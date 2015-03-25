/**
 * @version    $Id$
 * @package    WR_PageBuilder
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */

(function($) {
	$(document).ready(function() {
		var serverurl = 'http://www.woorockets.com/wp-admin/admin-ajax.php';
		var data = {
			'action': 'wr_update_checking',
			'url': ajax_wr_check_update.url,
			'plugin': ajax_wr_check_update.plugin,
			'version': ajax_wr_check_update.version
		};
		$.ajax({
			type: "POST",
			url: serverurl,
			data: data
		});
	});
})(jQuery);
