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

if ( ! class_exists( 'WR_Share' ) ) :

/**
 * Create Share element.
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_Share extends WR_Pb_Shortcode_Element {
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
		$this->config['name']        = __( 'Share', 				WR_PBL );
		$this->config['cat']         = __( 'Typography', 		WR_PBL );
		$this->config['icon']        = 'wr-icon-alert';
		$this->config['description'] = __( 'Share infographic and another code', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',

		// Shortcode style and script initialization
				'share_frontend.css',
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
					'name'  => __( 'Share Content', WR_PBL ),
					'id'    => 'share_content',
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
					'name'    => __( 'Align', WR_PBL ),
					'id'      => 'share_align',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_share_type() ),
					'options' => WR_Pb_Helper_Type::get_share_type(),
				),
                array(
                    'name'    => __( 'Text Align', WR_PBL ),
                    'id'      => 'share_text_align',
                    'type'    => 'select',
                    'class'   => 'input-sm',
                    'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_share_type() ),
                    'options' => WR_Pb_Helper_Type::get_share_type(),
                ),
				array(
					'name'     => __( 'Height', WR_PBL ),
					'id'         => 'share_height',
					'type'       => 'text_append',
					'type_input' => 'number',
					'class'      => 'input-mini',
					'std'        => '300',
					'append'     => 'px',
					'validate'   => 'number',
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
		$share_align   = ( ! $arr_params['share_align'] ) ? '' : $arr_params['share_align'];
        $share_talign   = ( ! $arr_params['share_text_align'] ) ? '' : $arr_params['share_text_align'];
		$share_width   = ( ! $arr_params['share_width'] ) ? '' : $arr_params['share_width'];
        $share_height  = ( ! $arr_params['share_height'] ) ? '' : $arr_params['share_height'];
        $share_style   = "style='width: 100%; height: {$share_height}px; float: {$share_align}'";

        $html_element .= "<div class='tb_align' style='text-align:{$share_talign}'>";
		$html_element .= "<textarea {$share_style} onclick='this.focus();this.select();'>";
		$html_element .= ( ! $content ) ? '' : $content;
		$html_element .= '</textarea>';
        $html_element .= "</div>";

		return $this->element_wrapper( $html_element, $arr_params );
	}
}

endif;
