<?php

/* Block direct access */
defined( 'ABSPATH' ) || exit();

/**
 * Class Post_Types
 *
 * Register Custom post types and taxonomies
 *
 * @package WP_Portfolio_Showcase
 *
 * @since 0.0.1
 */
class WP_Portfolio_Showcase_CPT {

	/**
	 * Post_Types constructor.
	 */
	function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'flush_rewrite_rules' ), 99 );
	}

	/**
	 * register custom post types
	 *
	 * @since 0.0.1
	 */
	function register_post_types() {
		register_post_type( 'portfolio', array(
			'labels'              => $this->get_posts_labels( 'Portfolio', 'Portfolio', 'Portfolio' ),
			'hierarchical'        => false, //Hierarchical causes memory issues - WP Loads all records
			'supports'            => apply_filters( 'wp_portfolio_showcase_post_supports', array( 'title', 'editor', 'thumbnail' ) ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-portfolio',
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => false,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => array( 'slug' => apply_filters( 'wp_portfolio_showcase_slug', 'portfolio' ) ),
			'capability_type'     => 'post',
			//'show_in_rest'        => true,
		) );
	}

	/**
	 * Register custom taxonomies
	 *
	 * @since 0.0.1
	 */
	public function register_taxonomies() {
		register_taxonomy( 'portfolio_category', array( 'portfolio' ), array(
			'hierarchical'      => true,
			'labels'            => $this->get_taxonomy_label( 'Portfolio Categories','Category', 'Categories' ),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => apply_filters( 'wp_portfolio_showcase_category_slug', [ 'slug' => 'portfolio-category' ] ),
		) );

		/*
		 * TODO: implement Portfolio Tags
		register_taxonomy( 'portfolio_tag', array( 'portfolio' ), array(
			'hierarchical'      => false,
			'labels'            => $this->get_taxonomy_label( 'Portfolio Tags', 'Tag', 'Tags' ),
			'show_ui'           => true,
			'show_admin_column' => true,
			'rewrite'           => apply_filters( 'wp_portfolio_showcase_tag_slug', [ 'slug' => 'portfolio-tag' ] ),
			'query_var'         => true,
		) );
		*
		*/

	}

	/**
	 * Get all labels from post types
	 *
	 * @param $menu_name
	 * @param $singular
	 * @param $plural
	 *
	 * @return array
	 * @since 0.0.1
	 */
	protected static function get_posts_labels( $menu_name, $singular, $plural, $type = 'plural' ) {
		$labels = array(
			'name'               => 'plural' == $type ? $plural : $singular,
			'all_items'          => sprintf( __( "All %s", 'wp-portfolio-showcase' ), $plural ),
			'singular_name'      => $singular,
			'add_new'            => sprintf( __( 'Add New %s', 'wp-portfolio-showcase' ), $singular ),
			'add_new_item'       => sprintf( __( 'Add New %s', 'wp-portfolio-showcase' ), $singular ),
			'edit_item'          => sprintf( __( 'Edit %s', 'wp-portfolio-showcase' ), $singular ),
			'new_item'           => sprintf( __( 'New %s', 'wp-portfolio-showcase' ), $singular ),
			'view_item'          => sprintf( __( 'View %s', 'wp-portfolio-showcase' ), $singular ),
			'search_items'       => sprintf( __( 'Search %s', 'wp-portfolio-showcase' ), $plural ),
			'not_found'          => sprintf( __( 'No %s found', 'wp-portfolio-showcase' ), $plural ),
			'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'wp-portfolio-showcase' ), $plural ),
			'parent_item_colon'  => sprintf( __( 'Parent %s:', 'wp-portfolio-showcase' ), $singular ),
			'menu_name'          => $menu_name,
		);

		return $labels;
	}


	/**
	 * Get all labels from taxonomies
	 *
	 * @param $menu_name
	 * @param $singular
	 * @param $plural
	 *
	 * @return array
	 * @since 0.0.1
	 */
	protected static function get_taxonomy_label( $menu_name, $singular, $plural ) {
		$labels = array(
			'name'              => sprintf( _x( '%s', 'taxonomy general name', 'wp-portfolio-showcase' ), $plural ),
			'singular_name'     => sprintf( _x( '%s', 'taxonomy singular name', 'wp-portfolio-showcase' ), $singular ),
			'search_items'      => sprintf( __( 'Search %', 'wp-portfolio-showcase' ), $plural ),
			'all_items'         => sprintf( __( 'All %s', 'wp-portfolio-showcase' ), $plural ),
			'parent_item'       => sprintf( __( 'Parent %s', 'wp-portfolio-showcase' ), $singular ),
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'wp-portfolio-showcase' ), $singular ),
			'edit_item'         => sprintf( __( 'Edit %s', 'wp-portfolio-showcase' ), $singular ),
			'update_item'       => sprintf( __( 'Update %s', 'wp-portfolio-showcase' ), $singular ),
			'add_new_item'      => sprintf( __( 'Add New %s', 'wp-portfolio-showcase' ), $singular ),
			'new_item_name'     => sprintf( __( 'New % Name', 'wp-portfolio-showcase' ), $singular ),
			'menu_name'         => __( $menu_name, 'wp-portfolio-showcase' ),
		);

		return $labels;
	}

	/**
	 * Flash The Rewrite Rules
	 *
	 * @since 2.0.2
	 */
	function flush_rewrite_rules() {
		if ( get_option( 'wp_portfolio_showcase_flush_rewrite_rules' ) ) {
			flush_rewrite_rules();
			delete_option( 'wp_portfolio_showcase_flush_rewrite_rules' );
		}
	}
}

new WP_Portfolio_Showcase_CPT;

