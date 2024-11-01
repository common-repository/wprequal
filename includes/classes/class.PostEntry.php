<?php
/**
 * Post Entry Class
 *
 * @since 1.0
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class PostEntry {

	use Api;

	/**
	 * @var PostEntry
	 */

	public static $instance;

	/**
	 * PostEntry constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * @return PostEntry
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Post the entry to Connect API.
	 *
	 * @param $lead_id
	 */

	public function post( $lead_id ) {

		$url  = 'https://wprequal.com/wp-json/wprequal-main/1.0/integration/';
		$args = $this->get_args();

        $args['blocking']       = FALSE;
        $args['body']['entry']  = $this->get_entry( $lead_id );

		wp_remote_post( $url, $args );

	}

	/**
	 * Get the entry data.
	 *
	 * @param $lead_id
	 *
	 * @return array
	 */

	public function get_entry( $lead_id ) {

		$entry = get_post_meta( $lead_id, 'contact', TRUE );

		if ( $terms = get_the_terms( $lead_id, 'param_referrer' ) ) {
			$entry['referrer'] = $terms[0]->name;
		}

		elseif ( $terms = get_the_terms( $lead_id, 'social_referrer' ) ) {
			$entry['referrer'] = $terms[0]->name;
		}

		$entry['tags'] = get_post_meta( $lead_id, 'fields', TRUE );

		return apply_filters( 'wprequal_post_entry_data', $entry, $lead_id );

	}

}