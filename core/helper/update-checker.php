<?php
/**
 * @version    $Id$
 * @package    WR_PageBuilder
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2014 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */

/**
 * @todo : Check plugin update
 */

class WR_Pb_Helper_Update_Checker {
	/**
	 * Check update by cURL.
	 *
	 * @return  void
	 */
	public static function check_by_curl() {
		$server_url = "http://www.woorockets.com/wp-admin/admin-ajax.php";
		$plugin_data = get_plugin_data( WR_PB_FILE );
		$data = array(
			'action' => 'wr_update_checking',
			'url' => site_url(),
			'plugin' => $plugin_data['Name'],
			'version' => $plugin_data['Version']
		);
		$ch = curl_init( $server_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		curl_exec( $ch );
		curl_close( $ch );
	}

	/**
	 * Check update by AJAX.
	 *
	 * @return  void
	 */
	public static function check_by_ajax() {
		wp_enqueue_script( 'wr-pb-check-update-js', WR_PB_URI . 'assets/woorockets/js/check-update.js' );
		$plugin_data = get_plugin_data( WR_PB_FILE );
		$ajax_wr_check_update = array(
			'url' => site_url(),
			'plugin' => $plugin_data['Name'],
			'version' => $plugin_data['Version']
		);
		wp_localize_script( 'wr-pb-check-update-js', 'ajax_wr_check_update', $ajax_wr_check_update );
	}
}
