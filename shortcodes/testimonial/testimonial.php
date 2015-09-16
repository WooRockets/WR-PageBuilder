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

if ( ! class_exists( 'WR_Testimonial' ) ) :

/**
 * Testimonial element for WR PageBuilder.
 *
 * @since  1.0.0
 */
class WR_Testimonial extends WR_Pb_Shortcode_Parent {
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
		$this->config['name']             = __( 'Testimonial', WR_PBL );
		$this->config['cat']              = __( 'Extra', WR_PBL );
		$this->config['icon']             = 'wr-icon-testimonial';
		$this->config['has_subshortcode'] = 'WR_Item_' . str_replace( 'WR_', '', __CLASS__ );
		$this->config['description']      = __( 'Testimonial with flexible settings', WR_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'admin_assets' => array(
				// Shortcode initialization
				'wr-popover.js',
			),

			'frontend_assets' => array(
				// Bootstrap 3
				'wr-pb-bootstrap-css',
				'wr-pb-bootstrap-js',

				// Font IcoMoon
				'wr-pb-font-icomoon-css',

				// Shortcode style
				'testimonial_frontend.css',
				'testimonial_frontend.js'
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
			'content' => array(

				array(
					'id'            => 'testimonial_items',
					'type'          => 'group',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
						array( 'std' => '' ),
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
					'name'     => __( 'Items per Slide', WR_PBL ),
					'id'       => 'items_per_slide',
					'type'     => 'text_number',
					'std'      => '2',
					'class'    => 'input-mini',
					'validate' => 'number',
				),
				array(
					'name'            => __( 'Slider Elements', WR_PBL ),
					'id'              => 'slider_elements',
					'type'            => 'checkbox',
					'class'           => 'jsn-column-item checkbox',
					'container_class' => 'jsn-columns-container jsn-columns-count-two',
					'std'             => 'arrows__#__indicator',
					'options'         => array(
						'arrows'    => __( 'Arrows', WR_PBL ),
						'indicator' => __( 'Indicator', WR_PBL ),
					),
				),
				array(
					'name' => __( 'Content Elements', WR_PBL ),
					'id' => 'content_elements',
					'type' => 'items_list',
					'std'           => 'content__#__image__#__name__#__job_title__#__country__#__company',
					'options'       => array(
						'content'     => __( 'Feedback Content', WR_PBL ),
						'image'       => __( 'Avatar', WR_PBL ),
						'name'        => __( 'Client\'s Name', WR_PBL ),
						'job_title'   => __( 'Client\'s Position', WR_PBL ),
						'country'     => __( 'Country', WR_PBL ),
						'company'     => __( 'Company', WR_PBL ),
					),
					'options_type'  => 'checkbox',
					'popover_items' => array( 'image', 'content' ),
					'style'         => array( 'height' => '200px' ),
					'container_class' => 'unsortable content-elements',
				),
				// popup settings for Elements = Image
				array(
					'name'              => __( 'Container Style', WR_PBL ),
					'id'                => 'author_image_style',
					'type'              => 'select',
					'std'               => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_container_style() ),
					'options'           => WR_Pb_Helper_Type::get_container_style(),
					'container_class'   => 'hidden',
					'data_wrap_related' => 'image',
				),
				// popup settings for Elements = Content
				array(
					'name' => __( 'Length Limitation', WR_PBL ),
					'id' => 'content_length',
					'type' => array(
						array(
							'id' => 'content_count',
							'type' => 'text_number',
							'std' => '',
							'class' => 'input-mini',
							'options' => WR_Pb_Helper_Type::get_fonts(),
							'parent_class' => 'combo-item',
						),
						array(
							'id'           => 'content_type',
							'type'         => 'select',
							'class'        => 'input-medium',
							'std'          => 'words',
							'options'      => array(
								'words'      => __( 'Words', WR_PBL ),
								'characters' => __( 'Characters', WR_PBL )
							),
							'parent_class' => 'combo-item',
						),
					),
					'container_class'   => 'combo-group hidden',
					'data_wrap_related' => 'content',
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
		$arr_params     = shortcode_atts( $this->config['params'], $atts );
		extract( $arr_params );
		$random_id      = WR_Pb_Utils_Common::random_string();
		$testimonial_id = "testimonial_$random_id";

		$styles                = "style='width:100%'";
		$image_container_style = ( $author_image_style != 'no-styling' ) ? "{$author_image_style}" : '';
		$content_elements      = array_filter( explode( '__#__', $content_elements ) );

		$testimonial_indicators   = array();
		$testimonial_indicators[] = '<ol class="carousel-indicators">';

		$sub_shortcode       = do_shortcode( $content );
		$testimonial_content = array();
		$items               = explode( '<!--seperate-->', $sub_shortcode );
		$items               = array_filter( $items );
		$count_items         = count( $items );
		foreach ( $items as $idx => $item ) {
			$item = unserialize( $item );
			if ( $idx % $items_per_slide == 0 ) {
				$active = ( $idx == 0 ) ? 'active' : '';
				$testimonial_content[] = "<div class='item row $active'>";

				$active_li = ( $idx == 0 ) ? "class='active'" : '';
				$testimonial_indicators[] = "<li $active_li></li>";
			}

			$divide    = ( $count_items > $items_per_slide ) ? $items_per_slide : $count_items;
			$colmd     = 'col-md-' . 12 / $divide;
			$colsm     = 'col-sm-' . 12 / $divide;

			$item_html = "<div class='wr-testimonial-item $colmd $colsm'>";

			$testimonial_info = array();
			if ( in_array( 'content', $content_elements ) ) {
				$item_content                = WR_Pb_Helper_Shortcode::remove_autop( $item['testimonial_content'] );
				$item_content                = WR_Pb_Utils_Common::trim_content( $item_content, $content_count, $content_type );
				$testimonial_info['content'] = "<div class='wr-testimonial-box top'><div class='arrow'></div><div class='wr-testimonial-content'><p>" . $item_content . '</p></div></div>';
			}

			if ( isset ( $item['image_file'] ) && ! empty( $item['image_file'] ) ) {				
				$width = $attachment['sizes'][$image_size]['width'];
				$height = $attachment['sizes'][$image_size]['height'];
				$img = "<div class='wr-testimonial-avatar'><img width='{$width}' height='{$height}' class='$image_container_style' src='{$item['image_file']}' /></div>";
			} else {
				$img = '';
			}

			$testimonial_info['image']    = ( in_array( 'image', $content_elements ) ) ? $img : '';

			// Process company field
			if ( isset( $item['company'] ) && $item['company'] != '' ) {
				$company_link = "<a href='{$item['web_url']}'>{$item['company']}</a>";
			} else {
				$company_link = "<a href='{$item['web_url']}'>{$item['web_url']}</a>";
			}
			if ( ! isset( $item['web_url'] ) || empty( $item['web_url'] ) ) {
				$company_link = $item['company'];
			}
			// Process testimonial metadata
			$arr_style = array();
			if ( isset( $item['name_height'] ) ) {
				$arr_style[] = 'font-size: ' . $item['name_height'] . 'px';
			}
			if ( isset( $item['name_color'] ) ) {
				$arr_style[] = 'color: ' . $item['name_color'];
			}
			$style     = ( $arr_style ) ? "style='" . implode( ';', $arr_style ) . "'" : '';
			$name      = ( isset ( $item['name'] ) && in_array( 'name', $content_elements ) ) ? "<strong {$style} class='wr-testimonial-name'>{$item['name']}</strong>" : '';
			$job_title = ( isset ( $item['job_title'] ) &&  in_array( 'job_title', $content_elements ) ) ? "<span class='wr-testimonial-jobtitle'>{$item['job_title']}</span>" : '';
			$country   = ( isset ( $item['country'] ) &&  in_array( 'country', $content_elements ) ) ? "<span class='wr-testimonial-country'>{$item['country']}</span>" : '';
			if ( $company_link ) {
				$company   = ( in_array( 'company', $content_elements ) ) ? "<span class='wr-testimonial-link'>$company_link</span>" : '';
			}
			$html_metadata = '';
			if ( $name != '' || $job_title != '' || $country != '' || $company != '' ) {
				$html_metadata .= '<div class="wr-testimonial-meta">';
				$html_metadata .= $name . $job_title . $country . $company;
				$html_metadata .= '</div>';
			}

			foreach ( $content_elements as $element ) {
				$item_html .= isset( $testimonial_info[$element] ) ? $testimonial_info[$element] : '';
			}
			$item_html .= $html_metadata;
			$item_html .= '</div>';
			$testimonial_content[] = $item_html;

			if ( ($idx + 1 ) % $items_per_slide == 0 || ( $idx + 1 ) == count( $items ) ) {
				$testimonial_content[] = '</div>';
			}
		}
		$testimonial_content      = "<div class='carousel-inner'>" . implode( '', $testimonial_content ) . '</div>';
		$testimonial_indicators[] = '</ol>';
		$testimonial_indicators   = implode( '', $testimonial_indicators );

		$script = "<script type='text/javascript'>
		(function ($){
			$( document ).ready(function(){
				if( $( '#$testimonial_id' ).length ){
					$( '#$testimonial_id .carousel-indicators li' ).each(function (i) {
						$(this).on('click', function () {
							$('#$testimonial_id').carousel(i);
						});
					});
				}
			});
		} )( jQuery );
		</script>";

		$slider_elements = explode( '__#__', $slider_elements );
		if ( $count_items <= $items_per_slide || ! in_array( 'indicator', $slider_elements ) )
			$testimonial_indicators = '';

		$testimonial_navigator = ( $count_items > $items_per_slide && in_array( 'arrows', $slider_elements ) ) ? "<a class='carousel-control left icon-arrow-left wr-arrow-left'></a><a class='carousel-control right icon-arrow-right wr-arrow-right'></a>" : '';
		$html = "<div class='carousel slide wr-testimonial' $styles id='$testimonial_id'>$testimonial_indicators $testimonial_content $testimonial_navigator</div>";
		return $this->element_wrapper( $script . $html, $arr_params );
	}
}

endif;
