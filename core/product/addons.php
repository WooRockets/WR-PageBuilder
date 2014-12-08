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

if ( ! class_exists( 'WR_Pb_Product_Addons' ) ) :

/**
 * Class that provides support for addons installation and management.
 *
 * @package  WR_Library
 * @since    1.0.0
 */
class WR_Pb_Product_Addons extends WR_Pb_Product_Info {
	/**
	 * Define valid addon manipulation actions and required capabilities.
	 *
	 * @var  array
	 */
	protected static $actions = array(
		'authenticate' => '',
		'install'      => 'install_plugins',
		'update'       => 'install_plugins',
		'uninstall'    => 'delete_plugins',
	);

	/**
	 * Hook into WordPress.
	 *
	 * @return  void
	 */
	public static function hook() {
		// Register Ajax action
		static $registered;
	
		if ( ! isset( $registered ) ) {
			add_action( 'wp_ajax_wr-addons-management',  array( __CLASS__, 'manage' ) );
	
			$registered = true;
		}
	}

	/**
	 * Render addons installation and management screen.
	 *
	 * @param   string  $plugin_file  Either absolute path to plugin main file or plugin's identified name defined in WooRockets server.
	 *
	 * @return  void
	 */
	public static function init( $plugin_file ) {
		// Hook into WordPress
		self::hook();

		// Get template path
		if ( $tmpl = WR_Pb_Loader::get_path( 'product/tmpl/addons.php' ) ) {
			// Get product information
			$plugin = self::get( $plugin_file );

			if ( ! $plugin ) {
				die( __( 'Cannot get addons information for current product.', WR_LIBRARY_TEXTDOMAIN ) );
			}

			// Check if user has customer account saved
			$customer_account     = get_option( 'wr_customer_account', null );
			$has_customer_account = ( is_array( $customer_account ) && ! @empty( $customer_account['username'] ) && ! @empty( $customer_account['password'] ) );

			// Load template
			include_once $tmpl;
		}
	}

	/**
	 * Handle addons management actions.
	 *
	 * @return  void
	 */
	public static function manage() {
		// Try to verify input data then execute action
		try {
			// Get addon to be manipulated
			$addon = isset( $_GET['addon'] ) ? $_GET['addon'] : null;

			if ( empty( $addon ) ) {
				throw new Exception( __( 'Missing addon to be installed.', WR_LIBRARY_TEXTDOMAIN ) );
			}

			// Get management action
			$action = isset( $_GET['do'] ) ? $_GET['do'] : null;

			if ( empty( $action ) || ! array_key_exists( $action, self::$actions ) ) {
				throw new Exception( __( 'The requested action is invalid.', WR_LIBRARY_TEXTDOMAIN ) );
			}

			// Execute requested action for provided addon
			$result = self::execute( $addon, $action );

			// Send response back
			echo json_encode( $result );

			// Exit immediately to prevent WordPress from processing further
			exit;
		} catch ( Exception $e ) {
			die(
				json_encode(
					array(
						'success' => false,
						'addon'   => $addon,
						'action'  => $action,
						'message' => $e->getMessage(),
					)
				)
			);
		}
	}

	/**
	 * Manipulate addon.
	 *
	 * @param   string  $addon   Addon to manipulate.
	 * @param   string  $action  Action to execute.
	 *
	 * @return  array
	 */
	protected static function execute( $addon, $action ) {
		// Check capabilities
		foreach ( self::$actions as $do => $capability ) {
			if ( $action == $do && ! empty( $capability ) && ! current_user_can( $capability ) ) {
				throw new Exception( __( 'You do not have sufficient permissions to either add or delete plugins for this site.' ) );
			}
		}

		// Check if addon should be updated or removed
		if ( 'update' == $action || 'uninstall' == $action ) {
			// Get plugin slug
			$plugin = self::check( $addon, false );

			if ( empty( $plugin ) ) {
				throw new Exception(
					__( 'update' == $action ? 'Cannot detect plugin to be updated.' : 'Cannot detect plugin to be removed.', WR_LIBRARY_TEXTDOMAIN )
				);
			}
		}

		// Check if addon should be removed
		if ( 'uninstall' == $action ) {
			$result = delete_plugins( array( $plugin ) );

			// Verify uninstallation result
			if ( is_wp_error( $result ) ) {
				throw new Exception( $result->get_error_message() );
			}
		} else {
			// Verify authentication data
			$authentication = (boolean) $_GET['authentication'];
			$username       = isset( $_POST['username'] ) ? $_POST['username'] : '';
			$password       = isset( $_POST['password'] ) ? $_POST['password'] : '';

			if ( $authentication && ( empty( $username ) || empty( $password ) ) ) {
				// Check if user has customer account saved
				$customer_account = get_option( 'wr_customer_account', null );

				if ( is_array( $customer_account ) && ! @empty( $customer_account['username'] ) && ! @empty( $customer_account['password'] ) ) {
					$username = $customer_account['username'];
					$password = $customer_account['password'];
				} else {
					throw new Exception( null );
				}
			}

			// Try to authenticate or download addon installation package
			try {
				$package = self::download( $addon, $authentication, $username, $password, 'authenticate' == $action );
			} catch ( Exception $e ) {
				throw $e;
			}

			// Get WordPress's WordPress Filesystem Abstraction object
			$wp_filesystem = WR_Pb_Init_File_System::get_instance();

			// Check if addon should be installed or updated
			if ( 'authenticate' != $action ) {
				// Verify core and add-on compatibility
				if ( isset( $_GET['core'] ) && ( $core = self::get( $_GET['core'] ) ) ) {
					// Extract downloaded add-on package
					$tmp_dir = substr( $package, 0, -4 );
					$result  = unzip_file( $package, $tmp_dir );

					if ( is_wp_error( $result ) ) {
						throw new Exception( $result->get_error_message() );
					}

					// Find constant definition file
					if ( @is_file( "{$tmp_dir}/defines.php" ) ) {
						include "{$tmp_dir}/defines.php";
					} elseif ( count( $defines = glob( "{$tmp_dir}/*/defines.php" ) ) ) {
						include current( $defines );
					}

					// Get minimum core version required for this add-on
					if ( defined( $core_version = strtoupper( $addon ) . '_CORE_VERSION' ) ) {
						eval( '$core_version = ' . $core_version . ';' );
					}

					if ( $core_version && version_compare( $core_version, $core['Version'], '>' ) ) {
						// Delete downloaded add-on package and clean-up temporary directory
						$wp_filesystem->delete( $package );
						$wp_filesystem->delete( $tmp_dir, true );

						// Skip add-on installation
						throw new Exception(
							sprintf(
								__( "Cannot install %1\$s v%2\$s.\nThis version requires %3\$s v%4\$s while you are using %5\$s v%6\$s.", WR_LIBRARY_TEXTDOMAIN ),
								$core['Addons'][ $addon ]->name,
								$core['Addons'][ $addon ]->version,
								$core['Name'],
								$core_version,
								$core['Name'],
								$core['Version']
							)
						);
					}

					// Verification done, clean-up temporary directory
					$wp_filesystem->delete( $tmp_dir, true );
				}

				// Init WordPress Plugin Upgrader
				class_exists( 'Plugin_Upgrader' ) || include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

				function_exists( 'screen_icon'     ) || include_once ABSPATH . 'wp-admin/includes/screen.php';
				function_exists( 'show_message'    ) || include_once ABSPATH . 'wp-admin/includes/misc.php';
				function_exists( 'get_plugin_data' ) || include_once ABSPATH . 'wp-admin/includes/plugin.php';

				// Either install or update add-on
				$upgrader = new Plugin_Upgrader();

				if ( 'install' == $action ) {
					// Install plugin
					$result = $upgrader->install( $package );

					// Verify installation result
					if ( is_wp_error( $result ) ) {
						throw new Exception( $result->get_error_message() );
					}
				} else {
					// Update plugin
					add_filter( 'upgrader_pre_install',       array( $upgrader, 'deactivate_plugin_before_upgrade' ), 10, 2 );
					add_filter( 'upgrader_clear_destination', array( $upgrader, 'delete_old_plugin'                ), 10, 4 );

					$upgrader->run(
						array(
							'package'           => $package,
							'destination'       => WP_PLUGIN_DIR,
							'clear_destination' => true,
							'clear_working'     => true,
							'hook_extra'        => array(
								'plugin' => $plugin,
								'type'   => 'plugin',
								'action' => 'update',
							),
						)
					);

					remove_filter( 'upgrader_pre_install',       array( $upgrader, 'deactivate_plugin_before_upgrade' ) );
					remove_filter( 'upgrader_clear_destination', array( $upgrader, 'delete_old_plugin'                ) );

					if ( is_wp_error( $upgrader->result ) ) {
						throw new Exception( $upgrader->result->get_error_message() );
					}

					// Force refresh of plugin update information
					if ( function_exists( 'wp_clean_plugins_cache' ) ) {
						wp_clean_plugins_cache( true );
					}
				}

				// Try to activate plugin
				try {
					$result = self::activate( $addon, $action );
				} catch ( Exception $e ) {
					throw $e;
				}

				// Remove downloaded add-on installation package
				$wp_filesystem->delete( $package );
			} else {
				// Check if user want to save customer account
				if ( isset( $_POST['remember'] ) && (boolean) $_POST['remember'] ) {
					update_option( 'wr_customer_account', array( 'username' => $username, 'password' => $password ) );
				}
			}
		}

		// Return data
		return is_array( $result ) ? $result : array(
			'success' => true,
			'addon'   => $addon,
			'action'  => $action,
			'message' => ( 'authenticate' == $action ) ? json_decode( $wp_filesystem->get_contents( $package ) ) : null,
		);
	}

	/**
	 * Method to download add-on package.
	 *
	 * @param   string   $addon              Identified name of add-on (as defined in WooRockets server).
	 * @param   boolean  $authentication     Whether authentication is required to be able to download add-on package?
	 * @param   string   $username           Username for authentication (only need if authentication is required).
	 * @param   string   $password           Password for authentication (only need if authentication is required).
	 * @param   boolean  $authenticate_only  Whether to authenticate or download add-on installation package?
	 *
	 * @return  string  Path to downloaded package.
	 */
	protected static function download( $addon, $authentication = false, $username = null, $password = null, $authenticate_only = false ) {
		// Get WordPress's WordPress Filesystem Abstraction object
		$wp_filesystem = WR_Pb_Init_File_System::get_instance();

		// Build query string
		$query[] = 'identified_name=' . $addon;

		if ( $authentication ) {
			$query[] = 'username=' . urlencode( $username );
			$query[] = 'password=' . urlencode( $password );
			$query[] = 'product_attr={%22authentication%22:%22true%22}';
		} else {
			$query[] = 'product_attr={%22authentication%22:%22false%22}';
		}

		if ( $authenticate_only ) {
			$query[] = 'upgrade=no';
		} else {
			$query[] = 'upgrade=yes';
			$query[] = 'file_attr={%22type%22:%22install%22}';
		}

		// Build final URL for downloading update package
		$url = self::$product_download . '&' . implode( '&', $query );

		// Download update package
		$target = wp_upload_dir();
		$target = $target['basedir'] . '/' . $addon . '_install.zip';
		$result = download_url( $url );

		if ( is_wp_error( $result ) ) {
			throw new Exception( $result->get_error_message() );
		}

		if ( $wp_filesystem->size( $result ) < 10 ) {
			$content = $wp_filesystem->get_contents( $result );

			switch ( $content ) {
				case 'ERR00':
					throw new Exception( __( 'Invalid Parameters! Can not verify your product information.', WR_LIBRARY_TEXTDOMAIN ) );
				break;

				case 'ERR01':
					throw new Exception( __( 'Invalid username or password.', WR_LIBRARY_TEXTDOMAIN ) );
				break;

				case 'ERR02':
					throw new Exception( __( 'We could not find the product in your order list. Seems like you did not purchase it yet.', WR_LIBRARY_TEXTDOMAIN ) );
				break;

				case 'ERR03':
					throw new Exception( __( 'Requested file is not found on server.', WR_LIBRARY_TEXTDOMAIN ) );
				break;

				default:
					if ( 'true' != $content && 'yes' != $content && '1' != $content ) {
						throw new Exception( __( 'Cannot connect to WooRockets.com server.', WR_LIBRARY_TEXTDOMAIN ) );
					}
				break;
			}
		}

		// Create a local file by copying temporary file
		if ( ! $authenticate_only ) {
			if ( ! $wp_filesystem->copy( $result, $target, true, FS_CHMOD_FILE ) ) {
				// If copy failed, chmod file to 0644 and try again
				$wp_filesystem->chmod( $target, 0644 );

				if ( ! $wp_filesystem->copy( $result, $target, true, FS_CHMOD_FILE ) ) {
					throw new Exception( __( 'Cannot store add-on package to local file system.', WR_LIBRARY_TEXTDOMAIN ) );
				}
			}
		}

		// Remove temporary file
		$wp_filesystem->delete( $result );

		// Finished
		return $target;
	}

	/**
	 * Detect plugin file of installed add-on and activate plugin.
	 *
	 * @param   string  $addon   Identified name of add-on (as defined in WooRockets server).
	 * @param   string  $action  Last executed action.
	 *
	 * return  void
	 */
	protected static function activate( $addon, $action = '' ) {
		// Check capabilities
		if ( ! current_user_can( 'activate_plugins' ) ) {
			throw new Exception( __( 'You do not have sufficient permissions to activate plugins for this site.' ) );
		}

		// Get WordPress's WordPress Filesystem Abstraction object
		$wp_filesystem = WR_Pb_Init_File_System::get_instance();

		// Get plugin slug
		$plugin = self::check( $addon, false );

		if ( empty( $plugin ) ) {
			throw new Exception( __( 'Cannot detect plugin to activate.', WR_LIBRARY_TEXTDOMAIN ) );
		}

		// Activate plugin
		$result = activate_plugin( $plugin, '', is_network_admin() );

		if ( is_wp_error( $result ) ) {
			return array(
				'success' => true,
				'addon'   => $addon,
				'action'  => $action,
				'message' => $result->get_error_message(),
			);
		}
	}
}

endif;
