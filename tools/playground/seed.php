<?php
/**
 * Testovací obsah pro lokální WordPress Playground (NE pro produkci).
 * Vytvoří homepage ze všech widgetů AKKSP, podstránku, menu a 3 články s obrázky.
 */
require_once '/wordpress/wp-load.php';
header( 'Content-Type: text/plain; charset=utf-8' );
ini_set( 'display_errors', '1' );
error_reporting( E_ALL );
if ( ! in_array( $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', array( '127.0.0.1', '::1' ), true ) && PHP_SAPI !== 'cli' ) {
	exit( 'local only' );
}
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

wp_set_current_user( 1 ); // Elementor ukládá kit jen s oprávněním
update_option( 'blogname', 'AKKSP advokátní kancelář' );
update_option( 'WPLANG', '' );
switch_theme( 'akksp-child' );
if ( function_exists( 'akksp_setup_elementor_kit' ) ) {
	akksp_setup_elementor_kit();
}

/** Nahraje obrázek z tématu do knihovny médií. */
function akksp_seed_attach( $file, $title ) {
	$src = get_stylesheet_directory() . '/assets/img/demo/' . $file;
	if ( ! file_exists( $src ) ) {
		$src = '/wordpress/wp-content/themes/akksp-child/assets/img/demo/' . $file;
	}
	$tmp = wp_tempnam( $file );
	copy( $src, $tmp );
	$id = media_handle_sideload( array( 'name' => $file, 'tmp_name' => $tmp ), 0, $title );
	return is_wp_error( $id ) ? 0 : $id;
}

/** Elementor data: každý widget ve vlastním plném kontejneru. */
function akksp_seed_elementor( $widgets ) {
	$els = array();
	foreach ( $widgets as $i => $w ) {
		$els[] = array(
			'id'       => substr( md5( 'c' . $i . $w[0] ), 0, 7 ),
			'elType'   => 'container',
			'isInner'  => false,
			'settings' => array( 'content_width' => 'full' ),
			'elements' => array(
				array(
					'id'         => substr( md5( 'w' . $i . $w[0] ), 0, 7 ),
					'elType'     => 'widget',
					'widgetType' => $w[0],
					'settings'   => $w[1],
					'elements'   => array(),
				),
			),
		);
	}
	return wp_slash( wp_json_encode( $els ) );
}

function akksp_seed_page( $title, $slug, $widgets ) {
	$existing = get_page_by_path( $slug );
	$id       = $existing ? $existing->ID : wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug ) );
	update_post_meta( $id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $id, '_elementor_template_type', 'wp-page' );
	update_post_meta( $id, '_wp_page_template', 'elementor_header_footer' );
	update_post_meta( $id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
	update_post_meta( $id, '_elementor_data', akksp_seed_elementor( $widgets ) );
	return $id;
}

// Články s obrázky (pro widget Aktuality).
$posts = array(
	array( 'Předání firmy nové generaci: Jak úspěšně naplánovat nástupnictví', 'Předání firmy patří k nejdůležitějším rozhodnutím v životě podnikatele.', 'jednaci-mistnost.jpg', '-3 days' ),
	array( 'Generační nástupnictví nezačíná smlouvou. Začíná rozhodnutím vlastníka', 'Vlastník firmy obvykle dokáže přesně popsat zákazníky, výrobní procesy i finanční plán.', 'hero-zasedacka.jpg', '-2 days' ),
	array( 'Nástupce nestačí jmenovat: Jak oddělit vlastnictví firmy od jejího řízení', 'Dobrý budoucí vlastník nemusí být nejlepším generálním ředitelem a schopný manažer nemusí být členem rodiny.', 'jednaci-mistnost.jpg', '-1 day' ),
);
$cat = wp_insert_term( 'Nástupnictví', 'category' );
$cat = is_wp_error( $cat ) ? (int) get_cat_ID( 'Nástupnictví' ) : (int) $cat['term_id'];
foreach ( $posts as $p ) {
	$exists = get_posts( array( 'post_type' => 'post', 'title' => $p[0], 'fields' => 'ids', 'posts_per_page' => 1 ) );
	if ( $exists ) {
		continue;
	}
	$pid = wp_insert_post( array( 'post_type' => 'post', 'post_status' => 'publish', 'post_title' => $p[0], 'post_excerpt' => $p[1], 'post_content' => '<p>' . $p[1] . '</p><h2>Ukázkový mezititulek</h2><p>Ukázkový text článku pro test typografie.</p><ul><li>První bod</li><li>Druhý bod</li></ul>', 'post_date' => gmdate( 'Y-m-d H:i:s', strtotime( $p[3] ) ), 'post_category' => array( $cat ) ) );
	$aid = akksp_seed_attach( $p[2], $p[0] );
	if ( $aid ) {
		set_post_thumbnail( $pid, $aid );
	}
}
wp_delete_post( 1, true ); // „Hello world!"

$home = akksp_seed_page(
	'Úvod',
	'uvod',
	array(
		array( 'akksp-hero', array() ),
		array( 'akksp-why', array() ),
		array( 'akksp-areas', array() ),
		array( 'akksp-team', array() ),
		array( 'akksp-references', array() ),
		array( 'akksp-training', array() ),
		array( 'akksp-news', array() ),
	)
);
$sub = akksp_seed_page(
	'Pro firmy',
	'pro-firmy',
	array(
		array( 'akksp-page-header', array( 'label' => 'Pro firmy', 'title' => 'Právní servis pro stabilní fungování a růst firmy', 'lead' => 'Smlouvy, spory, zaměstnanci, korporátní agenda i přeshraniční podnikání.' ) ),
		array( 'akksp-section-head', array( 'label' => '01 · Služby', 'title' => 'S čím firmám pomáháme', 'surface' => 'light' ) ),
		array( 'akksp-areas', array( 'label' => '02 · Oblasti', 'title' => 'Oblasti pro firmy' ) ),
	)
);
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home );

// Stránka bez Elementoru (test odsazení a typografie)
if ( ! get_page_by_path( 'kontakty' ) ) {
	wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Kontakty', 'post_name' => 'kontakty', 'post_content' => '<p>Husova 401, 739 61 Třinec.</p><h2>Napište nám</h2><p>Ukázka běžné stránky bez Elementoru.</p>' ) );
}

// Menu
$menus = array(
	'akksp-primary'      => array( 'Hlavní', array( 'Pro firmy' => get_permalink( $sub ), 'Pro jednotlivce' => '#', 'Česko / Polsko' => '#', 'O nás' => '#', 'Zkušenosti' => '#', 'Školení a workshopy' => '#', 'Právní poradna' => '#' ) ),
	'akksp-footer-info'  => array( 'Patička info', array( 'Mimosoudní řešení spotřebitelských sporů' => '#', 'Poučení klienta podle AML zákona' => '#', 'Ochrana osobních údajů' => '#', 'Cookies' => '#' ) ),
	'akksp-footer-media' => array( 'Patička média', array( 'Právní rady veřejnosti (eHutnik)' => '#', 'Teaser' => '#' ) ),
	'akksp-footer-legal' => array( 'Patička spodní', array( 'Ochrana osobních údajů' => '#', 'Cookies' => '#', 'Kontakty' => home_url( '/kontakty/' ) ) ),
);
$locations = get_theme_mod( 'nav_menu_locations', array() );
foreach ( $menus as $loc => $m ) {
	$menu = wp_get_nav_menu_object( $m[0] );
	$mid  = $menu ? $menu->term_id : wp_create_nav_menu( $m[0] );
	if ( ! $menu ) {
		foreach ( $m[1] as $title => $url ) {
			wp_update_nav_menu_item( $mid, 0, array( 'menu-item-title' => $title, 'menu-item-url' => $url, 'menu-item-status' => 'publish', 'menu-item-type' => 'custom' ) );
		}
	}
	$locations[ $loc ] = $mid;
}
set_theme_mod( 'nav_menu_locations', $locations );

if ( class_exists( '\Elementor\Plugin' ) ) {
	\Elementor\Plugin::$instance->files_manager->clear_cache();
}
echo "seed ok: home=$home sub=$sub\n";
