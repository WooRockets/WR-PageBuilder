<?php
/**
 * @version    $Id$
 * @package    WR_Library
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */

if ( ! class_exists( 'WR_Pb_Init_File_System' ) ) :

/**
 * File system initialization.
 *
 * @package  WR_Library
 * @since    1.0.0
 */
class WR_Pb_Init_File_System {
	/**
	 * Initialize WordPress Filesystem Abstraction.
	 *
	 * @return  object
	 */
	public static function get_instance() {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( ! $wp_filesystem ) {
			WP_Filesystem();
		}

		return $wp_filesystem;
	}
	
	/**
	 * Prepare a directory.
	 *
	 * @param   string  $path  Absolute path to directory needs preparation.
	 *
	 * @return  mixed  Directory path on success, boolean FALSE on failure
	 */
	public static function prepare_directory( $path ) {
		// Get WordPress Filesystem Abstraction object
		$wp_filesystem = self::get_instance();

		if ( ! $wp_filesystem->is_dir( $path ) ) {
			$result = explode( '/', str_replace( '\\', '/', $path ) );
			$path   = array();

			while ( count( $result ) ) {
				$path[] = current( $result );

				if ( ! $wp_filesystem->is_dir( implode( '/', $path ) ) ) {
					if ( ! $wp_filesystem->mkdir( implode( '/', $path ), 0755 ) ) {
						return false;
					}
				}

				// Shift paths
				array_shift( $result );
			}
		}

		return ( is_array( $path ) ? implode( '/', $path ) : $path );
	}
}

endif;
