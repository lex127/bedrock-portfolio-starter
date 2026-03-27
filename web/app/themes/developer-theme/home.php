<?php
/**
 * Blog posts index template.
 * Custom design matching the portfolio site aesthetic.
 */

get_header();

$lang = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : 'en';
$manual_paged = isset( $_GET['blog_page'] ) ? max( 1, absint( wp_unslash( $_GET['blog_page'] ) ) ) : 0;
$paged        = max( 1, $manual_paged, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

$home_url      = function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
$portfolio_url = get_post_type_archive_link( 'portfolio' );
$contact_page  = get_page_by_path( 'contact' );
$contact_url   = $contact_page ? get_permalink( $contact_page ) : $home_url . '#contact';
$blog_page_url = get_permalink( get_queried_object_id() ?: get_option( 'page_for_posts' ) );

$language_links = array();
if ( function_exists( 'pll_the_languages' ) ) {
	$language_links = pll_the_languages(
		array(
			'raw'                    => 1,
			'hide_if_empty'          => 0,
			'hide_if_no_translation' => 0,
		)
	);
}

$blog_posts = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 6,
		'paged'               => $paged,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
		'lang'                => $lang,
	)
);

$header_links = array(
	array(
		'url'   => $home_url,
		'label' => dt_label( 'home', 'nav' ),
	),
	array(
		'url'     => get_permalink( get_option( 'page_for_posts' ) ),
		'label'   => dt_label( 'articles', 'nav' ),
		'current' => true,
	),
	array(
		'url'   => $portfolio_url,
		'label' => dt_label( 'portfolio', 'nav' ),
	),
	array(
		'url'   => $contact_url,
		'label' => dt_label( 'contact', 'nav' ),
	),
);
?>

<div id="content" role="main" class="blog-shell">
	<header class="blog-shell__masthead">
		<div class="blog-shell__topbar">
			<?php echo developer_theme_render_brand( 'blog-shell__brand', $home_url ); ?>
			<nav class="blog-shell__nav site-topbar__desktop-nav" aria-label="Main navigation">
				<a href="<?php echo esc_url( $home_url ); ?>"><?php echo esc_html( dt_label( 'home', 'nav' ) ); ?></a>
				<a class="is-current" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php echo esc_html( dt_label( 'articles', 'nav' ) ); ?></a>
				<a href="<?php echo esc_url( $portfolio_url ); ?>"><?php echo esc_html( dt_label( 'portfolio', 'nav' ) ); ?></a>
				<a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( dt_label( 'contact', 'nav' ) ); ?></a>
			</nav>
			<?php if ( ! empty( $language_links ) ) : ?>
				<div class="blog-shell__langs site-topbar__desktop-langs" aria-label="Languages">
					<?php foreach ( $language_links as $ll ) : ?>
						<a class="<?php echo ! empty( $ll['current_lang'] ) ? 'is-active' : ''; ?>" href="<?php echo esc_url( $ll['url'] ); ?>">
							<?php echo esc_html( strtoupper( $ll['slug'] ) ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php echo developer_theme_render_mobile_menu( 'Main navigation', $header_links, $language_links ); ?>
		</div>
	</header>

	<div class="blog-shell__hero">
		<p class="blog-shell__eyebrow"><?php echo esc_html( dt_label( 'articles', 'nav' ) ); ?></p>
		<h1 class="blog-shell__title"><?php echo esc_html( dt_label( 'page_title', 'home' ) ); ?></h1>
		<p class="blog-shell__intro"><?php echo esc_html( dt_label( 'page_intro', 'home' ) ); ?></p>
	</div>

	<?php if ( $blog_posts->have_posts() ) : ?>
		<div class="blog-shell__grid">
			<?php
			while ( $blog_posts->have_posts() ) :
				$blog_posts->the_post();
				$cat      = get_the_category();
				$cat_name = ! empty( $cat ) ? $cat[0]->name : 'Article';
				if ( in_array( strtolower( $cat_name ), array( 'uncategorized', 'uncategorized-en', 'uncategorized-ru', 'без рубрики' ), true ) ) {
					$cat_name = dt_label( 'articles', 'nav' );
				}
				$word_count   = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', get_the_ID() ) ) );
				$reading_time = max( 1, (int) ceil( $word_count / 220 ) );
				?>
				<article class="blog-card">
					<a class="blog-card__link" href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="blog-card__media">
								<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy', 'class' => 'blog-card__image' ) ); ?>
							</div>
						<?php endif; ?>
						<div class="blog-card__content">
							<div class="blog-card__meta">
								<span class="blog-card__tag"><?php echo esc_html( $cat_name ); ?></span>
								<time class="blog-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								<span class="blog-card__reading"><?php echo esc_html( $reading_time ); ?> min</span>
							</div>
							<h2 class="blog-card__title"><?php the_title(); ?></h2>
							<p class="blog-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
							<span class="blog-card__cta"><?php echo esc_html( dt_label( 'read_more', 'home' ) ); ?> &rarr;</span>
						</div>
					</a>
				</article>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>

		<?php
		$prev_link = $paged > 1 ? add_query_arg( 'blog_page', $paged - 1, $blog_page_url ) : '';
		$next_link = $paged < (int) $blog_posts->max_num_pages ? add_query_arg( 'blog_page', $paged + 1, $blog_page_url ) : '';
		if ( $prev_link || $next_link ) :
			?>
			<nav class="blog-shell__pagination" aria-label="Blog pagination">
				<?php if ( $prev_link ) : ?>
					<a class="blog-shell__pagination-item blog-shell__pagination-item--prev" href="<?php echo esc_url( $prev_link ); ?>"><?php echo esc_html( dt_label( 'newer', 'home' ) ); ?></a>
				<?php endif; ?>
				<?php if ( $next_link ) : ?>
					<a class="blog-shell__pagination-item blog-shell__pagination-item--next" href="<?php echo esc_url( $next_link ); ?>"><?php echo esc_html( dt_label( 'older', 'home' ) ); ?></a>
				<?php endif; ?>
			</nav>
		<?php endif; ?>

	<?php else : ?>
		<p class="blog-shell__empty"><?php echo esc_html( dt_label( 'no_posts', 'home' ) ); ?></p>
	<?php endif; ?>

	<footer class="blog-shell__footer">
		<div class="blog-shell__footer-inner">
			<div>
				<strong><?php echo esc_html( dt_label( 'brand', 'nav' ) ); ?></strong>
				<p><?php echo esc_html( dt_label( 'footer_text', 'nav' ) ); ?></p>
			</div>
			<nav aria-label="Footer navigation">
				<a href="<?php echo esc_url( $home_url ); ?>"><?php echo esc_html( dt_label( 'home', 'nav' ) ); ?></a>
				<a href="<?php echo esc_url( $portfolio_url ); ?>"><?php echo esc_html( dt_label( 'portfolio', 'nav' ) ); ?></a>
				<a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( dt_label( 'contact', 'nav' ) ); ?></a>
			</nav>
		</div>
	</footer>
</div>

<?php get_footer(); ?>
