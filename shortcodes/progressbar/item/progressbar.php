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
if ( ! class_exists( 'WR_Item_Progressbar' ) ) {

	class WR_Item_Progressbar extends WR_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'item_text'        => __( 'Progress Bar Item', WR_PBL ),
				'data-modal-title' => __( 'Progress Bar Item', WR_PBL ),
			);

			// Inline edit for sub item
			$this->config['edit_inline'] = true;
		}

		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'    => __( 'Text', WR_PBL ),
						'id'      => 'pbar_text',
						'type'    => 'text_field',
						'class'   => 'input-sm',
						'std'     => __( WR_Pb_Utils_Placeholder::add_placeholder( 'Progress Bar Item %s', 'index' ), WR_PBL ),
						'role'    => 'title',
					),
					array(
						'name'         => __( 'Percentage', WR_PBL ),
						'id'           => 'pbar_percentage',
						'type'         => 'slider',
						'class'        => 'wr-slider',
						'std_max'      => '100',
						'std'          => '25',
					),
					array(
						'name'    => __( 'Color', WR_PBL ),
						'id'      => 'pbar_color',
						'type'    => 'select',
						'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_progress_bar_color() ),
						'options' => WR_Pb_Helper_Type::get_progress_bar_color(),
						'container_class'   => 'color_select2',
					),
					array(
						'name'    => __( 'Style', WR_PBL ),
						'id'      => 'pbar_item_style',
						'type'    => 'select',
						'class'   => 'input-sm',
						'std'     => WR_Pb_Helper_Type::get_first_option( WR_Pb_Helper_Type::get_progress_bar_item_style() ),
						'options' => WR_Pb_Helper_Type::get_progress_bar_item_style()
					),
					array(
						'name'      => __( 'Icon', WR_PBL ),
						'id'        => 'pbar_icon',
						'type'      => 'icons',
						'std'       => '',
						'role'      => 'title_prepend',
						'title_prepend_type' => 'icon',
					),
					array(
						'id'    => 'pbar_group',
						'class' => 'pbar_group_type',
						'type'  => 'hidden',
						'std'   => 'multiple-bars',
					),
				)
			);
		}

		public function element_shortcode_full( $atts = null, $content = null ) {
			extract( shortcode_atts( $this->config['params'], $atts ) );
			$pbar_percentage       = floatval( $pbar_percentage );
			$pbar_color            = ( strtolower( $pbar_color ) == 'progress-bar-primary' || empty( $pbar_color ) ) ? $pbar_color = '' : ' ' . $pbar_color;
			$percent               = ( ! $pbar_percentage ) ? '' : $pbar_percentage . '%';
			$pbar_value			   = ( ! $pbar_percentage ) ? '' : ' aria-valuenow="' . $pbar_percentage . '"';
			$pbar_item_style       = ( ! $pbar_item_style || strtolower( $pbar_item_style ) == 'solid' ) ? '' : $pbar_item_style;
			if ( $pbar_item_style == 'striped' ) {
				$pbar_item_style = ' progress-striped';
			}

			$pbar_icon    = ( ! $pbar_icon ) ? '' : "<i class='{$pbar_icon}'></i>";
			$html_content = "[icon]{$pbar_icon}[/icon][text]{$pbar_text}[/text]";

			// Add title progressbar
			$html_content = "<div class='progress-info'><span class='progress-title'>{$html_content}</span>[percentage]<span class='progress-percentage'>{$pbar_percentage}%</span>[/percentage]</div>";

			if ( $pbar_group == 'stacked' ) {
				$html_sub_elm = '[sub_content]' . $html_content . '[/sub_content]';
				$html_sub_elm .= "<div class='progress-bar{$pbar_color}{$pbar_item_style}' {$pbar_value}></div>";
			} else {
				$html_sub_elm = '[sub_content]' . $html_content . '[/sub_content]';
				$html_sub_elm .= "<div class='progress{$pbar_item_style}{active}'>";
				$html_sub_elm .= "<div class='progress-bar {$pbar_color}' role='progressbar'{$pbar_value}aria-valuemin='0' aria-valuemax='100'><span class='sr-only'>{$percent}</span></div>";
				$html_sub_elm .= '</div>';
			}

			return $html_sub_elm . '<!--seperate-->';
		}

	}

}