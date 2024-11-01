<?php
/**
 * WidgetCalcButton
 *
 * @since 4.0.0
 */

namespace WPrequal;
use WP_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}
 
class WidgetCalcButton extends WP_Widget {

	public function __construct(){

		parent::__construct(
			'wprequal_calc_button',
			__( 'WPrequal Calculator Button', 'wprequal' ), 
			array( 'description' => __( 'Display a Mortgage Calculator Pop Up Button.', 'wprequal' ) )
		);
		
	}
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		do_action( 'wprequal_calc_button' );
		echo $args['after_widget'];
	}
}