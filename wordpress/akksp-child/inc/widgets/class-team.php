<?php
/**
 * Tým: černobílé portréty, barva na hover.
 *
 * @package AKKSP
 */

namespace AKKSP\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Team extends Base {

	public function get_name() {
		return 'akksp-team';
	}

	public function get_title() {
		return __( 'AKKSP · Tým', 'akksp' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	protected function register_controls() {
		$this->start_controls_section( 'head', array( 'label' => __( 'Hlavička', 'akksp' ) ) );
		$this->add_head_controls(
			array(
				'label'    => '03 · Lidé',
				'title'    => 'Poznejte náš tým',
				'lead'     => 'Zkušení advokáti, kteří rozumí podnikání i životním situacím v Česku a Polsku.',
				'btn_text' => 'Seznam odborníků',
				'btn_url'  => '/nas-tym/',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'people_s', array( 'label' => __( 'Lidé', 'akksp' ) ) );
		$r = new Repeater();
		$r->add_control( 'photo', array( 'label' => __( 'Portrét (4:5)', 'akksp' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => '' ) ) );
		$r->add_control( 'name', array( 'label' => __( 'Jméno', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => '', 'label_block' => true ) );
		$r->add_control( 'role', array( 'label' => __( 'Role', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Advokát' ) );
		$r->add_control( 'link', array( 'label' => __( 'Odkaz na profil', 'akksp' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '' ) ) );
		$this->add_control(
			'people',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ name }}}',
				'default'     => array(
					array( 'name' => 'Mgr. David Troszok', 'role' => 'Advokát', 'photo' => $this->media_default( 'demo/troszok.jpg' ), 'link' => array( 'url' => '/nas-tym/mgr-david-troszok/' ) ),
					array( 'name' => 'Mgr. Dorota Filipczyk', 'role' => 'Advokát', 'photo' => $this->media_default( 'demo/filipczyk.jpg' ), 'link' => array( 'url' => '/nas-tym/mgr-dorota-filipczyk/' ) ),
					array( 'name' => 'Mgr. Ondřej Kadlubiec', 'role' => 'Advokát', 'photo' => $this->media_default( 'demo/kadlubiec.jpg' ), 'link' => array( 'url' => '/nas-tym/mgr-ondrej-kadlubiec/' ) ),
					array( 'name' => 'Mgr. Michaela Kadlubcová Škutová', 'role' => 'Advokát', 'photo' => $this->media_default( 'demo/kadlubcova.jpg' ), 'link' => array( 'url' => '/nas-tym/mgr-michaela-kadlubcova-skutova/' ) ),
				),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s   = $this->get_settings_for_display();
		$btn = akksp_button( $s['btn_text'], $this->url( $s['btn_link'] ), 'secondary', array( 'attrs' => trim( $this->link_extra( $s['btn_link'] ) ) ) );
		?>
<section class="ak-team ak-paper ak-section">
	<div class="ak-wrap ak-grid ak-team__grid">
		<?php $this->render_side_head( $s, 'ak-team__head', $btn ); ?>
		<div class="ak-team__people">
			<?php foreach ( (array) $s['people'] as $i => $p ) : ?>
			<a class="ak-person ak-reveal" style="--i:<?php echo (int) $i; ?>" href="<?php echo esc_url( $this->url( $p['link'] ?? array() ) ); ?>"<?php echo $this->link_extra( $p['link'] ?? array() ); // phpcs:ignore ?>>
				<span class="ak-person__img"><?php echo $this->image( $p['photo'], '', 'akksp-portrait', array( 'alt' => $p['name'], 'width' => 600, 'height' => 750 ) ); // phpcs:ignore ?></span>
				<span class="ak-person__name"><?php echo esc_html( $p['name'] ); ?></span>
				<span class="ak-mono"><?php echo esc_html( $p['role'] ); ?></span>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
		<?php
	}
}
