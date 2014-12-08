<?php
/**
 * @version	$Id$
 * @package	WR PageBuilder Shortcodes
 * @author	 WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 woorockets.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support:  Feedback - http://www.woorockets.com
 */

if ( ! class_exists( 'WR_Accordion' ) ) :

/**
 * Create accordion element.
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_Accordion extends WR_Pb_Shortcode_Parent {
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
		$this->config['name']             = __( 'Accordion', WR_PBL );
		$this->config['cat']              = __( 'Typography', WR_PBL );
		$this->config['icon']             = 'wr-icon-accordion';
		$this->config['has_subshortcode'] = 'WR_Item_' . str_replace( 'WR_', '', __CLASS__ );
		$this->config['description']      = __( 'Vertically stacked and tabbed content', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'admin_assets' => array(
				'wr-pb-joomlashine-iconselector-js',
			),
			'frontend_assets' => array(
					// Bootstrap 3
					'wr-pb-bootstrap-css',
					'wr-pb-bootstrap-js',
					// Font IcoMoon
					'wr-pb-font-icomoon-css',
					// Load css front_end for this element.
					'accordion_frontend.css'
				),
			);

		// Use Ajax to speed up element settings modal loading speed
		$this->config['edit_using_ajax'] = true;

		// Edit inline supplement
		add_action( 'wr_pb_modal_footer', array( 'WR_Pb_Objects_Modal', '_modal_footer' ) );
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
							'std'         => __( 'Tab', WR_PBL ),
							'action_type' => 'convert',
							'action'      => 'accordion_to_tab',
						),
						array(
							'std'         => __( 'Carousel', WR_PBL ),
							'action_type' => 'convert',
							'action'      => 'accordion_to_carousel',
						),
						array(
							'std'         => __( 'List', WR_PBL ),
							'action_type' => 'convert',
							'action'      => 'accordion_to_list',
						),
					)
				),
			),
			'content' => array(
				array(
					'id'            => 'accordion_items',
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
					'name'     => __( 'Initial Open', WR_PBL ),
					'id'       => 'initial_open',
					'type'     => 'text_number',
					'std'      => '1',
					'class'    => 'input-mini',
					'validate' => 'number',
					'tooltip'  => __( 'Set which item will be opened first', WR_PBL ),
				),
				array(
					'name'    => __( 'Allow Multiple Opening', WR_PBL ),
					'id'      => 'multi_open',
					'type'    => 'radio',
					'std'     => 'no',
					'options' => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
					'tooltip' => __( 'Keep previous item opened when clicking on another one', WR_PBL ),
				),
				array(
					'name'    => __( 'Enable Filter', WR_PBL ),
					'id'      => 'filter',
					'type'    => 'radio',
					'std'     => 'no',
					'options' => array( 'yes' => __( 'Yes', WR_PBL ), 'no' => __( 'No', WR_PBL ) ),
					'tooltip' => __( 'Filter based on items\' tags name. Please set tag for item first.', WR_PBL ),
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
		$initial_open = intval( $arr_params['initial_open'] );
		$multi_open   = $arr_params['multi_open'];
		$filter       = $arr_params['filter'];
		$random_id    = WR_Pb_Utils_Common::random_string();
		$script       = '';

		extract( $arr_params );

		if ( $multi_open == 'no' ) {
			$script .= "<script type='text/javascript'>( function ($) {
				$( document ).ready( function ()
				{
					// Bind event click accordion
					$( '#accordion_$random_id .panel-title a' ).on('click', function (e) {
						e.preventDefault();
						$('#accordion_$random_id .in').collapse('hide');
					});
				});
			} )( jQuery )</script>";
		}

		$sub_shortcode = do_shortcode( $content );
		$items = explode( '<!--seperate-->', $sub_shortcode );

		// remove empty element
		$items = array_filter( $items );

		// update id, class for each item
		$initial_open = ( $initial_open > count( $items ) ) ? 1 : $initial_open;
		foreach ( $items as $idx => $item ) {
			$open         = ( $idx + 1 == $initial_open ) ? 'in' : '';
			$item = str_replace( '{index}', $random_id . $idx, $item );
			$item = str_replace( '{show_hide}', $open, $item );
			$items[$idx] = $item;
		}
		$sub_shortcode = implode( '', $items );

		$filter_html = '';
		if ( $filter == 'yes' ) {
			$sub_sc_data = WR_Pb_Helper_Shortcode::extract_sub_shortcode( $content );
			$sub_sc_data = reset( $sub_sc_data );

			// tags to filter item
			$tags = array( 'all' );

			foreach ( $sub_sc_data as $shortcode ) {
				$extract_params = shortcode_parse_atts( $shortcode );
				$tags[] = $extract_params['tag'];
			}
			$tags = array_filter( $tags );

			if ( count( $tags ) > 1 ) {

				$tags = implode( ',', $tags );
				$tags = explode( ',', $tags );
				$tags = array_unique( $tags );
				$filter_html = WR_Pb_Helper_Shortcode::render_parameter( 'filter_list', $tags, $random_id );

				// remove "All" tag
				array_shift( $tags );
				$inner_tags = implode( ',', $tags );
				$script .= "<script type='text/javascript'>( function ($) {
				$( document ).ready( function ()
				{
					window.parent.jQuery.noConflict()( '#jsn_view_modal').contents().find( '#wr_share_data' ).text( '{$inner_tags}')
					var parent_criteria = '#filter_$random_id'
					var clientsClone = $( '#accordion_$random_id' );
					var tag_to_filter = 'div';
					var class_to_filter = '.panel-default';

					$( parent_criteria + ' a' ).click( function(e ) {
						// stop running filter
						$( class_to_filter ).each(function(){
							$( this ).stop( true )
						})
						e.preventDefault();

						//active clicked criteria
						$( parent_criteria + ' li' ).removeClass( 'active' );
						$( this ).parent().addClass( 'active' );

						var filterData = $( this ).attr( 'class' );
						var filters;
						if( filterData == 'all' ){
							filters = clientsClone.find( tag_to_filter );
						} else {
							filters = clientsClone.find( tag_to_filter + '[data-tag~='+ filterData +']' );
						}
						clientsClone.find( class_to_filter ).each(function(){
							$( this ).fadeOut()
						});
						filters.each(function(){
							$( this ).fadeIn()
						});
					});
				});
			} )( jQuery )</script>";
			}
		}

		$html = '<div class="panel-group" id="accordion_{ID}">' . $sub_shortcode . '</div>';
		$html = str_replace( '{ID}', $random_id, $html );

		return $this->element_wrapper( $filter_html . $html . $script, $arr_params );
	}
}

endif;
