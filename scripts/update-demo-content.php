<?php

$ensure_page = static function (string $title, string $slug, string $lang, string $template = ''): int {
    $page = get_page_by_path($slug);

    if (! $page) {
        $page_id = wp_insert_post([
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_content' => '',
        ]);
    } else {
        $page_id = (int) $page->ID;
        wp_update_post([
            'ID'         => $page_id,
            'post_title' => $title,
            'post_name'  => $slug,
        ]);
    }

    if (function_exists('pll_set_post_language')) {
        pll_set_post_language($page_id, $lang);
    }

    if ($template !== '') {
        update_post_meta($page_id, '_wp_page_template', $template);
    }

    return $page_id;
};

$link_translations = static function (array $translations): void {
    if (function_exists('pll_save_post_translations')) {
        pll_save_post_translations($translations);
    }
};

update_option('blogname', 'Your Portfolio');
update_option('blogdescription', 'Neutral multilingual portfolio starter');

$front_en = 7;
$blog_en  = 9;

wp_update_post(['ID' => $front_en, 'post_title' => 'Home', 'post_name' => 'home']);
wp_update_post(['ID' => $blog_en, 'post_title' => 'Articles', 'post_name' => 'blog']);

if (function_exists('pll_set_post_language')) {
    pll_set_post_language($front_en, 'en');
    pll_set_post_language($blog_en, 'en');
}

$front_ru = $ensure_page('Главная', 'glavnaya', 'ru');
$front_uk = $ensure_page('Головна', 'golovna', 'uk');
$blog_ru  = $ensure_page('Статьи', 'stati', 'ru');
$blog_uk  = $ensure_page('Статті', 'statti', 'uk');

$contact_en = 19;
$contact_ru = 20;
$contact_uk = 16;
$about_en   = 21;
$about_ru   = 22;
$about_uk   = 17;

wp_update_post(['ID' => $contact_en, 'post_title' => 'Contact', 'post_name' => 'contact']);
wp_update_post(['ID' => $contact_ru, 'post_title' => 'Контакты', 'post_name' => 'kontakty']);
wp_update_post(['ID' => $contact_uk, 'post_title' => 'Контакти', 'post_name' => 'kontakty-uk']);
wp_update_post(['ID' => $about_en, 'post_title' => 'About', 'post_name' => 'about']);
wp_update_post(['ID' => $about_ru, 'post_title' => 'Обо мне', 'post_name' => 'obo-mne']);
wp_update_post(['ID' => $about_uk, 'post_title' => 'Про мене', 'post_name' => 'pro-mene']);

foreach ([[$contact_en, 'en'], [$contact_ru, 'ru'], [$contact_uk, 'uk'], [$about_en, 'en'], [$about_ru, 'ru'], [$about_uk, 'uk']] as [$page_id, $lang]) {
    if (function_exists('pll_set_post_language')) {
        pll_set_post_language($page_id, $lang);
    }
}

update_post_meta($contact_en, '_wp_page_template', 'page-contact.php');
update_post_meta($contact_ru, '_wp_page_template', 'page-contact.php');
update_post_meta($contact_uk, '_wp_page_template', 'page-contact.php');
update_post_meta($about_en, '_wp_page_template', 'page-about.php');
update_post_meta($about_ru, '_wp_page_template', 'page-about.php');
update_post_meta($about_uk, '_wp_page_template', 'page-about.php');

$link_translations(['en' => $front_en, 'ru' => $front_ru, 'uk' => $front_uk]);
$link_translations(['en' => $blog_en, 'ru' => $blog_ru, 'uk' => $blog_uk]);
$link_translations(['en' => $contact_en, 'ru' => $contact_ru, 'uk' => $contact_uk]);
$link_translations(['en' => $about_en, 'ru' => $about_ru, 'uk' => $about_uk]);

update_option('page_on_front', $front_en);
update_option('page_for_posts', $blog_en);
update_option('show_on_front', 'page');

$posts = [
    14 => [
        'title'   => 'Planning a Lean Portfolio Website',
        'excerpt' => 'A practical outline for structuring a small portfolio site so visitors quickly understand your services, work, and next step.',
        'content' => "<p>A small portfolio site works best when each page has a job. The home page should explain what you do and who you help. Case studies should prove it with concrete examples. The contact page should remove friction rather than add more questions.</p><p>This demo article exists to show how writing can support a portfolio starter. Replace it with your own notes, project breakdowns, lessons learned, or updates that reinforce your positioning.</p><h2>Keep the structure simple</h2><p>Start with a clear headline, a short summary, and one primary call to action. Then move into proof: selected work, process, or a small list of outcomes.</p><h2>Write for scanning</h2><p>Most visitors skim before they commit to reading. Use meaningful headings, short paragraphs, and examples that make your experience feel concrete.</p>",
    ],
    15 => [
        'title'   => 'A Launch Checklist for a Service Website',
        'excerpt' => 'Before publishing a service site, check the fundamentals: messaging, navigation, contact flow, and whether the pages actually support your offer.',
        'content' => "<p>Launching a service website is not just about pushing files live. The real question is whether a visitor can understand the offer, trust the examples, and know what to do next within a few seconds.</p><p>Use this sample post to outline your own process. It can be a checklist, a behind-the-scenes note, or a short article that helps future clients understand how you think.</p><h2>Check the essentials</h2><p>Review navigation, forms, sample content, mobile layouts, and the wording of your main calls to action. Remove anything that distracts from the next step.</p><h2>Leave room to evolve</h2><p>A starter site should not feel frozen. It should make routine edits easy so your positioning and proof can improve over time.</p>",
    ],
];

foreach ($posts as $post_id => $data) {
    wp_update_post([
        'ID'           => $post_id,
        'post_title'   => $data['title'],
        'post_excerpt' => $data['excerpt'],
        'post_content' => $data['content'],
    ]);

    if (function_exists('pll_set_post_language')) {
        pll_set_post_language($post_id, 'en');
    }
}

$portfolio = [
    11 => [
        'title'   => 'Service Website Refresh for a Small Studio',
        'excerpt' => 'A sample case study about clarifying positioning, reorganising pages, and creating a cleaner path from introduction to enquiry.',
        'content' => "<p>This sample case study shows how to frame a project in a concise, useful way. Start with the context, explain the challenge, and then show what changed.</p><p>For this example, the goal was to simplify a service website for a small studio. The work focused on page hierarchy, clearer messaging, and a better balance between proof and contact points.</p><h2>What changed</h2><p>The new structure reduced clutter, grouped related services, and introduced stronger calls to action. The result was a site that felt easier to scan and easier to maintain.</p>",
        'label'   => 'Website refresh',
        'client'  => 'Small Studio',
        'link'    => 'https://example.com',
    ],
    12 => [
        'title'   => 'Client Portal Concept for Recurring Services',
        'excerpt' => 'A generic project example focused on dashboards, recurring work, and a clearer client experience after signup.',
        'content' => "<p>This example represents a portal-style project where the main objective was clarity after purchase. The interface needed to show status, next actions, and key documents without overwhelming the user.</p><p>Use a case like this to explain the problem, the scope, and the operational improvements that followed.</p><h2>Why it matters</h2><p>Case studies become stronger when they connect interface decisions to business outcomes such as lower support load, faster onboarding, or simpler communication.</p>",
        'label'   => 'Portal design',
        'client'  => 'Subscription Business',
        'link'    => 'https://example.com',
    ],
    13 => [
        'title'   => 'Multilingual Content Hub for a Growing Team',
        'excerpt' => 'A sample multilingual project showing how one structure can support several languages without multiplying maintenance effort.',
        'content' => "<p>This project is here to demonstrate how a multilingual case study can be presented in the starter. The focus was on a shared structure, clear page templates, and content that could be extended language by language.</p><p>When writing your own case studies, explain the editorial or operational benefit as well as the design or build work.</p><h2>Result</h2><p>The final setup made publishing easier, reduced duplicated effort, and gave the team a more reliable foundation for future updates.</p>",
        'label'   => 'Multilingual site',
        'client'  => 'Content Team',
        'link'    => 'https://example.com',
    ],
];

foreach ($portfolio as $post_id => $data) {
    wp_update_post([
        'ID'           => $post_id,
        'post_title'   => $data['title'],
        'post_excerpt' => $data['excerpt'],
        'post_content' => $data['content'],
    ]);
    update_post_meta($post_id, 'portfolio_label', $data['label']);
    update_post_meta($post_id, 'portfolio_client', $data['client']);
    update_post_meta($post_id, 'portfolio_link', $data['link']);

    if (function_exists('pll_set_post_language')) {
        pll_set_post_language($post_id, 'en');
    }
}

wp_trash_post(1);
wp_trash_post(2);
wp_trash_post(3);

echo "Demo content updated.\n";
