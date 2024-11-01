<?php
/**
 * Email Class
 *
 * @since 5.0
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}
 
class Email extends Mail {

	/**
	 * @var bool
	 */

	protected $html = TRUE;

	/**
	 * @var string
	 */

	protected $log_key = 'wprequal-email-error';

	/**
	 * @var string
	 */

	protected $email;

	/**
	 * @var int
	 */

	private $lead_id;

	/**
	 * @var Email
	 */

	public static $instance;

	/**
	 * Email constructor.
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
	 * @return Email
	 */

	public static function instance( $lead_id ) {

		if ( self::$instance === null ) {
			self::$instance = new self( $lead_id );
		}

		return self::$instance;

	}

	/**
	 * Send Email
	 *
	 * @since 5.0
	 * @since 5.0.12 Add action hook for Premium Button.
	 * @since 5.1    Use wp_mail(). Filter email headers.
	 */

	public function send_email() {
		
		add_action( 'wp_mail_failed', [ $this, 'log_mailer_error' ], 10, 1 );
		add_filter( 'wp_mail', [ $this, 'email_headers' ] );

		$contact     = get_post_meta( $this->lead_id, 'contact', TRUE );
		$this->email = $contact['email'];

		ob_start();

		view( 'email', 'notification', [
			'contact'    => $contact,
			'fields'     => get_post_meta( $this->lead_id, 'fields', TRUE ),
			'labels'     => get_post_meta( $this->lead_id, 'field_labels', TRUE ),
			'source_url' => get_post_meta( $this->lead_id, 'source_url', TRUE ),
			'logo'       => WPREQUAL_ASSETS .  'img/wprequal-logo.png'
		]);

		$msg      = ob_get_clean();
		$to_email = $this->to_email();

		if ( wp_mail( $to_email, 'New WPrequal Lead', $msg ) ) {
			Log::write( 'email-msg', $msg  );
		}

		remove_filter ( 'wp_mail', [ $this, 'email_headers' ] );

	}

}
