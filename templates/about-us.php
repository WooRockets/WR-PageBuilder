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
 * @todo : WR PageBuilder About page
 */
?>
<div class="wrap">
	<div class="jsn-bootstrap3">
		<h2><strong><?php esc_html_e( 'Welcome to WR Page Builder', WR_PBL ); ?></strong></h2>
		<div class="wr-pb-button-bar">
			<a class="btn btn-info wr-pb-button" href="<?php echo admin_url( 'admin.php?page=wr-pb-settings' ); ?>"><?php _e( 'Settings', WR_PBL ); ?></a>
			<a class="btn btn-info wr-pb-button" href="http://bit.ly/wrpb-about-docs" target="_blank"><?php _e( 'Docs', WR_PBL ); ?></a>
			<a href="https://twitter.com/WooRockets" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @WooRockets</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			<span class="wr-plugin-version"><?php _e( 'Version', WR_PBL ); ?> <?php $plugin_data = get_plugin_data( WR_PB_FILE ); echo $plugin_data['Version']; ?>. <?php _e( 'Follow us to get latest updates!', WR_PBL ); ?></span>
		</div>
		<p><?php _e( 'Thank you for installing! <strong>WR Page Builder by WooRockets</strong> is clear, simple and extremely easy to use. Designed with an intuitive interface, <strong>WR Page Builder</strong> gives you new experience with visual drag-and-drop functionality when building a WordPress site.', WR_PBL ); ?></p>
		<div role="tabpanel">
			<ul class="nav nav-tabs wr-pb-tabs" role="tablist">
				<li role="presentation" class="active">
					<a href="#hot-features" aria-controls="hot-features" role="tab" data-toggle="tab"><?php _e( 'Hot Features', WR_PBL ); ?></a>
				</li>
				<li role="presentation">
					<a href="#for-developers" aria-controls="for-developers" role="tab" data-toggle="tab"><?php _e( 'For Developers', WR_PBL ); ?></a>
				</li>
				<li role="presentation">
					<a href="#for-translators" aria-controls="for-translators" role="tab" data-toggle="tab"><?php _e( 'For Translators', WR_PBL ); ?></a>
				</li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="hot-features">
					<div class="feature-block">
						<h3><?php _e( 'Intuitive Layout', WR_PBL ); ?></h3>
						<p><?php _e( 'Once installed, it’s located next to the default WordPress Editor. To help you easily create your pages/posts, <strong>WR Page Builder</strong> is designed with a simple and intuitive layout. You can easily switch between compact mode and full preview mode to preview everything you have created without going back to the front-end.', WR_PBL ); ?></p>
					</div>
					<div class="feature-block">
						<h3><?php _e( 'Drag and Drop Layout', WR_PBL ); ?></h3>
						<p><?php _e( 'Drag and drop is a convenient functionality for creating a page or a post. You can easily arrange columns, move page elements or widgets into another position and even resize columns using just your mouse. You can also use the “Move button” on the sidebar to move rows up or down.', WR_PBL ); ?></p>
					</div>
					<div class="feature-block">
						<h3><?php _e( 'Advanced Page Elements (Built-in Shortcodes)', WR_PBL ); ?></h3>
						<p><?php _e( 'We have created many advanced page elements, such as: Pricing Tables, QR codes, Testimonials, Tables… You can choose the most suitable field and add as many elements as you want for your site without any coding. Interestingly, you can easily search these elements with the Spotlight Filter.', WR_PBL ); ?></p>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="for-developers">
					<p><?php _e( 'If you are going to build Add-on for WR Page Builder, this document is made for you. This part includes a knowledge base about WR Page Builder, some basic APIs, and a tutorial to make a simple Add-on with a simple element.', WR_PBL ); ?></p>
					<a class="btn btn-info wr-pb-button" href="http://bit.ly/wrpb-about-docs-for-dev" target="_blank"><?php _e( 'Docs for Developers', WR_PBL ); ?></a>
					<p><?php _e( 'Get our Source Code at Github!', WR_PBL ); ?></p>
					<a class="btn btn-info wr-pb-button" href="http://bit.ly/wrpb-about-github" target="_blank"><?php _e( 'Source Code', WR_PBL ); ?></a>
					<p><?php _e( 'Having any exciting ideas or improvements for WR Page Builder to grow our WordPress Community? Drop an email to our WooRockets Astronaut Tony at', WR_PBL ); ?> <a href="mailto:tony@woorockets.com">tony@woorockets.com</a>!</p>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="for-translators">
					<p><?php _e( 'If you are reading this, we need your contribution! We appreciate all kinds of support for Translating WR Page Builder into your language!', WR_PBL ); ?> <a href="http://bit.ly/wpdb-about-transifex" target="_blank"><?php _e( 'Translate WR Page Builder', WR_PBL ); ?></a>.</p>
					<p><?php _e( 'Our awesome translators', WR_PBL ); ?>:</p>
					<p class="translators-list">
						<a href="https://www.transifex.com/accounts/profile/kihoshin/" target="_blank">kihoshin</a>,
						<a href="https://www.transifex.com/accounts/profile/KevU/" target="_blank">KevU</a>,
						<a href="https://www.transifex.com/accounts/profile/hienntt/" target="_blank">hienntt</a>
					</p>
				</div>
			</div>
		</div>
		<br />
		<br />
		<div class="wr-banner-wrapper">
			<h3><?php _e( 'See our other awesomeness', WR_PBL ); ?></h3>
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
</div>

<?php
// Load inline style
$style = '
	.jsn-bootstrap3 { max-width: 1148px; }
	.jsn-bootstrap3 .wr-pb-tabs li a { font-size: 16px; }
	.jsn-bootstrap3 p { font-size: 14px; text-align: justify; }
	.jsn-bootstrap3 .wr-pb-button { vertical-align: top !important; padding: 3px 12px !important; margin-right: 5px; margin-bottom: 10px !important; }
	.jsn-bootstrap3 .wr-pb-button-bar { margin-bottom: 10px; }
	.jsn-bootstrap3 h3 { font-size: 20px !important; font-weight: bold !important; }
	.jsn-bootstrap3 #hot-features p { padding-left: 20px; }
	.jsn-bootstrap3 #hot-features .feature-block { background: #fff; border-radius: 8px; padding: 1px 20px 10px 20px; margin-top: 10px; }
	.jsn-bootstrap3 #for-developers > p:first-child, .jsn-bootstrap3 #for-translators > p:first-child { margin-top: 20px; }
	.jsn-bootstrap3 .translators-list a { text-decoration: underline; }
	.wr-banner-wrapper .wr-banner { float: left; line-height: 0; margin: 0px 10px 0px 10px; }
	.wr-banner-l a { text-decoration: none; }
	.wr-banner-l img { margin-right: 10px; }
	.wr-plugin-version { display: inline-block; vertical-align: top; margin: 5px 0px 0px 5px; font-size: 14px; }
';
WR_Pb_Init_Assets::inline( 'css', $style );
