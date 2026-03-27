<?php

/**
 * Developer Theme — functions and definitions.
 */

require_once get_template_directory() . '/inc/customizer-config.php';
require_once get_template_directory() . '/inc/customizer-helpers.php';
require_once get_template_directory() . '/inc/customizer.php';

add_action('after_setup_theme', 'developer_theme_setup');
function developer_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'custom-logo',
        [
            'height'               => 120,
            'width'                => 420,
            'flex-height'          => true,
            'flex-width'           => true,
            'unlink-homepage-logo' => true,
        ],
    );
    add_theme_support('site-icon');
    add_theme_support('html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ]);
}

function developer_theme_get_brand_asset_url()
{
    $custom_logo_id = (int) get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
        if ($logo_url) {
            return $logo_url;
        }
    }

    $site_icon_id = (int) get_option('site_icon');
    if ($site_icon_id) {
        $icon_url = wp_get_attachment_image_url($site_icon_id, 'full');
        if ($icon_url) {
            return $icon_url;
        }
    }

    return get_template_directory_uri() . '/assets/brand/brand-mark.svg';
}

function developer_theme_get_favicon_asset_url()
{
    return get_template_directory_uri() . '/assets/brand/favicon.svg';
}

function developer_theme_render_brand($link_class, $home_url)
{
    $brand_text = dt_label('brand', 'nav');
    $brand_mark = get_template_directory_uri() . '/assets/brand/brand-mark.svg';

    return sprintf(
        '<a class="%1$s site-brand" href="%2$s"><span class="site-brand__mark" aria-hidden="true"><img class="site-brand__mark-image" src="%3$s" alt="" width="40" height="40" decoding="async" /></span><span class="site-brand__name">%4$s</span></a>',
        esc_attr($link_class),
        esc_url($home_url),
        esc_url($brand_mark),
        esc_html($brand_text),
    );
}

function developer_theme_render_mobile_menu($nav_label, $nav_links, $language_links = [])
{
    if (empty($nav_links) || ! is_array($nav_links)) {
        return '';
    }

    $output  = '<details class="site-mobile-nav">';
    $output .= '<summary class="site-mobile-nav__toggle" aria-label="' . esc_attr($nav_label) . '">';
    $output .= '<span class="site-mobile-nav__toggle-lines" aria-hidden="true"><span></span><span></span><span></span></span>';
    $output .= '<span class="screen-reader-text">' . esc_html($nav_label) . '</span>';
    $output .= '</summary>';
    $output .= '<div class="site-mobile-nav__panel">';
    $output .= '<nav class="site-mobile-nav__links" aria-label="' . esc_attr($nav_label) . '">';

    foreach ($nav_links as $link) {
        if (empty($link['url']) || empty($link['label'])) {
            continue;
        }

        $link_classes = [ 'site-mobile-nav__link' ];
        if (! empty($link['current'])) {
            $link_classes[] = 'is-current';
        }

        $output .= sprintf(
            '<a class="%1$s" href="%2$s">%3$s</a>',
            esc_attr(implode(' ', $link_classes)),
            esc_url($link['url']),
            esc_html($link['label']),
        );
    }

    $output .= '</nav>';

    if (! empty($language_links) && is_array($language_links)) {
        $output .= '<div class="site-mobile-nav__langs" aria-label="Languages">';

        foreach ($language_links as $language_link) {
            if (empty($language_link['url']) || empty($language_link['slug'])) {
                continue;
            }

            $lang_classes = [ 'site-mobile-nav__lang' ];
            if (! empty($language_link['current_lang'])) {
                $lang_classes[] = 'is-active';
            }

            $output .= sprintf(
                '<a class="%1$s" href="%2$s">%3$s</a>',
                esc_attr(implode(' ', $lang_classes)),
                esc_url($language_link['url']),
                esc_html(strtoupper($language_link['slug'])),
            );
        }

        $output .= '</div>';
    }

    $output .= '</div></details>';

    return $output;
}

/**
 * Resolve the current language slug with an English fallback.
 */
function developer_theme_current_language(): string
{
    if (function_exists('pll_current_language')) {
        $lang = pll_current_language('slug');
        if (is_string($lang) && '' !== $lang) {
            return $lang;
        }
    }

    return 'en';
}

add_action('wp_head', 'developer_theme_output_favicon', 2);
function developer_theme_output_favicon()
{
    $favicon_url = developer_theme_get_favicon_asset_url();

    if (! $favicon_url) {
        return;
    }

    echo '<link rel="icon" href="' . esc_url($favicon_url) . '" type="image/svg+xml" />' . "\n";
    echo '<meta name="theme-color" content="#101828" />' . "\n";
}

/**
 * Ensure Polylang redirect_lang is enabled (front page URL uses language code).
 * Runs once, then stores a flag so it doesn't repeat.
 */
add_action('admin_init', 'developer_theme_fix_polylang_settings');
function developer_theme_fix_polylang_settings()
{
    if (get_option('developer_theme_pll_fixed')) {
        return;
    }

    $options = get_option('polylang');
    if (is_array($options) && empty($options['redirect_lang'])) {
        $options['redirect_lang'] = true;
        update_option('polylang', $options);
        delete_transient('pll_languages_list');
    }

    update_option('developer_theme_pll_fixed', '1');
}

/**
 * Enqueue styles — single CSS file, no jQuery.
 */
add_action('wp_enqueue_scripts', 'developer_theme_styles');
function developer_theme_styles()
{
    $stylesheet_path = get_stylesheet_directory() . '/style.css';
    $stylesheet_ver  = file_exists($stylesheet_path) ? (string) filemtime($stylesheet_path) : '1.0';

    wp_enqueue_style('developer-theme', get_stylesheet_uri(), [], $stylesheet_ver);
}

/**
 * Remove jQuery from frontend (keep in admin for plugins).
 */
add_action('wp_enqueue_scripts', 'developer_theme_dequeue_jquery');
function developer_theme_dequeue_jquery()
{
    if (! is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', '', [], '1.0', true);
    }
}

/**
 * Clean up wp_head output.
 */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/**
 * Disable block library CSS on frontend (not using Gutenberg blocks for display).
 */
add_action('wp_enqueue_scripts', 'developer_theme_remove_block_css', 100);
function developer_theme_remove_block_css()
{
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');
}

/**
 * Add JSON-LD structured data for front page.
 */
add_action('wp_head', 'developer_theme_structured_data');
function developer_theme_structured_data()
{
    if (! is_front_page()) {
        return;
    }

    $site_url = home_url('/');
    $logo_url = developer_theme_get_brand_asset_url();
    $schema   = [
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type' => 'WebSite',
                '@id'   => $site_url . '#website',
                'url'   => $site_url,
                'name'  => dt_shared('name'),
            ],
            [
                '@type' => 'Organization',
                '@id'   => $site_url . '#organization',
                'url'   => $site_url,
                'name'  => dt_shared('name'),
                'logo'  => $logo_url ? [
                    '@type' => 'ImageObject',
                    'url'   => $logo_url,
                ] : null,
            ],
            [
                '@type'      => 'ProfilePage',
                '@id'        => $site_url . '#profile',
                'url'        => $site_url,
                'isPartOf'   => [ '@id' => $site_url . '#website' ],
                'mainEntity' => [ '@id' => $site_url . '#person' ],
            ],
            [
                '@type'         => 'Person',
                '@id'           => $site_url . '#person',
                'name'          => dt_shared('name'),
                'jobTitle'      => dt_shared('job_title'),
                'description'   => dt_shared('job_title') . ' focused on websites, digital products, and content experiences that are clear, maintainable, and ready to launch.',
                'url'           => $site_url,
                'image'         => $site_url . ltrim(dt_shared('profile_image_url'), '/'),
                'address'       => [
                    '@type'           => 'PostalAddress',
                    'addressLocality' => dt_shared('location'),
                ],
                'sameAs'        => [
                    dt_shared('linkedin_url'),
                    dt_shared('github_url'),
                    dt_shared('telegram_url'),
                ],
                'worksFor'      => [
                    '@id' => $site_url . '#organization',
                ],
                'knowsAbout'    => [
                    'Digital strategy', 'Content systems', 'UX writing', 'Website delivery',
                    'Multilingual websites', 'Portfolio design', 'Case studies', 'SEO basics',
                    'Launch planning', 'Performance optimization',
                ],
            ],
        ],
    ];

    $schema['@graph'][3]['sameAs'] = array_values(
        array_filter(
            $schema['@graph'][3]['sameAs'],
            static fn($url) => is_string($url) && '' !== $url,
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}

/**
 * Add canonical URL and OG image for pages without thumbnails.
 */
add_action('wp_head', 'developer_theme_seo_meta', 1);
function developer_theme_seo_meta()
{
    $og_image = home_url(dt_shared('og_image_url'));

    if (is_front_page()) {
        echo '<link rel="canonical" href="' . esc_url(home_url('/')) . '" />' . "\n";
    }

    if (! has_post_thumbnail()) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
        echo '<meta property="og:image:width" content="430" />' . "\n";
        echo '<meta property="og:image:height" content="430" />' . "\n";
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
}

/**
 * Fix Yoast OG title for front page.
 */
add_filter('wpseo_opengraph_title', 'developer_theme_fix_og_title');
function developer_theme_fix_og_title($title)
{
    if (is_front_page()) {
        return dt_shared('name') . ' — ' . dt_shared('job_title');
    }
    return $title;
}

/**
 * Smooth scrolling for front page anchor links.
 */
add_action('wp_footer', 'developer_theme_smooth_scroll');
function developer_theme_smooth_scroll()
{
    if (is_front_page()) {
        echo '<style>html{scroll-behavior:smooth}</style>' . "\n";
    }
}

/**
 * Fix Polylang static front page URLs.
 */
add_filter('pll_additional_language_data', 'developer_theme_fix_polylang_home_url', 10, 2);
function developer_theme_fix_polylang_home_url($additional_data, $language)
{
    if ('page' !== get_option('show_on_front')) {
        return $additional_data;
    }

    $options = get_option('polylang');

    if (! empty($options['hide_default']) && ! empty($language['is_default'])) {
        $additional_data['home_url'] = trailingslashit(home_url('/'));
    } else {
        $additional_data['home_url'] = trailingslashit(home_url('/' . $language['slug'] . '/'));
    }

    return $additional_data;
}

/**
 * Fix page_link for translated front pages.
 */
add_filter('page_link', 'developer_theme_fix_front_page_link', 25, 2);
function developer_theme_fix_front_page_link($link, $id)
{
    if ('page' !== get_option('show_on_front') || ! function_exists('pll_get_post_language')) {
        return $link;
    }

    $front_id = (int) get_option('page_on_front');
    if (! $front_id || ! function_exists('pll_get_post_translations')) {
        return $link;
    }

    $translations = pll_get_post_translations($front_id);
    $lang         = pll_get_post_language($id);
    $options      = get_option('polylang');

    if ($lang && in_array($id, $translations, true)) {
        if (! empty($options['hide_default']) && $options['default_lang'] === $lang) {
            return trailingslashit(home_url('/'));
        }
        return trailingslashit(home_url('/' . $lang . '/'));
    }

    return $link;
}

/**
 * Fix Polylang static front page: make /ru/, /uk/, /es/ serve the translated front page.
 */
add_action('parse_request', 'developer_theme_fix_polylang_front_page', 5);
function developer_theme_fix_polylang_front_page($wp)
{
    if ('page' !== get_option('show_on_front') || ! function_exists('pll_current_language')) {
        return;
    }

    if (! empty($wp->query_vars['lang']) && empty($wp->query_vars['pagename']) && empty($wp->query_vars['name']) && empty($wp->query_vars['post_type'])) {
        $lang     = $wp->query_vars['lang'];
        $front_id = (int) get_option('page_on_front');

        if ($front_id && function_exists('pll_get_post')) {
            $translated_id = pll_get_post($front_id, $lang);

            if ($translated_id) {
                $wp->query_vars['page_id'] = $translated_id;
                unset($wp->query_vars['lang']);
            }
        }
    }
}

/**
 * Prevent canonical redirect for translated front pages.
 */
add_filter('redirect_canonical', 'developer_theme_prevent_front_page_redirect', 10, 2);
function developer_theme_prevent_front_page_redirect($redirect_url, $requested_url)
{
    if (is_front_page()) {
        return false;
    }
    return $redirect_url;
}

/**
 * Fix language switcher URLs for static front page translations.
 * Prevents /ru/home-2/, /uk/home-3/ — returns clean /ru/, /uk/, /es/.
 */
add_filter('pll_pre_translation_url', 'developer_theme_fix_switcher_front_page_url', 5, 3);
function developer_theme_fix_switcher_front_page_url($url, $language, $queried_object_id)
{
    if ('page' !== get_option('show_on_front') || ! function_exists('pll_get_post_translations')) {
        return $url;
    }

    $front_id = (int) get_option('page_on_front');
    if (! $front_id) {
        return $url;
    }

    $translations = pll_get_post_translations($front_id);
    $qid          = (int) $queried_object_id;

    // Check queried_object_id, or fallback to get_queried_object_id(), or is_front_page().
    $is_front = $qid === $front_id || in_array($qid, $translations, true);

    if (! $is_front && 0 === $qid) {
        $real_qid = (int) get_queried_object_id();
        $is_front = $real_qid === $front_id || in_array($real_qid, $translations, true);
    }

    if (! $is_front) {
        return $url;
    }

    $options = get_option('polylang');
    $slug    = $language->slug;

    if (! empty($options['hide_default']) && $slug === $options['default_lang']) {
        return trailingslashit(home_url('/'));
    }

    return trailingslashit(home_url('/' . $slug . '/'));
}

/**
 * Get the URL for a page by slug, translated into the given language via Polylang.
 * Falls back to the original page URL if no translation exists.
 *
 * @param string $slug  Page slug (e.g. 'contact', 'about').
 * @param string $lang  Language slug (e.g. 'en', 'ru').
 * @param string $fallback  Fallback URL if page not found at all.
 */
function developer_theme_page_url(string $slug, string $lang = 'en', string $fallback = ''): string
{
    $page = get_page_by_path($slug);
    if (! $page) {
        return $fallback ?: home_url('/');
    }

    if (function_exists('pll_get_post')) {
        $translated_id = pll_get_post($page->ID, $lang);
        if ($translated_id) {
            return (string) get_permalink($translated_id);
        }
    }

    return (string) get_permalink($page);
}

/**
 * Resolve the translated blog index URL when Polylang is active.
 */
function developer_theme_posts_page_url(string $lang = ''): string
{
    if ('' === $lang) {
        $lang = developer_theme_current_language();
    }

    return developer_theme_page_url('blog', $lang, get_permalink(get_option('page_for_posts')) ?: home_url('/'));
}

add_action('pre_get_posts', 'developer_theme_portfolio_order');
function developer_theme_portfolio_order($query)
{
    if (! is_admin() && $query->is_main_query() && $query->is_post_type_archive('portfolio')) {
        $query->set('orderby', 'menu_order');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
}
