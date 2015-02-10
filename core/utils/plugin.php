<?php
/**
 * WR PageBuilder Activate & Deactivate
 *
 * Show confirmation page before doing deactivation or go back
 *
 * @author         WooRockets Team <support@www.woorockets.com>
 * @package        IGPGBLDR
 * @version        $Id$
 */
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class WR_Pb_Utils_Plugin {

	function __construct() {
		// first-time activation
		register_activation_hook( WR_PB_FILE, array( &$this, 'do_activate' ) );

		// update plugin
		add_action( 'admin_init', array( &$this, 'do_activate' ), 100 );

		// deactivate plugin
		register_deactivation_hook( WR_PB_FILE, array( &$this, 'do_deactivate' ) );

		// bulk deactivate plugins
		add_action( 'admin_init', array( &$this, 'do_deactivate' ) );
	}

	/**
	 * Activate handle
	 */
	function do_activate() {
		// get current version of plugin
		$latest_version = WR_Pb_Helper_Functions::get_plugin_info( WR_PB_FILE, 'Version' );

		// get previous version of plugin
		$old_version = get_transient( 'wr_pb_version' );

		// compare version
		if ( ! $old_version || version_compare( $old_version, $latest_version, '<' ) ) {
			// update plugin version
			set_transient( 'wr_pb_version', $latest_version );

			// remove cache folder if plugin is installed before
			if ( $old_version ) {
				WR_Pb_Utils_Common::remove_cache_folder();
			}
		}
		// remove free shortcode directory
		if ( is_dir( WP_PLUGIN_DIR . '/wr-shortcodes-free' ) ) {
			delete_plugins( array( 'wr-shortcodes-free/main.php' ) );
		}
	}

	/**
	 * Deactivate handle
	 *
	 * @global type $pagenow
	 * @global type $wpdb
	 */
	function do_deactivate() {
		global $pagenow;
		if ( $pagenow == 'plugins.php' ) {
			$deactivate_action = false;
			$wr_pb_plugin      = 'wr-pagebuilder/wr-pagebuilder.php';

			// check if single deactivate plugin/ bulk deactivate plugins
			if ( ! empty( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], array( 'deactivate-selected', 'deactivate' ) ) ) {
				$action = $_REQUEST['action'];
				if ( ( $action == 'deactivate' && $_REQUEST['plugin'] == $wr_pb_plugin ) || ( $action == 'deactivate-selected' && in_array( $wr_pb_plugin, $_REQUEST['checked'] ) ) ) {
					$deactivate_action = true;
				}
			}

			if ( $deactivate_action ) {
				$wr_action  = 'wr_deactivate';
				if( is_network_admin() ){
				    $plugin_url = network_admin_url( 'plugins.php' );
				} else {
				    $plugin_url = admin_url( 'plugins.php' );
				}
				// check whether delete only WR PageBuilder OR Bulk deactivate plugins
				$deactivate_one = isset( $_POST['action'] ) ? false : true;

				// show Confirmation form before doing deactivate
				if ( ! isset( $_REQUEST['wr_wpnonce'] ) && ! isset( $_REQUEST['wr_back'] ) ) {
					// create wr_nonce
					$wr_nonce = wp_create_nonce( $wr_action );
					$method   = $deactivate_one ? 'GET' : 'POST';

					$back_text = __( 'No, take me back', WR_PBL );
					if ( $deactivate_one ) {
						$back_btn = "<a href='$plugin_url' class='button button-large'>" . $back_text . '</a>';
					} else {
						$back_btn = "<input type='submit' name='wr_back' class='button button-large' value='" . $back_text . "'>";
					}
					$form   = " action='{$plugin_url}' method='$method' ";
					$fields = '';

					foreach ( $_REQUEST as $key => $value ) {
						if ( ! is_array( $value ) ) {
							$fields .= "<input type='hidden' name='$key' value='$value' />";
						} else {
							foreach ( $value as $p ) {
								$fields .= "<input type='hidden' name='{$key}[]' value='$p' />";
							}
						}
					}
					$fields .= "<input type='hidden' name='wr_wpnonce' value='$wr_nonce' />";
					// show message
					ob_start();
					?>
<p>
<?php _e( 'After deactivating, all content built with PageBuilder will be parsed to plain HTML code. Are you sure you want to deactivate PageBuilder plugin?', WR_PBL ); ?>
</p>
<center>
	<form <?php echo balanceTags( $form ); ?>>
	<?php echo balanceTags( $fields ); ?>
		<input type="submit" name="wr_deactivate" class="button button-large"
			value="<?php _e( 'Yes, deactivate plugin', WR_PBL ); ?>"
			style="background: #d9534f; color: #fff; text-shadow: none; border: none;">
			<?php echo balanceTags( $back_btn ); ?>
	</form>
</center>
<p style="font-style: italic; font-size: 12px; margin-top: 20px;">
<?php _e( "Or if you want to deactivate without parsing 'content built with PageBuilder' to HTML code, click on the button below", WR_PBL ); ?>
</p>
<center>
	<form <?php echo balanceTags( $form ); ?>>
	<?php echo balanceTags( $fields ); ?>
		<input type="submit" name="wr_deactivate_light"
			class="button button-large"
			value="<?php _e( 'Deactivate without parsing data', WR_PBL ); ?>"
			style="background: #f0ad4e; color: #fff; text-shadow: none; border: none;">
	</form>
</center>
	<?php
	$message = ob_get_clean();
	// Change page title
	_default_wp_die_handler( $message, __( 'WordPress &rsaquo; Confirmation', WR_PBL ) );

	exit;
				} // Do deactivate after confirmation
				else {
					// get nonce
					$wr_nonce = esc_sql( $_REQUEST['wr_wpnonce'] );
					$nonce    = wp_verify_nonce( $wr_nonce, $wr_action );

					// if nonce is invalid
					if ( ! in_array( $nonce, array( 1, 2 ) ) ) {
						_default_wp_die_handler( __( 'Nonce is invalid!', WR_PBL ) );
						exit;
					}

					// do action when customer choose "take me back" in confirmation form
					if ( isset( $_REQUEST['wr_back'] ) ) {
						// remove WR PageBuilder from the checked list
						if ( ( $key = array_search( $wr_pb_plugin, $_REQUEST['checked'] ) ) !== false ) {
							unset( $_REQUEST['checked'][$key] );
						}

						// Overwrite list of checked plugins to deactivating
						$_POST['checked'] = $_REQUEST['checked'];
					} // deactivate WR PageBuilder & parsing content
					else {
						if ( isset( $_REQUEST['wr_deactivate'] ) ) {
							global $wpdb;
							// update post content = value of '_wr_html_content', deactivate pagebuilder
							$meta_key1 = 1;
							$meta_key2 = '_wr_html_content';
							$meta_key3 = '_wr_deactivate_pb';
							$wpdb->query(
							$wpdb->prepare(
									"
								UPDATE		$wpdb->posts p
								LEFT JOIN	$wpdb->postmeta p1
											ON p1.post_id = p.ID
								LEFT JOIN	$wpdb->postmeta p2
											ON p2.post_id = p.ID
								SET			post_content = p1.meta_value, p2.meta_value = %d
								WHERE		p1.meta_key = %s
											AND p2.meta_key = %s
								",
							$meta_key1,
							$meta_key2,
							$meta_key3
							)
							);

							// update post content = value of '_wr_html_content', deactivate pagebuilder all blog
							if( is_network_admin() ){
								// get list id blog all
								$list_prefix_musite = $wpdb->get_results(
									"SELECT blog_id FROM $wpdb->blogs",
									ARRAY_A
								);

								if($list_prefix_musite && count($list_prefix_musite) > 1){
									foreach ($list_prefix_musite as $key => $value) {
										if ($value['blog_id'] == 1) continue;
										
										$prefix = $wpdb->prefix.$value['blog_id'].'_';
										$wpdb->query(
											$wpdb->prepare(
												"
												UPDATE		{$prefix}posts p
												LEFT JOIN	{$prefix}postmeta p1
															ON p1.post_id = p.ID
												LEFT JOIN	{$prefix}postmeta p2
															ON p2.post_id = p.ID
												SET			post_content = p1.meta_value, p2.meta_value = %d
												WHERE		p1.meta_key = %s
															AND p2.meta_key = %s
												",
												$meta_key1,
												$meta_key2,
												$meta_key3
											)
										);
									}
								}
							}

							// delete pagebuilder content
							WR_Pb_Utils_Common::delete_meta_key( array( '_wr_page_builder_content', '_wr_page_active_tab' ) );

							do_action( 'wr_pb_deactivate' );
						}
					}
				}
			}
		}
	}
}