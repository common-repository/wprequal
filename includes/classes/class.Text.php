<?php
/**
 * WPrequal Text Class
 *
 * @since 5.1
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}
 
class Text extends Mail {

	/**
	 * @var bool
	 */

	protected $html  = FALSE;

	/**
	 * @var bool
	 */

	protected $email = FALSE;

	/**
	 * @var int
	 */

	private $lead_id;

	/**
	 * @var Text
	 */

	public static $instance;

	/**
	 * Text constructor.
	 *
	 * @param $lead_id
	 */

	public function __construct( $lead_id ) {

		parent::__construct( $lead_id );

		$this->lead_id = $lead_id;

		self::$instance = $this;

	}

	/**
	 * Instance.
	 *
	 * @param $lead_id
	 *
	 * @return Text
	 */

	public static function instance( $lead_id ) {

		if ( self::$instance === null ) {
			self::$instance = new self( $lead_id );
		}

		return self::$instance;

	}

	/**
	 * Send Text
	 *
	 * @since 5.1 
	 */

	public function send_text() {

		if ( $gateway = $this->gateway() ) {

			add_action( 'wp_mail_failed', array( $this, 'log_mailer_error' ), 10, 1 );
			add_filter( 'wp_mail', array( $this, 'email_headers' ) );

			$subject = 'New WPrequal Lead';
			$msg 	 = 'Please contact your new lead ASAP: ';

			$contact = get_post_meta( $this->lead_id, 'contact', TRUE ) ?: array();
			$fields  = get_post_meta( $this->lead_id, 'fields', TRUE )  ?: array();

			$data    = array_merge( $contact, $fields );
			$content = $msg . join( '|', $data );

			Log::write( 'wprequal-text', 'Attempt: ' . $gateway );
			Log::write( 'wprequal-text', $content );

			if ( ! wp_mail( $gateway, $subject, $content ) ) {
				Log::write( 'wprequal-text', 'Failed: ' . $gateway );
			}

			remove_filter ( 'wp_mail', array( $this, 'email_headers' ) );

		}

	}

}
