<?php
/**
 * @version    $Id$
 * @package    WR_PageBuilder
 * @author     InnoThemes Team <support@innothemes.com>
 * @copyright  Copyright (C) 2012 InnoThemes.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innothemes.com
 */

// Make sure response header is HTML document
@header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) );

// Check if requesting form only
$form_only = ( isset( $_GET['form_only'] ) && absint( $_GET['form_only'] ) );

// Print HTML structure if not requesting form only
if ( ! $form_only ) :
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<?php
endif;

// Do necessary actions for loading header assets
if ( $form_only ) {
	ob_start();
}

if ( ! $form_only ) {
	do_action( 'pb_admin_enqueue_scripts' );
}

do_action( 'admin_print_styles'  );
do_action( 'admin_print_scripts' );

if ( ! $form_only ) {
	do_action( 'pb_admin_head' );
}

if ( $form_only ) {
	ob_end_clean();

	// Do custom actions for loading assets
	do_action( 'pb_admin_enqueue_scripts' );
	do_action( 'pb_admin_print_styles'    );
	do_action( 'pb_admin_print_scripts'   );
	do_action( 'pb_admin_head'            );
}

// Print HTML structure if not requesting form only
if ( ! $form_only ) :
?>
</head>
<body class="jsn-master contentpane">
<?php
endif;

// Print HTML code for element settings
echo '' . $data;

// Do necessary actions for loading footer assets
do_action( 'pb_admin_footer'            );
do_action( 'admin_print_footer_scripts' );

// Register inline script if not previewing
if ( ! isset( $_GET['wr_shortcode_preview'] ) || ! $_GET['wr_shortcode_preview'] ) {
	$script = '
		if ($.HandleSetting && $.HandleSetting.init) $.HandleSetting.init();
';

	WR_Pb_Init_Assets::inline( 'js', $script, true );
}

// Print HTML structure if not requesting form only
if ( ! $form_only ) :
?>
</body>
</html>
<?php
endif;

// Exit immediately to prevent base gadget class from sending JSON data back
exit;
