<?php
/**
 * ContactForm
 *
 * @since 7.3
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class ContactForm extends PostType {

	/**
	 * @var string
	 */

	protected $post_type = 'wpq_contact_form';

	/**
	 * @var string
	 */

	protected $label_name = 'Contact Form';

	/**
	 * @var string
	 */

	protected $label_names = 'Contact Forms';

	/**
	 * @var string
	 */

	protected $menu_label = 'Contact Forms';

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
	 * @var ContactForm
	 */

	public static $instance;

	/**
	 * ContactForm constructor.
	 */

	public function __construct() {

		parent::__construct();

		self::$instance = $this;

	}

	/**
	 * Instance.
	 *
	 * @return ContactForm
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
		add_action( 'wp_enqueue_scripts', array( $this, 'localize_scripts' ) );
		add_action( 'wprequal_contact_form', array( $this, 'form' ), 10, 1 );

		add_shortcode( 'wprequal_contact_form', array( $this, 'contact_form' ) );

	}

	/**
	 * Localize Scripts
	 */

	public function localize_scripts() {

		wp_localize_script( 'wprequal_js', 'wpqContactForm', array(
			'emailMask' => $this->email_input_mask()
		));

	}

	/**
	 * Get the input for the form.
	 *
	 * @param $contact_form_id
	 *
	 * @return array|mixed
	 */

	public function get_inputs( $contact_form_id ) {

		if ( is_int( $contact_form_id ) && 0 < $contact_form_id ) {

			if ( $inputs = get_post_meta( $contact_form_id, 'inputs', TRUE ) ) {

				if ( ! empty( $inputs ) && is_array( $inputs ) ) {

					$defaults = [
						'label'         => '',
						'class'         => '',
						'key'           => '',
						'placeholder'   => '',
						'required'      => FALSE,
						'value'         => '',
						'default_value' => '',
						'type_label'    => '',
						'email_mask'    => '',
						'labels'        => []
					];

					foreach ( $inputs as $input => $values ) {
						$inputs[ $input ] = wp_parse_args( $values, $defaults );
					}

					return $inputs;

				}

			}

		}

		return array(
			0 => array(
				'type'       => 'name',
				'key'        => 'name',
				'label'      => 'Full Name',
				'type_label' => 'Full Name',
				'required'   => FALSE,
				'labels'     => array(
					'fname' => 'First',
					'lname' => 'Last',
				)
			),
			10 => array(
				'type'        => 'email',
				'key'         => 'email',
				'label'       => 'Email',
				'type_label'  => 'Email',
				'required'    => 'checked',
				'email_mask'  => '',
				'placeholder' => ''
			),
			20 => array(
				'type'        => 'phone',
				'key'         => 'phone',
				'label'       => 'Phone',
				'type_label'  => 'Phone',
				'required'    => FALSE,
				'placeholder' => ''
			)
		);

	}

	/**
	 * Get the form details.
	 *
	 * @param $contact_form_id
	 *
	 * @return array
	 */

	public static function get_details( $contact_form_id ) {

		$details = [
			'button_text' => 'Submit',
			'from_name'   => 'WPrequal Leads',
			'from_email'  => get_option( 'wprequal_from_email' ) ?: 'leads@wprequal.com',
			'to_email'    => get_option( 'wprequal_email' ) ?: Core::default_to_email(),
			'bcc_email'   => get_option( 'wprequal_bcc_email' ) ?: '',
			'sms_text'    => get_option( 'wprequal_sms_carrier_gateway' ) ?: ''
		];

		$contact_form_id = intval( $contact_form_id );

		if ( is_int( $contact_form_id ) && 0 < $contact_form_id ) {

			if ( $saved = get_post_meta( $contact_form_id, 'details', TRUE ) ) {

				if ( is_array( $saved ) ) {
					$details = wp_parse_args( array_filter( $saved ), $details );
				}

			}

		}

		return $details;

	}

	/**
	 * Get the contact form view.
	 *
	 * @param $args
	 */

	public function form( $args ) {

		$view = ( $args['type'] === 'contact' ) ?  'contact-form' : 'contact-inputs';
		$contact_form_id = intval( $args['contact_form_id'] );

		view( 'contact/form', $view, [
			'inputs'          => $this->get_inputs( $contact_form_id ),
			'details'         => self::get_details( $contact_form_id ),
			'type'            => $args['type'],
			'button_action'   => $args['button_action'],
			'contact_form_id' => $contact_form_id
		] );

	}

	/**
	 * Contact Form
	 *
	 * The contact form shortcode.
	 *
	 * @since 6.0
	 *
	 * @return string
	 */

	public function contact_form( $atts ) {

		$atts = shortcode_atts( array(
			'post_id' => 0
		), $atts, 'wprequal_contact_form' );

		$args = array(
			'contact_form_id' => (int) $atts['post_id'],
			'type'            => 'contact'
		);

		ob_start();
		do_action( 'wprequal_contact_form', $args );
		return ob_get_clean();

	}

	/**
	 * Email Input Mask
	 *
	 * The input mask for the email address.
	 *
	 * @since 6.3
	 *
	 * @return array|bool
	 */

	public function email_input_mask() {
		return array( 'alias' => 'email' );
	}
	
}