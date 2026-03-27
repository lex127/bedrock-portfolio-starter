<?php
/**
 * Plugin Name: Portfolio Post Type
 * Description: Registers the portfolio custom post type and taxonomies.
 */

add_action( 'init', 'developer_register_portfolio_post_type' );
function developer_register_portfolio_post_type() {
	register_post_type(
		'portfolio',
		array(
			'labels'              => array(
				'name'               => 'Portfolios',
				'singular_name'      => 'Portfolio',
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New Portfolio',
				'edit_item'          => 'Edit Portfolio',
				'new_item'           => 'New Portfolio',
				'view_item'          => 'View Portfolio',
				'search_items'       => 'Search Portfolios',
				'not_found'          => 'No Portfolios found',
				'not_found_in_trash' => 'No Portfolios found in Trash',
			),
			'public'              => true,
			'has_archive'         => true,
			'rewrite'             => array( 'slug' => 'portfolio' ),
			'supports'            => array( 'title', 'editor', 'thumbnail', 'comments', 'excerpt', 'revisions', 'custom-fields' ),
			'show_in_rest'        => true,
			'show_in_nav_menus'   => true,
			'exclude_from_search' => false,
			'can_export'          => true,
			'menu_icon'           => 'dashicons-portfolio',
		)
	);

	register_taxonomy(
		'portfolio_cat',
		'portfolio',
		array(
			'hierarchical'      => true,
			'show_in_nav_menus' => true,
			'labels'            => array(
				'name'              => 'Portfolio Categories',
				'singular_name'     => 'Portfolio Category',
				'search_items'      => 'Search Portfolio Categories',
				'all_items'         => 'All Portfolio Categories',
				'parent_item'       => 'Parent Portfolio Category',
				'parent_item_colon' => 'Parent Portfolio Category:',
				'edit_item'         => 'Edit Portfolio Category',
				'update_item'       => 'Update Portfolio Category',
				'add_new_item'      => 'Add New Portfolio Category',
				'new_item_name'     => 'New Portfolio Category Name',
				'menu_name'         => 'Portfolio Categories',
			),
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'portfolio_cat' ),
			'show_in_rest'      => true,
		)
	);

	register_taxonomy(
		'portfolio_skills',
		'portfolio',
		array(
			'hierarchical'      => false,
			'show_in_nav_menus' => true,
			'labels'            => array(
				'name'              => 'Portfolio Skills',
				'singular_name'     => 'Portfolio Skill',
				'search_items'      => 'Search Portfolio Skills',
				'all_items'         => 'All Portfolio Skills',
				'parent_item'       => 'Parent Portfolio Skill',
				'parent_item_colon' => 'Parent Portfolio Skill:',
				'edit_item'         => 'Edit Portfolio Skill',
				'update_item'       => 'Update Portfolio Skill',
				'add_new_item'      => 'Add New Portfolio Skill',
				'new_item_name'     => 'New Portfolio Skill Name',
				'menu_name'         => 'Portfolio Skills',
			),
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'portfolio_skill' ),
			'show_in_rest'      => true,
		)
	);
}
