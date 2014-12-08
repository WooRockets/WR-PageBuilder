<?php

/**
 * @version	$Id$
 * @package	WR PageBuilder
 * @author	 WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 woorockets.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support:  Feedback - http://www.woorockets.com
 */
if ( ! class_exists( 'WR_Widget' ) ) :

/**
 * Widget element for WR PageBuilder.
 *
 * @since  1.0.0
 */
class WR_Widget extends WR_Pb_Shortcode_Element {
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
	function element_config() {
		$this->config['shortcode'] = strtolower( __CLASS__ );

		// Use Ajax to speed up element settings modal loading speed
		$this->config['edit_using_ajax'] = true;
	}

	/**
	 * Define shortcode settings.
	 *
	 * @return  void
	 */
	function element_items() {
	}

	/**
	 * Generate HTML code from shortcode content.
	 *
	 * @param   array   $atts     Shortcode attributes.
	 * @param   string  $content  Current content.
	 *
	 * @return  string
	 */
	function element_shortcode_full( $atts = null, $content = null ) {
	}
}

endif;
