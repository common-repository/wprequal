<?php
/**
 * Log
 *
 * Add logs for plugin actions
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class Log {

	/**
	 * Write the log to the file.
	 *
	 * @param $key
	 * @param $data
	 */
	public static function write( $key, $data ) {

		if ( Settings::is_checked( 'wprequal_allow_logging' ) ) {

			$msg        = self::message( $data );
			$error_file = WPREQUAL_ERROR_LOG_DIR . "{$key}.log";

			// Create the WPrequal log file dir if we do not already have one.
			if ( ! file_exists( WPREQUAL_ERROR_LOG_DIR ) ) {
				mkdir( WPREQUAL_ERROR_LOG_DIR, 0755, TRUE );
			}

			if ( ! file_exists( $error_file ) ) {
				fopen( $error_file, 'w' );
			}

			error_log( $msg, 3, $error_file );

		}

	}

	/**
	 * Message for  the log.
	 *
	 * @param $data
	 *
	 * @return string
	 */
	public static function message( $data ) {

		ob_start();

		$date = date( "F j, Y, g:i a" );

		echo "[{$date}] - ";

		if ( is_array( $data ) || is_object( $data ) ) {
			print_r( $data );
		} else {
			echo $data;
		}

		echo "\n";
		echo '__________________________________________________________________________';
		echo "\n";

		return ob_get_clean();

	}

}