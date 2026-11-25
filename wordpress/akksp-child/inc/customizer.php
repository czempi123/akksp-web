<?php
/**
 * Údaje kanceláře v Přizpůsobit → AKKSP: kontakty a firma.
 * Používá je hlavička, patička a widget Kontaktní lišta.
 *
 * @package AKKSP
 */

defined( 'ABSPATH' ) || exit;

/**
 * Výchozí hodnoty (ze současného webu aktrinec.cz).
 */
function akksp_contact_defaults() {
	return array(
		'phone'        => '+420 558 997 103',
		'email'        => 'info@aktrinec.cz',
		'offices'      => 'Třinec · Praha · Krakov',
		'contact_url'  => '#kontakt',
		'company'      => 'AKKSP advokátní kancelář, s.r.o.',
		'company_info' => "IČO: 27787541\nZápis v obchodním rejstříku:\nC 29686 u Krajského soudu v Ostravě\nID datové schránky: xvwje95",
		'seat'         => "Husova 401\n739 61 Třinec\nČeská republika\n+420 558 997 104",
		'branch'       => "Průmyslová 1032\n739 61 Třinec\nČeská republika\n+420 558 532 496",
		'linkedin'     => '',
		'youtube'      => '',
	);
}

/**
 * Vrátí údaj kanceláře.
 */
function akksp_contact( $key ) {
	$defaults = akksp_contact_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	return get_theme_mod( 'akksp_' . $key, $default );
}

/**
 * Telefon ve tvaru pro odkaz tel:.
 */
function akksp_tel_href( $phone ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', (string) $phone );
}

add_action(
	'customize_register',
	function ( $wp_customize ) {
		$wp_customize->add_section(
			'akksp_contact',
			array(
				'title'       => __( 'AKKSP: kontakty a firma', 'akksp' ),
				'priority'    => 30,
				'description' => __( 'Údaje v hlavičce, kontaktní liště a patičce.', 'akksp' ),
			)
		);

		$fields = array(
			'phone'        => array( __( 'Telefon (hlavní)', 'akksp' ), 'text' ),
			'email'        => array( __( 'E-mail', 'akksp' ), 'email' ),
			'offices'      => array( __( 'Kanceláře (jeden řádek)', 'akksp' ), 'text' ),
			'contact_url'  => array( __( 'Odkaz tlačítka Kontakt', 'akksp' ), 'text' ),
			'company'      => array( __( 'Název společnosti', 'akksp' ), 'text' ),
			'company_info' => array( __( 'Údaje o společnosti (IČO, rejstřík, DS)', 'akksp' ), 'textarea' ),
			'seat'         => array( __( 'Sídlo (adresa + telefon)', 'akksp' ), 'textarea' ),
			'branch'       => array( __( 'Pobočka (adresa + telefon)', 'akksp' ), 'textarea' ),
			'linkedin'     => array( __( 'LinkedIn URL', 'akksp' ), 'url' ),
			'youtube'      => array( __( 'YouTube URL', 'akksp' ), 'url' ),
		);

		$defaults = akksp_contact_defaults();
		foreach ( $fields as $key => $field ) {
			$sanitize = 'sanitize_text_field';
			if ( 'textarea' === $field[1] ) {
				$sanitize = 'sanitize_textarea_field';
			} elseif ( 'url' === $field[1] ) {
				$sanitize = 'esc_url_raw';
			} elseif ( 'email' === $field[1] ) {
				$sanitize = 'sanitize_email';
			}
			$wp_customize->add_setting(
				'akksp_' . $key,
				array(
					'default'           => $defaults[ $key ],
					'sanitize_callback' => $sanitize,
				)
			);
			$wp_customize->add_control(
				'akksp_' . $key,
				array(
					'label'   => $field[0],
					'section' => 'akksp_contact',
					'type'    => $field[1],
				)
			);
		}
	}
);
