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

if ( ! class_exists( 'WR_Pb_Loader' ) ) :

// Include WR Library definition file
if ( @is_file( dirname( __FILE__ ) . '/defines.php' ) ) {
	include_once dirname( __FILE__ ) . '/defines.php';
}

/**
 * Class autoloader.
 *
 * @package  WR_Library
 * @since    1.0.0
 */
class WR_Pb_Loader {
	/**
	 * Base paths to search for class file.
	 *
	 * @var  array
	 */
	protected static $paths = array();

	/**
	 * Register base path to search for class files.
	 *
	 * @param   string  $path    Base path.
	 * @param   string  $prefix  Class prefix.
	 *
	 * @return  void
	 */
	public static function register( $path, $prefix = 'WR_' ) {
		// Allow one base directory associates with more than one class prefix
		if ( ! isset( self::$paths[ $path ] ) ) {
			self::$paths[ $path ] = array( $prefix );
		} elseif ( ! in_array( $prefix, self::$paths[ $path ] ) ) {
			self::$paths[ $path ][] = $prefix;
		}
	}

	/**
	 * Loader for WR Library classes.
	 *
	 * @param   string  $className  Name of class.
	 *
	 * @return  void
	 */
	public static function load( $className ) {
		// Only autoload class name prefixed with WR_
		if ( 0 === strpos( $className, 'WR_' ) ) {
			// Filter paths to search for class file
			self::$paths = apply_filters( 'wr_pb_loader_get_path', self::$paths );

			// Loop thru base directory to find class declaration file
			foreach ( array_reverse( self::$paths ) AS $base => $prefixes ) {
				// Loop thru all class prefix to find appropriate class declaration file
				foreach ( array_reverse( $prefixes ) as $prefix ) {
					// Check if requested class name match a supported class prefix
					if ( 0 === strpos( $className, $prefix ) ) {
						// Split the class name into parts separated by underscore character
						$path = explode( '_', trim( str_replace( $prefix, '', $className ), '_' ) );

						// Convert class name to file path
						$path = implode( '/', array_map( 'strtolower', $path ) );

						// Check if class file exists
						$file  = $path . '.php';
						$slave = $path . '/' . basename( $path ) . '.php';

						while ( true ) {
							$exists = false;

							// Check if file exists
							if ( @is_file( $base . '/' . $file ) ) {
								$exists = $file;
							}

							if ( $exists ) {
								break;
							}

							// Check if alternative file exists
							if ( @is_file( $base . '/' . $slave ) ) {
								$exists = $slave;
							}

							if ( $exists ) {
								break;
							}

							// If there is no any alternative file, quit the loop
							if ( false === strrpos( $file, '/' ) || 0 === strrpos( $file, '/' ) ) {
								break;
							}

							// Generate further alternative files
							$file  = preg_replace( '#/([^/]+)$#', '-\\1', $file );
							$slave = dirname( $file ) . '/' . substr( basename( $file ), 0, -4 ) . '/' . basename( $file );
						}

						if ( $exists ) {
							return include_once $base . '/' . $exists;
						}
					}
				}
			}

			return false;
		}
	}

	/**
	 * Search a file in registered paths.
	 *
	 * @param   string  $file  Relative file path to search for.
	 *
	 * @return  string
	 */
	public static function get_path( $file ) {
		// Generate alternative file name
		$slave = str_replace( '_', '-', $file );

		// Filter paths to search for file
		self::$paths = apply_filters( 'wr_pb_loader_get_path', self::$paths );

		foreach ( array_reverse( self::$paths ) AS $base => $prefixes ) {
			if ( @is_file( $base . '/' . $slave ) ) {
				return $base . '/' . $slave;
			} elseif ( @is_file( $base . '/' . $file ) ) {
				return $base . '/' . $file;
			}
		}

		return null;
	}
}

// Register class autoloader with PHP
spl_autoload_register( array( 'WR_Pb_Loader', 'load' ) );

// Register base path to look for class file
WR_Pb_Loader::register( dirname( __FILE__ ), 'WR_' );

// Include plugin definition file
if ( @is_file( dirname( dirname( __FILE__ ) ) . '/defines.php' ) ) {
	include_once dirname( dirname( __FILE__ ) ) . '/defines.php';
}

endif;
