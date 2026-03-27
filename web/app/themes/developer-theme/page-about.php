<?php
/**
 * Template Name: About Page
 * Professional background, tech stack, and working approach.
 */

get_header();

$lang = function_exists('pll_current_language') ? pll_current_language('slug') : 'en';

$home_url      = function_exists('pll_home_url') ? pll_home_url() : home_url('/');
$blog_url      = get_permalink(get_option('page_for_posts'));
$portfolio_url = get_post_type_archive_link('portfolio');
$contact_page  = get_page_by_path('contact');
$contact_url   = $contact_page ? get_permalink($contact_page) : $home_url;
$booking_url   = '#'; // TODO: Replace with Calendly link

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
        'url'   => $home_url . '#services',
        'label' => dt_label('services', 'nav'),
    ],
    [
        'url'   => $portfolio_url,
        'label' => dt_label('portfolio', 'nav'),
    ],
    [
        'url'   => $blog_url,
        'label' => dt_label('articles', 'nav'),
    ],
    [
        'url'   => $contact_url,
        'label' => dt_label('contact', 'nav'),
    ],
];

$stack_items = dt_label('about_stack_items', 'about');
?>

<div id="content" role="main" class="blog-shell">
	<header class="blog-shell__masthead">
		<div class="blog-shell__topbar">
			<?php echo developer_theme_render_brand('blog-shell__brand', $home_url); ?>
			<nav class="blog-shell__nav site-topbar__desktop-nav" aria-label="Main navigation">
				<a href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(dt_label('home', 'nav')); ?></a>
				<a href="<?php echo esc_url($home_url); ?>#services"><?php echo esc_html(dt_label('services', 'nav')); ?></a>
				<a href="<?php echo esc_url($portfolio_url); ?>"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></a>
				<a href="<?php echo esc_url($blog_url); ?>"><?php echo esc_html(dt_label('articles', 'nav')); ?></a>
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

	<!-- Hero -->
	<div class="blog-shell__hero">
		<p class="blog-shell__eyebrow"><?php echo esc_html(dt_label('about_eye', 'about')); ?></p>
		<h1 class="blog-shell__title"><?php echo esc_html(dt_label('about_h1', 'about')); ?></h1>
		<p class="blog-shell__intro"><?php echo esc_html(dt_label('about_intro', 'about')); ?></p>
	</div>

	<!-- About content -->
	<div class="about-page">

		<!-- Bio + Photo -->
		<section class="about-page__bio">
			<div class="about-page__bio-text">
				<h2><?php echo esc_html(dt_label('bio_h2', 'about')); ?></h2>
				<p><?php echo esc_html(dt_label('bio_p1', 'about')); ?></p>
				<p><?php echo esc_html(dt_label('bio_p2', 'about')); ?></p>
			</div>
			<div class="about-page__bio-photo">
				<img src="<?php echo esc_url(dt_shared('profile_image_url')); ?>" alt="<?php echo esc_attr(dt_label('brand', 'nav')); ?>" width="400" height="400" loading="lazy" />
			</div>
		</section>

		<!-- Experience Timeline -->
		<section class="about-page__section">
			<h2><?php echo esc_html(dt_label('exp_h2', 'about')); ?></h2>
			<p class="about-page__section-intro"><?php echo esc_html(dt_label('exp_intro', 'about')); ?></p>
			<div class="about-page__timeline">
				<?php
                $roles = [
                    [ 'role1_date', 'role1_h3', 'role1_p' ],
                    [ 'role2_date', 'role2_h3', 'role2_p' ],
                    [ 'role3_date', 'role3_h3', 'role3_p' ],
                ];
foreach ($roles as $role) :
    ?>
					<article class="about-page__role">
						<span class="about-page__role-date"><?php echo esc_html(dt_label($role[0], 'about')); ?></span>
						<h3><?php echo esc_html(dt_label($role[1], 'about')); ?></h3>
						<p><?php echo esc_html(dt_label($role[2], 'about')); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</section>

		<!-- Tech Stack -->
		<section class="about-page__section">
			<h2><?php echo esc_html(dt_label('stack_h2', 'about')); ?></h2>
			<p class="about-page__section-intro"><?php echo esc_html(dt_label('stack_intro', 'about')); ?></p>
			<ul class="about-page__stack">
				<?php foreach ($stack_items as $tech) : ?>
					<li><?php echo esc_html($tech); ?></li>
				<?php endforeach; ?>
			</ul>
		</section>

		<!-- How I Work -->
		<section class="about-page__section">
			<h2><?php echo esc_html(dt_label('approach_h2', 'about')); ?></h2>
			<p class="about-page__section-intro"><?php echo esc_html(dt_label('approach_intro', 'about')); ?></p>
			<ul class="about-page__principles">
				<?php foreach (dt_label('approach_items', 'about') as $item) : ?>
					<li><?php echo esc_html($item); ?></li>
				<?php endforeach; ?>
			</ul>
		</section>

		<!-- CTA -->
		<section class="about-page__cta">
			<h2><?php echo esc_html(dt_label('cta_h2', 'about')); ?></h2>
			<p><?php echo esc_html(dt_label('cta_p', 'about')); ?></p>
			<div class="about-page__cta-actions">
				<a class="about-page__button about-page__button--primary" href="<?php echo esc_url($booking_url); ?>"><?php echo esc_html(dt_label('cta_book', 'about')); ?> &rarr;</a>
				<a class="about-page__button" href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html(dt_label('cta_contact', 'about')); ?> &rarr;</a>
			</div>
		</section>

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
				<a href="<?php echo esc_url($portfolio_url); ?>"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></a>
			</nav>
		</div>
	</footer>
</div>

<?php get_footer(); ?>
