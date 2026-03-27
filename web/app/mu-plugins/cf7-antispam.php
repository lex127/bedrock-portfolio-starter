<?php

/**
 * Plugin Name: CF7 Anti-Spam (Honeypot + Time Trap)
 * Description: Adds a hidden honeypot field and minimum submission time check to Contact Form 7 forms.
 */

declare(strict_types=1);

/**
 * Inject honeypot field and timestamp into every CF7 form.
 */
add_filter('wpcf7_form_elements', static function (string $html): string {
    $token = wp_create_nonce('cf7_antispam_ts');
    $ts    = base64_encode((string) time());

    // Hidden honeypot field (bots fill it, humans never see it)
    $honeypot = '<div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">'
        . '<label for="cf7as_website">Website</label>'
        . '<input type="text" name="cf7as_website" id="cf7as_website" value="" tabindex="-1" autocomplete="off">'
        . '</div>';

    // Hidden timestamp field (detects instant submissions)
    $timestamp = '<input type="hidden" name="cf7as_ts" value="' . esc_attr($ts) . '">'
        . '<input type="hidden" name="cf7as_tk" value="' . esc_attr($token) . '">';

    return $honeypot . $timestamp . $html;
}, 10, 1);

/**
 * Check honeypot and time trap on submission.
 */
add_filter('wpcf7_spam', static function (bool $spam): bool {
    if ($spam) {
        return $spam;
    }

    // Honeypot check: if the hidden field has any value, it's a bot.
    $honeypot = sanitize_text_field($_POST['cf7as_website'] ?? '');

    if ($honeypot !== '') {
        return true;
    }

    // Time trap: form submitted in under 3 seconds is almost certainly a bot.
    $ts    = $_POST['cf7as_ts'] ?? '';
    $token = $_POST['cf7as_tk'] ?? '';

    if ($ts && $token && wp_verify_nonce($token, 'cf7_antispam_ts')) {
        $submitted_at = (int) base64_decode($ts);
        $elapsed      = time() - $submitted_at;

        if ($elapsed < 3) {
            return true;
        }
    }

    return false;
}, 10, 1);
