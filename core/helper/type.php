<?php

/**
 * @version    $Id$
 * @package    WR PageBuilder
 * @author     WooRockets Team <support@www.woorockets.com>
 * @copyright  Copyright (C) 2012 www.woorockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.woorockets.com
 * Technical Support:  Feedback - http://www.www.woorockets.com
 */

/**
 * @todo : Defines values of setting options
 */

if ( ! class_exists( 'WR_Pb_Helper_Type' ) ) {

	class WR_Pb_Helper_Type {

		/**
		 ** Google map type options
		 *
		 * @return array
		 */
		static function get_gmap_type() {
			return array(
				'HYBRID'    => __( 'Hybrid', WR_PBL ),
				'ROADMAP'   => __( 'Roadmap', WR_PBL ),
				'SATELLIGE' => __( 'Satellite', WR_PBL ),
				'TERRAIN'   => __( 'Terrain', WR_PBL ),
			);
		}

		/**
		 ** Zoom level options for google element
		 *
		 * @return array
		 */
		static function get_zoom_level() {
			return array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',
				'10' => '10',
				'11' => '11',
				'12' => '12',
				'13' => '13',
				'14' => '14',
			);
		}

		/**
		 * Container style options
		 *
		 * @return array
		 */
		static function get_container_style() {
			return array(
				'no-styling'    => __( 'No Styling', WR_PBL ),
				'img-rounded'   => __( 'Rounded', WR_PBL ),
				'img-circle'    => __( 'Circle', WR_PBL ),
				'img-thumbnail' => __( 'Thumbnail', WR_PBL )
			);
		}

		/**
		 ** Zoom level options for QRCode element
		 *
		 * @return array
		 */
		static function get_qr_container_style() {
			return array(
				'no-styling'    => __( 'No Styling', WR_PBL ),
				'img-thumbnail' => __( 'Thumbnail', WR_PBL )
			);
		}

		/**
		 * * Table design options
		 *
		 * @return array
		 */
		static function get_table_row_color() {
			return array(
				'default' => __( 'Default', WR_PBL ),
				'active'  => __( 'Active (Grey)', WR_PBL ),
				'success' => __( 'Success (Green)', WR_PBL ),
				'warning' => __( 'Warning (Orange)', WR_PBL ),
				'danger'  => __( 'Danger (Red)', WR_PBL ),
			);
		}

		/**
		 ** alert type options
		 *
		 * @return array
		 */
		static function get_alert_type() {
			return array(
				'alert-warning' => __( 'Warning', WR_PBL ),
				'alert-success' => __( 'Success', WR_PBL ),
				'alert-info'    => __( 'Info', WR_PBL ),
				'alert-danger'  => __( 'Danger', WR_PBL ),
			);
		}

		/**
		 ** progress bar color options
		 *
		 * @return array
		 */
		static function get_progress_bar_color() {
			return array(
				'progress-bar-primary' => __( 'Primary (Blue)', WR_PBL ),
				'progress-bar-info'    => __( 'Info (Light Blue)', WR_PBL ),
				'progress-bar-success' => __( 'Success (Green)', WR_PBL ),
				'progress-bar-warning' => __( 'Warning (Orange)', WR_PBL ),
				'progress-bar-danger'  => __( 'Danger (Red)', WR_PBL ),
			);
		}

		/**
		 ** progress bar style options
		 *
		 * @return array
		 */
		static function get_progress_bar_style() {
			return array(
				'multiple-bars' => __( 'Multiple bars', WR_PBL ),
				'stacked' 		=> __( 'Stacked', WR_PBL ),
			);
		}

		/**
		 ** progress bar item options
		 *
		 * @return array
		 */
		static function get_progress_bar_item_style() {
			return array(
				'solid'   => __( 'Solid', WR_PBL ),
				'striped' => __( 'Striped', WR_PBL ),
			);
		}

		/**
		 * Static function to get button color Options
		 *
		 * @return array
		 */
		static function get_button_color() {
			return array(
				'btn-default' => __( 'Default', WR_PBL ),
				'btn-primary' => __( 'Primary (Dark Blue)', WR_PBL ),
				'btn-info'    => __( 'Info (Light Blue)', WR_PBL ),
				'btn-success' => __( 'Success (Green)', WR_PBL ),
				'btn-warning' => __( 'Warning (Orange)', WR_PBL ),
				'btn-danger'  => __( 'Danger (Red)', WR_PBL ),
				'btn-link'    => __( 'Link', WR_PBL )
			);
		}

		/**
		 * Button size options
		 *
		 * @return array
		 */
		static function get_button_size() {
			return array(
				'default' => __( 'Default', WR_PBL ),
				'btn-xs'  => __( 'Mini', WR_PBL ),
				'btn-sm'  => __( 'Small', WR_PBL ),
				'btn-lg'  => __( 'Large', WR_PBL ),
				'custom'  => __( 'Custom', WR_PBL ),
			);
		}

		/**
		 * "Open in" option for anchor
		 *
		 * @return array
		 */
		static function get_open_in_options() {
			return array(
				'current_browser' => __( 'Current Tab', WR_PBL ),
				'new_browser' 	  => __( 'New Tab', WR_PBL ),
				'new_window' 	  => __( 'New Window', WR_PBL ),
				'lightbox' 		  => __( 'Lightbox', WR_PBL ),
			);
		}

		/**
		 * Icon position for List shortcode
		 *
		 * @return array
		 */
		static function get_icon_position() {
			return array(
				'left'    => __( 'Left', WR_PBL ),
				'right'   => __( 'Right', WR_PBL ),
				'center'   => __( 'Center', WR_PBL ),
			);
		}

		/**
		 * Position options
		 *
		 * @return array
		 */
		static function get_full_positions() {
			return array(
				'top'    => __( 'Top', WR_PBL ),
				'bottom' => __( 'Bottom', WR_PBL ),
				'left'   => __( 'Left', WR_PBL ),
				'right'  => __( 'Right', WR_PBL ),
			);
		}

		/**
		 * Icon size options
		 *
		 * @return array
		 */
		static function get_icon_sizes() {
			return array(
				'16' => '16',
				'24' => '24',
				'32' => '32',
				'48' => '48',
				'64' => '64',
			);
		}

		/**
		 * Icon style for List shortcode
		 *
		 * @return array
		 */
		static function get_icon_background() {
			return array(
				'circle' => __( 'Circle', WR_PBL ),
				'square' => __( 'Square', WR_PBL )
			);
		}

		/**
		 * Font options
		 *
		 * @return array
		 */
		static function get_fonts() {
			return array(
				'standard fonts' => __( 'Standard fonts', WR_PBL ),
				'google fonts'   => __( 'Google fonts', WR_PBL )
			);
		}

		/**
		 * Text align options
		 *
		 * @return array
		 */
		static function get_text_align() {
			return array(
				'inherit' => __( 'Inherit', WR_PBL ),
				'left'    => '<i class="wr-icon-align-left" title="' . __( 'Left', WR_PBL ) . '"></i>',
				'center'  => '<i class="wr-icon-align-center" title="' . __( 'Center', WR_PBL ) . '"></i>',
				'right'   => '<i class="wr-icon-align-right" title="' . __( 'Right', WR_PBL ) . '"></i>'
			);
		}

		/**
		 * Google map align options
		 *
		 * @return array
		 */
		static function get_map_align() {
			return array(
				'no'      => __( 'No Alignment', WR_PBL ),
				'left'    => '<i class="wr-icon-align-left" title="' . __( 'Left', WR_PBL ) . '"></i>',
				'center'  => '<i class="wr-icon-align-center" title="' . __( 'Center', WR_PBL ) . '"></i>',
				'right'   => '<i class="wr-icon-align-right" title="' . __( 'Right', WR_PBL ) . '"></i>'
			);
		}

		/**
		 * Font size options
		 *
		 * @return array
		 */
		static function get_font_size_types() {
			return array(
				'px'   => 'px',
				'em'   => 'em',
				'inch' => 'inch',
			);
		}

		/**
		 * Border style options
		 *
		 * @return array
		 */
		static function get_border_styles() {
			return array(
				'solid'  => __( 'Solid', WR_PBL ),
				'dotted' => __( 'Dotted', WR_PBL ),
				'dashed' => __( 'Dashed', WR_PBL ),
				'double' => __( 'Double', WR_PBL ),
				'groove' => __( 'Groove', WR_PBL ),
				'ridge'  => __( 'Ridge', WR_PBL ),
				'inset'  => __( 'Inset', WR_PBL ),
				'outset' => __( 'Outset', WR_PBL )
			);
		}

		/**
		 * Font style options
		 *
		 * @return array
		 */
		static function get_font_styles() {
			return array(
				'inherit' => __( 'Inherit', WR_PBL ),
				'italic'  => __( 'Italic', WR_PBL ),
				'normal'  => __( 'Normal', WR_PBL ),
				'bold'    => __( 'Bold', WR_PBL )
			);
		}

		/**
		 * Dummy content
		 *
		 * @return array
		 */
		static function lorem_text( $word_count = 50 ) {
			return ucfirst( WR_Pb_Utils_Common::lorem_text( $word_count, true ) );
		}

		/**
		 * Dummy person name
		 *
		 * @return array
		 */
		static function lorem_name() {
			$usernames = array( 'Jane Poe', 'Robert Roe', 'Mark Moe', 'Brett Boe', 'Carla Coe', 'Donna Doe', 'Juan Doe', 'Frank Foe', 'Grace Goe', 'Harry Hoe', 'Jackie Joe', 'Karren Koe', 'Larry Loe', 'Marta Moe', 'Norma Noe', 'Paula Poe', 'Quintin Qoe', 'Ralph Roe', 'Sammy Soe', 'Tommy Toe', 'Vince Voe', 'William Woe', 'Xerxes Xoe', 'Yvonne Yoe', 'Zachary Zoe', 'John Smith', 'Udin Sedunia', 'Mubarok Bau' );
			$index     = rand( 0, 27 );
			return $usernames[$index];
		}

		/**
		 * Get 1st option of array
		 *
		 * @param array $arr
		 * @return array
		 */
		static function get_first_option( $arr ) {
			foreach ( $arr as $key => $value ) {
				if ( ! is_array( $key ) )
				return $key;
			}
		}

		/**
		 * Method to get appearing animations
		 * @param $id DOM Id of genereted select box
		 * @return array Structure of Animations select box
		 */
		static function get_apprearing_animations( $id = 'appearing_animation' )
		{
			return array(
					'name'    => __( 'Appearing Animation', WR_PBL ),
					'id'      => $id,
					'type'    => 'select',
					'class'   => 'input-sm',
					'options' => array(
									'0'    => __( 'None', WR_PBL ),
									'slide_from_top'    => __( 'Slide in from Top', WR_PBL ),
									'slide_from_right'  => __( 'Slide in from Right', WR_PBL ),
									'slide_from_bottom' => __( 'Slide in from Bottom', WR_PBL ),
									'slide_from_left'   => __( 'Slide in from Left', WR_PBL ),
									'fade_in'   => __( 'Fade in', WR_PBL ),
								),
					'std'     => '0',
					'has_depend' => '1',
				);
		}

		/**
		 * Method to get Aminmation speeds list
		 * @param $id DOM ID of genereted select box
		 * @param $animations_select_id DOM ID of Animations select box
		 * @return array Structure of Speeds select box
		 */
		static function get_animation_speeds( $id = 'appearing_animation_speed', $animations_select_id = 'appearing_animation' )
		{
			return array(
					'name'    => __( 'Appearing Animation Speed', WR_PBL ),
					'id'      => $id,
					'dependency'      => array( $animations_select_id, '!=', '0' ),
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => 'Medium',
					'options' => array( 'Slow' =>  __( 'Slow', WR_PBL ),
										'Medium' =>  __( 'Medium', WR_PBL ) ,
										'Fast' =>  __( 'Fast', WR_PBL ),
									),

				);

		}
		/**
		 * Static function to get category options
		 *
		 * @param bool $has_root
		 *
		 * @return array
		 */
		static function get_categories( $has_root = false ) {
			$categories = get_categories();
			$arr_return = array();
			$return     = array();
			if ( $categories ) {
				if ( $has_root )
				$return[] = __( 'Root', WR_PBL );
				foreach ( $categories as $i => $category ) {
					$arr_return[] = array( 'id' => $category->term_id, 'parent' => $category->parent, 'title' => $category->name );
				}
				$level = 0;
				foreach ( $arr_return as $i => $item ) {
					if ( $item['parent'] == 0 ) {
						$id = $item['id'];
						unset( $arr_return[$i] );
						if ( ! isset( $item['title'] ) OR ! $item['title'] ) {
							$item['title'] = __( '( no title )', WR_PBL );
						}
						$return[$item['id']] = $item['title'];
						self::_recur_tree( $return, $arr_return, $id, $level );
					}
				}
			}
			return $return;
		}

		/**
		 * Posts list
		 *
		 * @global type $wpdb
		 * @return array
		 */
		static function get_posts() {
			global $wpdb;
			$numposts = $wpdb->get_var( "SELECT COUNT(* ) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post'" );
			if ( 0 < $numposts )
			$numposts = number_format( $numposts );
			$posts = get_posts( array( 'posts_per_page' => $numposts ) );
			$arr_return = array();
			$return     = array();
			if ( $posts ) {
				foreach ( $posts as $i => $post ) {
					$arr_return[] = array( 'id' => $post->ID, 'parent' => $post->post_parent, 'title' => $post->post_title );
				}

				foreach ( $arr_return as $i => $item ) {
					$return[$item['id']] = ( strlen( $item['title'] ) > 30 ) ? substr( $item['title'], 0, strrpos( substr( $item['title'], 0, 30 ), ' ' ) ) . '...' : $item['title'];
				}
			}
			return $return;
		}

		/**
		 * Pages list
		 *
		 * @param type $has_root
		 * @return array
		 */
		static function get_pages( $has_root = false ) {
			$pages = get_pages();
			$arr_return = array();
			$return     = array();
			if ( $pages ) {
				if ( $has_root )
				$return[] = __( 'Root', WR_PBL );
				foreach ( $pages as $i => $page ) {
					$arr_return[] = array( 'id' => $page->ID, 'parent' => $page->post_parent, 'title' => $page->post_title );
				}

				$level = 0;
				foreach ( $arr_return as $i => $item ) {
					if ( $item['parent'] == 0 ) {
						$id = $item['id'];
						unset( $arr_return[$i] );
						$return[$item['id']] = $item['title'];
						self::_recur_tree( $return, $arr_return, $id, $level );
					}
				}
			}

			return $return;
		}

		/**
		 * listing tree type using recursive
		 *
		 * @param array $return
		 * @param array $arr_return
		 * @param int $id
		 * @param int $level
		 * @param string $type
		 * @param string $prefix
		 */
		private static function _recur_tree( &$return, $arr_return, $id, $level, $type = '0', $prefix = '' ) {
			if ( $type == '0' ) {
				$level++;
				foreach ( $arr_return as $i => $item ) {
					if ( $item['parent'] == $id ) {
						unset( $arr_return[$i] );
						if ( ! isset( $item['title'] ) OR ! $item['title'] ) {
							$item['title'] = __( '( no title )', WR_PBL );
						}
						$return[$item['id']] = str_repeat( '&#8212; ', $level ) . $item['title'];
						self::_recur_tree( $return, $arr_return, $item['id'], $level, $type );
					}
				}
			} else if ( $type == '1' ) {
				$level++;
				foreach ( $arr_return as $i => $item ) {
					if ( $item->parent == $id ) {
						unset( $arr_return[$i] );
						if ( ! $item->name ) {
							$item->name = __( '( no name )', WR_PBL );
						}
						$return[$item->term_id] = str_repeat( '&#8212; ', $level ) . $item->name;
						self::_recur_tree( $return, $arr_return, $item->term_id, $level, $type );
					}
				}
			} else if ( $type == '-1' ) {
				$level++;
				foreach ( $arr_return as $i => $item ) {
					if ( $item->post_parent == $id ) {
						unset( $arr_return[$i] );
						if ( ! $item->post_title ) {
							$item->post_title = __( '( no title )', WR_PBL );
						}
						$return[$item->ID] = str_repeat( '&#8212; ', $level ) . $item->post_title;
						self::_recur_tree( $return, $arr_return, $item->ID, $level, $type );
					}
				}
			} else if ( $type == '-2' ) {
				$level++;
				foreach ( $arr_return as $i => $item ) {
					if ( $item->menu_item_parent == $id ) {
						unset( $arr_return[$i] );
						if ( ! $item->title ) {
							$item->title = __( '( no title )', WR_PBL );
						}
						if ( $prefix ) {
							$return[$prefix][$item->ID] = str_repeat( '&#8212; ', $level ) . $item->title;
							self::_recur_tree( $return, $arr_return, $item->ID, $level, $type, $prefix );
						}
					}
				}
			}
			return;
		}

		/**
		 * link type options
		 *
		 * @return multitype:
		 */
		static function get_link_types() {
			$taxonomies = self::get_public_taxonomies();
			$post_types = self::get_post_types();
			$arr = array(
				'no_link' => __( 'No Action', WR_PBL ),
				'url' => __( 'Go to URL', WR_PBL ),
				'single_entry' => array( 'text' => __( 'Go to Single Entry', WR_PBL ), 'type' => 'optiongroup' ),
			);
			$arr = array_merge( $arr, $post_types );
			$arr = array_merge( $arr, array( 'taxonomy' => array( 'text' => __( 'Go to Taxonomy Overview Page', WR_PBL ), 'type' => 'optiongroup' ) ) );
			$arr = array_merge( $arr, $taxonomies );
			return $arr;
		}

		/**
		 * Get single entry list: Post, Page, Product...
		 *
		 * @return array
		 */
		static function get_single_entry() {
			$post_types = self::get_post_types();
			return $post_types;
		}

		/**
		 ** content source options
		 *
		 * @return array
		 */
		static function get_content_source() {
			$taxonomies = self::get_public_taxonomies();
			$post_types = self::get_post_types();
			$arr = array(
				'nav_menu_item' => __( 'Menu', WR_PBL ),
				'single_entry' => array( 'text' => __( 'Single Entries', WR_PBL ), 'type' => 'optiongroup' ),
			);
			$arr = array_merge( $arr, $post_types );
			$arr = array_merge( $arr, array( 'taxonomy' => array( 'text' => __( 'Taxonomy Overview Page', WR_PBL ), 'type' => 'optiongroup' ) ) );
			$arr = array_merge( $arr, $taxonomies );
			return $arr;
		}

		/**
		 ** image link type options
		 *
		 * @return array
		 */
		static function get_image_link_types() {
			$imageLinkType = array();
			$linkTypes = self::get_link_types();
			$imageLinkType = array_slice( $linkTypes, 0, 1 );
			$imageLinkType['large_image'] = __( 'Show Large Image', WR_PBL );
			$imageLinkType = array_merge( $imageLinkType, array_slice( $linkTypes, 1 ) );
			return $imageLinkType;
		}

		/**
		 ** terms by taxonomies
		 *
		 * @param string $taxonomy
		 * @param string $allow_root
		 * @param string $order_by
		 *
		 * @return array
		 */
		static function get_term_taxonomies( $taxonomy = '', $allow_root = false, $order_by = 'count' ) {
			$taxonomies = self::get_public_taxonomies();
			$term_taxos = array();
			foreach ( $taxonomies as $taxo_slug => $taxo_name ) {
				if ( ! isset( $term_taxos[$taxo_slug] ) )
				$term_taxos[$taxo_slug] = array();
				if ( $allow_root ) {
					$exclude_taxo = self::_get_exclude_taxonomies();
					if ( in_array( $taxo_slug , $exclude_taxo ) ) {
						$term_taxos[$taxo_slug]['root'] = __( 'Root', WR_PBL );
					}
				}
				$terms = get_terms(
				$taxo_slug, array(
						'orderby' => $order_by,
						'hide_empty' => 0,
				)
				);

				if ( $order_by == 'name' ) {
					$return     = array();
					$level = 0;
					$arr_return = $terms;
					foreach ( $arr_return as $i => $item ) {
						if ( $item->parent == 0 ) {
							unset( $arr_return[$i] );
							if ( ! $item->name ) {
								$item->name = __( '( no name )', WR_PBL );
							}
							$return[$item->term_id] = $item->name;
							self::_recur_tree( $return, $arr_return, $item->term_id, $level, '1' );
						}
					}
					foreach ( $return as $id => $name ) {
						foreach ( $terms as $term ) {
							if ( $id == $term->term_id ) {
								$term_taxos[$taxo_slug][$term->term_id] = __( $name, WR_PBL );
							}
						}
					}
				} else {
					foreach ( $terms as $term ) {
						$term_taxos[$taxo_slug][$term->term_id] = __( $term->name, WR_PBL );
					}
				}
			}
			if ( $taxonomy )
			return $term_taxos[$taxonomy];
			return $term_taxos;
		}

		/**
		 * Static function get categories for Content Clips shortcode
		 *
		 * @param string $check_val
		 * @param array $attrs
		 *
		 * @return array
		 */
		static function get_categories_content_clips( $check_val, $attrs ) {
			$term_taxos = self::get_term_taxonomies();
			$result     = array();
			foreach ( $term_taxos as $taxo => $terms ) {
				$tmp_arr = array();
				$tmp_arr['options']    = $terms;
				$tmp_arr['dependency'] = array( $check_val, '=', $taxo );
				$result[] = array_merge( $attrs, $tmp_arr );
			}
			return $result;
		}

		/**
		 ** list of single item by post types: post, page, custom post type...
		 *
		 * @param string $posttype
		 *
		 * @return array
		 */
		static function get_single_by_post_types( $posttype = '' ) {
			$posttypes = self::get_post_types();
			$results   = array();
			foreach ( $posttypes as $slug => $name ) {
				if ( ! isset( $results[$slug] ) )
				$results[$slug] = array();
				// query post by post type
				$args = array( 'post_type' => $slug, 'posts_per_page' => -1, 'post_status' => ($slug == 'attachment' ) ? 'inherit' : 'publish' );
				$query = new WP_Query( $args );
				while ( $query->have_posts() ) {
					$query->the_post();
					$results[$slug][get_the_ID()] = __( get_the_title(), WR_PBL );
				}
				wp_reset_postdata();
			}
			if ( $posttype )
			return $results[$posttype];
			return $results;
		}

		/**
		 ** Single Entry options for Button Bar
		 *
		 * @param string $check_val
		 * @param array $attrs
		 *
		 * @return array
		 */
		static function get_single_item_button_bar( $check_val, $attrs ) {
			$post_singles = self::get_single_by_post_types();
			$term_taxos   = self::get_term_taxonomies();
			$result = array();
			foreach ( array_merge( $post_singles, $term_taxos ) as $taxo => $terms ) {
				$tmp_arr = array();
				$tmp_arr['options']    = $terms;
				$tmp_arr['dependency'] = array( $check_val, '=', $taxo );
				$result[] = array_merge( $attrs, $tmp_arr );
			}
			return $result;
		}

		/**
		 ** Single Entries options for Content List
		 *
		 * @param string $check_val
		 * @param array $attrs
		 *
		 * @return array
		 */
		static function get_single_entries_ctl( $check_val, $attrs, $post_types = array() ) {
			global $wpdb;
			$posttypes = array();
			$posttypes['nav_menu_item'] = __( 'Menu', WR_PBL );
			$posttypes = ! $post_types ? array_merge( $posttypes, self::get_post_types() ) : $post_types;
			$post_singles = $arr_post_has_parent = array();
			// Check taxonomies is parent type
			$exclude_taxo = self::_get_exclude_taxonomies();
			$public_taxs  = self::get_public_taxonomies( true );
			$term_taxos = self::get_term_taxonomies( '', true, 'name' );

			foreach ( $posttypes as $slug => $name ) {
				if ( ! isset( $post_singles[$slug] ) ) {
					$post_singles[$slug] = array();
					if ( in_array( $slug , $exclude_taxo ) AND $slug != 'nav_menu_item' ) {
						$post_singles[$slug]['root'] = __( 'Root', WR_PBL );
					}
				}

				$arr_posts = array();
				if ( $slug == 'page' ) {
					// process for page tree
					$sql   = $wpdb->prepare(
							"SELECT *
							FROM $wpdb->posts AS posts
							WHERE posts.post_type = %s AND posts.post_status = %s
							ORDER BY posts.post_parent ASC , posts.post_title ASC", 'page', 'publish'
							);
							$data  = $wpdb->get_results( $sql );
							$level = 0;

							foreach ( $data as $i => $item ) {
								if ( $item->post_parent == 0 ) {
									unset( $data[$i] );
									if ( ! $item->post_title ) {
										$item->post_title = __( '( no title )', WR_PBL );
									}
									$arr_posts[$item->ID] = __( $item->post_title, WR_PBL );
									self::_recur_tree( $arr_posts, $data, $item->ID, $level, '-1' );
								}
							}
				} else {
					// query post by post type
					$args = array( 'post_type' => $slug, 'posts_per_page' => -1, 'post_status' => ($slug == 'attachment' ) ? 'inherit' : 'publish' );
					$query = new WP_Query( $args );
					while ( $query->have_posts() ) {
						$query->the_post();
						$post_id = get_the_ID();
						$arr_posts[$post_id] = __( get_the_title(), WR_PBL );
					}

					wp_reset_postdata();
				}

				$arr_post_ids = array();
				foreach ( $arr_posts as $id => $title ) {
					if ( $id ) {
						$arr_post_ids[] = $id;
					}
				}

				$arr_post_ids	= implode( ',', $arr_post_ids );
				if ( $arr_post_ids ) {
					$sql = $wpdb->prepare(
							"SELECT term_rel.term_taxonomy_id AS term_taxonomy_id, name, object_id, taxonomy, term.term_id AS term_id
							FROM $wpdb->term_relationships AS term_rel
							INNER JOIN $wpdb->term_taxonomy as term_taxonomy
							ON term_taxonomy.term_taxonomy_id = term_rel.term_taxonomy_id
							INNER JOIN $wpdb->terms AS term
							ON term_taxonomy.term_id = term.term_id
							WHERE term_rel.object_id IN ( %s )", $arr_post_ids
					);

					$result = $wpdb->get_results( $sql );
				} else {
					$result = array();
				}
				$arr_taxonomy = array();
				if ( count( $result ) ) {
					foreach ( $result as $i => $item ) {
						if ( ! in_array( $item->taxonomy, $arr_taxonomy ) ) {
							$arr_taxonomy[] = $item->taxonomy;
						}
					}
				}

				if ( count( $result ) ) {
					if ( count( $arr_taxonomy ) >= 1 ) {
						foreach ( $arr_taxonomy as $j => $taxonomy ) {
							foreach ( $public_taxs as $tax_slug => $pb_tax ) {
								if ( $tax_slug == $taxonomy AND ! empty( $pb_tax ) ) {
									if ( $taxonomy == 'product_type' ) {
										$pb_tax = __( 'Product Type', WR_PBL );
									}
									$post_singles[$slug][$taxonomy] = array( 'text' => $pb_tax, 'type' => 'optiongroup' );

									if ( ! in_array( $slug, $arr_post_has_parent ) ) {
										$arr_post_has_parent[] = $slug;
									}

									$arr_cats = isset( $term_taxos[$taxonomy] ) ? (array) $term_taxos[$taxonomy] : array();
									foreach ( $arr_cats as $i => $cat ) {
										if ( $cat ) {
											$post_singles[$slug][$i] = $cat;
										}
									}
								}
							}
							foreach ( $result as $i => $item ) {
								foreach ( $arr_posts as $id => $title ) {
									if ( $item->object_id == $id AND $item->taxonomy == $taxonomy ) {
										$post_singles[$slug][$item->term_id] = __( $item->name, WR_PBL );

										if ( ! in_array( $slug, $arr_post_has_parent ) ) {
											$arr_post_has_parent[] = $slug;
										}
									}
								}
							}
							unset( $arr_taxonomy[$j] );
						}

					} else {
						foreach ( $arr_posts as $id => $title ) {
							foreach ( $result as $i => $item ) {
								if ( $item->object_id == $id ) {
									$post_singles[$slug][$item->term_id] = __( $item->name, WR_PBL );
								}
							}
						}
					}
				} else {
					foreach ( $arr_posts as $id => $title ) {
						$post_singles[$slug][$id] = $title;
					}
				}
			}

			$result     = array();

			foreach ( $posttypes as $_slug => $post ) {
				if ( in_array( $_slug, $exclude_taxo ) OR in_array( $_slug, $arr_post_has_parent ) ) {
					unset( $posttypes[$_slug] );
				}
			}

			foreach ( $post_singles as $taxo => $terms ) {
				$tmp_arr = array();
				if ( ! in_array( $taxo , $exclude_taxo ) ) {
					$tmp_arr['multiple'] = '1';
				}
				$allow = true;
				if ( count( $arr_post_has_parent ) ) {
					foreach ( $posttypes as $_slug => $post ) {
						if ( $_slug == $taxo ) {
							$allow = false;
							break;
						}
					}
				}
				if ( $allow ) {
					$tmp_arr['options'] = $terms;
				} else {
					$tmp_arr['options'] = array();
				}
				if ( $taxo == 'nav_menu_item' ) {
					$attrs['class'] = 'select2-select no_plus_depend';
				}
				$tmp_arr['no_order']   = '1';
				$tmp_arr['dependency'] = array( $check_val, '=', $taxo );
				$result[] = array_merge( $attrs, $tmp_arr );
			}
			return $result;
		}

		/**
		 ** exclude taxonomy and posttypes array for taxonomies parent-child type
		 *
		 * @return array
		 */
		static function _get_exclude_taxonomies() {
			global $wpdb;
			// set default exclude value
			$exclude_taxo   = array();
			$exclude_taxo[] = 'page';
			$exclude_taxo[] = 'nav_menu_item';

			$sql    = $wpdb->prepare( "SELECT DISTINCT( post_type ) FROM $wpdb->posts WHERE post_parent != %d", 0 );
			$result = $wpdb->get_results( $sql );

			foreach ( $result as $i => $item ) {
				if ( ! empty( $item->post_type ) AND ! in_array( $item->post_type, $exclude_taxo ) ) {
					$exclude_taxo[] = $item->post_type;
				}
			}

			$sql    = $wpdb->prepare(
					"SELECT term_taxonomy_id, taxonomy
					FROM $wpdb->term_taxonomy
					WHERE parent != %d", 0
			);
			$result = $wpdb->get_results( $sql );
			foreach ( $result as $i => $item ) {
				if ( ! in_array( $item->taxonomy , $exclude_taxo ) )
				$exclude_taxo[] = $item->taxonomy;
			}
			return $exclude_taxo;
		}

		/**
		 ** taxonomy without parent-child type
		 *
		 * @return string
		 */
		static function get_tax_no_parent() {
			global $wpdb;
			$arr_tax_no_parent = array();
			$sql = $wpdb->prepare( "SELECT taxonomy, parent FROM $wpdb->term_taxonomy WHERE 1 = %s", '1' );
			$result = $wpdb->get_results( $sql );

			$excluded = array();
			foreach ( $result as $i => $item ) {
				if ( ! in_array( $item->taxonomy, $excluded ) ) {
					if ( $item->parent == 0 AND ! in_array( $item->taxonomy, $arr_tax_no_parent ) ) {
						$arr_tax_no_parent[] = $item->taxonomy;
					} else if ( $item->parent != 0 AND in_array( $item->taxonomy, $arr_tax_no_parent ) ) {
						foreach ( $arr_tax_no_parent as $j => $_item ) {
							if ( $_item == $item->taxonomy ) {
								unset( $arr_tax_no_parent[$j] );
								$excluded[] = $item->taxonomy;
							}
						}
					}
				}
			}

			return implode( ',', $arr_tax_no_parent );
		}

		/**
		 ** public taxonomy options
		 *
		 * @param bool $is_full
		 *
		 * @return array
		 */
		static function get_public_taxonomies( $is_full = false ) {
			$arr_taxs = array();
			if ( ! $is_full ) {
				$taxs = get_taxonomies( array( 'public' => true, 'show_ui' => true ), 'objects' );
			} else {
				$taxs = get_taxonomies( null, 'objects' );
			}
			foreach ( $taxs as $i => $tax ) {
				if ( isset($tax->labels->singular_name ) && trim( $tax->labels->singular_name ) != '' ) {
					$arr_taxs[$tax->name] = __( $tax->labels->singular_name, WR_PBL );
				}
			}
			return $arr_taxs;
		}

		/**
		 * Static function get post type options
		 *
		 * @param bool $allow_filter
		 *
		 * @return array
		 */
		static function get_post_types( $allow_filter = false ) {
			$arr_posts = array();
			$posts     = get_post_types( array( 'public' => true, 'show_ui' => true ), 'objects' );
			foreach ( $posts as $i => $post ) {
				if ( ! $allow_filter ) {
					if ( $post->name == 'attachment' ) continue;
					if ( isset($post->labels->singular_name ) && trim( $post->labels->singular_name ) != '' ) {
						$arr_posts[$post->name] = __( $post->labels->singular_name, WR_PBL );
					}
				} else {
					$arr_posts[] = $post->name;
				}
			}
			return $arr_posts;
		}

		/**
		 * Private static function get exclude taxonomy array
		 *
		 * @return array
		 */
		private static function _get_exclude_tax() {
			global $wpdb;
			$exclude_taxo = array();

			$sql    = $wpdb->prepare( "SELECT DISTINCT( post_type ) FROM $wpdb->posts WHERE post_parent != 0", null );
			$result = $wpdb->get_results( $sql );

			foreach ( $result as $i => $item ) {
				if ( ! empty( $item->post_type ) AND ! in_array( $item->post_type, $exclude_taxo ) ) {
					$exclude_taxo[] = $item->post_type;
				}
			}
			return $exclude_taxo;
		}

		/**
		 * Private static function to get contentlist orderby array
		 *
		 * @return array
		 */
		private static function _get_ctl_order_by() {
			$arr_return = array();
			// setup for base post type
			$arr_return['post'] = array(
				'title'    => __( 'Title', WR_PBL ),
				'comment_count' => __( 'Comment Count', WR_PBL ),
				'date'     => __( 'Date', WR_PBL )
			);
			$arr_return['page'] = array(
				'title'    => __( 'Title', WR_PBL ),
				'comment_count' => __( 'Comment Count', WR_PBL ),
				'date'     => __( 'Date', WR_PBL )
			);

			$post_types = self::get_post_types();
			// setup for extend post type
			foreach ( $post_types as $slug => $post ) {
				if ( $slug ) {
					$arr_column = array();
					$arr_sort   = array(
						'title'    => 'title',
						'parent'   => 'parent',
						'comment_count' => 'comment_count',
						'date'     => array( 'date', true )
					);
					if ( has_filter( 'manage_edit-' . $slug . '_sortable_columns' ) ) {
						$arr_sort = array_merge( $arr_sort, apply_filters( 'manage_edit-' . $slug . '_sortable_columns', array() ) );
						if ( has_filter( 'manage_edit-' . $slug . '_columns' ) ) {
							$arr_column = apply_filters( 'manage_edit-' . $slug . '_columns', array() );
						}

						if ( $arr_sort AND $arr_column ) {
							$new_arr = array();
							foreach ( $arr_sort as $key => $value ) {
								foreach ( $arr_column as $_key => $_value ) {
									if ( $key == $_key ) {
										// process html
										if ( self::_is_html( $_value ) ) {
											$_value = substr( $_value, strpos( $_value, 'data-tip="' ) + 10 );
											$_value = substr( $_value, 0, strpos( $_value, '"' ) );
										}
										$new_arr[strtolower( $key )] = $_value;
									}
								}
							}
							$arr_return[$slug] = $new_arr;
						}
					}
				}
			}

			// setup for taxonomy
			$taxonomies = self::get_public_taxonomies();
			foreach ( $taxonomies as $slug => $tax ) {
				$arr_return[$slug] = array(
					'name'		=> __( 'Name', WR_PBL ),
					'description' => __( 'Description', WR_PBL ),
					'slug'		=> __( 'Slug', WR_PBL ),
					'count'       => __( 'Count', WR_PBL )
				);
			}

			return $arr_return;
		}

		/**
		 ** contentlist orderby options
		 *
		 * @param string $check_val
		 * @param array $attrs
		 * @return array
		 */
		static function get_list_ctl_order_by( $check_val, $attrs ) {
			$result = array();
			$data   = self::_get_ctl_order_by();
			foreach ( $data as $taxo => $terms ) {
				$tmp_arr = array();
				$tmp_arr['options'] = array_merge( array( 'no_order' => __( ' - No ordering - ', WR_PBL ) ), $terms );
				$tmp_arr['no_order']   = '1';
				$tmp_arr['dependency'] = array( $check_val, '=', $taxo );
				$result[] = array_merge( $attrs, $tmp_arr );
			}

			return $result;
		}

		/**
		 ** contentlist order
		 *
		 * @return array
		 */
		static function get_ctl_order() {
			return array(
				'asc'	=> __( 'Ascending', WR_PBL ),
				'desc'	=> __( 'Descending', WR_PBL )
			);
		}

		/**
		 * Private static function to check context string is html type
		 *
		 * @param string $string
		 *
		 * @return boolean
		 */
		private static function _is_html( $string = '' ) {
			if ( $string ) {
				if ( strlen( $string ) != strlen( strip_tags( $string ) ) ) {
					return true; // Contains HTML
				}
			}

			return false;
		}

		/**
		 * Private static function to get menu item options
		 *
		 * @return array
		 */
		private static function _get_menu_items() {
			$nav_menu_items = $arr_options = array();
			$nav_menus = wp_get_nav_menus();
			if ( count( $nav_menus ) ) {
				foreach ( $nav_menus as $i => $menu ) {
					$nav_menu_items[$menu->term_id] = wp_get_nav_menu_items( $menu, null );
				}
			}
			if ( count( $nav_menu_items ) ) {
				foreach ( $nav_menu_items as $term_id => $items ) {
					$arr_options[$term_id]['root'] = __( 'Root', WR_PBL );
					$level = 0;
					foreach ( $items as $i => $item ) {
						if ( $item->menu_item_parent == 0 ) {
							unset( $items[$i] );
							if ( ! $item->title ) {
								$item->title = __( '( no title )', WR_PBL );
							}
							$arr_options[$term_id][$item->ID] = __( $item->title, WR_PBL );
							self::_recur_tree( $arr_options, $items, $item->ID, $level, '-2', $term_id );
						}
					}
				}
			}
			return $arr_options;
		}

		/**
		 * menu item options
		 *
		 * @param string $check_val
		 * @param array $attrs
		 *
		 * @return array
		 */
		static function get_list_menu_items( $check_val, $attrs ) {
			$result = array();
			$data   = self::_get_menu_items();
			foreach ( $data as $taxo => $terms ) {
				$tmp_arr = array();
				$tmp_arr['options']    = $terms;
				$tmp_arr['no_order']   = '1';
				$tmp_arr['dependency'] = array( $check_val, '=', $taxo );
				$result[] = array_merge( $attrs, $tmp_arr );
			}

			return $result;
		}

		/**
		 * Static function to get pricing type of sub items
		 *
		 * @return array
		 */
		static function get_sub_item_pricing_type() {
			return array(
				'text' 		=> __( 'Free text', WR_PBL ),
				'checkbox' 	=> __( 'Yes / No', WR_PBL )
			);
		}

		/**
		 * Get posts by Term ID
		 *
		 * @param type $item_filter
		 * @param type $arr_ids
		 * @param type $source
		 */
		static function post_by_termid($item_filter, &$arr_ids, &$source) {
			global $wpdb;
			// Get list of Post ID by filter criteria
			$sql     = $wpdb->prepare(
                "SELECT DISTINCT(object_id), term_taxonomy_id
                FROM $wpdb->term_relationships AS term_rel
                WHERE term_rel.term_taxonomy_id IN ( %s )",
			$item_filter
			);
			$objlist = $wpdb->get_results( $sql );

			foreach ( $objlist as $i => $item ) {
				$arr_ids[] = $item->object_id;
				// get taxonomy
				$sqlx      = $wpdb->prepare(
                    "SELECT *
                    FROM $wpdb->term_taxonomy
                    WHERE term_taxonomy_id = %d",
				$item->term_taxonomy_id
				);
				$datax = $wpdb->get_results( $sqlx );
				foreach ( $datax as $i => $itemx ) {
					$source[] = $itemx->taxonomy;
				}
			}
		}

	}

}
?>
