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
 * @todo : List all page template
 */

$data = WR_Pb_Helper_Layout::get_premade_layouts();
?>

<div class="jsn-master" id="wr-pb-layout-box">
	<div class="jsn-bootstrap3">
		<div id="wr-layout-lib">
			<input type="hidden" id="wr-pb-layout-group"
				value="<?php echo esc_attr( WR_PAGEBUILDER_USER_LAYOUT ); ?>" />
			<!-- Elements -->

				<?php
				// Get only the templates which saved by user.
				$user_templates = isset ( $data['files'] ) && isset ( $data['files'][WR_PAGEBUILDER_USER_LAYOUT] ) ? $data['files'][WR_PAGEBUILDER_USER_LAYOUT] : array();
				if ( ! count( $user_templates ) ) {
					echo '<p class="jsn-bglabel">You did not save any page yet.</p>';
				} else {
					$items   = array();
					$items[] = '<ul class="jsn-items-list " style="height: auto;">';
					foreach ( $user_templates as $name => $path ) {
						$layout_name = WR_Pb_Helper_Layout::extract_layout_data( $path, 'name' );
						$layout_name = empty ( $layout_name ) ? __( '&mdash; Untitled &mdash;' ) : $layout_name;
						$content     = WR_Pb_Helper_Layout::extract_layout_data( $path, 'content' );
						$items[]     = '<li data-type="element" data-value="user_layout" data-id="' . $name . '" class="jsn-item premade-layout-item" style="display: list-item;">
					' . $layout_name . '
					<i class="icon-trash delete-item"></i>
				<textarea style="display:none">' . $content . '</textarea>
			</li>';

					}
					$items[] = '</ul>';

					echo balanceTags( implode( "\n", $items ) );
				}

				?>

		</div>
	</div>
</div>
