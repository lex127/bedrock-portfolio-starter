<?php
/**
 * 404 — Page not found.
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

	<div class="error-page">
		<p class="error-page__code">404</p>
		<h1 class="error-page__title"><?php echo esc_html( dt_label( '404_title', 'errors' ) ); ?></h1>
		<p class="error-page__text"><?php echo esc_html( dt_label( '404_text', 'errors' ) ); ?></p>
		<div class="error-page__actions">
			<a class="post-shell__button post-shell__button--primary" href="<?php echo esc_url( $home_url ); ?>"><?php echo esc_html( dt_label( 'home', 'nav' ) ); ?></a>
			<a class="post-shell__button" href="<?php echo esc_url( $portfolio_url ); ?>"><?php echo esc_html( dt_label( 'portfolio', 'nav' ) ); ?></a>
			<a class="post-shell__button" href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( dt_label( 'contact', 'nav' ) ); ?></a>
		</div>
	</div>

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
