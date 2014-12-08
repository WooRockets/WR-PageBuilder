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

if ( ! class_exists( 'WR_Button' ) ) :

/**
 * Create button elements
 *
 * @package  WR PageBuilder Shortcodes
 * @since    2.1.0
 */
class WR_Button extends WR_Pb_Shortcode_Element {

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
		$this->config['name']        = __( 'Button', WR_PBL );
		$this->config['cat']         = __( 'Extra', WR_PBL );
		$this->config['icon']        = 'wr-icon-button';
		$this->config['description'] = __( 'Eye catching button for hyperlinks', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'default_content'  => __( 'Button', WR_PBL ),
			'data-modal-title' => __( 'Button', WR_PBL ),

			'admin_assets' => array(
		// Shortcode initialization
				'wr-linktype.js',
				'button.js',
		),

			'frontend_assets' => array(
		// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',

		// Font IcoMoon
				'wr-pb-font-icomoon-css',

		// Fancy Box
				'wr-pb-jquery-fancybox-css',
				'wr-pb-jquery-fancybox-js',
		// Shortcode style
				'button_frontend.css',
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
					'name'    => __( 'Text', WR_PBL ),
					'id'      => 'button_text',
					'type'    => 'text_field',
					'std'     => __( 'Button', WR_PBL ),
					'role'    => 'title',
		),
		array(
					'name'       => __( 'On Click', WR_PBL ),
					'id'         => 'link_type',
					'type'       => 'select',
					'class'      => 'input-sm',
					'std'        => 'url',
					'options'    => WR_Pb_Helper_Type::get_link_types(),
					'has_depend' => '1',
					'tooltip' => __( 'Select link types: link to post, page, category...', WR_PBL ),
		),
		array(
					'name'       => __( 'URL', WR_PBL ),
					'id'         => 'button_type_url',
					'type'       => 'text_field',
					'class'      => 'input-sm',
					'std'        => 'http://',
					'dependency' => array( 'link_type', '=', 'url' ),
		),
		array(
					'name'  => __( 'Single Item', WR_PBL ),
					'id'    => 'single_item',
					'type'  => 'type_group',
					'std'   => '',
					'items' => WR_Pb_Helper_Type::get_single_item_button_bar(
						'link_type',
		array(
							'type'         => 'items_list',
							'options_type' => 'select',
							'ul_wrap'      => false,
		)
		),
		),
		array(
					'name'       => __( 'Open in', WR_PBL ),
					'id'         => 'open_in',
					'type'       => 'select',
					'class'      => 'input-sm',
					'std'        => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_open_in_options() ),
					'options'    => WR_Pb_Helper_Type::get_open_in_options(),
					'dependency' => array( 'link_type', '!=', 'no_link' ),
					'tooltip' => __( 'Select type of opening action when click on element', WR_PBL ),
		),
		array(
					'name'      => __( 'Icon', WR_PBL ),
					'id'        => 'icon',
					'type'      => 'icons',
					'std'       => '',
					'role'      => 'title_prepend',
					'title_prepend_type' => 'icon',
		),
		),
			'styling' => array(
			array(
						'type' => 'preview',
				),
				array(
					'name'    => __( 'Alignment', WR_PBL ),
					'id'      => 'button_alignment',
					'type'    => 'radio_button_group',
					'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_text_align() ),
					'options' => WR_Pb_Helper_Type::get_text_align(),
					'class'   => 'input-sm',
				),
				array(
					'name'            => __( 'Margin', WR_PBL ),
					'container_class' => 'combo-group',
					'id'              => 'button_margin',
					'type'            => 'margin',
					'extended_ids'    => array( 'button_margin_top', 'button_margin_right', 'button_margin_bottom', 'button_margin_left' ),
					'button_margin_top'    => array( 'std' => '0' ),
					'button_margin_right'  => array( 'std' => '0' ),
					'button_margin_bottom' => array( 'std' => '0' ),
					'button_margin_left'   => array( 'std' => '0' ),
					'tooltip'              => __( 'External spacing with other elements', WR_PBL )
				),
				array(
							'name'    => __( 'Size', WR_PBL ),
							'id'      => 'button_size',
							'type'    => 'select',
							'class'   => 'input-sm',
							'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_button_size() ),
							'options' => WR_Pb_Helper_Type::get_button_size(),
				),
				array(
							'name'    => __( 'Color', WR_PBL ),
							'id'      => 'button_color',
							'type'    => 'select',
							'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_button_color() ),
							'options' => WR_Pb_Helper_Type::get_button_color(),
							'container_class'   => 'color_select2',
							'tooltip' => __( 'Select the color of the button', WR_PBL ),
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
		$arr_params   = shortcode_atts( $this->config['params'], $atts );
		extract( $arr_params );
		$button_text  = ( ! $button_text ) ? '' : $button_text;
		$button_size  = ( ! $button_size || strtolower( $button_size ) == 'default' ) ? '' : $button_size;
		$button_color = ( ! $button_color || strtolower( $button_color ) == 'default' ) ? '' : $button_color;
		$button_icon  = ( ! $icon ) ? '' : "<i class='{$icon}'></i>";
		$tag          = 'a';
		$href         = '';
		$single_item  = explode( '__#__', $single_item );
		$single_item  = $single_item[0];
		if ( ! empty( $link_type ) ) {
			$taxonomies = WR_Pb_Helper_Type::get_public_taxonomies();
			$post_types = WR_Pb_Helper_Type::get_post_types();
			// single post
			if ( array_key_exists( $link_type, $post_types ) ) {
				$permalink = home_url() . "/?p=$single_item";
				$href      = ( ! $single_item ) ? ' href="#"' : " href='{$permalink}'";
			}
			// taxonomy
			else if ( array_key_exists( $link_type, $taxonomies ) ) {
				$permalink = get_term_link( intval( $single_item ), $link_type );
				if ( ! is_wp_error( $permalink ) )
				$href = ( ! $single_item ) ? ' href="#"' : " href='{$permalink}'";
			}
			else {
				switch ( $link_type ) {
					case 'no_link':
						$tag = 'button';
						break;
					case 'url':
						$href = ( ! $button_type_url ) ? ' href="#"' : " href='{$button_type_url}'";
						break;
				}
			}
		}
		$target = '';
		if ( $open_in ) {
			switch ( $open_in ) {
				case 'current_browser':
					$target = '';
					break;
				case 'new_browser':
					$target = ' target="_blank"';
					break;
				case 'new_window':
					$cls_button_fancy = 'wr-button-new-window';
					$script = WR_Pb_Helper_Functions::new_window( ".$cls_button_fancy", array( 'width' => '75%', 'height' => '75%' ) );
					break;
				case 'lightbox':
					$cls_button_fancy = 'wr-button-fancy';
					$script = WR_Pb_Helper_Functions::fancybox( ".$cls_button_fancy", array( 'type' => 'iframe', 'width' => '75%', 'height' => '75%' ) );
					break;
			}
		}
		$button_type      = ( $tag == 'button' ) ? " type='button'" : '';
		$cls_button_fancy = ( ! isset( $cls_button_fancy ) ) ? '' : $cls_button_fancy;
		$script           = ( ! isset( $script ) ) ? '' : $script;

		$cls_alignment = $custom_style = '';
		if ( strtolower( $arr_params['button_alignment'] ) != 'inherit' ) {
			if ( strtolower( $arr_params['button_alignment'] ) == 'left' )
				$cls_alignment = 'pull-left';
			if ( strtolower( $arr_params['button_alignment'] ) == 'right' )
				$cls_alignment = 'pull-right';
			if ( strtolower( $arr_params['button_alignment'] ) == 'center' )
				$custom_style = ';text-align:center;';
		}

		if ( isset( $arr_params['button_margin_top'] ) )
			$arr_params['div_margin_top'] = $arr_params['button_margin_top'];
		if ( isset( $arr_params['button_margin_left'] ) )
			$arr_params['div_margin_left'] = $arr_params['button_margin_left'];
		if ( isset( $arr_params['button_margin_right'] ) )
			$arr_params['div_margin_right'] = $arr_params['button_margin_right'];
		if ( isset( $arr_params['button_margin_bottom'] ) )
			$arr_params['div_margin_bottom'] = $arr_params['button_margin_bottom'];

		$html_element      = $script . "<{$tag} class='btn {$cls_alignment} {$button_size} {$button_color} {$cls_button_fancy}'{$href}{$target}{$button_type}>{$button_icon}{$button_text}</{$tag}>";
		return $this->element_wrapper( $html_element, $arr_params, null, $custom_style );
	}

}

endif;