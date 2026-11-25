<?php
/**
 * Reference: karty s citací + řada log klientů.
 *
 * @package AKKSP
 */

namespace AKKSP\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class References extends Base {

	public function get_name() {
		return 'akksp-references';
	}

	public function get_title() {
		return __( 'AKKSP · Reference', 'akksp' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array( 'label' => __( 'Hlavička', 'akksp' ) ) );
		$this->add_head_controls(
			array(
				'label'    => '',
				'title'    => 'Co říkají klienti',
				'lead'     => 'Důvěra našich klientů je pro nás největší motivací. Přinášíme několik vybraných referencí.',
				'btn_text' => 'Další reference',
				'btn_url'  => '/reference/',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'quotes_s', array( 'label' => __( 'Citace', 'akksp' ) ) );
		$r = new Repeater();
		$r->add_control( 'quote', array( 'label' => __( 'Citace', 'akksp' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 4, 'default' => '' ) );
		$r->add_control( 'name', array( 'label' => __( 'Jméno', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$r->add_control( 'company', array( 'label' => __( 'Firma / kontext', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$r->add_control( 'logo', array( 'label' => __( 'Logo (bílé, nepovinné)', 'akksp' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => '' ) ) );
		$this->add_control(
			'quotes',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ name }}}',
				'default'     => array(
					array(
						'quote'   => 'Během naší spolupráce jsme se opakovaně setkali s vysoce profesionálním přístupem, odbornou erudicí a spolehlivostí.',
						'name'    => 'Jiří Gerlich',
						'company' => 'Media CZ, s.r.o.',
						'logo'    => $this->media_default( 'demo/mediacz-logo-bile.png' ),
					),
					array(
						'quote'   => 'Chtěla bych tímto velmi poděkovat paní advokátce Mgr. Dorotě Filipczyk za poskytnuté služby při likvidaci obchodní společnosti v Polsku.',
						'name'    => 'Dagmar Šefčíková',
						'company' => 'Likvidace společnosti v PL',
					),
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'clients_s', array( 'label' => __( 'Loga klientů', 'akksp' ) ) );
		$this->add_control( 'clients_label', array( 'label' => __( 'Popisek', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Reference' ) );
		$this->add_control( 'clients', array( 'label' => __( 'Loga (zobrazí se jednobarevně)', 'akksp' ), 'type' => Controls_Manager::GALLERY, 'default' => array() ) );
		$this->end_controls_section();
	}

	/** Iniciály ze jména (pro odznak bez loga). */
	private function initials( $name ) {
		$parts = preg_split( '/\s+/u', trim( (string) $name ) );
		$out   = '';
		foreach ( array_slice( $parts, 0, 2 ) as $p ) {
			$out .= mb_substr( $p, 0, 1 );
		}
		return mb_strtoupper( $out );
	}

	protected function render() {
		$s   = $this->get_settings_for_display();
		$btn = akksp_button( $s['btn_text'], $this->url( $s['btn_link'] ), 'secondary', array( 'attrs' => trim( $this->link_extra( $s['btn_link'] ) ) ) );
		?>
<section class="ak-refs ak-light ak-section">
	<div class="ak-wrap ak-grid ak-refs__grid">
		<?php $this->render_side_head( $s, 'ak-refs__head', $btn ); ?>
		<div class="ak-refs__cards">
			<?php foreach ( (array) $s['quotes'] as $i => $q ) : ?>
			<figure class="ak-ref ak-card ak-reveal" style="--i:<?php echo (int) $i; ?>">
				<svg class="ak-ref__q" aria-hidden="true"><use href="#i-quote"/></svg>
				<blockquote><p><?php echo esc_html( $q['quote'] ); ?></p></blockquote>
				<figcaption class="ak-ref__who">
					<?php if ( ! empty( $q['logo']['url'] ) || ! empty( $q['logo']['id'] ) ) : ?>
					<span class="ak-ref__badge"><?php echo $this->image( $q['logo'], '', 'medium', array( 'alt' => $q['company'] ) ); // phpcs:ignore ?></span>
					<?php else : ?>
					<span class="ak-ref__badge ak-ref__badge--mono" aria-hidden="true"><?php echo esc_html( $this->initials( $q['name'] ) ); ?></span>
					<?php endif; ?>
					<span><b><?php echo esc_html( $q['name'] ); ?></b><span class="ak-mono"><?php echo esc_html( $q['company'] ); ?></span></span>
				</figcaption>
			</figure>
			<?php endforeach; ?>
		</div>
		<?php if ( ! empty( $s['clients'] ) ) : ?>
		<div class="ak-clients">
			<span class="ak-mono"><?php echo esc_html( $s['clients_label'] ); ?></span>
			<ul>
				<?php foreach ( $s['clients'] as $logo ) : ?>
				<li><?php echo $this->image( $logo, '', 'medium', array( 'alt' => get_post_meta( (int) ( $logo['id'] ?? 0 ), '_wp_attachment_image_alt', true ) ) ); // phpcs:ignore ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</section>
		<?php
	}
}
