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

if ( ! class_exists( 'WR_Tab' ) ) :

/**
 * Create Tabs element
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_Tab extends WR_Pb_Shortcode_Parent {
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
		$this->config['shortcode']        = strtolower( __CLASS__ );
		$this->config['name']             = __( 'Tab', WR_PBL );
		$this->config['cat']              = __( 'Typography', WR_PBL );
		$this->config['icon']             = 'wr-icon-tab';
		$this->config['has_subshortcode'] = 'WR_Item_' . str_replace( 'WR_', '', __CLASS__ );
		$this->config['description']      = __( 'Tabbed content', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',
				// Font IcoMoon
				'wr-pb-font-icomoon-css',
				// Shortcode style
				'tab_frontend.css',
				'tab_frontend.js'
			),
			'admin_assets' => array(
				'wr-pb-joomlashine-iconselector-js',
				'tab.js',
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
			'action' => array(
				array(
					'id'      => 'btn_convert',
					'type'    => 'button_group',
					'bound'   => 0,
					'actions' => array(
						array(
							'std'         => __( 'Accordion', WR_PBL ),
							'action_type' => 'convert',
							'action'      => 'tab_to_accordion',
						),
						array(
							'std'         => __( 'Carousel', WR_PBL ),
							'action_type' => 'convert',
							'action'      => 'tab_to_carousel',
						),
						array(
							'std'         => __( 'List', WR_PBL ),
							'action_type' => 'convert',
							'action'      => 'tab_to_list',
						),
					)
				),
			),
			'content' => array(

				array(
					'id'            => 'tab_items',
					'type'          => 'group',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => '' ),
						array( 'std' => '' ),
					),
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'     => __( 'Open First', WR_PBL ),
					'id'       => 'initial_open',
					'type'     => 'text_number',
					'std'      => '1',
					'class'    => 'input-mini',
					'validate' => 'number',
					'tooltip'  => __( 'Tab to be opened at first load', WR_PBL ),
				),
				array(
					'name'       => __( 'Fade Effect', WR_PBL ),
					'id'         => 'fade_effect',
					'type'       => 'radio',
					'std'        => 'no',
					'options'    => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
					'has_depend' => '1',
				),
				array(
					'name'    => __( 'Tab Position', WR_PBL ),
					'id'      => 'tab_position',
					'type'    => 'radio_button_group',
					'class'   => 'input-sm',
					'std'     => 'top',
					'options' => array(
						'top'     => '<i class="wr-icon-tab-top" title="' . __( 'Top', WR_PBL ) . '"></i>',
						'bottom'  => '<i class="wr-icon-tab-bottom" title="' . __( 'Bottom', WR_PBL ) . '"></i>',
						'left'    => '<i class="wr-icon-tab-left" title="' . __( 'Left', WR_PBL ) . '"></i>',
						'right'   => '<i class="wr-icon-tab-right" title="' . __( 'Right', WR_PBL ) . '"></i>',
					),
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
		$arr_params   = ( shortcode_atts( $this->config['params'], $atts ) );
		$initial_open = intval( $arr_params['initial_open'] );
		$tab_position = ( $arr_params['tab_position'] );

		$random_id    = WR_Pb_Utils_Common::random_string();

		$tab_navigator   = array();
		$tab_navigator[] = '<ul class="nav nav-tabs">';
		$sub_shortcode   = do_shortcode( $content );


		$items         = explode( '<!--seperate-->', $sub_shortcode );
		$items         = array_filter( $items );
		$initial_open  = ( $initial_open > count( $items ) ) ? 1 : $initial_open;
		$fade_effect = '';
		if ( $arr_params['fade_effect'] == 'yes' ) {
			$fade_effect = 'fade in';
		}
		foreach ( $items as $idx => $item ) {
			// Extract icon & heading
			$ex_heading = explode( '<!--heading-->', $item );
			$ex_icon    = explode( '<!--icon-->', isset ( $ex_heading[1] ) ? $ex_heading[1] : '' );

			$new_key = $random_id . $idx;
			$active  = ( $idx + 1 == $initial_open ) ? 'active' : '';

			$item            = isset ( $ex_icon[1] ) ? $ex_icon[1] : '';
			$item            = str_replace( '{index}', $new_key, $item );
			$item            = str_replace( '{active}', $active, $item );
			$item            = str_replace( '{fade_effect}', $fade_effect, $item );
			$items[ $idx ] = $item;

			$icon    = ! empty ( $ex_icon[0] ) ?  "<i class='{$ex_icon[0]}'></i>&nbsp;" : '';
			$heading = ! empty ( $ex_heading[0] ) ? $ex_heading[0] : ( __( 'Tab Item ' ) . ' ' . $idx );
			WR_Pb_Helper_Functions::heading_icon( $heading, $icon );
			$active_li       = ( $idx + 1 == $initial_open ) ? "class='active'" : '';
			$tab_navigator[] = "<li $active_li><a href='#pane$new_key'>{$icon}{$heading}</a></li>";
		}
		$sub_shortcode = implode( '', $items );
		$tab_content   = "<div class='tab-content'>$sub_shortcode</div>";
		// update min-height of each tab content in case tap position is left/right
		if ( in_array( $tab_position, array( 'left', 'right' ) ) ) {
			$min_height  = 36 * count( $items );
			$tab_content = WR_Pb_Utils_Placeholder::remove_placeholder( $tab_content, 'custom_style', "style='min-height: {$min_height}px'" );
		}

		$tab_navigator[] = '</ul>';

		$tab_positions = array( 'top' => '', 'left' => 'tabs-left', 'right' => 'tabs-right', 'bottom' => 'tabs-below' );
		$extra_class = $tab_positions[ $tab_position ];
		if ( $tab_position == 'bottom' ) {
			$tab_content .= implode( '', $tab_navigator );
		} else {
			$tab_content = implode( '', $tab_navigator ) . $tab_content;
		}


		$html_element = "<div class='tabbable $extra_class' id='tab_{ID}'>$tab_content</div>";
		$html_element = str_replace( '{ID}', "$random_id", $html_element );
		return $this->element_wrapper( $html_element, $arr_params );
	}
}

endif;
