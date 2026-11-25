<?php
/**
 * Patička (struktura podle současného webu).
 * Pokud je v Elementor Pro nastavená vlastní patička, má přednost.
 *
 * @package AKKSP
 */

defined( 'ABSPATH' ) || exit;

/**
 * Vypíše menu patičky s šipkami, nebo nic.
 */
$akksp_footer_menu = function ( $location ) {
	if ( ! has_nav_menu( $location ) ) {
		return;
	}
	add_filter( 'nav_menu_item_title', 'akksp_footer_arrow', 10, 3 );
	wp_nav_menu(
		array(
			'theme_location' => $location,
			'container'      => false,
			'depth'          => 1,
			'fallback_cb'    => false,
		)
	);
	remove_filter( 'nav_menu_item_title', 'akksp_footer_arrow', 10 );
};
?>
</div><!-- #obsah -->

<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) : ?>
<footer class="ak-footer ak-dark" id="kontakt">
	<div class="ak-wrap ak-grid ak-footer__cols">
		<div class="ak-footer__brand">
			<img src="<?php echo esc_url( akksp_logo_url( 'white' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="136" height="34" loading="lazy">
			<h2><?php echo esc_html( akksp_contact( 'company' ) ); ?></h2>
			<p><?php echo akksp_lines( akksp_contact( 'company_info' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
		</div>
		<div class="ak-footer__info">
			<h2><?php esc_html_e( 'Další informace', 'akksp' ); ?></h2>
			<?php $akksp_footer_menu( 'akksp-footer-info' ); ?>
		</div>
		<div class="ak-footer__media">
			<h2><?php esc_html_e( 'Média', 'akksp' ); ?></h2>
			<?php $akksp_footer_menu( 'akksp-footer-media' ); ?>
			<?php
			$akksp_li = akksp_contact( 'linkedin' );
			$akksp_yt = akksp_contact( 'youtube' );
			if ( $akksp_li || $akksp_yt ) :
				?>
			<div>
				<h2><?php esc_html_e( 'Sledujte nás', 'akksp' ); ?></h2>
				<div class="ak-footer__icons">
					<?php if ( $akksp_li ) : ?><a href="<?php echo esc_url( $akksp_li ); ?>" aria-label="LinkedIn" rel="noopener" target="_blank"><?php echo akksp_icon( 'in' ); // phpcs:ignore ?></a><?php endif; ?>
					<?php if ( $akksp_yt ) : ?><a href="<?php echo esc_url( $akksp_yt ); ?>" aria-label="YouTube" rel="noopener" target="_blank"><?php echo akksp_icon( 'yt' ); // phpcs:ignore ?></a><?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<div class="ak-footer__addr">
			<h2><?php esc_html_e( 'Adresa', 'akksp' ); ?></h2>
			<p><b><?php esc_html_e( 'Sídlo', 'akksp' ); ?></b><?php echo akksp_lines( akksp_contact( 'seat' ) ); // phpcs:ignore ?></p>
			<p><b><?php esc_html_e( 'Pobočka', 'akksp' ); ?></b><?php echo akksp_lines( akksp_contact( 'branch' ) ); // phpcs:ignore ?></p>
		</div>
	</div>
	<div class="ak-footer__bottom">
		<div class="ak-wrap">
			<span>© <span data-year><?php echo esc_html( gmdate( 'Y' ) ); ?></span> <?php echo esc_html( akksp_contact( 'company' ) ); ?></span>
			<span><?php esc_html_e( 'Všechna práva vyhrazena', 'akksp' ); ?></span>
			<?php if ( has_nav_menu( 'akksp-footer-legal' ) ) : ?>
			<nav aria-label="<?php esc_attr_e( 'Právní informace', 'akksp' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'akksp-footer-legal',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'depth'          => 1,
						'fallback_cb'    => false,
						'walker'         => new AKKSP_Plain_Links_Walker(),
					)
				);
				?>
			</nav>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
