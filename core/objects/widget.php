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
if ( ! class_exists( 'WR_Pb_Objects_Widget' ) ) {

	class WR_Pb_Objects_Widget extends WP_Widget {

		var $wr_widget_cssclass;
		var $wr_widget_description;
		var $wr_widget_idbase;
		var $wr_widget_name;

		/**
		 * constructor
		 *
		 * @access public
		 * @return void
		 */
		function __construct() {
			$this->wr_widget_cssclass    = 'wr-widget-pagebuilder';
			$this->wr_widget_description = __( 'Presentation of any PageBuilder element', WR_PBL );
			$this->wr_widget_idbase      = 'wr_widget_pagebuilder';
			$this->wr_widget_name        = __( 'PageBuilder Element', WR_PBL );

			/* Widget settings. */
			$widget_ops = array( 'classname' => $this->wr_widget_cssclass, 'description' => $this->wr_widget_description );

			/* Create the widget. */
			parent::__construct( 'wr_widget_pagebuilder', $this->wr_widget_name, $widget_ops );
		}

		/**
		 * widget function
		 *
		 * @see WP_Widget::widget()
		 * @access public
		 * @param array $args
		 * @param array $instance
		 * @return void
		 */
		function widget( $args, $instance ) {
			extract( $args );
			$title = $shortcode = '';
			// process shortcode
			if ( isset( $instance['wr_widget_shortcode'] ) ) {
				$shortcode = $instance['wr_widget_shortcode'];
				if ( ! $title ) {
					$str_title = substr( $shortcode, strpos( $shortcode, 'el_title=--quote--' ) );
					$str_title = str_replace( 'el_title=--quote--', '', $str_title );
					$title     = substr( $str_title, 0, strpos( $str_title, '--quote--' ) );
				}
				$shortcode = str_replace( '--quote--', '"', $shortcode );
				$shortcode = str_replace( '--open_square--', '[', $shortcode );
				$shortcode = str_replace( '--close_square--', ']', $shortcode );
			}
			if ( ! $title ) {
				global $Wr_Pb;
				$elements = $Wr_Pb->get_elements();
				if ( isset( $elements['element'] ) ) {
					foreach ( $elements['element'] as $idx => $element ) {
						// don't show sub-shortcode
						if ( ! isset( $element->config['name'] ) )
						continue;
						if ( isset( $instance['wr_element'] ) && $element->config['shortcode'] == $instance['wr_element'] ) {
							$title = $element->config['name'];
						}
					}
				}
			}
			// process widget title
			$title = apply_filters( 'widget_title', empty($instance['wr_element'] ) ? __( 'PageBuilder Element', WR_PBL ) : $title, $instance, $this->id_base );
			echo balanceTags( $before_widget );
			if ( $title ) {
				echo balanceTags( $before_title . $title . $after_title );
			}
			echo '<div class="jsn-bootstrap3">';
			echo balanceTags( do_shortcode( $shortcode ) );
			echo '</div>';
			echo balanceTags( $after_widget );
		}

		/**
		 * update pagebuilder widget element
		 *
		 * @see WP_Widget::update()
		 */
		function update( $new_instance, $old_instance ) {
			$instance                        = $old_instance;
			$instance['wr_element']          = strip_tags( $new_instance['wr_element'] );
			$instance['wr_widget_shortcode'] = $new_instance['wr_widget_shortcode'];

			return $instance;
		}

		/**
		 * form function.
		 *
		 * @see WP_Widget::form()
		 * @access public
		 * @param array $instance
		 * @return void
		 */
		function form( $instance ) {
			// Default
			$instance            = wp_parse_args( (array ) $instance, array( 'wr_element' => '', 'wr_widget_shortcode' => '' ) );
			$title               = '';
			$selected_value      = esc_attr( $instance['wr_element'] );
			$wr_widget_shortcode = $instance['wr_widget_shortcode'];

			global $Wr_Pb;
			$elements      = $Wr_Pb->get_elements();
			$elements_html = array();
			if ( $elements ) {
				foreach ( $elements['element'] as $idx => $element ) {
					// don't show sub-shortcode
					if ( ! isset( $element->config['name'] ) )
					continue;
					if ( $element->config['shortcode'] == $selected_value ) {
						$elements_html[] = '<option value="' . $element->config['shortcode'] . '" selected="selected">' . $element->config['name'] . '</option>';
						$title           = $element->config['name'];
					} else {
						$elements_html[] = '<option value="' . $element->config['shortcode'] . '">' . $element->config['name'] . '</option>';
					}
				}
			}
			?>
<div class="jsn-bootstrap3">

	<div class="wr-widget-setting">
	<?php
	if ( ! $elements ) {
		echo '<p>' . sprintf( __( 'No elements have been created yet!  ', WR_PBL ) ) . '</p>';
		return;
	}
	?>
		<label
			for="<?php echo esc_attr( $this->get_field_id( 'wr_element' ) ); ?>"><?php _e( 'Element', WR_PBL ) ?>
		</label>
		<div
			class="form-group control-group clearfix combo-group wr-widget-box">
			<div class="controls">
				<div class="combo-item">
					<select class="wr_widget_select_elm"
						id="<?php echo esc_attr( $this->get_field_id( 'wr_element' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'wr_element' ) ); ?>">
						<?php
						// shortcode elements
						foreach ( $elements_html as $idx => $element ) {
							echo balanceTags( $element );
						}
						?>
					</select>
				</div>
				<div class="combo-item">
					<a id="wr_widget_edit_btn" class="wr_widget_edit_btn btn btn-icon"
						data-shortcode="<?php echo esc_attr( $selected_value ) ?>"><i
						class="icon-pencil"></i><i class="jsn-icon16 jsn-icon-loading"
						id="wr-widget-loading" style="display: none"></i> </a>
				</div>
				<input class="wr_shortcode_widget" type="hidden"
					id="<?php echo esc_attr( $this->get_field_id( 'wr_widget_shortcode' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'wr_widget_shortcode' ) ); ?>"
					value="<?php echo esc_attr( $wr_widget_shortcode ); ?>" />
				<div class="jsn-section-content jsn-style-light hidden"
					id="form-design-content">
					<div class="wr-pb-form-container jsn-layout">
						<input type="hidden" id="wr-select-media" value="" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
						<?php
		}

	}

}
