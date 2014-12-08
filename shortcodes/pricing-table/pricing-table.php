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

if ( ! class_exists( 'WR_Pricing_Table' ) ) :

/**
 * Pricing table element for WR PageBuilder.
 *
 * @since  1.0.0
 */
class WR_Pricing_Table extends WR_Pb_Shortcode_Parent {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	// Predefined Attributes
	static $attributes = array(
		'max_domains' => array(
			'title' => 'Max Domains',
			'value' => array( '1', '5', '20' ),
			'type'  => 'text',
		),
		'storage'     => array(
			'title' => 'Storage',
			'value' => array( '100 MB', '500 MB', '2 TB' ),
			'type'  => 'text',
		),
		'ssl_support' => array(
			'title' => 'SSL Support',
			'value' => array( 'no', 'yes', 'yes' ),
			'type'  => 'checkbox',
		),
	);

	// Store index of pricing option/ pricing attribute
	static $index = 0;

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
		$this->config['name']             = __( 'Pricing Table', WR_PBL );
		$this->config['cat']              = __( 'Extra', WR_PBL );
		$this->config['icon']             = 'wr-icon-pricing-table';
		$this->config['description']      = __( 'Pricing table with flexible settings', WR_PBL );
		$this->config['has_subshortcode'] = 'WR_Item_' . str_replace( 'WR_', '', __CLASS__ );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'default_content'  => __( 'Pricing Table', WR_PBL ),
			'data-modal-title' => __( 'Pricing Table', WR_PBL ),

			'admin_assets' => array(
				// Shortcode style
				'pricing-table.css',
				'pricing_table.js',
				'wr-linktype.js',
				'wr-popover.js',
				'item_pricing_table.js',
			),

			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',

				// Font IcoMoon
				'wr-pb-font-icomoon-css',

				// Fancy Box
				'wr-pb-jquery-fancybox-css',
				'wr-pb-jquery-fancybox-js',

				// Shortcode style
				'pricing-table_frontend.css',
				'pricing_table_frontend.js',
			),
		);

		// Use Ajax to speed up element settings modal loading speed
		$this->config['edit_using_ajax'] = true;

		add_action( 'wr_pb_modal_footer', array( &$this, '_modal_footer' ) );
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
					'name'          => __( 'Attributes', WR_PBL ),
					'id'            => 'prtbl_attr',
					'type'          => 'group',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'] . '_Attr',
					'sub_items'     => array(
						WR_Pricing_Table::get_option( 'max_domains' ),
						WR_Pricing_Table::get_option( 'storage' ),
						WR_Pricing_Table::get_option( 'ssl_support' ),
					),
					'overwrite_shortcode_data' => false,
				),
				array(
					'name'                     => __( 'Options', WR_PBL ),
					'id'                       => 'prtbl_items',
					'type'                     => 'group',
					'no_title'                 => __( '(Untitled)', WR_PBL ),
					'shortcode'                => ucfirst( __CLASS__ ),
					'sub_item_type'            => $this->config['has_subshortcode'],
					'sub_items'                => array(
						array( 'std' => '', 'prtbl_item_title' => 'Free', 'prtbl_item_desc' => 'Free', 'prtbl_item_currency' => '$', 'prtbl_item_price' => '0', 'prtbl_item_time' => ' / month' ),
						array( 'std' => '', 'prtbl_item_title' => 'Standard', 'prtbl_item_desc' => 'Standard', 'prtbl_item_currency' => '$', 'prtbl_item_price' => '69', 'prtbl_item_feature' => 'yes', 'prtbl_item_time' => ' / month' ),
						array( 'std' => '', 'prtbl_item_title' => 'Premium', 'prtbl_item_desc' => 'Premium', 'prtbl_item_currency' => '$', 'prtbl_item_price' => '99', 'prtbl_item_time' => ' / month' ),
					),
					'overwrite_shortcode_data' => false,
				),
			),
			'styling' => array(
				array(
					'type' => 'preview',
				),
				array(
					'name'            => __( 'Elements', WR_PBL ),
					'id'              => 'prtbl_elements',
					'type'            => 'checkbox',
					'class'           => 'jsn-column-item checkbox',
					'container_class' => 'jsn-columns-container jsn-columns-count-two',
					'std'             => 'title__#__button__#__attributes',
					'options'         => array(
						'title'       => __( 'Title', WR_PBL ),
						'description' => __( 'Description', WR_PBL ),
						'image'       => __( 'Image', WR_PBL ),
						'attributes'  => __( 'Attributes', WR_PBL ),
						'price'       => __( 'Price', WR_PBL ),
						'button'      => __( 'Button', WR_PBL )
					),
					'tooltip'         => __( 'Elements to display on pricing table', WR_PBL )
				),
				WR_Pb_Helper_Type::get_apprearing_animations(),
				WR_Pb_Helper_Type::get_animation_speeds(),
			)
		);
	}

	/**
	 * Function to sync sub-shortcode content become sub-shortcode array
	 *
	 * @param array $arr_shortcodes
	 */
	private function sync_sub_content( $sub_shortcode = '' ) {
		$arr_shortcodes = array();
		if ( ! $sub_shortcode )
			return;

		// Convert to sub-shortcode array
		$arr_sub_shortcode = $arr_values = array();
		$pattern = '\\[(\\[?)(wr_item_pricing_table_attr)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
		preg_match_all( "/$pattern/s", $sub_shortcode, $matches );
		$arr_sub_shortcode['WR_Item_Pricing_Table_Attr'] = $matches[0];


		if ( isset( $arr_sub_shortcode['WR_Item_Pricing_Table_Attr'] ) && is_array( $arr_sub_shortcode['WR_Item_Pricing_Table_Attr'] ) ) {
			//$str_pr_tbl_shortcode = implode( '', $arr_sub_shortcode['WR_Item_Pricing_Table_Attr'] );
			$arr_shortcodes['wr_item_pricing_table_attr'] = implode( '', $arr_sub_shortcode['WR_Item_Pricing_Table_Attr'] );
		}

		$pattern = '\\[(\\[?)(wr_item_pricing_table)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
		preg_match_all( "/$pattern/s", $sub_shortcode, $matches );
		$arr_sub_shortcode['WR_Item_Pricing_Table']      = $matches[0];

		if ( isset( $arr_sub_shortcode['WR_Item_Pricing_Table'] ) && is_array( $arr_sub_shortcode['WR_Item_Pricing_Table'] ) ) {
			foreach ( $arr_sub_shortcode['WR_Item_Pricing_Table'] as $i => $item ) {
				$pattern = '\\[(\\[?)(wr_item_pricing_table_attr_value)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
				preg_match_all( "/$pattern/s", $item, $matches );
				$arr_values['WR_Item_Pricing_Table_Attr_Value'] = $matches[0];
				$count = count( $arr_values['WR_Item_Pricing_Table_Attr_Value'] );
				$_item = preg_replace( "/$pattern/s", '<!--wr-replace-flag-->', $item );

				// Simulate mechanism process sub-shortcode in modal template
				$sub_sc_data = WR_Item_Pricing_Table::_sub_items_filter( $arr_values, 'wr_item_pricing_table', $arr_sub_shortcode['WR_Item_Pricing_Table_Attr'] );
				if ( isset( $sub_sc_data['WR_Item_Pricing_Table_Attr_Value'] ) && is_array( $sub_sc_data['WR_Item_Pricing_Table_Attr_Value'] ) ) {
					$str_pr_tbl_shortcode = str_replace( str_repeat( '<!--wr-replace-flag-->', $count ), implode( '', $sub_sc_data['WR_Item_Pricing_Table_Attr_Value'] ), $_item );
				}
				$str_pr_tbl_shortcode = str_replace( '"prtbl_item_attr_value', '" prtbl_item_attr_value', $str_pr_tbl_shortcode );
				$arr_shortcodes['wr_item_pricing_table'][] = $str_pr_tbl_shortcode;
			}
		}

		return $arr_shortcodes;
	}

	private function check_field_allow( $allow = '', $pattern_scan = '', $arr_allows, $pr_tbl_col_value_html = '' ) {
		if ( ! $allow || ! $pattern_scan || ! is_array( $arr_allows ) || ! $pr_tbl_col_value_html )
			return $pr_tbl_col_value_html;

		if ( in_array( $allow, $arr_allows ) ) {
			$pr_tbl_col_value_html = str_replace( "[$pattern_scan]", '', $pr_tbl_col_value_html );
			$pr_tbl_col_value_html = str_replace( "[/$pattern_scan]", '', $pr_tbl_col_value_html );
		} else {
			$pattern     = '\\[(\\[?)('. $pattern_scan .')(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
			$pr_tbl_col_value_html = preg_replace( "/$pattern/s", '', $pr_tbl_col_value_html );
		}
		return $pr_tbl_col_value_html;
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
		$html_element       = '';
		$arr_sub_shortcodes = self::sync_sub_content( $content );
		$arr_params         = ( shortcode_atts( $this->config['params'], $atts ) );
		extract( $arr_params );

		$arr_elements          = explode( '__#__', $prtbl_elements );

		// Build html for cols label.
		$pr_tbl_label_html = '<div class="wr-prtbl-cols first">';

		// Append blank header
		$header_ = '<div class="wr-prtbl-title">';
		if ( in_array( 'image', $arr_elements ) ) {
			$header_ .= '<div class="wr-prtbl-image"></div>';
		}
		$header_ .= '<h3>&nbsp;</h3></div>';

		if ( in_array( 'price', $arr_elements ) || in_array( 'description', $arr_elements ) ) {
			$header_ .= '<div class="wr-prtbl-meta">';

			// append blank price
			if ( in_array( 'price', $arr_elements ) ) {
				$header_ .= '<div class="wr-prtbl-price">&nbsp;</div>';
			}

			// append blank price
			if ( in_array( 'description', $arr_elements ) ) {
				$header_ .= '<p class="wr-prtbl-desc">&nbsp;</p>';
			}
			$header_ .= '</div>';
		}

		// Process deactive pricing table item attribute
		set_transient( 'pricingtable_attrs' , '' );
		$pattern = '\\[(\\[?)(wr_item_pricing_table_attr)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
		preg_match_all( "/$pattern/s", $arr_sub_shortcodes['wr_item_pricing_table_attr'], $matches );
		$arr_prtbl_atts = $matches[0];
		$arr_disable_el = array();
		foreach ( $arr_prtbl_atts as $i => $att ) {
			if ( ! empty( $att ) ) {
				$att_extract_params = WR_Pb_Helper_Shortcode::extract_params( $att );
				if ( isset( $att_extract_params['disabled_el'] ) && $att_extract_params['disabled_el'] == 'yes' && isset( $att_extract_params['prtbl_item_attr_id'] ) ) {
					$arr_disable_el[] = $att_extract_params['prtbl_item_attr_id'];
				}
			}
		}
		set_transient( 'pricingtable_attrs' , json_encode( $arr_disable_el ) );

		$pr_tbl_label_html .= sprintf( '<div class="wr-prtbl-header">%s</div>', balanceTags( $header_ ) );
		if ( isset( $arr_sub_shortcodes['wr_item_pricing_table_attr'] ) && ! empty( $arr_sub_shortcodes['wr_item_pricing_table_attr'] ) ) {
			$pr_tbl_label_html .= '<ul class="wr-prtbl-features">';
			$pr_tbl_label_html .= do_shortcode( $arr_sub_shortcodes['wr_item_pricing_table_attr'] );
			$pr_tbl_label_html .= '</ul>';
		}
		$pr_tbl_label_html .= '<div class="wr-prtbl-footer"></div>';
		$pr_tbl_label_html .= '</div>';

		// Build html for cols value.
		$pr_tbl_col_value_html = '';
		if ( isset( $arr_sub_shortcodes['wr_item_pricing_table'] ) && ! empty( $arr_sub_shortcodes['wr_item_pricing_table'] ) ) {
			$pr_tbl_col_value_html = do_shortcode( implode( '', $arr_sub_shortcodes['wr_item_pricing_table'] ) );

			$pr_tbl_col_value_html = $this->check_field_allow( 'title', 'prtbl_item_title', $arr_elements, $pr_tbl_col_value_html );
			$pr_tbl_col_value_html = $this->check_field_allow( 'description', 'prtbl_item_desc', $arr_elements, $pr_tbl_col_value_html );
			$pr_tbl_col_value_html = $this->check_field_allow( 'image', 'prtbl_item_image', $arr_elements, $pr_tbl_col_value_html );
			$pr_tbl_col_value_html = $this->check_field_allow( 'button', 'prtbl_item_button', $arr_elements, $pr_tbl_col_value_html );
			$pr_tbl_col_value_html = $this->check_field_allow( 'price', 'prtbl_item_price', $arr_elements, $pr_tbl_col_value_html );

			if ( ! in_array( 'price', $arr_elements ) && ! in_array( 'description', $arr_elements ) ) {
				$pattern     = '\\[(\\[?)(prtbl_item_meta)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
				$pr_tbl_col_value_html = preg_replace( "/$pattern/s", '', $pr_tbl_col_value_html );
			} else {
				$pr_tbl_col_value_html = str_replace( "[prtbl_item_meta]", '', $pr_tbl_col_value_html );
				$pr_tbl_col_value_html = str_replace( "[/prtbl_item_meta]", '', $pr_tbl_col_value_html );
			}
		}
		$count_columns = isset( $arr_sub_shortcodes['wr_item_pricing_table'] ) ? count( $arr_sub_shortcodes['wr_item_pricing_table'] ) + 1 : 1;

		$html_element  = $pr_tbl_label_html . $pr_tbl_col_value_html;
		return $this->element_wrapper( $html_element, $arr_params, "table-$count_columns-col" );
	}

	/**
	 * Get shortcode parameters for Pricing Option
	 *
	 * @param string $attribute     The ID of attribute
	 * @param bool   $include_value Whether or not including Value parameter (true if call for WR_Item_Pricing_Table_Attr_Value)
	 *
	 * @return string
	 */
	static function get_option( $attribute, $include_value = false ) {
		// get all Predefined Attributes
		$attributes = WR_Pricing_Table::$attributes;

		// get index of current Option/Attribute
		$idx = WR_Pricing_Table::$index = WR_Pricing_Table::$index % 3;

		$title = isset ( $attributes[$attribute] ) ? ( isset ( $attributes[$attribute]['title'] ) ? $attributes[$attribute]['title'] : '' ) : '';
		$type  = isset ( $attributes[$attribute] ) ? ( isset ( $attributes[$attribute]['type'] ) ? $attributes[$attribute]['type'] : '' ) : '';
		if ( $include_value ) {
			$value = isset ( $attributes[$attribute] ) ? ( isset ( $attributes[$attribute]['value'][$idx] ) ? $attributes[$attribute]['value'][$idx] : '' ) : '';
		}

		$result = array(
			'std' => '', 'prtbl_item_attr_id' => $attribute, 'prtbl_item_attr_title' => $title, 'prtbl_item_attr_type' => $type,
		);
		if ( ! $include_value ) {
			$result['prtbl_item_attr_desc'] = $title;
		} else {
			$result['prtbl_item_attr_value'] = $result['prtbl_item_attr_desc'] = $value;
		}

		return $result;
	}

	/**
	 * Print Setting HTML of Pricing Item
	 */
	function _modal_footer( $shortcode ) {
		$pricing_item = new WR_Item_Pricing_Table();

	   if ( $shortcode == $this->config['shortcode'] ) {
		   // Create instance of sub shortcode
		   $instance = new $pricing_item->config['has_subshortcode'];
		   $instance->init_element();

		   // Get modal settings html
		   $settings_html = WR_Pb_Objects_Modal::get_shortcode_modal_settings( $instance->items, $instance->config['shortcode'] );

		   // Print html as template script
		   printf( "<script type='text/html' id='tmpl-wr-pb-hidden-setting'>\n%s\n</script>\n", balanceTags( $settings_html ) );
	   }
	}
}

endif;
