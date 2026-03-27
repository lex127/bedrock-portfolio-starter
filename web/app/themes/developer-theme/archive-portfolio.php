<?php
/**
 * Portfolio archive template.
 * Custom design matching the site aesthetic.
 */

get_header();

$lang = function_exists('pll_current_language') ? pll_current_language('slug') : 'en';

$home_url      = function_exists('pll_home_url') ? pll_home_url() : home_url('/');
$blog_url      = get_permalink(get_option('page_for_posts'));
$portfolio_url = get_post_type_archive_link('portfolio');
$contact_page  = get_page_by_path('contact');
$contact_url   = $contact_page ? get_permalink($contact_page) : $home_url . '#contact';

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
        'url'     => $portfolio_url,
        'label'   => dt_label('portfolio', 'nav'),
        'current' => true,
    ],
    [
        'url'   => $contact_url,
        'label' => dt_label('contact', 'nav'),
    ],
];
?>

<div id="content" role="main" class="blog-shell">
	<header class="blog-shell__masthead">
		<div class="blog-shell__topbar">
			<?php echo developer_theme_render_brand('blog-shell__brand', $home_url); ?>
			<nav class="blog-shell__nav site-topbar__desktop-nav" aria-label="Main navigation">
				<a href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(dt_label('home', 'nav')); ?></a>
				<a href="<?php echo esc_url($blog_url); ?>"><?php echo esc_html(dt_label('articles', 'nav')); ?></a>
				<a class="is-current" href="<?php echo esc_url($portfolio_url); ?>"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></a>
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
			<?php echo developer_theme_render_mobile_menu('Main navigation', $header_links, $language_links); ?>
		</div>
	</header>

	<div class="blog-shell__hero">
		<p class="blog-shell__eyebrow"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></p>
		<h1 class="blog-shell__title"><?php echo esc_html(dt_label('page_title', 'archive_portfolio')); ?></h1>
		<p class="blog-shell__intro"><?php echo esc_html(dt_label('page_intro', 'archive_portfolio')); ?></p>
	</div>

	<?php if (have_posts()) : ?>
		<div class="blog-shell__grid portfolio-grid-custom">
			<?php
            while (have_posts()) :
                the_post();
                $skills   = get_the_terms(get_the_ID(), 'portfolio_skills');
                $live_url = get_post_meta(get_the_ID(), 'portfolio_link', true);
                $pf_label = get_post_meta(get_the_ID(), 'portfolio_label', true);
                ?>
				<article class="pf-card">
					<a class="pf-card__link" href="<?php the_permalink(); ?>">
						<?php if (has_post_thumbnail()) : ?>
							<div class="pf-card__media">
								<?php the_post_thumbnail('medium_large', [ 'loading' => 'lazy', 'class' => 'pf-card__image' ]); ?>
							</div>
						<?php endif; ?>
						<div class="pf-card__body">
							<?php if ($pf_label) : ?>
								<span class="pf-card__label"><?php echo esc_html($pf_label); ?></span>
							<?php endif; ?>
							<h2 class="pf-card__title"><?php the_title(); ?></h2>
							<?php if (has_excerpt()) : ?>
								<p class="pf-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
							<?php endif; ?>
						</div>
					</a>
					<?php if (! empty($skills) && ! is_wp_error($skills)) : ?>
						<ul class="pf-card__stack">
							<?php foreach ($skills as $skill) : ?>
								<li><?php echo esc_html($skill->name); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if ($live_url) : ?>
						<a class="pf-card__live" href="<?php echo esc_url($live_url); ?>" target="_blank" rel="noopener noreferrer">
							<span class="pf-card__live-dot"></span> Live site &rarr;
						</a>
					<?php endif; ?>
				</article>
			<?php endwhile; ?>
		</div>
	<?php else : ?>
		<p class="blog-shell__empty"><?php echo esc_html(dt_label('no_posts', 'archive_portfolio')); ?></p>
	<?php endif; ?>

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
	</footer>
</div>

<?php get_footer(); ?>
