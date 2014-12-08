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
?>
<div class="jsn-master" id="wr-pb-custom-css-box">
	<div class="jsn-bootstrap3">
		<div class="form-group control-group jsn-items-list-container wr-modal-content">
			<form id="wr-form-report-bug" class="form-horizontal" role="form">
				<div class="form-group clearfix">
					<label for="wr_description" class="control-label" title="<?php echo __( 'Description', WR_PBL ) ?>"><?php echo __( 'Description', WR_PBL ) ?></label>
					<p class="help-block"><?php echo __( 'Please give the details of your report. Our technical staffs will recheck and fix bug(s) as soon as possible', WR_PBL ) ?></p>
					<textarea id="wr_description" name="wr_description" class="form-control" row="3" style="min-height:120px"></textarea>
				</div>
				<div class="form-group clearfix">
					<label for="wr_browser" class="control-label" title="<?php echo __( 'Browser', WR_PBL ) ?>"><?php echo __( 'Browser', WR_PBL ) ?></label>
					<p class="help-block"><?php echo __( 'Chose the browser that you meet the bug while using', WR_PBL ) ?></p>
					<select id="wr_browser" name="wr_browser" class="form-control">
						<option value="0"><?php echo __( '--Select Browser--', WR_PBL ) ?></option>
						<option value="firefox"><?php echo __( 'Firefox', WR_PBL ) ?></option>
						<option value="chrome"><?php echo __( 'Chrome', WR_PBL ) ?></option>
						<option value="safari"><?php echo __( 'Safari', WR_PBL ) ?></option>
						<option value="opera"><?php echo __( 'Opera', WR_PBL ) ?></option>
						<option value="ie"><?php echo __( 'Internet Explorer', WR_PBL ) ?></option>
						<option value="other"><?php echo __( 'Other', WR_PBL ) ?></option>
					</select>
				</div>
				<div class="form-group clearfix">
					<label for="wr_attachment" class="control-label" title="<?php echo __( 'Attachment(s)', WR_PBL ) ?>"><?php echo __( 'Attachment(s)', WR_PBL ) ?></label>
					<div class="controls">
						<div class="input-append input-group">
							<input id="wr_attachment" name="wr_attachment" class="input-sm form-control" type="text" value="" />
							<input type="hidden" name="wr_attachment_id" id="wr_attachment_id" value="" />
							<span class="wr_attachment_select input-group-addon btn btn-default">...</span>
							<span class="wr_attachment_remove input-group-addon btn btn-default"><i class="icon-remove"></i></span>
						</div>
					</div>
				</div>
				<div class="form-group clearfix">
					<label for="wr_url" class="control-label" title="<?php echo __( 'Or Enter an URL', WR_PBL ) ?>"><?php echo __( 'Or Enter an URL', WR_PBL ) ?></label>
					<input type="text" id="wr_url" name="wr_url" class="form-control" placeholder="http://" value="" />
				</div>
			</form>
		</div>
	</div>
</div>