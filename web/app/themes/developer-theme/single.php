<?php
get_header();
wp_reset_postdata();

if ( ! have_posts() ) {
	get_footer();
	return;
}

the_post();

$post_id = get_the_ID();
$lang    = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : 'en';

$home_url = add_query_arg( 'lang', $lang, home_url( '/' ) );
$posts_page_id = (int) get_option( 'page_for_posts' );

if ( $posts_page_id && function_exists( 'pll_get_post' ) ) {
	$translated_posts_page_id = pll_get_post( $posts_page_id, $lang );
	if ( $translated_posts_page_id ) {
		$posts_page_id = $translated_posts_page_id;
	}
}

$section_url = static function ( $base, $anchor ) {
	return $base . '#' . ltrim( $anchor, '#' );
};

$blog_url      = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/blog/' );
$portfolio_url = get_post_type_archive_link( 'portfolio' );
$contact_url   = $section_url( $home_url, 'contact' );

$language_links = array();
if ( function_exists( 'pll_the_languages' ) ) {
	$language_links = pll_the_languages(
		array(
			'raw'               => 1,
			'hide_if_empty'     => 0,
			'hide_if_no_translation' => 0,
		)
	);
}

$word_count   = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) );
$reading_time = max( 1, (int) ceil( $word_count / 220 ) );
$excerpt      = has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 34 );
$category     = get_the_category();
$category     = ! empty( $category ) ? $category[0]->name : dt_label( 'articles', 'nav' );
if ( in_array( strtolower( $category ), array( 'uncategorized', 'без рубрики', 'uncategorized-en', 'uncategorized-ru' ), true ) ) {
	$category = dt_label( 'articles', 'nav' );
}

$processed_ids = array();
$toc_items      = array();
$content_html   = apply_filters( 'the_content', get_the_content() );

$content_html = preg_replace_callback(
	'/<h([23])([^>]*)>(.*?)<\/h\1>/is',
	static function ( $matches ) use ( &$processed_ids, &$toc_items ) {
		$level = (int) $matches[1];
		$text  = trim( wp_strip_all_tags( $matches[3] ) );
		$id    = sanitize_title( $text );

		if ( '' === $id ) {
			$id = 'section-' . ( count( $toc_items ) + 1 );
		}

		if ( isset( $processed_ids[ $id ] ) ) {
			$processed_ids[ $id ]++;
			$id .= '-' . $processed_ids[ $id ];
		} else {
			$processed_ids[ $id ] = 1;
		}

		$toc_items[] = array(
			'id'    => $id,
			'text'  => $text,
			'level' => $level,
		);

		return sprintf( '<h%1$d id="%2$s"%3$s>%4$s</h%1$d>', $level, esc_attr( $id ), $matches[2], $matches[3] );
	},
	$content_html
);

$recent_posts = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 4,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'lang'                => $lang,
	)
);

$related_posts = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'lang'                => $lang,
	)
);

$prev_post = get_previous_post( false );
$next_post = get_next_post( false );

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

<div id="content" role="main">
	<div class="post-shell">
		<header class="post-masthead">
			<div class="post-masthead__topbar">
				<?php echo developer_theme_render_brand( 'post-masthead__brand', $home_url ); ?>

				<nav class="post-masthead__nav site-topbar__desktop-nav" aria-label="<?php echo esc_attr( dt_label( 'post_nav', 'single' ) ); ?>">
					<a href="<?php echo esc_url( $home_url ); ?>"><?php echo esc_html( dt_label( 'home', 'nav' ) ); ?></a>
					<a href="<?php echo esc_url( $blog_url ); ?>"><?php echo esc_html( dt_label( 'articles', 'nav' ) ); ?></a>
					<a href="<?php echo esc_url( $portfolio_url ); ?>"><?php echo esc_html( dt_label( 'portfolio', 'nav' ) ); ?></a>
					<a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( dt_label( 'contact', 'nav' ) ); ?></a>
				</nav>

				<?php if ( ! empty( $language_links ) ) : ?>
					<div class="post-masthead__langs site-topbar__desktop-langs" aria-label="Languages">
						<?php foreach ( $language_links as $language_link ) : ?>
							<a class="<?php echo ! empty( $language_link['current_lang'] ) ? 'is-active' : ''; ?>" href="<?php echo esc_url( $language_link['url'] ); ?>">
								<?php echo esc_html( strtoupper( $language_link['slug'] ) ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php echo developer_theme_render_mobile_menu( dt_label( 'post_nav', 'single' ), $header_links, $language_links ); ?>
			</div>

			<nav class="post-breadcrumbs" aria-label="<?php echo esc_attr( dt_label( 'breadcrumbs', 'single' ) ); ?>">
				<a href="<?php echo esc_url( $home_url ); ?>"><?php echo esc_html( dt_label( 'home', 'nav' ) ); ?></a>
				<span aria-hidden="true">&rsaquo;</span>
				<a href="<?php echo esc_url( $blog_url ); ?>"><?php echo esc_html( dt_label( 'articles', 'nav' ) ); ?></a>
				<span aria-hidden="true">&rsaquo;</span>
				<span><?php the_title(); ?></span>
			</nav>

			<div class="post-hero">
				<div class="post-hero__content">
					<p class="post-hero__eyebrow"><?php echo esc_html( dt_label( 'blog_article', 'single' ) . ' · ' . $category ); ?></p>
					<h1 class="post-hero__title" itemprop="headline"><?php the_title(); ?></h1>
					<p class="post-hero__excerpt"><?php echo esc_html( $excerpt ); ?></p>

					<div class="post-hero__meta">
						<span><strong><?php echo esc_html( dt_label( 'published', 'single' ) ); ?>:</strong> <?php echo esc_html( get_the_date() ); ?></span>
						<span><strong><?php echo esc_html( dt_label( 'updated', 'single' ) ); ?>:</strong> <?php echo esc_html( get_the_modified_date() ); ?></span>
						<span><?php echo esc_html( $reading_time . ' ' . dt_label( 'reading_time', 'single' ) ); ?></span>
					</div>

					<div class="post-hero__actions">
						<a class="post-shell__button post-shell__button--primary" href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( dt_label( 'cta_primary', 'single' ) ); ?></a>
						<a class="post-shell__button" href="<?php echo esc_url( $portfolio_url ); ?>"><?php echo esc_html( dt_label( 'cta_secondary', 'single' ) ); ?></a>
					</div>
				</div>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="post-hero__media">
						<?php echo get_the_post_thumbnail( $post_id, 'large', array( 'loading' => 'eager', 'itemprop' => 'image' ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</header>

		<?php if ( ! empty( $toc_items ) ) : ?>
			<section class="post-toc-panel post-sidebar__card" aria-labelledby="toc-title">
				<div class="post-toc-panel__intro">
					<p class="post-sidebar__eyebrow"><?php echo esc_html( dt_label( 'toc', 'single' ) ); ?></p>
					<h2 id="toc-title"><?php echo esc_html( dt_label( 'toc', 'single' ) ); ?></h2>
				</div>
				<ol class="post-sidebar__toc">
					<?php foreach ( $toc_items as $toc_item ) : ?>
						<li class="level-<?php echo (int) $toc_item['level']; ?>">
							<a href="#<?php echo esc_attr( $toc_item['id'] ); ?>"><?php echo esc_html( $toc_item['text'] ); ?></a>
						</li>
					<?php endforeach; ?>
				</ol>
			</section>
		<?php endif; ?>

		<div class="post-layout">
			<article class="post-article" itemscope itemtype="https://schema.org/BlogPosting">
				<div class="post-article__body" itemprop="articleBody">
					<?php echo wp_kses_post( $content_html ); ?>
				</div>

				<section class="post-share-card" aria-labelledby="share-title">
					<h2 id="share-title"><?php echo esc_html( dt_label( 'share_title', 'single' ) ); ?></h2>
					<div class="post-share-card__links">
						<a href="<?php echo esc_url( 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( get_permalink() ) ); ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a>
						<a href="<?php echo esc_url( 'https://twitter.com/intent/tweet?url=' . rawurlencode( get_permalink() ) . '&text=' . rawurlencode( get_the_title() ) ); ?>" target="_blank" rel="noopener noreferrer">X</a>
						<a href="<?php echo esc_url( 'mailto:?subject=' . rawurlencode( get_the_title() ) . '&body=' . rawurlencode( get_permalink() ) ); ?>">Email</a>
					</div>
				</section>

				<section class="post-author-card" aria-labelledby="author-title">
					<div class="post-author-card__avatar">
						<?php echo wp_get_attachment_image( 626, 'thumbnail', false, array( 'loading' => 'lazy', 'alt' => dt_shared( 'name' ) ) ); ?>
					</div>
					<div class="post-author-card__content">
						<p class="post-author-card__eyebrow"><?php echo esc_html( dt_label( 'author_title', 'single' ) ); ?></p>
						<h2 id="author-title"><?php echo esc_html( dt_shared( 'name' ) ); ?></h2>
						<p><?php echo esc_html( dt_label( 'author_body', 'single' ) ); ?></p>
					</div>
				</section>

				<?php if ( $prev_post || $next_post ) : ?>
					<nav class="post-pagination" aria-label="Article pagination">
						<?php if ( $prev_post ) : ?>
							<a class="post-pagination__item" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>">
								<span><?php echo esc_html( dt_label( 'previous_article', 'single' ) ); ?></span>
								<strong><?php echo esc_html( get_the_title( $prev_post ) ); ?></strong>
							</a>
						<?php endif; ?>

						<?php if ( $next_post ) : ?>
							<a class="post-pagination__item post-pagination__item--next" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">
								<span><?php echo esc_html( dt_label( 'next_article', 'single' ) ); ?></span>
								<strong><?php echo esc_html( get_the_title( $next_post ) ); ?></strong>
							</a>
						<?php endif; ?>
					</nav>
				<?php endif; ?>
			</article>

			<aside class="post-sidebar" aria-label="Article sidebar">
				<section class="post-sidebar__card">
					<h2><?php echo esc_html( dt_label( 'recent_posts', 'single' ) ); ?></h2>
					<?php if ( $recent_posts->have_posts() ) : ?>
						<ul class="post-sidebar__list">
							<?php
							while ( $recent_posts->have_posts() ) :
								$recent_posts->the_post();
								?>
								<li>
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</li>
							<?php endwhile; ?>
						</ul>
						<?php wp_reset_postdata(); ?>
					<?php endif; ?>
				</section>

				<section class="post-sidebar__card post-sidebar__card--cta">
					<p class="post-sidebar__eyebrow"><?php echo esc_html( dt_label( 'cta_eyebrow', 'single' ) ); ?></p>
					<h2><?php echo esc_html( dt_label( 'cta_title', 'single' ) ); ?></h2>
					<p><?php echo esc_html( dt_label( 'cta_body', 'single' ) ); ?></p>
					<a class="post-shell__button post-shell__button--primary" href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( dt_label( 'cta_primary', 'single' ) ); ?></a>
				</section>
			</aside>
		</div>

		<?php if ( $related_posts->have_posts() ) : ?>
			<section class="post-related-grid" aria-labelledby="related-posts-title">
				<div class="post-related-grid__header">
					<p class="post-related-grid__eyebrow"><?php echo esc_html( dt_label( 'explore_more', 'single' ) ); ?></p>
					<h2 id="related-posts-title"><?php echo esc_html( dt_label( 'recent_posts', 'single' ) ); ?></h2>
				</div>

				<div class="post-related-grid__items">
					<?php
					while ( $related_posts->have_posts() ) :
						$related_posts->the_post();
						?>
						<a class="post-related-grid__item" href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="post-related-grid__media"><?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?></div>
							<?php endif; ?>
							<div class="post-related-grid__content">
								<p><?php echo esc_html( get_the_date() ); ?></p>
								<h3><?php the_title(); ?></h3>
								<span><?php echo esc_html( wp_trim_words( get_the_excerpt(), 14 ) ); ?></span>
							</div>
						</a>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</section>
		<?php endif; ?>

		<footer class="post-shell__site-footer">
			<div>
				<strong><?php echo esc_html( dt_label( 'brand', 'nav' ) ); ?></strong>
				<p><?php echo esc_html( dt_label( 'footer_text', 'single' ) ); ?></p>
			</div>
			<nav aria-label="Footer navigation">
				<a href="<?php echo esc_url( $home_url ); ?>"><?php echo esc_html( dt_label( 'home', 'nav' ) ); ?></a>
				<a href="<?php echo esc_url( $blog_url ); ?>"><?php echo esc_html( dt_label( 'articles', 'nav' ) ); ?></a>
				<a href="<?php echo esc_url( $portfolio_url ); ?>"><?php echo esc_html( dt_label( 'portfolio', 'nav' ) ); ?></a>
				<a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( dt_label( 'contact', 'nav' ) ); ?></a>
			</nav>
		</footer>
	</div>
</div>

<?php get_footer(); ?>
