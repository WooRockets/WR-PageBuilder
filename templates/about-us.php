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

	wp_enqueue_style( 'wr-pb_about_us', WR_PB_URI . 'assets/woorockets/css/about-us.css' );

	// Get array list of dismissed pointers for current user and convert it to array
	$dismissed_pointers_thank = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

	if( !in_array( 'wr_pb_settings_pointer_pagebuilder_thank_installing', $dismissed_pointers_thank ) ){
		// Load inline style
		$style = '
			html.wp-toolbar{padding-top: 102px; }
			#wpadminbar{top:70px; }
			#wr-header{position: fixed; top: 0; width: 100%; left: 0; background: #0074a2; height: 70px; z-index: 1; }
			#wr-header .wr-logoheader{float: left; height: 100%; border-right: 1px solid #0080b1; background: #005d82; margin: 0 15px 0 0; }
			#wr-header .wr-logoheader img{margin: 13px 10px 0; }
			#wr-header p{font-size: 14px; color: #FFF; padding: 0 50px 0 0; display: table-cell; height: 70px; vertical-align: middle; }
			#wr-header p a{color: #6BD8FF; text-decoration: none; }
			#wr-header p a:hover{text-decoration: underline; color: #C1EFFF; }
			#wr-header #close-header{float: right; margin: -47px 20px 0 0; font-size: 28px; color: rgba(0,0,0,0.3); cursor: pointer; }
			#wr-header #close-header:hover{color: rgba(0,0,0,1); }
			@media screen and (max-width:600px){
				#wr-header {height: 172px; }
			}
		';
		WR_Pb_Init_Assets::inline( 'css', $style );

?>

		<div id="wr-header">
			<a class="wr-logoheader" target="_blank" href="http://www.woorockets.com/?utm_source=PageBuilder%20About&utm_medium=top%20logo&utm_campaign=Cross%20Promo%20Plugins"><img src="<?php echo WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/about-us/logo-header.png'; ?>" alt="woorockets.com" /></a>
			<p><?php printf(__('Thank you for installing WR Page Builder from WooRockets Team! We are making new hi-quality themes and plugins for you ;) Follow us on <a href="%s" target="_blank" >Twitter</a> or <a href="%s" target="_blank" >Subscribe</a> to our email list and be the first to get updated.', WR_PBL ) , 'http://bit.ly/wr-freebie-twitter', 'http://www.woorockets.com/?utm_source=PageBuilder%20About&utm_medium=banner-link&utm_campaign=Cross%20Promo%20Plugins#subscribe'); ?></p>
			<span id="close-header" class="dashicons dashicons-no"></span>
		</div>

		<script type="text/javascript">
			jQuery(document).ready( function($) {
				$("#wr-header #close-header").click(function(){

					$.post( ajaxurl, {
						pointer: "wr_pb_settings_pointer_pagebuilder_thank_installing", // pointer ID
						action: "dismiss-wp-pointer"
					});

					$("#wr-header").hide();
					$("html.wp-toolbar").css({'padding-top' : '32px'});
					$("#wpadminbar").css({'top' : 0});
					
				})
			});
		</script>

<?php 
	}
?>

<div class="wr-wrap">
	<div id="wr-about">
		<div class="logo-about"><img src="<?php echo WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/about-us/logo.png'; ?>" /></div>
		<div class="content-about">
			<h1>About WR Page Builder</h1>
			<div class="description">
				<p><?php _e( '<strong>WR Page Builder</strong> is the easiest page builder for WordPress and a totally free WordPress page builder plugin for everyone. It is a simple drag’n’drop plugin that helps you build a complete WordPress page within few minutes without any coding knowledge required.', WR_PBL ); ?></p>
			</div>
			<div class="info">
				<strong class="version"><?php _e( 'Current version', WR_PBL ); ?>: <?php $plugin_data = get_plugin_data( WR_PB_FILE ); echo $plugin_data['Version']; ?> (<a target="_blank" href="http://bit.ly/wrpb-about-changelog"><?php _e( 'Change log', WR_PBL ); ?></a>)</strong>
				<p><?php _e( 'Follow us to get latest updates', WR_PBL ); ?>!</p>
				<a href="https://twitter.com/WooRockets" class="twitter-follow-button" data-show-count="false" data-size="large"><?php _e( 'Follow', WR_PBL ); ?> @WooRockets</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			</div>
		</div>
	</div>

	<div id="email-features">
		<div class="left-feature">
			<div class="box-email">
				<form action="http://www.woorockets.com/wp-content/plugins/newsletter/do/subscribe.php" method="POST">
					<input type="hidden" value="from-pb" name="nr">
					<input class="txt" type="email" name="ne" required placeholder="<?php _e( 'Enter your email', WR_PBL ); ?>..." />
					<input class="btn" type="submit" value=" " />
				</form>
				<h3><?php _e( 'Join our mailing list', WR_PBL ); ?></h3>
				<p><?php _e( 'Receive the latest updates about WR Page Builder as well as all the best news from WooRockets', WR_PBL ); ?></p>
			</div>
			<div class="box-document">
				<a target="_black" class="link" href="http://www.woorockets.com/docs/wr-pagebuilder-user-manual/?utm_source=PageBuilder%20About&utm_medium=link&utm_campaign=Cross%20Promo%20Plugins"></a>
				<img src="<?php echo WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/about-us/support.png'; ?>" />
				<h3><?php _e( 'Documentation', WR_PBL ); ?></h3>
				<p><?php _e( 'Detailed construction of how to use WR Page Builder', WR_PBL ); ?></p>
			</div>
		</div>
		<div class="right-feature">
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
						<a class="button-primary" href="http://bit.ly/wrpb-about-docs-for-dev" target="_blank"><?php _e( 'Docs for Developers', WR_PBL ); ?></a>
						<p><?php _e( 'Get our Source Code at Github!', WR_PBL ); ?></p>
						<a class="button-primary" href="http://bit.ly/wrpb-about-github" target="_blank"><?php _e( 'Source Code', WR_PBL ); ?></a>
						<p><?php _e( 'Having any exciting ideas or improvements for WR Page Builder to grow our WordPress Community? Drop an email to our WooRockets Astronaut Tony at', WR_PBL ); ?> <a href="mailto:tony@woorockets.com">tony@woorockets.com</a>!</p>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="for-translators">
						<p><?php _e( 'If you are reading this, we need your contribution! We appreciate all kinds of support for Translating WR Page Builder into your language!', WR_PBL ); ?> <a class="wr-blue" href="http://bit.ly/wpdb-about-transifex" target="_blank"><?php _e( 'Translate WR Page Builder', WR_PBL ); ?></a>.</p>
						<p><?php _e( 'Our awesome translators', WR_PBL ); ?>:</p>
						<p class="translators-list">
							<a class="wr-blue" href="https://www.transifex.com/accounts/profile/kihoshin/" target="_blank">kihoshin</a>,
							<a class="wr-blue" href="https://www.transifex.com/accounts/profile/KevU/" target="_blank">KevU</a>,
							<a class="wr-blue" href="https://www.transifex.com/accounts/profile/hienntt/" target="_blank">hienntt</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="get-involved">
		<h2><?php _e( 'GET INVOLVED', WR_PBL ); ?></h2>
		<div class="list-involved">
			<div class="item-involved">
				<div class="item-involved-inner">
					<div class="icon-involved"><span class="dashicons dashicons-star-filled"></span><strong><?php _e( 'Rate WR Page Builder', WR_PBL ); ?></strong></div>
					<p><?php _e( 'Share your thoughts of WR Page Builder with other WordPress folks. Next versions of Page Builder will be improved basing on your opinions.', WR_PBL ); ?></p>
				</div>
			</div>
			<div class="item-involved">
				<div class="item-involved-inner">
					<div class="icon-involved"><span class="dashicons dashicons-desktop"></span><strong><?php _e( 'Submit your Website', WR_PBL ); ?></strong></div>
					<p><?php _e( "Share your website using WR Page Builder with us. We can include it in our showcase collection and have it exposed to thousands of WooRockets's website visitors.", WR_PBL ); ?></p>
				</div>
			</div>
		</div>
		<div class="list-involved">
			<div class="item-involved">
				<div class="item-involved-inner">
					<a target="_blank" class="button-primary" href="http://bit.ly/wrpb-about-rate"><?php _e( 'Review', WR_PBL ); ?></a>
				</div>
			</div>
			<div class="item-involved">
				<div class="item-involved-inner">
					<a target="_blank" class="button-primary" href="http://www.woorockets.com/contact/?utm_source=PageBuilder%20About&utm_medium=button&utm_campaign=Cross%20Promo%20Plugins"><?php _e( 'Submit your website', WR_PBL ); ?></a>
				</div>
			</div>
		</div>
	</div>

	<div id="our-blog">
		<div class="left-ourblog">
			<a target="_blank" class="link" href="http://www.woorockets.com/blog/?utm_source=PageBuilder%20About&utm_medium=banner&utm_campaign=Cross%20Promo%20Plugins"></a>
			<h3><?php _e( 'Learn more from <strong>OUR BLOG</strong>', WR_PBL ); ?></h3>
			<span></span>
			<p><?php _e( 'Follow our blog for latest news, tutorials & interviews about WooComerce & WordPress', WR_PBL ); ?></p>
		</div>
		<div class="right-ourblog">
			<h3><?php _e( 'SEE OUR OTHER AWESOMENESS', WR_PBL ); ?></h3>
			<span>***</span>
			<div class="list-product">
				<div class="item-product">
					<div class="img-product"><a target="_blank" href="http://www.woorockets.com/freebie/?utm_source=PageBuilder%20About&utm_medium=banner&utm_campaign=Cross%20Promo%20Plugins"><img src="<?php echo WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/about-us/freebies.png'; ?>"  /></a></div>
					<h4><a target="_blank" href="http://www.woorockets.com/freebie/?utm_source=PageBuilder%20About&utm_medium=banner&utm_campaign=Cross%20Promo%20Plugins"><?php _e( 'Freebies download', WR_PBL ); ?></a></h4>
				</div>
				<div class="item-product">
					<div class="img-product"><a target="_blank" href="http://www.woorockets.com/plugins/wr-megamenu/?utm_source=PageBuilder%20About&utm_medium=banner&utm_campaign=Cross%20Promo%20Plugins"><img src="<?php echo WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/about-us/mega-menu.png'; ?>"  /></a></div>
					<h4><a target="_blank" href="http://www.woorockets.com/plugins/wr-megamenu/?utm_source=PageBuilder%20About&utm_medium=banner&utm_campaign=Cross%20Promo%20Plugins">WR MegaMenu</a></h4>
				</div>
				<div class="item-product">
					<div class="img-product"><a target="_blank" href="http://www.woorockets.com/themes/corsa/?utm_source=PageBuilder%20About&utm_medium=banner&utm_campaign=Cross%20Promo%20Plugins"><img src="<?php echo WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/about-us/corsa.png'; ?>"  /></a></div>
					<h4><a target="_blank" href="http://www.woorockets.com/themes/corsa/?utm_source=PageBuilder%20About&utm_medium=banner&utm_campaign=Cross%20Promo%20Plugins"><?php _e( 'Corsa theme', WR_PBL ); ?></a></h4>
				</div>
			</div>
		</div>
	</div>

	<div id="wr-logo">
		<a tagret="_blank" href="http://www.woorockets.com/?utm_source=PageBuilder%20About&utm_medium=bot%20logo&utm_campaign=Cross%20Promo%20Plugins" class="link"></a>
		<img src="<?php echo WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/about-us/logo-footer.png'; ?>" />
		<h3>www.woorockets.com</h3>
	</div>

</div>

<script type="text/javascript">
	(function($) {
		$(document).ready(function() {
			$('#email-features .left-feature .box-email form .txt').focus(function () {
				$('#email-features .left-feature .box-email form').addClass('focus');
			})
			$('#email-features .left-feature .box-email form .txt').blur(function () {
				$('#email-features .left-feature .box-email form').removeClass('focus');
			})
		});
	})(jQuery);
</script>