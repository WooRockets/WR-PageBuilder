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

if ( ! class_exists( 'WR_Pb_Init_Admin_Menu' ) ) :

/**
 * Admin menu manipulation.
 *
 * @package  WR_Library
 * @since    1.0.0
 */
class WR_Pb_Init_Admin_Menu {
	/**
	 * Array of menus to be registered.
	 *
	 * @var  array
	 */
	protected static $add = array();

	/**
	 * Array of menus to be removed.
	 *
	 * @var  array
	 */
	protected static $remove = array();

	/**
	 * Array of menus to be replaced.
	 *
	 * @var  array
	 */
	protected static $replace = array();

	/**
	 * Hook into WordPress.
	 *
	 * @return  void
	 */
	public static function hook() {
		// Register action to manipulate admin menu
		static $registered;
	
		if ( ! isset( $registered ) ) {
			add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ), 1000 );
	
			$registered = true;
		}
	}

	/**
	 * Register a menu to be added to WordPress admin menu.
	 *
	 * Below is a sample use of this method:
	 *
	 * WR_Pb_Init_Admin_Menu::add(
	 *     array(
	 *         'page_title' => 'WR Sample - All Items',
	 *         'menu_title' => 'WR Sample',
	 *         'capability' => 'edit_posts',
	 *         'menu_slug'  => 'wr-sample-all-items',
	 *         'function'   => 'wr_sample_all_items',
	 *         'children'   => array(
	 *             array(
	 *                 'page_title' => 'WR Sample - All Items',
	 *                 'menu_title' => 'All Items',
	 *                 'capability' => 'edit_posts',
	 *                 'menu_slug'  => 'wr-sample-all-items',
	 *                 'function'   => 'wr_sample_all_items'
	 *             ),
	 *             array(
	 *                 'page_title' => 'WR Sample - Add New Item',
	 *                 'menu_title' => 'Add New',
	 *                 'capability' => 'edit_posts',
	 *                 'menu_slug'  => 'wr-sample-add-new',
	 *                 'function'   => 'wr_sample_add_new'
	 *             )
	 *         )
	 *     )
	 * );
	 *
	 * @param   array   $menu         Menu declaration array.
	 * @param   string  $parent_slug  The slug name for the parent menu ( or the file name of a standard WordPress admin page ).
	 *
	 * @return  void
	 */
	public static function add( $menu, $parent_slug = null ) {
		// Hook into WordPress
		self::hook();

		if ( ! isset( $menu['menu_title'] ) ) {
			return;
		}

		// Set default capability if not specified
		$menu['capability'] = isset( $menu['capability'] ) ? $menu['capability'] : 'edit_posts';

		// Generate menu slug if not specified
		$menu['menu_slug'] = isset( $menu['menu_slug'] ) ? $menu['menu_slug'] : preg_replace( '/[^a-zA-Z0-9]+/', '-', $menu['menu_title'] );

		// Set parent_slug if specified
		if ( ! empty( $parent_slug ) ) {
			$menu['parent_slug'] = $parent_slug;
		}

		// Store menu declaration for adding later
		self::$add[] = $menu;

		// Add submenu if specified
		if ( @is_array( $menu['children'] ) ) {
			foreach ( $menu['children'] AS $submenu ) {
				self::add( $submenu, $menu['menu_slug'] );
			}
		}
	}

	/**
	 * Remove a menu entry from WordPress admin menu.
	 *
	 * @param   string  $menu_slug    Menu slug.
	 * @param   string  $parent_slug  Parent slug is needed when the entry to be removed is a submenu.
	 *
	 * @return  void
	 */
	public static function remove( $menu_slug, $parent_slug = null ) {
		// Hook into WordPress
		self::hook();

		self::$remove[$menu_slug] = $parent_slug;
	}

	/**
	 * Replace a menu entry of WordPress admin menu.
	 *
	 * Below is a sample use of this method:
	 *
	 * WR_Pb_Init_Admin_Menu::replace(
	 *     'Dashboard',
	 *     array(
	 *         0 => 'Control Panel', // Menu label
	 *         1 => 'read',          // Capability
	 *         2 => 'index.php',     // Menu slug
	 *         3 => '',              // Parent slug
	 *         4 => '',              // <li> element's class attribute
	 *         5 => 'menu-dashboard' // <li> element's id attribute
	 *         6 => ''               // Custom menu icon URI
	 *     )
	 * );
	 *
	 * @param   string  $label        Label of menu entry to be replaced.
	 * @param   array   $menu         New menu declaration to replace.
	 * @param   string  $parent_slug  Parent slug is needed when the entry to be replaced is a submenu.
	 *
	 * @return  void
	 */
	public static function replace( $label, $menu, $parent_slug = null ) {
		// Hook into WordPress
		self::hook();

		self::$replace[$label][empty( $parent_slug ) ? 0 : $parent_slug] = $menu;
	}

	/**
	 * Manipulate WordPress admin menu.
	 *
	 * @return  void
	 */
	public static function admin_menu() {
		global $menu, $submenu;

		// Filter menus to be registered
		self::$add = apply_filters( 'wr_pb_register_admin_menu', self::$add );

		// Variable to hold menu position
		static $position;

		// Add menus
		foreach ( self::$add AS $entry ) {
			if ( isset( $entry['parent_slug'] ) ) {
				add_submenu_page(
					$entry['parent_slug'],
					$entry['page_title'],
					$entry['menu_title'],
					$entry['capability'],
					$entry['menu_slug'],
					isset( $entry['function'] ) ? $entry['function'] : ''
				);
			} else {
				// Calculate menu position
				if ( ! isset( $entry['position'] ) || empty( $entry['position'] ) ) {
					if ( ! isset( $position ) ) {
						eval( '$position = 50.' . rand( 100, 1000 ) . ';' );
					} else {
						$position++;
					}
				}

				add_menu_page(
					$entry['page_title'],
					$entry['menu_title'],
					$entry['capability'],
					$entry['menu_slug'],
					( isset( $entry['function'] ) && ! empty( $entry['function'] ) ) ? $entry['function'] : '',
					( isset( $entry['icon_url'] ) && ! empty( $entry['icon_url'] ) ) ? $entry['icon_url'] : '',
					( isset( $entry['position'] ) && ! empty( $entry['position'] ) ) ? "{$entry['position']}" : "{$position}"
				);
			}
		}

		// Remove menus
		foreach ( self::$remove AS $menu_slug => $parent_slug ) {
			if ( $parent_slug ) {
				remove_submenu_page( $parent_slug, $menu_slug );
			} else {
				remove_menu_page( $menu_slug );
			}
		}

		// Replace menus
		if ( count( self::$replace ) ) {
			foreach ( self::$replace AS $label => $data ) {
				// Get parent slug
				$parent_slug = array_keys( $data );
				$parent_slug = array_shift( $parent_slug );

				if ( $parent_slug && isset( $submenu[ $parent_slug ] ) ) {
					foreach ( $submenu[ $parent_slug ] AS $key => $value ) {
						if ( $value[0] === $label ) {
							foreach ( $data[ $parent_slug ] AS $k => $v ) {
								$submenu[ $parent_slug ][ $key ][ $k ] = $v;
							}
						}
					}
				} elseif ( $parent_slug === 0 ) {
					foreach ( $menu AS $key => $value ) {
						if ( $value[0] === $label ) {
							foreach ( $data[0] AS $k => $v ) {
								$menu[ $key ][ $k ] = $v;
							}
						}
					}
				}
			}
		}
	}
}

endif;
