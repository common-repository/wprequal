<?php
/**
 * SurveyFormAdmin
 *
 * @since 2.0
 *
 * @package WPrequal
 */
namespace WPrequal;

use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class SurveyFormAdmin extends SurveyForm {

	/**
	 * Path to survey form admin views.
	 */
	const view_path = 'survey/admin';

	/**
	 * @var SurveyFormAdmin
	 */

	public static $instance;

	/**
	 * SurveyFormAdmin constructor.
	 */

	public function __construct() {

		parent::__construct();

		self::$instance = $this;

	}

	/**
	 * Instance.
	 *
	 * @return SurveyForm|SurveyFormAdmin
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

		add_action( 'admin_head', array( $this, 'fa_script' ) );
		add_action( 'admin_menu', array( $this, 'replace_submit_meta_box' ) );
        add_action( 'admin_footer', array( $this, 'loading' ) );
		add_action( "add_meta_boxes_{$this->post_type}", array( $this, 'add_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_popup_meta_box' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( "save_post_{$this->post_type}", array( $this, 'save_slides' ), 10, 1 );
		add_action( 'save_post_post', array( $this, 'save_survey_popup' ), 10, 1 );
		add_action( 'save_post_page', array( $this, 'save_survey_popup' ), 10, 1 );

		add_filter( "bulk_actions-edit-{$this->post_type}", array( $this, 'custom_bulk_actions' ), 99 );
		add_filter( 'page_row_actions', array( $this, 'modify_list_row_actions' ), 10, 2 );

	}

	/**
	 * Localize data for admin scripts.
	 */

	public function admin_scripts() {

		if ( get_post_type() === $this->post_type ) {

			if ( $post_id = get_the_ID() ) {

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wprequal_admin_js' );

				$fa_license = self::fa_license();

				wp_localize_script( 'wprequal_admin_js', 'surveyAdmin', array(
					'assets'    => $this->assets( $post_id ),
					'jsonUrl'   => WPREQUAL_ASSETS . "json/{$fa_license}-icons.json"
				) );

			}

		}

	}

	/**
	 * Fontawesome license.
	 *
	 * @return string
	 */

	public static function fa_license() {

		$checked = get_option( 'wprequal_fa_pro' );

		return ( Core::status( 1 ) && 'checked' === $checked ) ? 'pro' : 'free';

	}

	/**
	 * Assets to build survey form inputs.
	 *
	 * @param $post_id
	 *
	 * @return array
	 */

	public function assets( $post_id ) {

		return array(
			'slides'       => $this->get_slides( $post_id ),
			'reset_msg'    => $this->reset_msg(),
			'template_msg' => $this->template_msg(),
			'inputs'       => $this->inputs()
		);

	}

	/**
	 * Cache the inputs for the survey form builder.
	 *
	 * @return array|mixed
	 */

	public function inputs() {

		$inputs = array(
			'icons'        => $this->view( self::view_path, 'icons' ),
			'text'         => $this->view( self::view_path, 'text' ),
			'buttons'      => $this->view( self::view_path, 'buttons' ),
			'amount'       => $this->view( self::view_path, 'amount' ),
			'contact'      => $this->view( self::view_path, 'contact' ),
			'confirmation' => $this->view( self::view_path, 'confirmation' ),
			'redirect'     => $this->view( self::view_path, 'redirect' ),
			'processing'   => $this->view( self::view_path, 'processing' ),
			'faSelect'     => $this->view( self::view_path, 'fa-select' ),
			'button'       => $this->view( self::view_path, 'button' ),
			'logicInput'   => $this->view( self::view_path, 'conditional-logic-result' )
		);

		return $inputs;

	}

	/**
	 * Survey form reset message.
	 *
	 * @return string|void
	 */

	public function reset_msg() {
		return __( 'WARNING: You are about to reset the form slides. All edits to the form slides will be lost. Do you want to continue?', 'wprequal' );
	}

	/**
	 * Template reset empty message.
	 *
	 * @return string|void
	 */

	public function template_msg() {
		return __( 'You must select a template!', 'wprequal' );
	}

	/**
	 * Add Meta Box
	 *
	 * @since 7.0
	 */

	public function add_meta_box() {

		add_meta_box(
			'slide-options',
			__( 'Form Builder - Options', 'wprequal' ),
			array( $this, 'meta_box'),
			$this->post_type,
			'side'
		);

		add_meta_box(
			'slides',
			__( 'Form Builder - Slides', 'wprequal' ),
			array( $this, 'meta_box'),
			$this->post_type,
			'advanced',
			'high'
		);

		add_meta_box(
			'settings',
			__( 'Form Builder - Settings', 'wprequal' ),
			array( $this, 'meta_box'),
			$this->post_type,
			'advanced',
			'low'
		);

	}

	/**
	 * Display a metabox.
	 *
	 * @param $post
	 * @param $view
	 */

	public function meta_box( $post, $view ) {
		
		$allow = [ 'shortcode', 'styles', 'template' ];

		if ( ! Core::status( 1 ) && ! in_array( $view['id'], $allow ) ) {
			view( 'buttons', 'go-premium' );
			return;
		}

		view( self::view_path, $view['id'] );

	}

	/**
	 * Save slides data.
	 *
	 * @param $post_id
	 */

	public function save_slides( $post_id ) {

		if ( ! current_user_can( WPREQUAL_CAP ) ) {
			return;
		}

		if ( ! isset( $_POST['original_publish'] ) ) {
			return;
		}

		if ( ! check_admin_referer( 'save_slides', 'save_slides_nonce' ) ) {
			return;
		}

		// Are we reseting a slide to the template
		if ( isset( $_POST['create_slides_from_template'] ) && 'checked' === $_POST['create_slides_from_template'] ) {

			$this->create_slides_from_template();

		}

		// We have slides and status, let's update the slides.
		elseif ( Core::status( 1 ) && isset( $_POST['slide'] ) ) {

			$slides = Core::sanitize_array( $_POST['slide'] );

			update_post_meta( $post_id, 'slides', $slides );

		}

		// We have status, but we do not have slides.
		// Let's add an empty array so things do not break.
		elseif ( Core::status( 1 ) ) {

			update_post_meta( $post_id, 'slides', array() );

		}

		// If we have styles, let's update them here.
		if ( isset( $_POST['styles'] ) ) {

			$styles = Core::sanitize_array( $_POST['styles'] );

			update_post_meta( $post_id, 'styles', $styles );

		}

		if ( isset( $_POST['back_text'] ) ) {

			$text = sanitize_text_field( $_POST['back_text'] );

			update_post_meta( $post_id, 'back_text', $text );

		}

	}

	/**
	 * Craete slides from json template.
	 *
	 * @since 7.0.4
	 */

	public function create_slides_from_template() {

		$template = sanitize_text_field( $_POST['template'] );

		if ( ! empty( $template ) ) {

			if ( $slides = SurveyForm::get_settings( $template )  ) {

				$post_id = intval( $_POST['post_id'] );

				if ( $post_id && 0 < $post_id ) {
					update_post_meta( $post_id, 'slides', $slides );
				}

			}

		}

	}

	/**
	 * Add popup meta box.
	 */

	public function add_popup_meta_box() {

		add_meta_box(
			'survey_form_popup',
			__( 'WPrequal Survey Popup', 'wprequal' ),
			array( $this, 'popup_meta_box'),
			array( 'post', 'page' ),
			'side'
		);

	}

	/**
	 * Popup meta box.
	 *
	 * @param $post
	 */

	public function popup_meta_box( $post ) {

		if ( ! Core::status( 1 ) ) {
			view( 'buttons', 'go-premium' );
			return;
		}

		$args = array(
			'post_type'         => 'wpq_survey_form',
			'name'              => 'wpq_survey_form',
			'class'             => 'wpq-survey-form',
			'id'                => 'post_id',
			'show_option_none'  => __( 'Select One', 'wprequal' ),
			'option_none_value' => 0,
			'selected'          => get_post_meta( $post->ID, 'wpq_survey_form', TRUE )
		);

		wp_dropdown_pages( $args );

		wp_nonce_field( 'wpq_survey_form', 'wpq_survey_form_nonce' );

	}

	/**
	 * Save survey popup.
	 *
	 * @param $post_id
	 */
	public function save_survey_popup( $post_id ) {

		global $typenow;

		if ( ! current_user_can( WPREQUAL_CAP ) ) {
			return;
		}

		if ( ! is_admin() ) {
			return;
		}

		if ( 'post' !== $typenow && 'page' !== $typenow ) {
			return;
		}

		if ( ! isset( $_POST['wpq_survey_form'] ) ) {
			return;
		}

		if ( ! isset( $_POST['wpq_survey_form_nonce'] ) ) {
			return;
		}

		if ( ! check_admin_referer( 'wpq_survey_form', 'wpq_survey_form_nonce' ) ) {
			return;
		}

		$survey_id = intval( $_POST['wpq_survey_form'] );

		if ( is_int( $survey_id ) && 0 < $survey_id && Core::status( 1 ) ) {

			update_post_meta( $post_id, 'wpq_survey_form', $survey_id );

		} else {

			delete_post_meta( $post_id, 'wpq_survey_form' );

		}

	}

    /**
     * Loading
     *
     * @return void
     */

    public function loading() {

        global $typenow;

        if ( $this->post_type === $typenow  && isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {
            view( 'survey/admin', 'loading' );
        }

    }
	
}
