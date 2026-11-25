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
		$this->add_control( 'image', array( 'label' => __( 'Fotka na pozadí', 'akksp' ), 'type' => Controls_Manager::MEDIA, 'default' => $this->media_default( 'demo/hero-zasedacka.jpg' ), 'separator' => 'before' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'lang', array( 'label' => __( 'Přepínač jazyka', 'akksp' ) ) );
		$this->add_control(
			'show_lang',
			array(
				'label'       => __( 'Zobrazit CZ | PL v panelu', 'akksp' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => __( 'Přepíná celé jazykové mutace webu (Polylang). Texty polské verze se zadávají v polském překladu stránky.', 'akksp' ),
			)
		);
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

	protected function render() {
		$s     = $this->get_settings_for_display();
		$h1_id = $this->uid( 'title' );
		$lang  = 'yes' === ( $s['show_lang'] ?? 'yes' ) ? akksp_lang_switch() : '';
		?>
<section class="ak-hero ak-dark" aria-labelledby="<?php echo esc_attr( $h1_id ); ?>">
	<div class="ak-photo"><?php echo $this->image( $s['image'], akksp_img( 'demo/hero-zasedacka.jpg' ), 'full', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); // phpcs:ignore ?></div>
	<div class="ak-hero__light" aria-hidden="true"></div>
	<div class="ak-wrap ak-grid">
		<div class="ak-hero__panel ak-glass">
			<div class="ak-hero__top">
				<p class="ak-label"><?php echo esc_html( $s['label'] ); ?></p>
				<?php echo $lang; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
			<h1 id="<?php echo esc_attr( $h1_id ); ?>" class="ak-display"><?php echo akksp_heading_html( $s['title'], $s['highlight'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h1>
			<?php if ( ! empty( $s['sub'] ) ) : ?>
			<p class="ak-lead ak-hero__sub"><?php echo esc_html( $s['sub'] ); ?></p>
			<?php endif; ?>
		</div>
	</div>
		<?php if ( ! empty( $s['routes'] ) ) : ?>
	<div class="ak-wrap ak-grid ak-hero__routes" id="rozcestnik">
			<?php foreach ( $s['routes'] as $i => $route ) : ?>
		<a class="ak-route ak-glass ak-reveal" style="--i:<?php echo (int) $i; ?>" href="<?php echo esc_url( $this->url( $route['link'] ?? array(), '#rozcestnik' ) ); ?>"<?php echo $this->link_extra( $route['link'] ?? array() ); // phpcs:ignore ?>>
			<h2 class="ak-h3 ak-route__title"><?php echo esc_html( $route['title'] ); ?></h2>
			<p><?php echo esc_html( $route['text'] ); ?></p>
			<span class="ak-route__go ak-arrow-box" aria-hidden="true"><?php echo akksp_icon( 'arrow' ); // phpcs:ignore ?></span>
		</a>
			<?php endforeach; ?>
	</div>
		<?php endif; ?>
</section>
		<?php
	}
}
