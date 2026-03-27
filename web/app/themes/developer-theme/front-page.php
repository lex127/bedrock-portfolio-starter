<?php
/**
 * Front page template — service-focused landing page.
 */

get_header();

$lang = function_exists('pll_current_language') ? pll_current_language('slug') : 'en';

$home_url      = function_exists('pll_home_url') ? pll_home_url() : home_url('/');
$portfolio_url = get_post_type_archive_link('portfolio');
$contact_url   = developer_theme_page_url('contact', $lang, $home_url . '#contact');
$booking_url   = $contact_url;
$cv_url        = trim((string) dt_shared('cv_pdf_url'));

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

$portfolio_items = new WP_Query(
    [
        'post_type'      => 'portfolio',
        'posts_per_page' => 3,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'lang'           => $lang,
    ],
);

$recent_articles = new WP_Query(
    [
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'lang'           => $lang,
    ],
);

$header_links = [
    [
        'url'   => $home_url,
        'label' => dt_label('home', 'nav'),
    ],
    [
        'url'   => '#services',
        'label' => dt_label('services', 'nav'),
    ],
    [
        'url'   => $portfolio_url,
        'label' => dt_label('portfolio', 'nav'),
    ],
    [
        'url'   => '#writing',
        'label' => dt_label('articles', 'nav'),
    ],
    [
        'url'   => $contact_url,
        'label' => dt_label('contact', 'nav'),
    ],
];
?>

<div id="content" role="main">
<div class="home-landing">

	<header class="home-landing__masthead">
		<div class="home-landing__topbar">
			<?php echo developer_theme_render_brand('home-landing__brand', $home_url); ?>
			<nav class="home-landing__nav site-topbar__desktop-nav" aria-label="Main navigation">
				<a href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(dt_label('home', 'nav')); ?></a>
				<a href="#services"><?php echo esc_html(dt_label('services', 'nav')); ?></a>
				<a href="<?php echo esc_url($portfolio_url); ?>"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></a>
				<a href="#writing"><?php echo esc_html(dt_label('articles', 'nav')); ?></a>
				<a href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html(dt_label('contact', 'nav')); ?></a>
			</nav>
			<?php if (! empty($language_links)) : ?>
				<div class="home-landing__langs site-topbar__desktop-langs" aria-label="Languages">
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

	<!-- Hero: Value proposition -->
	<section class="home-landing__hero">
		<div class="home-landing__hero-grid">
			<div>
				<p class="home-landing__eyebrow"><?php echo wp_kses_post(dt_label('hero_eyebrow', 'front')); ?></p>
				<h1><?php echo esc_html(dt_label('hero_h1', 'front')); ?></h1>
				<p class="home-landing__lead"><?php echo esc_html(dt_label('hero_lead', 'front')); ?></p>
				<p class="home-landing__summary"><?php echo esc_html(dt_label('hero_summary', 'front')); ?></p>
				<div class="home-landing__actions">
					<a class="home-landing__button home-landing__button--primary" href="<?php echo esc_url($booking_url); ?>"><?php echo esc_html(dt_label('cta_book', 'front')); ?></a>
					<a class="home-landing__button" href="#services"><?php echo esc_html(dt_label('cta_services', 'front')); ?></a>
					<?php if ('' !== $cv_url) : ?>
						<a class="home-landing__button" href="<?php echo esc_url($cv_url); ?>" target="_blank" rel="noopener noreferrer">
							<?php echo esc_html(dt_label('cta_cv', 'front')); ?>
						</a>
					<?php endif; ?>
				</div>
				<ul class="home-landing__stats">
					<li><strong><?php echo esc_html(dt_label('stat1_num', 'front')); ?></strong><span><?php echo esc_html(dt_label('stat1_label', 'front')); ?></span></li>
					<li><strong><?php echo esc_html(dt_label('stat2_num', 'front')); ?></strong><span><?php echo esc_html(dt_label('stat2_label', 'front')); ?></span></li>
					<li><strong><?php echo esc_html(dt_label('stat3_num', 'front')); ?></strong><span><?php echo esc_html(dt_label('stat3_label', 'front')); ?></span></li>
				</ul>
			</div>
			<div class="home-landing__portrait-wrap">
				<img class="home-landing__portrait" src="<?php echo esc_url(dt_shared('profile_image_url')); ?>" alt="<?php echo esc_attr(dt_label('brand', 'nav')); ?>" width="430" height="430" loading="eager" />
				<div class="home-landing__hero-note">
					<span class="home-landing__hero-note-dot"></span>
					<div>
						<strong><?php echo esc_html(dt_label('focus_label', 'front')); ?></strong>
						<span><?php echo esc_html(dt_label('focus_text', 'front')); ?></span>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Problems I Solve -->
	<section class="home-landing__section" id="problems">
		<div class="home-landing__section-head">
			<div>
				<p class="home-landing__section-eyebrow"><?php echo esc_html(dt_label('problems_eye', 'front')); ?></p>
				<h2><?php echo esc_html(dt_label('problems_h2', 'front')); ?></h2>
			</div>
			<p class="home-landing__section-intro"><?php echo esc_html(dt_label('problems_intro', 'front')); ?></p>
		</div>
		<div class="home-landing__grid home-landing__grid--problems">
			<?php
            $problems = [
                ['problem1_h3', 'problem1_p'],
                ['problem2_h3', 'problem2_p'],
                ['problem3_h3', 'problem3_p'],
            ];
foreach ($problems as $prob) :
    ?>
				<article class="home-landing__problem-card">
					<h3><?php echo esc_html(dt_label($prob[0], 'front')); ?></h3>
					<p><?php echo esc_html(dt_label($prob[1], 'front')); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- Services: Productized offerings -->
	<section class="home-landing__section home-landing__section--accent" id="services">
		<div class="home-landing__section-head">
			<div>
				<p class="home-landing__section-eyebrow"><?php echo esc_html(dt_label('services_eye', 'front')); ?></p>
				<h2><?php echo esc_html(dt_label('services_h2', 'front')); ?></h2>
			</div>
			<p class="home-landing__section-intro"><?php echo esc_html(dt_label('services_intro', 'front')); ?></p>
		</div>
		<div class="home-landing__grid home-landing__grid--services">
			<?php
            $services = [
                ['svc1_tag', 'svc1_h3', 'svc1_p', 'svc1_cta'],
                ['svc2_tag', 'svc2_h3', 'svc2_p', 'svc2_cta'],
                ['svc3_tag', 'svc3_h3', 'svc3_p', 'svc3_cta'],
            ];
foreach ($services as $svc) :
    ?>
				<article class="home-landing__service-card">
					<span class="home-landing__service-price"><?php echo esc_html(dt_label($svc[0], 'front')); ?></span>
					<h3><?php echo esc_html(dt_label($svc[1], 'front')); ?></h3>
					<p><?php echo esc_html(dt_label($svc[2], 'front')); ?></p>
					<span class="home-landing__service-cta"><?php echo esc_html(dt_label($svc[3], 'front')); ?> &rarr;</span>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- Social Proof -->
	<section class="home-landing__proof" id="proof">
		<p class="home-landing__proof-eye"><?php echo esc_html(dt_label('proof_eye', 'front')); ?></p>
		<ul class="home-landing__proof-list">
			<?php foreach (dt_label('proof_names', 'front') as $name) : ?>
				<li><?php echo esc_html($name); ?></li>
			<?php endforeach; ?>
		</ul>
	</section>

	<!-- Featured Case Studies -->
	<?php if ($portfolio_items->have_posts()) : ?>
	<section class="home-landing__section" id="portfolio">
		<div class="home-landing__section-head">
			<div>
				<p class="home-landing__section-eyebrow"><?php echo esc_html(dt_label('portfolio_eye', 'front')); ?></p>
				<h2><?php echo esc_html(dt_label('portfolio_h2', 'front')); ?></h2>
			</div>
			<p class="home-landing__section-intro"><?php echo esc_html(dt_label('portfolio_intro', 'front')); ?></p>
		</div>
		<div class="home-landing__grid home-landing__grid--portfolio">
			<?php
            while ($portfolio_items->have_posts()) :
                $portfolio_items->the_post();
                $skills     = get_the_terms(get_the_ID(), 'portfolio_skills');
                $live_url   = get_post_meta(get_the_ID(), 'portfolio_link', true);
                $pf_label   = get_post_meta(get_the_ID(), 'portfolio_label', true);
                ?>
				<article class="home-landing__portfolio-card">
					<a class="home-landing__portfolio-card-link" href="<?php the_permalink(); ?>">
						<?php if (has_post_thumbnail()) : ?>
							<img class="home-landing__portfolio-card-image" src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium_large')); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" />
						<?php endif; ?>
						<div class="home-landing__portfolio-card-body">
							<?php if ($pf_label) : ?>
								<span class="home-landing__portfolio-card-label"><?php echo esc_html($pf_label); ?></span>
							<?php endif; ?>
							<h3><?php the_title(); ?></h3>
							<p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
						</div>
					</a>
					<?php if (! empty($skills) && ! is_wp_error($skills)) : ?>
						<ul class="home-landing__portfolio-card-stack">
							<?php foreach ($skills as $skill) : ?>
								<li><?php echo esc_html($skill->name); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if ($live_url) : ?>
						<a class="home-landing__portfolio-card-live" href="<?php echo esc_url($live_url); ?>" target="_blank" rel="noopener noreferrer">
							<span class="home-landing__portfolio-card-live-dot"></span> Live site &rarr;
						</a>
					<?php endif; ?>
				</article>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
		<div class="home-landing__portfolio-cta">
			<a class="home-landing__button" href="<?php echo esc_url($portfolio_url); ?>"><?php echo esc_html(dt_label('portfolio', 'nav')); ?> &rarr;</a>
		</div>
	</section>
	<?php endif; ?>

	<!-- How I Work: Process -->
	<section class="home-landing__section home-landing__section--subtle" id="process">
		<div class="home-landing__section-head">
			<div>
				<p class="home-landing__section-eyebrow"><?php echo esc_html(dt_label('process_eye', 'front')); ?></p>
				<h2><?php echo esc_html(dt_label('process_h2', 'front')); ?></h2>
			</div>
			<p class="home-landing__section-intro"><?php echo esc_html(dt_label('process_intro', 'front')); ?></p>
		</div>
		<div class="home-landing__grid home-landing__grid--steps">
			<?php
            $steps = [
                ['step1_num', 'step1_h3', 'step1_p'],
                ['step2_num', 'step2_h3', 'step2_p'],
                ['step3_num', 'step3_h3', 'step3_p'],
            ];
foreach ($steps as $step) :
    ?>
				<article class="home-landing__step-card">
					<span class="home-landing__step-num"><?php echo esc_html(dt_label($step[0], 'front')); ?></span>
					<h3><?php echo esc_html(dt_label($step[1], 'front')); ?></h3>
					<p><?php echo esc_html(dt_label($step[2], 'front')); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- Latest Articles -->
	<section class="home-landing__section" id="writing">
		<div class="home-landing__section-head">
			<div>
				<p class="home-landing__section-eyebrow"><?php echo esc_html(dt_label('writing_eye', 'front')); ?></p>
				<h2><?php echo esc_html(dt_label('writing_h2', 'front')); ?></h2>
			</div>
			<p class="home-landing__section-intro"><?php echo esc_html(dt_label('writing_intro', 'front')); ?></p>
		</div>
		<?php if ($recent_articles->have_posts()) : ?>
			<div class="home-landing__grid home-landing__grid--articles">
				<?php
    while ($recent_articles->have_posts()) :
        $recent_articles->the_post();
        $cat      = get_the_category();
        $cat_name = ! empty($cat) ? $cat[0]->name : 'Article';
        if (in_array(strtolower($cat_name), ['uncategorized', 'uncategorized-en'], true)) {
            $cat_name = 'Article';
        }
        ?>
					<a class="home-landing__article" href="<?php the_permalink(); ?>">
						<?php if (has_post_thumbnail()) : ?>
							<img class="home-landing__article-image" src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium_large')); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" />
						<?php endif; ?>
						<div class="home-landing__article-body">
							<span class="home-landing__article-meta"><?php echo esc_html($cat_name); ?></span>
							<h3><?php the_title(); ?></h3>
							<p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
						</div>
					</a>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		<?php endif; ?>
	</section>

	<!-- Contact / CTA -->
	<section class="home-landing__section home-landing__section--contact" id="contact">
		<div class="home-landing__section-head">
			<div>
				<p class="home-landing__section-eyebrow"><?php echo esc_html(dt_label('contact_eye', 'front')); ?></p>
				<h2><?php echo esc_html(dt_label('contact_h2', 'front')); ?></h2>
			</div>
			<p class="home-landing__section-intro"><?php echo esc_html(dt_label('contact_intro', 'front')); ?></p>
		</div>
		<div class="home-landing__contact-grid">
			<a class="home-landing__contact-card" href="mailto:<?php echo esc_attr(dt_shared('email')); ?>">
				<strong>Email</strong>
				<span><?php echo esc_html(dt_shared('email')); ?></span>
			</a>
			<a class="home-landing__contact-card" href="<?php echo esc_url(dt_shared('linkedin_url')); ?>" target="_blank" rel="noopener noreferrer">
				<strong>LinkedIn</strong>
				<span><?php echo esc_html(dt_shared('linkedin_display')); ?></span>
			</a>
			<a class="home-landing__contact-card" href="<?php echo esc_url(dt_shared('github_url')); ?>" target="_blank" rel="noopener noreferrer">
				<strong>GitHub</strong>
				<span><?php echo esc_html(dt_shared('github_display')); ?></span>
			</a>
			<a class="home-landing__contact-card" href="<?php echo esc_url(dt_shared('telegram_url')); ?>" target="_blank" rel="noopener noreferrer">
				<strong>Telegram</strong>
				<span><?php echo esc_html(dt_shared('telegram_display')); ?></span>
			</a>
		</div>
		<div class="home-landing__contact-actions">
			<a class="home-landing__button home-landing__button--primary home-landing__button--lg" href="<?php echo esc_url($contact_url); ?>">
				<?php echo esc_html(dt_label('contact_cta_book', 'front')); ?> &rarr;
			</a>
			<a class="home-landing__button home-landing__button--lg" href="<?php echo esc_url($contact_url); ?>">
				<?php echo esc_html(dt_label('contact_cta', 'front')); ?> &rarr;
			</a>
		</div>
	</section>

	<footer class="home-landing__footer">
		<div class="home-landing__footer-inner">
			<div>
				<strong><?php echo esc_html(dt_label('brand', 'nav')); ?></strong>
				<p><?php echo esc_html(dt_label('footer_text', 'nav')); ?></p>
			</div>
			<nav aria-label="Footer navigation">
				<a href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(dt_label('home', 'nav')); ?></a>
				<a href="#services"><?php echo esc_html(dt_label('services', 'nav')); ?></a>
				<a href="<?php echo esc_url($portfolio_url); ?>"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></a>
				<a href="#writing"><?php echo esc_html(dt_label('articles', 'nav')); ?></a>
				<a href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html(dt_label('contact', 'nav')); ?></a>
			</nav>
		</div>
		<p class="home-landing__copyright"><?php echo wp_kses_post(dt_label('footer_copy', 'nav')); ?></p>
		<?php echo wp_kses_post(developer_theme_footer_credit()); ?>
	</footer>

</div>
</div>

<?php get_footer(); ?>
