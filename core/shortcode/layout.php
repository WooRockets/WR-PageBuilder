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
 * Parent class for layout elements
 */

class WR_Pb_Shortcode_Layout extends WR_Pb_Shortcode_Common {

	public function __construct() {
		$this->type = 'layout';
		$this->config['el_type'] = 'element';

		$this->element_config();
		$this->element_items();
		$this->shortcode_data();

		/* add shortcode */
		add_shortcode( $this->config['shortcode'], array( &$this, 'element_shortcode' ) );

		// enqueue custom script for current element
		if ( WR_Pb_Helper_Functions::is_modal_of_element( $this->config['shortcode'] ) ) {
			WR_Pb_Helper_Functions::shortcode_enqueue_assets( $this, 'admin_assets', '' );
		}

		parent::__construct();
	}

	/**
	 * html structure of item in List item
	 * @return type
	 */
	public function element_button( $sort ) {

	}

	/**
	 * html structure of element in Page Builder area
	 */
	public function element_in_pgbldr() {

	}

	/**
	 * DEFINE shortcode content
	 *
	 * @param type $atts
	 * @param type $content
	 */
	public function element_shortcode( $atts = null, $content = null ) {

	}

	/**
	 * get params & structure of shortcode
	 */
	public function shortcode_data() {

	}

}
