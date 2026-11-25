<?php
/**
 * Proč AKKSP: dlaždice s čísly (měkký reliéf na krémové).
 *
 * @package AKKSP
 */

namespace AKKSP\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Why extends Base {

	public function get_name() {
		return 'akksp-why';
	}

	public function get_title() {
		return __( 'AKKSP · Proč AKKSP (čísla)', 'akksp' );
	}

	public function get_icon() {
		return 'eicon-number-field';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Hlavička', 'akksp' ) ) );
		$this->add_control( 'label', array( 'label' => __( 'Štítek', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => '', 'label_block' => true ) );
		$this->add_control( 'title', array( 'label' => __( 'Nadpis', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Proč právě AKKSP?', 'label_block' => true ) );
		$this->add_control( 'link_text', array( 'label' => __( 'Text odkazu', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Více o nás' ) );
		$this->add_control( 'link', array( 'label' => __( 'Odkaz', 'akksp' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'tiles_s', array( 'label' => __( 'Dlaždice', 'akksp' ) ) );
		$r = new Repeater();
		$r->add_control( 'value', array( 'label' => __( 'Číslo nebo slovo', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => '30+' ) );
		$r->add_control( 'text', array( 'label' => __( 'Popis', 'akksp' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => '' ) );
		$r->add_control( 'highlight', array( 'label' => __( 'Zvýraznit (jemný zlatý obrys, „&" zlatě)', 'akksp' ), 'type' => Controls_Manager::SWITCHER, 'default' => '' ) );
		$this->add_control(
			'tiles',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ value }}}',
				'default'     => array(
					array( 'value' => '30+', 'text' => 'let zkušeností v právním poradenství' ),
					array( 'value' => '15+', 'text' => 'členný tým odborníků' ),
					array( 'value' => 'Česko & Polsko', 'text' => 'jedna kancelář, široké možnosti', 'highlight' => 'yes' ),
					array( 'value' => 'Praktická řešení', 'text' => 'srozumitelná, efektivní a s důrazem na výsledek' ),
				),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
<section class="ak-why ak-light ak-section">
	<div class="ak-wrap ak-grid ak-why__grid">
		<div class="ak-why__head">
			<?php echo akksp_label( $s['label'] ); // phpcs:ignore ?>
			<h2 class="ak-h2 ak-mask"><?php echo esc_html( $s['title'] ); ?></h2>
			<?php echo akksp_text_link( $s['link_text'], $this->url( $s['link'] ), $this->link_extra( $s['link'] ) ); // phpcs:ignore ?>
		</div>
		<div class="ak-why__tiles">
			<?php foreach ( (array) $s['tiles'] as $i => $t ) : ?>
				<?php
				// Krátké hodnoty s číslicí = velké číslo, jinak slovo.
				$is_num = (bool) preg_match( '/\d/', (string) $t['value'] ) && mb_strlen( (string) $t['value'] ) <= 6;
				?>
			<div class="ak-tile<?php echo ! empty( $t['highlight'] ) ? ' ak-tile--accent' : ''; ?> ak-reveal" style="--i:<?php echo (int) $i; ?>">
				<span class="<?php echo $is_num ? 'ak-why__num' : 'ak-why__word'; ?>"><?php echo str_replace( '&amp;', '<span class="ak-amp">&amp;</span>', esc_html( $t['value'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<p><?php echo esc_html( $t['text'] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
		<?php
	}
}
