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

if ( ! class_exists( 'WR_Tooltip' ) ) :

/**
 * Create Tooltip element
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_Tooltip extends WR_Pb_Shortcode_Element {
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
		$this->config['name']        = __( 'Tooltip', WR_PBL );
		$this->config['cat']         = __( 'Typography', WR_PBL );
		$this->config['icon']        = 'wr-icon-tooltip';
		$this->config['description'] = __( 'Tooltip with flexible setting', WR_PBL );

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
			'content' => array(

				array(
					'name'    => __( 'Parent Element Text', WR_PBL ),
					'id'      => 'text',
					'type'    => 'text_field',
					'class'   => 'input-sm',
					'std'     => __( 'Your text', WR_PBL ),
				),
				array(
					'name'    => __( 'Tooltip Content', WR_PBL ),
					'id'      => 'tooltip_content',
					'role'    => 'content',
					'type'    => 'tiny_mce',
					'std'     => __( 'Your tooltip content', WR_PBL ),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => __( 'Tooltip Position', WR_PBL ),
					'id'      => 'position',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_full_positions() ),
					'options' => WR_Pb_Helper_Type::get_full_positions(),
				),
				array(
					'name'       => __( 'Make Parent Element a Button', WR_PBL ),
					'id'         => 'tooltips_button',
					'type'       => 'radio',
					'std'        => 'no',
					'options'    => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
					'has_depend' => '1',
					'tooltip' => __( 'Create button from text', WR_PBL ),
				),
				array(
					'name' => __( 'Button Color', WR_PBL ),
					'type' => array(
					array(
							'id'      => 'button_color',
							'type'    => 'select',
							'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_button_color() ),
							'options' => WR_Pb_Helper_Type::get_button_color(),
						),
					),
					'dependency' => array( 'tooltips_button', '=', 'yes' ),
					'container_class'   => 'color_select2',
				),
				array(
					'name'            => __( 'Delay', WR_PBL ),
					'container_class' => 'combo-group',
					'type'            => array(
						array(
							'id'            => 'show',
							'type'          => 'text_append',
							'type_input'    => 'number',
							'class'         => 'input-mini',
							'std'           => '500',
							'append_before' => 'Show',
							'append'        => 'ms',
							'parent_class'  => 'input-group-inline',
							'validate'      => 'number',
						),
						array(
							'id'            => 'hide',
							'type'          => 'text_append',
							'type_input'    => 'number',
							'class'         => 'input-mini',
							'std'           => '100',
							'append_before' => 'Hide',
							'append'        => 'ms',
							'parent_class'  => 'input-group-inline',
							'validate'      => 'number',
						),
					),
					'tooltip' => __( 'Set time (ms) to show/ hide tooltip when hover/ leave', WR_PBL ),
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
		$random_id  = WR_Pb_Utils_Common::random_string();
		$tooltip_id = "tooltip_$random_id";
		// don't allow to run shortcode
		$content = str_replace( '[', '[[', $content );
		$content = str_replace( ']', ']]', $content );
		$content = html_entity_decode( $content );
		$content = preg_replace( '/\s+/', ' ', trim( $content ) );

		$button_color = ( ! $button_color || strtolower( $button_color ) == 'default' ) ? '' : $button_color;
		$position     = strtolower( $position );
		$delay_show   = ! empty( $show ) ? intval( $show ) : 500;
		$delay_hide   = ! empty( $hide ) ? intval( $hide ) : 100;
		$direction    = array( 'top' => 'top', 'bottom' => 'bottom', 'left' => 'left', 'right' => 'right' );
		$script = "<script type='text/javascript'>( function ($) {
				$( document ).ready( function ()
				{
					$('#$tooltip_id').click(function(e){
						e.preventDefault();
					})
					$('#$tooltip_id').tooltip({
						html: true,
						delay: { show: $delay_show, hide: $delay_hide },
						placement: '{$direction[$position]}'
					})
				});
			} )( jQuery )</script>";
		if ( $tooltips_button == 'no' ) {
			$html = "<a id='$tooltip_id' class='wr-label-des-tipsy' title='$content' href='#'>$text</a>";
		} else {
			$html = "<a id='$tooltip_id' class='wr-label-des-tipsy btn {$button_color}' title='$content' href='#'>$text</a>";
		}
		$html = $html . $script;
		if ( is_admin() ) {
			$custom_style = "style='margin-top: 50px;'";
			$html_element = "<center $custom_style>$html</center>";
		} else
		$html_element = $html;

		return $this->element_wrapper( $html_element, $arr_params );
	}
}

endif;
