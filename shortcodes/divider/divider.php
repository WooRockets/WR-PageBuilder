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

if ( ! class_exists( 'WR_Divider' ) ) :

/**
 * Horizontal line element.
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_Divider extends WR_Pb_Shortcode_Element {
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
		$this->config['name']        = __( 'Divider', WR_PBL );
		$this->config['cat']         = __( 'Extra', WR_PBL );
		$this->config['icon']        = 'wr-icon-divider';
		$this->config['description'] = __( 'Horizontal line for dividing sections', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(

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
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name' => __( 'Border', WR_PBL ),
					'type' => array(
						array(
							'id'           => 'div_border_width',
							'type'         => 'text_append',
							'type_input'   => 'number',
							'class'        => 'input-mini',
							'std'          => '2',
							'append'       => 'px',
							'validate'     => 'number',
							'parent_class' => 'combo-item input-append-inline',
						),
						array(
							'id'           => 'div_border_style',
							'type'         => 'select',
							'class'        => 'input-sm wr-border-type',
							'std'          => 'solid',
							'options'      => WR_Pb_Helper_Type::get_border_styles(),
							'parent_class' => 'combo-item'
						),
						array(
							'id'           => 'div_border_color',
							'type'         => 'color_picker',
							'std'          => '#E0DEDE',
							'parent_class' => 'combo-item',
						),
					),
					'container_class' => 'combo-group',
				),
				array(
					'name'         => __( 'Divider Width', WR_PBL ),
					'id'           => 'divider_width',
					'type'         => 'select',
					'class'        => 'input-mini-m input-sm wr-select2-editor',
					'std'          => '100',
					'options'      => array(
						'100' => __( '100', WR_PBL ),
						'80'  => __( '80', WR_PBL ),
						'70'  => __( '70', WR_PBL ),
						'60'  => __( '60', WR_PBL ),
						'50'  => __( '50', WR_PBL ),
					),
					'parent_class'    => 'combo-item input-append select-append input-group input-select-append wr-input-append',
					'append_text'     => '%',
					'container_class' => 'combo-group',
					'disable_select2' => true
				),
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
		$arr_params = shortcode_atts( $this->config['params'], $atts );
		extract( $arr_params );
		$styles = array();
		
		$divider_width  = ( $divider_width ) ? ( int ) $divider_width : '';
		if ( $divider_width ) {
			$styles[] = 'width: ' . intval( $divider_width ) . '%';
		}
		if ( $div_border_width ) {
			$styles[] = 'border-bottom-width:' . intval( $div_border_width ) . 'px';
		}
		if ( $div_border_style ) {
			$styles[] = 'border-bottom-style:' . $div_border_style;
		}
		if ( $div_border_color ) {
			$styles[] = 'border-bottom-color:' . urldecode( $div_border_color );
		}
		if ( $div_margin_top ) {
			$styles[] = 'margin-top:' . intval( $div_margin_top ) . 'px';
		}
		if ( $div_margin_bottom ) {
			$styles[] = 'margin-bottom:' . intval( $div_margin_bottom ) . 'px';
		}
		if ( count( $styles ) > 0 ) {
			$html_element = '<div style="' . implode( ';', $styles ) . '"></div>';
		} else {
			$html_element = '';
		}
		return $this->element_wrapper( $html_element, $arr_params );
	}
}

endif;
