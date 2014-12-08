<?php

/**
 * @version    $Id$
 * @package    WR PageBuilder
 * @author     WooRockets Team <support@www.woorockets.com>
 * @copyright  Copyright (C) 2012 www.woorockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.woorockets.com
 * Technical Support:  Feedback - http://www.www.woorockets.com
 */

/**
 * @todo : Related page template functions
 */
if ( ! class_exists( 'WR_Pb_Helper_Layout' ) ) {

	class WR_Pb_Helper_Layout {

		/**
		 * Save premade layouts file
		 *
		 * @param type $layout_name
		 * @param type $layout_content
		 */
		static function save_premade_layouts( $layout_name, $layout_content ) {
			$error      = 0;
			$upload_dir = WR_Pb_Helper_Functions::get_wp_upload_folder( '/wr-pb-layout/' . WR_PAGEBUILDER_USER_LAYOUT );

			$layout_name = preg_replace( '/([\[\]\\/\:\*\?"<>|])*/', '', $layout_name );
			$file_name   = sanitize_title( $layout_name );
			$file        = $upload_dir . '/layout-' . $file_name . '.tpl';

			// if layout name is existed, show error
			if ( file_exists( $file ) ) {
				$error = 1;
			} else {
				// create file & store layout information
				$fp = fopen( $file, 'w' );
				fwrite( $fp, '[wr_layout name="' . $layout_name . '"]' );
				fwrite( $fp, $layout_content );
				fclose( $fp );
			}

			return $error;
		}

		/**
		 * Get name of premade layouts file
		 */
		static function get_premade_layouts() {
			$path = WR_Pb_Helper_Functions::get_wp_upload_folder( '/wr-pb-layout/' );

			$upload_dir = array();
			while ( $d = glob( $path . '/*', GLOB_ONLYDIR ) ) {
				$path .= '/*';
				foreach ( $d as $adir ) {
					$upload_dir[] = $adir;
				}
			}

			$files = $providers = array();
			$dirs  = $upload_dir;
			foreach ( $dirs as $dir ) {
				$provider_id = self::get_provider_info( $dir, 'id' );

				// providerid - provider names
				$providers[$provider_id] = self::get_provider_info( $dir );

				// providerid - layouts
				foreach ( glob( $dir . '/*.tpl' ) as $filename ) {
					if ( ! isset ( $files[$provider_id] ) ) {
						$files[$provider_id] = array();
					}
					$files[$provider_id][basename( $filename )] = $filename;
				}
			}

			return array( 'providers' => $providers, 'files' => $files );
		}


		/**
		 * Get uri from dir path
		 *
		 * @param type $dir
		 * @param type $file
		 *
		 * @return type
		 */
		static function get_uri( $dir, $file ) {
			if ( $dir == WR_PB_PREMADE_LAYOUT ) {
				$uri = WR_PB_PREMADE_LAYOUT_URI;
			} else {
				$path_parts = pathinfo( $dir );
				$uri        = WR_Pb_Helper_Functions::get_wp_upload_url( '/wr-pb-layout/' ) . $path_parts['basename'];
			}

			return "$uri/$file";
		}

		/**
		 * Get content of premade layouts file, prinrt as template
		 */
		static function print_premade_layouts() {
			$files = self::get_premade_layouts();
			foreach ( $files as $provider => $layouts ) {
				foreach ( $layouts as $name => $path ) {
					$content = self::extract_layout_data( $path, 'content' );
					echo balanceTags( "<script type='text/html' id='tmpl-layout-$name'>\n$content\n</script>\n" );
				}
			}
		}

		/**
		 * Read file line by line
		 *
		 * @param type $path
		 *
		 * @return type
		 */
		static function extract_layout_data( $path, $data ) {
			$fp = @fopen( $path, 'r' );
			if ( $fp ) {
				$contents = fread( $fp, filesize( $path ) );
				$pattern  = '/\[wr_layout\s+([A-Za-z0-9_-]+=\"[^"\']*\"\s*)*\s*\]/';
				fclose( $fp );
				if ( in_array( $data, array( 'name', 'description', 'title' ) ) ) {
					return self::extract_data_from_shortcode( $pattern, $contents, $data );
				} else {
					if ( $data == 'content' ) {
						return preg_replace( $pattern, '', $contents );
					}
				}
			}
		}

		/**
		 * Extract shortcode param from content
		 *
		 * @param type $pattern
		 * @param type $contents
		 * @param type $data
		 *
		 * @return type
		 */
		static function extract_data_from_shortcode( $pattern, $contents, $data ) {
			preg_match( $pattern, $contents, $matches );
			$layout_info = isset ( $matches[0] ) ? $matches[0] : '';
			$params    = array();
			preg_match_all( '/[A-Za-z0-9_-]+=\"[^"\']*\"/u', $layout_info, $tmp_params, PREG_PATTERN_ORDER );
			foreach ( $tmp_params[0] as $param_value ) {
				$output = array();
				preg_match_all( '/([A-Za-z0-9_-]+)=\"([^"\']*)\"/u', $param_value, $output, PREG_SET_ORDER );
				foreach ( $output as $item ) {
					$params[$item[1]] = urldecode( $item[2] );
				}
			}

			return isset ( $params[$data] ) ? $params[$data] : '';
		}

		/**
		 * Get provider id of layout folder: Search for provider.info file to get provider name
		 *
		 * @param type $dir
		 */
		static function get_provider_info( $dir, $info = 'name' ) {
			if ( $info == 'id' ) {
				$path_parts = pathinfo( $dir );

				return ( ( $dir == WR_PB_PREMADE_LAYOUT ) ? 'wr_pb' : $path_parts['basename'] );
			}
			if ( $dir == WR_PB_PREMADE_LAYOUT ) {
				return __( 'WR Templates', WR_PBL );
			}

			// Get provider info from xml file
			$path = $dir . '/provider.xml';
			if ( file_exists( $path ) ) {
				$dom_object = new DOMDocument();
				if ( $dom_object->load( $path ) ) {
					$node = $dom_object->getElementsByTagName( $info );
					if ( $node ) {
						return $node->item( 0 )->nodeValue;
					}
				}
			}

			return __( 'Your Templates', WR_PBL );
		}

		/**
		 * Import layout from folder
		 *
		 * @param type $file
		 */
		static function import( $dir ) {
			$provider_name    = self::get_provider_info( $dir, 'name' );
			$folder_to_create = ( $provider_name == __( 'Your Templates', WR_PBL ) ) ? WR_PAGEBUILDER_USER_LAYOUT : sanitize_title( $provider_name );
			$new_dir          = WR_Pb_Helper_Functions::get_wp_upload_folder( '/wr-pb-layout/' . $folder_to_create, false );

			// if this is new provider, rename tmp folder to provider name
			if ( ! is_dir( $new_dir ) ) {
				return rename( $dir, $new_dir );
			} // move templates file & thumbnail to existed folder of provider
			else {
				foreach ( glob( $dir . '/*.*' ) as $filename ) {
					$path_parts = pathinfo( $filename );
					$name       = $path_parts['basename'];
					$ext        = $path_parts['extension'];
					// only copy image & template file
					if ( in_array( strtolower( $ext ), array( 'png', 'gif', 'jpg', 'jpeg', 'tpl', 'xml' ) ) ) {
						copy( $filename, "$new_dir/$name" );
					}
				}

				return true;
			}

			return false;
		}

		/**
		 * Remove group layout
		 *
		 * @param type $group
		 */
		static function remove_group( $group ) {
			$group = substr( $group, 0, - 7 );
			$dir   = WR_Pb_Helper_Functions::get_wp_upload_folder( '/wr-pb-layout/' . $group, false );
			if ( is_dir( $dir ) ) {
				WR_Pb_Utils_Common::recursive_delete( $dir );

				// if directory still exits, false
				if ( is_dir( $dir ) ) {
					return false;
				}

				return true;
			}

			return false;
		}

		/**
		 * Check if file with custom extension exists
		 *
		 * @param type $dir
		 * @param type $layout_name
		 *
		 * @return type
		 */
		static function check_ext_exist( $dir, $layout_name ) {
			$images_ext = array( 'png', 'gif', 'jpg', 'jpeg' );
			$got_ext    = '';
			foreach ( $images_ext as $ext ) {
				if ( file_exists( $dir . "/$layout_name.$ext" ) ) {
					$got_ext = $ext;
				} else if ( file_exists( $dir . "/$layout_name." . strtoupper( $ext ) ) ) {
					$got_ext = strtoupper( $ext );
				}
			}

			return $got_ext;
		}

		/**
		 * Remove group in layout
		 *
		 * @param type $group
		 * @param type $layout
		 */
		static function remove_layout( $group, $layout ) {
			$layout_name = str_replace( '.tpl', '', $layout );
			$dir         = WR_Pb_Helper_Functions::get_wp_upload_folder( '/wr-pb-layout/' . $group, false );
			$deleted     = array();
			if ( is_dir( $dir ) ) {
				// remove .tpl file
				$layout_file = $dir . "/$layout_name.tpl";
				if ( file_exists( $layout_file ) ) {
					$deleted[] = unlink( $layout_file ) ? 1 : 0;
				}

				$thumbnail = "$dir/$layout_name.png";
				$got_ext   = self::check_ext_exist( $dir, $layout_name );
				if ( ! empty( $got_ext ) ) {
					$thumbnail = "$dir/$layout_name.$got_ext";
					if ( file_exists( $thumbnail ) ) {
						$deleted[] = unlink( $thumbnail ) ? 1 : 0;
					}
				}

				if ( in_array( 0, $deleted ) ) {
					return false;
				}

				return true;
			}

			return false;
		}

	}

}
