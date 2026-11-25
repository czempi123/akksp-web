<?php
/**
 * Patička: značka | kontakt | adresy | dokumenty | média, spodní lišta s nastavením cookies.
 * Pod ní kontaktní panel (#kontakt) — vykreslí se vždy, i když hlavičku/patičku dodává Elementor Pro.
 *
 * @package AKKSP
 */

defined( 'ABSPATH' ) || exit;

/**
 * Menu patičky (prosté odkazy), nebo nic.
 */
$akksp_footer_menu = function ( $location ) {
	if ( ! has_nav_menu( $location ) ) {
		return;
	}
	wp_nav_menu(
		array(
			'theme_location' => $location,
			'container'      => false,
			'depth'          => 1,
			'fallback_cb'    => false,
		)
	);
};
$akksp_phone = akksp_contact( 'phone' );
$akksp_email = akksp_contact( 'email' );
$akksp_li    = akksp_contact( 'linkedin' );
?>
</div><!-- #obsah -->

<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) : ?>
<footer class="ak-footer ak-dark">
	<div class="ak-wrap ak-grid ak-footer__cols">
		<div class="ak-footer__brand">
			<img src="<?php echo esc_url( akksp_logo_url( 'white' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="136" height="34" loading="lazy">
			<h2><?php echo esc_html( akksp_contact( 'company' ) ); ?></h2>
			<p><?php echo akksp_lines( akksp_contact( 'company_info' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
		</div>
		<div class="ak-footer__contact">
			<h2><?php esc_html_e( 'Kontakt', 'akksp' ); ?></h2>
			<ul>
				<?php if ( $akksp_phone ) : ?><li><a href="<?php echo esc_attr( akksp_tel_href( $akksp_phone ) ); ?>"><?php echo esc_html( $akksp_phone ); ?></a></li><?php endif; ?>
				<?php if ( $akksp_email ) : ?><li><a href="mailto:<?php echo esc_attr( antispambot( $akksp_email ) ); ?>"><?php echo esc_html( antispambot( $akksp_email ) ); ?></a></li><?php endif; ?>
				<?php if ( $akksp_li ) : ?><li><a class="ak-footer__in" href="<?php echo esc_url( $akksp_li ); ?>" rel="noopener" target="_blank"><?php echo akksp_icon( 'in', true ); // phpcs:ignore ?>LinkedIn</a></li><?php endif; ?>
			</ul>
		</div>
		<div class="ak-footer__addr">
			<h2><?php esc_html_e( 'Adresy', 'akksp' ); ?></h2>
			<p><b><?php esc_html_e( 'Sídlo', 'akksp' ); ?></b><?php echo akksp_lines( akksp_contact( 'seat' ) ); // phpcs:ignore ?></p>
			<p><b><?php esc_html_e( 'Pobočka', 'akksp' ); ?></b><?php echo akksp_lines( akksp_contact( 'branch' ) ); // phpcs:ignore ?></p>
		</div>
		<div class="ak-footer__docs">
			<h2><?php esc_html_e( 'Dokumenty', 'akksp' ); ?></h2>
			<?php $akksp_footer_menu( 'akksp-footer-info' ); ?>
		</div>
		<div class="ak-footer__media">
			<h2><?php esc_html_e( 'Média', 'akksp' ); ?></h2>
			<?php $akksp_footer_menu( 'akksp-footer-media' ); ?>
		</div>
	</div>
	<div class="ak-footer__bottom">
		<div class="ak-wrap">
			<span>© <span data-year><?php echo esc_html( gmdate( 'Y' ) ); ?></span> <?php echo esc_html( akksp_contact( 'company' ) ); ?> <?php esc_html_e( 'Všechna práva vyhrazena.', 'akksp' ); ?></span>
			<?php if ( defined( 'cmplz_version' ) || class_exists( 'COMPLIANZ' ) ) : // Complianz otevře svou lištu na prvku s třídou cmplz-show-banner ?>
			<button type="button" class="ak-footer__cookie cmplz-show-banner"><?php echo akksp_icon( 'cookie', true ); // phpcs:ignore ?><?php esc_html_e( 'Nastavení cookies', 'akksp' ); ?></button>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php endif; ?>

<?php akksp_contact_drawer(); ?>

<?php wp_footer(); ?>
</body>
</html>
