<?php
/**
 * Template Name: Contact Page
 * Custom contact page with CF7 form, contact info, and map.
 */

get_header();

$lang = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : 'en';

$home_url      = function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
$blog_url      = get_permalink( get_option( 'page_for_posts' ) );
$portfolio_url = get_post_type_archive_link( 'portfolio' );
$contact_url   = get_permalink();

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
		'url'     => $contact_url,
		'label'   => dt_label( 'contact', 'nav' ),
		'current' => true,
	),
);
?>

<div id="content" role="main" class="blog-shell">
	<header class="blog-shell__masthead">
		<div class="blog-shell__topbar">
			<?php echo developer_theme_render_brand( 'blog-shell__brand', $home_url ); ?>
			<nav class="blog-shell__nav site-topbar__desktop-nav" aria-label="Main navigation">
				<a href="<?php echo esc_url( $home_url ); ?>"><?php echo esc_html( dt_label( 'home', 'nav' ) ); ?></a>
				<a href="<?php echo esc_url( $blog_url ); ?>"><?php echo esc_html( dt_label( 'articles', 'nav' ) ); ?></a>
				<a href="<?php echo esc_url( $portfolio_url ); ?>"><?php echo esc_html( dt_label( 'portfolio', 'nav' ) ); ?></a>
				<a class="is-current" href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( dt_label( 'contact', 'nav' ) ); ?></a>
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
		<p class="blog-shell__eyebrow"><?php echo esc_html( dt_label( 'contact', 'nav' ) ); ?></p>
		<h1 class="blog-shell__title"><?php echo esc_html( dt_label( 'page_title', 'contact' ) ); ?></h1>
		<p class="blog-shell__intro"><?php echo esc_html( dt_label( 'page_intro', 'contact' ) ); ?></p>
	</div>

	<div class="contact-page__layout">
		<div class="contact-page__form">
			<h2 class="contact-page__heading"><?php echo esc_html( dt_label( 'form_heading', 'contact' ) ); ?></h2>
			<?php echo do_shortcode( '[contact-form-7 id="9"]' ); ?>
		</div>

		<aside class="contact-page__sidebar">
			<h2 class="contact-page__heading"><?php echo esc_html( dt_label( 'info_heading', 'contact' ) ); ?></h2>

			<div class="contact-page__info-list">
				<a class="contact-page__info-item" href="mailto:<?php echo esc_attr( dt_shared( 'email' ) ); ?>">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
					<div>
						<strong>Email</strong>
						<span><?php echo esc_html( dt_shared( 'email' ) ); ?></span>
					</div>
				</a>
				<a class="contact-page__info-item" href="<?php echo esc_url( dt_shared( 'linkedin_url' ) ); ?>" target="_blank" rel="noopener noreferrer">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
					<div>
						<strong>LinkedIn</strong>
						<span><?php echo esc_html( dt_shared( 'linkedin_display' ) ); ?></span>
					</div>
				</a>
				<a class="contact-page__info-item" href="<?php echo esc_url( dt_shared( 'github_url' ) ); ?>" target="_blank" rel="noopener noreferrer">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.4 5.4 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65S8.93 17.38 9 18v4"/><path d="M9 18c-4.51 2-5-2-7-2"/></svg>
					<div>
						<strong>GitHub</strong>
						<span><?php echo esc_html( dt_shared( 'github_display' ) ); ?></span>
					</div>
				</a>
				<a class="contact-page__info-item" href="<?php echo esc_url( dt_shared( 'telegram_url' ) ); ?>" target="_blank" rel="noopener noreferrer">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/></svg>
					<div>
						<strong>Telegram</strong>
						<span><?php echo esc_html( dt_shared( 'telegram_display' ) ); ?></span>
					</div>
				</a>
			</div>

			<div class="contact-page__location">
				<h3 class="contact-page__subheading"><?php echo esc_html( dt_label( 'location_lbl', 'contact' ) ); ?></h3>
				<p class="contact-page__location-name"><?php echo esc_html( dt_label( 'location', 'contact' ) ); ?></p>
				<p class="contact-page__availability"><?php echo esc_html( dt_label( 'availability', 'contact' ) ); ?></p>
				<div class="contact-page__map">
					<iframe
						title="<?php echo esc_attr( dt_shared( 'location' ) ); ?>"
						src="<?php echo esc_url( dt_shared( 'map_embed_url' ) ); ?>"
						loading="lazy"
						referrerpolicy="no-referrer"
					></iframe>
				</div>
			</div>
		</aside>
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
				<a href="<?php echo esc_url( $portfolio_url ); ?>"><?php echo esc_html( dt_label( 'portfolio', 'nav' ) ); ?></a>
			</nav>
		</div>
	</footer>
</div>

<?php get_footer(); ?>
