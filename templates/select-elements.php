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
 * @todo : Popover to select Element
 */

global $Wr_Pb, $Wr_Pb_Shortcodes, $Wr_Sc_By_Providers_Name;

// Arrray of element objects
$elements = $Wr_Pb->get_elements();

if ( empty ( $elements ) || empty ( $elements['element'] ) ) {
    _e( 'You have not install Free or Pro Shortcode package.' );
} else {
	$elements_html = array(); // HTML button of a shortcode
	$categories    = array(); // array of shortcode category

	foreach ( $elements['element'] as $element ) {
		// don't show sub-shortcode
		if ( ! isset( $element->config['name'] ) ) {
			continue;
		}

		// get shortcode category

		$category = ''; // category name of this shortcode
		if ( ! empty( $Wr_Pb_Shortcodes[$element->config['shortcode']] ) ) {
			$category_name = $Wr_Pb_Shortcodes[$element->config['shortcode']]['provider']['name'] | '';
			$category      = strtolower( str_replace( ' ', '', $category_name ) );
			if ( ! array_key_exists( $category, $categories ) ) {
				$categories[$category] = $category_name;
			}
		}

		$elements_html[] = $element->element_button( $category );
	}
	?>
<div id="wr-add-element" class="wr-add-element add-field-dialog jsn-bootstrap3"
	style="display: none;">
	<div class="popover" style="display: block;">
		<h3 class="popover-title">
		<?php _e( 'Select Element', WR_PBL ); ?>
		</h3>
		<a type="button" class="close wr-popover-close">&times;</a>
		<div class="popover-content">
			<div class="jsn-elementselector">
				<div class="jsn-fieldset-filter">
					<fieldset>
						<div class="pull-left">
							<select id="jsn_filter_element"
								class="jsn-filter-button input-large">
								<optgroup label="<?php _e( 'Page Elements', WR_PBL ) ?>">
								<?php
								// Reorder the Categories of Elements
								$categories_order = array();
								$categories_order['all'] = __( 'All Elements', WR_PBL );

								// add Standard Elements as second option
								$standard_el = __( 'Standard Elements', WR_PBL );
								$key = array_search( $standard_el, $categories );
								$categories_order[$key] = $standard_el;

								unset( $key );

								// Sort other options by alphabetical order
								asort( $categories );
								$categories_order = array_merge( $categories_order, $categories );

								foreach ( $categories_order as $category => $name ) {
									$selected = ( $name == __( 'Standard Elements', WR_PBL ) ) ? 'selected' : '';
									printf( '<option value="%s" %s>%s</option>', esc_attr( $category ), $selected, esc_html( $name ) );
								}
								?>
								</optgroup>
								<option value="widget">
								<?php _e( 'Widgets', WR_PBL ) ?>
								</option>
								<option value="shortcode">
								<?php _e( 'PageBuilder Shortcode', WR_PBL ) ?>
								</option>
							</select>
						</div>
						<div class="pull-right jsn-quick-search" role="search">
							<input type="text"
								class="input form-control jsn-quicksearch-field"
								placeholder="<?php _e( 'Search', WR_PBL ); ?>..."> <a
								href="javascript:void(0);"
								title="<?php _e( 'Clear Search', WR_PBL ); ?>"
								class="jsn-reset-search" id="reset-search-btn"><i
								class="icon-remove"></i> </a>
						</div>
					</fieldset>
				</div>
				<!-- Elements -->
				<ul class="jsn-items-list">
				<?php
				// shortcode elements
				foreach ( $elements_html as $idx => $element ) {
					echo balanceTags( $element );
				}

				// widgets
				global $Wr_Pb_Widgets;
				foreach ( $Wr_Pb_Widgets as $wg_class => $config ) {
					$extra_                    = $config['extra_'];
					$config['edit_using_ajax'] = true;
					echo balanceTags( WR_Pb_Shortcode_Element::el_button( $extra_, $config ) );
				}
				?>
					<!-- Generate text area to add element from raw shortcode -->
					<li class="jsn-item full-width" data-value='raw'
						data-sort='shortcode'><textarea id="raw_shortcode"></textarea>

						<div class="text-center rawshortcode-container">
							<button class="shortcode-item btn btn-success"
								data-shortcode="raw" id="rawshortcode-add">
								<?php _e( 'Add Element', WR_PBL ); ?>
							</button>
						</div>
					</li>
				</ul>
				<p style="text-align: center">
				<?php // echo esc_html( __( 'Want to add more elements?', WR_PBL ) ); ?>
					&nbsp;<!--a target="_blank"
						href="<?php //echo esc_url( admin_url( 'admin.php?page=wr-pb-addons' ) ); ?>"><?php //echo esc_html( __( 'Check add-ons.', WR_PBL ) ); ?>
					</a-->
				</p>
			</div>
		</div>
	</div>
</div>

<?php
}