<?php
/**
 * Hlavička: plovoucí skleněná navigace + mobilní panel.
 * Pokud je v Elementor Pro nastavená vlastní hlavička, má přednost.
 *
 * @package AKKSP
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#001628">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="ak-skip" href="#obsah"><?php esc_html_e( 'Přejít k obsahu', 'akksp' ); ?></a>
<?php akksp_icon_sprite(); ?>

<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) :
	$contact_url = akksp_contact( 'contact_url' );
	?>
<header class="ak-nav" data-nav>
	<div class="ak-nav__bar">
		<a class="ak-nav__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ', ' . __( 'úvodní stránka', 'akksp' ) ); ?>">
			<img src="<?php echo esc_url( akksp_logo_url( 'white' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="116" height="29">
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
			<a class="ak-nav__icon ak-nav__search" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="<?php esc_attr_e( 'Hledat', 'akksp' ); ?>"><?php echo akksp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
			<?php echo akksp_lang_switch( 'nav' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo akksp_button( __( 'Kontakt', 'akksp' ), $contact_url, 'primary', array( 'size' => 'sm' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
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
		<?php echo akksp_lang_switch( 'mobile' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</div>
<?php endif; ?>

<div id="obsah" class="ak-main">
