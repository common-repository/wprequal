<?php
/**
 * WidgetRegisterForm.
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
 
class WidgetRegisterForm extends WP_Widget {
	
	/** constructor */
	public function __construct() {
		parent::__construct(
			'wprequal_register_form',
			__( 'WPrequal Register Form', 'wprequal' ),
			array( 'description' => __( 'Add a register form', 'wprequal' ), )
		);
	}
	
	/** @see WP_Widget::widget */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		if ( ! empty( $instance['content'] ) ) {
			printf( '%s',  "<div>{$instance['content']}</div>" );
		}

		$post_id = isset( $instance['post_id'] ) ? $instance['post_id'] : FALSE;

		do_action( 'wprequal_register_form', $post_id );

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

		view( 'contact/widget', 'register-form', [
			'title'        => ! empty( $instance['title'] )   ? $instance['title']   : '',
			'content'      => ! empty( $instance['content'] ) ? $instance['content'] : '',
			'title_id'     => $this->get_field_id( 'title' ),
			'content_id'   => $this->get_field_id( 'content' ),
			'title_name'   => $this->get_field_name( 'title' ),
			'content_name' => $this->get_field_name( 'content' ),
			'args'         => [
				'post_type'         => 'wpq_register_form',
				'name'              => $this->get_field_name( 'post_id' ),
				'class'             => 'post_id widefat',
				'id'                => 'post_id',
				'selected'          => $post_id,
				'show_option_none'  => __('Select One')
			]
		]);

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
		$instance = array();
		$instance['title']   = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['content'] = ( ! empty( $new_instance['content'] ) ) ? wp_kses_post( $new_instance['content'] ) : '';
		$instance['post_id'] = intval( $new_instance['post_id'] );

		return $instance;
	}

}