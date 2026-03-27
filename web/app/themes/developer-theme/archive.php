<?php
/**
 * Archive template (categories, tags, date archives).
 * Matches the blog shell design language.
 */

get_header();

$lang = function_exists('pll_current_language') ? pll_current_language('slug') : 'en';

$home_url      = function_exists('pll_home_url') ? pll_home_url() : home_url('/');
$blog_url      = developer_theme_posts_page_url($lang);
$portfolio_url = get_post_type_archive_link('portfolio');
$contact_url   = developer_theme_page_url('contact', $lang, $home_url . '#contact');

$archive_title       = get_the_archive_title();
$archive_description = get_the_archive_description();

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
        'url'     => $blog_url,
        'label'   => dt_label('articles', 'nav'),
        'current' => true,
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

<div id="content" role="main" class="blog-shell">
	<header class="blog-shell__masthead">
		<div class="blog-shell__topbar">
			<?php echo developer_theme_render_brand('blog-shell__brand', $home_url); ?>
			<nav class="blog-shell__nav site-topbar__desktop-nav" aria-label="Main navigation">
				<a href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(dt_label('home', 'nav')); ?></a>
				<a class="is-current" href="<?php echo esc_url($blog_url); ?>"><?php echo esc_html(dt_label('articles', 'nav')); ?></a>
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
			<?php echo developer_theme_render_mobile_menu('Main navigation', $header_links, $language_links); ?>
		</div>
	</header>

	<div class="blog-shell__hero">
		<nav class="blog-shell__breadcrumbs" aria-label="Breadcrumbs">
			<a href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(dt_label('home', 'nav')); ?></a>
			<span>/</span>
			<a href="<?php echo esc_url($blog_url); ?>"><?php echo esc_html(dt_label('articles', 'nav')); ?></a>
			<span>/</span>
			<span><?php echo wp_kses_post($archive_title); ?></span>
		</nav>
		<h1 class="blog-shell__title"><?php echo wp_kses_post($archive_title); ?></h1>
		<?php if ($archive_description) : ?>
			<p class="blog-shell__intro"><?php echo wp_kses_post($archive_description); ?></p>
		<?php endif; ?>
	</div>

	<?php if (have_posts()) : ?>
		<div class="blog-shell__grid">
			<?php
            while (have_posts()) :
                the_post();
                $cat      = get_the_category();
                $cat_name = ! empty($cat) ? $cat[0]->name : 'Article';
                if (in_array(strtolower($cat_name), [ 'uncategorized', 'uncategorized-en', 'uncategorized-ru', 'без рубрики' ], true)) {
                    $cat_name = dt_label('articles', 'nav');
                }
                $word_count   = str_word_count(wp_strip_all_tags(get_post_field('post_content', get_the_ID())));
                $reading_time = max(1, (int) ceil($word_count / 220));
                ?>
				<article class="blog-card">
					<a class="blog-card__link" href="<?php the_permalink(); ?>">
						<?php if (has_post_thumbnail()) : ?>
							<div class="blog-card__media">
								<?php the_post_thumbnail('medium_large', [ 'loading' => 'lazy', 'class' => 'blog-card__image' ]); ?>
							</div>
						<?php endif; ?>
						<div class="blog-card__content">
							<div class="blog-card__meta">
								<span class="blog-card__tag"><?php echo esc_html($cat_name); ?></span>
								<time class="blog-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
								<span class="blog-card__reading"><?php echo esc_html($reading_time); ?> min</span>
							</div>
							<h2 class="blog-card__title"><?php the_title(); ?></h2>
							<p class="blog-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 24)); ?></p>
							<span class="blog-card__cta"><?php echo esc_html(dt_label('read_more', 'archive')); ?> &rarr;</span>
						</div>
					</a>
				</article>
			<?php endwhile; ?>
		</div>

		<?php
        $prev_link = get_previous_posts_link(dt_label('newer', 'archive'));
	    $next_link = get_next_posts_link(dt_label('older', 'archive'));
	    if ($prev_link || $next_link) :
	        ?>
			<nav class="blog-shell__pagination" aria-label="Archive pagination">
				<?php if ($prev_link) : ?>
					<span class="blog-shell__pagination-item"><?php echo wp_kses_post($prev_link); ?></span>
				<?php endif; ?>
				<?php if ($next_link) : ?>
					<span class="blog-shell__pagination-item"><?php echo wp_kses_post($next_link); ?></span>
				<?php endif; ?>
			</nav>
		<?php endif; ?>

	<?php else : ?>
		<p class="blog-shell__empty"><?php echo esc_html(dt_label('no_posts', 'archive')); ?></p>
	<?php endif; ?>

	<footer class="blog-shell__footer">
		<div class="blog-shell__footer-inner">
			<div>
				<strong><?php echo esc_html(dt_label('brand', 'nav')); ?></strong>
				<p><?php echo esc_html(dt_label('footer_text', 'nav')); ?></p>
			</div>
			<nav aria-label="Footer navigation">
				<a href="<?php echo esc_url($home_url); ?>"><?php echo esc_html(dt_label('home', 'nav')); ?></a>
				<a href="<?php echo esc_url($portfolio_url); ?>"><?php echo esc_html(dt_label('portfolio', 'nav')); ?></a>
				<a href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html(dt_label('contact', 'nav')); ?></a>
			</nav>
		</div>
		<?php echo wp_kses_post(developer_theme_footer_credit()); ?>
	</footer>
</div>

<?php get_footer(); ?>
