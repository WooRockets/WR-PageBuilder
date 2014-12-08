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
?>
<div class="wrap jsn-bootstrap3">
	<h2><?php _e( $plugin['Name'], WR_LIBRARY_TEXTDOMAIN ); ?> <?php _e( 'Add-ons', WR_LIBRARY_TEXTDOMAIN ); ?></h2>
	<p>
		<?php printf( __( 'Extend %s functionality with following add-ons', WR_LIBRARY_TEXTDOMAIN ), __( $plugin['Name'], WR_LIBRARY_TEXTDOMAIN ) ); ?>
	</p>
	<div id="wr-product-addons">
		<ul id="<?php echo '' . $plugin['Identified_Name']; ?>-addons" class="thumbnails clearfix">
			<?php foreach ( $plugin['Addons'] as $identified_name => $details ) : ?>
			<li class="thumbnail pull-left">
				<a href="<?php echo esc_url( $details->url ); ?>" target="_blank">
					<img src="<?php echo esc_url( $details->thumbnail ); ?>" alt="<?php esc_attr_e( $details->name, WR_LIBRARY_TEXTDOMAIN ) ?>" />
				</a>
				<?php if ( ! $details->compatible ) : ?>
				<span class="label label-danger"><?php _e( 'Incompatible', WR_LIBRARY_TEXTDOMAIN ); ?></span>
				<?php elseif ( $details->installed ) : ?>
				<span class="label label-success"><?php _e( 'Installed', WR_LIBRARY_TEXTDOMAIN ); ?></span>
				<?php endif; ?>
				<div class="caption">
					<h3><?php _e( $details->name, WR_LIBRARY_TEXTDOMAIN ) ?></h3>
					<p><?php _e( $details->description, WR_LIBRARY_TEXTDOMAIN ) ?></p>
					<div class="actions clearfix">
						<div class="pull-left">
							<?php if ( ! $details->installed ) : ?>
							<a class="btn btn-primary <?php if ( ! $details->compatible ) echo 'disabled'; ?>" href="javascript:void(0);" <?php if ( $details->compatible ) : ?>data-action="install" data-authentication="<?php echo absint( $details->authentication ); ?>" data-identification="<?php echo '' . $details->identified_name; ?>"<?php endif; ?>>
								<?php _e( 'Install', WR_LIBRARY_TEXTDOMAIN ); ?>
							</a>
							<?php else : if ( $details->updatable ) : ?>
							<a class="btn btn-primary <?php if ( ! $details->compatible ) echo 'disabled'; ?>" href="javascript:void(0);" data-action="update" <?php if ( $details->compatible ) : ?>data-authentication="<?php echo absint( $details->authentication ); ?>" data-identification="<?php echo '' . $details->identified_name; ?>"<?php endif; ?>>
								<?php _e( 'Update', WR_LIBRARY_TEXTDOMAIN ); ?>
							</a>
							<?php endif; ?>
							<a class="btn <?php if ( ! $details->updatable ) echo 'btn-primary'; ?> <?php if ( ! $details->compatible ) echo 'incompatible'; ?>" href="javascript:void(0);" data-action="uninstall" data-authentication="<?php echo absint( $details->authentication ); ?>" data-identification="<?php echo '' . $details->identified_name; ?>">
								<?php _e( 'Uninstall', WR_LIBRARY_TEXTDOMAIN ); ?>
							</a>
							<?php endif; ?>
						</div>
						<a class="btn btn-info pull-right" href="<?php echo esc_url( $details->url ); ?>" target="_blank">
							<?php _e( 'More Info', WR_LIBRARY_TEXTDOMAIN ); ?>
						</a>
					</div>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<div class="jsn-bootstrap3 wr-product-addons-authentication">
	<div class="modal fade" id="<?php echo '' . $plugin['Identified_Name']; ?>-authentication" tabindex="-1" role="dialog" aria-labelledby="<?php echo '' . $plugin['Identified_Name']; ?>-authentication-modal-label" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="<?php echo '' . $plugin['Identified_Name']; ?>-authentication-modal-label">
						<?php _e( 'WooRockets Customer Account', WR_LIBRARY_TEXTDOMAIN ); ?>
					</h4>
				</div>
				<div class="modal-body">
					<form name="WR_Addons_Authentication" method="POST" class="form-horizontal" autocomplete="off">
						<div class="alert alert-danger hidden">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<span class="message"></span>
						</div>
						<div class="form-group clearfix">
							<label class="col-sm-3 control-label" for="username"><?php _e( 'Username', WR_LIBRARY_TEXTDOMAIN ); ?>:</label>
							<div class="col-sm-9">
								<input type="text" value="" class="form-control" id="username" name="username" autocomplete="off" />
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-sm-3 control-label" for="password"><?php _e( 'Password', WR_LIBRARY_TEXTDOMAIN ); ?>:</label>
							<div class="col-sm-9">
								<input type="password" value="" class="form-control" id="password" name="password" autocomplete="off" />
							</div>
						</div>
						<div class="form-group clearfix">
							<div class="col-sm-9 pull-right">
								<div class="checkbox-inline">
									<label>
										<input type="checkbox" value="1" id="remember" name="remember" autocomplete="off" />
										<?php _e( 'Remember Me', WR_LIBRARY_TEXTDOMAIN ); ?>
									</label>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary"><?php _e( 'Install', WR_LIBRARY_TEXTDOMAIN ); ?></button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e( 'Cancel', WR_LIBRARY_TEXTDOMAIN ); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
// Load inline script initialization
$script = '
		new $.WR_ProductAddons({
			base_url: "' . esc_url( admin_url( 'admin-ajax.php?action=wr-addons-management' ) ) . '",
 			core_plugin: "' . $plugin['Identified_Name'] . '",
 			has_saved_account: ' . ( $has_customer_account ? 'true' : 'false' ) . ',
			language: {
				CANCEL: "' . __( 'Cancel', WR_LIBRARY_TEXTDOMAIN ) . '",
				INSTALL: "' . __( 'Install', WR_LIBRARY_TEXTDOMAIN ) . '",
				UNINSTALL: "' . __( 'Uninstall', WR_LIBRARY_TEXTDOMAIN ) . '",
				INSTALLED: "' . __( 'Installed', WR_LIBRARY_TEXTDOMAIN ) . '",
				INCOMPATIBLE: "' . __( 'Incompatible', WR_LIBRARY_TEXTDOMAIN ) . '",
				UNINSTALL_CONFIRM: "' . __( 'Are you sure you want to uninstall %s?', WR_LIBRARY_TEXTDOMAIN ) . '",
				AUTHENTICATING: "' . __( 'Verifying...', WR_LIBRARY_TEXTDOMAIN ) . '",
				INSTALLING: "' . __( 'Installing...', WR_LIBRARY_TEXTDOMAIN ) . '",
				UPDATING: "' . __( 'Updating...', WR_LIBRARY_TEXTDOMAIN ) . '",
				UNINSTALLING: "' . __( 'Uninstalling...', WR_LIBRARY_TEXTDOMAIN ) . '",
			}
		});';

WR_Pb_Init_Assets::inline( 'js', $script );
