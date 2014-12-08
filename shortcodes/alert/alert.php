<?php
/**
 * @version	$Id$
 * @package	WR PageBuilder
 * @author	 WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 woorockets.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support:  Feedback - http://www.woorockets.com
 */

if ( ! class_exists( 'WR_Alert' ) ) :

/**
 * Create Alert element.
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_Alert extends WR_Pb_Shortcode_Element {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Configure shortcode.
	 *
	 * @return  void
	 */
	public function element_config() {
		$this->config['shortcode']   = strtolower( __CLASS__ );
		$this->config['name']        = __( 'Alert', 				WR_PBL );
		$this->config['cat']         = __( 'Typography', 		WR_PBL );
		$this->config['icon']        = 'wr-icon-alert';
		$this->config['description'] = __( 'Multiple Alert message box types', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',

		// Shortcode style and script initialization
				'alert_frontend.css',
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
	public function element_items() {
		$this->items = array(
			'content' => array(

				array(
					'name'  => __( 'Alert Content', WR_PBL ),
					'id'    => 'alert_content',
						'type'  => 'tiny_mce',
					'role'  => 'content',
					'rows'  => '12',
					'std'   => WR_Pb_Helper_Type::lorem_text(12),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => __( 'Type', WR_PBL ),
					'id'      => 'alert_style',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_alert_type() ),
					'options' => WR_Pb_Helper_Type::get_alert_type(),
				),
				array(
					'name'     => __( 'Show Icon', WR_PBL ),
					'id'       => 'show_icon',
					'type'     => 'radio',
					'std'      => 'no',
					'options'  => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
					'has_depend' => 1
				),
				array(
					'name' => __( 'Icon Size', WR_PBL ),
					'type' => array(
						array(
							'id'         => 'icon_size',
							'type'       => 'text_append',
							'type_input' => 'number',
							'class'      => 'input-mini',
							'std'        => '16',
							'append'     => 'px',
							'validate'   => 'number',
						),
					),
					'dependency'  => array( 'show_icon', '=', 'yes' ),
				),
				array(
					'name'		=> __( 'Allow to close', 		WR_PBL ),
					'id'		=> 'alert_close',
					'type'		=> 'radio',
					'std'		=> 'no',
					'options'	=> array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
					'tooltip'	=> __( 'Whether the customers can close the alert or not', WR_PBL ),
				),				
				WR_Pb_Helper_Type::get_apprearing_animations(),
				WR_Pb_Helper_Type::get_animation_speeds(),				
			)
		);
	}

	/**
	 * Generate HTML code from shortcode content.
	 *
	 * @param   array   $atts     Shortcode attributes.
	 * @param   string  $content  Current content.
	 *
	 * @return  string
	 */
	public function element_shortcode_full( $atts = null, $content = null ) {
		$html_element  = '';
		$arr_params	   = ( shortcode_atts( $this->config['params'], $atts ) );
		$alert_style   = ( ! $arr_params['alert_style'] ) ? '' : $arr_params['alert_style'];
		$alert_close   = ( ! $arr_params['alert_close'] || $arr_params['alert_close'] == 'no' ) ? '' : '<button type="button" class="wr-close close" data-dismiss="alert">&times;</button>';
		$icon_size     = ( ! $arr_params['icon_size'] ) ? '' : "style='font-size:{$arr_params['icon_size']}px; height:{$arr_params['icon_size']}px; width:{$arr_params['icon_size']}px; line-height:{$arr_params['icon_size']}px; float:left'";
		$icon          = '';
		if ( isset( $arr_params['show_icon'] ) && $arr_params['show_icon'] == 'yes' ) {
			switch( $alert_style ) {
				case 'alert-warning':
					$icon = "<i class='icon-warning' {$icon_size}></i>";
					break;
				case 'alert-success':
					$icon = "<i class='icon-checkmark' {$icon_size}></i>";
					break;
				case 'alert-info':
					$icon = "<i class='icon-help' {$icon_size}></i>";
					break;
				case 'alert-danger':
					$icon = "<i class='icon-cancel' {$icon_size}></i>";
					break;
			}
		}

		$html_element .= "<div class='alert wr-alert {$alert_style}'>";
		$html_element .= $icon;
		$html_element .= $alert_close;
		$html_element .= ( ! $content ) ? '' : $content;
		$html_element .= '</div>';

		return $this->element_wrapper( $html_element, $arr_params );
	}
}

endif;
