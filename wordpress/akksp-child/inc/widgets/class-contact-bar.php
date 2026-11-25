<?php
/**
 * Kontaktní lišta (údaje z Přizpůsobit → AKKSP: kontakty, lze přepsat).
 *
 * @package AKKSP
 */

namespace AKKSP\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Contact_Bar extends Base {

	public function get_name() {
		return 'akksp-contact-bar';
	}

	public function get_title() {
		return __( 'AKKSP · Kontaktní lišta', 'akksp' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Obsah', 'akksp' ) ) );
		$this->add_control( 'title', array( 'label' => __( 'Nadpis', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Kontaktujte nás' ) );
		$this->add_control( 'note', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => __( 'Prázdná pole převezmou údaje z Přizpůsobit → AKKSP: kontakty a firma.', 'akksp' ), 'content_classes' => 'elementor-descriptor' ) );
		$this->add_control( 'phone', array( 'label' => __( 'Telefon', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$this->add_control( 'email', array( 'label' => __( 'E-mail', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$this->add_control( 'offices', array( 'label' => __( 'Kanceláře', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$this->add_control( 'btn_text', array( 'label' => __( 'Text tlačítka', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Kontaktovat nás', 'separator' => 'before' ) );
		$this->add_control( 'btn_link', array( 'label' => __( 'Odkaz tlačítka', 'akksp' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$phone   = $s['phone'] ? $s['phone'] : akksp_contact( 'phone' );
		$email   = $s['email'] ? $s['email'] : akksp_contact( 'email' );
		$offices = $s['offices'] ? $s['offices'] : akksp_contact( 'offices' );
		$btn_url = $this->url( $s['btn_link'], '#kontakt' ); // #kontakt otevře kontaktní panel
		?>
<section class="ak-contact ak-dark" aria-label="<?php echo esc_attr( $s['title'] ); ?>">
	<div class="ak-wrap">
		<div class="ak-contact__row">
			<h2 class="ak-contact__title"><?php echo esc_html( $s['title'] ); ?></h2>
			<a href="<?php echo esc_attr( akksp_tel_href( $phone ) ); ?>"><small class="ak-label"><?php esc_html_e( 'Telefon', 'akksp' ); ?></small><span><?php echo esc_html( $phone ); ?></span></a>
			<a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><small class="ak-label"><?php esc_html_e( 'E-mail', 'akksp' ); ?></small><span><?php echo esc_html( antispambot( $email ) ); ?></span></a>
			<div><small class="ak-label"><?php esc_html_e( 'Kancelář', 'akksp' ); ?></small><span style="color:var(--ak-paper)"><?php echo esc_html( $offices ); ?></span></div>
			<div><?php echo akksp_button( $s['btn_text'], $btn_url ); // phpcs:ignore ?></div>
		</div>
	</div>
</section>
		<?php
	}
}
