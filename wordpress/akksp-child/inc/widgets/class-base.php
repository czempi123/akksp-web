<?php
/**
 * Společný základ widgetů AKKSP.
 *
 * @package AKKSP
 */

namespace AKKSP\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

abstract class Base extends Widget_Base {

	public function get_categories() {
		return array( 'akksp' );
	}

	public function get_keywords() {
		return array( 'akksp', 'sekce' );
	}

	/** Bez vnitřních mezer Elementoru — sekce si řídí odsazení samy. */
	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	/* ------------------------------------------------------------------ */
	/* Ovládací prvky                                                     */
	/* ------------------------------------------------------------------ */

	/**
	 * Štítek, nadpis, úvodní text a tlačítko sekce.
	 *
	 * @param array $d Výchozí hodnoty: label, title, lead, btn_text, btn_url.
	 */
	protected function add_head_controls( array $d ) {
		$this->add_control(
			'label',
			array(
				'label'       => __( 'Štítek nad nadpisem (nepovinný)', 'akksp' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $d['label'] ?? '',
				'label_block' => true,
			)
		);
		$this->add_control(
			'title',
			array(
				'label'       => __( 'Nadpis', 'akksp' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $d['title'] ?? '',
				'label_block' => true,
			)
		);
		if ( array_key_exists( 'lead', $d ) ) {
			$this->add_control(
				'lead',
				array(
					'label'   => __( 'Úvodní text', 'akksp' ),
					'type'    => Controls_Manager::TEXTAREA,
					'rows'    => 3,
					'default' => $d['lead'],
				)
			);
		}
		if ( array_key_exists( 'btn_text', $d ) ) {
			$this->add_control(
				'btn_text',
				array(
					'label'     => __( 'Text tlačítka', 'akksp' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => $d['btn_text'],
					'separator' => 'before',
				)
			);
			$this->add_control(
				'btn_link',
				array(
					'label'   => __( 'Odkaz tlačítka', 'akksp' ),
					'type'    => Controls_Manager::URL,
					'default' => array( 'url' => $d['btn_url'] ?? '' ),
				)
			);
		}
	}

	/* ------------------------------------------------------------------ */
	/* Pomocníci pro výstup                                               */
	/* ------------------------------------------------------------------ */

	protected function url( $link, $fallback = '#' ) {
		if ( is_array( $link ) && ! empty( $link['url'] ) ) {
			return $link['url'];
		}
		return $fallback;
	}

	/** Atributy target/rel pro odkaz z ovládacího prvku URL. */
	protected function link_extra( $link ) {
		$out = '';
		if ( is_array( $link ) && ! empty( $link['is_external'] ) ) {
			$out .= ' target="_blank"';
		}
		$rel = array();
		if ( is_array( $link ) && ! empty( $link['is_external'] ) ) {
			$rel[] = 'noopener';
		}
		if ( is_array( $link ) && ! empty( $link['nofollow'] ) ) {
			$rel[] = 'nofollow';
		}
		if ( $rel ) {
			$out .= ' rel="' . esc_attr( implode( ' ', $rel ) ) . '"';
		}
		return $out;
	}

	/** <img> z ovládacího prvku MEDIA (s velikostmi z knihovny médií), jinak výchozí soubor tématu. */
	protected function image( $media, $fallback, $size = 'large', $attrs = array() ) {
		$attrs = wp_parse_args( $attrs, array( 'alt' => '', 'loading' => 'lazy' ) );
		if ( is_array( $media ) && ! empty( $media['id'] ) ) {
			return wp_get_attachment_image( (int) $media['id'], $size, false, $attrs );
		}
		$src  = ( is_array( $media ) && ! empty( $media['url'] ) ) ? $media['url'] : $fallback;
		$html = '<img src="' . esc_url( $src ) . '"';
		foreach ( $attrs as $k => $v ) {
			$html .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
		}
		return $html . '>';
	}

	/** Výchozí hodnota obrázku pro ovládací prvek MEDIA. */
	protected function media_default( $file ) {
		return array( 'url' => akksp_img( $file ) );
	}

	/** Hlavička sekce: (nepovinný štítek) nadpis + úvod | akce vpravo. */
	protected function render_head( array $s, $action_html = '' ) {
		echo '<div class="ak-head ak-grid">';
		echo '<div class="ak-head__title">';
		echo akksp_label( $s['label'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput
		if ( ! empty( $s['title'] ) ) {
			echo '<h2 class="ak-h2 ak-mask">' . esc_html( $s['title'] ) . '</h2>';
		}
		if ( ! empty( $s['lead'] ) ) {
			echo '<p class="ak-lead">' . esc_html( $s['lead'] ) . '</p>';
		}
		echo '</div>';
		if ( $action_html ) {
			echo '<div class="ak-head__action">' . $action_html . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
		}
		echo '</div>';
	}

	/** Boční hlavička (Proč AKKSP, Tým, Reference). */
	protected function render_side_head( array $s, $class, $action_html = '' ) {
		echo '<div class="' . esc_attr( $class ) . '">';
		echo akksp_label( $s['label'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput
		echo '<div>';
		if ( ! empty( $s['title'] ) ) {
			echo '<h2 class="ak-h2 ak-mask">' . esc_html( $s['title'] ) . '</h2>';
		}
		if ( ! empty( $s['lead'] ) ) {
			echo '<p class="ak-lead" style="margin-top:16px">' . esc_html( $s['lead'] ) . '</p>';
		}
		echo '</div>';
		echo $action_html; // phpcs:ignore WordPress.Security.EscapeOutput
		echo '</div>';
	}

	/** Stejné ID uvnitř stránky i při více instancích widgetu. */
	protected function uid( $suffix ) {
		return 'ak-' . $this->get_id() . '-' . $suffix;
	}
}
