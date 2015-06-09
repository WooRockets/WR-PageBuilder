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
	$saved = ( isset ( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) ? __( 'Settings saved.', WR_PBL ) : __( 'Settings saved.', WR_PBL );

	$msg = $type = '';
	if ( isset ( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) {
		$msg  = __( 'Settings saved.', WR_PBL );
		$type = 'updated';
	} else {
		if ( isset ( $_GET['settings-updated'] ) && $_GET['settings-updated'] != 'true' ) {
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

	<div id="wr-promo-ab">
			<h3>Premium<br>
			WooCommerce Themes</h3>
			<ul>
			<li><span><img src="<?php echo WR_PB_URI; ?>assets/woorockets/images/about-us/excellent-icon.png"></span>Excellent designs</li>
			<li><span><img src="<?php echo WR_PB_URI; ?>assets/woorockets/images/about-us/unlimited-icon.png"></span>Unlimited customization ability</li>
			<li><span><img src="<?php echo WR_PB_URI; ?>assets/woorockets/images/about-us/additional-icon.png"></span>Additional eCommerce features</li>
			</ul>
			<p class="btn-premium"><a href="http://www.woorockets.com/themes/?utm_source=PageBuilder&utm_medium=Setting&utm_campaign=Cross%20Promo%20Banner" target="_blank"><strong>View the collection now</strong><br>
			<span>And learn how our themes can boost your business!</span></a></p>
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
		.wr-banner-wrapper .wr-banner { float: left; line-height: 0; margin: 0px 10px 0px 10px; }
		.wr-banner-l a{
			text-decoration: none;
		}
		.wr-banner-l img{
			margin-right: 10px;
		}
		.wr-accordion { border: 1px solid #E5E5E5; margin-top: 20px; }
		.wr-accordion-title { margin: 0; padding: 8px 20px; cursor: pointer; background: #C3C3C3; }
		.wr-accordion-content { padding: 0; border-top: 1px solid #E5E5E5; line-height: 0; display: none; }

		/*** Premium ***/
		#wr-promo-ab {
		  background: url(' . WR_PB_URI . 'assets/woorockets/images/about-us/bg-wr-promo.jpg) center top no-repeat;
		  background-size: auto 100%;
		  text-align: center;
		  overflow: hidden;
		  font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
		  width: 1030px;
  		  margin-top: 20px;
		}
		#wr-promo-ab h3 {
		    margin: 70px 0 30px;
		    color: #fff;
		    font-size: 32px;
		    font-weight: bold;
		    line-height: 1.1;
		}
		#wr-promo-ab ul {
		    margin: 0 10px 25px 10px;
		    padding: 0;
		    list-style: none;
		    color: #6c7885;
		}
		#wr-promo-ab li {
		    display: inline-block;
		    line-height: 31px;
		    margin: 0 5px 10px;
		}
		#wr-promo-ab li span {
		    background: #6c7886;
		    float: left;
		    border-radius: 50%;
		    -o-border-radius: 50%;
		    -ms-border-radius: 50%;
		    -moz-border-radius: 50%;
		    -webkit-border-radius: 50%;
		    margin: 0 5px 0 0;
		}
		#wr-promo-ab li img {
		    margin: 8px;
		    float: left !important;
		}
		#wr-promo-ab .btn-premium {
		    margin: 0 0 60px 0;
		}
		#wr-promo-ab .btn-premium a {
		    display: inline-block;   
		    margin: 0;
		    background: #418858;
		    color: #fff;
		    padding: 10px 25px;
		    border-radius: 3px;
		    -o-border-radius: 3px;
		    -ms-border-radius: 3px;
		    -moz-border-radius: 3px;
		    -webkit-border-radius: 3px;
		    font-size: 11px;
		    box-shadow: 0 4px 0 0 #2a6d40;
		    -o-box-shadow: 0 4px 0 0 #2a6d40;
		    -ms-box-shadow: 0 4px 0 0 #2a6d40;
		    -moz-box-shadow: 0 4px 0 0 #2a6d40;
		    -webkit-box-shadow: 0 4px 0 0 #2a6d40;
		    text-decoration: none;
		    transition: all 0.3s;
		    -o-transition: all 0.3s;
		    -ms-transition: all 0.3s;
		    -moz-transition: all 0.3s;
		    -webkit-transition: all 0.3s;
		}
		#wr-promo-ab .btn-premium strong {
		    font-size: 18px;
		}
		#wr-promo-ab .btn-premium a:hover {
		    background: #2a6d40;
		    text-decoration:none;
		    box-shadow: 0 4px 0 0 #418858;
		    -o-box-shadow: 0 4px 0 0 #418858;
		    -ms-box-shadow: 0 4px 0 0 #418858;
		    -moz-box-shadow: 0 4px 0 0 #418858;
		    -webkit-box-shadow: 0 4px 0 0 #418858;
		}

		@media only screen and (max-width: 1232px), (max-device-width: 1232px) {
			#wr-promo-ab {
				width:100%
			}
		}

		@media only screen and (max-width: 768px), (max-device-width: 768px) {
		  #wr-promo-ab ul {
		    width: 270px;
		    margin-right: auto;
		    margin-left: auto;
		  }
		  #wr-promo-ab ul li {
		    display: block;
		    text-align: left;
		    margin-left: 0;
		    margin-bottom: 20px;
		  }
		}

        ';
WR_Pb_Init_Assets::inline( 'css', $style );
