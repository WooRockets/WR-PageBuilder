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
 * @todo : Define HTML output of element types
 */

if ( ! class_exists( 'WR_Pb_Helper_Html' ) ) {

	class WR_Pb_Helper_Html {

		/**
		 * Get dependency information of an element
		 * @param array $element
		 */
		static function get_dependency( $element ) {
			$depend_info = array( 'data' => '', 'class' => '' );
			$dependency = ! empty( $element['dependency'] ) ? $element['dependency'] : '';
			if ( $dependency ) {
				$depend_info['data']  = " data-depend-element='param-{$dependency[0]}' data-depend-operator='{$dependency[1]}' data-depend-value='{$dependency[2]}'";
				$depend_info['class'] = ' wr_hidden_depend wr_depend_other ';
			}
			return $depend_info;
		}

		/**
		 * Get depend class & data to show/hide this option
		 *
		 * @param array $element
		 * @return type
		 */
		static function get_extra_info( $element ) {
			// check if element has dependened elements
			if ( ! isset( $element['class'] ) )
			$element['class'] = '';
			$element['class'] .= ' form-control input-sm';
			$element['class'] .= ( ! empty($element['has_depend'] ) && $element['has_depend'] == '1') ? ' wr_has_depend' : '';
			if ( isset( $element['exclude_class'] ) && is_array( $element['exclude_class'] ) ) {
				foreach ( $element['exclude_class'] as $i => $class ) {
					$element['class'] = str_replace( $class, '', $element['class'] );
				}
			}

			$depend_info = self::get_dependency( $element );
			$element['depend_class'] = $depend_info['class'];
			$element['depend_data']  = $depend_info['data'];
			return $element;
		}

		/**
		 * Add parent class for option/ group of options
		 *
		 * @param type $output
		 * @return type
		 */
		static function bound_options( $output ) {
			return '<div class="controls">' . $output . '</div>';
		}

		/**
		 * Add data attributes for element
		 *
		 * @param array $element
		 * @param type $output
		 * @return type
		 */
		static function get_data_info( $element, $output ) {
			$role   = ! empty( $element['role'] ) ? "data-role='{$element['role']}'" : '';
			$role  .= ! empty( $element['title_prepend_type'] ) ? "data-title-prepend='{$element['title_prepend_type']}'" : '';
			$role  .= ! empty( $element['related_to'] ) ? "data-related-to='{$element['related_to']}'" : '';
			$output = str_replace( 'DATA_INFO', $role, $output );
			return $output;
		}

		/**
		 * Get style info
		 *
		 * @param array $element
		 * @param type $output
		 * @return type
		 */
		static function get_style( $element, $output ) {
			$style = ! empty( $element['style'] ) ? $element['style'] : '';
			if ( is_array( $element['style'] ) ) {
				$styles = array();
				foreach ( $element['style'] as $att_name => $att_value ) {
					$styles[] = "$att_name : $att_value";
				}
				$styles = "style = '" . implode( ';', $styles ) . "'";
			}else
			$styles = '';
			$output  = WR_Pb_Utils_Placeholder::remove_placeholder( $output, 'custom_style', $styles );
			return $output;
		}

		/**
		 * Output final HTML of a element
		 *
		 * @param array $element
		 * @param type $output
		 * @return type
		 */
		static function final_element( $element, $output, $label, $no_id = false ) {
			// data info settings
			$output = self::get_data_info( $element, $output );
			// custom style settings
			//$output = self::get_style( $element, $output );
			// parent class
			if ( ! empty( $element['parent_class'] ) ) {
				$output = "<div class='{$element['parent_class']}'>" . $output . '</div>';
			}

			if ( isset( $element['blank_output'] ) )
			return $output;
			else if ( isset($element['bound'] ) && $element['bound'] == '0')
			return $label . $output;
			else {
				$id = ( isset($element['id'] ) && ! $no_id) ? "id='parent-{$element['id']}'" : '';
				if ( ! ( isset($element['wrap'] ) && $element['wrap'] == '0' ) ) {
					$output = self::bound_options( $output );
				}
				$wrap_class = ( ! isset( $element['wrap_class'] ) ) ? 'control-group form-group clearfix' : $element['wrap_class'];
				$container_class   = isset( $element['container_class'] ) ? $element['container_class'] : '';
				$depend_class      = isset( $element['depend_class'] ) ? $element['depend_class'] : '';
				$depend_data       = isset( $element['depend_data'] ) ? $element['depend_data'] : '';
				$data_wrap_related = isset( $element['data_wrap_related'] ) ? "data-related-to='{$element['data_wrap_related']}'" : '';

				return "<div $id class='$wrap_class $container_class $depend_class' $depend_data $data_wrap_related> $label $output </div>";
			}
		}

		/**
		 * Show/Hide label for a type element
		 * @param array $element
		 * @return type
		 */
		static function get_label( $element ) {
			// Generate HTML code for label
			$label = '';

			if ( ( ! isset( $element['showlabel'] ) || $element['showlabel'] != '0' ) && isset( $element['name'] ) ) {
				// Generate attributes for tooltip
				$tooltip = '';

				if ( isset( $element['tooltip'] ) ) {
					$tooltip = '<i class="icon-help wr-tooltip-toggle" data-html="true" title = "' . $element['tooltip'] . '"></i>';
				}

				$label = "<label class='control-label wr-label-left'><span >{$element['name']}</span>{$tooltip}</label>";
			}

			return $label;
		}

	}

	// end class
} // end if ! class_exists