<?php
/**
 * Hero: fotka, skleněný panel, přepínač CZ / PL, rozcestník.
 *
 * @package AKKSP
 */

namespace AKKSP\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Hero extends Base {

	public function get_name() {
		return 'akksp-hero';
	}

	public function get_title() {
		return __( 'AKKSP · Hero + rozcestník', 'akksp' );
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Text (CZ)', 'akksp' ) ) );
		$this->add_control( 'label', array( 'label' => __( 'Štítek', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Advokátní kancelář AKKSP', 'label_block' => true ) );
		$this->add_control( 'title', array( 'label' => __( 'Nadpis', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Právní partner v', 'label_block' => true ) );
		$this->add_control( 'highlight', array( 'label' => __( 'Zvýrazněná část nadpisu (zlatě)', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Česku a Polsku.', 'label_block' => true ) );
		$this->add_control( 'sub', array( 'label' => __( 'Podnadpis', 'akksp' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => 'Komplexní právní podpora pro firmy, podnikatele i jednotlivce. Srozumitelně, spolehlivě a s důrazem na praktická řešení.' ) );
		$this->add_control( 'cities', array( 'label' => __( 'Města (oddělit čárkou, první je zvýrazněné)', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Třinec, Praha, Krakov', 'label_block' => true ) );
		$this->add_control( 'image', array( 'label' => __( 'Fotka na pozadí', 'akksp' ), 'type' => Controls_Manager::MEDIA, 'default' => $this->media_default( 'demo/hero-zasedacka.jpg' ), 'separator' => 'before' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'lang', array( 'label' => __( 'Přepínač CZ / PL', 'akksp' ) ) );
		$this->add_control(
			'lang_mode',
			array(
				'label'       => __( 'Režim', 'akksp' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'polylang',
				'options'     => array(
					'polylang' => __( 'Odkazy na jazykové verze (Polylang)', 'akksp' ),
					'inline'   => __( 'Přepnout text přímo v hero', 'akksp' ),
					'none'     => __( 'Nezobrazovat', 'akksp' ),
				),
				'description' => __( 'Bez Polylangu se režim „odkazy" automaticky přepne na „text v hero".', 'akksp' ),
			)
		);
		$this->add_control( 'label_pl', array( 'label' => __( 'Štítek (PL)', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Kancelaria adwokacka AKKSP', 'label_block' => true, 'condition' => array( 'lang_mode' => 'inline' ) ) );
		$this->add_control( 'title_pl', array( 'label' => __( 'Nadpis (PL)', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Partner prawny w', 'label_block' => true, 'condition' => array( 'lang_mode' => 'inline' ) ) );
		$this->add_control( 'highlight_pl', array( 'label' => __( 'Zvýraznění (PL)', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Czechach i w Polsce.', 'label_block' => true, 'condition' => array( 'lang_mode' => 'inline' ) ) );
		$this->add_control( 'sub_pl', array( 'label' => __( 'Podnadpis (PL)', 'akksp' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => 'Kompleksowe wsparcie prawne dla firm, przedsiębiorców i osób prywatnych. Zrozumiale, rzetelnie i z naciskiem na praktyczne rozwiązania.', 'condition' => array( 'lang_mode' => 'inline' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'routes_s', array( 'label' => __( 'Rozcestník (karty)', 'akksp' ) ) );
		$r = new Repeater();
		$r->add_control( 'title', array( 'label' => __( 'Nadpis', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => '', 'label_block' => true ) );
		$r->add_control( 'text', array( 'label' => __( 'Text', 'akksp' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => '' ) );
		$r->add_control( 'link', array( 'label' => __( 'Odkaz', 'akksp' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '' ) ) );
		$this->add_control(
			'routes',
			array(
				'label'       => __( 'Karty', 'akksp' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => 'Podnikání v Česku a Polsku', 'text' => 'Právní podpora bez přeshraničních bariér.' ),
					array( 'title' => 'Pro firmy a podnikatele', 'text' => 'Právní servis pro stabilní fungování a růst firmy.' ),
					array( 'title' => 'Pro jednotlivce', 'text' => 'Právní jistota v klíčových životních situacích.' ),
				),
			)
		);
		$this->end_controls_section();
	}

	/** Přepínač jazyka: odkazy (Polylang) nebo tlačítka (přepnutí textu). */
	private function lang_switch( $mode ) {
		if ( 'none' === $mode ) {
			return '';
		}
		$langs = akksp_languages();
		if ( 'polylang' === $mode && count( $langs ) >= 2 ) {
			$current = 'cz';
			$links   = '';
			foreach ( $langs as $l ) {
				$label = strtoupper( 'cs' === $l['slug'] ? 'cz' : $l['slug'] );
				if ( ! empty( $l['current_lang'] ) ) {
					$current = strtolower( $label );
				}
				$links .= sprintf( '<a href="%s" lang="%s"%s>%s</a>', esc_url( $l['url'] ), esc_attr( $l['slug'] ), ! empty( $l['current_lang'] ) ? ' aria-current="true"' : '', esc_html( $label ) );
			}
			// Jezdec ukazuje aktuální jazyk; podporované jsou dva jazyky (CZ, PL), další se jen vypíšou.
			return sprintf( '<nav class="ak-seg" data-lang="%s" aria-label="%s"><span class="ak-seg__thumb" aria-hidden="true"></span>%s</nav>', esc_attr( 'cz' === $current ? 'cz' : 'pl' ), esc_attr__( 'Jazyk', 'akksp' ), $links );
		}
		return '<div class="ak-seg" data-lang="cz" role="group" aria-label="' . esc_attr__( 'Jazyk úvodního textu', 'akksp' ) . '"><span class="ak-seg__thumb" aria-hidden="true"></span><button type="button" aria-pressed="true" data-set="cz">CZ</button><button type="button" aria-pressed="false" data-set="pl">PL</button></div>';
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		// PL texty jsou skryté podmínkou; při náhradním režimu je bereme z uložených hodnot.
		$raw = $this->get_settings();
		foreach ( array( 'label_pl', 'title_pl', 'highlight_pl', 'sub_pl' ) as $k ) {
			if ( ! isset( $s[ $k ] ) && isset( $raw[ $k ] ) ) {
				$s[ $k ] = $raw[ $k ];
			}
		}
		$mode   = $s['lang_mode'] ?? 'polylang';
		$inline = ( 'inline' === $mode ) || ( 'polylang' === $mode && count( akksp_languages() ) < 2 );
		$mode   = $inline ? 'inline' : $mode;

		$swap = function ( $cz, $pl ) use ( $inline ) {
			if ( ! $inline ) {
				return '';
			}
			return sprintf( ' data-cz="%s" data-pl="%s"', esc_attr( $cz ), esc_attr( $pl ) );
		};

		$title_cz = akksp_heading_html( $s['title'], $s['highlight'] );
		$title_pl = akksp_heading_html( $s['title_pl'] ?? '', $s['highlight_pl'] ?? '' );
		$cities   = array_filter( array_map( 'trim', explode( ',', (string) $s['cities'] ) ) );
		$h1_id    = $this->uid( 'title' );
		?>
<section class="ak-hero ak-dark" aria-labelledby="<?php echo esc_attr( $h1_id ); ?>" data-hero>
	<div class="ak-photo"><?php echo $this->image( $s['image'], akksp_img( 'demo/hero-zasedacka.jpg' ), 'full', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); // phpcs:ignore ?></div>
	<div class="ak-hero__light" aria-hidden="true"></div>
	<div class="ak-wrap ak-grid">
		<div class="ak-hero__panel ak-glass">
			<div class="ak-hero__top">
				<p class="ak-label ak-swap"<?php echo $swap( esc_html( $s['label'] ), esc_html( $s['label_pl'] ?? '' ) ); // phpcs:ignore ?>><?php echo esc_html( $s['label'] ); ?></p>
				<?php echo $this->lang_switch( $mode ); // phpcs:ignore ?>
			</div>
			<h1 id="<?php echo esc_attr( $h1_id ); ?>" class="ak-display ak-swap"<?php echo $swap( $title_cz, $title_pl ); // phpcs:ignore ?>><?php echo $title_cz; // phpcs:ignore ?></h1>
			<?php if ( ! empty( $s['sub'] ) ) : ?>
			<p class="ak-lead ak-hero__sub ak-swap"<?php echo $swap( esc_html( $s['sub'] ), esc_html( $s['sub_pl'] ?? '' ) ); // phpcs:ignore ?>><?php echo esc_html( $s['sub'] ); ?></p>
			<?php endif; ?>
			<?php if ( $cities ) : ?>
			<p class="ak-hero__cities ak-mono"><?php foreach ( $cities as $c ) { echo '<span>' . esc_html( $c ) . '</span>'; } ?></p>
			<?php endif; ?>
		</div>
	</div>
		<?php if ( ! empty( $s['routes'] ) ) : $n = count( $s['routes'] ); ?>
	<div class="ak-wrap ak-grid ak-hero__routes" id="rozcestnik">
			<?php foreach ( $s['routes'] as $i => $route ) : ?>
		<a class="ak-route ak-glass ak-reveal" style="--i:<?php echo (int) $i; ?>" href="<?php echo esc_url( $this->url( $route['link'] ?? array(), '#rozcestnik' ) ); ?>"<?php echo $this->link_extra( $route['link'] ?? array() ); // phpcs:ignore ?>>
			<span class="ak-route__meta ak-mono"><?php echo esc_html( sprintf( '%02d / %02d', $i + 1, $n ) ); ?></span>
			<h2 class="ak-h3 ak-route__title"><?php echo esc_html( $route['title'] ); ?></h2>
			<p><?php echo esc_html( $route['text'] ); ?></p>
			<span class="ak-route__go" aria-hidden="true"><?php echo akksp_icon( 'arrow' ); // phpcs:ignore ?></span>
		</a>
			<?php endforeach; ?>
	</div>
		<?php endif; ?>
</section>
		<?php
	}
}
