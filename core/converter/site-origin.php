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
 * Site Origin data converter class.
 *
 * @since  2.3.0
 */
class WR_Pb_Converter_Site_Origin extends WR_Pb_Converter {
	/**
	 * Converter file name without extension.
	 *
	 * @var  string
	 */
	protected $converter = 'site-origin';

	/**
	 * Data mapping.
	 *
	 * @var  array
	 */
	protected $mapping = array(
		'wr_row' => array(),

		'wr_column' => array(
			'attributes' => array(
				'weight' => 'span',
			),
		),

		'wr_widget' => array(
			'attributes' => array(
				'title' => 'el_title',
				'class' => 'widget_id',
			),
		),

		'wr_text' => array(),

		'wr_image' => array(
			'attributes' => array(
				'link_type' => 'link_type',
				'href'      => 'image_type_url',
				'src'       => 'image_file',
				'image'     => 'image_file',
				'animation' => 'image_effect',
			),
		),

		'wr_contentclips' => array(
			'attributes' => array(
				'title'          => 'el_title',
				'post_type'      => 'wr_cl_source',
				'posts_per_page' => 'wr_cl_limit',
				'orderby'        => 'wr_cl_orderby',
				'order'          => 'wr_cl_order',
			),
		),

		'SiteOrigin_Panels_Widgets_EmbeddedVideo' => array(
			'tag'        => 'wr_video',
			'attributes' => array(
				'video' => 'video_source_local',
			),
		),

		'SiteOrigin_Panels_Widgets_Video' => array(
			'tag'        => 'wr_video',
			'attributes' => array(
				'url' => 'video_source_local',
			),
		),

		'wr_buttonbar' => array(
			'attributes' => array(
				'align' => 'buttonbar_alignment',
			),
		),

		'wr_item_buttonbar' => array(
			'attributes' => array(
				'link_type'  => 'link_type',
				'text'       => 'button_text',
				'url'        => 'button_type_url',
				'new_window' => 'open_in',
			),
		),

		'wr_promobox' => array(
			'attributes' => array(
				'link_type'         => 'link_type',
				'title'             => 'pb_title',
				'button_text'       => 'pb_button_title',
				'button_url'        => 'pb_button_url',
				'button_new_window' => 'pb_button_open_in',
			),
		),

		'wr_list' => array(
			'attributes' => array(
				'title'     => 'el_title',
				'show_icon' => 'show_icon',
			),
		),

		'wr_item_list' => array(),

		'wr_testimonial' => array(),

		'wr_item_testimonial' => array(
			'attributes' => array(
				'name'  => 'name',
				'image' => 'image_file',
			),
		),
	);

	/**
	 * Define WordPress's built-in widgets.
	 *
	 * @var  array
	 */
	protected static $wp_widgets = array(
		'WP_Widget_Search',
		'WP_Widget_Meta',
		'WP_Widget_Recent_Comments',
		'WP_Widget_Calendar',
		'WP_Widget_Pages',
		'WP_Widget_Tag_Cloud',
		'WP_Nav_Menu_Widget',
		'WP_Widget_Text',
		'WP_Widget_Recent_Posts',
		'WP_Widget_Links',
		'WP_Widget_Categories',
		'WP_Widget_Archives',
		'WP_Widget_RSS',
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

		// Register filter to parse Site Origin data
		add_filter( "wr_pb_parse_data_{$this->converter}", array( &$this, 'parse_site_origin_data' ) );

		// Register action to finalize data conversion
		add_action( "wr_pb_after_convert_{$this->converter}_data", array( &$this, 'finalize' ) );
	}

	/**
	 * Check if there is Site Origin data to convert.
	 *
	 * @param   WP_Post  $post  WordPress's post object.
	 *
	 * @return  mixed
	 */
	public static function check( $post ) {
		// Get Site Origin data
		$panels_data = get_post_meta( $post->ID, 'panels_data', true );

		if ( ! is_array( $panels_data ) || ! count( $panels_data ) ) {
			return false;
		}

		return $panels_data;
	}

	/**
	 * Parse Site Origin data from given post then convert to WR PageBuilder data.
	 *
	 * @param   array  $data  Current parsed data.
	 *
	 * @return  array
	 */
	public function parse_site_origin_data( $data = array() ) {
		// Get Site Origin data from given post
		if ( ! ( $panels_data = self::check( $this->post ) ) ) {
			return '';
		}

		// Parse layout structure first
		foreach ( $panels_data['grid_cells'] as $k => $cell ) {
			$grid = (int) $cell['grid'];

			// Define grid if not declared before
			if ( ! isset( $data[ $grid ]) ) {
				$data[ $grid ] = array(
					'tag'        => 'wr_row',
					'attributes' => array(),
					'children'   => array(),
				);
			}

			$data[ $grid ]['children'][] = array(
				'tag'        => 'wr_column',
				'attributes' => array( 'weight' => 'span' . round( $cell['weight'] * 12 ) ),
				'children'   => array(),
			);
		}

		// Then parse widgets data
		foreach ( $panels_data['widgets'] as $widget ) {
			// Get widget position in layout structure
			$grid = (int) $widget['info']['grid'];
			$cell = (int) $widget['info']['cell'];

			// Get widget class
			$class = $widget['info']['class'];

			// Generate method name to check for
			$method = 'parse_' . strtolower( $class );

			// Unset info no longer needed
			unset( $widget['info'] );

			// Store widget data
			if ( method_exists( $this, $method ) ) {
				$data[ $grid ]['children'][ $cell ]['children'][] = call_user_func( array( &$this, $method ), $widget, $class );
			} else {
				$data[ $grid ]['children'][ $cell ]['children'][] = $this->parse_widget( $widget, $class );
			}
		}

		return $data;
	}

	/**
	 * Finalize data conversion.
	 *
	 * @param   array  $new_post  Array of new post data.
	 *
	 * @return  void
	 */
	public function finalize( $new_post ) {
		// Delete Site Origin post meta field
		global $wpdb, $table_prefix;

		$wpdb->query( "DELETE FROM {$table_prefix}postmeta WHERE post_id = {$new_post['ID']} AND meta_key = 'panels_data';" );
	}

	/**
	 * Parse widget.
	 *
	 * @param   array   $widget  Widget data.
	 * @param   string  $class   Name of widget class.
	 *
	 * @return  array
	 */
	protected function parse_widget( $widget, $class = '' ) {
		// Check if mapping defined for this widget class
		if ( array_key_exists( $class, $this->mapping ) ) {
			return array(
				'tag'        => $class,
				'attributes' => $widget,
			);
		} else {
			// Generate query string for widget parameters
			$query_string = $this->to_query_string( $widget, '', array( 'info' ) );

			// Render widget
			ob_start();

			the_widget( $class, $query_string );

			$html = ob_get_clean();
			$html = trim( $html );

			if ( in_array( $class, self::$wp_widgets ) || empty( $html ) ) {
				return array(
					'tag'        => 'wr_widget',
					'attributes' => array( 'class' => $class ),
					'children'   => $query_string,
				);
			} else {
				return array(
					'tag'        => 'wr_text',
					'children'   => $html,
				);
			}
		}
	}

	/**
	 * Parse `SiteOrigin_Panels_Widgets_Gallery` widget.
	 *
	 * @param   array   $widget  Widget data.
	 * @param   string  $class   Name of widget class.
	 *
	 * @return  array
	 */
	protected function parse_siteorigin_panels_widgets_gallery( $widget, $class = '' ) {
		// Generate `gallery` shortcode tag
		$shortcode = $this->to_shortcode_tag( 'gallery', $widget );

		return array(
			'tag'      => 'wr_text',
			'children' => $shortcode,
		);
	}

	/**
	 * Parse `SiteOrigin_Panels_Widgets_PostContent` widget.
	 *
	 * @param   array   $widget  Widget data.
	 * @param   string  $class   Name of widget class.
	 *
	 * @return  array
	 */
	protected function parse_siteorigin_panels_widgets_postcontent( $widget, $class = '' ) {
		// Get post content
		$content = '';

		switch ( $widget['type'] ) {
			case 'title' :
				$content = $this->post->post_title;
				break;

			case 'content' :
				$content = wpautop( $this->post->post_content );
				break;

			case 'featured' :
				if ( has_post_thumbnail( $this->post->ID ) ) {
					$content = get_the_post_thumbnail( $this->post->ID );
				}
				break;
		}

		return array(
			'tag'      => 'wr_text',
			'children' => $content,
		);
	}

	/**
	 * Parse `SiteOrigin_Panels_Widgets_Image` widget.
	 *
	 * @param   array   $widget  Widget data.
	 * @param   string  $class   Name of widget class.
	 *
	 * @return  array
	 */
	protected function parse_siteorigin_panels_widgets_image( $widget, $class = '' ) {
		// Prepare shortcode parameters
		if ( isset( $widget['href'] ) && ! empty( $widget['href'] ) ) {
			$widget['link_type'] = 'url';
		}

		return array(
			'tag'        => 'wr_image',
			'attributes' => $widget,
		);
	}

	/**
	 * Parse `SiteOrigin_Panels_Widgets_PostLoop` widget.
	 *
	 * @param   array   $widget  Widget data.
	 * @param   string  $class   Name of widget class.
	 *
	 * @return  array
	 */
	protected function parse_siteorigin_panels_widgets_postloop( $widget, $class = '' ) {
		// Check if equivalent element available in WR PageBuilder
		global $shortcode_tags;

		if ( ! array_key_exists( 'wr_contentclips', $shortcode_tags ) ) {
			// Equivalent element not available, embed original widget directly
			return $this->parse_widget( $widget, $class );
		}

		return array(
			'tag'        => 'wr_contentclips',
			'attributes' => $widget,
		);
	}

	/**
	 * Parse `SiteOrigin_Panels_Widget_Animated_Image` widget.
	 *
	 * @param   array   $widget  Widget data.
	 * @param   string  $class   Name of widget class.
	 *
	 * @return  array
	 */
	protected function parse_siteorigin_panels_widget_animated_image( $widget, $class = '' ) {
		// Prepare shortcode parameters
		$widget['animation'] = 'yes';

		return array(
			'tag'        => 'wr_image',
			'attributes' => $widget,
		);
	}

	/**
	 * Parse `SiteOrigin_Panels_Widget_Button` widget.
	 *
	 * @param   array   $widget  Widget data.
	 * @param   string  $class   Name of widget class.
	 *
	 * @return  array
	 */
	protected function parse_siteorigin_panels_widget_button( $widget, $class = '' ) {
		// Prepare shortcode children
		$widget['link_type'] = 'url';

		if ( (int) $widget['new_window'] ) {
			$widget['new_window'] = 'new_browser';
		} else {
			$widget['new_window'] = 'current_browser';
		}

		$children = array(
		array(
				'tag'        => 'wr_item_buttonbar',
				'attributes' => $widget,
		),
		);

		// Prepare shortcode parameters
		if ( 'justify' == $widget['align'] ) {
			$widget['align'] = 'inherit';
		}

		return array(
			'tag'        => 'wr_buttonbar',
			'attributes' => $widget,
			'children'   => $children,
		);
	}

	/**
	 * Parse `SiteOrigin_Panels_Widget_Call_To_Action` widget.
	 *
	 * @param   array   $widget  Widget data.
	 * @param   string  $class   Name of widget class.
	 *
	 * @return  array
	 */
	protected function parse_siteorigin_panels_widget_call_to_action( $widget, $class = '' ) {
		// Prepare shortcode children
		$children = $widget['subtitle'];

		// Prepare shortcode parameters
		$widget['link_type'] = 'url';

		if ( (int) $widget['button_new_window'] ) {
			$widget['button_new_window'] = 'new_browser';
		} else {
			$widget['button_new_window'] = 'current_browser';
		}

		return array(
			'tag'        => 'wr_promobox',
			'attributes' => $widget,
			'children'   => $children,
		);
	}

	/**
	 * Parse `SiteOrigin_Panels_Widget_List` widget.
	 *
	 * @param   array   $widget  Widget data.
	 * @param   string  $class   Name of widget class.
	 *
	 * @return  array
	 */
	protected function parse_siteorigin_panels_widget_list( $widget, $class = '' ) {
		// Get list items
		$children = array( $widget['text'] );

		if ( false !== strpos( $widget['text'], '*' ) ) {
			$children = explode( '*', $widget['text'] );
		}

		// Prepare shortcode children
		foreach ( $children as $k => & $child ) {
			$child = trim( $child );

			// Check if list item is empty
			if ( empty( $child ) ) {
				unset( $children[ $k ] );

				continue;
			}

			// Prepare list item
			$title = $child;
			$text  = '';

			if ( false !== strpos( $child, "\r" ) || false !== strpos( $child, "\n" ) ) {
				list( $title, $text ) = preg_split( '/[\r\n]+/', $child, 2 );
			}

			$child = array(
				'tag'        => 'wr_item_list',
				'attributes' => array( 'heading' => $title ),
				'children'   => $text,
			);
		}

		// Prepare shortcode parameters
		$widget['show_icon'] = 'no';

		return array(
			'tag'        => 'wr_list',
			'attributes' => $widget,
			'children'   => $children,
		);
	}

	/**
	 * Parse `SiteOrigin_Panels_Widget_Price_Box` widget.
	 *
	 * @param   array   $widget  Widget data.
	 * @param   string  $class   Name of widget class.
	 *
	 * @return  array
	 */
	protected function parse_siteorigin_panels_widget_price_box( $widget, $class = '' ) {
		// Process features text
		$list     = array( 'text' => $widget['features'] );
		$features = array( $this->parse_siteorigin_panels_widget_list( $list ) );

		// Prepare shortcode children
		$widget['subtitle']  = '<h4>' . esc_html( $widget['price'] ) . '<span> /' . esc_html( $widget['per'] ) . '</span></h4>';
		$widget['subtitle'] .= '<p class="information">' . wp_kses_post( $widget['information'] ) . '</p>';
		$widget['subtitle'] .= $this->convert_elements( $features, $this->mapping );

		return $this->parse_siteorigin_panels_widget_call_to_action( $widget );
	}

	/**
	 * Parse `SiteOrigin_Panels_Widget_Testimonial` widget.
	 *
	 * @param   array   $widget  Widget data.
	 * @param   string  $class   Name of widget class.
	 *
	 * @return  array
	 */
	protected function parse_siteorigin_panels_widget_testimonial( $widget, $class = '' ) {
		// Check if equivalent element available in WR PageBuilder
		global $shortcode_tags;

		if ( ! array_key_exists( 'wr_testimonial', $shortcode_tags ) ) {
			// Equivalent element not available, embed original widget directly
			return $this->parse_widget( $widget, $class );
		}

		// Prepare shortcode children
		/*if ( $widget['url'] ) {
		$widget['name'] = '<a href="' . esc_url( $widget['url'] ) . '"' . ( (int) $widget['new_window'] ? ' target="_blank"' : '' ) . '>' . $widget['name'] . '</a>';
		}*/

		if ( $widget['location'] ) {
			$widget['name'] .= ', ' . $widget['location'];
		}

		$children = array(
		array(
				'tag'        => 'wr_item_testimonial',
				'attributes' => $widget,
				'children'   => $widget['text'],
		),
		);

		return array(
			'tag'      => 'wr_testimonial',
			'children' => $children,
		);
	}
}
