<?php
/**
 * @version	$Id$
 * @package	WR PageBuilder
 * @author	 WooRockets Team <support@www.woorockets.com>
 * @copyright  Copyright (C) 2012 www.woorockets.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.woorockets.com
 * Technical Support:  Feedback - http://www.www.woorockets.com
 */

/**
 * @todo : HTML form to save page template
 */
?>
<div id="wr-add-layout" style="display: none;">
	<div class="popover top" style="display: block;">
		<div class="arrow"></div>
		<div class="popover-content">

			<div class="layout-box">
				<div id="save-layout" class="layout-action">
					<a href="javascript:void(0)"><?php _e( 'Save current content as template', WR_PBL ); ?>
						<i class="icon-star"></i> </a>
				</div>
				<div id="save-layout-form"
					class="input-append hidden layout-toggle-form">
					<input type="text" name="layout_name" id="layout-name"
						placeholder="<?php _e( 'Layout name', WR_PBL ); ?>">
					<button class="btn" type="button">
						<i class="icon-checkmark"></i>
					</button>
					<button type="button" class="btn btn-layout-cancel"
						data-id="save-layout">
						<i class="icon-remove"></i>
					</button>
				</div>
				<div class="hidden layout-loading">
					<i class="jsn-icon16 jsn-icon-loading"></i>
				</div>
				<div class="hidden layout-message">
				<?php _e( 'Saved successfully', WR_PBL ); ?>
				</div>
			</div>

			<div id="apply-layout">
				<a href="javascript:void(0)"><?php _e( 'Apply template from library', WR_PBL ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
