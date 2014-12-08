<?php
/**
 * Main class for add on
 *
 * Define properties & methods
 *
 * @author         WooRockets Team <support@www.woorockets.com>
 * @package        IGPGBLDR
 * @version        $Id$
 */
class WR_Pb_Addon {

	// prodiver name
	protected $provider;

	// register assets (js/css)
	protected $assets_register;

	// enqueue assets for Admin pages
	protected $assets_enqueue_admin;

	// enqueue assets for Modal setting iframe
	protected $assets_enqueue_modal;

	// enqueue assets for Frontend
	protected $assets_enqueue_frontend;

	/**
	 * Get provider data
	 *
	 * @return type
	 */
	public function get_provider() {
		return $this->provider;
	}

	/**
	 * Get provider assets path & uri
	 *
	 * @return type
	 */
	public function get_assets_register() {
		return $this->assets_register;
	}

	/**
	 * Get custom enqueued assets for WP admin
	 *
	 * @return type
	 */
	public function get_assets_enqueue_admin() {
		return $this->assets_enqueue_admin;
	}

	/**
	 * Get custom enqueued assets for WR modal
	 *
	 * @return type
	 */
	public function get_assets_enqueue_modal() {
		return $this->assets_enqueue_modal;
	}

	/**
	 * Get custom enqueued assets for Front end
	 *
	 * @return type
	 */
	public function get_assets_enqueue_frontend() {
		return $this->assets_enqueue_frontend;
	}

	/**
	 * Set provider data
	 *
	 * @param array $provider
	 */
	public function set_provider( $provider ) {
		$this->provider = $provider;
	}

	/**
	 * Register custom assets
	 *
	 * @param array $assets
	 */
	public function set_assets_register( $assets ) {
		$this->assets_register = $assets;
	}

	/**
	 * Add custom assets for WP admin
	 *
	 * @param array $assets
	 */
	public function set_assets_enqueue_admin( $assets ) {
		$this->assets_enqueue_admin = $assets;
	}

	/**
	 * Add custom assets for WR modal
	 *
	 * @param array $assets
	 */
	public function set_assets_enqueue_modal( $assets ) {
		$this->assets_enqueue_modal = $assets;
	}

	/**
	 * Add custom assets for WP frontend
	 *
	 * @param array $assets
	 */
	public function set_assets_enqueue_frontend( $assets ) {
		$this->assets_enqueue_frontend = $assets;
	}

	/**
	 * Initialize addon
	 */
	public function __construct() {
		add_filter( 'wr_pb_provider', array( &$this, 'get_provider_data' ) );
		add_filter( 'wr_pb_register_assets', array( &$this, 'register_assets_register' ) );
		add_filter( 'wr_pb_assets_enqueue_admin', array( &$this, 'enqueue_assets_admin' ) );
		add_filter( 'wr_pb_assets_enqueue_modal', array( &$this, 'enqueue_assets_modal' ) );
		add_filter( 'wr_pb_assets_enqueue_frontend', array( &$this, 'enqueue_assets_frontend' ) );
	}

	/**
	 * Get provider data and return necessary information
	 *
	 * @param array $providers
	 *
	 * @return string
	 */
	public function get_provider_data( $providers ) {

		// get provider data
		$provider = $this->get_provider();

		if ( empty ( $provider ) || empty ( $provider['file'] ) ) {
			return $providers;
		}

		// variables
		$file             = $provider['file'];
		$path             = plugin_dir_path( $file );
		$uri              = plugin_dir_url( $file );
		$shortcode_dir    = empty ( $provider['shortcode_dir'] ) ? 'shortcodes' : $provider['shortcode_dir'];
		$js_shortcode_dir = empty ( $provider['js_shortcode_dir'] ) ? 'assets/js/shortcodes' : $provider['js_shortcode_dir'];

		// Check if path is absolute
		if ( ! is_dir( $shortcode_dir ) ) {
			$shortcode_dir = $path . $shortcode_dir;
		}

		//get plugin name & main file
		$main_file        = pathinfo( $file );
		$folder           = basename( $main_file['dirname'] );
		$main_file        = $folder . '/' . $main_file['basename'];
		$providers[$path] = array(
			'path'             => $path,
			'uri'              => $uri,
			'file'             => $main_file,
			'file_path'        => $file,
			'folder'           => $folder,
			'name'             => $provider['name'],
			'shortcode_dir'    => $shortcode_dir,
			'js_shortcode_dir' => array( 'path' => $path . $js_shortcode_dir, 'uri' => $uri . $js_shortcode_dir ),
		);

		return $providers;
	}

	/**
	 * Register custom assets
	 *
	 * @param array $assets
	 *
	 * @return array
	 */
	public function register_assets_register( $assets ) {
		$this_asset = $this->get_assets_register();
		$assets     = array_merge( $assets, empty ( $this_asset ) ? array() : $this_asset );

		return $assets;
	}

	/**
	 * Register custom assets for WP admin
	 *
	 * @param array $assets
	 *
	 * @return array
	 */
	public function enqueue_assets_admin( $assets ) {
		$this_asset = $this->get_assets_enqueue_admin();
		$assets     = array_merge( $assets, empty ( $this_asset ) ? array() : $this_asset );

		return $assets;
	}

	/**
	 * Register custom assets for WR modal
	 *
	 * @param array $assets
	 *
	 * @return array
	 */
	public function enqueue_assets_modal( $assets ) {
		$this_asset = $this->get_assets_enqueue_modal();
		$assets     = array_merge( $assets, empty ( $this_asset ) ? array() : $this_asset );

		return $assets;
	}

	/**
	 * Register custom assets for WP frontend
	 *
	 * @param array $assets
	 *
	 * @return array
	 */
	public function enqueue_assets_frontend( $assets ) {
		$this_asset = $this->get_assets_enqueue_frontend();
		$assets     = array_merge( $assets, empty ( $this_asset ) ? array() : $this_asset );

		return $assets;
	}

	/**
	 * Register Path to extended Parameter type
	 *
	 * @param string $path
	 */
	public function register_extended_parameter_path( $path ) {
		WR_Pb_Loader::register( $path, 'WR_Pb_Helper_Html_' );
	}

	/**
	 * Show admin notice
	 *
	 * @param string $addon_name
	 * @param string $core_required
	 *
	 * @return string
	 */
	static function show_notice( $data, $action, $type = 'error' ) {

		// show message
		ob_start();

		switch ( $action ) {

			// show message about core version required
			case 'core_required':
				extract( $data );

				?>
<div class="<?php echo esc_attr( $type ); ?>">
	<p>
	<?php _e( "You can not activate this WR PageBuilder's provider:", WR_PBL ); ?>
		<br> <b><?php echo esc_html( $addon_name ); ?> </b>
	</p>

	<p>
	<?php _e( "It requires WR PageBuilder's version:", WR_PBL ); ?>
		<br> <b><?php echo esc_html( $core_required ); ?> </b> <br>
		<?php echo esc_html( 'or above to work. Please update WR PageBuilder to newest version.' ); ?>
		<br>
	</p>
</div>

<!-- custom js to hide "Plugin actived" -->

		<?php
		$js_code = "$('#message.updated').hide();";
		echo balanceTags( WR_Pb_Helper_Functions::script_box( $js_code ) );

		break;

default:
	break;
		}

		$message = ob_get_clean();

		return $message;
	}

	/**
	 * Get Constant name defines core version required of this addon plugin
	 *
	 * @param string $addon_file
	 */
	static function core_version_constant( $addon_file ) {
		$path_parts = pathinfo( $addon_file );
		if ( $path_parts ) {
			// get dir name of add on
			$dirname = basename( $path_parts['dirname'] );
			$dirname = str_replace( '-', '_', $dirname );

			// return the Constant defines core version required of this add on
			return strtoupper( $dirname ) . '_CORE_VERSION';
		}

		return '';
	}

	/**
	 * Get Constant value of Constant defines core version required
	 *
	 * @param array  $provider
	 * @param string $addon_file
	 *
	 * @return string
	 */
	static function core_version_requied_value( $provider, $addon_file ) {

		// include defines.php from provider plugin folder, where defines core version required by addon
		if ( file_exists( $provider['path'] . 'defines.php' ) ) {
			include_once $provider['path'] . 'defines.php';
		}

		// get constant name defines core version required
		$constant = WR_Pb_Addon::core_version_constant( $addon_file );

		// get value of core version required
		return ( defined( $constant ) ) ? constant( $constant ) : NULL;
	}

	/**
	 * Check compatibility of this addon & WR core
	 *
	 * @param string $core_required
	 * @param string $core_version
	 * @param string $addon_file
	 *
	 * @return bool
	 */
	static function compatibility_handle( $core_required, $core_version, $addon_file ) {

		// if current core version < core version required
		if ( version_compare( $core_required, $core_version, '>' ) ) {

			// deactivate addon
			deactivate_plugins( array( $addon_file ) );

			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Custom function when activate an Addon plugin
	 */
	public static function activation_hook() {
		do_action( 'reload_wr_shortcodes' );
	}

	/**
	 * Custom function when update an Addon plugin
	 */
	public static function update_hook( $plugin_file ) {

		// Get plugin_directory/main_file
		$main_file        = pathinfo( $plugin_file );
		$addon_folder           = basename( $main_file['dirname'] );

		// Get current version of addon
		$addon_version = WR_Pb_Helper_Functions::get_plugin_info( $plugin_file, 'Version' );

		// Get stored version of addon
		$option_name = 'wr_addon_' . $addon_folder . '_version';
		$addon_version_old = get_option( $option_name );

		// If version is changed, updated
		if ( version_compare( $addon_version, $addon_version_old ) != 0 ) {
			do_action( 'reload_wr_shortcodes' );

			// Update version
			update_option( $option_name, $addon_version );
		}
	}

	/**
	 * Custom function when deactivate an Addon plugin
	 */
	public static function deactivation_hook() {
		do_action( 'reload_wr_shortcodes' );
	}

	/**
	 * Custom function when uninstall an Addon plugin
	 */
	public static function uninstall_hook() {
		do_action( 'reload_wr_shortcodes' );
	}

}