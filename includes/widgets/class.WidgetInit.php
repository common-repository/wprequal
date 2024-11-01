<?php
/**
 * WidgetInit.
 *
 * Init Widgets for plugin
 *
 * @since 6.2.14
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}
 
class WidgetInit {

	/**
	 * @var WidgetInit
	 */

	public static $instance;

	/**
	 * WidgetInit constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Instance.
	 *
	 * @return WidgetInit
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Widgets Init
	 *
	 * Init the widgets.
	 *
	 * @since 5.1
	 */

	public function widgets_init() {

		$widgets = array(
			'WPrequal\WidgetCalcButton'       => array( 'mortgage', 'real_estate' ),
			'WPrequal\WidgetCalc'             => array( 'mortgage', 'real_estate' ),
			'WPrequal\WidgetSurveyFormButton' => array( 'mortgage', 'real_estate' ),
			'WPrequal\WidgetSurveyForm'       => FALSE,
			'WPrequal\WidgetContactForm'      => FALSE,
			'WPrequal\WidgetRegisterForm'     => FALSE,
			'WPrequal\WidgetAmortize'         => array( 'mortgage', 'real_estate' )
		);

		foreach ( $widgets as $widget => $extends ) {

			if ( Core::extend( $extends ) ) {
				register_widget( $widget );
			}

		}

	}

}

/**
 * Register WPrequal_Widget.
 *
 * @since 1.0
 */

add_action( 'widgets_init', function() {
	$init = WidgetInit::instance();
	$init->widgets_init();
} );