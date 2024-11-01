<?php
/**
 * RegisterForm
 *
 * @since 7.3
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class RegisterForm extends PostType {

	/**
	 * @var string
	 */

	protected $post_type = 'wpq_register_form';

	/**
	 * @var string
	 */

	protected $label_name = 'Register Form';

	/**
	 * @var string
	 */

	protected $label_names = 'Register Forms';

	/**
	 * @var string
	 */

	protected $menu_label = 'Register Forms';

	/**
	 * @var string
	 */

	protected $menu_icon = FALSE;

	/**
	 * @var array
	 */

	protected $supports = array( 'title' );

	/**
	 * @var bool
	 */

	protected $show_in_menu = WPREQUAL_OPTIONS;

	/**
	 * @var array
	 */

	protected $taxonomies = array();

	/**
	 * @var RegisterForm
	 */

	public static $instance;

	/**
	 * RegisterForm constructor.
	 */

	public function __construct() {

		parent::__construct();

		self::$instance = $this;

	}

	/**
	 * Instance.
	 *
	 * @return RegisterForm
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Actions
	 *
	 * @since 7.0
	 */

	public function actions() {

		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'wprequal_register_form', array( $this, 'form' ), 10, 2 );

		add_shortcode( 'wprequal_register_form', array( $this, 'register_form' ) );

	}

	/**
	 * Get the input for the form.
	 *
	 * @param $post_id
	 *
	 * @return array|mixed
	 */

	public static function get_input( $post_id ) {

		$meta = FALSE;

		if ( is_int( $post_id ) && 0 < $post_id ) {
			$meta = get_post_meta( $post_id, 'input', TRUE );
		}

		return ( ! empty( $meta ) ) ? $meta : array(
			'type'        => 'email',
			'key'         => 'email',
			'label'       => 'Email',
			'required'    => 'checked',
			'email_mask'  => 'yes',
			'placeholder' => ''
		);

	}

	/**
	 * Get the contact form view.
	 *
	 * @param        $contact_form_id
	 * @param string $type
	 */

	public function form( $contact_form_id, $type = 'register' ) {

		view( 'contact/form', 'register-form', [
			'contact_form_id' => $contact_form_id,
			'type'            => $type,
			'input'           => self::get_input( $contact_form_id ),
			'details'         => ContactForm::get_details( $contact_form_id )
		]);

	}

	/**
	 * Register Form
	 *
	 * The register form shortcode.
	 *
	 * @since 6.0
	 *
	 * @return string
	 */

	public function register_form( $atts ) {

		$atts = shortcode_atts( array(
			'post_id' => 0
		), $atts, 'wprequal_register_form' );

		ob_start();
		do_action( 'wprequal_register_form', (int) $atts['post_id'] );
		return ob_get_clean();

	}
	
}