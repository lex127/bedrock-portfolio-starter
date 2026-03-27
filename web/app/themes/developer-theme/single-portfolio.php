<?php
/**
 * Single portfolio case study template.
 */

get_header();
wp_reset_postdata();

if (! have_posts()) {
    get_footer();

    return;
}

the_post();

$post_id = get_the_ID();
$lang    = developer_theme_current_language();

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

$skills   = get_the_terms($post_id, 'portfolio_skills');
$live_url = get_post_meta($post_id, 'portfolio_link', true);
$client   = get_post_meta($post_id, 'portfolio_client', true);
$pf_label = get_post_meta($post_id, 'portfolio_label', true);
$excerpt  = has_excerpt() ? wp_strip_all_tags(get_the_excerpt()) : wp_trim_words(wp_strip_all_tags(get_post_field('post_content', $post_id)), 34);

$content_html = apply_filters('the_content', get_the_content());

$related_portfolio = new WP_Query(
    [
        'post_type'           => 'portfolio',
        'posts_per_page'      => 3,
        'post__not_in'        => [$post_id],
        'ignore_sticky_posts' => true,
        'lang'                => $lang,
    ],
);

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
?>

<div id="content" role="main">
	<div class="post-shell">
		<header class="post-masthead">
			<div class="post-masthead__topbar">
				<?php echo developer_theme_render_brand('post-masthead__brand', $home_url); ?>

				<nav class="post-masthead__nav site-topbar__desktop-nav" aria-label="Navigation">
					<a href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(dt_label('home', 'nav')); ?></a>
					<a href="<?php echo esc_url($blog_url); ?>"><?php echo esc_html(dt_label('articles', 'nav')); ?></a>
					<a href="<?php echo esc_url($portfolio_url); ?>"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></a>
					<a href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html(dt_label('contact', 'nav')); ?></a>
				</nav>

				<?php if (! empty($language_links)) : ?>
					<div class="post-masthead__langs site-topbar__desktop-langs" aria-label="Languages">
						<?php foreach ($language_links as $ll) : ?>
							<a class="<?php echo ! empty($ll['current_lang']) ? 'is-active' : ''; ?>" href="<?php echo esc_url($ll['url']); ?>">
								<?php echo esc_html(strtoupper($ll['slug'])); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php echo developer_theme_render_mobile_menu('Navigation', $header_links, $language_links); ?>
			</div>

			<nav class="post-breadcrumbs" aria-label="Breadcrumbs">
				<a href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(dt_label('home', 'nav')); ?></a>
				<span aria-hidden="true">&rsaquo;</span>
				<a href="<?php echo esc_url($portfolio_url); ?>"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></a>
				<span aria-hidden="true">&rsaquo;</span>
				<span><?php the_title(); ?></span>
			</nav>

			<div class="post-hero">
				<div class="post-hero__content">
					<p class="post-hero__eyebrow"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></p>
					<?php if ($pf_label) : ?>
						<span class="pf-case__label"><?php echo esc_html($pf_label); ?></span>
					<?php endif; ?>
					<h1 class="post-hero__title"><?php the_title(); ?></h1>
					<p class="post-hero__excerpt"><?php echo esc_html($excerpt); ?></p>

					<?php if (! empty($skills) && ! is_wp_error($skills)) : ?>
						<ul class="pf-case__stack">
							<?php foreach ($skills as $skill) : ?>
								<li><?php echo esc_html($skill->name); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<div class="post-hero__actions">
						<?php if ($live_url) : ?>
							<a class="post-shell__button post-shell__button--primary" href="<?php echo esc_url($live_url); ?>" target="_blank" rel="noopener noreferrer">Visit live site &rarr;</a>
						<?php endif; ?>
						<a class="post-shell__button" href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html(dt_label('contact', 'nav')); ?></a>
					</div>
				</div>

				<?php if (has_post_thumbnail()) : ?>
					<div class="post-hero__media">
						<?php echo get_the_post_thumbnail($post_id, 'large', ['loading' => 'eager']); ?>
					</div>
				<?php endif; ?>
			</div>
		</header>

		<div class="post-layout">
			<article class="post-article">
				<div class="post-article__body">
					<?php echo wp_kses_post($content_html); ?>
				</div>
			</article>

			<aside class="post-sidebar" aria-label="Project sidebar">
				<section class="post-sidebar__card">
					<p class="post-sidebar__eyebrow">Project details</p>
					<dl class="pf-case__details">
						<?php if ($client) : ?>
							<dt>Client</dt>
							<dd><?php echo esc_html($client); ?></dd>
						<?php endif; ?>
						<?php if (! empty($skills) && ! is_wp_error($skills)) : ?>
							<dt>Stack</dt>
							<dd><?php echo esc_html(implode(', ', wp_list_pluck($skills, 'name'))); ?></dd>
						<?php endif; ?>
						<?php if ($live_url) : ?>
							<dt>Website</dt>
							<dd><a href="<?php echo esc_url($live_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html(wp_parse_url($live_url, PHP_URL_HOST)); ?></a></dd>
						<?php endif; ?>
					</dl>
				</section>

				<section class="post-sidebar__card post-sidebar__card--cta">
					<p class="post-sidebar__eyebrow">Hire me</p>
					<h2>Need similar work?</h2>
					<p>If you need help with a similar project, feel free to reach out.</p>
					<a class="post-shell__button post-shell__button--primary" href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html(dt_label('contact', 'nav')); ?></a>
				</section>
			</aside>
		</div>

		<?php if ($related_portfolio->have_posts()) : ?>
			<section class="post-related-grid" aria-labelledby="related-portfolio-title">
				<div class="post-related-grid__header">
					<p class="post-related-grid__eyebrow"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></p>
					<h2 id="related-portfolio-title">More projects</h2>
				</div>

				<div class="post-related-grid__items">
					<?php
                    while ($related_portfolio->have_posts()) :
                        $related_portfolio->the_post();
                        ?>
						<a class="post-related-grid__item" href="<?php the_permalink(); ?>">
							<?php if (has_post_thumbnail()) : ?>
								<div class="post-related-grid__media"><?php the_post_thumbnail('medium_large', ['loading' => 'lazy']); ?></div>
							<?php endif; ?>
							<div class="post-related-grid__content">
								<h3><?php the_title(); ?></h3>
								<span><?php echo esc_html(wp_trim_words(get_the_excerpt(), 14)); ?></span>
							</div>
						</a>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</section>
		<?php endif; ?>

		<footer class="post-shell__site-footer">
			<div>
				<strong><?php echo esc_html(dt_label('brand', 'nav')); ?></strong>
				<p><?php echo esc_html(dt_label('footer_text', 'nav')); ?></p>
			</div>
			<nav aria-label="Footer navigation">
				<a href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(dt_label('home', 'nav')); ?></a>
				<a href="<?php echo esc_url($blog_url); ?>"><?php echo esc_html(dt_label('articles', 'nav')); ?></a>
				<a href="<?php echo esc_url($portfolio_url); ?>"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></a>
				<a href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html(dt_label('contact', 'nav')); ?></a>
			</nav>
			<?php echo wp_kses_post(developer_theme_footer_credit()); ?>
		</footer>
	</div>
</div>

<?php get_footer(); ?>
