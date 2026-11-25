<?php
/**
 * Hlavička sekce (štítek | nadpis | akce) pro libovolný obsah podstránek.
 *
 * @package AKKSP
 */

namespace AKKSP\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Section_Head extends Base {

	public function get_name() {
		return 'akksp-section-head';
	}

	public function get_title() {
		return __( 'AKKSP · Hlavička sekce', 'akksp' );
	}

	public function get_icon() {
		return 'eicon-heading';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Obsah', 'akksp' ) ) );
		$this->add_head_controls(
			array(
				'label'    => '01 · Název sekce',
				'title'    => 'Nadpis sekce',
				'lead'     => '',
				'btn_text' => '',
				'btn_url'  => '',
			)
		);
		$this->add_control(
			'surface',
			array(
				'label'     => __( 'Plocha', 'akksp' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'light',
				'options'   => array( 'light' => __( 'Krémová', 'akksp' ), 'paper' => __( 'Papír', 'akksp' ), 'dark' => __( 'Navy', 'akksp' ) ),
				'separator' => 'before',
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$surface = in_array( $s['surface'], array( 'light', 'paper', 'dark' ), true ) ? $s['surface'] : 'light';
		$btn     = akksp_button( $s['btn_text'], $this->url( $s['btn_link'] ), 'secondary', array( 'attrs' => trim( $this->link_extra( $s['btn_link'] ) ) ) );
		echo '<div class="ak-' . esc_attr( $surface ) . ' ak-section-head"><div class="ak-wrap">';
		$this->render_head( $s, $btn );
		echo '</div></div>';
	}
}
