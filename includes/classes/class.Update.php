<?php
/**
 * Update Class
 *
 * @since 6.2.14
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class Update {

	use Api;

	/**
	 * Current version is 1.0 on startup.
	 *
	 * @var string
	 */

	private $current_version;

	/**
	 * @var Update
	 */

	public static $instance;

	/**
	 * Update constructor.
	 */

	public function __construct( $current_version ) {

		$this->current_version = $current_version;

		self::$instance = $this;

	}

	public static function instance( $current_version ) {

		if ( self::$instance === null ) {
			self::$instance = new self( $current_version );
		}

		return self::$instance;

	}

	/**
	 * Run
	 *
	 * Run any plugin updates available.
	 *
	 * @since 2.0.0
	 */

	public function run() {

		$settings = Settings::instance();

		/**
		 * Request new free access token if not already. (Free accounts only)
		 *
		 * @since 7.12.0
		 */
		// There is no status so we must be free
		if ( $settings->get_status() < 1 ) {
			// We do not have a free token, let's get one.
			if ( ! $settings->is_free_version() ) {
				Core::instance()->fetch_access_token();
			}
		}

		// Run if between 1.1 and 7.0
		if ( version_compare( $this->current_version, '1.1', '>=' ) && version_compare( $this->current_version, '7.0', '<' ) ) {
			// Convert leads to new lead format in 7.0+
			$this->convert_leads();
		}

		// Run if between 7.0 and 7.4
		if ( version_compare( $this->current_version, '7.0', '>=' ) && version_compare( $this->current_version, '7.4', '<' ) ) {

			if ( $version = get_option( 'wprequal_version_7_loaded' ) ) {
				add_option( 'wprequal_forms_loaded', $version, '', 'no' );
				delete_option( 'wprequal_version_7_loaded' );
			}

		}

		// Run if between 7.0 and 7.2.10
		if ( version_compare( $this->current_version, '7.0', '>=' ) && version_compare( $this->current_version, '7.2.10', '<=' ) ) {
			$this->update_currency_symbols();
		}

		// Run if between 7.0 and 7.4
		if ( version_compare( $this->current_version, '7.0', '>=' ) && version_compare( $this->current_version, '7.4', '<' ) ) {
			// Install the default contact form and update the survey forms
			$this->version_7_4();
		}

		// Run if between 7.0 and 7.5.2
		if ( version_compare( $this->current_version, '7.0', '>=' ) && version_compare( $this->current_version, '7.5.2', '<=' ) ) {
			// Rename prequal form to survey form
			$this->rename_prequal_form();
			$this->rename_prequal_form_term();
		}

		// Install the default forms
		// NOTE: As of 7.4 the survey form JSON does not include new contact slide fields
		if ( ! get_option( 'wprequal_forms_loaded' ) ) {
			$this->load_forms();
			add_option( 'wprequal_forms_loaded', WPREQUAL_VERSION, '', 'no' );
		}

		// Run if never ran before
		if ( ! get_option( 'wprequal_back_link_text' ) ) {
			// Add back link text
			$this->back_link_text();
			add_option( 'wprequal_back_link_text', WPREQUAL_VERSION, '', 'no' );
		}

		// Since the WPREQUAL_VERSION option is not set yet on initial plugin activation,
		// We can run this on updates only.
		if ( get_option( 'WPREQUAL_VERSION' ) ) {
			// Run deactivation functions
			deactivate();
			// Run activation functions
			activate();
		}

		// Update current plugin version
		if ( update_option( 'WPREQUAL_VERSION', WPREQUAL_VERSION ) ) {

			delete_transient( Settings::status_key );

			Log::write( 'version-update-' . WPREQUAL_VERSION, 'complete' );

		}

	}

	/**
	 * Load Forms.
	 */

    private function load_forms() {

		// Load the contact form.
	    $contact_form_id = $this->load_contact_form();

	 	// Load survey form.
	    $this->load_survey_form( $contact_form_id );

	    // Load the registration form
	    $this->load_register_form();

    }

	/**
	 * Load survey forms.
	 *
	 * @param $contact_form_id
	 */

    private function load_survey_form( $contact_form_id ) {

	 	require_once( ABSPATH . 'wp-admin/includes/post.php' );

	 	$forms = array(
	 		'mortgage'    => 'Mortgage',
		    'real_estate' => 'Real Estate'
	    );

	 	foreach ( $forms as $file_name => $title ) {

		    if ( ! post_exists( $title, '', '', 'wpq_survey_form' ) ) {

			    $post_id = wp_insert_post( array(
				    'post_title'  => $title,
				    'post_type'   => 'wpq_survey_form',
				    'post_status' => 'publish'
			    ) );

			    if ( $slides = SurveyForm::get_settings( $file_name )  ) {

				    // Add the contact form id for the contact slides
				    foreach ( $slides as $key => $values ) {

					    if ( 'contact' === $slides[$key]['type'] ) {
						    $slides[$key]['contact_form_id'] = $contact_form_id;
						    $slides[$key]['button_action'] = 'submit-button';
					    }

				    }

				    update_post_meta( $post_id, 'slides', $slides );

				    Log::write( 'version-update-' . WPREQUAL_VERSION, $slides );

			    }

			    if ( $styles = SurveyForm::get_settings( 'styles' ) ) {

				    update_post_meta( $post_id, 'styles', $styles );

				    Log::write( 'version-update-' . WPREQUAL_VERSION, $styles );

			    }

			    // Set the back button text.
			    update_post_meta( $post_id, 'back_text', 'Back' );

			    // Assign the first post ID as a default setting.
			    if ( ! get_option( 'wprequal_popup_post_id' ) ) {
				    update_option( 'wprequal_popup_post_id', $post_id );
			    }

		    }

	    }

    }

	private function convert_leads() {

	 	$post_ids = get_posts( array(
	 		'posts_per_page' => -1,
	 		'post_type'      => 'wprequal_lead',
		    'fields'         => 'ids'
	    ));

		Log::write( 'version-update-' . WPREQUAL_VERSION, $post_ids );

	 	if ( count( $post_ids ) < 1 ) {
	 		return;
	    }

	 	$lead_fields = $this->lead_fields();

		$contact_fields = array(
			'fname',
			'lname',
			'email',
			'phone',
			'comments'
		);

	 	foreach ( $post_ids as $post_id ) {

	 		// Contact
	 		$new_contact = array();

		    $meta = get_post_meta( $post_id, 'wprequal_lead', TRUE );

	 		foreach ( $contact_fields as $contact_field ) {
			    $new_contact[$contact_field] = isset( $meta[$contact_field] ) ? $meta[$contact_field] : '';
		    }

		    update_post_meta( $post_id, 'contact', $new_contact );

		    Log::write( 'version-update-' . WPREQUAL_VERSION, $new_contact );

	 		// Fields
	 		$new_fields = array();

		    foreach ( $lead_fields as $key => $value ) {
		    	$new_fields[ $key ] = isset( $meta[ $key ] ) ? $meta[ $key ] : '';
		    }

		    update_post_meta( $post_id, 'fields', $new_fields );

		    Log::write( 'version-update-' . WPREQUAL_VERSION, $new_fields );

		    // Labels
		    $new_labels = array();

		    foreach ( $lead_fields as $key => $value ) {
		    	$new_labels[ $key ] = $value;
		    }

		    $new_labels = array_filter( $new_labels );

		    update_post_meta( $post_id, 'field_labels', $new_labels );

		    Log::write( 'version-update-' . WPREQUAL_VERSION, $new_labels );

	    }

	}

	private function lead_fields() {

		return array(
			'ptype'               => 'Property Type',
			'credit'              => 'Credit',
			'bankruptcy'          => 'Bankruptcy',
			'current_postal_code' => 'Current Postal Code',
			'ownership'           => 'Ownership',
			'loan_amount'         => 'Loan Amount',
			'down_payment'        => 'Money Down',
			'agent'               => 'Has Real Estate Agent',
			'found_home'          => 'Found Home',
			'search_postal_code'  => 'New Postal Code',
			'moving_date'         => 'Moving Date',
			'how_soon'            => 'How Soon',
			'military'            => 'Military'
		);

	}

	private function update_currency_symbols() {

		$post_ids = get_posts( array(
			'posts_per_page' => -1,
			'post_type'      => 'wpq_survey_form',
			'fields'         => 'ids'
		));

		foreach ( $post_ids as $post_id ) {

			if ( $slides = get_post_meta( $post_id, 'slides', TRUE ) ) {

				foreach ( $slides as $key => $values ) {

					if ( 'amount' === $slides[$key]['type'] ) {

						if ( isset( $slides[$key]['class'] ) ) {

							$symbol  = 'dollar';
							$symbols = Core::currency_symbols();

							foreach ( $symbols as $name => $code ) {

								if ( FALSE !== strpos( $slides[ $key ]['class'], $name ) ) {
									$symbol = $name;
								}

							}

							$slides[$key]['currency_symbol'] = $symbol;
							unset( $slides[$key]['class'] );
						}

					}

				}

				update_post_meta( $post_id, 'slides', $slides );

			}

		}

	}

	private function version_7_4() {
		$contact_form_id = $this->load_contact_form();
		$this->update_ccontact_slides( $contact_form_id );
		$this->load_register_form();
	}

	private function load_contact_form() {

		require_once( ABSPATH . 'wp-admin/includes/post.php' );

		$title = 'Contact Form';
		$type  = 'wpq_contact_form';

		if ( ! $contact_form_id = post_exists( $title, '', '', $type ) ) {

			$contact_form_id = wp_insert_post( array(
				'post_title'  => $title,
				'post_slug'   => sanitize_title( $title ),
				'post_type'   => $type,
				'post_status' => 'publish'
			) );

			$fofm = ContactForm::instance();

			$inputs = $fofm->get_inputs( 0 );
			update_post_meta( $contact_form_id, 'inputs', $inputs );

			$details = ContactForm::get_details( 0 );
			update_post_meta( $contact_form_id, 'details', $details );

		}

		update_option( 'wprequal_get_quote_post_id', $contact_form_id );

		return $contact_form_id;

	}

	private function update_ccontact_slides( $contact_form_id ) {

		$post_ids = get_posts( array(
			'posts_per_page' => -1,
			'post_type'      => 'wpq_survey_form',
			'fields'         => 'ids'
		));

		foreach ( $post_ids as $post_id ) {

			if ( $slides = get_post_meta( $post_id, 'slides', TRUE ) ) {

				foreach ( $slides as $key => $values ) {

					if ( 'contact' === $slides[$key]['type'] ) {
						$slides[$key]['contact_form_id'] = $contact_form_id;
						$slides[$key]['button_action'] = 'submit-button';
					}

				}

				update_post_meta( $post_id, 'slides', $slides );

			}

		}

	}

	private function load_register_form() {

		require_once( ABSPATH . 'wp-admin/includes/post.php' );

		$title = 'Register Form';
		$type  = 'wpq_register_form';

		if ( ! post_exists( $title, '', '', $type ) ) {

			$post_id = wp_insert_post( array(
				'post_title'  => $title,
				'post_slug'   => sanitize_title( $title ),
				'post_type'   => $type,
				'post_status' => 'publish'
			) );

			$fofm = RegisterForm::instance();

			$input = $fofm->get_input( 0 );
			update_post_meta( $post_id, 'input', $input );

			$details = ContactForm::get_details( 0 );
			update_post_meta( $post_id, 'details', $details );

		}

	}

	public function rename_prequal_form() {

		global $wpdb;

		$wpdb->update(
			$wpdb->posts,
			array( 'post_type' => 'wpq_survey_form' ),
			array( 'post_type' => 'prequal_form' )
		);

	}

	public function rename_prequal_form_term() {

		global $wpdb;

		$wpdb->update(
			$wpdb->terms,
			array( 'name' => 'Survey Form', 'slug' => 'survey-form' ),
			array( 'name' => 'Prequal Form' )
		);

	}

	/**
	 * Back Link Text
	 *
	 * @version 7.6.3
	 */

	public function back_link_text() {

		$post_ids = get_posts( array(
			'posts_per_page' => -1,
			'post_type'      => 'wpq_survey_form',
			'fields'         => 'ids'
		));

		foreach ( $post_ids as $post_id ) {

			if ( ! get_post_meta( $post_id, 'back_text', TRUE ) ) {
				update_post_meta( $post_id, 'back_text', 'Back' );
			}

		}

	}

}