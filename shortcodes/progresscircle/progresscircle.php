<?php
/**
 * @version    $Id$
 * @package    WR PageBuilder
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 woorockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support:  Feedback - http://www.woorockets.com
 */

if ( ! class_exists( 'WR_Progresscircle' ) ) :

/**
 * Progress circle element for WR PageBuilder.
 *
 * @since  1.0.0
 */
class WR_Progresscircle extends WR_Pb_Shortcode_Element {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	function __construct() {
		// Register 3rd-party assets
		add_filter( 'wr_pb_register_assets', array( &$this, 'register_assets' ) );

		// Let the parent class continue the construction
		parent::__construct();
	}

	/**
	 * Configure shortcode.
	 *
	 * @return  void
	 */
	function element_config() {
		$this->config['shortcode']   = strtolower( __CLASS__ );
		$this->config['name']        = __( 'Progress Circle', WR_PBL );
		$this->config['cat']         = __( 'Extra', WR_PBL );
		$this->config['icon']        = 'wr-icon-progress-circle';
		$this->config['description'] = __( 'Animated progress circle', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'default_content'  => __( 'Progress Circle', WR_PBL ),
			'data-modal-title' => __( 'Progress Circle', WR_PBL ),
			
			'admin_assets' => array(
				// Shortcode style and script initialization
				'wr-colorpicker.js',
				'progresscircle.js',
			),

			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',

				// Font IcoMoon
				'wr-pb-font-icomoon-css',

				// Lazy Load
				'wr-pb-jquery-lazyload-js',


				// Circliful
				'wr-pb-progress-circle-css',
				'wr-pb-progress-circle-js',

				// Shortcode style and script initialization
				'progresscircle_frontend.css',
				'progresscircle_frontend.js',
			),
		);

		// Use Ajax to speed up element settings modal loading speed
		$this->config['edit_using_ajax'] = true;
	}

	/**
	 * Define shortcode settings.
	 *
	 * @return  void
	 */
	function element_items() {
		$this->items = array(
			'content' =>array(

				array(
					'name'    => __( 'Text', WR_PBL ),
					'id'      => 'text',
					'type'    => 'text_field',
					'role'    => 'content',
					'std'     => __( 'Circle', WR_PBL),
				),
				array(
					'name'    => __( 'Description', WR_PBL ),
					'id'      => 'description',
					'type'    => 'text_field',
					'std'     => __( 'The circle information', WR_PBL),
				)
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'       => __( 'Percentage', WR_PBL ),
					'id'         => 'percent',
					'type'       => 'text_append',
					'type_input' => 'number',
					'class'      => 'input-mini',
					'std'        => '15',
					'append'     => '%',
					'validate'   => 'number',
				),
				array(
					'name'    => __( 'Foreground Color', WR_PBL ),
					'id'      => 'fg_color',
					'type'    => 'color_picker',
					'std'     => ' 	#556b2f',
				),
				array(
					'name'    => __( 'Background Color', WR_PBL ),
					'id'      => 'bg_color',
					'type'    => 'color_picker',
					'std'     => ' 	#eeeeee',
				),
				array(
					'name'       => __( 'Use Fill Color', WR_PBL ),
					'id'         => 'use_fill',
					'type'       => 'radio',
					'std'        => 'no',
					'has_depend' => '1',
					'options'    => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
				),
				array(
					'name'       => __( 'Fill Color', WR_PBL ),
					'id'         => 'fill_color',
					'type'       => 'color_picker',
					'std'        => '#ffffff',
					'dependency' => array( 'use_fill', '=', 'yes' )
				),
				array(
					'name'       => __( 'Circle Thickness', WR_PBL ),
					'id'         => 'width',
					'type'       => 'text_append',
					'type_input' => 'number',
					'class'      => 'input-mini',
					'std'        => '15',
					'append'     => 'px',
					'validate'   => 'number',
				),
				array(
					'name'       => __( 'Dimension', WR_PBL ),
					'id'         => 'dimension',
					'type'       => 'text_append',
					'type_input' => 'number',
					'class'      => 'input-mini',
					'std'        => '200',
					'append'     => 'px',
					'validate'   => 'number',
					'tooltip'    => __( 'Size of process circle, caculated by its diameter', WR_PBL )
				),
				array(
					'name'       => __( 'Font Size', WR_PBL ),
					'id'         => 'font_size',
					'type'       => 'text_append',
					'type_input' => 'number',
					'class'      => 'input-mini',
					'std'        => '15',
					'append'     => 'px',
					'validate'   => 'number',
				),
				array(
					'name'       => __( 'Icon', WR_PBL ),
					'id'         => 'icon',
					'type'       => 'icons',
					'std'        => '',
					'title_prepend_type' => 'icon',
				),
				array(
					'name'       => __( 'Show half', WR_PBL ),
					'id'         => 'is_half',
					'type'       => 'radio',
					'std'        => 'no',
					'options'    => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
				),
				array(
					'name'            => __( 'Margin', WR_PBL ),
					'container_class' => 'combo-group',
					'id'              => 'circle_margin',
					'type'            => 'margin',
					'extended_ids'    => array( 'circle_margin_top', 'circle_margin_bottom', 'circle_margin_left', 'circle_margin_right' ),
					'circle_margin_top'	   => array( 'std' => '10' ),
					'circle_margin_bottom' => array( 'std' => '10' ),
					'tooltip'              => __( 'External spacing with other elements', WR_PBL ),
				),				
				WR_Pb_Helper_Type::get_apprearing_animations(),
				WR_Pb_Helper_Type::get_animation_speeds(),				
			)
		);
	}

	/**
	 * Register 3rd-party assets.
	 *
	 * @param   array  $assets  Current assets array.
	 *
	 * @return  void
	 */
	public function register_assets( $assets ) {
		$assets['wr-pb-progress-circle-css'] = array(
			'src' => plugin_dir_url( __FILE__ ) . '/assets/3rd-party/jquery-circliful/css/jquery.circliful.css',
			'ver' => '0.1.5',
		);

		$assets['wr-pb-progress-circle-js'] = array(
			'src' => plugin_dir_url( __FILE__ ) . '/assets/3rd-party/jquery-circliful/js/jquery.circliful.min.js',
			'ver' => '0.1.5',
		);

		return $assets;
	}

	/**
	 * Generate HTML code from shortcode content.
	 *
	 * @param   array   $atts     Shortcode attributes.
	 * @param   string  $content  Current content.
	 *
	 * @return  string
	 */
	function element_shortcode_full( $atts = null, $content = null ) {
		$arr_params = shortcode_atts( $this->config['params'], $atts );
		extract( $arr_params );

		if ( isset( $arr_params['circle_margin_left'] ) )
			$arr_params['div_margin_left'] = $arr_params['circle_margin_left'];
		if ( isset( $arr_params['circle_margin_right'] ) )
			$arr_params['div_margin_right'] = $arr_params['circle_margin_right'];
		if ( isset( $arr_params['circle_margin_top'] ) )
			$arr_params['div_margin_top'] = $arr_params['circle_margin_top'];
		if ( isset( $arr_params['circle_margin_bottom'] ) )
			$arr_params['div_margin_bottom'] = $arr_params['circle_margin_bottom'];

		$html = '<div  class="wr-progress-circle" ';
		$html .= ( ! empty( $content ) )     ? ' data-text="' . $content . '"' : '';
		$html .= ( ! empty( $description ) ) ? ' data-info="' . $description . '"' : '';
		$html .= ( ! empty( $dimension ) )   ? ' data-dimension="' . $dimension . '"' : '';
		$html .= ( ! empty( $width ) )       ? ' data-width="' . $width . '"' : '';
		$html .= ( ! empty( $font_size ) )   ? ' data-fontsize="' . $font_size . '"' : '';
		$html .= ( ! empty( $percent ) )     ? ' data-percent="' . $percent . '"' : '';
		$html .= ( ! empty( $fg_color ) )    ? ' data-fgcolor="' . $fg_color . '"' : '';
		$html .= ( ! empty( $bg_color ) )    ? ' data-bgcolor="' . $bg_color . '"' : '';
		if ( $use_fill == 'yes' ) {
			$html .= ( ! empty( $fill_color ) )  ? ' data-fill="' . $fill_color . '"' : '';
		}
		if ( $is_half == 'yes' ) {
			$html .= ' data-type="half"';
		}
		$html .= ( ! empty( $icon ) )  ? ' data-icon="' . $icon . '"' : '';
		$html .= '></div>';

		return $this->element_wrapper( $html, $arr_params );
	}
}

endif;
