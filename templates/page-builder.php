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

global $post;

wp_nonce_field( 'wr_builder', WR_NONCE . '_builder' );

$settings = WR_Pb_Product_Plugin::wr_pb_settings_options();
$enable_fullmode = ! isset( $settings['wr_pb_settings_fullmode'] ) || ( isset( $settings['wr_pb_settings_fullmode'] ) && $settings['wr_pb_settings_fullmode'] == 'enable' );

?>
<!-- Buttons bar -->
<div
	class="jsn-form-bar">
	<div id="status-switcher" class="btn-group" data-toggle="buttons-radio">
		<button type="button" class="switchmode-button btn btn-default active"
			id="status-on"
			data-original-title="<?php _e( 'Active Page Builder', WR_PBL ) ?>">
			<?php _e( 'On', WR_PBL ) ?>
		</button>
		<button type="button" class="switchmode-button btn btn-default"
			id="status-off"
			data-original-title="<?php _e( 'Deactivate Page Builder', WR_PBL ) ?>">
			<?php _e( 'Off', WR_PBL ) ?>
		</button>
	</div>
	<div id="mode-switcher" class="btn-group" data-toggle="buttons-radio">
		<button type="button" class="switchmode-button btn btn-default active"
			id="switchmode-compact">
			<?php _e( 'Compact', WR_PBL ) ?>
		</button>
		<?php if ( $enable_fullmode ) : ?>
		<button type="button" class="switchmode-button btn btn-default" id="switchmode-full">
			<?php _e( 'Full', WR_PBL ) ?>
		</button>
		<?php endif; ?>
	</div>

	<!-- Page Templates -->
	<div class="pull-right" id="top-btn-actions">
		<div class="pull-left" id="page-custom-css">
			<button class="btn btn-default" onclick="return false;">
			<?php _e( 'Custom CSS', WR_PBL ) ?>
			</button>
		</div>
		<div class="btn-group dropdown pull-left" id="page-template">
			<a class="btn btn-default dropdown-toggle wr-dropdown-toggle"
				href="#"> <?php _e( 'Page template', WR_PBL ) ?> <span class="caret"></span>
			</a>
			<ul class="dropdown-menu pull-right">
				<li><a href="#" id="save-as-new" class="wr-modal-toggle"><?php _e( 'Save as new template', WR_PBL ); ?>
				</a></li>
				<li><a id="apply-page" href="#"><?php _e( 'Load template', WR_PBL ); ?>
				</a></li>
			</ul>
		</div>
	</div>

	<!-- Save as new template modal -->
	<div id="save-as-new-dialog" role="dialog" aria-hidden="true"
		tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header ui-dialog-title">
					<h3>
					<?php _e( 'Save as new template', WR_PBL ); ?>
					</h3>
				</div>
				<div class="modal-body form-horizontal">
					<div class="form-group">
						<label class="control-label" for="template-name"><?php _e( 'Template name:' );?>
						</label>
						<div class="controls">
							<input type="text" id="template-name" class="input form-control">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn btn-primary template-save"><?php _e( 'Save', WR_PBL ); ?>
					</a> <a href="#" class="btn template-cancel"><?php _e( 'Cancel', WR_PBL ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
	<!-- END Save as new template modal -->
</div>

<!-- WR PageBuilder elements -->
<div class="jsn-section-content jsn-style-light"
	id="form-design-content">
	<div id="wr-pbd-loading" class="text-center">
		<i class="jsn-icon32 jsn-icon-loading"></i>
	</div>
	<div class="wr-pb-form-container jsn-layout">
	<?php if ( @count( $converters ) ) : ?>
		<?php foreach ( $converters as $id => $name ) : ?>
		<!-- Data conversion dialog -->
		<div class="data-conversion-dialog" data-target="<?php echo esc_attr( $id ); ?>">
			<div class="alert alert-warning">
				<i class="icon-warning"></i>
				&nbsp;&nbsp;&nbsp;
				<span class="message">
				<?php
				// Get current post type's singular_name
				$post_type = get_post_type_object( get_post_type() );
				$post_type = strtolower( $post_type->labels->singular_name );

				printf(
					__(
						'Your current %1$s has been built by using <strong>%2$s</strong>. Would you like to convert all data to <strong>WR PageBuilder</strong>?',
						WR_PBL
					),
					$post_type,
					$name
				);
				?>
				</span>
			</div>
			<div class="action">
				<div class="text-center">
					<label>
						<input type="checkbox" name="backup_data" value="1" checked="checked" />
						<?php printf( __( 'I also want to backup all data as a new %s', WR_PBL ), $post_type ); ?>
					</label>
				</div>
				<div class="text-center">
					<button class="btn btn-success col-xs-3 center-block" data-action="convert-only">
						<span data-working-text="<?php _e( 'Converting Data...', WR_PBL ); ?>">
							<?php _e( 'Convert' ); ?>
						</span>
					</button>
					<?php _e( 'or', WR_PBL ); ?>
					<button class="btn btn-link" data-action="convert-and-publish">
						<span data-working-text="<?php _e( 'Converting Data...', WR_PBL ); ?>">
							<?php _e( 'Convert and Publish' ); ?>
						</span>
					</button>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	<?php
	else :

	$pagebuilder_content = get_post_meta( $post->ID, '_wr_page_builder_content', true );

	if ( ! empty( $pagebuilder_content ) ) :
		$builder = new WR_Pb_Helper_Shortcode();
		echo balanceTags( $builder->do_shortcode_admin( $pagebuilder_content ) );
	endif;
	?>
		<a href="javascript:void(0);" id="jsn-add-container"
			class="jsn-add-more"><i class="wr-icon-add-row"></i> <?php _e( 'Add Row', WR_PBL ) ?>
		</a>
		<?php
		// Default layouts
		include WR_PB_TPL_PATH . '/default-layouts.php';
		?>
		<input type="hidden" id="wr-select-media" value="" />
	<?php endif; ?>
	</div>
	<div id="deactivate-msg" class="jsn-section-empty hidden">
		<p class="jsn-bglabel">
			<span class="jsn-icon64 jsn-icon-remove"></span>
			<?php _e( 'PageBuilder for this page is currently off.', WR_PBL ); ?>
		</p>
		<p class="jsn-bglabel">
			<a href="javascript:void(0)" class="btn btn-success"
				id="status-on-link"><?php _e( 'Turn PageBuilder on', WR_PBL )?> </a>
		</p>

	</div>
</div>

<!-- Link to website -->
<div id="branding">
	<div class="pull-left">
		<div>
			<?php _e( 'Powered by', WR_PBL )?>
			<a href="http://www.woorockets.com/?utm_source=PageBuilder%20Backend&utm_medium=Text&utm_campaign=Powered%20By" target="_blank">WooRockets.com</a> | <a
				href="http://www.woorockets.com/docs/wr-pagebuilder-user-manual/?utm_source=PageBuilder%20Backend&utm_medium=Text&utm_campaign=Powered%20By"
				target="_blank"><?php _e( 'Documentation', WR_PBL ); ?> </a>
		</div>
	</div>
	<div class="clearbreak"></div>
</div>

			<?php

			// Page Template
			include 'layout/template.php';

			// Insert Post ID as hidden field
			$post_id = isset ( $_GET['post'] ) ? $_GET['post'] : ( isset ( $post->ID ) ? $post->ID : '' );
			?>
<div id="wr-pb-css-value">
	<input type="hidden" name="wr_pb_post_id" value="<?php echo esc_attr( $post_id ); ?>">
</div>

<!--[if IE]>
<style>
	.jsn-quicksearch-field{
		height: 28px;
	}
</style>
<![endif]-->
