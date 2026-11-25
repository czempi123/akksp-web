<?php
/**
 * Výsledky vyhledávání (po odeslání formuláře; Ivory Search používá stejnou šablonu,
 * takže jeho filtry a nastavení zůstávají v platnosti).
 *
 * @package AKKSP
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $wp_query;
$akksp_total = (int) $wp_query->found_posts;
?>
<section class="ak-page-header ak-dark">
	<div class="ak-wrap ak-grid">
		<div class="ak-page-header__inner">
			<p class="ak-label"><?php esc_html_e( 'Vyhledávání', 'akksp' ); ?></p>
			<h1 class="ak-display"><?php echo esc_html( get_search_query() ? get_search_query() : __( 'Hledat na webu', 'akksp' ) ); ?></h1>
			<p class="ak-lead">
				<?php
				/* translators: %d: number of results */
				echo esc_html( sprintf( _n( 'Nalezen %d výsledek', 'Nalezeno %d výsledků', $akksp_total, 'akksp' ), $akksp_total ) );
				?>
			</p>
			<div class="ak-search ak-search--inline"><?php echo akksp_search_form(); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		</div>
	</div>
</section>

<section class="ak-paper ak-section">
	<div class="ak-wrap">
		<?php if ( have_posts() ) : ?>
		<ol class="ak-results">
			<?php
			while ( have_posts() ) :
				the_post();
				$akksp_type  = get_post_type_object( get_post_type() );
				$akksp_names = array(
					'post' => __( 'Článek', 'akksp' ),
					'page' => __( 'Stránka', 'akksp' ),
				);
				$akksp_label = $akksp_names[ get_post_type() ] ?? ( $akksp_type ? $akksp_type->labels->singular_name : '' );
				?>
			<li>
				<a class="ak-result" href="<?php the_permalink(); ?>">
					<span class="ak-mono"><?php echo esc_html( $akksp_label ); ?><?php echo 'post' === get_post_type() ? ' · ' . esc_html( get_the_date( 'j. n. Y' ) ) : ''; ?></span>
					<span class="ak-h3"><?php the_title(); ?></span>
					<?php if ( has_excerpt() || get_the_content() ) : ?>
					<span class="ak-small"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30, '…' ) ); ?></span>
					<?php endif; ?>
					<span class="ak-result__go ak-arrow-box" aria-hidden="true"><?php echo akksp_icon( 'arrow' ); // phpcs:ignore ?></span>
				</a>
			</li>
			<?php endwhile; ?>
		</ol>
		<nav class="ak-pager" aria-label="<?php esc_attr_e( 'Stránkování výsledků', 'akksp' ); ?>">
			<?php
			echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput
				array(
					'prev_text' => __( 'Předchozí', 'akksp' ),
					'next_text' => __( 'Další', 'akksp' ),
				)
			);
			?>
		</nav>
		<?php else : ?>
		<p class="ak-lead"><?php esc_html_e( 'Nic jsme nenašli. Zkuste jiné slovo, nebo nám rovnou zavolejte.', 'akksp' ); ?></p>
		<p style="margin-top:24px"><?php echo akksp_button( akksp_contact( 'phone' ), akksp_tel_href( akksp_contact( 'phone' ) ), 'secondary', array( 'icon' => 'phone' ) ); // phpcs:ignore ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
