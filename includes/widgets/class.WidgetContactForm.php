<?php
/**
 * WidgetContactForm.
 *
 * Displays WPrequal widget in widget areas.
 *
 * @since 1.1
 */

namespace WPrequal;
use WP_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}
 
class WidgetContactForm extends WP_Widget {
	
	/** constructor */
	public function __construct() {
		parent::__construct(
			'wprequal_contact_form',
			__( 'WPrequal Contact Form', 'wprequal' ),
			[ 'description' => __( 'Add a contact form', 'wprequal' ), ]
		);
	}
	
	/** @see WP_Widget::widget */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		if ( ! empty( $instance['content'] ) ) {
			echo "<div>{$instance['content']}</div>";
		}

		$post_id = isset( $instance['post_id'] ) ? $instance['post_id'] : FALSE;

		$form_args = [
			'contact_form_id' => (int) $post_id,
			'type'            => 'contact'
		];

		do_action( 'wprequal_contact_form', $form_args );

		echo $args['after_widget'];

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$post_id = isset( $instance['post_id'] ) ? $instance['post_id'] : '';

		view( 'contact/widget', 'contact-form', [
			'title'        => ! empty( $instance['title'] )   ? $instance['title']   : '',
			'content'      => ! empty( $instance['content'] ) ? $instance['content'] : '',
			'title_id'     => $this->get_field_id( 'title' ),
			'title_name'   => $this->get_field_name( 'title' ),
			'content_id'   => $this->get_field_id( 'content' ),
			'content_name' => $this->get_field_name( 'content' ),
			'args'         => [
				'post_type'        => 'wpq_contact_form',
				'name'             => $this->get_field_name( 'post_id' ),
				'class'            => 'post_id widefat',
				'id'               => 'post_id',
				'selected'         => $post_id,
				'show_option_none' => __('Select One')
			]
		] );

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = [];
		$instance['title']   = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['content'] = ( ! empty( $new_instance['content'] ) ) ? wp_kses_post( $new_instance['content'] ) : '';
		$instance['post_id'] = intval( $new_instance['post_id'] );

		return $instance;
	}

}