<?php
/**
 * Styly, skripty a lokální písma.
 *
 * @package AKKSP
 */

defined( 'ABSPATH' ) || exit;

/**
 * Verze souboru podle data změny (spolehlivé obnovení cache po nasazení).
 */
function akksp_asset_ver( $rel ) {
	$path = AKKSP_DIR . '/' . ltrim( $rel, '/' );
	return file_exists( $path ) ? AKKSP_VERSION . '.' . filemtime( $path ) : AKKSP_VERSION;
}

/**
 * Jsou k dispozici lokální písma? (stahuje tools/get-fonts.ps1)
 */
function akksp_fonts_available() {
	return file_exists( AKKSP_DIR . '/assets/fonts/fonts.css' );
}

add_action(
	'wp_enqueue_scripts',
	function () {
		$css = array(
			'akksp-tokens'     => 'assets/css/tokens.css',
			'akksp-base'       => 'assets/css/base.css',
			'akksp-components' => 'assets/css/components.css',
			'akksp-sections'   => 'assets/css/sections.css',
			'akksp-wp'         => 'assets/css/wp.css',
		);

		$deps = array();
		if ( akksp_fonts_available() ) {
			wp_enqueue_style( 'akksp-fonts', AKKSP_URI . '/assets/fonts/fonts.css', array(), akksp_asset_ver( 'assets/fonts/fonts.css' ) );
			$deps[] = 'akksp-fonts';
		}

		foreach ( $css as $handle => $rel ) {
			wp_enqueue_style( $handle, AKKSP_URI . '/' . $rel, $deps, akksp_asset_ver( $rel ) );
			$deps = array( $handle );
		}

		wp_enqueue_script( 'akksp-site', AKKSP_URI . '/assets/js/site.js', array(), akksp_asset_ver( 'assets/js/site.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	},
	20
);

/**
 * Třída „js" co nejdřív (animace se skryjí jen když JS běží).
 */
add_action(
	'wp_head',
	function () {
		echo "<script>document.documentElement.classList.add('js');</script>\n";
	},
	1
);

/**
 * Přednačtení hlavních řezů písma.
 */
add_action(
	'wp_head',
	function () {
		if ( ! akksp_fonts_available() ) {
			return;
		}
		$preload = array( 'source-serif-4-var-latin-ext.woff2', 'ibm-plex-sans-400-latin-ext.woff2' );
		foreach ( $preload as $file ) {
			if ( file_exists( AKKSP_DIR . '/assets/fonts/' . $file ) ) {
				printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( AKKSP_URI . '/assets/fonts/' . $file ) );
			}
		}
	},
	2
);

/**
 * Upozornění v administraci, dokud nejsou nahraná lokální písma.
 */
add_action(
	'admin_notices',
	function () {
		if ( akksp_fonts_available() || ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		echo '<div class="notice notice-warning"><p><strong>AKKSP:</strong> ' . esc_html__( 'Chybí lokální písma (assets/fonts/fonts.css). Web zatím používá systémová písma. Spusťte tools/get-fonts.ps1 a téma nahrajte znovu.', 'akksp' ) . '</p></div>';
	}
);
