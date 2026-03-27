<?php
/**
 * Search results template.
 */

get_header();

$lang = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : 'en';

$home_url      = function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
$blog_url      = get_permalink( get_option( 'page_for_posts' ) );
$portfolio_url = get_post_type_archive_link( 'portfolio' );
$contact_page  = get_page_by_path( 'contact' );
$contact_url   = $contact_page ? get_permalink( $contact_page ) : $home_url . '#contact';

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

$header_links = array(
	array(
		'url'   => $home_url,
		'label' => dt_label( 'home', 'nav' ),
	),
	array(
		'url'   => $blog_url,
		'label' => dt_label( 'articles', 'nav' ),
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

$search_query = get_search_query();
?>

<div id="content" role="main" class="blog-shell">
	<header class="blog-shell__masthead">
		<div class="blog-shell__topbar">
			<?php echo developer_theme_render_brand( 'blog-shell__brand', $home_url ); ?>
			<nav class="blog-shell__nav site-topbar__desktop-nav" aria-label="Navigation">
				<a href="<?php echo esc_url( $home_url ); ?>"><?php echo esc_html( dt_label( 'home', 'nav' ) ); ?></a>
				<a href="<?php echo esc_url( $blog_url ); ?>"><?php echo esc_html( dt_label( 'articles', 'nav' ) ); ?></a>
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
			<?php echo developer_theme_render_mobile_menu( 'Navigation', $header_links, $language_links ); ?>
		</div>
	</header>

	<div class="blog-shell__hero">
		<p class="blog-shell__eyebrow"><?php echo esc_html( dt_label( 'search_eyebrow', 'errors' ) ); ?></p>
		<h1 class="blog-shell__title"><?php echo esc_html( $search_query ); ?></h1>
		<p class="blog-shell__intro">
			<?php
			if ( have_posts() ) {
				printf( esc_html( dt_label( 'search_found', 'errors' ) ), (int) $wp_query->found_posts );
			} else {
				echo esc_html( dt_label( 'search_empty', 'errors' ) );
			}
			?>
		</p>
	</div>

	<?php if ( have_posts() ) : ?>
		<div class="blog-shell__grid search-results">
			<?php
			while ( have_posts() ) :
				the_post();
				$post_type = get_post_type();
				?>
				<article class="search-result">
					<a class="search-result__link" href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="search-result__media">
								<?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
							</div>
						<?php endif; ?>
						<div class="search-result__body">
							<span class="search-result__type"><?php echo esc_html( 'portfolio' === $post_type ? dt_label( 'portfolio', 'nav' ) : dt_label( 'articles', 'nav' ) ); ?></span>
							<h2 class="search-result__title"><?php the_title(); ?></h2>
							<?php if ( has_excerpt() || get_the_content() ) : ?>
								<p class="search-result__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
							<?php endif; ?>
						</div>
					</a>
				</article>
			<?php endwhile; ?>
		</div>
	<?php else : ?>
		<div class="error-page error-page--compact">
			<p class="error-page__text"><?php echo esc_html( dt_label( 'search_suggestion', 'errors' ) ); ?></p>
			<div class="error-page__actions">
				<a class="post-shell__button post-shell__button--primary" href="<?php echo esc_url( $home_url ); ?>"><?php echo esc_html( dt_label( 'home', 'nav' ) ); ?></a>
				<a class="post-shell__button" href="<?php echo esc_url( $portfolio_url ); ?>"><?php echo esc_html( dt_label( 'portfolio', 'nav' ) ); ?></a>
			</div>
		</div>
	<?php endif; ?>

	<footer class="blog-shell__footer">
		<div class="blog-shell__footer-inner">
			<div>
				<strong><?php echo esc_html( dt_label( 'brand', 'nav' ) ); ?></strong>
				<p><?php echo esc_html( dt_label( 'footer_text', 'nav' ) ); ?></p>
			</div>
			<nav aria-label="Footer navigation">
				<a href="<?php echo esc_url( $home_url ); ?>"><?php echo esc_html( dt_label( 'home', 'nav' ) ); ?></a>
				<a href="<?php echo esc_url( $blog_url ); ?>"><?php echo esc_html( dt_label( 'articles', 'nav' ) ); ?></a>
				<a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( dt_label( 'contact', 'nav' ) ); ?></a>
			</nav>
		</div>
	</footer>
</div>

<?php get_footer(); ?>
