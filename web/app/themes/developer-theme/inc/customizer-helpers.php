<?php
/**
 * Customizer helper functions for retrieving theme text.
 */

/**
 * Get a translated label with Customizer override support.
 *
 * @param string $key   The label key.
 * @param string $group Group: 'shared', 'nav', 'front', 'home', 'archive_portfolio', 'contact', 'single', 'archive'.
 * @param string $lang  Language slug. Auto-detected if empty.
 * @return string|array
 */
function dt_label( $key, $group = 'nav', $lang = '' ) {
	if ( empty( $lang ) ) {
		$lang = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : 'en';
	}

	$config = developer_theme_customizer_config();

	if ( 'shared' === $group ) {
		$mod_key = 'dt_shared_' . $key;
		$default = $config['shared'][ $key ] ?? '';
	} elseif ( 'nav' === $group ) {
		$mod_key = 'dt_' . $lang . '_nav_' . $key;
		$default = $config['nav'][ $lang ][ $key ] ?? ( $config['nav']['en'][ $key ] ?? '' );
	} else {
		$mod_key = 'dt_' . $lang . '_' . $group . '_' . $key;
		$default = $config['templates'][ $group ][ $lang ][ $key ]
				?? ( $config['templates'][ $group ]['en'][ $key ] ?? '' );
	}

	if ( is_array( $default ) ) {
		$raw = get_theme_mod( $mod_key, wp_json_encode( $default, JSON_UNESCAPED_UNICODE ) );
		if ( is_array( $raw ) ) {
			return $raw;
		}
		$decoded = json_decode( $raw, true );
		return is_array( $decoded ) ? $decoded : $default;
	}

	return get_theme_mod( $mod_key, $default );
}

/**
 * Get a shared (language-independent) setting.
 *
 * @param string $key Setting key.
 * @return string
 */
function dt_shared( $key ) {
	return dt_label( $key, 'shared' );
}
