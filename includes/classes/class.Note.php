<?php
/**
 * Note Class
 *
 * @since 5.0.3
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class Note {

	/**
	 * @var Note
	 */

	public static $instance;

	/**
	 * Note constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * @return Note
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
	 * @since 5.0.3
	 */
	 
	public function actions() {
		add_action( 'add_meta_boxes', array( $this, 'add_note_metabox' ) );
		add_action( 'wp_ajax_new_note_ajax', array( $this, 'new_note_ajax' ) );
		add_filter( 'pretty_date', array( $this, 'pretty_date' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * Scripts
	 *
	 * @since 5.0.3
	 */

	public function scripts(){

		wp_localize_script( 'wprequal_admin_js', 'note', array(
			'endpoint' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'note_secure_me' )
		));

	}

	/** 
	 *	Add note Metabox
	 *
	 *	Add metaboxes.
	 *
	 *	@since 5.0.3
	 *	
	 *	@link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */

	public function add_note_metabox() {
		
		add_meta_box( 
			'note_meta', 
			__( 'Notes', 'wprequal' ),
			array( $this, 'note_metabox'), 
			'wprequal_lead',
			'advanced',
            'default'
		);
		
	}

	/** 
	 *	Note Body
	 *
	 *  The body of the note in note metabox.
	 *
	 *	@since 5.0.3
	 */

	public function note_body( $date, $text ) { 

		$pretty_date = apply_filters( 'pretty_date',  $date );

		ob_start(); ?>

		<li>
			<i class="far fa-check-circle new-note-check"></i>
			<span class="note-text-date"><?php printf( __( '%s' ), $pretty_date ); ?></span>
			<span class="note-text"><?php printf( __( '%s' ), $text ); ?></span>
		</li><?php

		return ob_get_clean(); 

	}

	/** 
	 *	New note Ajax
	 *
	 *  Stores the new note in the db. Returns a respose to jQuery.
	 *
	 *	@since 5.0.3
	 */

	public function new_note_ajax() {
		
		check_ajax_referer( 'note_secure_me', 'note_nonce' );
		
		$input = $_REQUEST['input'];

		$text = sanitize_text_field( $input['text'] );

		$lead_id = intval( $input['lead_id'] );
		
		if( is_int( $lead_id ) && ! empty( $text ) ) {

			$date = $this->add_note( $lead_id, $text );
			$note = '<span class="new-note">' . stripslashes( $this->note_body( $date, $text ) ) . '</span>';

			$response = array(
				'class' 	=> 'fa-check-circle',
				'note'		=> $note,
				'success'	=> true
			);

		} else {
			
			$response = array(
				'class' 	=> 'fa-exclamation-triangle',
				'note'		=> false,
				'success'	=> false
			);
			
		}
		
		echo json_encode( $response );

		die();
		
	}

	/** 
	 *	Add Note
	 *
	 *  Stores the new note in the db.
	 *
	 *	@since 5.0.3
	 */

	public static function add_note( $lead_id, $text ) {

		$date = current_time( 'mysql' );

		$note = (object) array(
			'date' 	=> $date,
			'text'	=> $text
		);

		add_post_meta( $lead_id, 'note', $note );

		return $date;
		
	}

	/** 
	 *	note Metabox
	 *
	 *	@since 5.0.3
	 */

	public function note_metabox() {

		wp_enqueue_script( 'wprequal_admin_js' );

		$lead_id = get_the_ID(); ?>

		<ul id="wprequal-note-wrapper"><?php

			if( $note = get_post_meta( $lead_id, 'note' ) ) { 

				krsort( $note ); 
				
				foreach( $note as $note ) { 

					$note =  $this->note_body( $note->date, $note->text );

					printf( esc_html__( '%s' ), $note );

				}

			} ?>

		</ul>

		<table class="form-table note_meta views_meta">
			
			<tbody>

				<tr>
					<th colspan="4" scope="row"><label for="new-note-text"><?php _e( 'New note:', 'wprequal' ) ; ?></label></th>
				</tr>

				<tr>
					<td colspan="4"><textarea id="new-note-text" class="large-text"></textarea></td>
				</tr>

				<tr>
					<td colspan="4">
						<div class="button button-primary add-note">
							<i class="fal fa-plus-square new-note-spinner"></i> 
							<?php _e( 'Save note', 'wprequal' ) ; ?>
						</div>
					</td>
				</tr>
				
			</tbody>

		</table>

		<input type="hidden" id="lead_id" value="<?php printf( __( '%s' ), $lead_id ); ?>"><?php

	}

	/**
	 * Pretty Date
	 *
	 * The date in a defined pretty format.
	 *
	 * @since 5.0.3
	 *
	 * @param  mixed  $input A text timestamp. Or, an int PHP time.
	 * @param  bool   $gmt   True to add the gmt offset. False to not add gmt offset.
	 * @return string $date  The formatted date.
	 */

	public function pretty_date( $input, $gmt = false ) {
		
		$offset = $gmt ? get_option( 'gmt_offset' ) * 3600 : 0;

		$time = is_int( $input ) ? $input : strtotime( $input );

		$date = ! $time ? '-' : date( 'M j, Y, g:i a', $time + ( $offset ) ); 

		return $date;

	}

}
