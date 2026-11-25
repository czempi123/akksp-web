<?php
/**
 * Aktuality: poslední články z blogu (novinové sloupce).
 *
 * @package AKKSP
 */

namespace AKKSP\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class News extends Base {

	public function get_name() {
		return 'akksp-news';
	}

	public function get_title() {
		return __( 'AKKSP · Aktuality z blogu', 'akksp' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Obsah', 'akksp' ) ) );
		$this->add_control( 'label', array( 'label' => __( 'Štítek', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => '', 'label_block' => true ) );
		$this->add_control( 'title', array( 'label' => __( 'Nadpis', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Aktuálně z právního světa', 'label_block' => true ) );
		$this->add_control( 'link_text', array( 'label' => __( 'Text odkazu', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Všechny články' ) );
		$this->add_control( 'link', array( 'label' => __( 'Odkaz', 'akksp' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '/blog/' ) ) );

		$cats = array( '' => __( 'Všechny kategorie', 'akksp' ) );
		foreach ( get_categories( array( 'hide_empty' => false ) ) as $cat ) {
			$cats[ (string) $cat->term_id ] = $cat->name;
		}
		$this->add_control( 'category', array( 'label' => __( 'Kategorie', 'akksp' ), 'type' => Controls_Manager::SELECT, 'default' => '', 'options' => $cats, 'separator' => 'before' ) );
		$this->add_control( 'count', array( 'label' => __( 'Počet článků', 'akksp' ), 'type' => Controls_Manager::NUMBER, 'default' => 3, 'min' => 1, 'max' => 9 ) );
		$this->add_control( 'read_more', array( 'label' => __( 'Text „číst"', 'akksp' ), 'type' => Controls_Manager::TEXT, 'default' => 'Číst článek' ) );
		$this->add_control( 'show_date', array( 'label' => __( 'Zobrazit datum', 'akksp' ), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$args  = array(
			'post_type'           => 'post',
			'posts_per_page'      => max( 1, (int) $s['count'] ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);
		if ( ! empty( $s['category'] ) ) {
			$args['cat'] = (int) $s['category'];
		}
		$q    = new \WP_Query( $args );
		$link = akksp_text_link( $s['link_text'], $this->url( $s['link'], get_permalink( get_option( 'page_for_posts' ) ) ), trim( $this->link_extra( $s['link'] ) ) );
		?>
<section class="ak-news ak-paper ak-section">
	<div class="ak-wrap">
		<?php $this->render_head( $s, $link ); ?>
		<?php if ( $q->have_posts() ) : ?>
		<div class="ak-news__list">
			<?php
			$i = 0;
			while ( $q->have_posts() ) :
				$q->the_post();
				$cats = get_the_category();
				$meta = array();
				if ( $cats ) {
					$meta[] = $cats[0]->name;
				}
				if ( 'yes' === $s['show_date'] ) {
					$meta[] = get_the_date( 'j. n. Y' );
				}
				?>
			<article class="ak-post ak-reveal" style="--i:<?php echo (int) $i++; ?>">
				<?php if ( has_post_thumbnail() ) : ?>
				<a class="ak-post__img" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'akksp-card', array( 'alt' => '' ) ); ?></a>
				<?php endif; ?>
				<?php if ( $meta ) : ?><span class="ak-mono"><?php echo esc_html( implode( ' · ', $meta ) ); ?></span><?php endif; ?>
				<h3 class="ak-h3"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<p class="ak-small"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24, '…' ) ); ?></p>
				<a class="ak-link" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php echo esc_html( $s['read_more'] ); ?> <?php echo akksp_icon( 'arrow', true ); // phpcs:ignore ?></a>
			</article>
			<?php endwhile; ?>
		</div>
			<?php
			wp_reset_postdata();
		else :
			?>
		<p class="ak-lead"><?php esc_html_e( 'Zatím tu nejsou žádné články.', 'akksp' ); ?></p>
		<?php endif; ?>
	</div>
</section>
		<?php
	}
}
