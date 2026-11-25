<?php
/**
 * Hlavička: navy lišta přes celou šířku, vyhledávání (Ivory Search), CZ | PL, mobilní menu.
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
	<div class="ak-nav__bar">
		<a class="ak-nav__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ', ' . __( 'úvodní stránka', 'akksp' ) ); ?>">
			<img src="<?php echo esc_url( akksp_logo_url( 'white' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="120" height="30">
		</a>
		<nav aria-label="<?php esc_attr_e( 'Hlavní navigace', 'akksp' ); ?>">
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
		<div class="ak-nav__tools">
			<button type="button" class="ak-nav__icon" aria-label="<?php esc_attr_e( 'Hledat', 'akksp' ); ?>" aria-controls="hledani" aria-expanded="false" data-search-open><?php echo akksp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
			<?php echo $akksp_lang; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo akksp_button( __( 'Kontakt', 'akksp' ), akksp_contact( 'contact_url' ), 'primary', array( 'size' => 'sm' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<button type="button" class="ak-burger" aria-expanded="false" aria-controls="mobilni-menu" aria-label="<?php esc_attr_e( 'Menu', 'akksp' ); ?>" data-burger><span class="ak-burger__lines"><i></i><i></i><i></i></span></button>
		</div>
	</div>
</header>

<div class="ak-mobile" id="mobilni-menu" data-mobile>
	<nav class="ak-mobile__menu" aria-label="<?php esc_attr_e( 'Mobilní navigace', 'akksp' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'akksp-primary',
				'container'      => false,
				'items_wrap'     => '<ul>%3$s</ul>',
				'depth'          => 1,
				'fallback_cb'    => false,
				'akksp_mobile'   => true,
			)
		);
		?>
	</nav>
	<div class="ak-mobile__foot ak-dark">
		<?php echo akksp_button( akksp_contact( 'phone' ), akksp_tel_href( akksp_contact( 'phone' ) ), 'primary', array( 'icon' => 'phone' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php echo $akksp_lang; // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</div>

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
