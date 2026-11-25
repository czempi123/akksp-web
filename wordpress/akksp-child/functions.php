<?php
/**
 * AKKSP child téma (rodič: Hello Elementor).
 *
 * @package AKKSP
 */

defined( 'ABSPATH' ) || exit;

define( 'AKKSP_VERSION', '1.0.0' );
define( 'AKKSP_DIR', get_stylesheet_directory() );
define( 'AKKSP_URI', get_stylesheet_directory_uri() );

require_once AKKSP_DIR . '/inc/setup.php';
require_once AKKSP_DIR . '/inc/assets.php';
require_once AKKSP_DIR . '/inc/customizer.php';
require_once AKKSP_DIR . '/inc/template-tags.php';
require_once AKKSP_DIR . '/inc/elementor.php';
