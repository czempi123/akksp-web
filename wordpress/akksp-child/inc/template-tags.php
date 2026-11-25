<?php
/**
 * Pomocné funkce pro šablony a widgety.
 *
 * @package AKKSP
 */

defined( 'ABSPATH' ) || exit;

/**
 * SVG sprite s ikonami (vkládá se jednou za <body>).
 */
function akksp_icon_sprite() {
	?>
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
	<symbol id="i-arrow" viewBox="0 0 24 24"><path d="M4 12h16M14 6l6 6-6 6"/></symbol>
	<symbol id="i-phone" viewBox="0 0 24 24"><path d="M6.6 3.5h2.8l1.6 4.4-2 1.3a10.6 10.6 0 0 0 5.8 5.8l1.3-2 4.4 1.6v2.8a2 2 0 0 1-2.1 2A16.5 16.5 0 0 1 4.6 5.6a2 2 0 0 1 2-2.1z"/></symbol>
	<symbol id="i-mail" viewBox="0 0 24 24"><rect x="3" y="5.5" width="18" height="13" rx="1.5"/><path d="m3.5 6.5 8.5 6.5 8.5-6.5"/></symbol>
	<symbol id="i-pin" viewBox="0 0 24 24"><path d="M12 21s-6.5-5.7-6.5-11.2a6.5 6.5 0 0 1 13 0C18.5 15.3 12 21 12 21z"/><circle cx="12" cy="9.8" r="2.3"/></symbol>
	<symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.4-4.4"/></symbol>
	<symbol id="i-quote" viewBox="0 0 32 32"><path d="M4 22.5c0-6.2 2.9-10.9 8.6-14l1.6 2.3c-3.3 2-5 4.4-5.3 7.2H14V27H4v-4.5zm14 0c0-6.2 2.9-10.9 8.6-14l1.6 2.3c-3.3 2-5 4.4-5.3 7.2H28V27H18v-4.5z"/></symbol>
	<symbol id="i-in" viewBox="0 0 24 24"><rect x="3.5" y="3.5" width="17" height="17" rx="2.5"/><path d="M8.2 10.5v6M8.2 7.6v.1M11.8 16.5v-3.6a2.3 2.3 0 0 1 4.6 0v3.6M11.8 10.5v6"/></symbol>
	<symbol id="i-yt" viewBox="0 0 24 24"><rect x="2.8" y="5.8" width="18.4" height="12.4" rx="3.5"/><path d="m10.3 9.3 4.4 2.7-4.4 2.7z"/></symbol>
</svg>
	<?php
}

/**
 * Ikona ze spritu.
 */
function akksp_icon( $name, $small = false ) {
	return sprintf(
		'<svg class="ak-ico%s" aria-hidden="true" focusable="false"><use href="#i-%s"/></svg>',
		$small ? ' ak-ico--sm' : '',
		esc_attr( $name )
	);
}

/**
 * URL loga v barevné mutaci: white | ink | navy.
 */
function akksp_logo_url( $variant = 'white' ) {
	return AKKSP_URI . '/assets/img/akksp-logo-' . sanitize_key( $variant ) . '.svg';
}

/**
 * URL obrázku dodaného s tématem (výchozí obsah widgetů).
 */
function akksp_img( $file ) {
	return AKKSP_URI . '/assets/img/' . ltrim( $file, '/' );
}

/**
 * Tlačítko „text | šipkový modul".
 *
 * @param string $text    Popisek.
 * @param string $url     Odkaz.
 * @param string $variant primary | secondary | glass.
 * @param array  $args    size (sm), attrs (string), icon.
 */
function akksp_button( $text, $url, $variant = 'primary', $args = array() ) {
	if ( '' === trim( (string) $text ) ) {
		return '';
	}
	$args  = wp_parse_args( $args, array( 'size' => '', 'attrs' => '', 'icon' => 'arrow' ) );
	$class = 'ak-btn ak-btn--' . sanitize_html_class( $variant ) . ( $args['size'] ? ' ak-btn--' . sanitize_html_class( $args['size'] ) : '' );
	return sprintf(
		'<a class="%1$s" href="%2$s"%3$s><span>%4$s</span><i class="ak-arrow-box">%5$s</i></a>',
		esc_attr( $class ),
		esc_url( $url ? $url : '#' ),
		$args['attrs'] ? ' ' . $args['attrs'] : '',
		esc_html( $text ),
		akksp_icon( $args['icon'], 'sm' === $args['size'] )
	);
}

/**
 * Textový odkaz s šipkou (mono verzálky).
 */
function akksp_text_link( $text, $url, $attrs = '' ) {
	if ( '' === trim( (string) $text ) ) {
		return '';
	}
	return sprintf( '<a class="ak-link" href="%s"%s>%s %s</a>', esc_url( $url ? $url : '#' ), $attrs ? ' ' . $attrs : '', esc_html( $text ), akksp_icon( 'arrow', true ) );
}

/**
 * Víceřádkový text: první řádek tučně (adresy v patičce).
 */
function akksp_lines( $text, $bold_first = false ) {
	$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $text ) ), 'strlen' );
	$out   = array();
	foreach ( array_values( $lines ) as $i => $line ) {
		$safe = esc_html( $line );
		if ( preg_match( '/^\+?[0-9 ]{9,}$/', $line ) ) {
			$safe = '<a href="' . esc_attr( akksp_tel_href( $line ) ) . '">' . $safe . '</a>';
		}
		$out[] = ( $bold_first && 0 === $i ) ? '<b>' . $safe . '</b>' : $safe;
	}
	return implode( '<br>', $out );
}

/**
 * Přepínač jazyků (Polylang). Bez Polylangu nevypíše nic.
 *
 * @param string $context nav | mobile | seg.
 */
function akksp_languages() {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return array();
	}
	$langs = pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0 ) );
	return is_array( $langs ) ? $langs : array();
}

/**
 * Přepínač jazykových mutací celého webu (segment CZ | PL | …), odkazy z Polylangu.
 * Bez Polylangu (nebo s jediným jazykem) nevypíše nic.
 */
function akksp_lang_switch() {
	$langs = array_values( akksp_languages() );
	if ( count( $langs ) < 2 ) {
		return '';
	}
	$links = '';
	$pos   = 0;
	foreach ( $langs as $i => $lang ) {
		$label   = strtoupper( 'cs' === $lang['slug'] ? 'cz' : $lang['slug'] );
		$current = ! empty( $lang['current_lang'] );
		if ( $current ) {
			$pos = $i;
		}
		$links .= sprintf(
			'<a href="%s" hreflang="%s" lang="%s"%s>%s</a>',
			esc_url( $lang['url'] ),
			esc_attr( str_replace( '_', '-', $lang['locale'] ?? $lang['slug'] ) ),
			esc_attr( $lang['slug'] ),
			$current ? ' aria-current="true"' : '',
			esc_html( $label )
		);
	}
	return sprintf(
		'<nav class="ak-seg" style="--n:%d;--pos:%d" aria-label="%s"><span class="ak-seg__thumb" aria-hidden="true"></span>%s</nav>',
		count( $langs ),
		$pos,
		esc_attr__( 'Jazyk webu', 'akksp' ),
		$links
	);
}

/**
 * Vyhledávací formulář do overlaye: stávající Ivory Search (stejná konfigurace, filtry
 * i živé výsledky jako dnes), jinak vlastní searchform.php tématu.
 */
function akksp_search_form() {
	$id = absint( akksp_contact( 'search_form_id' ) );
	if ( $id && shortcode_exists( 'ivory-search' ) ) {
		return do_shortcode( '[ivory-search id="' . $id . '"]' );
	}
	return get_search_form( array( 'echo' => false ) );
}

/**
 * Šipka za položkou menu v patičce.
 */
function akksp_footer_arrow( $title ) {
	return $title . ' ' . akksp_icon( 'arrow', true );
}

/**
 * Walker: jen holé odkazy (spodní lišta patičky).
 */
class AKKSP_Plain_Links_Walker extends Walker_Nav_Menu {
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item    = $data_object;
		$current = in_array( 'current-menu-item', (array) $item->classes, true ) ? ' aria-current="page"' : '';
		$target  = $item->target ? ' target="' . esc_attr( $item->target ) . '" rel="noopener"' : '';
		$output .= sprintf( '<a href="%s"%s%s>%s</a>', esc_url( $item->url ), $current, $target, esc_html( $item->title ) );
	}
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}
}

/**
 * Štítek sekce „01 · Název".
 */
function akksp_label( $text, $extra_class = '' ) {
	if ( '' === trim( (string) $text ) ) {
		return '';
	}
	return '<p class="ak-label' . ( $extra_class ? ' ' . esc_attr( $extra_class ) : '' ) . '">' . esc_html( $text ) . '</p>';
}

/**
 * Nadpis s volitelně zvýrazněnou částí (zlatě na tmavé).
 */
function akksp_heading_html( $text, $highlight = '' ) {
	$out = esc_html( $text );
	if ( '' !== trim( (string) $highlight ) ) {
		$out .= ' <em>' . esc_html( $highlight ) . '</em>';
	}
	return $out;
}
