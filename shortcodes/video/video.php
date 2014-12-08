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

if ( ! class_exists( 'WR_Video' ) ) :

/**
 * Create Video element
 * User can input file from local server or
 * from other sources like Youtube, Vimeo...
 *
 * @package  WR PageBuilder Shortcodes
 * @since    1.0.0
 */
class WR_Video extends WR_Pb_Shortcode_Element {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'wp_ajax_video_validate_file', array( &$this, 'validate_file' ) );
	}

	/**
	 * Configure shortcode.
	 *
	 * @return  void
	 */
	public function element_config() {
		$this->config['shortcode']   = strtolower( __CLASS__ );
		$this->config['name']        = __( 'Video', WR_PBL );
		$this->config['cat']         = __( 'Media', WR_PBL );
		$this->config['icon']        = 'wr-icon-video';
		$this->config['description'] = __( 'Embed Youtube/Vimeo or local file player', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'admin_assets' => array(
		// Shortcode initialization
				'video.js',
		),

			'frontend_assets' => array(
		// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',

		// Media Element
				'mediaelement-css',
				'mediaelement-js',
				'wr-pb-mediaelement-js',

		// Shortcode style and script initialization
				'video_frontend.css',
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
			// video source dropdown list on top.
			'generalaction' => array(
				'settings' => array(
					'id'    => 'general_action',
					'class' => 'general-action no-label pull-left',
				),
				array(
					'id'         => 'video_sources',
					'type'       => 'select',
					'has_depend' => '1',
					'std'        => 'local',
					'options'    => array(
						'local'   => __( 'Local file', WR_PBL ),
						'youtube' => __( 'Youtube', WR_PBL ),
						'vimeo'   => __( 'Vimeo', WR_PBL )
					),
					'exclude_class' => array( 'form-control' )
				)
			),
			// Content Tab
			'content' => array(

			array(
					'id'          => 'video_source_local',
					'name'        => __( 'Video File', WR_PBL ),
					'type'        => 'select_media',
					'filter_type' => 'video',
					'media_type'  => 'video',
					'class'       => 'jsn-input-large-fluid',
					'dependency'  => array( 'video_sources', '=', 'local' ),
			),
			// Youtube.
			array(
					'id'           => 'video_source_link_youtube',
					'name'         => __( 'Video Link', WR_PBL ),
					'type'         => 'text_append',
					'type_input'   => 'text',
					'dependency'   => array( 'video_sources', '=', 'youtube' ),
					'class'        => 'span6 input-sm',
					'specific_class' => 'input-group col-xs-12',
			),
			// Vimeo.
			array(
					'id'           => 'video_source_link_vimeo',
					'name'         => __( 'Video Link', WR_PBL ),
					'type'         => 'text_append',
					'type_input'   => 'text',
					'dependency'   => array( 'video_sources', '=', 'vimeo' ),
					'class'        => 'span6 input-sm',
					'specific_class' => 'input-group col-xs-12',
				),
			),
			// Styling tab .
			'styling' => array(
				array(
					'type' => 'preview',
				),
				/**
				 * Parameters for local video
				 */
				array(
					'name'                         => __( 'Dimension', WR_PBL ),
					'container_class'              => 'combo-group',
					'dependency'                   => array( 'video_sources', '=', 'local' ),
					'id'                           => 'video_local_dimension',
					'type'                         => 'dimension',
					'extended_ids'                 => array( 'video_local_dimension_width', 'video_local_dimension_height' ),
					'video_local_dimension_width'  => array( 'std' => '500' ),
					'video_local_dimension_height' => array( 'std' => '270' ),
					'tooltip'                      => __( 'Set width and height of element', WR_PBL ),
				),
				array(
					'name'            => __( 'Elements', WR_PBL ),
					'id'              => 'video_local_elements',
					'type'            => 'checkbox',
					'class'           => 'jsn-column-item checkbox',
					'container_class' => 'jsn-columns-container jsn-columns-count-two',
					'dependency'      => array( 'video_sources', '=', 'local' ),
					'std'             => 'play_button__#__overlay_play_button__#__current_time__#__time_rail__#__track_duration__#__volume_button__#__volume_slider__#__fullscreen_button',
					'options'         => array(
						'play_button' => __( 'Play/Pause Button', WR_PBL ),
						'overlay_play_button' => __( 'Overlay Play Button', WR_PBL ),
						'current_time'        => __( 'Current Time', WR_PBL ),
						'time_rail'           => __( 'Time Rail', WR_PBL ),
						'track_duration'      => __( 'Track Duration', WR_PBL ),
						'volume_button'       => __( 'Volume Button', WR_PBL ),
						'volume_slider'       => __( 'Volume Slider', WR_PBL ),
						'fullscreen_button'   => __( 'Fullscreen Button', WR_PBL )
					),
					'tooltip' => __( 'Elements to display on video player', WR_PBL ),
				),
				array(
					'name'         => __( 'Start volume', WR_PBL ),
					'id'           => 'video_local_start_volume',
					'type'         => 'text_append',
					'type_input'   => 'number',
					'class'        => 'jsn-input-number input-mini',
					'parent_class' => 'combo-item',
					'std'          => '80',
					'append'       => '%',
					'dependency'   => array( 'video_sources', '=', 'local' ),
					'validate'     => 'number',
				),
				array(
					'name'       => __( 'Loop', WR_PBL ),
					'id'         => 'video_local_loop',
					'type'       => 'radio',
					'std'        => 'false',
					'dependency' => array( 'video_sources', '=', 'local' ),
					'options'    => array(
						'true'  => __( 'Yes', WR_PBL ),
						'false' => __( 'No', WR_PBL )
					),
					'tooltip' => __( 'Whether to repeat playing or not', WR_PBL ),
				),
				// Youtube video parameters
				array(
					'name'                           => __( 'Dimension', WR_PBL ),
					'container_class'                => 'combo-group',
					'dependency'                     => array( 'video_sources', '=', 'youtube' ),
					'id'                             => 'video_youtube_dimension',
					'type'                           => 'dimension',
					'extended_ids'                   => array( 'video_youtube_dimension_width', 'video_youtube_dimension_height' ),
					'video_youtube_dimension_width'  => array( 'std' => '500' ),
					'video_youtube_dimension_height' => array( 'std' => '270' ),
					'tooltip' => __( 'Set width and height of element', WR_PBL ),
				),
				array(
					'name'       => __( 'Show List', WR_PBL ),
					'id'         => 'video_youtube_show_list',
					'type'       => 'radio',
					'std'        => '0',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'1' => __( 'Yes', WR_PBL ),
						'0' => __( 'No', WR_PBL )
					)
				),
				array(
					'name'       => __( 'Auto Play', WR_PBL ),
					'id'         => 'video_youtube_autoplay',
					'type'       => 'radio',
					'std'        => '0',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'1' => __( 'Yes', WR_PBL ),
						'0' => __( 'No', WR_PBL )
					),
				),
				array(
					'name'       => __( 'Loop', WR_PBL ),
					'id'         => 'video_youtube_loop',
					'type'       => 'radio',
					'std'        => '0',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'1' => __( 'Yes', WR_PBL ),
						'0' => __( 'No', WR_PBL )
					),
					'tooltip' => __( 'Whether to repeat playing or not', WR_PBL ),
				),
				array(
					'name'       => __( 'Show YouTube Logo', WR_PBL ),
					'id'         => 'video_youtube_modestbranding',
					'type'       => 'radio',
					'std'        => '1',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'0' => __( 'Yes', WR_PBL ),
						'1' => __( 'No', WR_PBL )
					),
					'tooltip'    => __( 'Display YouTube logo on action bar that redirects users to original YouTube video', WR_PBL ),
				),
				array(
					'name'       => __( 'Show Related Video', WR_PBL ),
					'id'         => 'video_youtube_rel',
					'type'       => 'radio',
					'std'        => '1',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'1' => __( 'Yes', WR_PBL ),
						'0' => __( 'No', WR_PBL )
					),
					'tooltip'    => __( 'Display related video  at the end of YouTube video', WR_PBL ),
				),
				array(
					'name'       => __( 'Show Information', WR_PBL ),
					'id'         => 'video_youtube_showinfo',
					'type'       => 'radio',
					'std'        => '1',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'1' => __( 'Yes', WR_PBL ),
						'0' => __( 'No', WR_PBL )
					),
					'tooltip'    => __( 'Display video title & brief info on top bar of video player', WR_PBL ),
				),
				array(
					'name'       => __( 'Auto Hide', WR_PBL ),
					'id'         => 'video_youtube_autohide',
					'type'       => 'select',
					'std'        => '2',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'2' => __( 'Auto minimize Progress Bar', WR_PBL ),
						'1' => __( 'Both after playing a couple seconds', WR_PBL ),
						'0' => __( 'Never Hide', WR_PBL )
					),
				),
				array(
					'name'       => __( 'Show Caption (CC)', WR_PBL ),
					'id'         => 'video_youtube_cc',
					'type'       => 'radio',
					'std'        => '0',
					'dependency' => array( 'video_sources', '=', 'youtube' ),
					'options'    => array(
						'1' => __( 'Never', WR_PBL ),
						'0' => __( 'Yes', WR_PBL )
					),
				),
				// Vimeo video parameters
				array(
					'name'                         => __( 'Dimension', WR_PBL ),
					'container_class'              => 'combo-group',
					'dependency'                   => array( 'video_sources', '=', 'vimeo' ),
					'id'                           => 'video_vimeo_dimension',
					'type'                         => 'dimension',
					'extended_ids'                 => array( 'video_vimeo_dimension_width', 'video_vimeo_dimension_height' ),
					'video_vimeo_dimension_width'  => array( 'std' => '500' ),
					'video_vimeo_dimension_height' => array( 'std' => '270' ),
					'tooltip' => __( 'Set width and height of element', WR_PBL ),
				),
				array(
					'name'       => __( 'Auto Play', WR_PBL ),
					'id'         => 'video_vimeo_autoplay',
					'type'       => 'radio',
					'std'        => 'false',
					'dependency' => array( 'video_sources', '=', 'vimeo' ),
					'options'    => array(
						'true'  => __( 'Yes', WR_PBL ),
						'false' => __( 'No', WR_PBL )
					),
					'tooltip' => __( 'Auto play the video', WR_PBL ),
				),
				array(
					'name'       => __( 'Loop', WR_PBL ),
					'id'         => 'video_vimeo_loop',
					'type'       => 'radio',
					'std'        => 'false',
					'dependency' => array( 'video_sources', '=', 'vimeo' ),
					'options'    => array(
						'true'  => __( 'Yes', WR_PBL ),
						'false' => __( 'No', WR_PBL )
					),
					'tooltip' => __( 'Whether to repeat playing or not', WR_PBL ),
				),
				array(
					'name'       => __( 'Controls Color', WR_PBL ),
					'id'         => 'video_vimeo_color',
					'type'       => 'color_picker',
					'std'        => '#54BBFC',
					'dependency' => array( 'video_sources', '=', 'vimeo' ),
					'hide_value' => true,
					'tooltip' => __( 'Set color of controls', WR_PBL ),
				),
				array(
					'type'  => 'hr',
				),
				// Basic styling parameters
				array(
					'name'    => __( 'Alignment', WR_PBL ),
					'id'      => 'video_alignment',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => 'center',
					'options' => array(
						'0'      => __( 'No Alignment', WR_PBL ),
						'left'   => __( 'Left', WR_PBL ),
						'right'  => __( 'Right', WR_PBL ),
						'center' => __( 'Center', WR_PBL ),
					),
				),
				array(
					'name'            => __( 'Margin', WR_PBL ),
					'container_class' => 'combo-group',
					'id'              => 'video_margin',
					'type'            => 'margin',
					'extended_ids'    => array( 'video_margin_top', 'video_margin_right', 'video_margin_bottom', 'video_margin_left' ),
					'video_margin_top'    => array( 'std' => '10' ),
					'video_margin_bottom' => array( 'std' => '10' ),
					'tooltip'             => __( 'External spacing with other elements', WR_PBL ),
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
		if ( $atts['video_sources'] == 'local' ) {
			$atts['video_local_dimension_width'] = $atts['video_local_dimension_width'] ? $atts['video_local_dimension_width'] : '100%';
			$arr_params                          = ( shortcode_atts( $this->config['params'], $atts ) );
			$arr_params                          = $this->merge_margin( $arr_params );
			if ( empty( $arr_params['video_source_local'] ) ){
				$html_element = "<p class='jsn-bglabel'>" . __( 'No video file selected', WR_PBL ) . '</p>';
			} else {
				$html_element = $this->generate_local_file( $arr_params );
			}
		} else if ( $atts['video_sources'] == 'youtube' ) {
			$atts['video_youtube_dimension_width'] = $atts['video_youtube_dimension_width'] ? $atts['video_youtube_dimension_width'] : '100%';
			$arr_params                            = ( shortcode_atts( $this->config['params'], $atts ) );
			$arr_params                            = $this->merge_margin( $arr_params );
			if ( empty( $arr_params['video_source_link_youtube'] ) ){
				$html_element = "<p class='jsn-bglabel'>" . __( 'No video file selected', WR_PBL ) . '</p>';
			} else {
				$html_element = $this->generate_youtube( $arr_params );
			}
		} else if ( $atts['video_sources'] == 'vimeo' ) {
			$atts['video_vimeo_dimension_width'] = $atts['video_vimeo_dimension_width'] ? $atts['video_vimeo_dimension_width'] : '100%';
			$arr_params                          = ( shortcode_atts( $this->config['params'], $atts ) );
			$arr_params                          = $this->merge_margin( $arr_params );
			if ( empty( $arr_params['video_source_link_vimeo'] ) ){
				$html_element = "<p class='jsn-bglabel'>" . __( 'No video file selected', WR_PBL ) . '</p>';
			} else {
				$html_element = $this->generate_vimeo( $arr_params );
			}
		}

		return $this->element_wrapper( $html_element, $arr_params );
	}

	/*
	 * Method to merge audio margin with div margin
	*
	* @param   array  $params Shortcode parameters.
	*
	* @return  array  $params
	*/
	private function merge_margin( $params ) {
		if ( isset( $params['video_margin_left'] ) && $params['video_margin_left'] != '' )
			$params['div_margin_left'] = $params['video_margin_left'];
		if ( isset( $params['video_margin_right'] ) && $params['video_margin_right'] != '' )
			$params['div_margin_right'] = $params['video_margin_right'];
		if ( isset( $params['video_margin_top'] ) && $params['video_margin_top'] != '' )
			$params['div_margin_top'] = $params['video_margin_top'];
		if ( isset( $params['video_margin_bottom'] ) && $params['video_margin_bottom'] != '' )
			$params['div_margin_bottom'] = $params['video_margin_bottom'];
		return $params;
	}

	/**
	 * Generate HTML for local video player.
	 *
	 * @param   array  $params  Shortcode parameters.
	 *
	 * @return  string  HTML code.
	 */
	function generate_local_file( $params ) {
		$random_id = WR_Pb_Utils_Common::random_string();
		$video_size = array();
		$video_size['width']  = ' width="' . $params['video_local_dimension_width'] . '" ';
		$video_size['height'] = ( $params['video_local_dimension_height'] != '' ) ? ' height="' . $params['video_local_dimension_height'] . '" ' : '';

		$player_options = '{';
		$player_options .= $params['video_local_start_volume'] ? 'startVolume: ' . ( int ) $params['video_local_start_volume'] / 100 . ',' : '';
		$player_options .= $params['video_local_loop'] ? 'loop: ' . $params['video_local_loop'] . ',' : '';

		$_progress_bar_color = isset($params['video_local_progress_color']) ? '$(".mejs-time-loaded, .mejs-horizontal-volume-current", video_container).css("background", "none repeat scroll 0 0 ' . $params['video_local_progress_color'] . '");' : '';

		$params['video_local_elements'] = explode( '__#__', $params['video_local_elements'] );

		$player_elements = '';
		$player_elements .= in_array( 'play_button', $params['video_local_elements'] ) ? '' : '$(".mejs-playpause-button", video_container).hide();';
		$player_elements .= in_array( 'overlay_play_button', $params['video_local_elements'] ) ? '' : '$(".mejs-overlay-button", video_container).hide();';
		$player_elements .= in_array( 'current_time', $params['video_local_elements'] ) ? '' : '$(".mejs-currenttime-container", video_container).hide();';
		$player_elements .= in_array( 'time_rail', $params['video_local_elements'] ) ? '' : '$(".mejs-time-rail", video_container).hide();';
		$player_elements .= in_array( 'track_duration', $params['video_local_elements'] ) ? '' : '$(".mejs-duration-container", video_container).hide();';
		$player_elements .= in_array( 'volume_button', $params['video_local_elements'] ) ? '' : '$(".mejs-volume-button", video_container).hide();';
		$player_elements .= in_array( 'volume_slider', $params['video_local_elements'] ) ? '' : '$(".mejs-horizontal-volume-slider", video_container).hide();';
		$player_elements .= in_array( 'fullscreen_button', $params['video_local_elements'] ) ? '' : '$(".mejs-fullscreen-button", video_container).hide();';

		// Alignment
		$container_class = 'local_file ';
		$container_style = '';
		if ( $params['video_alignment'] === 'right' ) {
			$container_style .= 'float: right;';
			$container_class .= 'clearafter pull-right';
		} else if ( $params['video_alignment'] === 'center' ) {
			$container_style .= 'margin: 0 auto;';
		} else if ( $params['video_alignment'] === 'left' ) {
			$container_style .= 'float: right;';
			$container_class .= 'clearafter pull-left';
		}
		// Genarate Container class
		$container_class = $container_class ? ' class="' . $container_class . '" ' : '';

		$player_options .= 'defaultVideoHeight:' . ( intval( $params['video_local_dimension_height'] ) - 10 ) . ',';
		$player_options .= 'success: function(mediaElement, domObject){

var video_container= $(domObject).parents(".mejs-container");
' . $player_elements . '
},';
		$player_options .= 'keyActions:[], pluginPath:"' . get_site_url() . '/wp-includes/js/mediaelement/' . '"}';

		$script = '
<script type="text/javascript">
jQuery(document).ready(function ($){
new MediaElementPlayer("#' . $random_id . '",
' . $player_options . '
);

});
</script>';

		// This under is the fix for Chrome video dimension issue
		$container_style .= 'width: ' . $params['video_local_dimension_width'] . 'px;';
		$container_style .= 'height: ' . $params['video_local_dimension_height'] . 'px;';

		$container_style = $container_style ? ' style=" ' . $container_style . ' " ' : '';

		// Define the media type
		$src    = str_replace( ' ', '+', urldecode( $params['video_source_local'] ) );
		$source = '<source type="%s" src="%s" />';
		$type   = wp_check_filetype( $src );
		$source = sprintf( $source, $type['type'], esc_url( $src ) );

		$video  = '<video id="' . $random_id . '" ' . $video_size['width'] . $video_size['height'] . ' controls="controls" preload="none" src="' . $src . '">
' . $source . '
</video>';

		return $script . '<div ' . $container_class . $container_style . '>'
		. $video . '
</div>';
	}

	/**
	 * Generate HTML for Youtube.
	 *
	 * @param   array  $params  Shortcode parameters.
	 *
	 * @return  string  HTML code.
	 */
	function generate_youtube( $params ) {
		$random_id = WR_Pb_Utils_Common::random_string();

		$_w = ' width="' . $params['video_youtube_dimension_width'] . '" ';
		$_h = $params['video_youtube_dimension_height'] ? ' height="' . $params['video_youtube_dimension_height'] . '" ' : '';

		// Alignment
		$container_class = '';
		$object_style = '';
		if ( $params['video_alignment'] === 'right' ) {
			$object_style    .= 'float:right;';
			$container_class .= 'clearafter pull-right';
		} else if ( $params['video_alignment'] === 'center' ) {
			$object_style .= 'margin: 0 auto;';
		} else if ( $params['video_alignment'] === 'left' ) {
			$object_style    .= 'float:left;';
			$container_class .= 'clearafter pull-left';
		}

		// Genarate Container class
		$container_class = $container_class ? 'class="' . $container_class . '" ' : '';

		$params['video_source_link_youtube'] = urldecode( $params['video_source_link_youtube'] );
		// Get video ID.
		$video_info = $this->get_youtube_video_info( $params['video_source_link_youtube'] );
		$video_info = json_decode( $video_info );
		if ( ! $video_info )
		return;
		$video_info = $video_info->html;
		$_arr = array();
		$video_src = '';
		preg_match( '/src\s*\n*=\s*\n*"([^"]*)"/i', $video_info, $_arr );

		if ( count( $_arr ) ) {
			// Check if video url included playlist id.
			$pattern = '#list=([A-Za-z0-9^/]*)#i';
			$matches = array();
			preg_match_all( $pattern, $params['video_source_link_youtube'], $matches, PREG_SET_ORDER );

			if ( count( $matches ) ) {
				if ( isset( $params['video_youtube_show_list'] ) && $params['video_youtube_show_list'] == '1' ) {

					$video_src = 'http://www.youtube.com/embed?listType=playlist&list=';
					$_list_id = $matches[0][1];
					$video_src .= $_list_id;
					$video_src .= '&innerframe=true';
				} else {
					$video_src = $_arr[1];
					$video_src .= '&innerframe=true';
				}
			} else {
				$video_src = $_arr[1];
				$video_src .= '&innerframe=true';
			}

			parse_str( $params['video_source_link_youtube'], $youtube_url );

			$video_src .= isset($params['video_youtube_autoplay']) ? '&autoplay=' . (int) $params['video_youtube_autoplay'] : '';
			$video_src .= isset($params['video_youtube_autohide']) ? '&autohide=' . (int) $params['video_youtube_autohide'] : '';
			$video_src .= isset($params['video_youtube_controls']) ? '&controls=' . (int) $params['video_youtube_controls'] : '';
			$video_src .= isset($params['video_youtube_loop']) ? '&loop=' . (int) $params['video_youtube_loop'] . '&playlist=' . reset( $youtube_url ) : '';
			$video_src .= (isset($params['video_youtube_cc']) && (int) $params['video_youtube_cc'] == 1) ? '&cc_load_policy =1' : '';
			$video_src .= isset( $params['video_youtube_modestbranding'] ) ? '&modestbranding=' . (int) $params['video_youtube_modestbranding'] : '';
			$video_src .= isset( $params['video_youtube_rel'] ) ? '&rel=' . (int) $params['video_youtube_rel'] : '';
			$video_src .= isset( $params['video_youtube_showinfo'] ) ? '&showinfo=' . (int) $params['video_youtube_showinfo'] : '';
		}

		$embed = '<div ' . $container_class . '>';
		$embed .= '<iframe style="display:block;' . $object_style . '" ' . $_w . $_h . '
src="' . $video_src . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		$embed .= '</div>';

		return $embed;
	}

	/**
	 * Generate HTML for Vimeo.
	 *
	 * @param   array  $params  Shortcode parameters.
	 *
	 * @return  string  HTML code.
	 */
	function generate_vimeo( $params ) {
		$random_id = WR_Pb_Utils_Common::random_string();

		$_w = ' width="' . $params['video_vimeo_dimension_width'] . '" ';
		$_h = $params['video_vimeo_dimension_height'] ? ' height="' . $params['video_vimeo_dimension_height'] . '" ' : '';
		// Alignment
		$container_class = '';
		$object_style = '';
		if ( $params['video_alignment'] === 'right' ) {
			$object_style    .= 'float:right;';
			$container_class .= 'clearafter pull-right';
		} else if ( $params['video_alignment'] === 'center' ) {
			$object_style .= 'margin: 0 auto;';
		} else if ( $params['video_alignment'] === 'left' ) {
			$object_style    .= 'float:left;';
			$container_class .= 'clearafter pull-left';
		}

		// Genarate Container class
		$container_class = $container_class ? 'class="' . $container_class . '" ' : '';

		// Get video ID.
		$params['video_source_link_vimeo'] = urldecode( $params['video_source_link_vimeo'] );
		$video_info                        = self::get_vimeo_video_info( $params['video_source_link_vimeo'] );
		$video_info                        = json_decode( $video_info );
		if ( ! $video_info )
		return;
		$video_info = $video_info->html;
		$_arr = array();
		$video_src = '';
		preg_match( '/src\s*\n*=\s*\n*"([^"]*)"/i', $video_info, $_arr );
		if ( count( $_arr ) ) {
			$video_src = $_arr[1];
			$video_src .= '?innerframe=true';
			$video_src .= isset($params['video_vimeo_autoplay']) ? '&autoplay=' . (string) $params['video_vimeo_autoplay'] : '';
			$video_src .= isset($params['video_vimeo_loop']) ? '&loop=' . (string) $params['video_vimeo_loop'] : '';
			$video_src .= isset($params['video_vimeo_title']) ? '&title=' . (string) $params['video_vimeo_title'] : '';
			$video_src .= isset($params['video_vimeo_color']) ? '&color=' . str_replace( '#', '', (string) $params['video_vimeo_color'] ) : '';
		}

		$embed = '<div ' . $container_class . '>';
		$embed .= '<iframe webkitallowfullscreen mozallowfullscreen allowfullscreen style="display:block;' . $object_style . '" ' . $_w . $_h . '"
src="' . $video_src . '" frameborder="0"></iframe>';
		$embed .= '</div>';


		return $embed;
	}

	/**
	 * Enqueue custom asset for front-end.
	 *
	 * @return  void
	 */
	public function custom_assets_frontend() {
		parent::custom_assets_frontend();
		if ( file_exists( ABSPATH . 'wp-includes/js/mediaelement/wp-mediaelement.js' ) ) {
			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}
	}

	/**
	 * Method to check if file existed.
	 *
	 * @return  void
	 */
	function validate_file() {
		$file_url  = $_POST['file_url'];
		$file_type = (string) $_POST['file_type'];
		if ( $file_type == 'youtube' ) {
			$content = $this->get_youtube_video_info( $file_url );
			$info    = json_decode( $content );
			if ( count( $info ) ) {
				$data    = array();
				$content = '';
				$content .= __( 'Title', WR_PBL ) . ': <b>' . (string) $info->title . '</b><br>';
				$content .= __( 'Author Name', WR_PBL ) . ': <b>' . (string) $info->author_name . '</b><br>';

				$info->description = isset( $info->description ) ? wp_trim_words( (string) $info->description, 20 ) : '';
				$content           .= __( 'Description', WR_PBL ) . ': <b>' . (string) $info->description . '</b><br>';
				$data['content']   = $content;
				$data['type']      = 'video';

				// Check if url had this format "list=SJHkjhlKJHSA".
				$pattern = '#list=[A-Za-z0-9^/]*#i';
				if ( preg_match( $pattern, $file_url ) && stripos( $info->html, 'videoseries?' ) === false ) {
					$data['type'] = 'list';
				}
				exit( json_encode( $data ) );
			}
		} else if ( $file_type == 'vimeo' ) {
			$content = self::get_vimeo_video_info( $file_url );
			$info    = json_decode( $content );
			if ( count( $info ) ) {
				$data    = array();
				$content = '';
				$content .= __( 'Title', WR_PBL ) . ': <b>' . (string) $info->title . '</b><br>';
				$content .= __( 'Author Name', WR_PBL ) . ': <b>' . (string) $info->author_name . '</b><br>';

				$info->description = isset( $info->description ) ? wp_trim_words( (string) $info->description, 20 ) : '';
				$content           .= __( 'Description', WR_PBL ) . ': <b>' . (string) $info->description . '</b><br>';
				$data['content']   = $content;
				exit( json_encode( $data ) );
			}
		}

		exit('false');
	}

	/**
	 * Method to get video info from Youtube.
	 *
	 * @param   string  $file_url  Link to Youtube video.
	 *
	 * @return  mixed  Video info or boolean FALSE on failure.
	 */
	function get_youtube_video_info( $file_url ) {
		if ( empty( $file_url ) )
		return NULL;
		$api_url = 'http://www.youtube.com/oembed?url=' . $file_url . '&format=json';
		$html    = wp_remote_get( $api_url );
		if ( isset( $html['body'] ) ) {
			return $html['body'];
		}
		return false;
	}

	/**
	 * Method to get video info from Vimeo.
	 *
	 * @param   string  $file_url  Link to Youtube video.
	 *
	 * @return  mixed  Video info or boolean FALSE on failure.
	 */
	static function get_vimeo_video_info( $file_url ) {
		if ( empty( $file_url ) )
		return NULL;
		$api_url = 'http://vimeo.com/api/oembed.json?url=' . $file_url;
		$html    = wp_remote_get( $api_url );
		if ( isset( $html['body'] ) ) {
			return $html['body'];
		}
		return false;
	}
}

endif;
