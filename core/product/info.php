<?php
/**
 * @version    $Id$
 * @package    WR_Library
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2014 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */

if ( ! class_exists( 'WR_Pb_Product_Info' ) ) :

/**
 * Product info class.
 *
 * @package  WR_Library
 * @since    1.0.0
 */
class WR_Pb_Product_Info {
	// Link to get product information from Envato
	const INFO_URL = 'http://marketplace.envato.com/api/v3/item:%ITEM-ID%.json';

	// Link to verify product purchase with Envato.
	const UPDATE_URL = 'http://www.woorockets.com/?wr-download-update=%PRODUCT%&username=%USERNAME%&api_key=%API-KEY%&purchase_code=%PURCHASE-CODE%';

	/**
	 * Retrieved product information.
	 *
	 * @var  array
	 */
	protected static $product_data;

	/**
	 * Time to cache product information.
	 *
	 * @var  integer
	 */
	protected static $cache_time = 86400;

	/**
	 * Parsed products data.
	 *
	 * @var  array
	 */
	protected static $products = array();

	/**
	 * Hook into WooRockets' products.
	 *
	 * @return  void
	 */
	public static function hook() {
		// Register action to save Envato product purchase data
		static $registered;

		if ( ! isset( $registered ) ) {
			add_action( 'wr_envato_purchase_data', array( __CLASS__, 'save' ), 10, 2 );

			$registered = true;
		}
	}

	/**
	 * Method to get product info.
	 *
	 * Product info will be returned in the following format:
	 *
	 * array(
	 *     'Name'             => 'WR Sample',
	 *     'Description'      => 'Sample plugin that demonstrates the functionality of WR Library (WooRockets's shared library). By woorockets.com.',
	 *     'Version'          => '1.0.0',
	 *     'Item_ID'  => 'wr-sample',
	 *     'Addons'           => null,
	 *     'Available_Update' => 0,
	 * )
	 *
	 * @param   string  $plugin  Path to plugin main file.
	 *
	 * @return  mixed  Plugin info if plugin is installed, NULL otherwise.
	 */
	public static function get( $plugin ) {
		// Hook into WordPress
		self::hook();

		// Verify plugin main file
		if ( false === strpos( $plugin, '/' ) && false === strpos( $plugin, '\\' ) && ! @is_file( $plugin ) ) {
			$plugin = self::check( $plugin );

			if ( empty( $plugin ) ) {
				return null;
			}
		}

		// Request product info only if not available
		if ( ! isset( self::$products[ $plugin ] ) ) {
			self::$products[ $plugin ] = null;

			// Get plugin data if neccessary
			if ( function_exists( 'get_plugin_data' ) ) {
				$data = get_plugin_data( $plugin );
			}

			// Store relative path to plugin main file
			$data['Main_File'] = $plugin;

			// Generate identification string for plugin
			$name = str_replace( '-', '_', basename( dirname( $plugin ) ) );

			// Get extra info from constant
			foreach ( array( 'Item_ID' => null, 'Addons' => null ) as $key => $default ) {
				// Generate constant name
				$const = strtoupper( "{$name}_{$key}" );

				if ( ! defined( $const ) && @is_file( dirname( $plugin ) . '/defines.php' ) ) {
					include_once dirname( $plugin ) . '/defines.php';
				}

				// Get constant value
				if ( defined( $const ) ) {
					eval( '$const = ' . $const . ';' );
				} else {
					$const = $default;
				}

				// Store extra info
				$data[ $key ] = $const;
			}

			// Get product update information
			$data['Available_Update'] = 0;

			if ( isset( $data['Item_ID'] ) && self::updatable( $data['Item_ID'] ) ) {
				$data['Available_Update']++;
			}

			if ( ! empty( $data['Addons'] ) ) {
				// Preset add-ons
				$addons = array();

				if ( is_string( $data['Addons'] ) ) {
					// Get add-ons details
					$items = explode( ',', $data['Addons'] );

					foreach ( $items as $name ) {
						// Check if add-on is installed
						$addon = self::get( $name );

						// Store add-on info
						if ( $addon ) {
							// Check if add-on has update
							if ( self::updatable( $addon['Item_ID'] ) ) {
								$addon['Available_Update'] = 1;

								// Increase total update as well
								$data['Available_Update']++;
							}

							$addons[] = $addon;
						}

						// Store add-ons data
						$data['Addons'] = $addons;
					}
				}
			}

			self::$products[ $plugin ] = $data;
		}

		return self::$products[ $plugin ];
	}

	/**
	 * Save Envato related data of a product purchase.
	 *
	 * @param   string  $plugin   Path to plugin main file.
	 * @param   string  $options  Associative array of options.
	 *
	 * @return  void
	 */
	public static function save( $plugin, $options ) {
		// Get plugin info
		if ( is_string( $plugin ) ) {
			$plugin = self::get( $plugin );
		}
	
		if ( ! empty( $plugin ) ) {
			// Get previously saved purchase data
			$purchase = get_option( "{$plugin['Item_ID']}_purchase_data", array() );
	
			// Get submitted purchase data
			foreach ( array( 'username', 'api_key', 'purchase_code' ) as $option ) {
				if ( isset( $options[ "envato_{$option}" ] ) && ! empty( $options[ "envato_{$option}" ] ) ) {
					if ( ! isset( $purchase[ $option ] ) || $purchase[ $option ] != $options[ "envato_{$option}" ] ) {
						$purchase[ $option ] = $options[ "envato_{$option}" ];
					}
				}
			}
	
			if ( ! isset( $purchase['username'] ) || ! isset( $purchase['api_key'] ) || ! isset( $purchase['purchase_code'] ) ) {
				return;
			}
	
			// Request WooRockets to verify purchase data
			$slug = apply_filters( 'wr_product_update_slug', basename( dirname( $plugin['Main_File'] ) ) );
	
			$link = str_replace(
					array( '%PRODUCT%', '%USERNAME%', '%API-KEY%', '%PURCHASE-CODE%' ),
					array( $slug, $purchase['username'], $purchase['api_key'], $purchase['purchase_code'] ),
					self::UPDATE_URL
			);
	
			$result = wp_remote_get( $link . '&verify_only=1' );
	
			if ( is_wp_error( $result ) ) {
				echo '<div class="error"><p><strong>' . $result->get_error_message() . '</strong></p></div>';
	
				return;
			}
	
			if ( 'OK' != $result['body'] ) {
				echo '<div class="error"><p><strong>' . $result['body'] . '</strong></p></div>';
	
				return;
			}
	
			// Store purchase data to database
			$purchase['download_url'] = $link;
	
			update_option( "{$plugin['Item_ID']}_purchase_data", $purchase );
		}
	}

	/**
	 * Check if a plugin is installed or not?
	 *
	 * @param   string   $name      Plugin's identified name defined in WooRockets server.
	 * @param   boolean  $abs_path  Return either absolute path to plugin main file or just plugin slug?
	 *
	 * @return  mixed  Either absolute path to plugin main file or plugin slug if plugin is installed, NULL otherwise.
	 */
	protected static function check( $name, $abs_path = true ) {
		// Check if this plugin is installed
		$name       = str_replace( '_', '-', trim( $name ) );
		$plugin     = null;
		$plugin_dir = WP_PLUGIN_DIR;

		if ( @is_file( "{$plugin_dir}/{$name}.php" ) ) {
			$plugin = "{$plugin_dir}/{$name}.php";
		}

		if ( ! $plugin && @is_file( "{$plugin_dir}/{$name}/main.php" ) ) {
			$plugin = "{$plugin_dir}/{$name}/main.php";
		}

		if ( ! $plugin && @is_file( "{$plugin_dir}/{$name}/{$name}.php" ) ) {
			$plugin = "{$plugin_dir}/{$name}/{$name}.php";
		}

		if ( $plugin && ! $abs_path ) {
			$plugin = str_replace( "{$plugin_dir}/", '', $plugin );
		}

		return $plugin;
	}

	/**
	 * Check if product has update.
	 *
	 * @param   array  $item_id  Item ID of product at Envato.
	 *
	 * @return  mixed  Product details object if update is available, NULL otherwise.
	 */
	public static function updatable( $item_id ) {
		// Get latest product info
		$product_data = self::info( $item_id );

		if ( ! $product_data ) {
			return null;
		}

		// Get last product update
		$last_update = get_option( "{$item_id}_last_update", null );

		if ( empty( $last_update ) ) {
			// Store last product update for the first time checking
			update_option( "{$item_id}_last_update", $product_data->last_update );

			return null;
		}

		// Compare latest product update with last update time
		if ( strtotime( $product_data->last_update ) > strtotime( $last_update ) ) {
			// Get purchase data previously saved in database
			$product_data->purchase_data = get_option( "{$item_id}_purchase_data", null );

			if ( ! empty( $product_data->purchase_data ) && ! is_object( $product_data->purchase_data ) ) {
				$product_data->purchase_data = ( object ) $product_data->purchase_data;
			}

			// Return product details object
			return $product_data;
		}

		return null;
	}

	/**
	 * Get latest product info using Envato API.
	 *
	 * @param   array  $item_id  Item ID of product at Envato.
	 *
	 * @return  array
	 */
	protected static function info( $item_id ) {
		global $pagenow;

		// Request Envato for product info only if not available
		if ( ! isset( self::$product_data[ $item_id ] ) ) {
			self::$product_data[ $item_id ] = get_transient( "wr_product_info_{$item_id}" );

			if ( ! self::$product_data[ $item_id ] ) {
				// Request Envato for product info
				$result = wp_remote_get( str_replace( '%ITEM-ID%', $item_id, self::INFO_URL ) );

				if ( ! is_wp_error( $result ) ) {
					if ( self::$product_data[ $item_id ] = json_decode( $result['body'], true ) ) {
						// Store data to cache
						set_transient( "wr_product_info_{$item_id}", self::$product_data[ $item_id ], self::$cache_time );
					}
				}
			}

			// Finalize product info
			if ( self::$product_data[ $item_id ] ) {
				self::$product_data[ $item_id ] = ( object ) self::$product_data[ $item_id ]['item'];
			}
		}

		return self::$product_data[ $item_id ];
	}
}

endif;
