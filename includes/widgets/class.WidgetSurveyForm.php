<?php
/**
 * WidgetForm.
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
 
class WidgetSurveyForm extends WP_Widget {
	
	/** constructor */
	public function __construct() {
		parent::__construct(
			'wprequal',
			__( 'WPrequal Survey Form', 'wprequal' ),
			array( 'description' => __( 'Add a mortgage survey form', 'wprequal' ), )
		);
	}
	
	/** @see WP_Widget::widget */
	public function widget( $args, $instance ) {

		$post_id = isset( $instance['post_id'] ) ? $instance['post_id'] : FALSE;

		echo $args['before_widget'];
		do_action( 'wprequal_survey_form', $post_id );
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

		view( 'survey/widget', 'form', [
			'args' => [
				'post_type' => 'wpq_survey_form',
				'name'      => $this->get_field_name( 'post_id' ),
				'class'     => 'post_id widefat',
				'id'        => 'post_id',
				'selected'  => $post_id
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

		$instance            = array();
		$instance['post_id'] = intval( $new_instance['post_id'] );

		return $instance;

	}

}
