<?php
/**
 * Hlavička: jeden řádek přes celou šířku (logo, celé menu napřímo, vyhledávání Ivory Search,
 * CZ | PL | EN, Kontakt = kontaktní panel). Bez burgeru: na tabletu a mobilu přejde menu jako druhý řádek pod logo.
 * Pokud je v Elementor Pro přiřazená vlastní hlavička (Theme Builder), má přednost.
 *
 * @package AKKSP
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#02182C">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="ak-skip" href="#obsah"><?php esc_html_e( 'Přejít k obsahu', 'akksp' ); ?></a>
<?php akksp_icon_sprite(); ?>

<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) :
	$akksp_lang = akksp_lang_switch();
	?>
<header class="ak-nav" data-nav>
	<div class="ak-nav__in">
		<a class="ak-nav__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ', ' . __( 'úvodní stránka', 'akksp' ) ); ?>">
			<img src="<?php echo esc_url( akksp_logo_url( 'white' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="128" height="32">
		</a>
		<div class="ak-nav__main">
			<nav class="ak-nav__scroll" aria-label="<?php esc_attr_e( 'Hlavní navigace', 'akksp' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'akksp-primary',
						'container'      => false,
						'menu_class'     => 'ak-nav__menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>
			<button type="button" class="ak-nav__icon ak-nav__icon--m" aria-label="<?php esc_attr_e( 'Hledat', 'akksp' ); ?>" aria-controls="hledani" aria-expanded="false" data-search-open><?php echo akksp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
		</div>
		<div class="ak-nav__tools">
			<button type="button" class="ak-nav__icon" aria-label="<?php esc_attr_e( 'Hledat', 'akksp' ); ?>" aria-controls="hledani" aria-expanded="false" data-search-open><?php echo akksp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
			<?php echo $akksp_lang; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo akksp_button( __( 'Kontakt', 'akksp' ), '#kontakt', 'primary', array( 'size' => 'sm', 'attrs' => 'aria-controls="kontakt" aria-expanded="false" data-contact-open' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</header>

<div class="ak-search" id="hledani" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Vyhledávání', 'akksp' ); ?>" data-search>
	<div class="ak-search__inner">
		<div class="ak-search__head">
			<p class="ak-label"><?php echo esc_html( akksp_contact( 'search_title' ) ); ?></p>
			<button type="button" class="ak-search__close" aria-label="<?php esc_attr_e( 'Zavřít vyhledávání', 'akksp' ); ?>" data-search-close><span class="ak-px__ico"></span></button>
		</div>
		<?php echo akksp_search_form(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</div>
<?php endif; ?>

<div id="obsah" class="ak-main">
