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
if ( ! class_exists( 'WR_Pb_Objects_Modal' ) ) {

	class WR_Pb_Objects_Modal {

		private static $instance;

		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		private function __construct() {
			add_filter( 'wr_pb_register_assets', array( &$this, 'apply_assets' ) );
			$this->enqueue_admin_style();
			$this->enqueue_admin_script();

			// Localize script
			$this->wr_localize();

			do_action( 'wr_modal_init' );
		}

		/**
		 * Register custom assets to use on Modal
		 *
		 * @param array $assets
		 *
		 * @return array
		 */
		public function apply_assets( $assets ) {
			WR_Pb_Helper_Functions::load_bootstrap_3( $assets );
			$assets['wr-pb-handlesetting-js'] = array(
				'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/js/handle_setting.js',
				'ver' => '1.0.0',
			);
			if ( WR_Pb_Helper_Functions::is_preview() ) {
				$assets['wr-pb-frontend-css'] = array(
					'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/css/front_end.css',
					'ver' => '1.0.0',
				);
			}
			$assets['wr-pb-modal-css']             = array(
				'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/css/modal.css',
				'ver' => '1.0.0',
			);
			$assets['wr-pb-codemirror-css']        = array(
				'src' => WR_Pb_Helper_Functions::path( 'assets/3rd-party/' ) . '/codemirror/codemirror.css',
				'ver' => '1.0.0',
			);
			$assets['wr-pb-codemirror-js']         = array(
				'src' => WR_Pb_Helper_Functions::path( 'assets/3rd-party/' ) . '/codemirror/codemirror.js',
				'ver' => '1.0.0',
			);
			$assets['wr-pb-codemirrormode-css-js'] = array(
				'src' => WR_Pb_Helper_Functions::path( 'assets/3rd-party/' ) . '/codemirror/mode/css.js',
				'ver' => '1.0.0',
			);
			$assets['wr-pb-scrollreveal'] = array(
				'src' => WR_Pb_Helper_Functions::path( 'assets/3rd-party/scrollreveal' ) . '/scrollReveal.js',
				'ver' => '0.1.2',
			);
			$assets = apply_filters( 'wr_pb_assets_register_modal', $assets );

			return $assets;
		}

		/**
		 * Enqueue scripts
		 */
		public function enqueue_admin_script() {
			WR_Pb_Helper_Functions::enqueue_scripts_modal();

			wp_enqueue_media();

			WR_Pb_Helper_Functions::enqueue_scripts_end();

			WR_Pb_Init_Assets::load( array( 'wr-pb-placeholder' ) );
		}

		/**
		 * Enqueue style
		 */
		public function enqueue_admin_style() {
			WR_Pb_Helper_Functions::enqueue_styles();

			if ( ! WR_Pb_Helper_Functions::is_preview() ) {
				WR_Pb_Init_Assets::load( array( 'wr-pb-modal-css' ) );
			} else {
				WR_Pb_Init_Assets::load( array( 'wr-pb-frontend-css', 'wr-pb-scrollreveal' ) );
				WR_Pb_Init_Assets::inline( 'js', "
					var revealObjects  = null;
					$(document).ready(function (){
						if($('[data-scroll-reveal]').length) {
							if (!revealObjects) {
								revealObjects = new scrollReveal({
								        reset: true
								    });
							}
						}
					});
				" );
			}
		}

		/**
		 * Localize Script
		 */
		public function wr_localize() {
			WR_Pb_Init_Assets::localize( 'wr-pb-handlesetting-js', 'Wr_Ajax', WR_Pb_Helper_Functions::localize_js() );
		}

		/**
		 * Get related content for each Modal
		 *
		 * @param type $page
		 */
		public function preview_modal( $page = '' ) {
			add_action( 'wr_pb_modal_page_content', array( &$this, 'content' . $page ), 10 );
		}

		/**
		 * HTML content for Shortcode editing Modal
		 */
		public function content() {
			include WR_PB_TPL_PATH . '/modal.php';

			// Load required assets
			$assets = apply_filters( 'wr_pb_assets_enqueue_modal', array( 'wr-pb-handlesetting-js', ) );

			WR_Pb_Init_Assets::load( $assets );
		}

		/**
		 * HTML content for Page template Modal
		 */
		public function content_layout() {

			include WR_PB_TPL_PATH . '/layout/list.php';

			// load last assets: HandleSettings & hooked assets
			$assets = apply_filters( 'wr_pb_assets_enqueue_modal', array( 'wr-pb-handlesetting-js' ) );
			WR_Pb_Init_Assets::load( $assets );
		}

		/**
		 * HTML content for Custom css Modal
		 */
		public function content_custom_css() {
			$assets = apply_filters( 'wr_pb_assets_enqueue_modal', array( 'wr-pb-codemirror-css', 'wr-pb-codemirror-js', 'wr-pb-codemirrormode-css-js' ) );
			WR_Pb_Init_Assets::load( $assets );
			include WR_PB_TPL_PATH . '/custom-css.php';
		}

		/**
		 * HTML content for Reprt Bug Modal
		 *
		 * @return void
		 */
		public function content_report_bug() {
			$assets = apply_filters( 'wr_pb_assets_enqueue_modal', array(  ) );
			WR_Pb_Init_Assets::load( $assets );
			include WR_PB_TPL_PATH . '/report-bug.php';
		}

		public function content_add_element() {
			$assets = apply_filters( 'wr_pb_assets_enqueue_modal', array(  ) );
			WR_Pb_Init_Assets::load( $assets );
			include WR_PB_TPL_PATH . '/select-elements.php';
		}

		/**
		 * Ignore settings key in array
		 *
		 * @param array $options
		 *
		 * @return array
		 */
		static function ignore_settings( $options ) {
			if ( array_key_exists( 'settings', $options ) ) {
				$options = array_slice( $options, 1 );
			}

			return $options;
		}

		/**
		 * Add setting data to a tag
		 *
		 * @param string $tag
		 * @param array  $data
		 * @param string $content
		 *
		 * @return string
		 */
		static function tab_settings( $tag, $data, $content ) {
			$tag_data = array();
			if ( ! empty( $data ) ) {
				foreach ( $data as $key => $value ) {
					if ( ! empty( $value ) ) {
						$tag_data[] = "$key = '$value'";
					}
				}
			}
			$tag_data = implode( ' ', $tag_data );

			return "<$tag $tag_data>$content</$tag>";
		}

		/**
		 * get HTML of Modal Settings Box of Shortcode
		 *
		 * @param array $options
		 *
		 * @return string
		 */
		static function get_shortcode_modal_settings( $settings, $shortcode = '', $input_params = null, $raw_shortcode = null, $has_preview = false, $no_tab = false ) {
			$i    = 0;
			$tabs = $contents = $actions = $general_actions = array();
			$icon_tab = array( 'content' => 'pencil-square-o', 'styling' => 'magic', 'shortcode' => 'code' );
			foreach ( (array) $settings as $tab => $options ) {
				$options = self::ignore_settings( $options );
				if ( $tab == 'action' ) {
					foreach ( $options as $option ) {
						$actions[] = WR_Pb_Helper_Shortcode::render_parameter( $option['type'], $option );
					}
				} else if ( $tab == 'generalaction' ) {
					foreach ( $options as $option ) {
						$option['id']      = isset( $option['id'] ) ? ( 'param-' . $option['id'] ) : '';
						$general_actions[] = WR_Pb_Helper_Shortcode::render_parameter( $option['type'], $option );
					}
				} else {
					$active = ( $i ++ == 0 ) ? 'active' : '';
					if ( strtolower( $tab ) != 'notab' ) {
						$data_                = isset( $settings[$tab]['settings'] ) ? $settings[$tab]['settings'] : array();
						$data_['href']        = "#$tab";
						$data_['data-toggle'] = 'tab';
						$content_             = ucfirst( $tab );
						$icon                 = isset( $icon_tab[$tab] ) ? sprintf( '<i class="fa fa-%s"></i>', $icon_tab[$tab] ) : '';
						$tabs[]               = "<li class='$active'>" . self::tab_settings( 'a', $data_, $icon . $content_ ) . '</li>';
					}

					$has_margin = 0;
					$param_html = array();
					foreach ( $options as $idx => $option ) {
						// check if this element has Margin param (1)
						if ( isset( $option['name'] ) && $option['name'] == __( 'Margin', WR_PBL ) && $option['id'] != 'div_margin' )
						$has_margin = 1;
						// if (1), don't use the 'auto extended margin ( top, bottom ) option'
						if ( $has_margin && isset( $option['id'] ) && $option['id'] == 'div_margin' )
						continue;

						$type         = $option['type'];
						$option['id'] = isset( $option['id'] ) ? ( 'param-' . $option['id'] ) : "$idx";
						if ( ! is_array( $type ) ) {
							// Exclude preview field
							if ( $type != 'preview' ) {
								$param_html[$option['id']] = WR_Pb_Helper_Shortcode::render_parameter( $type, $option, $input_params );
							}
						} else {
							$output_inner = '';
							foreach ( $type as $sub_options ) {
								$sub_options['id'] = isset( $sub_options['id'] ) ? ( 'param-' . $sub_options['id'] ) : '';
								/* for sub option, auto assign bound = 0 {not wrapped by <div class='controls'></div> } */
								$sub_options['bound'] = '0';
								/* for sub option, auto assign 'input-small' class */
								$sub_options['class'] = isset( $sub_options['class'] ) ? ( $sub_options['class'] ) : '';
								$type                 = $sub_options['type'];
								$output_inner .= WR_Pb_Helper_Shortcode::render_parameter( $type, $sub_options );
							}
							$option                    = WR_Pb_Helper_Html::get_extra_info( $option );
							$label                     = WR_Pb_Helper_Html::get_label( $option );
							$param_html[$option['id']] = WR_Pb_Helper_Html::final_element( $option, $output_inner, $label );
						}
					}

					if ( !empty( $param_html['param-copy_style_from'] ) ) {
						// move "auto extended margin ( top, bottom ) option" to top of output
						$style_copy = isset( $param_html['param-copy_style_from'] ) ? $param_html['param-copy_style_from'] : '';

						if ( ! empty ( $param_html['param-div_margin'] ) ) {
							$margin = $param_html['param-div_margin'];
							$param_html = array_merge(
							array(
							$style_copy,
							$margin,
							),
							$param_html
							);
							unset( $param_html['param-copy_style_from'] );
							unset( $param_html['param-div_margin'] );
						}else{
							$param_html = array_merge(
							array(
							$style_copy
							),
							$param_html
							);
							unset( $param_html['param-copy_style_from'] );
						}
					}

					$param_html  = implode( '', $param_html );
					$content_tab = "<div class='tab-pane $active wr-pb-setting-tab' id='$tab'>$param_html</div>";
					$contents[]  = $content_tab;
				}
			}

			if ( $no_tab ) {
				return $param_html;
			}

			// Auto-append `Shortcode Content` tab
			if ( $shortcode != 'wr_row' ) {
				self::shortcode_content_tab( $tabs, $contents, $raw_shortcode );
			}

			return self::setting_tab_html( $shortcode, $tabs, $contents, $general_actions, $settings, $actions, $has_preview );
		}

		/**
		 * Generate tab with content, use for generating Modal
		 *
		 * @return string
		 */
		static function setting_tab_html( $shortcode, $tabs, $contents, $general_actions, $settings, $actions, $has_preview = false ) {
			$output = '<input type="hidden" value="' . $shortcode . '" id="shortcode_name" name="shortcode_name" />';

			/* Tab Content - Styling */

			$output .= '<div class="jsn-tabs">';
			if ( count( $tabs ) > 0 ) {
				$output .= '<ul class="" id="wr_option_tab">';
				$output .= implode( '', $tabs );
				$output .= '</ul>';
			}
			/* Tab Content */

			$output .= implode( '', $contents );

			$output .= "<div class='jsn-buttonbar wr_action_btn'>";

			/* Tab Content - General actions */
			if ( count( $general_actions ) ) {
				$data_    = $settings['generalaction']['settings'];
				$content_ = implode( '', $general_actions );
				$output  .= self::tab_settings( 'div', $data_, $content_ );
			}

			$output .= implode( '', $actions );
			$output .= '</div>';
			$output .= '</div>';

			if ( $has_preview == true ) {
				$output = "<div class='wr-setting-resize'>{$output}</div>";
			}

			return $output;
		}

		/**
		 * Append shortcode content tab to all element settings modal.
		 *
		 * @param   array  &$tabs         Current tabs array.
		 * @param   array  &$contents     Currnt content array.
		 * @param   string $raw_shortcode Raw shortcode content.
		 *
		 * @return  void
		 */
		public static function shortcode_content_tab( &$tabs, &$contents, $raw_shortcode ) {
			// Auto-append `Shortcode Content` tab only if this is not a sub-modal
			if ( ! isset( $_REQUEST['submodal'] ) || ! $_REQUEST['submodal'] ) {
				// Generate tab for shortcode content
				$tabs[] = '<li><a href="#shortcode-content" data-toggle="tab">' . '<i class="fa fa-code"></i>' . __( 'Shortcode', WR_PBL ) . '</a></li>';

				// Generate content for shortcode content tab
				$contents[] = '<div class="tab-pane clearfix" id="shortcode-content">'
				. '<textarea id="shortcode_content" class="form-control" rows="10" disabled="disabled">' . esc_textarea( $raw_shortcode ) . '</textarea>'
				. '<div class="text-center"><button class="btn btn-success" id="copy_to_clipboard" data-textchange="' . __( 'Done!', WR_PBL ) . '">' . __( 'Copy to Clipboard', WR_PBL ) . '</button></div>'
				. '</div>';
			} else {
				// Generate hidden text area to hold raw shortcode
				$contents[] = '<textarea class="hidden" id="shortcode_content">' . esc_textarea( $raw_shortcode ) . '</textarea>';
			}
		}

		/**
		 * Generate Setting HTML for each shortcode
		 *
		 * @global object $Wr_Pb
		 * @param string $shortcode The current shortcode
		 * @param array $params The shortcode content
		 * @param string $el_title Title of shortcode
		 * @param bool $no_tab
		 *
		 * @return string
		 */
		public static function shortcode_modal_settings( $shortcode, $params, $el_title = '', $no_tab = false ) {
			$html = '';

			// get shortcode class
			$class = WR_Pb_Helper_Shortcode::get_shortcode_class( $shortcode );

			if ( class_exists( $class ) ) {
				global $Wr_Pb;
				$settings_html = '';
				$elements = $Wr_Pb->get_elements();
				$instance = isset( $elements['element'][strtolower( $class )] ) ? $elements['element'][strtolower( $class )] : null;

				if ( ! is_object( $instance ) ) {
					$instance = new $class();
				}
				$instance->init_element();
				$has_preview = false;

				// Generate default params if they were not posted.
				if ( empty( $params ) ) {
					$params  = $instance->config['shortcode_structure'];
				}

				if ( ! empty( $params ) ) {
					$params = str_replace( '#_EDITTED', '', $params );
					$extract_params = WR_Pb_Helper_Shortcode::extract_params( $params, $shortcode );

					// if have sub-shortcode, extract sub shortcodes content
					if ( ! empty( $instance->config['has_subshortcode'] ) ) {
						$sub_sc_data                         = WR_Pb_Helper_Shortcode::extract_sub_shortcode( $params, true );
						$sub_sc_data                         = apply_filters( 'wr_pb_sub_items_filter', $sub_sc_data, $shortcode, isset ( $_COOKIE['wr_pb_data_for_modal'] ) ? $_COOKIE['wr_pb_data_for_modal'] : '' );
						$extract_params['sub_items_content'] = $sub_sc_data;
					}

					// Set auto title for the subitem if have
					$extract_title   =( isset( $el_title ) && $el_title != __( '(Untitled)', WR_PBL ) ) ? $el_title : '';
					// MODIFY $instance->items
					WR_Pb_Helper_Shortcode::generate_shortcode_params( $instance->items, NULL, $extract_params, TRUE, FALSE, $extract_title, $has_preview, $sc_content_tab );

					// if have sub-shortcode, re-generate shortcode structure
					if ( ! empty( $instance->config['has_subshortcode'] ) ) {
						$instance->shortcode_data();
					}
				}

				// get Modal setting box
				$settings      = $instance->items;
				$settings_html .= WR_Pb_Objects_Modal::get_shortcode_modal_settings( $settings, $shortcode, $extract_params, $params, $has_preview, $no_tab );

				// Add preview
				if ( $has_preview ) {
					$settings_html .= WR_Pb_Helper_Shortcode::render_parameter( 'preview' );
				}

				$html = balanceTags( $settings_html );
			}


			return $html;
		}

		public static function _modal_footer() {

		}

	}
}
