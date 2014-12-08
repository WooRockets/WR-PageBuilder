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
 * Base data converter class.
 *
 * Base class for converting data from other page builder plugin to WR PageBuilder.
 *
 * @since  2.3.0
 */
class WR_Pb_Converter {
	/**
	 * An array to hold instantiated converter object.
	 *
	 * @var  array
	 */
	private static $_instance = array();

	/**
	 * Converter file name without extension.
	 *
	 * @var  string
	 */
	protected $converter = '';

	/**
	 * Pattern to match shortcode tag.
	 *
	 * @var  string
	 */
	protected static $pattern = '\[(\[?)([a-zA-Z0-9\-_]+)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';

	/**
	 * Data mapping.
	 *
	 * Must be declared in following format:
	 *
	 * $mapping = array(
	 *     'row' => array(
	 *         'tag' => wr_row',
	 *         'attributes' => array(
	 *             'width' => 'width',
	 *         ),
	 *     ),
	 *     'col' => array(
	 *         'tag' => 'wr_column',
	 *         'attributes' => array(
	 *             'weight' => 'span',
	 *         ),
	 *     ),
	 *     'widget' => array(
	 *         'tag' => 'wr_widget',
	 *         'attributes' => array(
	 *             'title' => 'el_title',
	 *             'class' => 'widget_id',
	 *         ),
	 *     ),
	 * );
	 *
	 * @var  array
	 */
	protected $mapping = array();

	/**
	 * Get an instance of specified converter class.
	 *
	 * @param   string   $converter  Converter to instantiate.
	 * @param   WP_Post  $post       WordPress's post object.
	 *
	 * @return  object
	 */
	public static function get_converter( $converter, $post ) {
		// Instantiate converter class only if not already instantiated
		if ( ! isset( self::$_instance[ $converter ] ) ) {
			// Preset variable
			self::$_instance[ $converter ] = false;

			// Check if converter class exists
			$class = explode( '-', $converter );
			$class = array_map( 'ucfirst', $class );
			$class = 'WR_Pb_Converter_' . implode( '_', $class );

			// Try to autoload converter class
			if ( ! class_exists( $class, true ) ) {
				$class = __CLASS__;
			}

			// Instantiate converter class
			self::$_instance[ $converter ] = new $class( $post );

			// Set converter name if class constructor forgot doing this
			if ( empty( self::$_instance[ $converter ]->converter ) ) {
				self::$_instance[ $converter ]->converter = $converter;
			}

			// Store post to converter object if class constructor forgot doing this
			if ( ! isset( self::$_instance[ $converter ]->post ) ) {
				self::$_instance[ $converter ]->post = $post;
			}
		}

		return self::$_instance[ $converter ];
	}

	/**
	 * Get all available data converters.
	 *
	 * @return  array
	 */
	public static function get_converters() {
		global $post;

		// Initialize WordPress Filesystem Abstraction
		$wp_filesystem = WR_Pb_Init_File_System::get_instance();

		// Get available data converter
		$files      = $wp_filesystem->dirlist( dirname( __FILE__ ) );
		$converters = array();

		foreach ( $files as $file ) {
			if ( 'converter.php' != $file['name'] ) {
				$converter = substr( $file['name'], 0, -4 );

				// Generate data converter class name
				$class = explode( '-', $converter );
				$class = array_map( 'ucfirst', $class );
				$class = 'WR_Pb_Converter_' . implode( '_', $class );

				if ( class_exists( $class, true ) ) {
					// Check if there is data to convert
					if ( call_user_func( array( $class, 'check' ), $post ) ) {
						$converters[ $converter ] = ucwords( str_replace( '-', ' ', substr( $file['name'], 0, -4 ) ) );
					}
				}
			}
		}

		// Allow 3rd-party plugin to hook into data conversion
		$converters = apply_filters( 'wr_pb_get_data_converters', $converters );

		return $converters;
	}

	/**
	 * Constructor
	 *
	 * @param   WP_Post  $post  WordPress's post object.
	 *
	 * @return  void
	 */
	public function __construct( $post ) {
		// Store post to this object
		$this->post = $post;
	}

	/**
	 * Parse data of other page builder from given post then convert to WR PageBuilder data.
	 *
	 * @return  mixed  Boolean TRUE on success, error message on failure.
	 */
	public function convert() {
		// Preset parsed data and mapping array
		$data    = array();
		$mapping = $this->mapping;

		// Trigger filters to parse other page builder data from given post and get mapping array
		$data    = apply_filters( "wr_pb_parse_data_{$this->converter}", $data   , $this );
		$mapping = apply_filters( "wr_pb_map_data_{$this->converter}"  , $mapping, $this );

		// Check if we have data to do conversion
		if ( is_string( $data ) && empty( $data ) ) {
			return sprintf(
				__( 'Not found any %s data.', WR_PBL ),
				ucwords( str_replace( '-', ' ', $this->converter ) )
			);
		}

		// If data is not affected by any filter, process normally
		if ( ! count( $data ) ) {
			// Find all shortcodes
			if ( preg_match_all( '/' . self::$pattern . '/s', $this->post->post_content, $matches, PREG_SET_ORDER ) && count( $matches ) ) {
				foreach ( $matches as $match ) {
					$data[] = $this->process_shortcode( $match );
				}
			}
		}

		// Convert parsed data to WR PageBuilder data
		$data = $this->convert_elements( $data, $mapping );

		if ( empty( $data ) ) {
			return sprintf(
				__( 'Not found any %s data.', WR_PBL ),
				ucwords( str_replace( '-', ' ', $this->converter ) )
			);
		}

		// Check if we should backup current post
		if ( isset( $_REQUEST['backup_data'] ) && $_REQUEST['backup_data'] ) {
			$backup                = (array) $this->post;
			$backup['ID'         ] = null;
			$backup['post_title' ] = "{$this->post->post_title} (backup)";
			$backup['post_status'] = 'pending';
			$backup['post_name'  ] = "{$this->post->post_name}-backup";

			// Create new post
			if ( ! ( $id = wp_insert_post( $backup ) ) ) {
				return __( 'Cannot backup current post.', WR_PBL );
			}

			// Update new post GUID
			$backup['ID'  ] = $id;
			$backup['guid'] = preg_replace( '/\?page_id=\d+/', "?page_id={$id}", $backup['guid'] );

			wp_insert_post( $backup );

			// Duplicate post meta also
			global $wpdb, $table_prefix;

			$post_meta = $wpdb->get_results( "SELECT * FROM {$table_prefix}postmeta WHERE post_id = {$this->post->ID};" );
			$ignore    = array( '_edit_last', '_edit_lock', '_post_restored_from' );

			foreach ( $post_meta as $meta ) {
				if ( ! in_array( $meta->meta_key, $ignore ) ) {
					$wpdb->query( "INSERT INTO {$table_prefix}postmeta (post_id, meta_key, meta_value) VALUES ({$id}, '{$meta->meta_key}', '{$meta->meta_value}');" );
				}
			}
		}

		// Prepare to save post with new data
		$post                 = (array) $this->post;
		$post['post_content'] = $data;

		// Check if we should unpublish current post
		if ( isset( $_REQUEST['do'] ) && 'convert-and-publish' != $_REQUEST['do'] ) {
			$post['post_status'] = 'pending';
		}

		// Update current post
		if ( ! wp_insert_post( $post ) ) {
			return __( 'Cannot update current post.', WR_PBL );
		}

		// Update post meta to activate WR PageBuilder tab
		update_post_meta( $this->post->ID, '_wr_page_active_tab', 1 );

		// Store WR PageBuilder data to post meta also
		if ( ! update_post_meta( $this->post->ID, '_wr_page_builder_content', $data ) ) {
			return __( 'Cannot store WR PageBuilder data to post meta.', WR_PBL );
		}

		// Trigger action to finalize data conversion
		do_action( "wr_pb_after_convert_{$this->converter}_data", $post, $this->post );

		return $post['ID'];
	}

	/**
	 * Process matched shortcode to array of data that is able to convert to WR PageBuilder shortcode later.
	 *
	 * @param   array  $match  Data of matched shortcode.
	 *
	 * @return  array
	 */
	protected function process_shortcode( $match ) {
		// Prepare shortcode data
		$tag      = $match[2];
		$attrs    = shortcode_parse_atts( $match[3] );
		$children = $match[5];

		if ( ! empty( $children ) ) {
			// Process all nested shortcode
			if ( preg_match_all( '/' . self::$pattern . '/s', $children, $matches, PREG_SET_ORDER ) && count( $matches ) ) {
				$children = array();

				foreach ( $matches as $match ) {
					$children[] = $this->process_shortcode( $match );
				}
			}
		}

		return array(
			'tag'        => $tag,
			'attributes' => empty( $attrs ) ? array() : $attrs,
			'children'   => $children,
		);
	}

	/**
	 * Convert parsed data of other page builder elements to WR PageBuilder elements.
	 *
	 * @param   array  $elements  Other page builder elements.
	 * @param   array  $mapping   Data mapping array.
	 *
	 * @return  string
	 */
	protected function convert_elements( $elements, $mapping ) {
		$result = '';

		if ( is_array( $elements ) && is_array( $mapping ) ) {
			// Map parsed data to WR PageBuilder data
			foreach ( $elements as $element ) {
				// Allow shortcode filterable before doing conversion
				$element = apply_filters( "wr_pb_convert_{$element['tag']}_shortcode", $element );

				// Prepare shortcode children
				$children = '';

				if ( isset( $element['children'] ) ) {
					if ( is_string( $element['children'] ) ) {
						$children = $element['children'];
					} elseif ( is_array( $element['children'] ) && count( $element['children'] ) ) {
						// Map children recursively
						$children = $this->convert_elements( $element['children'], $mapping );
					}
				}

				// Process shortcode data
				if ( isset( $mapping[ $element['tag'] ] ) && is_array( $mapping[ $element['tag'] ] ) ) {
					// Map shortcode tag
					$name = isset( $mapping[ $element['tag'] ]['tag'] ) ? $mapping[ $element['tag'] ]['tag'] : $element['tag'];

					// Map shortcode parameters
					$params = array();

					if ( isset( $mapping[ $element['tag'] ]['attributes'] ) && is_array( $mapping[ $element['tag'] ]['attributes'] ) ) {
						if ( isset( $element['attributes'] ) && is_array( $element['attributes'] ) ) {
							foreach ( $element['attributes'] as $k => $v ) {
								if ( isset( $mapping[ $element['tag'] ]['attributes'][ $k ] ) ) {
									$params[ $mapping[ $element['tag'] ]['attributes'][ $k ] ] = $v;
								}
							}
						}
					}

					// Prepare children for WR Widget shortcode
					if ( 'wr_widget' == $name ) {
						$children = $this->to_query_string( $element['attributes'], '', array( 'widget_id' ) );
					}
				} else {
					// There is no mapping available for this shortcode, wrap it inside an WR Text element
					$name = 'wr_text';

					$params = array(
						'el_title'   => isset( $element['attributes']['title'   ] ) ? $element['attributes']['title'   ] : $element['tag'],
						'css_suffix' => isset( $element['attributes']['el_class'] ) ? $element['attributes']['el_class'] : '',
					);

					// Generate nested shortcode
					$nested_shortcode = $this->to_shortcode_tag( $element['tag'], isset( $element['attributes'] ) ? $element['attributes'] : array(), $children );

					// Do nested shortcode
					$children = do_shortcode( $nested_shortcode );
					$children = trim( $children );

					if ( empty( $children ) ) {
						$children = $nested_shortcode;
					}
				}

				// Generate shortcode tag
				$result .= $this->to_shortcode_tag( $name, $params, $children );
			}
		}

		return $result;
	}

	/**
	 * Convert associative array to query string.
	 *
	 * @param   array   $params     An associative array of parameters.
	 * @param   string  $namespace  A namespace.
	 * @param   array   $exclude    Array of key should be ignored.
	 *
	 * @return  string
	 */
	protected function to_query_string( $params, $namespace = '', $exclude = array() ) {
		// Preset array of name/value pairs
		$pairs = array();

		if ( ! is_array( $params ) ) {
			return;
		}

		foreach ( $params as $k => $v ) {
			if ( in_array( $k, $exclude ) ) {
				continue;
			}

			// Generate variable name
			$name = empty( $namespace ) ? $k : "{$namespace}[{$k}]";

			// Generate name/value pair
			if ( is_array( $v ) ) {
				$pairs[] = $this->to_query_string( $v, $name );
			} else {
				$pairs[] = "{$name}={$v}";
			}
		}

		return implode( '&', $pairs );
	}

	/**
	 * Generate shortcode tag.
	 *
	 * @param   string  $name    Shortcode tag name.
	 * @param   array   $params  Shortcode parameters.
	 * @param   string  $child   Shortcode children.
	 *
	 * @return  string
	 */
	protected function to_shortcode_tag( $name, $params = array(), $child = '' ) {
		// Generate shortcode tag
		$shortcode = '[' . esc_attr( $name );

		// Generate shortcode parameters
		foreach ( $params as $k => $v ) {
			if ( is_string( $v ) && ! empty( $v ) ) {
				$shortcode .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
			}
		}

		// Finalize shortcode tag
		$shortcode .= ']' . $child . '[/' . $name . ']';

		return $shortcode;
	}
}
