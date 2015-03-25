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
 * Core initialization class of WR Pb Plugin.
 *
 * @package  WR_Pb_Assets_Register
 * @since	1.0.0
 */
class WR_Pb_Core {

	/**
	 * WR Pb Plugin's custom post type slug.
	 *
	 * @var  string
	 */
	private $wr_elements;

	/**
	 * Constructor.
	 *
	 * @return  void
	 */
	function __construct() {
		$this->wr_elements = array();

		global $pagenow;
		if (
				'post.php' == $pagenow || 'post-new.php' == $pagenow // Post editing page
				|| 'widgets.php' == $pagenow                         // Widget page, for WR Page Element Widget
				|| ( isset( $_GET['wr-gadget'] ) && $_GET['wr-gadget'] != '' )	// WR Gadet
				|| ( defined( 'DOING_AJAX' ) && DOING_AJAX )         // Ajax page
				|| ! is_admin()                                      // Front end
		)
		{
			$this->register_element();
			$this->register_widget();
		}

		$this->custom_hook();
	}

	/**
	 * Get array of shortcode elements
	 * @return type
	 */
	function get_elements() {
		return $this->wr_elements;
	}

	/**
	 * Add shortcode element
	 * @param type $type: type of element ( element/layout )
	 * @param type $class: name of class
	 * @param type $element: instance of class
	 */
	function set_element( $type, $class, $element = null ) {
		if ( empty( $element ) )
		$this->wr_elements[$type][strtolower( $class )] = new $class();
		else
		$this->wr_elements[$type][strtolower( $class )] = $element;
	}

	/**
	 * WR PageBuilder custom hook
	 */
	function custom_hook() {
		// filter assets
		add_filter( 'wr_pb_register_assets', array( &$this, 'apply_assets' ) );
		add_action( 'admin_head', array( &$this, 'load_assets' ), 10 );
		add_action( 'admin_head', array( &$this, 'load_elements_list' ), 10 );
		// translation
		add_action( 'init', array( &$this, 'translation' ) );
		// register modal page
		add_action( 'admin_init', array( &$this, 'modal_register' ) );
		add_action( 'admin_init', array( &$this, 'widget_register_assets' ) );

		// enable shortcode in content & filter content with IGPB shortcodes
		add_filter( 'the_content', array( &$this, 'pagebuilder_to_frontend' ), 9 );
		add_filter( 'the_content', 'do_shortcode' );

		// enqueue js for front-end
		add_action( 'wp_enqueue_scripts', array( &$this, 'frontend_scripts' ) );

		// hook saving post
		add_action( 'edit_post', array( &$this, 'save_pagebuilder_content' ) );
		add_action( 'save_post', array( &$this, 'save_pagebuilder_content' ) );
		add_action( 'publish_post', array( &$this, 'save_pagebuilder_content' ) );
		add_action( 'edit_page_form', array( &$this, 'save_pagebuilder_content' ) );
		add_action( 'pre_post_update', array( &$this, 'save_pagebuilder_content' ) );

		// ajax action
		add_action( 'wp_ajax_save_css_custom', array( &$this, 'save_css_custom' ) );
		add_action( 'wp_ajax_delete_layout', array( &$this, 'delete_layout' ) );
		add_action( 'wp_ajax_delete_layouts_group', array( &$this, 'delete_layouts_group' ) );
		add_action( 'wp_ajax_reload_layouts_box', array( &$this, 'reload_layouts_box' ) );
		add_action( 'wp_ajax_wrpb_clear_cache', array( &$this, 'igpb_clear_cache' ) );
		add_action( 'wp_ajax_save_layout', array( &$this, 'save_layout' ) );
		add_action( 'wp_ajax_submit_report_bug', array( &$this, 'submit_report_bug' ) );
		add_action( 'wp_ajax_upload_layout', array( &$this, 'upload_layout' ) );
		add_action( 'wp_ajax_update_whole_sc_content', array( &$this, 'update_whole_sc_content' ) );
		add_action( 'wp_ajax_shortcode_extract_param', array( &$this, 'shortcode_extract_param' ) );
		add_action( 'wp_ajax_get_json_custom', array( &$this, 'ajax_json_custom' ) );
		add_action( 'wp_ajax_get_shortcode_tpl', array( &$this, 'get_shortcode_tpl' ) );
		add_action( 'wp_ajax_get_default_shortcode_structure', array( &$this, 'get_default_shortcode_structure' ) );
		add_action( 'wp_ajax_get_settings_html', array( &$this, 'get_settings_html' ) );

		add_action( 'wp_ajax_text_to_pagebuilder', array( &$this, 'text_to_pagebuilder' ) );
		add_action( 'wp_ajax_get_html_content', array( &$this, 'get_html_content' ) );
		add_action( 'wp_ajax_get_same_elements', array( &$this, 'get_same_elements' ) );
		add_action( 'wp_ajax_merge_style_params', array( &$this, 'merge_style_params' ) );
		// add IGPB metabox
		add_action( 'add_meta_boxes', array( &$this, 'custom_meta_boxes' ) );

		// print html template of shortcodes
		add_action( 'admin_footer', array( &$this, 'element_tpl' ) );
		add_filter( 'wp_handle_upload_prefilter', array( &$this, 'media_file_name' ), 100 );

		// add IGPB button to Wordpress TinyMCE
		add_filter( 'wp_default_editor', create_function('', 'return "html";') );
		add_filter( 'tiny_mce_before_init', array( &$this, 'tiny_mce_before_init' ) );
		if ( $this->check_support( 'has_editor' ) ) {
			add_action( 'media_buttons_context',  array( &$this, 'add_page_element_button' ) );
		}

		// Remove Gravatar from Ig Modal Pages
		if ( is_admin() ) {
			add_filter( 'bp_core_fetch_avatar', array( &$this, 'remove_gravatar' ), 1, 9 );
			add_filter( 'get_avatar', array( &$this, 'get_gravatar' ), 1, 5 );
		}

		// add body class in backend
		add_filter( 'admin_body_class', array( &$this, 'admin_bodyclass' ) );

		// get image size
		add_filter( 'wr_pb_get_json_image_size', array( &$this, 'get_image_size' ) );

		// Editor hook before & after
		add_action( 'edit_form_after_title', array( &$this, 'hook_after_title' ) );
		add_action( 'edit_form_after_editor', array( &$this, 'hook_after_editor' ) );

		// Frontend hook
		add_filter( 'post_class', array( &$this, 'wp_bodyclass' ) );
		add_action( 'wp_head', array( &$this, 'post_view' ) );
		add_action( 'wp_footer', array( &$this, 'enqueue_compressed_assets' ) );

		// Custom css
		add_action( 'wp_head', array( &$this, 'enqueue_custom_css' ), 25 );
		add_action( 'wp_head', array( $this, 'print_frontend_styles' ), 25 );
		
		// Register 'wr_installed_product' filter
		add_filter( 'wr_pb_installed_product', array( __CLASS__, 'register_product' ) );

		// Add Wp pointer style/script
		add_action( 'admin_enqueue_scripts', array( $this, 'add_wp_pointer_assets' ) );

		do_action( 'wr_pb_custom_hook' );
	}

	/**
	 * Wp pointer style/script
	 */
	function add_wp_pointer_assets() {
		// Assume pointer shouldn't be shown
		$enqueue_pointer_script_style = false;

		// Get array list of dismissed pointers for current user and convert it to array
		$dismissed_pointers = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

		// If this post has not used PageBuilder
		global $post;
		$not_used_pb = isset( $post ) && ( 1 !== get_post_meta( $post->ID, '_wr_page_active_tab' ) );

		// Check if our pointer is not among dismissed ones
		if( $not_used_pb && !in_array( 'wr_pb_settings_pointer', $dismissed_pointers ) ) {
			$enqueue_pointer_script_style = true;

			// Add footer scripts using callback function
			add_action( 'admin_print_footer_scripts', array( $this, 'custom_pointer_scripts' ) );
		}

		// Enqueue pointer CSS and JS files, if needed
		if( $enqueue_pointer_script_style ) {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
		}
	}

	/**
	 * Show WR Pagbuider pointer
	 */
	function custom_pointer_scripts() {
		// Pointer content
		$pointer_content  = sprintf( "<p style=\"font-weight:bold;\">%s!</p>", __( 'Start using the PageBuilder', WR_PBL ) );
		?>

		<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			$('#wr_editor_tabs').pointer({
				content:		'<?php echo $pointer_content; ?>',
				position:		{
									edge:	'left', // arrow direction
									align:	'center' // vertical alignment
								},
				pointerWidth:	350,
				close:			function() {
									$.post( ajaxurl, {
											pointer: 'wr_pb_settings_pointer', // pointer ID
											action: 'dismiss-wp-pointer'
									});
								}
			}).pointer('open');
		});
		//]]>
		</script>
		<?php
	}

	/**
	 * Apply 'wr_installed_product' filter.
	 *
	 * @param   array  $plugins  Array of installed WooRockets product.
	 *
	 * @return  array
	 */
	public static function register_product( $plugins ) {
		// Register product identification
		$plugins[] = WR_PAGEBUILDER_IDENTIFICATION;

		return $plugins;
	}

	/**
	 * Save the envato settings
	 * @param unknown_type $arg_holder
	 * @param array $settings
	 */
	function save_envato_settings( $arg_holder, $settings ) {
		$option    = WR_PAGEBUILDER_ITEM_ID . "_purchase_data";
		// Check if the option existed
		if ( get_option($option) ) {
			update_option( $option,
					array(
						'username'      => $settings['envato_username'],
						'api_key'       => $settings['envato_api_key'],
						'purchase_code' => $settings['envato_purchase_code'],
					)
				);
		}else{
			add_option( $option,
					array(
						'username'      => $settings['envato_username'],
						'api_key'       => $settings['envato_api_key'],
						'purchase_code' => $settings['envato_purchase_code'],
					)
				);
		}
	}

	/**
	 * Get translation file
	 */
	function translation() {
		load_plugin_textdomain( WR_PBL, false, dirname( plugin_basename( WR_PB_FILE ) ) . '/languages/' );
	}

	/**
	 * Register custom asset files
	 *
	 * @param type $assets
	 * @return string
	 */
	function apply_assets( $assets ) {
		$assets['wr-pb-frontend-css'] = array(
			'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/css/front_end.css',
			'ver' => '1.0.0',
		);
		WR_Pb_Helper_Functions::load_bootstrap_3( $assets );
		if ( ! is_admin() || WR_Pb_Helper_Functions::is_preview() ) {
			$options = array( 'wr_pb_settings_boostrap_js', 'wr_pb_settings_boostrap_css' );
			// get saved options value
			foreach ( $options as $key ) {
				$$key = get_option( $key, 'enable' );
			}
			if ( $wr_pb_settings_boostrap_css != 'enable' ) {
				$assets['wr-pb-bootstrap-css'] = array(
					'src' => '',
					'ver' => '3.0.2',
				);
			}
			if ( $wr_pb_settings_boostrap_js != 'enable' ) {
				$assets['wr-pb-bootstrap-js'] = array(
					'src' => '',
					'ver' => '3.0.2',
				);
			}

			$assets['wr-pb-scrollreveal'] = array(
				'src' => WR_Pb_Helper_Functions::path( 'assets/3rd-party/scrollreveal' ) . '/scrollReveal.js',
				'ver' => '0.1.2',
			);
			$assets['wr-pb-stellar'] = array(
				'src' => WR_Pb_Helper_Functions::path( 'assets/3rd-party/stellar' ) . '/stellar.js',
				'ver' => '0.6.2',
			);
		}
		$assets['wr-pb-joomlashine-frontend-css'] = array(
			'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/css/jsn-gui-frontend.css',
			'deps' => array( 'wr-pb-bootstrap-css' ),
		);
		$assets['wr-pb-frontend-responsive-css'] = array(
			'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/css/front_end_responsive.css',
			'ver' => '1.0.0',
		);
		$assets['wr-pb-addpanel-js'] = array(
			'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/js/add_page_builder.js',
			'ver' => '1.0.0',
		);
		$assets['wr-pb-layout-js'] = array(
			'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/js/layout.js',
			'ver' => '1.0.0',
		);
		$assets['wr-pb-widget-js'] = array(
			'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/js/widget.js',
			'ver' => '1.0.0',
		);
		$assets['wr-pb-placeholder'] = array(
			'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/js/placeholder.js',
			'ver' => '1.0.0',
		);
		$assets['wr-pb-settings-js'] = array(
			'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/js/product/settings.js',
			'ver' => '1.0.0',
		);
		$assets['wr-pb-upgrade-js'] = array(
			'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/js/product/upgrade.js',
			'ver' => '1.0.0',
		);
		$assets['wr-pb-tinymce-btn'] = array(
			'src' => WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/js/tinymce.js',
			'ver' => '1.0.0',
		);
		return $assets;
	}

	/**
	 * Enqueue scripts & style for Front end
	 */
	function frontend_scripts() {
		/* Load stylesheets */
		$wr_pb_frontend_css = array( 'wr-pb-font-icomoon-css', 'wr-pb-joomlashine-frontend-css', 'wr-pb-frontend-css', 'wr-pb-frontend-responsive-css' );

		WR_Pb_Init_Assets::load( $wr_pb_frontend_css );

		// Load scripts
		$wr_pb_frontend_js = array( 'wr-pb-bootstrap-js', 'wr-pb-scrollreveal', 'wr-pb-stellar' );

		// Proceed element appearing animation
		WR_Pb_Init_Assets::load( apply_filters( 'wr_pb_assets_enqueue_frontend',  $wr_pb_frontend_js ) );
		WR_Pb_Init_Assets::inline( 'js', "
			var WR_Ig_RevealObjects  = null;
			var WR_Ig_StellarObjects = null;
			$(document).ready(function (){
				// Enable Appearing animations for elements
				if($('[data-scroll-reveal]').length) {
					if (!WR_Ig_RevealObjects) {
						WR_Ig_RevealObjects = new scrollReveal({
						        reset: true
						    });
					}
				}
				// Enable paralax for row container
				if($('[data-stellar-background-ratio]').length) {
					if (!WR_Ig_StellarObjects) {
						WR_Ig_StellarObjects = $.stellar({
					        horizontalScrolling: false,
					        verticalOffset: 40
					    });
					}
				}
			});
		" );
	}

	/**
	 * Add WR PageBuilder Metaboxes
	 */
	function custom_meta_boxes() {
		// Fixed bug header already send
		ob_start();
		if ( $this->check_support() ) {
			add_meta_box(
				'wr_page_builder',
			__( 'WR PageBuilder', WR_PBL ),
			array( &$this, 'page_builder_html' )
			);
		}

	}

	/**
	 * Content file for WR PageBuilder Metabox
	 */
	function page_builder_html() {
		// Get available data converters
		$converters = WR_Pb_Converter::get_converters();

		if ( @count( $converters ) ) {
			// Load script initialization for data conversion
			WR_Pb_Init_Assets::load( 'wr-pb-convert-data-js' );
		}

		// Load script initialization for undo / redo action
		WR_Pb_Init_Assets::load( 'wr-pb-activity-js' );

		include WR_PB_TPL_PATH . '/page-builder.php';
	}

	/**
	 * Register all Parent & No-child element, for Add Element popover
	 */
	function register_element() {
		global $Wr_Pb_Shortcodes;
		$current_shortcode = WR_Pb_Helper_Functions::current_shortcode();
		$Wr_Pb_Shortcodes  = ! empty ( $Wr_Pb_Shortcodes ) ? $Wr_Pb_Shortcodes : WR_Pb_Helper_Shortcode::wr_pb_shortcode_tags();
		foreach ( $Wr_Pb_Shortcodes as $name => $sc_info ) {
			$arr  = explode( '_', $name );
			$type = $sc_info['type'];
			if ( ! $current_shortcode || ! is_admin() || in_array( $current_shortcode, $arr ) || ( ! $current_shortcode && $type == 'layout' ) ) {
				$class   = WR_Pb_Helper_Shortcode::get_shortcode_class( $name );
				if ( class_exists( $class ) ) {
					$element = new $class();
					$this->set_element( $type, $class, $element );
					//				$this->register_sub_el( $class, 1 );
				}
			}
		}
	}

	/**
	 * Register IGPB Widget
	 */
	function register_widget(){
		register_widget( 'WR_Pb_Objects_Widget' );
	}

	/**
	 * Regiter sub element
	 *
	 * @param string $class
	 * @param int $level
	 */
	private function register_sub_el( $class, $level = 1 ) {
		$item  = str_repeat( 'Item_', intval( $level ) - 1 );
		$class = str_replace( "WR_$item", "WR_Item_$item", $class );
		if ( class_exists( $class ) ) {
			// 1st level sub item
			$element = new $class();
			$this->set_element( 'element', $class, $element );
			// 2rd level sub item
			$this->register_sub_el( $class, 2 );
		}
	}

	/**
	 * print HTML template of shortcodes
	 */
	function element_tpl() {
		ob_start();

		// Print template for WR PageBuilder elements
		$elements = $this->get_elements();

		foreach ( $elements as $type_list ) {
			foreach ( $type_list as $element ) {
				// Get element type
				$element_type = $element->element_in_pgbldr( null, null, null, null, false);
				// Print template tag
				foreach ( $element_type as $element_structure ) {
					echo balanceTags( "<script type='text/html' id='tmpl-{$element->config['shortcode']}'>\n{$element_structure}\n</script>\n" );
				}
			}
		}

		// Print widget template
		global $Wr_Pb_Widgets;

		if ( class_exists( 'WR_Widget' ) ) {
			foreach ( $Wr_Pb_Widgets as $shortcode => $shortcode_obj ) {
				// Instantiate Widget element
				$element = new WR_Widget();

				// Prepare necessary variables
				$modal_title = $shortcode_obj['identity_name'];
				$content     = $element->config['exception']['data-modal-title'] = $modal_title;

				$element->config['shortcode']           = $shortcode;
				$element->config['shortcode_structure'] = WR_Pb_Utils_Placeholder::add_placeholder( "[wr_widget widget_id=\"$shortcode\"]%s[/wr_widget]", 'widget_title' );
				$element->config['el_type']             = 'widget';

				// Get element type
				$element_type = $element->element_in_pgbldr( null, null, null, null, false);

				// Print template tag
				foreach ( $element_type as $element_structure ) {
					echo balanceTags( "<script abc type='text/html' id='tmpl-{$shortcode}'>\n{$element_structure}\n</script>\n" );
				}
			}
		}

		// Allow printing extra footer
		do_action( 'wr_pb_footer' );

		ob_end_flush();
	}

	/**
	 * Show Modal page
	 */
	function modal_register() {
		if ( WR_Pb_Helper_Functions::is_modal() ) {
			$cls_modal = WR_Pb_Objects_Modal::get_instance();
			if ( ! empty( $_GET['wr_modal_type'] ) )
				$cls_modal->preview_modal();
			if ( ! empty( $_GET['wr_layout'] ) )
				$cls_modal->preview_modal( '_layout' );
			if ( ! empty( $_GET['wr_custom_css'] ) )
				$cls_modal->preview_modal( '_custom_css' );
			if ( ! empty( $_GET['wr_report_bug'] ) )
				$cls_modal->preview_modal( '_report_bug' );
			if ( ! empty( $_GET['wr_add_element'] ) )
				$cls_modal->preview_modal( '_add_element' );
		}
	}

	/**
	 * Do action on modal page hook
	 */
	function modal_page_content() {
		do_action( 'wr_pb_modal_page_content' );
	}

	/**
	 * Save WR PageBuilder shortcode content of a post/page
	 *
	 * @param int $post_id
	 * @return type
	 */
	function save_pagebuilder_content( $post_id ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

		if ( ! isset($_POST[WR_NONCE . '_builder'] ) || ! wp_verify_nonce( $_POST[WR_NONCE . '_builder'], 'wr_builder' ) ) {
			return;
		}

		$wr_deactivate_pb = intval( esc_sql( $_POST['wr_deactivate_pb'] ) );

		if ( $wr_deactivate_pb ) {
			WR_Pb_Utils_Common::delete_meta_key( array( '_wr_page_builder_content', '_wr_html_content', '_wr_page_active_tab', '_wr_post_view_count' ), $post_id );
		} else {
			$wr_active_tab = intval( esc_sql( $_POST['wr_active_tab'] ) );
			$post_content  = '';

			// WR PageBuilder is activate
			if ( $wr_active_tab ) {
				$data = array();

				if ( isset( $_POST['shortcode_content'] ) && is_array( $_POST['shortcode_content'] ) ) {
					foreach ( $_POST['shortcode_content'] as $shortcode ) {
						$data[] = trim( stripslashes( $shortcode ) );
					}
				} else {
					$data[] = '';
				}

				$post_content = WR_Pb_Utils_Placeholder::remove_placeholder( implode( '', $data ), 'wrapper_append', '' );

				// update post meta
				update_post_meta( $post_id, '_wr_page_builder_content', $post_content );
				update_post_meta( $post_id, '_wr_html_content', WR_Pb_Helper_Shortcode::doshortcode_content( $post_content ) );
			}
			else {
				$content = stripslashes( $_POST['content'] );
				/// remove this line? $content = apply_filters( 'the_content', $content );
				$post_content = $content;
			}

			// update current active tab
			update_post_meta( $post_id, '_wr_page_active_tab', $wr_active_tab );
		}

		// update whether or not deactive pagebuilder
		update_post_meta( $post_id, '_wr_deactivate_pb', $wr_deactivate_pb );
	}

	/**
	 * Render shortcode preview in a blank page
	 *
	 * @return Ambigous <string, mixed>|WP_Error
	 */
	function shortcode_iframe_preview() {

		if ( isset( $_GET['wr_shortcode_preview'] ) ) {
			if ( ! isset($_GET['wr_shortcode_name'] ) || ! isset( $_POST['params'] ) )
			return __( 'empty shortcode name / parameters', WR_PBL );

			if ( ! isset($_GET[WR_NONCE] ) || ! wp_verify_nonce( $_GET[WR_NONCE], WR_NONCE ) )
			return;

			$shortcode = esc_sql( $_GET['wr_shortcode_name'] );
			$params    = urldecode( $_POST['params'] );
			$pattern   = '/^\[wr_widget/i';
			if ( ! preg_match( $pattern, trim( $params ) ) ) {
				// get shortcode class
				$class = WR_Pb_Helper_Shortcode::get_shortcode_class( $shortcode );

				// get option settings of shortcode
				$elements = $this->get_elements();
				$element  = isset( $elements['element'][strtolower( $class )] ) ? $elements['element'][strtolower( $class )] : null;
				if ( ! is_object( $element ) )
				$element = new $class();

				if ( $params ) {
					$extract_params = WR_Pb_Helper_Shortcode::extract_params( $params, $shortcode );
				} else {
					$extract_params = $element->config;
				}

				$element->shortcode_data();

				$_shortcode_content = $extract_params['_shortcode_content'];
				$content = $element->element_shortcode( $extract_params, $_shortcode_content );
			} else {
				$class = 'WR_Widget';
				$content = WR_Pb_Helper_Shortcode::widget_content( array( $params ) );
			}
			global $Wr_Pb_Preview_Class;
			$Wr_Pb_Preview_Class = $class;

			$html  = '<div id="shortcode_inner_wrapper" class="jsn-bootstrap3"><fieldset>';
			$html .= $content;
			$html .= '</fieldset></div>';
			echo balanceTags( $html );
		}
	}

	/**
	 * Update Shortcode content by merge its content & sub-shortcode content
	 */
	function update_whole_sc_content() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;

		$shortcode_content     = $_POST['shortcode_content'];
		$sub_shortcode_content = $_POST['sub_shortcode_content'];
		echo balanceTags( WR_Pb_Helper_Shortcode::merge_shortcode_content( $shortcode_content, $sub_shortcode_content ) );

		exit;
	}

	/**
	 * extract a param from shortcode data
	 */
	function shortcode_extract_param() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;

		$data		  = $_POST['data'];
		$extract_param = $_POST['param'];
		$extract       = array();
		$shortcodes    = WR_Pb_Helper_Shortcode::extract_sub_shortcode( $data );
		foreach ( $shortcodes as $shortcode ) {
			$shortcode    = stripslashes( $shortcode );
			$parse_params = shortcode_parse_atts( $shortcode );
			$extract[]    = isset( $parse_params[$extract_param] ) ? trim( $parse_params[$extract_param] ) : '';
		}
		$extract = array_filter( $extract );
		$extract = array_unique( $extract );

		echo balanceTags( implode( ',', $extract ) );
		exit;
	}

	function ajax_json_custom() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;

		if ( ! $_POST['custom_type'] )
		return 'false';

		$response = apply_filters( 'wr_pb_get_json_' . $_POST['custom_type'], $_POST );
		echo balanceTags( $response );

		exit;
	}

	/**
	 * Get shortcode structure with default attributes
	 * eg: [wr_text title="The text"]Lorem ipsum[/wr_text]
	 * Enter description here ...
	 */
	function get_default_shortcode_structure() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;
		if ( ! $_POST['shortcode'] )
		return;
		$shortcode = $_POST['shortcode'];
		$class     = WR_Pb_Helper_Shortcode::get_shortcode_class( $shortcode );
		if ( class_exists( $class ) ) {
			$element   = new $class();
			if ( method_exists( $element, 'init_element' ) ) {
				$element->init_element();
			}
			$shortcode_structure = isset( $element->config['shortcode_structure'] ) ? $element->config['shortcode_structure'] : '';

			if ( strpos( $shortcode, 'wr_item' ) === false ) {
				// Replace _WR_INDEX_ with index string when call generate shortcode first.
				$this->index_shortcode_item = 1;
				$shortcode_structure        = preg_replace_callback( '/_WR_INDEX_/', array( &$this, 'replace_index_count' ), $shortcode_structure );
			}

			echo $shortcode_structure;
		}

		exit;
	}

	/**
	 * Replace _WR_INDEX_ with index string in shortcode
	 *
	 * @param string $matches
	 * @return string
	 */
	private function replace_index_count( $matches ) {
		return $this->index_shortcode_item++;
	}

	/**
	 * Get settings HTML modal from shortcode content
	 *
	 * @return html
	 */
	function get_settings_html() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;

		$shortcode      = $_POST['shortcode'];
		$shortcode_data = $_POST['data'];

		$sub_el_settings = WR_Pb_Objects_Modal::shortcode_modal_settings( $shortcode, stripslashes( $shortcode_data ), '', true );
		printf( "<div class='sub-element-settings form' style='display: none'>%s</div>", balanceTags( $sub_el_settings ) );

		exit;
	}

	/**
	 * Update PageBuilder when switch Classic Editor to WR PageBuilder
	 *
	 * @return string
	 */
	function text_to_pagebuilder() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;

		if ( ! isset( $_POST['content'] ) )
		return;
		// $content = urldecode( $_POST['content'] );
		$content = ( $_POST['content'] );
		$content = stripslashes( $content );

		$empty_str = WR_Pb_Helper_Shortcode::check_empty_( $content );
		if ( strlen( trim( $content ) ) && strlen( trim( $empty_str ) ) ) {
			$builder = new WR_Pb_Helper_Shortcode();

			// remove wrap p tag
			$content = preg_replace( '/^<p>(.*)<\/p>$/', '$1', $content );
			$content = balanceTags( $content );

			echo balanceTags( $builder->do_shortcode_admin( $content, false, true ) );
		} else {
			echo '';
		}

		exit;
	}

	/**
	 * Show WR PageBuilder content for Frontend post
	 *
	 * @param string $content
	 * @return string
	 */
	function pagebuilder_to_frontend( $content ) {
		global $post;

		// Get what tab (Classic - Pagebuilder) is active when Save content of this post
		$wr_page_active_tab = get_post_meta( $post->ID, '_wr_page_active_tab', true );

		$wr_deactivate_pb = get_post_meta( $post->ID, '_wr_deactivate_pb', true );

		// Check password protected in post
		$allow_show_post = false;
		if ( 'publish' == $post->post_status && empty( $post->post_password ) ) {
			$allow_show_post = true;
		}

		// if Pagebuilder is active when save and pagebuilder is not deactivate on this post
		if ( $wr_page_active_tab && empty( $wr_deactivate_pb ) && $allow_show_post == true ) {
			$wr_pagebuilder_content = get_post_meta( $post->ID, '_wr_page_builder_content', true );
			if ( ! empty( $wr_pagebuilder_content ) ) {
				// remove placeholder text which was inserted to &lt; and &gt;
				$wr_pagebuilder_content = WR_Pb_Utils_Placeholder::remove_placeholder( $wr_pagebuilder_content, 'wrapper_append', '' );

				$wr_pagebuilder_content = preg_replace_callback(
						'/\[wr_widget\s+([A-Za-z0-9_-]+=\"[^"\']*\"\s*)*\s*\](.*)\[\/wr_widget\]/Us', array( 'WR_Pb_Helper_Shortcode', 'widget_content' ), $wr_pagebuilder_content
				);

				$content = $wr_pagebuilder_content;
			}
		}

		return $content;
	}

	/**
	 * Get output html of pagebuilder content
	 */
	function get_html_content() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;

		$content = $_POST['content'];
		$content = stripslashes( $content );
		$content = WR_Pb_Helper_Shortcode::doshortcode_content( $content );

		if ( ! empty( $content ) ) {
			echo "<div class='jsn-bootstrap3'>" . $content . '</div>';
		}
		exit;
	}

	/**
	 * Get media file name
	 *
	 * @param array $file
	 * @return array
	 */
	function media_file_name( $file ) {
		$file_name = iconv( 'utf-8', 'ascii//TRANSLIG//IGNORE', $file['name'] );
		if ( $file_name ) {
			$file['name'] = $file_name;
		}
		return $file;
	}

	/**
	 * Check condition to load WR PageBuilder content & assets.
	 *
	 * @return  boolean
	 */
	function check_support( $has_editor = false ) {
		global $pagenow, $post;

		if ( 'post.php' == $pagenow || 'post-new.php' == $pagenow || 'widgets.php' == $pagenow ) {
			if ( 'widgets.php' != $pagenow && ! empty( $post->ID ) ) {
				// Check if WR PageBuilder is enabled for this post type
				$settings  = WR_Pb_Product_Plugin::wr_pb_settings_options();
				$post_type = get_post_type( $post->ID );

				// Only want to check whether has Editor or not
				if ( $has_editor ) {
					return post_type_supports( $post_type, 'editor' );
				}

				// Whether PageBuilder is enable for this post type or not
				if ( is_array( $settings['wr_pb_settings_enable_for'] ) ) {
					if ( isset( $settings['wr_pb_settings_enable_for'][ $post_type ] ) ) {
						return ( 'enable' == $settings['wr_pb_settings_enable_for'][ $post_type ] );
					} else {
						return post_type_supports( $post_type, 'editor' );
					}
				} elseif ( 'enable' == $settings['wr_pb_settings_enable_for'] ) {
					return post_type_supports( $post_type, 'editor' );
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * Method to preload Elements list popup HTML
	 *
	 * @return void
	 */
	function load_elements_list() {
		if ( $this->check_support() ) {
			ob_start();
			include WR_PB_TPL_PATH . '/select-elements.php';
			ob_flush();
		}
	}

	/**
	 * Load necessary assets.
	 *
	 * @return  void
	 */
	function load_assets() {
		if ( $this->check_support( 'has_editor' ) ) {
			// Load styles
			WR_Pb_Helper_Functions::enqueue_styles();

			// Load scripts
			WR_Pb_Helper_Functions::enqueue_scripts();

			$scripts = array( 'wr-pb-jquery-select2-js', 'wr-pb-addpanel-js', 'wr-pb-jquery-resize-js', 'wr-pb-joomlashine-modalresize-js', 'wr-pb-layout-js', 'wr-pb-placeholder', 'wr-pb-tinymce-btn' );
			WR_Pb_Init_Assets::load( apply_filters( 'wr_pb_assets_enqueue_admin', $scripts ) );

			WR_Pb_Helper_Functions::enqueue_scripts_end();
		}
	}

	/**
	 * Register pagebuilder widget assets
	 *
	 * @return void
	 */
	function widget_register_assets() {
		global $pagenow;

		if ( $pagenow == 'widgets.php' ) {
			// enqueue admin script
			if ( function_exists( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			} else {
				wp_enqueue_style( 'thickbox' );
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_script( 'thickbox' );
			}
			$this->load_assets();
			WR_Pb_Init_Assets::load( 'wr-pb-handlesetting-js' );
			WR_Pb_Init_Assets::load( 'wr-pb-jquery-fancybox-js' );
			WR_Pb_Init_Assets::load( 'wr-pb-widget-js' );
		}
	}

	/**
	 * Add Inno Button to Classic Editor
	 *
	 * @param array $context
	 * @return array
	 */
	function add_page_element_button( $context ) {
		$icon_url = WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/wr-pgbldr-icon-black.png';
		$context .= '<a title="' . __( 'Add Page Element', WR_PBL ) . '" class="button" id="wr_pb_button" href="#"><i class="mce-ico mce-i-none" style="background-image: url(\'' . $icon_url . '\')"></i>' . __( 'Add Page Element', WR_PBL ) . '</a>';

		return $context;
	}

	function tiny_mce_before_init( $init ) {
		$init['setup'] = <<<JS
[function(ed) {
    ed.on('blur', function(ed) {
        tinyMCE.triggerSave();
		jQuery('.wr_pb_editor').first().trigger('change');
    });
}][0]
JS;

		return $init;
	}

	/**
	 * Gravatar : use default avatar, don't request from gravatar server
	 *
	 * @param type $image
	 * @param type $params
	 * @param type $item_id
	 * @param type $avatar_dir
	 * @param type $css_id
	 * @param type $html_width
	 * @param type $html_height
	 * @param type $avatar_folder_url
	 * @param type $avatar_folder_dir
	 * @return type
	 */
	function remove_gravatar( $image, $params, $item_id, $avatar_dir, $css_id, $html_width, $html_height, $avatar_folder_url, $avatar_folder_dir ) {

		$default = WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/default_avatar.png';

		if ( $image && strpos( $image, 'gravatar.com' ) ) {

			return '<img src="' . $default . '" alt="avatar" class="avatar" ' . $html_width . $html_height . ' />';
		} else {
			return $image;
		}
	}

	/**
	 * Gravatar : use default avatar
	 *
	 * @param type $avatar
	 * @param type $id_or_email
	 * @param type $size
	 * @param string $default
	 * @return type
	 */
	function get_gravatar( $avatar, $id_or_email, $size, $default ) {
		$default = WR_Pb_Helper_Functions::path( 'assets/woorockets' ) . '/images/default_avatar.png';
		return '<img src="' . $default . '" alt="avatar" class="avatar" width="60" height="60" />';
	}

	/**
	 * Add admin body class
	 *
	 * @param string $classes
	 * @return string
	 */
	function admin_bodyclass( $classes ) {
		$classes .= ' jsn-master';
		if ( isset($_GET['wr_load_modal'] ) AND isset( $_GET['wr_modal_type']) ) {
			$classes .= ' contentpane';
		}
		return $classes;
	}

	/**
	 * Get image size
	 *
	 * @param array $post_request
	 * @return string
	 */
	function get_image_size( $post_request ) {
		$response  = '';
		$image_url = $post_request['image_url'];

		if ( $image_url ) {
			$image_id   = WR_Pb_Helper_Functions::get_image_id( $image_url );
			$attachment = wp_prepare_attachment_for_js( $image_id );
			if ( $attachment['sizes'] ) {
				$sizes		       = $attachment['sizes'];
				$attachment['sizes'] = null;
				foreach ( $sizes as $key => $item ) {
					$item['total_size']	= $item['height'] + $item['width'];
					$attachment['sizes'][ucfirst( $key )] = $item;
				}
			}
			$response = json_encode( $attachment );
		}

		return $response;
	}

	/**
	 * Filter frontend body class
	 *
	 * @param array $classes
	 * @return array
	 */
	function wp_bodyclass( $classes ) {
		$classes[] = 'jsn-master';
		return $classes;
	}

	/**
	 * Update post view in frontend
	 *
	 * @global type $post
	 * @return type
	 */
	function post_view() {
		global $post;
		if ( ! isset($post ) || ! is_object( $post ) )
		return;
		if ( is_single( $post->ID ) ) {
			WR_Pb_Helper_Functions::set_postview( $post->ID );
		}
	}

	/**
	 * Add custom HTML code after title in Post editing page
	 *
	 * @global type $post
	 */
	function hook_after_title() {
		global $post;
		if ( $this->check_support() ) {
			$wr_pagebuilder_content = get_post_meta( $post->ID, '_wr_page_builder_content', true );

			// Get active tab
			$wr_page_active_tab = get_post_meta( $post->ID, '_wr_page_active_tab', true );
			$tab_active         = isset( $wr_page_active_tab ) ? intval( $wr_page_active_tab ) : ( ! empty( $wr_pagebuilder_content ) ? 1 : 0 );

			// Deactivate pagebuilder
			$wr_deactivate_pb = get_post_meta( $post->ID, '_wr_deactivate_pb', true );
			$wr_deactivate_pb = isset( $wr_deactivate_pb ) ? intval( $wr_deactivate_pb ) : 0;

			$wrapper_style = $tab_active ? 'style="display:none"' : '';

			// Get array list of dismissed pointers for current user and convert it to array
			$dismissed_pointers = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

			// If this post has not used PageBuilder
			global $post;
			$not_used_pb = isset( $post ) && ( 1 !== get_post_meta( $post->ID, '_wr_page_active_tab' ) );
			
			// Check if our pointer is not among dismissed ones
			$translate = NULL;
			$current_lang = get_option( 'WPLANG' );
			
			if( $current_lang && !preg_match("/^en/", $current_lang) && $not_used_pb && !in_array( 'wr_pb_settings_pointer_translate', $dismissed_pointers ) ){
				
				$language = 'your language';
				if (file_exists(ABSPATH . 'wp-admin/includes/translation-install.php')) {
					require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
					$translations = wp_get_available_translations();
					$language = $translations[$current_lang]['native_name'];
				}
				
				$translate = '
					<div id="translation-transifex">
						<p>
							<a target="_blank" href="http://goo.gl/Sg2owo">'.sprintf(__('Help translate WR PageBuilder to %s.') , $language).'</a>
							<span id="wr-close"></span>
						</p>
					</div>
					<script type="text/javascript">
						jQuery(document).ready( function($) {
							$("#wr-close").click(function(e){
								$.post( ajaxurl, {
										pointer: "wr_pb_settings_pointer_translate", // pointer ID
										action: "dismiss-wp-pointer"
								});
								$("#translation-transifex").hide();
							})
						});
					</script>
				';
			}

			echo '
				<input id="wr_active_tab" name="wr_active_tab" value="' . $tab_active . '" type="hidden">
				<input id="wr_deactivate_pb" name="wr_deactivate_pb" value="' . $wr_deactivate_pb . '" type="hidden">
				<div class="jsn-bootstrap3 wr-editor-wrapper" ' . $wrapper_style . '>
					<ul class="nav nav-tabs" id="wr_editor_tabs">
						<li class="active"><a href="#wr_editor_tab1">' . __( 'Classic Editor', WR_PBL ) . '</a></li>
						<li><a href="#wr_editor_tab2">' . __( 'WR PageBuilder', WR_PBL ) . '</a></li>
					</ul>
					'.$translate.'
					<div class="tab-content wr-editor-tab-content">
						<div class="tab-pane active" id="wr_editor_tab1">';
		}
	}

	/**
	 * Add custom HTML code after text editor in Post editing page
	 *
	 * @global type $post
	 */
	function hook_after_editor() {
		if ( $this->check_support() ) {
			echo '</div><div class="tab-pane" id="wr_editor_tab2"><div id="wr_before_pagebuilder"></div></div></div></div>';
		} else {
			echo '<div class="tab-pane" id="wr_editor_tab2" style="display:none">'
			. '<div id="wr_before_pagebuilder">'
			. '<div class="jsn-section-content jsn-style-light" id="form-design-content">'
			. '<div class="wr-pb-form-container jsn-layout"></div>'
			. '</div>'
			. '</div>'
			. '</div>';
		}
	}

	/**
	 * Compress asset files
	 */
	function enqueue_compressed_assets() {
		if ( ! empty ( $_SESSION['wr-pb-assets-frontend'] ) ) {
			global $post;
			if ( empty ( $post ) )
			exit;
			$wr_pb_settings_cache = get_option( 'wr_pb_settings_cache', 'enable' );
			if ( $wr_pb_settings_cache != 'enable' ) {
				exit;
			}
			$contents_of_type = array();
			$this_session     = $_SESSION['wr-pb-assets-frontend'][$post->ID];
			// Get content of assets file from shortcode directories
			foreach ( $this_session as $type => $list ) {
				$contents_of_type[$type] = array();
				foreach ( $list as $path => $modified_time ) {
					$fp = @fopen( $path, 'r' );
					if ( $fp ) {
						$contents_of_type[$type][$path] = fread( $fp, filesize( $path ) );
						fclose( $fp );
					}
				}
			}
			// Write content of css, js to 2 seperate files
			$cache_dir = WR_Pb_Helper_Functions::get_wp_upload_folder( '/igcache/pagebuilder' );
			foreach ( $contents_of_type as $type => $list ) {
				$handle_info   = $this_session[$type];
				$hash_name     = md5( implode( ',', array_keys( $list ) ) );
				$file_name     = "$hash_name.$type";
				$file_to_write = "$cache_dir/$file_name";

				// check stored data
				$store = WR_Pb_Helper_Functions::compression_data_store( $handle_info, $file_name );

				if ( $store[0] == 'exist' ) {
					$file_name     = $store[1];
					$file_to_write = "$cache_dir/$file_name";
				} else {
					$fp = fopen( $file_to_write, 'w' );
					$handle_contents = implode( "\n/*------------------------------------------------------------*/\n", $list );
					fwrite( $fp, $handle_contents );
					fclose( $fp );
				}

				// Enqueue script/style to footer of page
				if ( file_exists( $file_to_write ) ) {
					$function = ( $type == 'css' ) ? 'wp_enqueue_style' : 'wp_enqueue_script';
					$function( $file_name, WR_Pb_Helper_Functions::get_wp_upload_url( '/igcache/pagebuilder' ) . "/$file_name" );
				}
			}
		}
	}

	/**
	 * Clear cache files
	 *
	 * @return type
	 */
	function igpb_clear_cache() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;

		$delete = WR_Pb_Utils_Common::remove_cache_folder();

		echo balanceTags( $delete ? __( '<i class="icon-checkmark"></i>', WR_PBL ) : __( "Fail. Can't delete cache folder", WR_PBL ) );

		exit;
	}

	/*
	 * Function to process when submit report bug
	 */
	function submit_report_bug() {
		if ( ! isset( $_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) ) return;

		$data = isset( $_POST['data'] ) ? $_POST['data'] : '';
		$result = false;
		if ( is_array( $data ) && ! empty( $data ) ) {
			$arr_params = array();
			foreach ( $data as $i => $item ) {
				$arr_params[$item['name']] = $item['value'];
			}
			extract( $arr_params );

			// Configure for email received report bug
			$email   = 'woorockets@gmail.com';
			$subject = __( 'WR PageBuilder Bug Report', WR_PBL );
			$message = "
				<b>- Description:</b> {$wr_description}
			<br />
				<b>- Browser:</b> {$wr_browser}
			<br />
				<b>- Attachment path:</b> {$wr_attachment}
			<br />
				</b>- URL:</b> {$wr_url}
			";
			$headers = array('Content-Type: text/html; charset=UTF-8');
			$attachment_path = get_attached_file( $wr_attachment_id );

			$attachment = ( ! empty( $attachment_path ) ) ? array( $attachment_path ) : null;
			if ( $email ) {
				$result = wp_mail( $email, $subject, $message, $headers, $attachment );
			}
		}

		if ( $result == true ) {
			echo '1';
		} else {
			echo '0';
		}
		exit;
	}

	/**
	 * Save premade layout to file
	 *
	 * @return type
	 */
	function save_layout() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;

		$layout_name    = $_POST['layout_name'];
		$layout_content = stripslashes( $_POST['layout_content'] );

		$error = WR_Pb_Helper_Layout::save_premade_layouts( $layout_name, $layout_content );

		echo intval( $error ) ? 'error' : 'success';

		exit;
	}

	/**
	 * Upload premade layout to file
	 *
	 * @return type
	 */
	function upload_layout() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;

		$status = 0;
		if ( ! empty ( $_FILES ) ) {
			$fileinfo = $_FILES['file'];
			$file     = $fileinfo['tmp_name'];
			$tmp_file = 'tmp-layout-' . time();
			$dest     = WR_Pb_Helper_Functions::get_wp_upload_folder( '/wr-pb-layout/' . $tmp_file );
			if ( $fileinfo['type'] == 'application/octet-stream' ) {
				WP_Filesystem();
				$unzipfile = unzip_file( $file, $dest );
				if ( $unzipfile ) {
					// explore extracted folder to get provider info
					$status = WR_Pb_Helper_Layout::import( $dest );
				}
				// remove zip file
				unlink( $file );
			}
			WR_Pb_Utils_Common::recursive_delete( $dest );
		}
		echo intval( $status );

		exit;
	}

	/**
	 * Get list of Page template
	 *
	 * @return type
	 */
	function reload_layouts_box() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;

		include WR_PB_TPL_PATH . '/layout/list.php';

		exit;
	}

	/**
	 * Delete group layout
	 *
	 * @return html
	 */
	function delete_layouts_group() {
		if ( ! isset( $_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) ) {
			return;
		}

		$group  = sanitize_key( $_POST['group'] );
		$delete = WR_Pb_Helper_Layout::remove_group( $group );

		include WR_PB_TPL_PATH . '/layout/list.php';

		exit;
	}

	/**
	 * Delete layout
	 *
	 * @return int
	 */
	function delete_layout() {
		if ( ! isset( $_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) ) {
			return;
		}

		$group  = sanitize_key( $_POST['group'] );
		$layout = urlencode( $_POST['layout'] );
		$delete = WR_Pb_Helper_Layout::remove_layout( $group, $layout );

		echo esc_html( $delete ? 1 : 0 );

		exit;
	}

	/**
	 * Save custom CSS information: files, code
	 *
	 * @return void
	 */
	function save_css_custom() {
		if ( ! isset( $_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) ) {
			return;
		}

		$post_id = esc_sql( $_POST['post_id'] );
		// save custom css code & files
		WR_Pb_Helper_Functions::custom_css( $post_id, 'css_files', 'put', esc_sql( $_POST['css_files'] ) );
		WR_Pb_Helper_Functions::custom_css( $post_id, 'css_custom', 'put', esc_textarea( $_POST['custom_css'] ) );

		exit;
	}

	/**
	 * Get same type elements in a text
	 *
	 * @return type
	 */
	function get_same_elements() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;
		$shortcode_name  = $_POST['shortcode_name'];
		$content         = $_POST['content'];

		// replace opening tag
		$regex   = '\\[' // Opening bracket
		. '(\\[?)' // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
		. "($shortcode_name)" // 2: Shortcode name
		. '(?![\\w-])' // Not followed by word character or hyphen
		. '(' // 3: Unroll the loop: Inside the opening shortcode tag
		. '[^\\]\\/]*' // Not a closing bracket or forward slash
		. '(?:'
		. '\\/(?!\\])' // A forward slash not followed by a closing bracket
		. '[^\\]\\/]*' // Not a closing bracket or forward slash
		. ')*?'
		. ')'
		. '(?:'
		. '(\\/)' // 4: Self closing tag ...
		. '\\]' // ... and closing bracket
		. '|'
		. '\\]' // Closing bracket
		. ')'
		. '(\\]?)'; // 6: Optional second closing brocket for escaping shortcodes: [[tag]]

		preg_match_all('#' . $regex . '#', $content, $out, PREG_SET_ORDER);

		$select_options   = array();
		$options          = array();

		$k = 0;
		foreach ( $out as $el ) {
			$extracted_params  = WR_Pb_Helper_Shortcode::extract_params($el[0]);
			if ( $extracted_params ) {
				$k ++;
				$el_title   = $extracted_params['el_title'] ? $extracted_params['el_title'] : __( '(Untitled)', WR_PBL );
				// Append unique number to ensure array key is unique
				// for sorting purpose.
				if ( isset( $options[$el_title] ) ) {
					$options[$el_title . "___" . $k ] = $el[0];
				}else{
					$options[$el_title] = $el[0];
				}

			}
		}

		if ( count( $options ) ) {
			// Sort the options by title
			ksort( $options );

			foreach ( $options as $title => $value ) {
				if ( stripos( $value, '#_EDITTED' ) === false ) {
					if ( strpos( $title, "___" ) !== false ) {
						$title = substr( $title, 0, strpos( $title, "___" ) );
					}
					$select_options[]  = "<option value='" . $value . "'>" . $title . '</option>';
				}
			}

		}

		// Print out the options HTML for select box
		echo implode('', $select_options);
		exit;
	}

	/**
	 * Merge new style params to existed shortcode content
	 *
	 * @return type
	 */
	function merge_style_params() {
		if ( ! isset($_POST[WR_NONCE] ) || ! wp_verify_nonce( $_POST[WR_NONCE], WR_NONCE ) )
		return;
		$shortcode_name  = $_POST['shortcode_name'];
		$structure       = str_replace( "\\", "", $_POST['content'] );
		$alter_structure = str_replace( "\\", "", $_POST['new_style_content'] );

		// Extract params of current element
		$params    = WR_Pb_Helper_Shortcode::extract_params( $structure, $shortcode_name );

		// Extract styling params of copied element
		$alter_params  = WR_Pb_Helper_Shortcode::get_styling_atts( $shortcode_name , $alter_structure );

		// Alter params of current element by copied element's params
		if ( count( $alter_params ) ) {
			foreach ( $alter_params as $k => $v ) {
				$params[$k]    = $v;
			}
		}

		$_shortcode_content = '';
		// Exclude shortcode_content from param list
		if ( isset ( $params['_shortcode_content'] ) ) {
			$_shortcode_content  = $params['_shortcode_content'];
			unset ($params['_shortcode_content']);
		}

		$new_shortcode_structure = WR_Pb_Helper_Shortcode::join_params($params, $shortcode_name, $_shortcode_content );
		// Print out new shortcode structure.
		echo $new_shortcode_structure;
		exit;
	}

	/**
	 * Echo custom css code, link custom css files
	 */
	function enqueue_custom_css() {
		global $post;
		if ( ! isset( $post ) || ! is_object( $post ) ) {
			return;
		}

		$wr_deactivate_pb = get_post_meta( $post->ID, '_wr_deactivate_pb', true );

		// if not deactivate pagebuilder on this post
		if ( empty( $wr_deactivate_pb ) ) {

			$custom_css_data = WR_Pb_Helper_Functions::custom_css_data( isset ( $post->ID ) ? $post->ID : NULL );
			extract( $custom_css_data );

			$css_files = stripslashes( $css_files );

			if ( ! empty( $css_files ) ) {
				$css_files = json_decode( $css_files );
				$data      = $css_files->data;

				foreach ( $data as $idx => $file_info ) {
					$checked = $file_info->checked;
					$url     = $file_info->url;

					// if file is checked to load, enqueue it
					if ( $checked ) {
						wp_enqueue_style( 'wr-pb-custom-file-' . $post->ID . '-' . $idx, $url );
					}
				}
			}
		}
	}

	/**
	 * Print style on front-end
	 */
	function print_frontend_styles() {
		global $post;
		if ( ! isset( $post ) || ! is_object( $post ) ) {
			return;
		}

		$wr_deactivate_pb = get_post_meta( $post->ID, '_wr_deactivate_pb', true );

		// if not deactivate pagebuilder on this post
		if ( empty( $wr_deactivate_pb ) ) {

			$custom_css_data = WR_Pb_Helper_Functions::custom_css_data( isset ( $post->ID ) ? $post->ID : NULL );
			extract( $custom_css_data );

			$css_custom = html_entity_decode( stripslashes( $css_custom ) );

			echo balanceTags( "<style id='wr-pb-custom-{$post->ID}-css'>\n$css_custom\n</style>\n" );
		}
	}
}