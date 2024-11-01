<?php
/**
 * WidgetCalc
 *
 * @since 4.0.0
 */

namespace WPrequal;
use WP_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}
 
class WidgetAmortize extends WP_Widget {

	public function __construct(){

		parent::__construct(
			'wprequal_amortize',
			__( 'WPrequal Amortize', 'wprequal' ),
			array( 'description' => __( 'Display a Mortgage Amortization Calculator.', 'wprequal' ) )
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
		do_action( 'wprequal_amortize' );
		echo $args['after_widget'];

	}

}