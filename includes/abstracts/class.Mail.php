<?php
/**
 * Mail
 *
 * @since 6.2.12
 *
 * @package WPrequal
 */
namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

abstract class Mail {

	/**
	 * @var mixed
	 */

	private $details;

	/**
	 * Mail constructor.
	 */

	public function __construct( $post_id ) {
		$this->details = $this->details( $post_id );
	}

	/**
	 * Contact form details.
	 *
	 * @param $survey_form_id
	 *
	 * @return bool
	 */

	public function details( $survey_form_id ) {

		if ( $contact_form_id = get_post_meta( $survey_form_id, 'contact_form_id', TRUE ) ) {

			$details = ContactForm::get_details( $contact_form_id );

			$details['contact_form_id'] = $contact_form_id;
			$details['survey_form_id']  = $survey_form_id;

			Log::write( 'details', $details );

			return $details;

		}

		Log::write( 'details', "No details - Survey Form ID: $survey_form_id" );

		return ContactForm::get_details( 0 );

	}

	/**
	 * Email Headers
	 *
	 * @since 5.0
	 * @since 5.1 Return as array.
	 */

	public function email_headers( $args ) {

		$from_name  = $this->from_name();
		$from_email = $this->from_email();

		$headers 	= [];
		$headers[] 	= "From: $from_name <$from_email>";
		$headers[] 	= 'MIME-Version: 1.0';

		if ( $this->html ) {
			$headers[] = 'Content-type:text/html;charset=UTF-8';
		}

		if ( $this->email && Core::status( 1 ) ) {
			$headers[] = "Reply-To: {$this->email} <{$this->email}>";
		}

		if ( ! empty( $this->details['bcc_email'] ) ) {
			$headers[] = "Bcc: {$this->details['bcc_email']}";
		}

		$args['headers'] = $headers;

		Log::write( 'email-headers', $headers );

		return apply_filters( 'wprequal_email_headers', $args );

	}

	/**
	 * To Email
	 *
	 * This is where we send the entry email.
	 *
	 * @since 5.0
	 * @since 5.0.10 Call with filter hook.
	 *
	 * @return string The email address to send the entry.
	 */

	public function to_email() {

		$to_email = $this->details['to_email'];

		$to_email = empty( $to_email ) ? Core::default_to_email() : $to_email;

		Log::write( 'to-email', $to_email );

		return apply_filters( 'wprequal_to_email', $to_email );

	}

	/**
	 * From Email
	 *
	 * This is where we send the entry email from.
	 *
	 * @since 6.2.12
	 *
	 * @return string $from_email The email address to send the entry from.
	 */

	public function from_email() {

		$from_email = 'leads@wprequal.com';

		if ( ! empty( $this->details['from_email'] ) ) {
			$from_email = $this->details['from_email'];
		}

		Log::write( 'from-email', $from_email );

		return apply_filters( 'wprequal_from_email', $from_email );

	}

	/**
	 * From Name.
	 *
	 * @return mixed|void
	 */

	public function from_name() {

		$from_name = 'WPrequal Leads';

		if ( ! empty( $this->details['from_name'] ) ) {
			$from_name = $this->details['from_name'];
		}

		Log::write( 'from-name', $from_name );

		return apply_filters( 'wprequal_from_name', $from_name );

	}

	/**
	 * Gateway
	 *
	 * The address to send the text message.
	 *
	 * @since 5.1
	 *
	 * @return string $gateway The carrier gateway. Or bool false if empty.
	 */

	public function gateway() {

		$gateway = $this->details['sms_text'];

		Log::write( 'sms-gateway', $gateway );

		return empty( $gateway ) ? false : $gateway;

	}

	/**
	 * Log Mailer Error
	 *
	 * Log errors if any occur.
	 *
	 * @since 6.2.12
	 *
	 * @param object $wp_error The wp_error object.
	 */

	public function log_mailer_error( $wp_error ) {

		Log::write( $this->log_key, $wp_error );

	}
	
}