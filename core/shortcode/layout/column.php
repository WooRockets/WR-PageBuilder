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
			$this->config['edit_using_ajax'] = true;
			$this->config['extract_param'] = array(
				'span',
				'span_md',
				'span_sm',
				'span_xs',

				'offset_lg',
				'offset_md',
				'offset_sm',
				'offset_xs',

				'push_lg',
				'push_md',
				'push_sm',
				'push_xs',

				'pull_lg',
				'pull_md',
				'pull_sm',
				'pull_xs',

				'visible_lg',
				'visible_md',
				'visible_sm',
				'visible_xs',

				'hidden_on'
			);
		}

		/**
		 * contain setting items of this element (use for modal box)
		 *
		 */
		function element_items() {

			$this->items = array(
				'Notab' => array(
	                 // --------------------------------------------------------------- GRID SIZE
	                 array(
						'name'		=> __('Size large', WR_PBL),
						'id' 		=> 'span',
						'type'		=> 'select',
						'std'		=> 'span12',
						'options'	=> array(
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
	                array(
						'name'		=> __('Size medium', WR_PBL),
						'id' 		=> 'span_md',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
					array(
						'name'		=> __('Size small', WR_PBL),
						'id' 		=> 'span_sm',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
					array(
						'name'		=> __('Size extra-small', WR_PBL),
						'id' 		=> 'span_xs',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
					// --------------------------------------------------------------- OFFSET
					array(
						'name'		=> __('Offset large', WR_PBL),
						'id' 		=> 'offset_lg',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
	                array(
						'name'		=> __('Offset medium', WR_PBL),
						'id' 		=> 'offset_md',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
					array(
						'name'		=> __('Offset small', WR_PBL),
						'id' 		=> 'offset_sm',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
					array(
						'name'		=> __('Offset extra-small', WR_PBL),
						'id' 		=> 'offset_xs',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
					// --------------------------------------------------------------- PUSH
					array(
						'name'		=> __('Push large', WR_PBL),
						'id' 		=> 'push_lg',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
	                array(
						'name'		=> __('Push medium', WR_PBL),
						'id' 		=> 'push_md',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
					array(
						'name'		=> __('Push small', WR_PBL),
						'id' 		=> 'push_sm',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
					array(
						'name'		=> __('Push extra-small', WR_PBL),
						'id' 		=> 'push_xs',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
					// --------------------------------------------------------------- PULL
					array(
						'name'		=> __('Pull large', WR_PBL),
						'id' 		=> 'pull_lg',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
	                array(
						'name'		=> __('Pull medium', WR_PBL),
						'id' 		=> 'pull_md',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
					array(
						'name'		=> __('Pull small', WR_PBL),
						'id' 		=> 'pull_sm',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
					array(
						'name'		=> __('Pull extra-small', WR_PBL),
						'id' 		=> 'pull_xs',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'span1' => __('1col', WR_PBL),
							'span2' => __('2col', WR_PBL),
							'span3' => __('3col', WR_PBL),
							'span4' => __('4col', WR_PBL),
							'span5' => __('5col', WR_PBL),
							'span6' => __('6col', WR_PBL),
							'span7' => __('7col', WR_PBL),
							'span8' => __('8col', WR_PBL),
							'span9' => __('9col', WR_PBL),
							'span10' => __('10col', WR_PBL),
							'span11' => __('11col', WR_PBL),
							'span12' => __('12col', WR_PBL)
						)
					),
					// --------------------------------------------------------------- VISIBILITY
					array(
						'name'		=> __('Visible type large', WR_PBL),
						'id'		=> 'visible_lg',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'block' => __('block', WR_PBL),
							'inline-block' => __('inline-block', WR_PBL),
							'inline' => __('inline', WR_PBL)
						)
					),
					array(
						'name'		=> __('Visible type medium', WR_PBL),
						'id'		=> 'visible_md',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'block' => __('block', WR_PBL),
							'inline-block' => __('inline-block', WR_PBL),
							'inline' => __('inline', WR_PBL)
						)
					),
					array(
						'name'		=> __('Visible type small', WR_PBL),
						'id'		=> 'visible_sm',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'block' => __('block', WR_PBL),
							'inline-block' => __('inline-block', WR_PBL),
							'inline' => __('inline', WR_PBL)
						)
					),
					array(
						'name'		=> __('Visible type extra-small', WR_PBL),
						'id'		=> 'visible_xs',
						'type'		=> 'select',
						'std'		=> 'none',
						'options'	=> array(
							'none' => __('none (no change)', WR_PBL),
							'block' => __('block', WR_PBL),
							'inline-block' => __('inline-block', WR_PBL),
							'inline' => __('inline', WR_PBL)
						)
					),
					// --------------------------------------------------------------- HIDDEN
					array(
						'name'		=> __('Hidden on ...'),
						'id'		=> 'hidden_on',
						'type'		=> 'checkbox',
						'std'		=> 'none',
						'options'	=> array(
							'lg' => __('large', WR_PBL),
							'md' => __('medium', WR_PBL),
							'sm' => __('small', WR_PBL),
							'xs' => __('extra-small', WR_PBL)
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
			$this->config['shortcode_structure'] = WR_Pb_Helper_Shortcode::generate_shortcode_structure( $this->config['shortcode'], $this->config['params'] );
		}

		/**
		 *
		 * @param type $content			 : inner shortcode elements of this column
		 * @param string $shortcode_data
		 * @return string
		 */
		public function element_in_pgbldr( $content = '', $shortcode_data = '' ) {
			$column_html    = empty($content) ? '' : WR_Pb_Helper_Shortcode::do_shortcode_admin( $content, true );
			$span           = ( ! empty($this->params['span'] ) ) ? $this->params['span'] : 'span12';
			if ( empty($shortcode_data) )
			$shortcode_data = $this->config['shortcode_structure'];
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
			extract( shortcode_atts( array(
	        	'span' => 'span12',
	        	'span_md' => 'none',
	        	'span_sm' => 'none',
	        	'span_xs' => 'none',

	        	'offset_lg' => 'none',
	        	'offset_md' => 'none',
	        	'offset_sm' => 'none',
	        	'offset_xs' => 'none',

	        	'push_lg' => 'none',
	        	'push_md' => 'none',
	        	'push_sm' => 'none',
	        	'push_xs' => 'none',

	        	'pull_lg' => 'none',
	        	'pull_md' => 'none',
	        	'pull_sm' => 'none',
	        	'pull_xs' => 'none',

	        	'visible_lg' => 'none',
	        	'visible_md' => 'none',
	        	'visible_sm' => 'none',
	        	'visible_xs' => 'none',

	        	'hidden_on' => 'none',

	        	'style' => ''
	        ), $atts ) );
			$style   = empty( $style ) ? '' : "style='$style'";
			$span    = 'col-lg-'.intval( substr( $span, 4 ) );

			$span_md = ($span_md == 'none') ? '' : ' col-md-'.intval( substr( $span_md, 4 ) );
			$span_sm = ($span_sm == 'none') ? '' : ' col-sm-'.intval( substr( $span_sm, 4 ) );
			$span_xs = ($span_xs == 'none') ? '' : ' col-xs-'.intval( substr( $span_xs, 4 ) );

			$offset_lg = ($offset_lg == 'none') ? '' : ' col-lg-offset-'.intval( substr( $offset_lg, 4 ) );
			$offset_md = ($offset_md == 'none') ? '' : ' col-md-offset-'.intval( substr( $offset_md, 4 ) );
			$offset_sm = ($offset_sm == 'none') ? '' : ' col-sm-offset-'.intval( substr( $offset_sm, 4 ) );
			$offset_xs = ($offset_xs == 'none') ? '' : ' col-xs-offset-'.intval( substr( $offset_xs, 4 ) );

			$push_lg = ($push_lg == 'none') ? '' : ' col-lg-push-'.intval( substr( $push_lg, 4 ) );
			$push_md = ($push_md == 'none') ? '' : ' col-md-push-'.intval( substr( $push_md, 4 ) );
			$push_sm = ($push_sm == 'none') ? '' : ' col-sm-push-'.intval( substr( $push_sm, 4 ) );
			$push_xs = ($push_xs == 'none') ? '' : ' col-xs-push-'.intval( substr( $push_xs, 4 ) );

			$pull_lg = ($pull_lg == 'none') ? '' : ' col-lg-pull-'.intval( substr( $pull_lg, 4 ) );
			$pull_md = ($pull_md == 'none') ? '' : ' col-md-pull-'.intval( substr( $pull_md, 4 ) );
			$pull_sm = ($pull_sm == 'none') ? '' : ' col-sm-pull-'.intval( substr( $pull_sm, 4 ) );
			$pull_xs = ($pull_xs == 'none') ? '' : ' col-xs-pull-'.intval( substr( $pull_xs, 4 ) );

			/**
			*
			* This is for bootstrap 3.2 (the visible classes have changed)
			*
			**/
			// $visible_lg = ($visible_lg == 'none') ? '' : ' visible-lg-'.$visible_lg;
			// $visible_md = ($visible_md == 'none') ? '' : ' visible-md-'.$visible_md;
			// $visible_sm = ($visible_sm == 'none') ? '' : ' visible-sm-'.$visible_sm;
			// $visible_xs = ($visible_xs == 'none') ? '' : ' visible-xs-'.$visible_xs;
			/**
			*
			* This is for the WR included bootstrap version (remove this if you implement the latest BS version)
			*
			**/
			$visible_lg = ($visible_lg == 'none') ? '' : ' visible-lg';
			$visible_md = ($visible_md == 'none') ? '' : ' visible-md';
			$visible_sm = ($visible_sm == 'none') ? '' : ' visible-sm';
			$visible_xs = ($visible_xs == 'none') ? '' : ' visible-xs';

			$hidden_on = ($hidden_on == 'none') ? '' : explode('__#__', trim($hidden_on));

			$hidden_classes = '';

			foreach ($hidden_on as $key => $value) {
				if (strlen(trim($value)) > 0) {
					$hidden_classes .= ' hidden-'.$value;
				}
			}

			$class   = "$span$span_md$span_sm$span_xs$offset_lg$offset_md$offset_sm$offset_xs$push_lg$push_md$push_sm$push_xs$pull_lg$pull_md$pull_sm$pull_xs$visible_lg$visible_md$visible_sm$visible_xs$hidden_classes";

			$content = WR_Pb_Helper_Shortcode::remove_autop( $content );

			return '<div class="' . $class . '" ' . $style . '>' . $content . '</div>';
		}

	}

}
