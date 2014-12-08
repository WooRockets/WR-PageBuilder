<?php
/**
 *
 * Uninstalling WR PageBuilder: deletes post metas & options
 *
 * @author		WooRockets Team <support@www.woorockets.com>
 * @package		IGPGBLDR
 * @version		$Id$
 */

//if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
exit();

include_once 'core/utils/common.php';

// delete all other providers
$providers = get_option( '_wr_pb_providers' );
if ( $providers ) {
	$providers    = unserialize( $providers );
	$list_plugins = array();
	foreach ( $providers as $provider ) {
		if ( ! empty ( $provider['file'] ) ) {
			$list_plugins[] = $provider['file'];
		}
	}
	delete_plugins( $list_plugins );
}
// delete cache folder
WR_Pb_Utils_Common::remove_cache_folder();

// delete meta key
WR_Pb_Utils_Common::delete_meta_key( array( '_wr_page_builder_content', '_wr_html_content', '_wr_page_active_tab', '_wr_post_view_count', '_wr_deactivate_pb', '_wr_page_builder_css_files', '_wr_page_builder_css_custom' ) );