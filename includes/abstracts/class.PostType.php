<?php
/**
 * Post Type
 *
 * Handle post type
 *
 * @since   6.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

abstract class PostType {

	/**
	 * @var bool
	 */
	protected $show_in_menu = TRUE;

	/**
	 * @var array
	 */

	private $capabilities;

	/**
	 * PostType constructor.
	 */

	public function __construct() {
		$this->capabilities = $this->capabilities();
	}

	/**
	 * Register a post type.
	 */
	public function register_post_type() {

		register_post_type( $this->post_type,
			array(
				'labels'                => $this->labels(),
				'show_in_menu'			=> $this->show_in_menu,
				'menu_icon'             => $this->menu_icon,
				'menu_position'			=> 6,
				'public' 				=> TRUE,
				'has_archive' 			=> FALSE,
				'exclude_from_search'	=> FALSE,
				'publicly_queryable'	=> FALSE,
				'hierarchical' 			=> TRUE,
				'show_in_nav_menus'		=> FALSE,
				'show_in_admin_bar'		=> FALSE,
				'supports'				=> $this->supports,
				'capabilities' 			=> $this->capabilities
			)
		);

	}

	/**
	 * Post type labels.
	 * @return array
	 */
	public function labels() {

		return array(
			 'name' 		 => __( $this->label_names ),
			 'singular_name' => __( $this->label_name ),
			 'add_new_item'	 => __( "Add New {$this->label_name}" ),
			 'edit_item'	 => __( "Edit {$this->label_name}" ),
			 'new_item'		 => __( "New {$this->label_name}" ),
			 'view_item'	 => __( "View {$this->label_name}" ),
			 'view_items'	 => __( "View {$this->label_names}" ),
			 'search_items'	 => __( "Search {$this->label_names}" ),
			 'all_items'	 => __( $this->label_names ),
			 'attributes'	 => __( "{$this->label_name} Attributes" ),
			 'menu_name'	 => __( $this->menu_label ),
		 );

	}

	/**
	 * Register a taxonomy.
	 */
	public function register_taxonomies() {

		foreach ( $this->taxonomies as $taxonomy => $values ) {

			$args = array(
				'hierarchical'      => TRUE,
				'labels'            => $this->taxonomy_labels( $values ),
				'show_ui'           => TRUE,
				'show_in_menu'		=> FALSE,
				'show_admin_column' => TRUE,
				'show_in_nav_menus'	=> FALSE,
				'query_var'         => FALSE,
				'public' 			=> TRUE,
				'show_tagcloud'		=> FALSE,
				'rewrite' 			=> FALSE,
				'capabilities'      => $values['capabilities']
			);

			register_taxonomy( $taxonomy, $this->post_type, $args );
		}

	}

	/**
	 * Taxonomy labels.
	 *
	 * @param $values
	 *
	 * @return array
	 */
	public function taxonomy_labels( $values ) {

		$label  = $values['label'];
		$labels = $values['labels'];

		return array(
			'name'              => _x( $labels, $labels, 'wprequal' ),
			'singular_name'     => _x( $label, $label, 'wprequal' ),
			'search_items'      => __( "Search {$labels}", 'wprequal' ),
			'all_items'         => __( "All {$labels}", 'wprequal' ),
			'parent_item'       => __( "Parent {$label}", 'wprequal' ),
			'parent_item_colon' => __( "Parent {$label}:", 'wprequal' ),
			'edit_item'         => __( "Edit {$label}", 'wprequal' ),
			'update_item'       => __( "Update {$label}", 'wprequal' ),
			'add_new_item'      => __( "Add New {$label}", 'wprequal' ),
			'new_item_name'     => __( "New {$label} Name", 'wprequal' ),
			'menu_name'         => __( $label, 'wprequal' ),
		);

	}

	/**
	 * Capabilities.
	 *
	 * @return array
	 */

	public function capabilities() {

		$caps =  array(
			'edit_post'          	=> WPREQUAL_CAP,
			'read_post'          	=> WPREQUAL_CAP,
			'delete_post'        	=> WPREQUAL_CAP,
			'delete_posts'        	=> WPREQUAL_CAP,
			'edit_posts'         	=> WPREQUAL_CAP,
			'edit_others_posts'  	=> WPREQUAL_CAP,
			'delete_others_post'	=> WPREQUAL_CAP,
			'delete_others_posts'	=> WPREQUAL_CAP,
			'read_private_posts' 	=> WPREQUAL_CAP,
			'publish_posts'      	=> WPREQUAL_CAP,
			'create_posts'       	=> WPREQUAL_CAP
		);

		if ( ! Core::status( 1 ) && 'wpq_contact_form' !== $this->post_type ) {

			$caps['publish_posts'] = 'do_not_allow';
			$caps['create_posts']  = 'do_not_allow';

			if ( 'wpq_survey_form' === $this->post_type ) {
				$caps['delete_post']         = 'do_not_allow';
				$caps['delete_posts']        = 'do_not_allow';
				$caps['delete_others_post']  = 'do_not_allow';
				$caps['delete_others_posts'] = 'do_not_allow';
			}

		}

		return $caps;

	}

	/**
	 *	Replace Submit Metabox
	 *
	 *	Replace the submit metabox.
	 *
	 *	@since 5.0
	 */

	public function replace_submit_meta_box() {

		remove_meta_box( 'submitdiv', '', 'core' );

		add_meta_box(
			'submitdiv',
			__( 'Save/Update', 'wprequal' ),
			array( $this, 'submit_meta_box' ),
			$this->post_type,
			'side',
			'high'
		);

	}

	/**
	 *	Submit Metabox
	 *
	 *	Content for the submit metabox for save this and contacts.
	 *
	 *	@since 5.0
	 */

	public function submit_meta_box() {

		global $action, $post;

		$post_type_object = get_post_type_object( $this->post_type );
		$can_publish 	  = current_user_can( $post_type_object->cap->publish_posts );
		$can_delete 	  = current_user_can( $post_type_object->cap->delete_post );

		view( 'admin', 'submit-metabox', [
			'post_id'     => $post->ID,
			'post_status' => $post->post_status,
			'can_publish' => $can_publish,
			'can_delete'  => $can_delete,
			'label_name'  => $this->label_name
		]);

	}

	/**
	 *	Custom Bulk Actions
	 *
	 *  Customize the bulk actions dropdown in wp admin screen.
	 *
	 *	@since 5.0
	 *
	 *  @param  array $actions The array of actions provided by WP and other plugins.
	 *  @return array $actions The custom array of actions we provided.
	 */

	public function custom_bulk_actions( $actions ) {

		unset( $actions );

		$actions['edit']   = __( 'Edit', 'edit' );
		$actions['delete'] = __( 'Delete Permanently', 'delete' );

		if ( ! Core::status( 1 ) && 'wpq_survey_form' === $this->post_type ) {
			unset( $actions['delete'] );
		}

		return $actions;

	}

	/**
	 *	Modify List Row Actions
	 *
	 *	Modify the list row for a post type.
	 *
	 *	@since 5.0
	 *
	 *  @param  array  $actions The default actions.
	 *  @param  object $post    The post object.
	 *  @return array  $actions The modified actions.
	 */

	public function modify_list_row_actions( $actions, $post ) {

		global $typenow;

		// Check for your post type.
		if ( $typenow === $this->post_type ) {

			unset( $actions['inline hide-if-no-js'] );
			unset( $actions['trash'] );

		}

		return $actions;

	}

}