<?php
/**
 * ContactFormAdmin
 *
 * @since 7.3
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
    wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class ContactFormAdmin extends ContactForm {

    /**
     * Path to the views' admin.
     */

    const view_path = 'contact/admin';

    /**
     * @var ContactFormAdmin
     */

    public static $instance;

    /**
     * ContactFormAdmin constructor.
     */

    public function __construct() {

        parent::__construct();

        self::$instance = $this;

    }

    /**
     * Instance.
     *
     * @return ContactForm|ContactFormAdmin
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

        add_action( 'admin_menu', [ $this, 'replace_submit_meta_box' ] );
        add_action( "add_meta_boxes_{$this->post_type}", [ $this, 'add_meta_box' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
        add_action( "save_post_{$this->post_type}", [ $this, 'save_inputs' ], 10, 1 );

        add_filter( "bulk_actions-edit-{$this->post_type}", [ $this, 'custom_bulk_actions' ], 99 );
        add_filter( 'page_row_actions', [ $this, 'modify_list_row_actions' ], 10, 2 );

    }

    /**
     * Localize data for admin scripts.
     */

    public function admin_scripts() {

        if ( get_post_type() === $this->post_type ) {

            if ( $post_id = get_the_ID() ) {

                wp_enqueue_script( 'wprequal_admin_js' );

                wp_localize_script( 'wprequal_admin_js', 'contactAdmin', [
                    'inputs' => $this->inputs(),
                    'msg'    => $this->alert_messages()
                ] );

            }

        }

    }

    /**
     * Contact form admin inputs.
     *
     * @return array
     */

    public function inputs() {

        $inputs = [];
        $views  = $this->input_types();

        foreach ( $views as $view => $label ) {
            $inputs[ $view ] = $this->get_view( $view );
        }

        return $inputs;

    }

    /**
     * Input Types.
     *
     * @return array
     */

    public function input_types() {

        return [
            // Contact Fields
            'name'     => __( 'Name', 'wprequal' ),
            'email'    => __( 'Email', 'wprequal' ),
            'phone'    => __( 'Phone', 'wprequal' ),
            'comments' => __( 'Comments', 'wprequal' ),
            // Lead Fields
            'text'     => __( 'Text Input', 'wprequal' ),
            'number'   => __( 'Number', 'wprequal' ),
            'checkbox' => __( 'Checkbox', 'wprequal' ),
            'radio'    => __( 'Radio Button', 'wprequal' ),
            'select'   => __( 'Select', 'wprequal' ),
            'textarea' => __( 'Text Area', 'wprequal' ),
            'range'    => __( 'Range Slider', 'wprequal' ),
            'hidden'   => __( 'Hidden', 'wprequal' ),
            'section'  => __( 'Section', 'wprequal' )
        ];

    }

    /**
     * Contact fields.
     *
     * @return array
     */

    public function contact_fields() {

        return [
            'name',
            'email',
            'phone',
            'comments'
        ];

    }

    /**
     * View.
     *
     * @param      $view
     * @param bool $return
     *
     * @return string
     */

    public static function view( $view, $input = [] ) {

        view( self::view_path, $view, [
            'input' => $input
        ] );

    }

    /**
     * Get the view for an input.
     *
     * @param $view
     *
     * @return string
     */

    public function get_view( $view ) {

        ob_start();

        self::view( $view, $this->default_values() );

        return ob_get_clean();

    }

    /**
     * Default values for an input.
     *
     * @return array
     */

    public function default_values() {

        return [
            'type'          => '{type}',
            'key'           => '{key}',
            'label'         => '',
            'type_label'    => '{type_label}',
            'required'      => '',
            'placeholder'   => '',
            'default_value' => '',
            'labels'        => [],
            'min'           => 0,
            'max'           => 0,
            'step'          => 0,
            'default'       => ''
        ];

    }

    /**
     * JS alert messages.
     *
     * @return array
     */

    public function alert_messages() {

        return [
            'correctErrors'  => __( 'Please correct the errors and try again!' ),
            'onlyOneAllowed' => __( 'Only 1 {label} field is allowed. Please use a lead field instead.' )
        ];

    }

    /**
     * Add Meta Box
     *
     * @since 7.0
     */

    public function add_meta_box() {

        add_meta_box(
            'contact-fields',
            __( 'Form Builder - Contact Fields', 'wprequal' ),
            [ $this, 'meta_box' ],
            $this->post_type,
            'side'
        );

        add_meta_box(
            'lead-fields',
            __( 'Form Builder - Lead Fields', 'wprequal' ),
            [ $this, 'meta_box' ],
            $this->post_type,
            'side'
        );

        add_meta_box(
            'inputs',
            __( 'Form Builder - Inputs', 'wprequal' ),
            [ $this, 'meta_box' ],
            $this->post_type,
            'advanced',
            'high'
        );

        add_meta_box(
            'details',
            __( 'Form Builder - Details', 'wprequal' ),
            [ $this, 'meta_box' ],
            $this->post_type,
            'advanced',
            'default'
        );

        add_meta_box(
            'shortcode',
            __( 'Form Builder - Shortcode', 'wprequal' ),
            [ $this, 'meta_box' ],
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

        $allow = [ 'shortcode', 'contact-fields', 'details', 'inputs' ];

        if ( ! Core::status( 1 ) && ! in_array( $view['id'], $allow ) ) {
            view( 'buttons', 'go-premium' );

            return;
        }

        switch ( $view['id'] ) {

            case 'contact-fields':
            case 'lead-fields':
                $args = [
                    'inputs'         => $this->input_types(),
                    'contact_fields' => $this->contact_fields()
                ];
                break;

            case 'inputs':
                $args = [
                    'inputs' => $this->get_inputs( get_the_ID() )
                ];
                break;

            case 'details':
                $args = ContactForm::get_details( get_the_ID() );
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

        if ( ! check_admin_referer( 'save_contact_inputs', 'save_contact_inputs_nonce' ) ) {
            return;
        }

        // We have slides and status, let's update the inputs.
        if ( isset( $_POST['input'] ) ) {

            $inputs = Core::sanitize_array( $_POST['input'] );
            $inputs = $this->sanitize_name( $inputs );

            update_post_meta( $post_id, 'inputs', $inputs );

        } // Let's add an empty array so things do not break.
        else {

            update_post_meta( $post_id, 'inputs', [] );

        }

        if ( isset( $_POST['details'] ) ) {

            $details = Core::sanitize_array( $_POST['details'] );

            update_post_meta( $post_id, 'details', $details );

        } // Let's add an empty array so things do not break.
        else {

            update_post_meta( $post_id, 'details', [] );

        }

    }

    /**
     * Sanitize the input name.
     *
     * @param $inputs
     *
     * @return mixed
     */

    public function sanitize_name( $inputs ) {

        foreach ( $inputs as $key => $value ) {

            if ( isset( $value['name'] ) ) {
                $inputs[ $key ]['name'] = sanitize_key( str_replace( ' ', '_', $value['name'] ) );
            }

        }

        return $inputs;

    }

}