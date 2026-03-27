<?php
/**
 * WordPress Customizer registration — bulk settings from config.
 */

add_action( 'customize_register', 'developer_theme_customize_register' );
function developer_theme_customize_register( $wp_customize ) {
	$config    = developer_theme_customizer_config();
	$languages = array( 'en', 'ru', 'uk', 'es' );

	$lang_names = array(
		'en' => 'English',
		'ru' => 'Русский',
		'uk' => 'Українська',
		'es' => 'Español',
	);

	// --- Panel: Shared Settings ---
	$wp_customize->add_panel( 'dt_shared_panel', array(
		'title'    => 'Shared Settings',
		'priority' => 30,
	) );

	$wp_customize->add_section( 'dt_shared_identity', array(
		'title' => 'Identity & Links',
		'panel' => 'dt_shared_panel',
	) );

	foreach ( $config['shared'] as $key => $default ) {
		$setting_id = 'dt_shared_' . $key;
		$is_url     = str_contains( $key, 'url' );
		$wp_customize->add_setting( $setting_id, array(
			'default'           => $default,
			'sanitize_callback' => $is_url ? 'esc_url_raw' : 'sanitize_text_field',
		) );
		$wp_customize->add_control( $setting_id, array(
			'label'   => ucwords( str_replace( '_', ' ', $key ) ),
			'section' => 'dt_shared_identity',
			'type'    => 'text',
		) );
	}

	// --- Panel per language ---
	foreach ( $languages as $lang ) {
		$panel_id = 'dt_' . $lang . '_panel';
		$wp_customize->add_panel( $panel_id, array(
			'title'    => $lang_names[ $lang ] . ' — Content',
			'priority' => 31,
		) );

		// Navigation section.
		$nav_section = 'dt_' . $lang . '_nav';
		$wp_customize->add_section( $nav_section, array(
			'title' => 'Navigation & Footer',
			'panel' => $panel_id,
		) );
		foreach ( ( $config['nav'][ $lang ] ?? array() ) as $key => $default ) {
			$setting_id = 'dt_' . $lang . '_nav_' . $key;
			developer_theme_register_text_control( $wp_customize, $setting_id, $default, $nav_section, $key );
		}

		// Template sections.
		$template_labels = array(
			'front'             => 'Front Page',
			'home'              => 'Blog Index',
			'archive_portfolio' => 'Portfolio Archive',
			'contact'           => 'Contact Page',
			'single'            => 'Single Post',
			'archive'           => 'Archive',
		);

		foreach ( $template_labels as $tpl => $tpl_label ) {
			if ( empty( $config['templates'][ $tpl ][ $lang ] ) ) {
				continue;
			}

			$section_id = 'dt_' . $lang . '_' . $tpl;
			$wp_customize->add_section( $section_id, array(
				'title' => $tpl_label,
				'panel' => $panel_id,
			) );

			foreach ( $config['templates'][ $tpl ][ $lang ] as $key => $default ) {
				$setting_id = 'dt_' . $lang . '_' . $tpl . '_' . $key;
				developer_theme_register_text_control( $wp_customize, $setting_id, $default, $section_id, $key );
			}
		}
	}
}

/**
 * Register a single text setting + control.
 */
function developer_theme_register_text_control( $wp_customize, $id, $default, $section, $label_key ) {
	$is_array = is_array( $default );
	$is_long  = ! $is_array && mb_strlen( (string) $default ) > 80;

	if ( $is_array ) {
		$wp_customize->add_setting( $id, array(
			'default'           => wp_json_encode( $default, JSON_UNESCAPED_UNICODE ),
			'sanitize_callback' => 'developer_theme_sanitize_json_array',
		) );
		$wp_customize->add_control( $id, array(
			'label'       => ucwords( str_replace( '_', ' ', $label_key ) ),
			'description' => 'JSON array. One item per line in JSON format.',
			'section'     => $section,
			'type'        => 'textarea',
		) );
	} elseif ( $is_long ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $default,
			'sanitize_callback' => 'wp_kses_post',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => ucwords( str_replace( '_', ' ', $label_key ) ),
			'section' => $section,
			'type'    => 'textarea',
		) );
	} else {
		$wp_customize->add_setting( $id, array(
			'default'           => $default,
			'sanitize_callback' => 'wp_kses_post',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => ucwords( str_replace( '_', ' ', $label_key ) ),
			'section' => $section,
			'type'    => 'text',
		) );
	}
}

/**
 * Sanitize a JSON array value.
 */
function developer_theme_sanitize_json_array( $input ) {
	$decoded = json_decode( $input, true );
	if ( is_array( $decoded ) ) {
		return wp_json_encode( array_map( 'wp_kses_post', $decoded ), JSON_UNESCAPED_UNICODE );
	}
	$lines = array_filter( array_map( 'trim', explode( "\n", $input ) ) );
	return wp_json_encode( array_map( 'wp_kses_post', $lines ), JSON_UNESCAPED_UNICODE );
}
