<?php
/**
 * Napojení na Elementor: kategorie, widgety, písma, globální barvy.
 *
 * @package AKKSP
 */

defined( 'ABSPATH' ) || exit;

/**
 * Žádná Google Fonts z Elementoru (GDPR) — písma jsou lokální.
 */
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );

/**
 * Písma tématu v nabídce typografie Elementoru (typ „system" = nestahuje se).
 */
add_filter(
	'elementor/fonts/additional_fonts',
	function ( $fonts ) {
		$fonts['Source Serif 4'] = 'system';
		$fonts['IBM Plex Sans']  = 'system';
		$fonts['IBM Plex Mono']  = 'system';
		return $fonts;
	}
);

/**
 * Kategorie „AKKSP" v panelu widgetů.
 */
add_action(
	'elementor/elements/categories_registered',
	function ( $elements_manager ) {
		$elements_manager->add_category(
			'akksp',
			array(
				'title' => __( 'AKKSP sekce', 'akksp' ),
				'icon'  => 'eicon-folder',
			)
		);
	}
);

/**
 * Registrace widgetů.
 */
add_action(
	'elementor/widgets/register',
	function ( $widgets_manager ) {
		require_once AKKSP_DIR . '/inc/widgets/class-base.php';

		$widgets = array(
			'hero'          => 'Hero',
			'page-header'   => 'Page_Header',
			'contact-bar'   => 'Contact_Bar',
			'why'           => 'Why',
			'areas'         => 'Areas',
			'team'          => 'Team',
			'references'    => 'References',
			'training'      => 'Training',
			'news'          => 'News',
			'section-head'  => 'Section_Head',
		);

		foreach ( $widgets as $file => $class ) {
			require_once AKKSP_DIR . '/inc/widgets/class-' . $file . '.php';
			$fqcn = '\\AKKSP\\Widgets\\' . $class;
			$widgets_manager->register( new $fqcn() );
		}
	}
);

/**
 * Při aktivaci tématu: barvy a písma do globálního nastavení Elementoru (Site Settings)
 * a vypnutí výchozích barev/písem Elementoru. Existující položky se nepřepisují.
 */
function akksp_setup_elementor_kit() {
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );
	update_option( 'elementor_font_display', 'swap' );

	$kit_id = (int) get_option( 'elementor_active_kit' );
	if ( ! $kit_id ) {
		return;
	}
	$settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
	$settings = is_array( $settings ) ? $settings : array();

	$colors = array(
		'akcream'  => array( 'Krémová', '#F5F1EE' ),
		'akpaper'  => array( 'Papír', '#FAF8F4' ),
		'akink'    => array( 'Inkoust', '#0A0E45' ),
		'akbody'   => array( 'Text', '#111335' ),
		'aknavy'   => array( 'Navy', '#001628' ),
		'aknavy2'  => array( 'Navy 850', '#02182C' ),
		'aknavy3'  => array( 'Navy 700', '#123249' ),
		'akgold'   => array( 'Zlatá', '#F8C46F' ),
		'akgold2'  => array( 'Zlatá výplň', '#FCCE85' ),
		'akgold3'  => array( 'Zlatá linka', '#DEB66C' ),
		'aksand'   => array( 'Písek', '#FCE4C4' ),
		'akamber'  => array( 'Jantar', '#E29421' ),
	);
	$existing = isset( $settings['custom_colors'] ) && is_array( $settings['custom_colors'] ) ? $settings['custom_colors'] : array();
	$ids      = wp_list_pluck( $existing, '_id' );
	foreach ( $colors as $id => $c ) {
		if ( ! in_array( $id, $ids, true ) ) {
			$existing[] = array( '_id' => $id, 'title' => 'AKKSP ' . $c[0], 'color' => $c[1] );
		}
	}
	$settings['custom_colors'] = $existing;

	$type = isset( $settings['custom_typography'] ) && is_array( $settings['custom_typography'] ) ? $settings['custom_typography'] : array();
	$tids = wp_list_pluck( $type, '_id' );
	$typo = array(
		'akserif' => array( 'AKKSP Nadpis', 'Source Serif 4', '600' ),
		'aksans'  => array( 'AKKSP Text', 'IBM Plex Sans', '400' ),
		'akmono'  => array( 'AKKSP Štítek', 'IBM Plex Mono', '500' ),
	);
	foreach ( $typo as $id => $t ) {
		if ( ! in_array( $id, $tids, true ) ) {
			$type[] = array(
				'_id'                    => $id,
				'title'                  => $t[0],
				'typography_typography'  => 'custom',
				'typography_font_family' => $t[1],
				'typography_font_weight' => $t[2],
			);
		}
	}
	$settings['custom_typography'] = $type;

	// Výchozí písmo a barva textu celé stránky.
	$settings['body_typography_typography']  = 'custom';
	$settings['body_typography_font_family'] = 'IBM Plex Sans';
	$settings['body_color']                  = '#111335';

	update_post_meta( $kit_id, '_elementor_page_settings', $settings );

	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}
add_action( 'after_switch_theme', 'akksp_setup_elementor_kit' );

/**
 * Editor Elementoru: v náhledu vše hned viditelné (bez čekání na odkrytí).
 */
add_action(
	'elementor/preview/enqueue_styles',
	function () {
		wp_add_inline_style( 'akksp-wp', '.ak-reveal,.ak-mask{opacity:1!important;transform:none!important;clip-path:none!important}' );
	}
);
