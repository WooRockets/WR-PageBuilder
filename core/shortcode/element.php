<?php
/**
 * @version	$Id$
 * @package	WR PageBuilder
 * @author	 WooRockets Team <support@www.woorockets.com>
 * @copyright  Copyright (C) 2012 www.woorockets.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.woorockets.com
 * Technical Support:  Feedback - http://www.www.woorockets.com
 */
/*
 * Parent class for normal elements
 */

class WR_Pb_Shortcode_Element extends WR_Pb_Shortcode_Common {

	public function __construct() {
		$this->type = 'element';
		$this->config['el_type'] = 'element';

		$this->element_config();

		// add shortcode
		add_shortcode( $this->config['shortcode'], array( &$this, 'element_shortcode' ) );

	}

	/**
	 * Method to call neccessary functions for initialyzing the backend
	 */
	public function init_element()
	{
		$this->element_items();
		$this->element_items_extra();
		$this->shortcode_data();

		do_action( 'wr_pb_element_init' );

		parent::__construct();

		// enqueue assets for current element in backend (modal setting iframe)
		if ( WR_Pb_Helper_Functions::is_modal_of_element( $this->config['shortcode'] ) ) {
			add_action( 'pb_admin_enqueue_scripts', array( &$this, 'enqueue_assets_modal' ) );
		}

		// enqueue assets for current element in backend (preview iframe)
		if ( WR_Pb_Helper_Functions::is_preview() ) {
			add_action( 'pb_admin_enqueue_scripts', array( &$this, 'enqueue_assets_frontend' ) );
		}
	}

	/**
	 * Custom assets for frontend
	 */
	public function custom_assets_frontend() {
		// enqueue custom assets here
	}

	/**
	 * Enqueue scripts for frontend
	 */
	public function enqueue_assets_frontend() {
		WR_Pb_Helper_Functions::shortcode_enqueue_assets( $this, 'frontend_assets', '_frontend' );
	}

	/**
	 * Enqueue scripts for modal setting iframe
	 *
	 * @param type $hook
	 */
	public function enqueue_assets_modal( $hook ) {
		WR_Pb_Helper_Functions::shortcode_enqueue_assets( $this, 'admin_assets', '' );
	}

	/**
	 * Define configuration information of shortcode
	 */
	public function element_config() {

	}

	/**
	 * Define setting options of shortcode
	 */
	public function element_items() {

	}

	/**
	 * Add more options to all elements
	 */
	public function element_items_extra() {
		$shotcode_name = $this->config['shortcode'];

		$disable_el = array(
			'name' => __( 'Disable', WR_PBL ),
			'id' => 'disabled_el',
			'type' => 'radio',
			'std' => 'no',
			'options' => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
			'wrap_class' => 'form-group control-group hidden clearfix',
		);

		// if not child element
		if ( strpos( $shotcode_name, 'item_' ) === false || ! empty( $this->config['use_wrapper'] ) ) {
			$css_wrapper = array(
				'name'    => __( 'Advanced', WR_PBL ),
				'id'      => '',
				'type'    => 'fieldset',
			);
			$css_suffix = array(
				'name'    => __( 'CSS Class', WR_PBL ),
				'id'      => 'css_suffix',
				'type'    => 'text_field',
				'std'     => __( '', WR_PBL ),
				'tooltip' => __( 'Custom CSS Class for the wrapper div of this element', WR_PBL )
			);
			$id_wrapper = array(
				'name'    => __( 'CSS ID', WR_PBL ),
				'id'      => 'id_wrapper',
				'type'    => 'text_field',
				'std'     => __( '', WR_PBL ),
				'tooltip' => __( 'Custom CSS ID for the wrapper div of this element', WR_PBL ),
			);
		}

		// Copy style from other element.
		$style_copy = array(
			'name'    => __( 'Copy Style from...', WR_PBL ),
			'id'      => 'copy_style_from',
			'type'    => 'select',
			'options' => array( '0' => __( 'Select element', WR_PBL ) ),
			'std'     => __( '0', WR_PBL ),
			'tooltip' => __( 'Copy Styling prameters from other same type element', WR_PBL ),
		);

		// Add Element Title
		if ( isset ( $this->items['content'] ) && strpos( $shotcode_name, 'item_' ) === false ) {
			$this->items['content'] = array_merge(
				array(
					array(
						'name'    => __( 'Element Title', WR_PBL ),
						'id'      => 'el_title',
						'type'    => 'text_field',
						'class'   => 'input-sm',
						'std'     => __( '', WR_PBL ),
						'role'    => 'title',
						'tooltip' => __( 'Set title for current element for identifying easily', WR_PBL )
					),
				),
				$this->items['content']
			);
		}

		if ( isset ( $this->items['styling'] ) ) {
			$this->items['styling'] = array_merge(
				$this->items['styling'], array(
					$css_wrapper,
					$css_suffix,
					$id_wrapper,
					$disable_el,
					// always at the end of array
					array(
						'name'			=> __( 'Margin', WR_PBL ),
						'container_class' 	=> 'combo-group',
						'id'			=> 'div_margin',
						'type'			=> 'margin',
						'extended_ids'	=> array( 'div_margin_top', 'div_margin_bottom', 'div_margin_left', 'div_margin_right' ),
						'div_margin_top'	=> array( 'std' => '0' ),
						'div_margin_bottom'	=> array( 'std' => '25' ),
						'div_margin_left'   => array( 'std' => '0' ),
						'div_margin_right'  => array( 'std' => '0' ),
						'margin_elements'	=> 't, b, l, r',
						'tooltip' 			=> __( 'External spacing with other elements', 	WR_PBL )
					),
				)
			);

			array_unshift( $this->items['styling'], $style_copy );
		} else {
			if ( isset ( $this->items['Notab'] ) ) {
				$this->items['Notab'] = array_merge(
					$this->items['Notab'], array(
						$css_suffix,
						$id_wrapper,
						$disable_el,
					)
				);
			}
		}
	}

	/**
	 * DEFINE html structure of shortcode in Page Builder area
	 *
	 * @param string $content
	 * @param string $shortcode_data: string stores params (which is modified default value) of shortcode
	 * @param string $el_title: Element Title used to identifying elements in WR PageBuilder
	 * @param int $index
	 * @param bool $inlude_sc_structure
	 * @param array $extra_params
	 * Ex:  param-tag=h6&param-text=Your+heading&param-font=custom&param-font-family=arial
	 * @return string
	 */
	public function element_in_pgbldr( $content = '', $shortcode_data = '', $el_title = '', $index = '', $inlude_sc_structure = true, $extra_params = array() ) {
		// Init neccessary data to render element in backend.
		$this->init_element();

		$shortcode		  = $this->config['shortcode'];
		$is_sub_element   = ( isset( $this->config['sub_element'] ) ) ? true : false;
		$parent_shortcode = ( $is_sub_element ) ? str_replace( 'wr_item_', '', $shortcode ) : $shortcode;
		$type			  = ! empty( $this->config['el_type'] ) ? $this->config['el_type'] : 'widget';

		// Empty content if this is not sub element
		if ( ! $is_sub_element )
		$content = '';

		$exception   = isset( $this->config['exception'] ) ? $this->config['exception'] : array();
		$content     = ( isset( $exception['default_content'] ) ) ? $exception['default_content'] : $content;
		$modal_title = '';
		// if is widget
		if ( $type == 'widget' ) {
			global $Wr_Pb_Widgets;
			if ( isset( $Wr_Pb_Widgets[$shortcode] ) && is_array( $Wr_Pb_Widgets[$shortcode] ) && isset( $Wr_Pb_Widgets[$shortcode]['identity_name'] ) ) {
				$modal_title = $Wr_Pb_Widgets[$shortcode]['identity_name'];
				$content     = $this->config['exception']['data-modal-title'] = $modal_title;
			}
		}

		// if content is still empty, Generate it
		if ( empty( $content ) ) {
			if ( ! $is_sub_element )
			$content = ucfirst( str_replace( 'wr_', '', $shortcode ) );
			else {
				if ( isset( $exception['item_text'] ) ) {
					if ( ! empty( $exception['item_text'] ) )
					$content = WR_Pb_Utils_Placeholder::add_placeholder( $exception['item_text'] . ' %s', 'index' );
				} else
				$content = WR_Pb_Utils_Placeholder::add_placeholder( ( __( ucfirst( $parent_shortcode ), WR_PBL ) . ' ' . __( 'Item', WR_PBL ) ) . ' %s', 'index' );
			}
		}
		$content = ! empty( $el_title ) ? ( $content . ': ' . "<span>$el_title</span>" ) : $content;

		// element name
		if ( $type == 'element' ) {
			if ( ! $is_sub_element )
			$name = ucfirst( str_replace( 'wr_', '', $shortcode ) );
			else
			$name = __( ucfirst( $parent_shortcode ), WR_PBL ) . ' ' . __( 'Item', WR_PBL );
		}
		else {
			$name = $content;
		}
		if ( empty($shortcode_data) )
		$shortcode_data = $this->config['shortcode_structure'];

		// Process index for subitem element
		if ( ! empty( $index ) ) {
			$shortcode_data = str_replace( '_WR_INDEX_' , $index, $shortcode_data );
		}

		$shortcode_data  = stripslashes( $shortcode_data );
		$element_wrapper = ! empty( $exception['item_wrapper'] ) ? $exception['item_wrapper'] : ( $is_sub_element ? 'li' : 'div' );
		$content_class   = ( $is_sub_element ) ? 'jsn-item-content' : 'wr-pb-element';
		$modal_title     = empty ( $modal_title ) ? ( ! empty( $exception['data-modal-title'] ) ? "data-modal-title='{$exception['data-modal-title']}'" : '' ) : $modal_title;
		$element_type    = "data-el-type='$type'";				
		$edit_using_ajax = $this->config['edit_using_ajax'] ? sprintf( "data-using-ajax='%s'", esc_attr( $this->config['edit_using_ajax'] ) ) : '';
		
		$data = array(
			'element_wrapper' => $element_wrapper,
			'modal_title' => $modal_title,
			'element_type' => $element_type,
			'edit_using_ajax' => $edit_using_ajax,
			'edit_inline' => isset( $this->config['edit_inline'] ) ? 1 : 0,
			'name' => $name,
			'shortcode' => $shortcode,
			'shortcode_data' => $shortcode_data,
			'content_class' => $content_class,
			'content' => $content,
			'action_btn' => empty( $exception['action_btn'] ) ? '' : $exception['action_btn'],
			'is_sub_element' => $is_sub_element,
		);
		// Merge extra params if it exists.
		if ( ! empty( $extra_params ) ) {
			$data = array_merge( $data, $extra_params );
		}
		$extra = array();
		if ( isset( $this->config['exception']['disable_preview_container'] ) ) {
			$extra = array(
				'has_preview' => FALSE,
			);
		}
		$data = array_merge( $data, $extra );
		$html_preview = WR_Pb_Helper_Functions::get_element_item_html( $data, $inlude_sc_structure );
		return array(
		$html_preview
		);
	}

	/**
	 * DEFINE shortcode content
	 *
	 * @param array $atts
	 * @param string $content
	 */
	public function element_shortcode_full( $atts = null, $content = null ) {

	}

	/**
	 * return shortcode content: if shortcode is disable, return empty
	 *
	 * @param array $atts
	 * @param string $content
	 */
	public function element_shortcode( $atts = null, $content = null ) {
		$this->init_element();

		$prefix = WR_Pb_Helper_Functions::is_preview() ? 'pb_admin' : 'wp';

		// enqueue custom assets at footer of frontend/backend
		add_action( "{$prefix}_footer", array( &$this, 'custom_assets_frontend' ) );

		$arr_params = ( shortcode_atts( $this->config['params'], $atts ) );
		if ( $arr_params['disabled_el'] == 'yes' ) {
			if ( WR_Pb_Helper_Functions::is_preview() ) {
				return ''; //_e( 'This element is deactivated. It will be hidden at frontend', WR_PBL );
			}
			return '';
		}

		// enqueue script for current element in frontend
		add_action( 'wp_footer', array( &$this, 'enqueue_assets_frontend' ), 1 );
		// get full shortcode content
		$string  = htmlentities( $content, null, 'utf-8' );
		$content = str_replace( "&nbsp;", "", $string );
		$content = html_entity_decode( $content );

		return $this->element_shortcode_full( $atts, $content );
	}

	/**
	 * Wrap output html of a shortcode
	 *
	 * @param array $arr_params
	 * @param string $html_element
	 * @param string $extra_class
	 * @return string
	 */
	public function element_wrapper( $html_element, $arr_params, $extra_class = '', $custom_style = '' ) {
		$shortcode_name = WR_Pb_Helper_Shortcode::shortcode_name( $this->config['shortcode'] );
		// extract margin here then insert inline style to wrapper div
		$styles = array();
		if ( ! empty ( $arr_params['div_margin_top'] ) ) {
			$styles[] = 'margin-top:' . intval( $arr_params['div_margin_top'] ) . 'px';
		}
		if ( ! empty ( $arr_params['div_margin_bottom'] ) ) {
			$styles[] = 'margin-bottom:' . intval( $arr_params['div_margin_bottom'] ) . 'px';
		}
		if ( ! empty ( $arr_params['div_margin_left'] ) ) {
			$styles[] = 'margin-left:' . intval( $arr_params['div_margin_left'] ) . 'px';
		}
		if ( ! empty ( $arr_params['div_margin_right'] ) ) {
			$styles[] = 'margin-right:' . intval( $arr_params['div_margin_right'] ) . 'px';
		}
		$style = count( $styles ) ? implode( '; ', $styles ) : '';
		if ( ! empty( $style ) || ! empty( $custom_style ) ){
			$style = "style='$style $custom_style'";
		}

		$class        = "jsn-bootstrap3 wr-element-container wr-element-$shortcode_name";
		$extra_class .= ! empty ( $arr_params['css_suffix'] ) ? ' ' . esc_attr( $arr_params['css_suffix'] ) : '';
		$class       .= ! empty ( $extra_class ) ? ' ' . ltrim( $extra_class, ' ' ) : '';
		$extra_id     = ! empty ( $arr_params['id_wrapper'] ) ? ' ' . esc_attr( $arr_params['id_wrapper'] ) : '';
		$extra_id     = ! empty ( $extra_id ) ? "id='" . ltrim( $extra_id, ' ' ) . "'" : '';
		
		// Element appearing animation
		$appearring_animation = '';
		if ( ! empty ( $arr_params['appearing_animation'] ) && $arr_params['appearing_animation'] != '0' ) {
			$animation_speed = '0.6';
			if ( ! empty( $arr_params['appearing_animation_speed'] ) ) {
				switch ( $arr_params['appearing_animation_speed'] ) {
					case 'Slow': 
						$animation_speed = '0.9';
						break;
					case 'Medium':
						$animation_speed = '0.6';
						break;
					case 'Fast'	:
						$animation_speed = '0.3';
						break;
				}
			} 			
			switch ( $arr_params['appearing_animation'] ) {
				case 'slide_from_top':
					$appearring_animation   = ' data-scroll-reveal="enter top and move 150px over ' . $animation_speed . 's" ';
					break;
				case 'slide_from_right':
					$appearring_animation   = ' data-scroll-reveal="enter right and move 150px over ' . $animation_speed . 's" ';
					break;
				case 'slide_from_bottom':
					$appearring_animation   = ' data-scroll-reveal="enter bottom and move 150px over ' . $animation_speed . 's" ';
					break;
				case 'slide_from_left':
					$appearring_animation   = ' data-scroll-reveal="enter left and move 150px over ' . $animation_speed . 's" ';
					break;
				case 'fade_in':
					$appearring_animation   = ' data-scroll-reveal="ease-in 0px over ' . $animation_speed . 's" ';
					break;				
			}
		}
		$html = "<div  $extra_id class='$class' $style>" . balanceTags( $html_element ) . '</div>';
		if ($appearring_animation) {
			$html = "<div $appearring_animation>" . $html . "</div>";	
		}
		return $html	;
	}

	/**
	 * Define html structure of shortcode in "Select Elements" Modal
	 *
	 * @param string $data_sort The string relates to Provider name to sort
	 * @return string
	 */
	public function element_button( $data_sort = '' ) {
		// Prepare variables
		$type  = 'element';
		$data_value = strtolower( $this->config['name'] );

		$extra = sprintf( 'data-value="%s" data-type="%s" data-sort="%s"', esc_attr( $data_value ), esc_attr( $type ), esc_attr( $data_sort ) );

		return self::el_button( $extra, $this->config );
	}

	/**
	 * HTML output for a shortcode in Add Element popover
	 *
	 * @param string $extra
	 * @param array $config
	 * @return string
	 */
	public static function el_button( $extra, $config ) {
		// Generate icon if necessary
		$icon = isset( $config['icon'] ) ? $config['icon'] : 'wr-icon-widget';

		$icon = '<i class="wr-icon-formfields ' . $icon . '"></i> ';

		// Generate data-iframe attribute if needed
		$attr = '';

		if ( isset( $config['edit_using_ajax'] ) && $config['edit_using_ajax'] ) {
			$attr = ' data-use-ajax="1"';
		}

		return '<li class="jsn-item"' . ( empty( $extra ) ? '' : ' ' . trim( $extra ) ) . '>
					<button data-shortcode="' . $config['shortcode'] . '" class="shortcode-item btn btn-default" title="' . $config['description'] . '"' . $attr . '>
						' . $icon . $config['name'] . '
							<p class="help-block">' . $config['description'] . '</p>
					</button>
				</li>';
	}

	/**
	 * Get params & structure of shortcode
	 */
	public function shortcode_data() {
		$params = WR_Pb_Helper_Shortcode::generate_shortcode_params( $this->items, null, null, false, true );
		// add Margin parameter for Not child shortcode
		if ( strpos( $this->config['shortcode'], '_item' ) === false ) {
			$this->config['params'] = array_merge( array( 'div_margin_top' => '10', 'div_margin_bottom' => '10', 'disabled_el' => 'no', 'css_suffix' => '', 'id_wrapper' => '' ), $params );
		}
		else {
			$this->config['params'] = $params;
		}
		$this->config['shortcode_structure'] = WR_Pb_Helper_Shortcode::generate_shortcode_structure( $this->config['shortcode'], $this->config['params'] );
	}

}
