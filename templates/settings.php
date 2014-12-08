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
 * @todo : WR PageBuilder Settings page
 */
?>
<div class="wrap">

	<h2>
	<?php esc_html_e( 'WR PageBuilder Settings', WR_PBL ); ?>
	</h2>

	<?php
	// Show message when save
	$saved = ( isset ( $_GET ) && $_GET['settings-updated'] == 'true' ) ? __( 'Settings saved.', WR_PBL ) : __( 'Settings saved.', WR_PBL );

	$msg = $type = '';
	if ( isset ( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) {
		$msg  = __( 'Settings saved.', WR_PBL );
		$type = 'updated';
	} else {
		if ( $_GET['settings-updated'] != 'true' ) {
			$msg  = __( 'Settings is not saved.', WR_PBL );
			$type = 'error';
		}
	}

	if ( isset ( $_GET['settings-updated'] ) ) {
		?>
	<div id="setting-error-settings_updated"
		class="<?php echo esc_attr( $type ); ?> settings-error">
		<p>
			<strong><?php echo esc_html( $msg ); ?> </strong>
		</p>
	</div>
	<?php
	}


	$options = array( 'wr_pb_settings_cache', 'wr_pb_settings_boostrap_js', 'wr_pb_settings_boostrap_css' );
	// submit handle
	if ( ! empty ( $_POST ) ) {
		foreach ( $options as $key ) {
			$value = ! empty( $_POST[$key] ) ? 'enable' : 'disable';
			update_option( $key, $value );
		}

		unset( $_POST );
		WR_Pb_Helper_Functions::alert_msg( array( 'success', __( 'Your settings are saved successfully', WR_PBL ) ) );
	}
	// get saved options value
	foreach ( $options as $key ) {
		$$key = get_option( $key, 'enable' );
	}

	// show options form
	?>
	<form method="POST" action="options.php">
	<?php
	$page = 'wr-pb-settings';
	settings_fields( $page );
	do_settings_sections( $page );
	submit_button();
	?>
	</form>
	<div class="wr-banner-wrapper">
		<h3>See our other awesomeness</h3>
		<div class="wr-banner-l">
			<a href="http://www.woorockets.com/plugins/wr-megamenu/?utm_source=PageBuilder%20Setting&utm_medium=banner&utm_campaign=Cross%20Promo%20Plugins" target="_blank">
				<img src="<?php echo WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/banners/MegaMenu_S.jpg'; ?>" alt="WR Mega Menu" />
			</a>
			<a href="http://www.woorockets.com/plugins/wr-contactform/?utm_source=PageBuilder%20Setting&utm_medium=banner&utm_campaign=Cross%20Promo%20Plugins" target="_blank">
				<img src="<?php echo WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/banners/ContactForm_S.jpg'; ?>" alt="WR Contact Form" />
			</a>
			<a href="http://www.woorockets.com/themes/corsa/?utm_source=PageBuilder%20Setting&utm_medium=banner&utm_campaign=Cross%20Promo%20Plugins" target="_blank">
				<img src="<?php echo WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/banners/Corsa_S.jpg'; ?>" alt="WR Corsa" />
			</a>
		</div>
	</div>
</div>

	<?php
	// Load inline script initialization
	$script = '
		new WR_Pb_Settings({
			ajaxurl: "' . admin_url( 'admin-ajax.php' ) . '",
			_nonce: "' . wp_create_nonce( WR_NONCE ) . '",
			button: "wr-pb-clear-cache",
			button: "wr-pb-clear-cache",
			loading: "#wr-pb-clear-cache .layout-loading",
			message: $("#wr-pb-clear-cache").parent().find(".layout-message"),
		});
        ';

WR_Pb_Init_Assets::inline( 'js', $script );

	// Load inlide style
	$loading_img = WR_PB_URI . '/assets/woorockets/images/icons-16/icon-16-loading-circle.gif';
	$style = '
		.jsn-bootstrap3 { margin-top: 30px; }
        .jsn-bootstrap3 .checkbox { background:#fff; }
        #wr-pb-clear-cache, .layout-message { margin-left: 6px; }
        .jsn-icon-loading { background: url("' . $loading_img . '") no-repeat scroll left center; content: " "; display: none; height: 16px; width: 16px; float: right; margin-left: 20px; margin-top: -26px; padding-top: 10px; }
<<<<<<< Updated upstream
		.wr-banner-wrapper .wr-banner { float: left; line-height: 0; margin: 0px 10px 0px 10px; }
=======
		.wr-banner-l a{
			text-decoration: none;
		}
		.wr-banner-l img{
			margin-right: 10px;
		}
		.wr-accordion { border: 1px solid #E5E5E5; margin-top: 20px; }
		.wr-accordion-title { margin: 0; padding: 8px 20px; cursor: pointer; background: #C3C3C3; }
		.wr-accordion-content { padding: 0; border-top: 1px solid #E5E5E5; line-height: 0; display: none; }
>>>>>>> Stashed changes
        ';
WR_Pb_Init_Assets::inline( 'css', $style );
