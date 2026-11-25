<?php
/**
 * Hero: střídání fotek kanceláře (bez rámečku), nadpis, kontaktní tlačítka
 * a pod ním samostatný pruh rozcestníku.
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

	/** Výchozí fotky: zasedačka, schodiště, jednací místnost. */
	private function default_slides() {
		return array(
			array( 'id' => 0, 'url' => akksp_img( 'demo/hero-zasedacka.jpg' ) ),
			array( 'id' => 0, 'url' => akksp_img( 'demo/schody.jpg' ) ),
			array( 'id' => 0, 'url' => akksp_img( 'demo/jednaci-mistnost.jpg' ) ),
		);
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Text', 'akksp' ) ) );
		$this->add_control( 'title', array( 'label' => __( 'Nadpis', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Právní partner v', 'label_block' => true, 'description' => __( 'Jednopísmenná předložka na konci (v, s, k…) se přesune na druhý řádek ke zvýrazněné části.', 'akksp' ) ) );
		$this->add_control( 'highlight', array( 'label' => __( 'Zvýrazněná část nadpisu (zlatě, na novém řádku)', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Česku a Polsku.', 'label_block' => true ) );
		$this->add_control( 'sub', array( 'label' => __( 'Podnadpis', 'akksp' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => 'Komplexní právní podpora pro firmy, podnikatele i jednotlivce. Srozumitelně, spolehlivě a s důrazem na praktická řešení.' ) );
		$this->add_control( 'btn_text', array( 'label' => __( 'Hlavní tlačítko', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Kontaktovat nás', 'separator' => 'before', 'description' => __( 'Otevře kontaktní panel (odkaz #kontakt). Jiný odkaz lze zadat níže.', 'akksp' ) ) );
		$this->add_control( 'btn_link', array( 'label' => __( 'Odkaz hlavního tlačítka', 'akksp' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#kontakt' ) ) );
		$this->add_control( 'show_phone', array( 'label' => __( 'Druhé tlačítko s telefonem', 'akksp' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'description' => __( 'Číslo z Přizpůsobit → AKKSP: kontakty a firma.', 'akksp' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'media_s', array( 'label' => __( 'Fotky (prolínání)', 'akksp' ) ) );
		$this->add_control(
			'slides',
			array(
				'label'       => __( 'Fotky na pozadí', 'akksp' ),
				'type'        => Controls_Manager::GALLERY,
				'default'     => $this->default_slides(),
				'description' => __( '1 fotka = statické pozadí. Více fotek se samo pomalu prolíná s jemným přiblížením (bez ovládacích prvků); při omezeném pohybu zůstává první fotka.', 'akksp' ),
			)
		);
		$this->add_control( 'interval', array( 'label' => __( 'Délka jedné fotky (s)', 'akksp' ), 'type' => Controls_Manager::NUMBER, 'min' => 4, 'max' => 20, 'default' => 7 ) );
		$this->end_controls_section();

		$this->start_controls_section( 'routes_s', array( 'label' => __( 'Rozcestník (pruh pod hero)', 'akksp' ) ) );
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

	/** Nadpis: první řádek + <br> + (předložka) zvýraznění. */
	private function heading( $title, $highlight ) {
		$title     = trim( (string) $title );
		$highlight = trim( (string) $highlight );
		if ( '' === $highlight ) {
			return akksp_typo( $title );
		}
		$carry = '';
		if ( preg_match( '/^(.*\S)\s+(\S)$/u', $title, $m ) ) { // „Právní partner v" → „v" patří na druhý řádek
			$title = $m[1];
			$carry = esc_html( $m[2] ) . '&nbsp;';
		}
		return ( '' !== $title ? akksp_typo( $title ) . '<br>' : '' ) . $carry . '<em>' . akksp_typo( $highlight ) . '</em>';
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$h1_id  = $this->uid( 'title' );
		$slides = ! empty( $s['slides'] ) ? array_values( $s['slides'] ) : $this->default_slides();
		$ms     = max( 4, min( 20, (int) ( $s['interval'] ?? 7 ) ) ) * 1000;
		$multi  = count( $slides ) > 1;
		$phone  = akksp_contact( 'phone' );
		$link   = $s['btn_link'] ?? array( 'url' => '#kontakt' );
		$href   = $this->url( $link, '#kontakt' );
		?>
<section class="ak-hero ak-dark" aria-labelledby="<?php echo esc_attr( $h1_id ); ?>"<?php echo $multi ? ' data-slides style="--ak-slide-ms:' . (int) $ms . 'ms"' : ''; ?>>
	<div class="ak-hero__media" data-slides-media>
		<?php
		foreach ( $slides as $i => $slide ) {
			$attrs = array(
				'class'   => 'ak-hero__slide' . ( 0 === $i ? ' is-active' : '' ),
				'alt'     => '',
				'loading' => 0 === $i ? 'eager' : 'lazy',
			);
			if ( 0 === $i ) {
				$attrs['fetchpriority'] = 'high';
			}
			echo $this->image( $slide, akksp_img( 'demo/hero-zasedacka.jpg' ), 'full', $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
		?>
	</div>
	<div class="ak-wrap ak-hero__content">
		<div class="ak-hero__text">
			<h1 id="<?php echo esc_attr( $h1_id ); ?>" class="ak-display"><?php echo $this->heading( $s['title'] ?? '', $s['highlight'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h1>
			<?php if ( ! empty( $s['sub'] ) ) : ?>
			<p class="ak-lead"><?php echo akksp_typo( $s['sub'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
			<div class="ak-hero__actions">
				<?php
				$attrs = '#kontakt' === $href ? 'aria-controls="kontakt" data-contact-open' : trim( $this->link_extra( $link ) );
				echo akksp_button( $s['btn_text'] ?? '', $href, 'primary', array( 'attrs' => $attrs ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				if ( 'yes' === ( $s['show_phone'] ?? 'yes' ) && $phone ) {
					echo akksp_button( $phone, akksp_tel_href( $phone ), 'glass', array( 'icon' => 'phone' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				}
				?>
			</div>
		</div>
	</div>
</section>
		<?php if ( ! empty( $s['routes'] ) ) : ?>
<section class="ak-routes ak-light" id="rozcestnik" aria-label="<?php esc_attr_e( 'Rozcestník', 'akksp' ); ?>">
	<div class="ak-wrap">
		<div class="ak-routes__list">
			<?php foreach ( $s['routes'] as $i => $route ) : ?>
			<a class="ak-route ak-card ak-reveal" style="--i:<?php echo (int) $i; ?>" href="<?php echo esc_url( $this->url( $route['link'] ?? array(), '#rozcestnik' ) ); ?>"<?php echo $this->link_extra( $route['link'] ?? array() ); // phpcs:ignore ?>>
				<h2 class="ak-h3 ak-route__title"><?php echo esc_html( $route['title'] ); ?></h2>
				<p><?php echo esc_html( $route['text'] ); ?></p>
				<span class="ak-route__go ak-arrow-box" aria-hidden="true"><?php echo akksp_icon( 'arrow' ); // phpcs:ignore ?></span>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
		<?php endif; ?>
		<?php
	}
}
