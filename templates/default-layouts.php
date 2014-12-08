<?php
/**
 * Show thumbnail for default layouts
 */
echo '<div class="row-fluid wr-layout-thumbs">';
$layouts = WR_Row::$layouts;
foreach ( $layouts as $columns ) {
	$columns_name = implode( 'x', $columns );

	$icon_class = implode( '-', $columns );
	$icon_class = 'wr-layout-' . $icon_class;
	$icon = "<i class='{$icon_class}'></i>";

	printf( '<div class="thumb-wrapper" data-columns="%s" title="%s">%s</div>', implode( ',', $columns ), $columns_name, $icon );
}
echo '</div>';