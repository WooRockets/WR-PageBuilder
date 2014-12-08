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

if ( ! class_exists( 'WR_QRCode' ) ) :

/**
 * Create QR Code element
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_QRCode extends WR_Pb_Shortcode_Element {
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
		$this->config['name']        = __( 'QR Code', WR_PBL );
		$this->config['cat']         = __( 'Extra', WR_PBL );
		$this->config['icon']        = 'wr-icon-qr-code';
		$this->config['description'] = __( 'QR code with data setting', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'default_content'  => __( 'QR Code', WR_PBL ),
			'data-modal-title' => __( 'QR Code', WR_PBL ),

			'admin_assets' => array(
		// Shortcode initialization
				'qrcode.js',
		),

			'frontend_assets' => array(
		// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',
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
					'name'          => __( 'Data', WR_PBL ),
					'id'            => 'qr_content',
					'type'          => 'text_area',
					'class'         => 'input-sm',
					'std'           => 'http://www.woorockets.com',
					'tooltip'       => __( 'Here you can input names, urls, phone numbers, email addresses or plain text', WR_PBL ),
					'exclude_quote' => '1',
				),
				array(
					'name'    => __( 'Image ALT Text', WR_PBL ),
					'id'      => 'qr_alt',
					'type'    => 'text_field',
					'class'   => 'input-sm',
					'std'     => __( 'Wordpress themes from www.woorockets.com', WR_PBL ),
					'tooltip' => __( 'Text tooltip appears when QR box is hovered through', WR_PBL ),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => __( 'Container Style', WR_PBL ),
					'id'      => 'qr_container_style',
					'type'    => 'radio',
					//'class'   => 'input-sm',
					'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_qr_container_style() ),
					'options' => WR_Pb_Helper_Type::get_qr_container_style(),
				),
				array(
					'name'    => __( 'Alignment', WR_PBL ),
					'id'      => 'qr_alignment',
					'class'   => 'input-sm',
					'type'    => 'radio_button_group',
					'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_text_align() ),
					'options' => WR_Pb_Helper_Type::get_text_align(),
				),
				array(
					'name'         => __( 'QR Code Size', WR_PBL ),
					'id'           => 'qrcode_sizes',
					'type'         => 'select',
					'class'        => 'input-mini-m input-sm wr-select2-editor',
					'std'          => '150',
					'options'      => array(
						'150' => __( '150', WR_PBL ),
						'200' => __( '200', WR_PBL ),
						'250' => __( '250', WR_PBL ),
						'300' => __( '300', WR_PBL ),
						'350' => __( '350', WR_PBL ),
					),
					'parent_class'    => 'combo-item input-append select-append input-group input-select-append wr-input-append',
					'append_text'     => 'px',
					'container_class' => 'combo-group',
					'disable_select2' => true
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
		$html_element = '';
		$arr_params   = ( shortcode_atts( $this->config['params'], $atts ) );
		extract( $arr_params );
		$qrcode_sizes  = ( $qrcode_sizes ) ? ( int ) $qrcode_sizes : 0;
		$cls_alignment = '';
		if ( strtolower( $arr_params['qr_alignment'] ) != 'inherit' ) {
			if ( strtolower( $arr_params['qr_alignment'] ) == 'left' ) {
				$cls_alignment = 'pull-left';
			}
			if ( strtolower( $arr_params['qr_alignment'] ) == 'right' ) {
				$cls_alignment = 'pull-right';
			}
			if ( strtolower( $arr_params['qr_alignment'] ) == 'center' ) {
				$cls_alignment = 'text-center';
			}
		}
		$class_img    = ( $qr_container_style != 'no-styling' ) ? "class='{$qr_container_style}'" : '';
		$qr_content   = str_replace( '<wr_quote>', '"', $qr_content );
		$image        = 'https://chart.googleapis.com/chart?chs=' . $qrcode_sizes . 'x' . $qrcode_sizes . '&cht=qr&chld=H|1&chl=' . $qr_content;
		$qr_alt       = ( ! empty( $qr_alt ) ) ? "alt='{$qr_alt}'" : '';
		$html_element = "<img src='{$image}' {$qr_alt} width='{$qrcode_sizes}' height='{$qrcode_sizes}' $class_img />";
		if ( $cls_alignment != '' ) {
			$html_element = "<div>{$html_element}</div>";
		}

		return $this->element_wrapper( $html_element, $arr_params, $cls_alignment );
	}
}

endif;
