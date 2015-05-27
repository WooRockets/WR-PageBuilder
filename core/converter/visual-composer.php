<?php
/**
 * @version    $Id$
 * @package    WR_PageBuilder
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */

/**
 * Visual Composer data converter class.
 *
 * @since  2.3.0
 */
class WR_Pb_Converter_Visual_Composer extends WR_Pb_Converter {
	/**
	 * Converter file name without extension.
	 *
	 * @var  string
	 */
	protected $converter = 'visual-composer';

	/**
	 * Data mapping.
	 *
	 * @var  array
	 */
	protected $mapping = array(
		'vc_row' => array(
			'tag'        => 'wr_row',
			'attributes' => array(
				'bg_color'        => 'solid_color_value',
				'padding_top'     => 'div_padding_top',
				'padding_right'   => 'div_padding_right',
				'padding_bottom'  => 'div_padding_bottom',
				'padding_left'    => 'div_padding_left',
				'bg_image'        => 'pattern',
				'bg_image_repeat' => 'stretch',
				'el_class'        => 'css_suffix',
				'background'      => 'background',
			),
		),

		'vc_column' => array(
			'tag'        => 'wr_column',
			'attributes' => array(
				'width' => 'span',
			),
		),

		'vc_column_text' => array(
			'tag' => 'wr_text',
			'attributes' => array(
				'el_class' => 'css_suffix',
			)
		),

		'vc_separator' => array(
			'tag' => 'wr_divider',
			'attributes' => array(
				'bg_color' => 'div_border_color',
				'style'    => 'div_border_style',
				'el_class' => 'css_suffix',
			),
		),

		'vc_text_separator' => array(
			'tag' => 'wr_divider',
			'attributes' => array(
				'title'    => 'el_title',
				'bg_color' => 'div_border_color',
				'style'    => 'div_border_style',
				'el_class' => 'css_suffix',
			),
		),

		'vc_message' => array(
			'tag' => 'wr_alert',
			'attributes' => array(
				'color'    => 'alert_style',
				'el_class' => 'css_suffix',
			),
		),
        'vc_message' => array(
            'tag' => 'wr_share',
            'attributes' => array(
                'color'    => 'alert_style',
                'el_class' => 'css_suffix',
            ),
        ),

		'vc_toggle' => array(
			'tag' => 'wr_item_accordion',
			'attributes' => array(
				'title'    => 'heading',
				'color'    => 'alert_style',
				'el_class' => 'css_suffix',
			),
		),

		'vc_single_image' => array(
			'tag' => 'wr_image',
			'attributes' => array(
				'title'           => 'el_title',
				'image'           => 'image_file',
				'css_animation'   => 'image_effect',
				'img_size'        => 'image_size',
				'alignment'       => 'image_alignment',
				'style'           => 'image_container_style',
				'img_link'        => 'image_type_url',
				'img_link_target' => 'open_in',
				'el_class'        => 'css_suffix',
				'link_type'       => 'link_type',
			),
		),

		'vc_tabs' => array(
			'tag' => 'wr_tab',
			'attributes' => array(
				'title'        => 'el_title',
				'el_class'     => 'css_suffix',
			),
		),

		'vc_tab' => array(
			'tag' => 'wr_item_tab',
			'attributes' => array(
				'title'  => 'heading',
				'tab_id' => 'id_wrapper',
			),
		),

		'vc_tour' => array(
			'tag' => 'wr_tab',
			'attributes' => array(
				'title'        => 'el_title',
				'el_class'     => 'css_suffix',
				'tab_position' => 'tab_position',
			),
		),

		'vc_accordion' => array(
			'tag' => 'wr_accordion',
			'attributes' => array(
				'title'      => 'el_title',
				'active_tab' => 'initial_open',
				'el_class'   => 'css_suffix',
			),
		),

		'vc_accordion_tab' => array(
			'tag' => 'wr_item_accordion',
			'attributes' => array(
				'title' => 'heading',
			),
		),

		'vc_button' => array(
			'tag' => 'wr_button',
			'attributes' => array(
				'title'     => 'button_text',
				'href'      => 'button_type_url',
				'target'    => 'open_in',
				'color'     => 'button_color',
				'icon'      => 'icon',
				'size'      => 'button_size',
				'el_title'  => 'el_title',
				'el_class'  => 'css_suffix',
				'link_type' => 'link_type',
			),
		),

		'vc_cta_button' => array(
			'tag' => 'wr_promobox',
			'attributes' => array(
				'title'        => 'pb_button_title',
				'href'         => 'pb_button_url',
				'target'       => 'pb_button_open_in',
				'color'        => 'pb_button_color',
				'size'         => 'pb_button_size',
				'h2'           => 'pb_title',
				'accent_color' => 'pb_bg_color',
				'el_title'     => 'el_title',
				'el_class'     => 'css_suffix',
				'link_type'    => 'link_type',
			),
		),

		'vc_video' => array(
			'tag' => 'wr_video',
			'attributes' => array(
				'title'         => 'el_title',
				'link_local'    => 'video_source_local',
				'link_youtube'  => 'video_source_link_youtube',
				'link_vimeo'    => 'video_source_link_vimeo',
				'el_class'      => 'css_suffix',
				'video_sources' => 'video_sources',
			),
		),

		'vc_progress_bar' => array(
			'tag' => 'wr_progressbar',
			'attributes' => array(
				'title'         => 'el_title',
				'el_class'      => 'css_suffix',
			),
		),

		'wr_accordion' => array(
			'attributes' => array(
				'multi_open' => 'multi_open',
			),
		),

		'wr_carousel' => array(
			'attributes' => array(
				'title'              => 'el_title',
				'interval'           => 'automatic_cycling',
				'autoplay'           => 'automatic_cycling',
				'pagination_control' => 'show_indicator',
				'prev_next_buttons'  => 'show_arrows',
				'el_class'           => 'css_suffix',
			),
		),

		'wr_item_carousel' => array(
			'attributes' => array(
				'image_file' => 'image_file',
			),
		),

		'wr_contentclips' => array(
			'attributes' => array(
				'title'                    => 'el_title',
				'size'                     => 'wr_cl_limit',
				'order_by'                 => 'wr_cl_orderby',
				'order'                    => 'wr_cl_order',
				'post_type'                => 'wr_cl_source',
				'item_filter'              => 'item_filter',
				'item_filter_select_multi' => 'item_filter_select_multi',
				'grid_columns_count'       => 'items_per_slide',
				'grid_layout'              => 'elements',
				'layout'                   => 'elements',
				'slides_per_view'          => 'items_per_slide',
				'slider_elements'          => 'slider_elements',
				'count'                    => 'wr_cl_limit',
				'posttypes'                => 'wr_cl_source',
				'categories'               => 'item_filter',
				'orderby'                  => 'wr_cl_orderby',
				'el_class'                 => 'css_suffix',
			),
		),

		'wr_item_progressbar' => array(
			'attributes' => array(
				'pbar_text'       => 'pbar_text',
				'pbar_percentage' => 'pbar_percentage',
				'pbar_item_style' => 'pbar_item_style',
				'bgcolor'         => 'pbar_color',
			),
		),

		'wr_progresscircle' => array(
			'attributes' => array(
				'title'       => 'el_title',
				'value'       => 'percent',
				'label_value' => 'text',
				'el_class'    => 'css_suffix',
			),
		),

		'wr_widget' => array(
			'attributes' => array(
				'widget_id' => 'widget_id',
			),
		),
	);

	/**
	 * Icon mapping.
	 *
	 * @var  array
	 */
	protected static $icon_mapping = array(
		'wpb_address_book'      => 'icon-address',
		'wpb_alarm_clock'       => 'icon-clock',
		'wpb_anchor'            => 'icon-location',
		'wpb_application_image' => 'icon-picture',
		'wpb_arrow'             => 'icon-arrow-right-2',
		'wpb_asterisk'          => 'icon-star',
		'wpb_hammer'            => 'icon-wrench',
		'wpb_balloon'           => 'icon-comments',
		'wpb_balloon_buzz'      => 'icon-comments-2',
		'wpb_binocular'         => 'icon-search',
		'wpb_bookmark'          => 'icon-bookmark',
		'wpb_camcorder'         => 'icon-camera-2',
		'wpb_camera'            => 'icon-camera',
		'wpb_chart'             => 'icon-bars',
		'wpb_chart_pie'         => 'icon-pie',
		'wpb_clock'             => 'icon-clock',
		'wpb_mail'              => 'icon-mail',
		'wpb_play'              => 'icon-play-2',
	);

	/**
	 * Button size mapping.
	 *
	 * @var  array
	 */
	protected static $btn_size_mapping = array(
		'btn-mini'  => 'btn-xs',
		'btn-small' => 'btn-sm',
		'btn-large' => 'btn-lg',
		'xs'        => 'btn-xs',
		'sm'        => 'btn-sm',
		'lg'        => 'btn-lg',
	);

	/**
	 * Widget mapping.
	 *
	 * @var  array
	 */
	protected static $widget_mapping = array(
		'vc_wp_search'         => 'WP_Widget_Search',
		'vc_wp_meta'           => 'WP_Widget_Meta',
		'vc_wp_recentcomments' => 'WP_Widget_Recent_Comments',
		'vc_wp_calendar'       => 'WP_Widget_Calendar',
		'vc_wp_pages'          => 'WP_Widget_Pages',
		'vc_wp_tagcloud'       => 'WP_Widget_Tag_Cloud',
		'vc_wp_custommenu'     => 'WP_Nav_Menu_Widget',
		'vc_wp_text'           => 'WP_Widget_Text',
		'vc_wp_posts'          => 'WP_Widget_Recent_Posts',
		'vc_wp_links'          => 'WP_Widget_Links',
		'vc_wp_categories'     => 'WP_Widget_Categories',
		'vc_wp_archives'       => 'WP_Widget_Archives',
		'vc_wp_rss'            => 'WP_Widget_RSS',
	);

	/**
	 * Constructor
	 *
	 * @param   WP_Post  $post  WordPress's post object.
	 *
	 * @return  void
	 */
	public function __construct( $post ) {
		parent::__construct( $post );

		// Register filter to prepare Visual Composer data
		add_filter( "wr_pb_parse_data_{$this->converter}", array( &$this, 'prepare_visual_composer_data' ) );

		// Register filters to convert Visual Composer shortcodes
		add_filter( 'wr_pb_convert_vc_row_shortcode'            , array( &$this, 'convert_vc_row_shortcode'          ) );
		add_filter( 'wr_pb_convert_vc_column_shortcode'         , array( &$this, 'convert_vc_column_shortcode'       ) );
		add_filter( 'wr_pb_convert_vc_separator_shortcode'      , array( &$this, 'convert_vc_separator_shortcode'    ) );
		add_filter( 'wr_pb_convert_vc_text_separator_shortcode' , array( &$this, 'convert_vc_separator_shortcode'    ) );
		add_filter( 'wr_pb_convert_vc_single_image_shortcode'   , array( &$this, 'convert_vc_single_image_shortcode' ) );
		add_filter( 'wr_pb_convert_vc_gallery_shortcode'        , array( &$this, 'convert_vc_gallery_shortcode'      ) );
		add_filter( 'wr_pb_convert_vc_images_carousel_shortcode', array( &$this, 'convert_vc_gallery_shortcode'      ) );
		add_filter( 'wr_pb_convert_vc_tabs_shortcode'           , array( &$this, 'convert_vc_tabs_shortcode'         ) );
		add_filter( 'wr_pb_convert_vc_tour_shortcode'           , array( &$this, 'convert_vc_tabs_shortcode'         ) );
		add_filter( 'wr_pb_convert_vc_posts_grid_shortcode'     , array( &$this, 'convert_vc_posts_grid_shortcode'   ) );
		add_filter( 'wr_pb_convert_vc_carousel_shortcode'       , array( &$this, 'convert_vc_posts_grid_shortcode'   ) );
		add_filter( 'wr_pb_convert_vc_posts_slider_shortcode'   , array( &$this, 'convert_vc_posts_grid_shortcode'   ) );
		add_filter( 'wr_pb_convert_vc_button_shortcode'         , array( &$this, 'convert_vc_button_shortcode'       ) );
		add_filter( 'wr_pb_convert_vc_button2_shortcode'        , array( &$this, 'convert_vc_button_shortcode'       ) );
		add_filter( 'wr_pb_convert_vc_cta_button_shortcode'     , array( &$this, 'convert_vc_cta_button_shortcode'   ) );
		add_filter( 'wr_pb_convert_vc_cta_button2_shortcode'    , array( &$this, 'convert_vc_cta_button_shortcode'   ) );
		add_filter( 'wr_pb_convert_vc_video_shortcode'          , array( &$this, 'convert_vc_video_shortcode'        ) );
		add_filter( 'wr_pb_convert_vc_gmaps_shortcode'          , array( &$this, 'convert_vc_gmaps_shortcode'        ) );
		add_filter( 'wr_pb_convert_vc_raw_html_shortcode'       , array( &$this, 'convert_vc_raw_html_shortcode'     ) );
		add_filter( 'wr_pb_convert_vc_raw_js_shortcode'         , array( &$this, 'convert_vc_raw_html_shortcode'     ) );
		add_filter( 'wr_pb_convert_vc_progress_bar_shortcode'   , array( &$this, 'convert_vc_progress_bar_shortcode' ) );
		add_filter( 'wr_pb_convert_vc_pie_shortcode'            , array( &$this, 'convert_vc_pie_shortcode'          ) );

		// Register action to finalize data conversion
		add_action( "wr_pb_after_convert_{$this->converter}_data", array( &$this, 'finalize' ) );
	}

	/**
	 * Check if there is Visual Composer data to convert.
	 *
	 * @param   WP_Post  $post  WordPress's post object.
	 *
	 * @return  mixed
	 */
	public static function check( $post ) {
		if ( empty( $post->post_content ) || false === strpos( $post->post_content, '[vc_' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Prepare Visual Composer data in current post.
	 *
	 * @return  mixed
	 */
	public function prepare_visual_composer_data() {
		// Check if current post has Visual Composer data
		if ( ! self::check( $this->post ) ) {
			return '';
		}

		// Clean-up all nested rows and columns because WR PageBuilder does not support nested row
		$this->post->post_content = preg_replace(
		array( '/\[vc_row_inner[^\]]*\]/', '/\[vc_column_inner[^\]]*\]/' ),
		array(                         '', ''                            ),
		$this->post->post_content
		);

		$this->post->post_content = str_replace(
		array( '[/vc_row_inner]', '[/vc_column_inner]' ),
		array(                '', ''                   ),
		$this->post->post_content
		);

		// Wrap continuous `vc_toggle` shortcode inside an WR Accordion element
		$parts = explode( '[vc_toggle', $this->post->post_content );

		if ( 1 < count( $parts ) ) {
			$first = true;

			for ( $i = 1, $n = count( $parts ); $i < $n; $i++ ) {
				$part = & $parts[ $i ];

				// Rebuild content
				$part = '[vc_toggle' . $part;

				if ( $first ) {
					$part = '[wr_accordion multi_open="yes"]' . $part;

					$first = false;
				}

				if ( '[/vc_toggle]' != substr( $part, -12 ) ) {
					$part = str_replace( '[/vc_toggle]', '[/vc_toggle][/wr_accordion]', $part );

					$first = true;
				}
			}

			$this->post->post_content = implode( $parts );
		}

		// Convert shortcode for loading widget globally
		foreach ( self::$widget_mapping as $tag => $widget ) {
			if ( false !== strpos( $this->post->post_content, '[' . $tag ) ) {
				$this->post->post_content = str_replace( '[' . $tag, '[wr_widget widget_id="' . $widget . '"', $this->post->post_content );
			}
		}

		return array();
	}

	/**
	 * Finalize data conversion.
	 *
	 * @param   array  $new_post  Array of new post data.
	 *
	 * @return  void
	 */
	public function finalize( $new_post ) {
		// Delete Visual Composer post meta fields
		global $wpdb, $table_prefix;

		$wpdb->query( "DELETE FROM {$table_prefix}postmeta WHERE post_id = {$new_post['ID']} AND (meta_key LIKE '_wpb_vc%' OR meta_key = 'vc_teaser');" );
	}

	/**
	 * Prepare parameters for `vc_row` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_row_shortcode( $element ) {
		// Shorten attributes access
		$params = & $element['attributes'];

		// Prepare `vc_row` shortcode parameters
		if ( isset( $params['padding'] ) && ! empty( $params['padding'] ) ) {
			foreach ( array( 'top', 'right', 'bottom', 'left' ) as $dir ) {
				$params["padding_{$dir}"] = $params['padding'];
			}

			unset( $params['padding'] );
		}

		if ( isset( $params['bg_color'] ) && ! empty( $params['bg_color'] ) ) {
			// Set background type
			$params['background'] = 'solid';
		}

		if ( isset( $params['bg_image'] ) && ! empty( $params['bg_image'] ) ) {
			// Set background type
			$params['background'] = 'pattern';

			// Get URL to selected background image
			$params['bg_image'] = wp_get_attachment_url( $params['bg_image'] );

			// Prepare background repeat value
			if ( isset( $params['bg_image_repeat'] ) ) {
				if ( '' == $params['bg_image_repeat'] ) {
					$params['bg_image_repeat'] = 'none';
				} elseif ( 'no-repeat' == $params['bg_image_repeat'] ) {
					$params['bg_image_repeat'] = 'full';
				}
			}
		}

		return $element;
	}

	/**
	 * Prepare parameters for `vc_column` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_column_shortcode( $element ) {
		// Shorten attributes access
		$params = & $element['attributes'];

		// Prepare `vc_column` shortcode parameters
		if ( isset( $params['width'] ) && ! empty( $params['width'] ) ) {
			eval( '$params["width"] = "span" . round( ( ' . $params['width'] . ' ) * 12 );' );
		} else {
			$params['width'] = 'span12';
		}

		return $element;
	}

	/**
	 * Prepare parameters for `vc_separator` / `vc_text_separator` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_separator_shortcode( $element ) {
		// Shorten attributes access
		$params = & $element['attributes'];

		// Prepare `vc_separator` / `vc_text_separator` shortcode parameters
		if ( isset( $params['color'] ) && ! empty( $params['color'] ) ) {
			$params['bg_color'] = $params['color'];

			unset( $params['color'] );
		}

		if ( isset( $params['accent_color'] ) && ! empty( $params['accent_color'] ) ) {
			$params['bg_color'] = $params['accent_color'];

			unset( $params['accent_color'] );
		}

		return $element;
	}

	/**
	 * Prepare parameters for `vc_single_image` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_single_image_shortcode( $element ) {
		// Shorten attributes access
		$params = & $element['attributes'];

		// Prepare `vc_single_image` shortcode parameters
		if ( isset( $params['image'] ) && ! empty( $params['image'] ) ) {
			// Get URL to selected image
			$params['image'] = wp_get_attachment_url( $params['image'] );
		}

		if ( isset( $params['css_animation'] ) ) {
			if ( empty( $params['css_animation'] ) ) {
				$params['css_animation'] = 'no';
			} else {
				$params['css_animation'] = 'yes';
			}
		}

		if ( isset( $params['img_size'] ) && ! in_array( $params['img_size'], array( 'thumbnail', 'medium', 'large', 'full' ) ) ) {
			unset( $params['img_size'] );
		}

		if ( isset( $params['alignment'] ) && empty( $params['alignment'] ) ) {
			$params['alignment'] = 'left';
		}

		if ( isset( $params['style'] ) && ! empty( $params['style'] ) ) {
			if ( 'rounded' == substr( $params['style'], -7 ) ) {
				$params['style'] = 'img-rounded';
			} elseif ( 'circle' == substr( $params['style'], -6 ) ) {
				$params['style'] = 'img-circle';
			} else {
				$params['style'] = 'img-thumbnail';
			}
		}

		if ( isset( $params['img_link'] ) && ! empty( $params['img_link'] ) ) {
			// Set link type
			$params['link_type'] = 'url';
		}

		if ( isset( $params['img_link_large'] ) && ! empty( $params['img_link_large'] ) ) {
			// Set link type
			$params['link_type'] = 'large_image';
		}

		if ( isset( $params['img_link_target'] ) && ! empty( $params['img_link_target'] ) ) {
			if ( '_self' == $params['img_link_target'] ) {
				$params['img_link_target'] = 'current_browser';
			} elseif ( 'lightbox' != $params['img_link_target'] ) {
				$params['img_link_target'] = 'new_browser';
			}
		}

		return $element;
	}

	/**
	 * Convert `vc_gallery` / `vc_images_carousel` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_gallery_shortcode( $element ) {
		// Check if type of `vc_gallery` is image grid
		if ( isset( $element['attributes']['type'] ) && 'image_grid' == $element['attributes']['type'] ) {
			// Convert to WordPress's built-in `gallery` shortcode
			$element['tag'] = 'gallery';

			// Convert shortcode parameters
			$element['attributes']['ids' ] = $element['attributes']['images'  ];
			$element['attributes']['size'] = $element['attributes']['img_size'];

			if ( isset( $element['attributes']['onclick'] ) && 'link_no' == $element['attributes']['onclick'] ) {
				$element['attributes']['link'] = 'none';
			}

			// Unset junk attributes
			unset( $element['attributes']['type'    ] );
			unset( $element['attributes']['images'  ] );
			unset( $element['attributes']['img_size'] );
			unset( $element['attributes']['onclick' ] );

			return $element;
		}

		// Prepare shortcode parameters
		if ( isset( $element['attributes']['interval'] ) ) {
			if ( ! (int) $element['attributes']['interval'] ) {
				$element['attributes']['interval'] = 'no';
			} else {
				$element['attributes']['interval'] = 'yes';
			}
		}

		if ( isset( $element['attributes']['hide_pagination_control'] ) ) {
			$element['attributes']['pagination_control'] = 'no';
		}

		if ( isset( $element['attributes']['hide_prev_next_buttons'] ) ) {
			$element['attributes']['prev_next_buttons'] = 'no';
		}

		// Prepare shortcode children
		$children = array();
		$slides   = array();
		$images   = explode( ',', $element['attributes']['images'] );

		if ( ! isset( $element['attributes']['slides_per_view'] ) ) {
			$element['attributes']['slides_per_view'] = 1;
		}

		for ( $i = 0, $n = count( $images ); $i < $n; $i++ ) {
			if ( 1 < $element['attributes']['slides_per_view'] || ( isset( $element['attributes']['onclick'] ) && 'link_no' != $element['attributes']['onclick'] ) ) {
				// Prepare image attributes
				$image_attrs = array();

				$image_attrs['image'   ] = $images[ $i ];
				$image_attrs['img_size'] = $element['attributes']['img_size'];

				if ( isset( $element['attributes']['onclick'] ) && 'link_no' != $element['attributes']['onclick'] ) {
					if ( 'link_image' == $element['attributes']['onclick'] ) {
						$image_attrs['img_link_large' ] = 'yes';
						$image_attrs['img_link_target'] = 'lightbox';
					} elseif ( isset( $element['attributes']['custom_links'] ) && ! empty( $element['attributes']['custom_links'] ) ) {
						// Parse custom links
						if ( ! isset( $image_links ) ) {
							$image_links = explode( ',', $element['attributes']['custom_links'] );
							$image_index = 0;
						}

						$image_attrs['img_link'] = @$image_links[ $image_index++ ];

						if ( isset( $element['attributes']['custom_links_target'] ) ) {
							if ( '_self' == $element['attributes']['custom_links_target'] ) {
								$image_attrs['img_link_target'] = 'current_browser';
							} else {
								$image_attrs['img_link_target'] = 'new_browser';
							}
						}
					}
				}

				// Create new `vc_single_image` shortcode
				$slides[] = array(
					'tag'        => 'vc_single_image',
					'attributes' => $image_attrs,
				);

				// Check if we have enought slides per view
				if ( (int) $element['attributes']['slides_per_view'] == count( $slides ) || $n == $i + 1 ) {
					// Create new WR Item Carousel element
					$children[] = array(
						'tag'      => 'wr_item_carousel',
						'children' => $slides,
					);

					// Reset slides array
					$slides = array();
				}
			} else {
				// Create new `wr_item_carousel` shortcode
				$children[] = array(
					'tag'        => 'wr_item_carousel',
					'attributes' => array( 'image_file' => wp_get_attachment_url( $images[ $i ] ) ),
				);
			}
		}

		// Create new WR Carousel element
		$carousel = array(
			'tag'        => 'wr_carousel',
			'attributes' => $element['attributes'],
			'children'   => $children,
		);

		return $carousel;
	}

	/**
	 * Convert `vc_tabs` / `vc_tour` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_tabs_shortcode( $element ) {
		// Shorten attributes access
		$params = & $element['attributes'];

		// Prepare `vc_tabs` / `vc_tour` shortcode parameters
		if ( 'vc_tour' == $element['tag'] ) {
			$params['tab_position'] = 'left';
		}

		return $element;
	}

	/**
	 * Convert `vc_posts_grid` / `vc_carousel` / `vc_posts_slider` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_posts_grid_shortcode( $element ) {
		// Check if equivalent element available in WR PageBuilder
		global $shortcode_tags;

		if ( ! array_key_exists( 'wr_contentclips', $shortcode_tags ) ) {
			// Equivalent element not available, keep original shortcode as is
			return $element;
		}

		// Shorten attributes access
		$params = & $element['attributes'];

		// Prepare `vc_posts_grid` / `vc_carousel` / `vc_posts_slider` shortcode parameters
		if ( 'vc_posts_slider' != $element['tag'] ) {
			if ( isset( $params['loop'] ) && ! empty( $params['loop'] ) ) {
				$query = $params['loop'];
			}

			if ( isset( $params['posts_query'] ) && ! empty( $params['posts_query'] ) ) {
				$query = $params['posts_query'];
			}

			if ( isset( $query ) ) {
				// Parse posts query
				$pairs = explode( '|', $query );
				$query = array();

				foreach ( $pairs as $pair ) {
					$pair = explode( ':', $pair, 2 );

					// Keep original shortcode as is if individual posts are sepcified
					if ( 'by_id' == $pair[0] && ! empty( $pair[1] ) ) {
						return $element;
					}

					// Simply continue if param is not supported
					if ( 'authors' == $pair[0] ) {
						continue;
					}

					// Prepare loop parameters
					switch ( $pair[0] ) {
						case 'order_by' :
							if ( ! in_array( $pair[1], array( 'title', 'comment_count', 'date' ) ) ) {
								$pair[1] = 'no_order';
							}
							break;

						case 'post_type' :
							if ( false != strpos( $pair[1], ',' ) ) {
								$pair[1] = 'post';
							}
							break;

						case 'categories' :
						case 'tags' :
						case 'tax_query' :
							// Set multi filter
							$params['item_filter_select_multi'] = 1;

							// Update filter
							if ( isset( $params['item_filter'] ) ) {
								$pair[1] = $params['item_filter'] . ',' . $pair[1];
							}
							break;
					}

					$query[ $pair[0] ] = $pair[1];
				}

				$params = array_merge( $params, $query );
			}

			// Prepare layout parameters
			if ( isset( $params['grid_layout'] ) && ! empty( $params['grid_layout'] ) ) {
				$layout = & $params['grid_layout'];
			}

			if ( isset( $params['layout'] ) && ! empty( $params['layout'] ) ) {
				$layout = & $params['layout'];
			}

			if ( isset( $layout ) ) {
				// Define attributes mapping
				$mapping = array(
					'title' => 'title',
					'image' => 'thumbnail',
					'text'  => 'description',
				);

				// Parse grid layout parameters
				$grid_layout = explode( ',', $layout );
				$elements    = '';

				foreach ( $grid_layout as $elm ) {
					if ( false !== ($pos = strpos( $elm, '|' ) ) ) {
						$elm = substr( $elm, 0, $pos );
					}

					if ( isset( $mapping[ $elm ] ) ) {
						$elements .= $mapping[ $elm ] . '__#__';
					}
				}

				$layout = $elements;
			}

			// Prepare other parameters
			$params['slider_elements'] = '';

			if ( ! isset( $params['hide_prev_next_buttons'] ) ) {
				$params['slider_elements'] .= 'arrows__#__';
			}

			if ( ! isset( $params['hide_pagination_control'] ) ) {
				$params['slider_elements'] .= 'indicator__#__';
			}
		} else {
			// Keep original shortcode as is if individual posts are sepcified
			if ( isset( $params['posts_in'] ) && ! empty( $params['posts_in'] ) ) {
				return $element;
			}

			if ( isset( $params['posttypes'] ) && ! empty( $params['posttypes'] ) ) {
				if ( false != strpos( $params['posttypes'], ',' ) ) {
					$params['posttypes'] = 'post';
				}
			}

			if ( isset( $params['categories'] ) && ! empty( $params['categories'] ) ) {
				// Get categories ID
				$categories           = explode( ',', $params['categories'] );
				$params['categories'] = array();

				foreach ( $categories as $category ) {
					if ( 0 < ( $category = get_cat_ID( $category ) ) ) {
						$params['categories'][] = $category;
					}
				}

				if ( count( $params['categories'] ) ) {
					$params['categories'              ] = implode( ',', $params['categories'] );
					$params['item_filter_select_multi'] = 1;
				}
			}
		}

		// Convert shortcode tag
		$element['tag'] = 'wr_contentclips';

		return $element;
	}

	/**
	 * Convert `vc_button` / `vc_button2` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_button_shortcode( $element ) {
		// Shorten attributes access
		$params = & $element['attributes'];

		// Prepare `vc_button` / `vc_button2` shortcode parameters
		if ( ! isset( $params['href'] ) && isset( $params['link'] ) && ! empty( $params['link'] ) ) {
			$params['href'] = $params['link'];
		}

		if ( isset( $params['href'] ) && ! empty( $params['href'] ) ) {
			// Set link type
			$params['link_type'] = 'url';

			// Parse link attributes
			$link_attrs = explode( '|', $params['href'] );

			foreach ( $link_attrs as $link_attr ) {
				if ( empty( $link_attr ) ) {
					continue;
				}

				// Parse name:value pair
				$pair = explode( ':', $link_attr, 2 );

				if ( 1 == count( $pair ) ) {
					$params['href'] = $link_attr;
				} else {
					if ( 'url' == $pair[0] ) {
						$params['href'] = urldecode( $pair[1] );
					} else if ( 'title' == $pair[0] ) {
						$params['el_title'] = urldecode( $pair[1] );
					} else {
						$params[ $pair[0] ] = trim( $pair[1] );
					}
				}
			}
		}

		if ( isset( $params['target'] ) ) {
			if ( '_self' == $params['target'] ) {
				$params['target'] = 'current_browser';
			} else {
				$params['target'] = 'new_browser';
			}
		}

		if ( isset( $params['color'] ) && ! empty( $params['color'] ) ) {
			if ( 'vc_button2' == $element['tag'] ) {
				$params['color'] = 'btn-default';
			} elseif ( 'wpb_button' == $params['color'] ) {
				$params['color'] = 'btn-default';
			} elseif ( 'btn-inverse' == $params['color'] ) {
				$params['color'] = 'btn-link';
			}
		}

		if ( isset( $params['icon'] ) && ! empty( $params['icon'] ) ) {
			if ( isset( self::$icon_mapping[ $params['icon'] ] ) ) {
				$params['icon'] = self::$icon_mapping[ $params['icon'] ];
			} else {
				$params['icon'] = '';
			}
		}

		if ( isset( $params['size'] ) && ! empty( $params['size'] ) ) {
			if ( isset( self::$btn_size_mapping[ $params['size'] ] ) ) {
				$params['size'] = self::$btn_size_mapping[ $params['size'] ];
			} else {
				$params['size'] = 'default';
			}
		}

		// Convert shortcode tag if needed
		if ( 'vc_button2' == $element['tag'] ) {
			$element['tag'] = 'vc_button';
		}

		return $element;
	}

	/**
	 * Convert `vc_cta_button` / `vc_cta_button2` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_cta_button_shortcode( $element ) {
		// Shorten attributes access
		$params = & $element['attributes'];

		// Prepare `vc_cta_button` / `vc_cta_button2` shortcode parameters
		if ( ! isset( $params['href'] ) && isset( $params['link'] ) && ! empty( $params['link'] ) ) {
			$params['href'] = $params['link'];
		}

		if ( isset( $params['href'] ) && ! empty( $params['href'] ) ) {
			// Set link type
			$params['link_type'] = 'url';

			// Parse link attributes
			$link_attrs = explode( '|', $params['href'] );

			foreach ( $link_attrs as $link_attr ) {
				if ( empty( $link_attr ) ) {
					continue;
				}

				// Parse name:value pair
				$pair = explode( ':', $link_attr, 2 );

				if ( 1 == count( $pair ) ) {
					$params['href'] = $link_attr;
				} else {
					if ( 'url' == $pair[0] ) {
						$params['href'] = urldecode( $pair[1] );
					} else if ( 'title' == $pair[0] ) {
						$params['el_title'] = urldecode( $pair[1] );
					} else {
						$params[ $pair[0] ] = trim( $pair[1] );
					}
				}
			}
		}

		if ( isset( $params['target'] ) ) {
			if ( '_self' == $params['target'] ) {
				$params['target'] = 'current_browser';
			} else {
				$params['target'] = 'new_browser';
			}
		}

		if ( isset( $params['color'] ) && ! empty( $params['color'] ) ) {
			if ( 'vc_cta_button2' == $element['tag'] ) {
				$params['color'] = 'btn-default';
			} elseif ( 'wpb_button' == $params['color'] ) {
				$params['color'] = 'btn-default';
			} elseif ( 'btn-inverse' == $params['color'] ) {
				$params['color'] = 'btn-link';
			}
		}

		if ( isset( $params['size'] ) && ! empty( $params['size'] ) ) {
			if ( isset( self::$btn_size_mapping[ $params['size'] ] ) ) {
				$params['size'] = self::$btn_size_mapping[ $params['size'] ];
			} else {
				$params['size'] = 'default';
			}
		}

		// Prepare shortcode children
		if ( ! isset( $element['children'] ) ) {
			$element['children'] = '';
		}

		if ( isset( $params['h4'] ) && ! empty( $params['h4'] ) ) {
			$element['children'] = '<h4>' . $params['h4'] . '</h4>' . $element['children'];
		}

		if ( isset( $params['call_text'] ) && ! empty( $params['call_text'] ) ) {
			$element['children'] .= $params['call_text'];

			unset( $params['call_text'] );
		}

		// Convert shortcode tag if needed
		if ( 'vc_cta_button2' == $element['tag'] ) {
			$element['tag'] = 'vc_cta_button';
		}

		return $element;
	}

	/**
	 * Convert `vc_video` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_video_shortcode( $element ) {
		// Shorten attributes access
		$params = & $element['attributes'];

		// Prepare `vc_video` shortcode parameters
		if ( isset( $params['link'] ) && ! empty( $params['link'] ) ) {
			if ( false !== stripos( $params['link'], 'youtube' ) ) {
				$params['video_sources'] = 'youtube';
				$params['link_youtube' ] = $params['link'];
			} elseif ( false !== stripos( $params['link'], 'vimeo' ) ) {
				$params['video_sources'] = 'vimeo';
				$params['link_vimeo'   ] = $params['link'];
			} else {
				$params['video_sources'] = 'local';
				$params['link_local'   ] = $params['link'];
			}

			unset( $params['link'] );
		}

		return $element;
	}

	/**
	 * Convert `vc_gmaps` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_gmaps_shortcode( $element ) {
		// Shorten attributes access
		$params = & $element['attributes'];

		// Prepare shortcode children
		if ( ! isset( $params['link'] ) || empty( $params['link'] ) || ! preg_match( '/^#E\-8_/', $params['link'] ) ) {
			return $element;
		}

		$element['children'] = rawurldecode( base64_decode( preg_replace( '/^#E\-8_/', '', $params['link'] ) ) );

		// Convert shortcode tag
		$element['tag'] = 'vc_column_text';

		return $element;
	}

	/**
	 * Convert `vc_raw_html` / `vc_raw_js` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_raw_html_shortcode( $element ) {
		// Prepare shortcode children
		if ( ! empty( $element['children'] ) ) {
			$element['children'] = rawurldecode( base64_decode( strip_tags( $element['children'] ) ) );
		}

		// Convert shortcode tag
		$element['tag'] = 'vc_column_text';

		return $element;
	}

	/**
	 * Convert `vc_progress_bar` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_progress_bar_shortcode( $element ) {
		// Shorten attributes access
		$params = & $element['attributes'];

		// Prepare `vc_progress_bar` shortcode parameters
		$bgcolor = $pbar_item_style = null;

		if ( isset( $params['bgcolor'] ) && ! empty( $params['bgcolor'] ) ) {
			// Define color mapping
			$mapping = array(
				'bar_blue'   => 'progress-bar-info',
				'bar_green'  => 'progress-bar-success',
				'bar_orange' => 'progress-bar-warning',
				'bar_red'    => 'progress-bar-danger',
			);

			if ( isset( $mapping[ $params['bgcolor'] ] ) ) {
				$bgcolor = $mapping[ $params['bgcolor'] ];
			} else {
				$bgcolor = 'default';
			}

			unset( $params['bgcolor'] );
		}

		if ( isset( $params['options'] ) && ! empty( $params['options'] ) ) {
			if ( in_array( 'striped', explode( ',', $params['options'] ) ) ) {
				$pbar_item_style = 'striped';
			}

			unset( $params['options'] );
		}

		// Prepare shortcode children
		if ( isset( $params['values'] ) && ! empty( $params['values'] ) ) {
			$children = explode( ',', $params['values'] );

			foreach ( $children as & $item ) {
				// Get progress bar values
				list( $progress, $text ) = explode( '|', $item, 2 );

				// Create new `wr_item_progressbar` shortcode
				$item = array(
					'tag'        => 'wr_item_progressbar',
					'attributes' => array(
						'pbar_text'       => $text,
						'pbar_percentage' => $progress,
						'pbar_item_style' => $pbar_item_style,
						'bgcolor'         => $bgcolor,
				),
				);

			}
		}

		$element['children'] = $children;

		return $element;
	}

	/**
	 * Convert `vc_pie` shortcode.
	 *
	 * @param   array  $element  Parsed shortcode data.
	 *
	 * @return  array
	 */
	public function convert_vc_pie_shortcode( $element ) {
		// Check if equivalent element available in WR PageBuilder
		global $shortcode_tags;

		if ( ! array_key_exists( 'wr_progresscircle', $shortcode_tags ) ) {
			// Equivalent element not available, keep original shortcode as is
			return $element;
		}

		// Convert shortcode tag
		$element['tag'] = 'wr_progresscircle';

		return $element;
	}
}
