<?php
/**
 * Záhlaví podstránky: tmavý pruh pod skleněnou navigací.
 *
 * @package AKKSP
 */

namespace AKKSP\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Page_Header extends Base {

	public function get_name() {
		return 'akksp-page-header';
	}

	public function get_title() {
		return __( 'AKKSP · Záhlaví podstránky', 'akksp' );
	}

	public function get_icon() {
		return 'eicon-header';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Obsah', 'akksp' ) ) );
		$this->add_control( 'label', array( 'label' => __( 'Štítek', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Pro firmy', 'label_block' => true ) );
		$this->add_control( 'title', array( 'label' => __( 'Nadpis (H1)', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Právní servis pro stabilní fungování a růst firmy', 'label_block' => true ) );
		$this->add_control( 'lead', array( 'label' => __( 'Úvodní text', 'akksp' ), 'type' => Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => '' ) );
		$this->add_control( 'image', array( 'label' => __( 'Fotka (nepovinná)', 'akksp' ), 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => '' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s         = $this->get_settings_for_display();
		$has_image = ! empty( $s['image']['url'] ) || ! empty( $s['image']['id'] );
		?>
<section class="ak-page-header ak-dark">
		<?php if ( $has_image ) : ?>
	<div class="ak-photo"><?php echo $this->image( $s['image'], '', 'full', array( 'loading' => 'eager' ) ); // phpcs:ignore ?></div>
		<?php endif; ?>
	<div class="ak-wrap ak-grid">
		<div class="ak-page-header__inner">
			<?php echo akksp_label( $s['label'] ); // phpcs:ignore ?>
			<h1 class="ak-display"><?php echo esc_html( $s['title'] ); ?></h1>
			<?php if ( ! empty( $s['lead'] ) ) : ?><p class="ak-lead"><?php echo esc_html( $s['lead'] ); ?></p><?php endif; ?>
		</div>
	</div>
</section>
		<?php
	}
}
