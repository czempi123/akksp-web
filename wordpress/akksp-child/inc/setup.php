<?php
/**
 * Základní nastavení tématu: menu, podpora funkcí, třídy body.
 *
 * @package AKKSP
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_child_theme_textdomain( 'akksp', AKKSP_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );

		register_nav_menus(
			array(
				'akksp-primary'     => __( 'AKKSP: hlavní menu', 'akksp' ),
				'akksp-footer-info' => __( 'AKKSP: patička – Další informace', 'akksp' ),
				'akksp-footer-media'=> __( 'AKKSP: patička – Média', 'akksp' ),
				'akksp-footer-legal'=> __( 'AKKSP: patička – spodní lišta', 'akksp' ),
			)
		);

		add_image_size( 'akksp-portrait', 600, 750, true );
		add_image_size( 'akksp-card', 800, 533, true );
	},
	20
);

/**
 * Hello Elementor: vlastní hlavičku a patičku vykresluje toto téma,
 * výchozí stylování Hello (mimo reset) vypínáme.
 */
add_filter( 'hello_elementor_enqueue_theme_style', '__return_false' );
// Reset Hello Elementor stylizuje tlačítka (růžový rámeček #c36) silnějším selektorem než design system.
add_filter( 'hello_elementor_enqueue_style', '__return_false' );
add_filter( 'hello_elementor_enable_skip_link', '__return_false' ); // vlastní „Přejít k obsahu" je v header.php
add_filter( 'hello_elementor_description_meta_tag', '__return_false' );

/**
 * Zjistí, zda stránka začíná tmavým záhlavím (hero / záhlaví podstránky).
 * Podle toho se navigace zobrazí přes fotku, nebo se obsah odsadí.
 */
function akksp_has_dark_top( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_queried_object_id();
	if ( ! $post_id || ! is_singular() ) {
		return false;
	}
	$data = get_post_meta( $post_id, '_elementor_data', true );
	if ( ! is_string( $data ) || '' === $data ) {
		return false;
	}
	return ( false !== strpos( $data, '"widgetType":"akksp-hero"' ) || false !== strpos( $data, '"widgetType":"akksp-page-header"' ) );
}

add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = akksp_has_dark_top() ? 'ak-top-dark' : 'ak-top-light';
		$classes[] = 'ak-theme';
		return $classes;
	}
);

/**
 * Pořadové číslo položek mobilního menu (pro postupný náběh).
 */
add_filter(
	'nav_menu_link_attributes',
	function ( $atts, $item, $args ) {
		static $i = 0;
		if ( isset( $args->akksp_mobile ) && $args->akksp_mobile ) {
			$atts['style'] = '--i:' . (int) $i;
			$i++;
		}
		return $atts;
	},
	10,
	3
);

/**
 * U položek mobilního menu přidá číslo vpravo (01, 02 …).
 */
add_filter(
	'nav_menu_item_title',
	function ( $title, $item, $args ) {
		static $n = 0;
		if ( isset( $args->akksp_mobile ) && $args->akksp_mobile && 0 === (int) $item->menu_item_parent ) {
			$n++;
			$title .= ' <span class="ak-mono">' . esc_html( str_pad( (string) $n, 2, '0', STR_PAD_LEFT ) ) . '</span>';
		}
		return $title;
	},
	10,
	3
);
