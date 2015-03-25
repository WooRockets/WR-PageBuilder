<?php
/**
 * @version    $Id$
 * @package    WR_PageBuilder
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support: Feedback - http://www.woorockets.com/contact-us/get-support.html
 */

/**
 * WR PageBuilder Settings
 *
 * @package  WR_PageBuilder
 * @since    1.0.0
 */
class WR_Pb_Product_Plugin {
	/**
	 * Define pages.
	 *
	 * @var  array
	 */
	public static $pages = array( 'wr-pb-settings', 'wr-pb-addons', 'wr-pb-about-us' );

	/**
	 * Current WR PageBuilder settings.
	 *
	 * @var  array
	 */
	protected static $settings;

	/**
	 * Initialize WR PageBuilder plugin.
	 *
	 * @return  void
	 */
	public static function init() {
		global $pagenow;

		// Get product information
		$plugin = WR_Pb_Product_Info::get( WR_PB_FILE );

		// Remove line below to enable Addons mechanism feature.
		$plugin['Addons'] = null;

		// Generate menu title
		$menu_title = __( 'WR PageBuilder', WR_PBL );

		// Define admin menus
		$admin_menus = array(
			'page_title' => __( 'WR PageBuilder', WR_PBL ),
			'menu_title' => $menu_title,
			'capability' => 'manage_options',
			'menu_slug'  => 'wr-pb-about-us',
			'icon_url'   => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/wr-pgbldr-icon-white.png',
			'function'   => array( __CLASS__, 'about_us' ),
			'children'   => array(
				array(
					'page_title' => __( 'WR PageBuilder - Settings', WR_PBL ),
					'menu_title' => __( 'Settings', WR_PBL ),
					'capability' => 'manage_options',
					'menu_slug'  => 'wr-pb-settings',
					'function'   => array( __CLASS__, 'settings' ),
				),
				array(
					'page_title' => __( 'WR PageBuilder - About', WR_PBL ),
					'menu_title' => __( 'About', WR_PBL ),
					'capability' => 'manage_options',
					'menu_slug'  => 'wr-pb-about-us',
					'function'   => array( __CLASS__, 'about_us' ),
				),
			),
		);

		if ( $plugin['Addons'] ) {
			// Generate menu title
			$menu_title = __( 'Add-ons', WR_PBL );

			if ( $plugin['Available_Update'] && ( 'admin.php' == $pagenow && isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], self::$pages ) ) ) {
				$menu_title .= " <span class='wr-available-updates update-plugins count-{$plugin['Available_Update']}'><span class='pending-count'>{$plugin['Available_Update']}</span></span>";
			}

			// Update admin menus
			$admin_menus['children'][] = array(
				'page_title' => __( 'WR PageBuilder - Add-ons', WR_PBL ),
				'menu_title' => $menu_title,
				'capability' => 'manage_options',
				'menu_slug'  => 'wr-pb-addons',
				'function'   => array( __CLASS__, 'addons' ),
			);
		}

		// Initialize necessary WR Library classes
		WR_Pb_Init_Admin_Menu::hook();
		WR_Pb_Product_Addons ::hook();

		// Register admin menus
		WR_Pb_Init_Admin_Menu::add( $admin_menus );

		// Remove redundant menu
		WR_Pb_Init_Assets::inline( 'js', '$(\'#toplevel_page_wr-pb-about-us .wp-first-item\').hide();' );

		// Register 'wr_pb_installed_product' filter
		add_filter( 'wr_pb_installed_product', array( __CLASS__, 'register_product' ) );

		// Load required assets
		if ( 'admin.php' == $pagenow && isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], array( 'wr-pb-settings', 'wr-pb-addons' ) ) ) {
			// Load common assets
			WR_Pb_Init_Assets::load( array( 'wr-bootstrap-css', 'wr-jsn-css' ) );

			switch ( $_REQUEST['page'] ) {
				case 'wr-pb-addons':
					// Load addons style and script
					WR_Pb_Init_Assets::load( array( 'wr-pb-addons-css', 'wr-pb-addons-js' ) );
				break;
			}
		}

		// Register Ajax actions
		if ( 'admin-ajax.php' == $pagenow ) {
			add_action( 'wp_ajax_wr-pb-convert-data',  array( __CLASS__, 'convert_data' ) );
		}
	}

	/**
	 * Apply 'wr_pb_installed_product' filter.
	 *
	 * @param   array  $plugins  Array of installed WooRockets product.
	 *
	 * @return  array
	 */
	public static function register_product( $plugins ) {
		// Register product identification
		$plugins[] = WR_PAGEBUILDER_IDENTIFICATION;

		return $plugins;
	}

	/**
	 * Convert other page builder data to WR PageBuilder data.
	 *
	 * @return  void
	 */
	public static function convert_data() {
		// Set custom error reporting level
		error_reporting( E_ALL ^ E_NOTICE );

		// Get current post
		$post = isset( $_REQUEST['post'] ) ? get_post( $_REQUEST['post'] ) : null;

		if ( ! $post ) {
			die( json_encode( array( 'success' => false, 'message' => __( 'Missing post ID.', WR_PBL ) ) ) );
		}

		// Get converter
		$converter = isset( $_REQUEST['converter'] ) ? WR_Pb_Converter::get_converter( $_REQUEST['converter'], $post ) : null;

		if ( ! $converter ) {
			die( json_encode( array( 'success' => false, 'message' => __( 'Missing data converter.', WR_PBL ) ) ) );
		}

		// Handle conversion of other page builder data to WR PageBuilder
		$result = $converter->convert();

		if ( ! is_integer( $result ) || ! $result ) {
			$response = array( 'success' => false, 'message' => $result );
		} else {
			if ( isset( $_REQUEST['do'] ) && 'convert-and-publish' != $_REQUEST['do'] ) {
				$result = __( 'Data has been successfully converted!', WR_PBL );
			} else {
				$result = __( 'Data has been successfully converted and published!', WR_PBL );
			}

			$response = array( 'success' => true, 'message' => $result );
		}

		die( json_encode( $response ) );
	}

	/**
	 * Load required assets.
	 *
	 * @return  void
	 */
	public static function load_assets() {
		WR_Pb_Helper_Functions::enqueue_styles();
		WR_Pb_Helper_Functions::enqueue_scripts_end();
	}

	/**
	 * Render addons installation and management screen.
	 *
	 * @return  void
	 */
	public static function addons() {
		// Instantiate product addons class
		WR_Pb_Product_Addons::init( WR_PB_FILE );
	}

	/**
	 * Render settings page.
	 *
	 * @return  void
	 */
	public static function settings() {
		// Load update script
		WR_Pb_Init_Assets::load( array( 'wr-pb-settings-js' ) );

		include WR_PB_TPL_PATH . '/settings.php';
	}

	/**
	 * Render About-us page.
	 *
	 * @return  void
	 */
	public static function about_us() {
		// Load assets
		WR_Pb_Init_Assets::load( array( 'wr-pb-bootstrap-css', 'wr-pb-bootstrap-js' ) );
		// Load template
		include WR_PB_TPL_PATH . '/about-us.php';
	}

	/**
	 * Register settings with WordPress.
	 *
	 * @return  void
	 */
	public static function settings_form() {
		// Add the section to reading settings so we can add our fields to it
		$page    = 'wr-pb-settings';
		$section = 'wr-pb-settings-form';

		add_settings_section(
		$section,
			'',
		array( __CLASS__, 'wr_pb_section_callback' ),
		$page
		);

		// Add the field with the names and function to use for our settings, put it in our new section
		$fields = array(
			array(
					'id'    => 'enable_for',
					'title' => __( 'Enable PageBuilder for...', WR_PBL ),
			),
			array(
					'id'    => 'cache',
					'title' => __( 'Enable Caching', WR_PBL ),
			),
			array(
					'id'     => 'bootstrap',
					'title'  => __( 'Load Bootstrap Assets', WR_PBL ),
			///// for multiple fields in a setting box
					'params' => array( 'wr_pb_settings_boostrap_js', 'wr_pb_settings_boostrap_css' ),
			),
			array(
					'id'    => 'fullmode',
					'title' => __( 'Enable full mode', WR_PBL ),
			),
			array(
					'id'    => 'auto_check_update',
					'title' => __( 'Send plugin info to WooRockets for improving', WR_PBL )
			)
		);

		foreach ( $fields as $field ) {
			// Preset field id
			$field_id = $field['id'];

			// Do not add prefix for WooRockets Customer Account settings
			if ( 'wr_customer_account' != $field['id'] ) {
				$field_id = str_replace( '-', '_', $page ) . '_' . $field['id'];
			}

			// Register settings field
			add_settings_field(
			$field_id,
			$field['title'],
			array( __CLASS__, 'wr_pb_setting_callback_' . $field['id'] ),
			$page,
			$section,
			isset ( $field['args'] ) ? $field['args'] : array()
			);

			// Register our setting so that $_POST handling is done for us and callback function just has to echo the <input>
			register_setting( $page, $field_id );

			$params = isset( $field['params'] ) ? $field['params'] : array();
			foreach ( (array) $params as $field_id ) {
				register_setting( $page, $field_id );
			}
		}

	}

	/**
	 * Get current settings.
	 *
	 * @return  array
	 */
	public static function wr_pb_settings_options() {
		if ( ! isset( self::$settings ) ) {
			// Define options
			$options  = array( 'wr_pb_settings_enable_for', 'wr_pb_settings_cache', 'wr_pb_settings_fullmode', 'wr_pb_settings_boostrap_js', 'wr_pb_settings_boostrap_css', 'wr_pb_settings_auto_check_update' );

			// Get saved options value
			self::$settings = array();

			foreach ( $options as $key ) {
				self::$settings[$key] = get_option( $key, ( $key != 'wr_pb_settings_fullmode' ) ? 'enable' : 'disable' );
			}
		}

		return self::$settings;
	}

	/**
	 * Check/select options.
	 *
	 * @param   string  $value    Current value.
	 * @param   string  $compare  Desired value for checking/selecting option.
	 * @param   string  $check    HTML attribute of checked/selected state.
	 *
	 * @return  void
	 */
	public static function wr_pb_setting_show_check( $value, $compare, $check ) {
		echo esc_attr( ( $value == $compare ) ? "$check='$check'" : '' );
	}

	/**
	 * Setting section callback handler.
	 *
	 * @return  void
	 */
	public static function wr_pb_section_callback() {}

	/**
	 * Render HTML code for `Enable On` field.
	 *
	 * @return  void
	 */
	public static function wr_pb_setting_callback_enable_for() {
		// Get all post types
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		// Prepare post types as field options
		$options = array();

		global $_wp_post_type_features;

		foreach ( $post_types as $slug => $defines ) {
			// Filter supported post type
			if ( 'attachment' != $slug && post_type_supports( $slug, 'editor' ) ) {
				$options[ $slug ] = $defines->labels->name;
			}
		}

		// Get current settings
		$settings = self::wr_pb_settings_options();
		extract( $settings );

		// Render field options
		$first = true;

		foreach ( $options as $slug => $label ) :

		// Prepare checking state
		$checked = '';

		if ( 'enable' == $wr_pb_settings_enable_for ) :
		$checked = 'checked="checked"';
		elseif ( is_array( $wr_pb_settings_enable_for ) && ( ! isset( $wr_pb_settings_enable_for[ $slug ] ) || 'enable' == $wr_pb_settings_enable_for[ $slug ] ) ) :
		$checked = 'checked="checked"';
		endif;

		// Set value based on checking state
		$value = empty( $checked ) ? 'disable' : 'enable';

		if ( ! $first ) :
		echo '<br />';
		endif;
		?>
<label for="wr_pb_settings_enable_for_<?php esc_attr_e( $slug ); ?>"> <input
	type="hidden"
	name="wr_pb_settings_enable_for[<?php esc_attr_e( $slug ); ?>]"
	value="<?php esc_attr_e( $value ); ?>" /> <input
	id="wr_pb_settings_enable_for_<?php esc_attr_e( $slug ); ?>"
	<?php _e( $checked ); ?>
	onclick="jQuery(this).prev().val(this.checked ? 'enable' : 'disable');"
	type="checkbox" autocomplete="off" /> <?php _e( $label ); ?> </label>
	<?php
	$first = false;

	endforeach;
	?>
<p class="description">
<?php _e( 'Uncheck post types where you do not want WR PageBuilder to be activated.', WR_PBL ); ?>
</p>
<?php
	}

	/**
	 * Render HTML code for `Enable Caching` field.
	 *
	 * @return  void
	 */
	public static function wr_pb_setting_callback_cache() {
		$settings = self::wr_pb_settings_options();
		extract( $settings );
		?>
<div>
	<select name="wr_pb_settings_cache">
		<option value="enable"
		<?php selected( $wr_pb_settings_cache, 'enable' ); ?>>
			<?php _e( 'Yes', WR_PBL ); ?>
		</option>
		<option value="disable"
		<?php selected( $wr_pb_settings_cache, 'disable' ); ?>>
			<?php _e( 'No', WR_PBL ); ?>
		</option>
	</select>
	<button class="button button-default"
		data-textchange="<?php _e( 'Done!', WR_PBL ) ?>" id="wr-pb-clear-cache">
		<?php _e( 'Clear cache', WR_PBL ); ?>
		<i class="jsn-icon16 layout-loading jsn-icon-loading"></i>
	</button>
	<span class="hidden layout-message alert"></span>
</div>
<p class="description">
<?php _e( "Select 'Yes' if you want to cache CSS and JS files of WR PageBuilder", WR_PBL ); ?>
</p>
<?php
	}

	public static function wr_pb_setting_callback_fullmode() {
		$settings = self::wr_pb_settings_options();
		extract( $settings );
		?>
<div>
	<select name="wr_pb_settings_fullmode">
		<option value="enable"
		<?php selected( $wr_pb_settings_fullmode, 'enable' ); ?>>
			<?php _e( 'Yes', WR_PBL ); ?>
		</option>
		<option value="disable"
		<?php selected( $wr_pb_settings_fullmode, 'disable' ); ?>>
			<?php _e( 'No', WR_PBL ); ?>
		</option>
	</select>
	<p class="description">
	<?php _e( 'Full Mode is on experiencing period, it could affect to your Post/Page editing performance.', WR_PBL ); ?>
	</p>
</div>
<?php
	}

	/**
	 * Render HTML code for `Load Bootstrap Assets` field.
	 *
	 * @return  void
	 */
	public static function wr_pb_setting_callback_bootstrap() {
		$settings = self::wr_pb_settings_options();
		extract( $settings );
		?>
<label> <input type="checkbox" name="wr_pb_settings_boostrap_js"
	value="enable"
	<?php checked( $wr_pb_settings_boostrap_js, 'enable' ); ?>> <?php _e( 'JS', WR_PBL ); ?>
</label>
<br>
<label> <input type="checkbox" name="wr_pb_settings_boostrap_css"
	value="enable"
	<?php checked( $wr_pb_settings_boostrap_css, 'enable' ); ?>> <?php _e( 'CSS', WR_PBL ); ?>
</label>
<p class="description">
<?php _e( 'You should choose NOT to load Bootstrap CSS / JS if your theme or some other plugin installed on your website already loaded it.', WR_PBL ); ?>
</p>
<?php
	}

	/**
	 * Render HTML code for `Auto Check Update` field.
	 *
	 * @return  void
	 */
	public static function wr_pb_setting_callback_auto_check_update() {
		$settings = self::wr_pb_settings_options();
		extract( $settings );
		?>
<div>
	<select name="wr_pb_settings_auto_check_update">
		<option value="enable"
		<?php selected( $wr_pb_settings_auto_check_update, 'enable' ); ?>>
			<?php _e( 'Yes', WR_PBL ); ?>
		</option>
		<option value="disable"
		<?php selected( $wr_pb_settings_auto_check_update, 'disable' ); ?>>
			<?php _e( 'No', WR_PBL ); ?>
		</option>
	</select>
</div>
<?php
	}
}
