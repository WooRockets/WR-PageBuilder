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
/*
 * Define a column shortcode
 */
if ( ! class_exists( 'WR_Column' ) ) {

	class WR_Column extends WR_Pb_Shortcode_Layout {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		function element_config() {
			$this->config['shortcode']     = strtolower( __CLASS__ );
			$this->config['extract_param'] = array(
				'span',
				'hidden_on'
			);

			// Use Ajax to speed up element settings modal loading speed
			$this->config['edit_using_ajax'] = true;
		}

		/**
		 * contain setting items of this element (use for modal box)
		 *
		 */
		function element_items() {
			$this->items = array(
				'Notab' => array(
					// --------------------------------------------------------------- HIDDEN
					array(
						'name'    => __( 'Hidden on ...', WR_PBL ),
						'id'      => 'hidden_on',
						'type'    => 'checkbox',
						'std'     => '',
						'options' => array(
							'hidden-lg' => __( 'Large' , WR_PBL ),
							'hidden-md' => __( 'Medium', WR_PBL ),
							'hidden-sm' => __( 'Small', WR_PBL ),
							'hidden-xs' => __( 'Extra-Small', WR_PBL )
						)
					)
				)
			);
		}

		/**
		 * get params & structure of shortcode
		 */
		public function shortcode_data() {
			$this->config['params'] = WR_Pb_Helper_Shortcode::generate_shortcode_params( $this->items, null, null, false, true );
			$this->config['params']['span'] = ( ! empty( $this->params['span'] ) ) ? $this->params['span'] : 'span12';
			$this->config['shortcode_structure'] = WR_Pb_Helper_Shortcode::generate_shortcode_structure( $this->config['shortcode'], $this->config['params'] );
		}

		/**
		 *
		 * @param type $content			 : inner shortcode elements of this column
		 * @param string $shortcode_data
		 * @return string
		 */
		public function element_in_pgbldr( $content = '', $shortcode_data = '' ) {
			$column_html    = empty( $content ) ? '' : WR_Pb_Helper_Shortcode::do_shortcode_admin( $content, true );
			$span           = ( ! empty( $this->params['span'] ) ) ? $this->params['span'] : 'span12';
			if ( empty( $shortcode_data ) )
				$shortcode_data = $this->config['shortcode_structure'];
			// remove [/wr_row][wr_column...] from $shortcode_data
			$shortcode_data = explode( '][', $shortcode_data );
			$shortcode_data = $shortcode_data[0] . ']';

			// Remove empty value attributes of shortcode tag.
			$shortcode_data	= preg_replace( '/\[*([a-z_]*[\n\s\t]*=[\n\s\t]*"")/', '', $shortcode_data );

			$rnd_id   = WR_Pb_Utils_Common::random_string();
			$column[] = '<div class="jsn-column-container clearafter shortcode-container ">
							<div class="jsn-column ' . $span . '">
								<div class="thumbnail clearafter">
									<textarea class="hidden" data-sc-info="shortcode_content" name="shortcode_content[]" >' . $shortcode_data . '</textarea>
									<div class="jsn-column-content item-container" data-column-class="' . $span . '" >
										<div class="jsn-handle-drag jsn-horizontal jsn-iconbar-trigger">
											<div class="jsn-iconbar layout">
												<a href="javascript:void(0);" title="Edit Column" data-shortcode="' . $this->config['shortcode'] . '" class="element-edit column" data-use-ajax="' . ( $this->config['edit_using_ajax'] ? 1 : 0 ) . '"><i class="icon-pencil"></i></a>
												<a class="item-delete column" onclick="return false;" title="' . __( 'Delete column', WR_PBL ) . '" href="#"><i class="icon-trash"></i></a>
											</div>
										</div>
										<div class="jsn-element-container item-container-content">
											' . $column_html . '</div>
										<a class="jsn-add-more wr-more-element" href="javascript:void(0);"><i class="icon-plus"></i>' . __( 'Add Element', WR_PBL ) . '</a>
									</div>
									<textarea class="hidden" name="shortcode_content[]" >[/' . $this->config['shortcode'] . ']</textarea>
								</div>
							</div>
						</div>';
			return $column;
		}

		/**
		 * define shortcode structure of element
		 */
		function element_shortcode( $atts = null, $content = null ) {
			extract( shortcode_atts(
				array(
	        		'span' => 'span12',
	        		'hidden_on' => '',
	        		'style' => ''
	        	),
	        	$atts
	        ) );
			$style   = empty( $style ) ? '' : "style='$style'";
			$span    = intval( substr( $span, 4 ) );
			$hidden_on = trim( str_replace( '__#__', ' ', $hidden_on ) );
			$class   = "col-md-$span col-sm-$span col-xs-12 $hidden_on";

			$content = WR_Pb_Helper_Shortcode::remove_autop( $content );

			return '<div class="' . $class . '" ' . $style . '>' . $content . '</div>';
		}

	}

}
