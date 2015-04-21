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

if ( ! class_exists( 'WR_Googlemap' ) ) :

/**
 * Google map element for WR PageBuilder.
 *
 * @since  1.0.0
 */
class WR_Googlemap extends WR_Pb_Shortcode_Parent {
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
		$this->config['name']             = __( 'Google Maps', WR_PBL );
		$this->config['cat']              = __( 'Extra', WR_PBL );
		$this->config['icon']             = 'wr-icon-google-map';
		$this->config['description']      = __( 'Map with a specified title and location', WR_PBL );
		$this->config['has_subshortcode'] = 'WR_Item_' . str_replace( 'WR_', '', __CLASS__ );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'default_content'  => __( 'Google Maps', WR_PBL ),
			'data-modal-title' => __( 'Google Maps', WR_PBL ),

			'admin_assets' => array(
				// Shortcode initialization
				'googlemap.js',
			),

			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',
				'googlemap_frontend.css',
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
			'content' => array(

				array(
					'id'            => 'gmap_items',
					'type'          => 'group',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => '[wr_item_googlemap gmi_title="Apple Store, Fifth Avenue" gmi_desc_content="767 5th Ave New York, NY 10153, United States  1 212-336-1440" gmi_url="https://plus.google.com/105794202623216829535/about?gl=vn" gmi_image="https://lh6.googleusercontent.com/-kRHmUypT7rk/UWuDd_MbjsI/AAAAAACMJTw/0Bk8Pszwyls/s250-c-k-no/Apple Store, Fifth Avenue" gmi_long="-73.989637" gmi_lat="40.741220" gmi_enable_direct="no" gmi_destination=""]767 5th Ave New York, NY 10153, United States  1 212-336-1440[/wr_item_googlemap]' ),
						array( 'std' => '[wr_item_googlemap gmi_title="Paley Park" gmi_desc_content="New York, NY 10022 United States" gmi_url="https://plus.google.com/101814405146294453824/about?gl=vn" gmi_image="https://lh6.googleusercontent.com/-pEEYVRCcoXg/T5UfT58tJ3I/AAAAAAAAZa8/sfiH6w8_R5g/s90/berlin-wall-manhattan-ny-nyc_thumb.jpg" gmi_long="-73.975152" gmi_lat="40.760196" gmi_enable_direct="no" gmi_destination=""]New York, NY 10022 United States[/wr_item_googlemap]' ),
					),
					'label_item'    => 'Marker ',
					'add_item_text' => 'Add Marker',
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'    => __( 'Container Style', WR_PBL ),
					'id'      => 'gmap_container_style',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_qr_container_style() ),
					'options' => WR_Pb_Helper_Type::get_qr_container_style()
				),
				array(
					'name'    => __( 'Alignment', WR_PBL ),
					'id'      => 'gmap_alignment',
					'class'   => 'input-sm',
					'std'     => 'center',
					'type'    => 'radio_button_group',
					'options' => WR_Pb_Helper_Type::get_map_align(),
				),
				array(
					'name'                      => __( 'Dimension', WR_PBL ),
					'container_class'           => 'combo-group',
					'type'                      => 'dimension',
					'id'                        => 'gmap_dimension',
					'extended_ids'              => array( 'gmap_dimension_width', 'gmap_dimension_width_unit', 'gmap_dimension_height' ),
					'gmap_dimension_width'      => array( 'std' => '500' ),
					'gmap_dimension_height'     => array( 'std' => '300' ),
					'gmap_dimension_width_unit' => array(
						'options' => array( 'px' => 'px', '%' => '%' ),
						'std'     => 'px',
					),
				),
				array(
					'name'               => __( 'Margin', WR_PBL ),
					'container_class'    => 'combo-group',
					'id'                 => 'gmap_margin',
					'type'               => 'margin',
					'extended_ids'       => array( 'gmap_margin_top', 'gmap_margin_right', 'gmap_margin_bottom', 'gmap_margin_left' ),
					'gmap_margin_top'	 => array( 'std' => '10' ),
					'gmap_margin_bottom' => array( 'std' => '10' ),
					'tooltip'            => __( 'Margin', WR_PBL )
				),
				array(
					'type' => 'hr',
				),
				array(
					'name'    => __( 'Zoom Level', WR_PBL ),
					'id'      => 'gmap_zoom',
					'class'   => 'wr-slider',
					'type'    => 'slider',
					'std_max' => '14',
					'std'     => '12'
				),
				array(
					'name'    => __( 'Map Type', WR_PBL ),
					'id'      => 'gmap_type',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => 'ROADMAP',
					'options' => WR_Pb_Helper_Type::get_gmap_type(),
				),
				array(
					'name'            => __( 'Elements', WR_PBL ),
					'id'              => 'gmap_elements',
					'type'            => 'checkbox',
					'class'           => 'jsn-column-item checkbox',
					'container_class' => 'jsn-columns-container jsn-columns-count-two',
					'std'             => 'streetViewControl__#__zoomControl__#__panControl__#__mapTypeControl__#__scaleControl__#__overviewMapControl__#__scrollwheel',
					'options'         => array(
						'streetViewControl'  => __( 'Street View', WR_PBL ),
						'zoomControl'        => __( 'Zoom Controls', WR_PBL ),
						'panControl'         => __( 'Pan Controls', WR_PBL ),
						'mapTypeControl'     => __( 'Map Type Controls', WR_PBL ),
						'scaleControl'       => __( 'Scale Controls', WR_PBL ),
						'overviewMapControl' => __( 'Overview Map Controls', WR_PBL ),
						'scrollwheel'        => __( 'Scrollwheel Zooming', WR_PBL ),
					),
				),
				WR_Pb_Helper_Type::get_apprearing_animations(),
				WR_Pb_Helper_Type::get_animation_speeds(),
			)
		);
	}

	/**
	 * Enqueue custom assets for front-end.
	 *
	 * @return  void
	 */
	public function custom_assets_frontend() {
		parent::custom_assets_frontend();

		wp_enqueue_script( 'wr-pb-googlemap-js', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', null, null, true );
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

		$html_element = $container_class = $str_scripts = '';
		$arr_params   = ( shortcode_atts( $this->config['params'], $atts ) );
		extract( $arr_params );
		$arrDefaultOptions = array('streetViewControl', 'zoomControl', 'panControl', 'mapTypeControl', 'scaleControl', 'overviewMapControl', 'scrollwheel');
		if ( $gmap_elements ) {
			$arr_elements = array_filter( explode( '__#__', $gmap_elements ) );
			foreach ( $arrDefaultOptions as $i => $item ) {
				if ( in_array( $item, $arr_elements ) ) {
					$str_scripts .= $item . ': true,';
				} else {
					$str_scripts .= $item . ': false,';
				}
			}
		}
		$random_id       = WR_Pb_Utils_Common::random_string();
		$player_elements = '';
		if ( $gmap_alignment === 'right' ) {
			$player_elements .= '$( "#wr-gmap-' . $random_id . '" ).css( "float", "right" );';
			$container_class .= 'clearafter ';
		} else if ( $gmap_alignment === 'center' ) {
			$player_elements .= '$( "#wr-gmap-' . $random_id . '" ).css( {"margin" : "0 auto", "display" : "block" } );';
		} else if ( $gmap_alignment === 'left' ) {
			$player_elements .= '$( "#wr-gmap-' . $random_id . '" ).css( "float", "left" );';
			$container_class .= 'clearafter ';
		}

		$html_element .= '<script type="text/javascript">(function ($) {
			$( document ).ready( function () {
				' . $player_elements . '
				var map_' . $random_id . '				= "";
				var marker_locations_' . $random_id . ' = [];
				function get_latlong(obj) {
					var myLatlng		 = new google.maps.LatLng( obj.gmi_lat, obj.gmi_long);
					return myLatlng;
				}

				function get_infobox(obj) {
					var infowindow		= "";
					var contentString	 = "<div class=\"wr-gmi-info\" style=\"width:250px\" >";
					contentString		 += "<div class=\"wr-gmi-thumb\">";
					if ( obj.gmi_image != "" && obj.gmi_image != "http://" )
						contentString		 += "<img src=\"" + obj.gmi_image + "\" />";
					contentString		 += "</div>";
					contentString		 += "<span class=\"wr-gmi-title\"><b>" + obj.gmi_title + "</b></span>";
					if ( obj.gmi_desc_content )
						contentString		 += "<p>" + obj.gmi_desc_content + "</p>";
					if ( obj.gmi_url != "" && obj.gmi_url != "http://" )
						contentString		 += "<a href=\"" + obj.gmi_url + "\" target=\"_blank\">more...></a>";
					contentString		 += "</div>";
					infowindow		 = new google.maps.InfoWindow({
						content: contentString
					});
					infowindow.setOptions({maxWidth:300});
					return infowindow;
				}

				function markerAtPoint(latlng) {
					for (var i = 0; i < marker_locations_' . $random_id . '.length; ++i) {
						if (marker_locations_' . $random_id . '[i].equals(latlng)) return true;
					}
					return false;
				}

				function initialize_' . $random_id . '() {
					var gmap_zoom	= parseInt("' . $gmap_zoom . '");
					var gmap_lat	= "";
					var gmap_long	= "";
					var gmap_type	= google.maps.MapTypeId.' . $gmap_type . ';
					var directionsService_' . $random_id . '	 = new google.maps.DirectionsService();
					var has_direction		= false;

					var mapOptions_' . $random_id . ' = {
						zoom: gmap_zoom,
						center: new google.maps.LatLng(0,0),
						mapTypeId: gmap_type,
						' . $str_scripts . '
					};
					map_' . $random_id . ' = new google.maps.Map(document.getElementById(\'wr-gmap-' . $random_id . '\'), mapOptions_' . $random_id . ');
					var rendererOptions_' . $random_id . ' = {
						map: map_' . $random_id . ',
						suppressMarkers: true
					}
					var directionsDisplay_' . $random_id . ' = new google.maps.DirectionsRenderer(rendererOptions_' . $random_id . ');
					directionsDisplay_' . $random_id . '.setMap(map_' . $random_id . ');

					// Check has directions
					$( "#wr-gmap-wrapper-' . $random_id . ' .wr-gmi-lat-long" ).each(function (index) {
						var obj = JSON.parse($(this).val());
						if ( obj.gmi_lat != "" && obj.gmi_long != "" && obj.gmi_destination != "" ) {
							has_direction = true;
						}
					});

					// Add markers
					if ( has_direction == false ) {
						$( "#wr-gmap-wrapper-' . $random_id . ' .wr-gmi-lat-long" ).each(function (index) {
							var obj = JSON.parse($(this).val());
							if ( obj.gmi_lat != "" && obj.gmi_long != "" ) {
								var myLatlng	 = get_latlong(obj);
								var infowindow	 = get_infobox(obj);
								if ( map_' . $random_id . ' ) {
									var marker		= new google.maps.Marker({
										position: myLatlng,
										map: map_' . $random_id . ',
										title: obj.gmi_title
									});
									marker_locations_' . $random_id . '.push(myLatlng);
									map_' . $random_id . '.setCenter(marker.getPosition());
									google.maps.event.addListener(marker, \'click\', function() {
										infowindow.open(map_' . $random_id . ',marker);
									});
								}
							}
						});
					} else {

						$( "#wr-gmap-wrapper-' . $random_id . ' .wr-gmi-lat-long" ).each(function (i) {
							var obj = JSON.parse($(this).val());
							$( "#wr-gmap-wrapper-' . $random_id . ' .wr-gmi-lat-long" ).each(function (j) {
								var sub_obj	 = JSON.parse($(this).val());

								if ( sub_obj.gmi_title == obj.gmi_destination && sub_obj.gmi_lat != "" && sub_obj.gmi_long != "" ) {
									var start        = get_latlong(obj);
									var end			 = get_latlong(sub_obj);

									var infowindow	 = get_infobox(obj);
									if ( map_' . $random_id . ' ) {
										var marker			 = new google.maps.Marker({
											position: start,
											map: map_' . $random_id . ',
											title: obj.gmi_title
										});
										marker_locations_' . $random_id . '.push(start);
										google.maps.event.addListener(marker, \'click\', function() {
											infowindow.open(map_' . $random_id . ',marker);
										});
									}

									var sub_infowindow	 = get_infobox(sub_obj);
									if ( map_' . $random_id . ' ) {
										var sub_marker		 = new google.maps.Marker({
											position: end,
											map: map_' . $random_id . ',
											title: sub_obj.gmi_title
										});
										marker_locations_' . $random_id . '.push(end);
										google.maps.event.addListener(sub_marker, \'click\', function() {
											sub_infowindow.open(map_' . $random_id . ',sub_marker);
										});
									}

									var request = {
										origin:start,
										destination:end,
										travelMode: google.maps.DirectionsTravelMode.DRIVING
									};

									directionsService_' . $random_id . '.route(request, function(response, status) {
										if (status == google.maps.DirectionsStatus.OK) {
											directionsDisplay_' . $random_id . '.setDirections(response);
										}
									});
								}

								if ( markerAtPoint(new google.maps.LatLng( obj.gmi_lat, obj.gmi_long) ) == false ) {
									var myLatlng	 = get_latlong(obj);
									var infowindow	 = get_infobox(obj);
									if ( map_' . $random_id . ' ) {
										var marker		= new google.maps.Marker({
											position: myLatlng,
											map: map_' . $random_id . ',
											title: obj.gmi_title
										});
										marker_locations_' . $random_id . '.push(myLatlng);
										map_' . $random_id . '.setCenter(marker.getPosition());
										google.maps.event.addListener(marker, \'click\', function() {
											infowindow.open(map_' . $random_id . ',marker);
										});
									}
								}
							});
						});
					}
				}
				google.maps.event.addDomListener(window, \'load\', initialize_' . $random_id . ');

				// Center map on resize
				google.maps.event.addDomListener(window, \'resize\', function() {
					var center = map_' . $random_id . '.getCenter();
					google.maps.event.trigger(map_' . $random_id . ', \'resize\');
					map_' . $random_id . '.setCenter(center);
				});
			});
		})(jQuery)</script>';
		$class = 'wr-googlemap';
		if ( $gmap_container_style == 'img-thumbnail' ) {
			$class .= ' img-thumbnail';
		}
		if ( $gmap_margin_top )
			$gmap_styles[] = "margin-top:{$gmap_margin_top}px !important";
		if ( $gmap_margin_bottom )
			$gmap_styles[] = "margin-bottom:{$gmap_margin_bottom}px !important";
		if ( $gmap_margin_right )
			$gmap_styles[] = "margin-right:{$gmap_margin_right}px !important";
		if ( $gmap_margin_left )
			$gmap_styles[] = "margin-left:{$gmap_margin_left}px !important";
		if ( $gmap_dimension_height )
			$gmap_styles[] = "height:{$gmap_dimension_height}px !important";
		if ( $gmap_dimension_width )
			$gmap_styles[] = "width:{$gmap_dimension_width}{$gmap_dimension_width_unit} !important";
		$styles = ( count( $gmap_styles ) ) ? ' style="' . implode( ';', $gmap_styles ) . '"' : '';

		$html_element .= '<div id="wr-gmap-wrapper-' . $random_id . '">';
		$html_element .= '<div id="wr-gmap-' . $random_id . '" ' . $styles . ' class="' . $class . '"></div>';
		$sub_shortcode = WR_Pb_Helper_Shortcode::remove_autop( $content );
		$items         = explode( '<!--seperate-->', $sub_shortcode );
		// remove empty element
		$items         = array_filter( $items );
		$initial_open  = ( ! isset( $initial_open ) || $initial_open > count( $items ) ) ? 1 : $initial_open;
		foreach ( $items as $idx => $item ) {
			$open        = ( $idx + 1 == $initial_open ) ? 'in' : '';
			$items[$idx] = $item;
		}
		$sub_htmls    = do_shortcode( $sub_shortcode );
		$html_element .= $sub_htmls;
		$html_element .= '</div>';

		return $this->element_wrapper( $html_element, $arr_params );
	}

}

endif;
