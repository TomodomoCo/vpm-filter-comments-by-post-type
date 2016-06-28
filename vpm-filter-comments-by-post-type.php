<?php
/*
Plugin Name: Filter Comments by Post Type
Plugin URI: https://www.vanpattenmedia.com
Description: Display comments for a specific post type in the WordPress admin area
Author: Van Patten Media Inc.
Author URI: https://www.vanpattenmedia.com/
Version: 1.0
*/

class Filter_Comments_by_Post_Type {

	public function __construct() {
		add_action( 'restrict_manage_comments', array( $this, 'build_filter_dropdown' ) );
	}

	/**
	 * Get an array of post types that support comments
	 *
	 * @return array
	 */
	public function get_supported_post_types() {
		$post_types = get_post_types( [], 'objects' );

		$filtered_post_types['any'] = __( 'All post types' );

		foreach ( $post_types as $slug => $type ) {
			if ( ! post_type_supports( $slug, 'comments' ) )
				continue;

			$filtered_post_types[ $slug ] = $type->labels->name;
		}

		return apply_filters( 'vpm_comments_post_types', $filtered_post_types );
	}

	/**
	 * Build the post type filter dropdown
	 *
	 * @return void
	 */
	public function build_filter_dropdown() {
		// The currently selected/requested post type
		$current_post_type = isset( $_REQUEST['post_type'] ) ? sanitize_key( $_REQUEST['post_type'] ) : '';

		// Get the supported post types
		$post_types = $this->get_supported_post_types();

		echo '<label class="screen-reader-text" for="filter-by-post-type">' . __( 'Filter by post type' ) . '</label>';
		echo '<select id="filter-by-post-type" name="post_type">';

			foreach ( $post_types as $type => $label )
				echo "\t" . '<option value="' . esc_attr( $type ) . '"' . selected( $current_post_type, $type, false ) . ">$label</option>\n";

		echo '</select>';
	}

}

new Filter_Comments_by_Post_Type;
