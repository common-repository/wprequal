<?php
/**
 * Lead Class
 *
 * @since 5.0
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class Lead extends PostType {

	/**
	 * @var string
	 */

	protected $post_type = 'wprequal_lead';

	/**
	 * @var string
	 */

	protected $label_name = 'Lead';

	/**
	 * @var string
	 */

	protected $label_names = 'Leads';

	/**
	 * @var string
	 */

	protected $menu_label = 'WPrequal Leads';

	/**
	 * @var bool
	 */

	protected $menu_icon = FALSE;

	/**
	 * @var array
	 */

	protected $supports = array( 'title' );

	/**
	 * @var string
	 */

	protected $show_in_menu = WPREQUAL_OPTIONS;

	/**
	 * @var string
	 */

	private $contact_key = 'contact';

	/**
	 * @var string
	 */

	private $fields_key  = 'fields';

	/**
	 * @var string
	 */

	private $labels_key  = 'field_labels';

	/**
	 * @var array
	 */

	protected $taxonomies = array(
		'lead_source' => array(
			'label'        => 'Lead Source',
			'labels'       => 'Lead Source',
			'capabilities' => array(
				'assign_terms' => WPREQUAL_CAP,
				'edit_terms'   => FALSE,
				'delete_terms' => FALSE,
				'manage_terms' => WPREQUAL_CAP
			)
		),
		'social_referrer' => array(
			'label'        => 'Social Media Referrer',
			'labels'       => 'Social Media Referrer',
			'capabilities' => array(
				'assign_terms' => WPREQUAL_CAP,
				'edit_terms'   => FALSE,
				'delete_terms' => FALSE,
				'manage_terms' => WPREQUAL_CAP
			)
		),
		'param_referrer' => array(
			'label'        => 'URL Param Referrer',
			'labels'       => 'URL Param Referrer',
			'capabilities' => array(
				'assign_terms' => WPREQUAL_CAP,
				'edit_terms'   => FALSE,
				'delete_terms' => FALSE,
				'manage_terms' => WPREQUAL_CAP
			)
		)
	);

	/**
	 * @var Lead
	 */

	public static $instance;

	/**
	 * Lead constructor.
	 */

	public function __construct() {

		parent::__construct();

		self::$instance = $this;

	}

	/**
	 * Instance.
	 *
	 * @return Lead
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
	 * @since 5.0
	 */

	public function actions() {
		
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );

		if ( is_admin() ) {

			$note = Note::instance();
			$note->actions();

			add_action( "save_post_{$this->post_type}" , array( $this, 'save_lead' ) );
			add_action( 'admin_menu', array( $this, 'replace_submit_meta_box' ) );
			add_action( 'admin_menu', array( $this, 'add_submenu_item' ) );
			add_action( 'admin_init', array( $this, 'export_leads' ) );
			add_action( "add_meta_boxes_{$this->post_type}", array( $this, 'add_meta_box' ) );

			add_filter( 'bulk_actions-edit-wprequal_lead', array( $this, 'custom_bulk_actions' ), 99 );
			add_filter( 'manage_wprequal_lead_posts_columns' , array( $this, 'lead_columns' ) );
			add_filter( 'page_row_actions', array( $this, 'modify_list_row_actions' ), 10, 2 );

		}

	}

	/**
	 * Create Lead
	 *
	 * Create a custom post type wprequal_lead.
	 *
	 * @since 5.0
	 *
	 * @param array $lead The array from a form submit.
	 */

	public static function create_lead( $entry ) {
		return ( new self )->insert_lead( $entry );
	}

	/**
	 * Insert Lead.
	 *
	 * @param $entry
	 *
	 * @return int|\WP_Error
	 */

	public function insert_lead( $entry ) {

		extract( $entry );
		extract( $entry['lead'] );

		$title = $this->lead_title( $contact );

		$post = array(
			'post_title'    => $title,
			'post_status'   => 'publish',
			'post_type'		=> $this->post_type
		); 

		$lead_id = wp_insert_post( $post );

		if ( ! empty( $contact ) ) {
			update_post_meta( $lead_id, $this->contact_key, $contact );
		}

		if ( ! empty( $contact_form_id ) ) {
			update_post_meta( $lead_id, 'contact_form_id', $contact_form_id );
		}

		if ( ! empty( $source_url ) ) {
			update_post_meta( $lead_id, 'source_url', $source_url );
		}

		if ( ! empty( $fields ) ) {

			$this->save_lead_fields( $lead_id, $fields );

			$field_labels_slides = array();
			$field_labels_inputs = array();

			if ( ! empty( $survey_id ) ) {
				// The survey_id is extracted from $entry.
				$field_labels_slides = $this->field_labels( $survey_id, 'slides' );
			}

			if ( ! empty( $contact_form_id ) ) {

				// We do not need to do this for a register form
				if ( 'register' !== $type ) {
					// The contact_form_id is extracted from $entry.
					$field_labels_inputs = $this->field_labels( $contact_form_id, 'inputs' );
				}

			}

			$labels = array_merge( $field_labels_slides, $field_labels_inputs );
			update_post_meta( $lead_id, $this->labels_key, $labels );

		}

		if ( ! empty( $calc_fields ) ) {

			update_post_meta( $lead_id, $this->fields_key, $calc_fields );

			$labels = $this->calc_labels();
			// Let's save the labels as there are when the lead is captured.
			update_post_meta( $lead_id, $this->labels_key, $labels );

		}

		if ( ! empty( $type ) ) {

			if ( $type = $this->map_type( $type ) ) {
				$this->set_term( $lead_id, 'lead_source', $type );
			}

		}

		if ( ! empty( $social_referrer ) ) {
			$this->set_term( $lead_id, 'social_referrer', $social_referrer );
		}

		if ( ! empty( $param_referrer ) ) {
			$this->set_term( $lead_id, 'param_referrer', $param_referrer );
		}

		if ( Core::status( 1 ) ) {
			do_action( 'wprequal_after_lead_created', $lead_id );
		}

		return $lead_id;

	}

	/**
	 * get the title for this lead.
	 *
	 * @param $contact
	 *
	 * @return string
	 */

	public function lead_title( $contact ) {

		extract( $contact );

		$full_name = trim( $fname . ' ' . $lname );

		if ( ! empty( $full_name ) ) {
			return $full_name;
		}

		if ( ! empty( $email ) ) {
			return $email;
		}

		if ( ! empty( $phone ) ) {
			return $phone;
		}

		return 'New Lead';

	}

	/**
	 * Set the object term.
	 *
	 * @param $lead_id
	 * @param $taxonomy
	 * @param $value
	 */

	public function set_term( $lead_id, $taxonomy, $value ) {

		if ( ! $term = term_exists( $value, $taxonomy ) ) {
			$term = wp_insert_term( $value, $taxonomy );
		}

		wp_set_object_terms( $lead_id, intval( $term['term_id'] ), $taxonomy );

	}

	/**
	 * Map type of registration source.
	 *
	 * @param $type
	 *
	 * @return bool|mixed
	 */

	public function map_type( $type ) {

		$types = array(
			'register'  => 'Registration Form',
			'contact'   => 'Contact Form',
			'survey'   => ' Survey Form',
			'get_quote' => 'Get Quote Form'
		);

		return empty( $types[$type] ) ? FALSE : $types[$type];

	}

	/**
	 * Save Lead
	 *
	 * Save the lead data on post edit submit.
	 *
	 * @since 5.0
	 */

	public function save_lead() {

		if ( ! current_user_can( WPREQUAL_CAP ) ) {
			return;
		}

		if ( ! isset( $_POST['original_publish'] ) ) {
			return;
		}

		if ( ! check_admin_referer( 'save_lead', 'save_lead_nonce' ) ) {
			return;
		}

		global $post;

		if ( isset( $post->post_type ) && $post->post_type === $this->post_type ) {

			if ( isset( $_POST[$this->contact_key] ) ) {

				$contact = Core::sanitize_array( $_POST[$this->contact_key] );

				update_post_meta( $post->ID, $this->contact_key, $contact );

			}

			if ( isset( $_POST[$this->fields_key] ) ) {

				$this->save_lead_fields( $post->ID, $_POST[$this->fields_key] );

			}

		}

	}

	/**
	 * Save the lead fields.
	 *
	 * @param $lead_id
	 * @param $fields
	 */

	public function save_lead_fields( $lead_id, $fields ) {

		$fields = Core::sanitize_array( $fields );

		// Save arrays as strings
		foreach ( $fields as $key => $value ) {

			if ( is_array( $value ) ) {
				$fields[$key] = join( ', ', $value );
			}

		}

		update_post_meta( $lead_id, $this->fields_key, $fields );

	}

	/**
	 * Get field labels from to survey form.
	 *
	 * @param $post_id
	 *
	 * @return array
	 */

	public function field_labels( $post_id, $meta_key ) {

		$labels  = array();

		if ( $slides = get_post_meta( (int) $post_id, $meta_key, TRUE ) ) {

			foreach ( $slides as $slide ) {

				$labels[ $slide['key'] ] = $slide['label'];

				if ( 'contact' === $slide['type'] ) {

					$contact_labels = $this->field_labels( (int) $slide['contact_form_id'], 'inputs' );
					$labels         = array_merge( $labels, $contact_labels );

				}

			}

		}

		return $labels;

	}

	/**
	 * Calc Labels.
	 *
	 * We get the calc labels for each lead so they will match
	 * what the labels are when the lead is captured.
	 *
	 * @return array
	 */

	public function calc_labels() {

		$labels = array(
			'insurance'    => get_option( 'wprequal_insurance_label' ),
			'tax'          => get_option( 'wprequal_tax_label' ),
			'term'         => get_option( 'wprequal_term_label' ),
			'rate'         => get_option( 'wprequal_rate_label' ),
			'price'        => get_option( 'wprequal_price_label' ),
			'down_payment' => get_option( 'wprequal_down_payment_label' ),
		);

		return $labels;

	}

	/**
	 * Add Meta Box
	 *
	 * @since 5.0
	 */

	public function add_meta_box() {

		add_meta_box(
			$this->contact_key,
			__( 'Contact Info', 'wprequal' ),
			array( $this, 'metabox'), 
			$this->post_type,
			'advanced',
            'high'
		);

		add_meta_box(
			$this->fields_key,
			__( 'Lead Fields', 'wprequal' ),
			array( $this, 'metabox'),
			$this->post_type,
			'advanced',
			'high'
		);
		
	}

	/**
	 * Meta Box
	 *
	 * @since 5.0
	 *
	 * @param object $post The WP Post object.
	 */

	public function metabox( $post, $meta_box ) {

		switch ( $meta_box['id'] ) {

			case 'contact' :
				$args = [
					'values' => get_post_meta( get_the_ID(), $this->contact_key, TRUE ) ?: array()
				];
				break;

			case 'fields' :
				$args = [
					'values' => get_post_meta( get_the_ID(), $this->fields_key, TRUE ) ?: array(),
					'labels' => get_post_meta( get_the_ID(), $this->labels_key, TRUE ) ?: array()
				];
				break;

			default :
				$args = [];

		}

		view( 'lead', $meta_box['id'], $args );

	}

	/**
	 * Lead Columns
	 *
	 * Columns on the Leads edit screen.
	 *
	 * @since 5.0
	 *
	 * @param  array $columns An array of column names.
	 * @return array $columns An array of custom column names.
	 */

	public function lead_columns( $columns ) {

		return array_merge(
			array( 'cb' 	                  => __( 'checkall', 'wprequal' ) ),
			array( 'title' 	                  => __( 'Full Name', 'wprequal' ) ),
			array( 'taxonomy-lead_source'     => __( 'Lead Source', 'wprequal' ) ),
			array( 'taxonomy-social_referrer' => __( 'Social Media Referrer', 'wprequal' ) ),
			array( 'taxonomy-param_referrer'  => __( 'URL Param Referrer', 'wprequal' ) ),
			array( 'date' 	                  => __( 'Date Created', 'wprequal' ) )
		);

	}

	/**
	 * Add Export Leads Menu Item.
	 *
	 * @since 5.2
	 */

	public function add_submenu_item() {

		add_submenu_page(
			WPREQUAL_OPTIONS,
			'Export Leads',
			'Export Leads',
			WPREQUAL_CAP,
			'export-leads',
			array( $this, 'export_leads_page' )
		);

	}

	/**
	 * Export Leads Page
	 *
	 * HTML for the Export Leads page.
	 *
	 * @since 5.3
	 */

	public function export_leads_page() {

		$access_token = Core::access_token();

		$args = [
			'href' => admin_url( "/edit.php?post_type={$this->post_type}&page=export-leads&export_key={$access_token}" )
		];

		view( 'lead', 'export', $args );

	}

	/**
	 * Export Leads
	 *
	 * @since 5.3
	 */

	public function export_leads() {

		if ( isset( $_GET['export_key'] ) && Core::access_token() === $_GET['export_key'] ) {
			
			$keys = array(
				'fname',
				'lname',
				'email',
				'phone'
			);

			$output = join( ',', $keys ) . PHP_EOL;

			$args = array(
				'posts_per_page' => -1,
				'post_type'      => $this->post_type,
				'fields'         => 'ids'
			);

			$lead_ids = get_posts( $args );

			foreach ( $lead_ids as $lead_id ) {

				$row  = array();
				$data = get_post_meta( $lead_id, $this->contact_key, TRUE );

				foreach( $keys as $key ) {
					$row[] = empty( $data[$key] ) ? '' : $data[$key];
				}

				$output .= join( ',', $row ) . PHP_EOL;

			}

			$export_dir = $this->export_dir();

			$basename = 'leads-' . date( 'y-m-d' ) . '-' . rand( 10000, time() ) . '.csv';

			file_put_contents(  "{$export_dir}/{$basename}", rtrim( $output, PHP_EOL ) );

			header( 'Pragma: public' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Cache-Control: private', FALSE );
			header( 'Content-Type: application/octet-stream' );
			header( 'Content-Disposition: attachment; filename="' . basename( $basename ) . '";' );
			header( 'Content-Transfer-Encoding: binary' );

			echo readfile( "{$export_dir}/{$basename}" );

			exit;

		}

	}

	/**
	 * Export Dir
	 *
	 * @since 5.3
	 *
	 * @return string The path for the exp0rt dir.
	 */

	public function export_dir() {

		$upload     = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$export_dir = $upload['basedir'] . '/crm-export';

		if ( ! is_dir( $export_dir ) ) {
			wp_mkdir_p( $export_dir );
		}

		return $export_dir;

	}

}
