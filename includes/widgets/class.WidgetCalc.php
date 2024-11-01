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
 
class WidgetCalc extends WP_Widget {

	public function __construct(){

		parent::__construct(
			'wprequal_calc',
			__( 'WPrequal Calculator', 'wprequal' ), 
			array(
			'description' => __( 'Display a Mortgage Calculator.', 'wprequal' )
		));
		
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

		$template = ( isset( $instance['template'] ) && 'focus' === $instance['template'] )  ? 'focus' : 'calc';
		$size     = ( isset( $instance['size'] )     && 'small'     === $instance['size'] )  ? 'small'     : '';
		$shade    = ( isset( $instance['shade'] )    && 'dark'      === $instance['shade'] ) ? 'dark'      : '';

		echo $args['before_widget'];
		do_action( 'wprequal_calc', $template, $size, $shade );
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

		$template = ( isset( $instance['template'] ) && 'focus' === $instance['template'] ) ? 'checked' : '';
		$size     = ( isset( $instance['size'] )     && 'small' === $instance['size'] )     ? 'checked' : '';
		$shade    = ( isset( $instance['shade'] )    && 'dark'  === $instance['shade'] )    ? 'checked' : ''; ?>

		<p>
			<input
				id="size"
				name="<?php echo $this->get_field_name( 'template' ); ?>"
				type="checkbox" value="focus" <?php esc_attr_e( $template ); ?>
			>
			<label for="size"><?php _e( 'Focus Template', 'wprequal' ); ?></label>
		</p>

		<p>
			<input
				id="size"
				name="<?php echo $this->get_field_name( 'size' ); ?>"
				type="checkbox" value="small" <?php esc_attr_e( $size ); ?>
			>
			<label for="size"><?php _e( 'Small Calculator', 'wprequal' ); ?></label>
		</p>

		<p>
			<input
				id="shade"
				name="<?php echo $this->get_field_name( 'shade' ); ?>"
				type="checkbox" value="dark" <?php esc_attr_e( $shade ); ?>
			>
			<label for="shade"><?php _e( 'Dark Background', 'wprequal' ); ?></label>
		</p><?php

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

		$instance          = array();
		$instance['template'] = ( 'focus' === $new_instance['template'] ) ? 'focus' : '';
		$instance['size']     = ( 'small' === $new_instance['size'] )     ? 'small' : '';
		$instance['shade']    = ( 'dark'  === $new_instance['shade'] )    ? 'dark'  : '';

		return $instance;

	}

}