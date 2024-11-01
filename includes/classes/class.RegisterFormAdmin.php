<?php
/**
 * RegisterFormAdmin
 *
 * @since 7.3
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class RegisterFormAdmin extends RegisterForm {

	/**
	 * Path to contact admin views.
	 */

	const view_path = 'contact/admin';

	/**
	 * @var RegisterFormAdmin
	 */

	public static $instance;

	/**
	 * RegisterFormAdmin constructor.
	 */

	public function __construct() {

		parent::__construct();

		self::$instance = $this;

	}

	/**
	 * Instance.
	 *
	 * @return RegisterForm|RegisterFormAdmin
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

		add_action( 'admin_menu', array( $this, 'replace_submit_meta_box' ) );
		add_action( "add_meta_boxes_{$this->post_type}", array( $this, 'add_meta_box' ) );
		add_action( "save_post_{$this->post_type}", array( $this, 'save_inputs' ), 10, 1 );

		add_filter( "bulk_actions-edit-{$this->post_type}", array( $this, 'custom_bulk_actions' ), 99 );
		add_filter( 'page_row_actions', array( $this, 'modify_list_row_actions' ), 10, 2 );

	}

	/**
	 * Add Meta Box
	 *
	 * @since 7.0
	 */

	public function add_meta_box() {

		add_meta_box(
			'register-input',
			__( 'Form Builder', 'wprequal' ),
			array( $this, 'meta_box'),
			$this->post_type,
			'advanced',
			'high'
		);

		add_meta_box(
			'details',
			__( 'Form Builder - Details', 'wprequal' ),
			array( $this, 'meta_box'),
			$this->post_type,
			'advanced',
			'default'
		);

		add_meta_box(
			'register-shortcode',
			__( 'Form Builder - Shortcode', 'wprequal' ),
			array( $this, 'meta_box'),
			$this->post_type,
			'side'
		);

	}

	/**
	 * Display a metabox.
	 *
	 * @param $post
	 * @param $view
	 */

	public function meta_box( $post, $view ) {

		switch( $view['id'] ) {

			case 'details':
				$args = ContactForm::get_details( get_the_ID() );
				break;

			case 'register-input':
				$args = [ 'input' => RegisterForm::get_input( get_the_ID() ) ];
				break;

			default:
				$args = [];

		}

		view( self::view_path, $view['id'], $args );
	}

	/**
	 * Save inputs data.
	 *
	 * @param $post_id
	 */

	public function save_inputs( $post_id ) {

		if ( ! current_user_can( WPREQUAL_CAP ) ) {
			return;
		}

		if ( ! isset( $_POST['original_publish'] ) ) {
			return;
		}

		if ( ! check_admin_referer( 'save_register_inputs', 'save_register_inputs_nonce' ) ) {
			return;
		}

		// We have slides and status, let's update the inputs.
		if ( isset( $_POST['input'] ) ) {

			$inputs = Core::sanitize_array( $_POST['input'] );

			// Since everything is in array. We will just grab in from arry here.
			foreach( $inputs as $input ) {
				$input = $input;
			}

			update_post_meta( $post_id, 'input', $input );

		}

		// Let's add an empty array so things do not break.
		else {

			update_post_meta( $post_id, 'input', [] );

		}

		if ( isset( $_POST['details'] ) ) {

			$details = Core::sanitize_array( $_POST['details'] );

			update_post_meta( $post_id, 'details', $details );

		}

		// Let's add an empty array so things do not break.
		else {

			update_post_meta( $post_id, 'details', [] );

		}

	}
	
}