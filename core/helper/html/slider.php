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
class WR_Pb_Helper_Html_Slider extends WR_Pb_Helper_Html {
	/**
	 * Horizonal slider to select a numeric value
	 * @param type $element
	 * @return type
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );
		$std_max = empty ( $element['std_max'] ) ? 100 : $element['std_max'];
		$output  = '<script>
			( function ($ ) {
				$( document ).ready( function ()
				{
					var slider_ = $( ".wr-slider" );
					var input_slider = slider_.next("input").first();
					slider_.slider({
						range: "min",
						value: ' . $element['std'] . ',
						min: 1,
						max: ' . $std_max .',
						slide: function ( event, ui ) {
							var input_slide = $(ui.handle).parent().next("input").first();
							input_slide.val( ui.value ).change();
							$( ui.handle ).html( "<div class=\'wr-slider-value\'>" + ui.value + "%</div>" );
						},
						create: function( event, ui ) {
							$( "#' . $element['id'] . '_slider .ui-slider-handle" ).html( "<div class=\'wr-slider-value\'>" + ' . $element['std'] . ' + "%</div>" );
						}
					});
				});
			})( jQuery )
		</script>';
		$output .= '<div id="' . $element['id'] . '_slider" class="' . $element['class'] . '" ></div>';
		$output .= '<input type="text" class="hidden" id="' . $element['id'] . '" value="' . $element['std'] . '" />';

		return parent::final_element( $element, $output, $label );
	}
}