<?php
/**
 * Oblasti právní pomoci: skleněné karty 3×3 s rozbalením „+ → ×".
 *
 * @package AKKSP
 */

namespace AKKSP\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Areas extends Base {

	public function get_name() {
		return 'akksp-areas';
	}

	public function get_title() {
		return __( 'AKKSP · Oblasti právní pomoci', 'akksp' );
	}

	public function get_icon() {
		return 'eicon-accordion';
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array( 'label' => __( 'Hlavička', 'akksp' ) ) );
		$this->add_head_controls(
			array(
				'label'    => 'Oblasti naší právní pomoci',
				'title'    => 'S čím vám pomůžeme?',
				'lead'     => 'Široké spektrum právních služeb pro firmy a podnikatele.',
				'btn_text' => 'Všechny oblasti právní pomoci',
				'btn_url'  => '',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'items_s', array( 'label' => __( 'Oblasti', 'akksp' ) ) );
		$this->add_control( 'list_label', array( 'label' => __( 'Nadpis seznamu po rozbalení', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Pomůžeme vám' ) );
		$this->add_control( 'columns_note', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => __( 'Oblasti se řadí do 3 sloupců shora dolů (1–3 první sloupec, 4–6 druhý …).', 'akksp' ), 'content_classes' => 'elementor-descriptor' ) );
		$r = new Repeater();
		$r->add_control( 'title', array( 'label' => __( 'Název', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => '', 'label_block' => true ) );
		$r->add_control( 'sub', array( 'label' => __( 'Upřesnění', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => '', 'label_block' => true ) );
		$r->add_control( 'items', array( 'label' => __( 'Situace (každá na nový řádek)', 'akksp' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 5, 'default' => '' ) );
		$r->add_control( 'link', array( 'label' => __( 'Odkaz na detail (nepovinný)', 'akksp' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '' ) ) );
		$this->add_control(
			'areas',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => 'Smlouvy', 'sub' => 'a obchodní vztahy', 'items' => "sepsat smlouvu\nvyjednat smlouvu\nkdyž sjednáváte úvěr\nkdyž poskytujete zápůjčku" ),
					array( 'title' => 'Spory', 'sub' => 'a vymáhání pohledávek', 'items' => "když vám zákazník neplatí\nbránit se žalobě\nv soudním řízení\nnarovnat spor\nuzavřít smír" ),
					array( 'title' => 'Duševní vlastnictví', 'sub' => 'a ochrana know-how', 'items' => "přihlásit ochrannou známku\npřihlásit patent\nchránit průmyslové vlastnictví\nchránit duševní vlastnictví" ),
					array( 'title' => 'Zaměstnanci', 'sub' => 'a pracovní právo', 'items' => "ve sporu o neplatnost výpovědi\nv jednání s odborovou organizací\nodškodnit pracovní úraz\nproškolit zaměstnance" ),
					array( 'title' => 'Korporátní agenda', 'sub' => 'a restrukturalizace', 'items' => "předat rodinnou firmu dětem\njednat s péčí řádného hospodáře\nzřídit nebo zrušit organizační složku" ),
					array( 'title' => 'Regulace a compliance', 'sub' => '(AML, GDPR aj.)', 'items' => "s ochranou osobních údajů\ns vnitřními předpisy\nkdyž k vám přijde kontrola" ),
					array( 'title' => 'Nemovitosti', 'sub' => 'a výstavba', 'items' => "převést nemovitost\npři prodeji nemovitosti\nzřídit věcné břemeno\nna katastru nemovitostí" ),
					array( 'title' => 'Přeshraniční podnikání', 'sub' => '(CZ / PL)', 'items' => "podnikat v Polsku\nzřídit nebo zrušit organizační složku\ns právníkem, který mluví česky i polsky" ),
					array( 'title' => 'M&A a strategie', 'sub' => '(akvizice, prodeje, fúze)', 'items' => "provést due diligence\ns fúzí\nkdyž kupujete závod" ),
				),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$areas = array_values( (array) $s['areas'] );
		$total = count( $areas );
		$cols  = $total ? array_chunk( $areas, (int) ceil( $total / 3 ), true ) : array();
		$btn   = akksp_button( $s['btn_text'], $this->url( $s['btn_link'] ), 'secondary', array( 'attrs' => trim( $this->link_extra( $s['btn_link'] ) ) ) );
		?>
<section class="ak-areas ak-dark ak-section">
	<div class="ak-wrap">
		<?php $this->render_head( $s, $btn ); ?>
		<div class="ak-areas__cols">
			<?php foreach ( $cols as $col ) : ?>
			<div class="ak-areas__col">
				<?php
				foreach ( $col as $i => $a ) :
					$pid   = $this->uid( 'area-' . $i );
					$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $a['items'] ) ), 'strlen' );
					?>
				<div class="ak-area ak-glass ak-reveal" style="--i:<?php echo (int) $i; ?>">
					<button type="button" class="ak-area__btn" aria-expanded="false" aria-controls="<?php echo esc_attr( $pid ); ?>">
						<span class="ak-area__title"><?php echo esc_html( $a['title'] ); ?></span>
						<span class="ak-area__sub"><?php echo esc_html( $a['sub'] ); ?></span>
						<span class="ak-px" aria-hidden="true"><span class="ak-px__ico"></span></span>
					</button>
					<div class="ak-acc__panel" id="<?php echo esc_attr( $pid ); ?>">
						<div><div class="ak-area__body">
							<?php if ( $s['list_label'] ) : ?><p class="ak-label"><?php echo esc_html( $s['list_label'] ); ?></p><?php endif; ?>
							<?php if ( $lines ) : ?>
							<ul class="ak-list-sq"><?php foreach ( $lines as $line ) { echo '<li>' . esc_html( $line ) . '</li>'; } ?></ul>
							<?php endif; ?>
							<?php if ( ! empty( $a['link']['url'] ) ) : ?>
								<?php echo akksp_text_link( __( 'Více o oblasti', 'akksp' ), $a['link']['url'], trim( $this->link_extra( $a['link'] ) ) ); // phpcs:ignore ?>
							<?php endif; ?>
						</div></div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
		<?php
	}
}
