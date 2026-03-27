<?php
/**
 * Generic page template.
 */

get_header();

if (! have_posts()) {
    get_footer();

    return;
}

the_post();

$lang = function_exists('pll_current_language') ? pll_current_language('slug') : 'en';

$home_url      = function_exists('pll_home_url') ? pll_home_url() : home_url('/');
$blog_url      = developer_theme_posts_page_url($lang);
$portfolio_url = get_post_type_archive_link('portfolio');
$contact_url   = developer_theme_page_url('contact', $lang, $home_url . '#contact');

$language_links = [];
if (function_exists('pll_the_languages')) {
    $language_links = pll_the_languages(
        [
            'raw'                    => 1,
            'hide_if_empty'          => 0,
            'hide_if_no_translation' => 0,
        ],
    );
}

$header_links = [
    [
        'url'   => $home_url,
        'label' => dt_label('home', 'nav'),
    ],
    [
        'url'   => $blog_url,
        'label' => dt_label('articles', 'nav'),
    ],
    [
        'url'   => $portfolio_url,
        'label' => dt_label('portfolio', 'nav'),
    ],
    [
        'url'   => $contact_url,
        'label' => dt_label('contact', 'nav'),
    ],
];

$content_html = apply_filters('the_content', get_the_content());
?>

<div id="content" role="main" class="blog-shell">
	<header class="blog-shell__masthead">
		<div class="blog-shell__topbar">
			<?php echo developer_theme_render_brand('blog-shell__brand', $home_url); ?>
			<nav class="blog-shell__nav site-topbar__desktop-nav" aria-label="Navigation">
				<a href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(dt_label('home', 'nav')); ?></a>
				<a href="<?php echo esc_url($blog_url); ?>"><?php echo esc_html(dt_label('articles', 'nav')); ?></a>
				<a href="<?php echo esc_url($portfolio_url); ?>"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></a>
				<a href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html(dt_label('contact', 'nav')); ?></a>
			</nav>
			<?php if (! empty($language_links)) : ?>
				<div class="blog-shell__langs site-topbar__desktop-langs" aria-label="Languages">
					<?php foreach ($language_links as $ll) : ?>
						<a class="<?php echo ! empty($ll['current_lang']) ? 'is-active' : ''; ?>" href="<?php echo esc_url($ll['url']); ?>">
							<?php echo esc_html(strtoupper($ll['slug'])); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php echo developer_theme_render_mobile_menu('Navigation', $header_links, $language_links); ?>
		</div>
	</header>

	<div class="blog-shell__hero">
		<h1 class="blog-shell__title"><?php the_title(); ?></h1>
	</div>

	<div class="page-content">
		<div class="post-article__body">
			<?php echo wp_kses_post($content_html); ?>
		</div>
	</div>

	<footer class="blog-shell__footer">
		<div class="blog-shell__footer-inner">
			<div>
				<strong><?php echo esc_html(dt_label('brand', 'nav')); ?></strong>
				<p><?php echo esc_html(dt_label('footer_text', 'nav')); ?></p>
			</div>
			<nav aria-label="Footer navigation">
				<a href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(dt_label('home', 'nav')); ?></a>
				<a href="<?php echo esc_url($blog_url); ?>"><?php echo esc_html(dt_label('articles', 'nav')); ?></a>
				<a href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html(dt_label('contact', 'nav')); ?></a>
			</nav>
		</div>
		<?php echo wp_kses_post(developer_theme_footer_credit()); ?>
	</footer>
</div>

<?php get_footer(); ?>
