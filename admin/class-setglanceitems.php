<?php
/**
 * Set glance items
 *
 * @package 19h47/set-glance-items
 */

/**
 * Set glance items class
 */
class SetGlanceItems {
	/**
	 * Taxonomies
	 *
	 * @var array
	 */
	private $taxonomies;

	/**
	 * Post types
	 *
	 * @var array
	 */
	private $post_types;

	/**
	 * Construct
	 *
	 * @param array $taxonomies Array of custom taxonomies.
	 * @param array $post_types Array of custom post types.
	 *
	 * @access public
	 */
	public function __construct( array $taxonomies, array $post_types ) {
		$this->taxonomies = $taxonomies;
		$this->post_types = $post_types;

		add_filter( 'dashboard_glance_items', array( $this, 'set_a_glance_taxonomies' ), 10, 1 );
		add_filter( 'dashboard_glance_items', array( $this, 'set_a_glance_posts' ), 10, 1 );

		add_action( 'admin_enqueue_scripts', array( $this, 'css' ) );
	}

	/**
	 * Set a glance taxonomies
	 *
	 * @param array $items Array of extra 'At a Glance' widget items.
	 *
	 * @access public
	 * @return $items
	 */
	public function set_a_glance_taxonomies( array $items ) {
		$args = array(
			'public'   => true,
			'_builtin' => false,
		);

		$taxonomies = get_taxonomies( $args, 'objects', 'or' );

		foreach ( $taxonomies as $taxonomy ) {
			foreach ( $this->taxonomies as $custom_taxonomy ) {
				if ( $taxonomy->name === $custom_taxonomy['name'] ) {
					$num_terms = wp_count_terms( $taxonomy->name );
					$num       = number_format_i18n( $num_terms );
					$text      = _n(
						$taxonomy->labels->singular_name, // phpcs:ignore
						$taxonomy->labels->name, // phpcs:ignore
						intval( $num_terms )
					);

					if ( current_user_can( 'edit_posts' ) ) {
						$output  = '<a class="taxonomy-count ' . $taxonomy->name . '-count" ';
						$output .= 'href="edit-tags.php?taxonomy=' . $taxonomy->name . '">';
						$output .= $num . ' ' . $text;
						$output .= '</a>';
					} else {
						$output  = '<span class="taxonomy-count ' . $post_type->name . '-count">';
						$output .= $num . ' ' . $text;
						$output .= '</span>';
					}

					$items[] = $output;
				}
			}
		}

		return $items;
	}


	/**
	 * Set a glance posts
	 *
	 * @param array $items $items Array of extra 'At a Glance' widget items.
	 *
	 * @return $items
	 */
	public function set_a_glance_posts( array $items ) {
		$args     = array(
			'public'   => true,
			'_builtin' => false,
		);
		$output   = 'object';
		$operator = 'and';

		$post_types = get_post_types( $args, $output, $operator );

		foreach ( $post_types as $post_type ) {
			foreach ( $this->post_types as $custom_post_type ) {
				if ( $post_type->name === $custom_post_type['name'] ) {
					$num_posts = wp_count_posts( $post_type->name );
					$num       = number_format_i18n( $num_posts->publish );
					$text      = _n(
						$post_type->labels->singular_name, // phpcs:ignore
						$post_type->labels->name, // phpcs:ignore
						intval( $num_posts->publish )
					);

					if ( current_user_can( 'edit_posts' ) ) {
						$output  = '<a class="post-count ' . $post_type->name . '-count" ';
						$output .= 'href="edit.php?post_type=' . $post_type->name . '">';
						$output .= $num . ' ' . $text;
						$output .= '</a>';
					} else {
						$output  = '<span class="post-count ' . $post_type->name . '-count">';
						$output .= $num . ' ' . $text;
						$output .= '</span>';
					}

					$items[] = $output;
				}
			}
		}

		return $items;
	}

	/**
	 * CSS
	 *
	 * @return void
	 */
	public function css() {
		$css = null;

		wp_register_style( 'dashboard-right-now', false, array(), '1.0.0' );
		wp_enqueue_style( 'dashboard-right-now' );

		foreach ( $this->taxonomies as $taxonomy ) {
			$css .= "#dashboard_right_now .{$taxonomy['name']}-count::before { content: \"{$taxonomy['code']}\"; }";
		}

		foreach ( $this->post_types as $post_type ) {
			$css .= "#dashboard_right_now .{$post_type['name']}-count::before { content: \"{$post_type['code']}\"; }";
		}

		wp_add_inline_style( 'dashboard-right-now', $css );
	}
}
