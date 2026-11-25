<?php
/**
 * Školení a workshopy: fotka + skleněný panel.
 *
 * @package AKKSP
 */

namespace AKKSP\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Training extends Base {

	public function get_name() {
		return 'akksp-training';
	}

	public function get_title() {
		return __( 'AKKSP · Banner s fotkou (Školení)', 'akksp' );
	}

	public function get_icon() {
		return 'eicon-image-box';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Obsah', 'akksp' ) ) );
		$this->add_head_controls(
			array(
				'label'    => '05 · Vzdělávání',
				'title'    => 'Školení a workshopy',
				'lead'     => 'Přinášíme praktické znalosti z oblasti práva pro firmy i jednotlivce.',
				'btn_text' => 'Zobrazit nabídku školení',
				'btn_url'  => '',
			)
		);
		$this->add_control( 'image', array( 'label' => __( 'Fotka', 'akksp' ), 'type' => Controls_Manager::MEDIA, 'default' => $this->media_default( 'demo/jednaci-mistnost.jpg' ), 'separator' => 'before' ) );
		$this->add_control(
			'side',
			array(
				'label'   => __( 'Panel', 'akksp' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array( 'right' => __( 'Vpravo', 'akksp' ), 'left' => __( 'Vlevo', 'akksp' ) ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
<section class="ak-training ak-dark<?php echo 'left' === $s['side'] ? ' ak-training--left' : ''; ?>">
	<div class="ak-photo"><?php echo $this->image( $s['image'], akksp_img( 'demo/jednaci-mistnost.jpg' ), 'full' ); // phpcs:ignore ?></div>
	<div class="ak-section">
		<div class="ak-wrap ak-grid">
			<div class="ak-training__panel ak-glass ak-reveal">
				<?php echo akksp_label( $s['label'] ); // phpcs:ignore ?>
				<h2 class="ak-h2"><?php echo esc_html( $s['title'] ); ?></h2>
				<?php if ( ! empty( $s['lead'] ) ) : ?><p class="ak-lead"><?php echo esc_html( $s['lead'] ); ?></p><?php endif; ?>
				<?php echo akksp_button( $s['btn_text'], $this->url( $s['btn_link'] ), 'primary', array( 'attrs' => trim( $this->link_extra( $s['btn_link'] ) ) ) ); // phpcs:ignore ?>
			</div>
		</div>
	</div>
</section>
		<?php
	}
}
