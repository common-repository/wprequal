<?php
/**
 * SurveyForm
 *
 * @since 2.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class SurveyForm extends PostType {

	/**
	 * Path to survey form views.
	 */
	const view_path = 'survey/form';

	/**
	 * @var string
	 */

	protected $post_type = 'wpq_survey_form';

	/**
	 * @var string
	 */

	protected $label_name = 'Survey Form';

	/**
	 * @var string
	 */

	protected $label_names = 'Survey Forms';

	/**
	 * @var string
	 */

	protected $menu_label = 'Survey Forms';

	/**
	 * @var string
	 */

	protected $menu_icon = FALSE;

	/**
	 * @var array
	 */

	protected $supports = array( 'title' );

	/**
	 * @var bool
	 */

	protected $show_in_menu = WPREQUAL_OPTIONS;

	/**
	 * @var array
	 */

	protected $taxonomies = array();

	/**
	 * @var SurveyForm
	 */

	public static $instance;

	/**
	 * SurveyForm constructor.
	 */

	public function __construct() {

		parent::__construct();

		self::$instance = $this;

	}

	/**
	 * Instance.
	 *
	 * @return SurveyForm
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

		add_action( 'wp_head', array( $this, 'fa_script' ) );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'localize_scripts' ) );
		add_action( 'wp_footer', array( $this, 'popup_form' ) );
		add_action( 'wprequal_survey_form', array( $this, 'form' ), 10, 1 );
		add_action( 'wprequal_survey_form_button', array( $this, 'button' ), 10, 2 );

		add_shortcode( 'wprequal', array( $this, 'survey_form' ) );
		add_shortcode( 'wprequal_survey_form_button', array( $this, 'button_shortcode' ) );

	}

	/**
	 * Localize Scripts
	 */

	public function localize_scripts() {

		wp_localize_script( 'wprequal_js', 'surveyForm', array(
			'forms'            => array(),
			'slide'            => $this->view( self::view_path, 'slide' ),
			'icon'             => $this->view( self::view_path, 'icon' ),
			'button'           => $this->view( self::view_path, 'button' ),
			'text'             => $this->view( self::view_path, 'text' ),
			'amount'           => $this->view( self::view_path, 'amount' ),
			'contact'          => $this->view( self::view_path, 'contact' ),
			'processing'       => $this->view( self::view_path, 'processing' ),
			'confirmation'     => $this->view( self::view_path, 'confirmation' ),
			'currency_symbols' => Core::currency_symbols()
		));

		wp_localize_script( 'wprequal_js', 'wprequal_popup', array(
			'delay'     => $this->delay(),
			'popupForm' => $this->popup_form(),
			'cookie'    => $this->get_cookie()
		));

	}

	/**
	 * Get the contact form for contact this slide.
	 *
	 * @param $slide
	 *
	 * @return false|string
	 */

	public function contact_form( $slide ) {

		$slide['type'] = 'survey';

		ob_start();
		do_action( 'wprequal_contact_form', $slide );
		return ob_get_clean();

	}

	/**
	 * Font Awesome kits URL script.
	 */

	public function fa_script() {

		if ( Settings::is_checked( 'wprequal_fa_pro' ) ) {

			if ( $kits_url = $this->fa_kits_url() ) {
				echo '<script src="' . esc_url( $kits_url ) . '" crossorigin="anonymous"></script>';
			}

		}

	}

	/**
	 * Font Awesome Kits URL.
	 *
	 * @return bool|mixed|void
	 */

	public function fa_kits_url() {

		// By defining this, We allow developers to use their own Font Awesome Pro license URL.
		// To protect the developers kits URL. This does not show up in the input in the WPrequal settings.
		$kits_url = defined( 'WPREQUAL_FA_KITS_URL' ) ? WPREQUAL_FA_KITS_URL : get_option( 'wprequal_fa_kits_url' );

		return empty( $kits_url ) ? FALSE : $kits_url;

	}

	/**
	 * Survey form
	 *
	 * @param $post_id
	 */

	public function form( $post_id ) {

		$post_id = empty( $post_id ) ? $this->default_id() : $post_id;
		$rand    = mt_rand();
		$form_id = "wpq-{$rand}";

		view( self::view_path, 'form', [
			'post_id'   => $post_id,
			'form'      => [
				'form_id' => $form_id,
				'slides'  => $this->get_slides( $post_id, TRUE )
			],
			'back_text' => get_post_meta( $post_id, 'back_text', TRUE ) ?: '',
			'styles'    => $this->styles( $post_id, $form_id ),
			'classes'   => $this->classes( $post_id )
		] );

	}

	/**
	 * View.
	 *
	 * @param      $path
	 * @param      $view
	 *
	 * @return string
	 */

	public function view( $path, $view ) {

		ob_start();
		view( $path, $view );
		return ob_get_clean();

	}

	/**
	 * Get the slides for this post and index the keys if requested.
	 *
	 * @param      $post_id
	 * @param bool $index
	 *
	 * @return array|mixed
	 */

	public function get_slides( $post_id, $index = FALSE ) {

		if ( $slides = get_post_meta( $post_id, 'slides', TRUE ) ) {

			if ( $index ) {

				$indexed = array();

				foreach( $slides as $key => $slide ) {

					if ( 'contact' === $slide['type'] ) {
						$slide['contact_form'] = $this->contact_form( $slide );
					}

					$indexed[] = $slide;

				}

				return $indexed;

			}

			return $slides;
		}

		return array();

	}

	/**
	 * Default post ID for survey form.
	 *
	 * This is to offer a default ID when no ID is provided.
	 *
	 * @return mixed
	 */

	public function default_id() {

		$post_ids = get_posts(
			array(
				'items_per_page' => 1,
				'post_type'      => $this->post_type,
				'fields'         => 'ids',
				'orderby'        => 'ID',
				'order'          => 'ASC'
			)
		);

		return $post_ids[0];

	}

	/**
	 * Survey Form
	 *
	 * Display WPrequal body using [wprequal] shortcode.
	 *
	 * @since 1.1
	 *
	 * @param array $atts Array of values defined in shortcode.
	 */

	public function survey_form( $atts ) {

		$post_id = isset( $atts['post_id'] ) ? $atts['post_id'] : FALSE;

		ob_start();
		$this->form( $post_id );
		return ob_get_clean();

	}

	/**
	 * Popup
	 *
	 * Display WPrequal body as a popup.
	 *
	 * @since 3.0.0
	 * @since 6.2.9 Add style attribute to hide form for extended popup delay
	 */

	public function popup() {

		$post_id = $this->popup_id();

		ob_start();
		$this->form( $post_id );
		$form = ob_get_clean();

		view( self::view_path, 'popup', [
			'form' => $form
		]);

	}

	/**
	 * Survey form id for the popup.
	 *
	 * @return int
	 */

	public function popup_id() {

		if ( ( is_page() || is_single() ) && get_post_meta( get_the_ID(), 'wpq_survey_form', TRUE ) ) {

			$post_id = get_post_meta( get_the_ID(), 'wpq_survey_form', TRUE );

		} else {

			$post_id = get_option( 'wprequal_popup_post_id' );

		}

		if ( ! empty( $post_id ) && get_post_status( $post_id ) && $this->post_type === get_post_type( $post_id ) ) {
			return (int) $post_id;
		}

		return $this->default_id();

	}

	/**
	 * Classes
	 *
	 * @return string
	 */

	public function classes( $post_id ) {

		$classes   = array();
		$classes[] = 'wprequal-form';
		$classes[] = 'survey-form';
        $classes[] = "wpq-{$post_id}";

		$styles = get_post_meta( $post_id, 'styles', TRUE );

		if ( ! empty( $styles['theme'] ) ) {
			$classes[] = $styles['theme'];
		}

		return join( ' ', $classes );

	}

	/**
	 * Styles for the survey form.
	 *
	 * @param $post_id
	 * @param $form_id
	 *
	 * @return string
	 */

	public function styles( $post_id, $form_id ) {

		$css = '';

		if( $styles = get_post_meta( $post_id, 'styles', TRUE ) ) {

			extract( $styles );

			if ( ! empty( $icon_color ) ) {

				$css .= "
					#{$form_id}.survey-form.default .slide.icons i,
					#{$form_id}.survey-form.default-dark .slide.icons i,
					#{$form_id}.survey-form.realistic .slide.icons i,
					#{$form_id}.survey-form.authentic .slide.icons i {
                        color: $icon_color !important;
                    }
                ";

				$css .= "
                    #{$form_id}.survey-form.passage .slide.icons .icon-button,
                    #{$form_id}.survey-form.precise .slide.icons .icon-button{
                        color: #FFFFFF;
                    }
                ";

				$css .= "
                    #{$form_id}.survey-form.perimeter .slide.icons .icon-button i,
                    #{$form_id}.survey-form.circumference .slide.icons .icon-button i{
                        border-color: $icon_color !important;
                        color: $icon_color !important;
                    }
                ";

				$css .= "
				    #{$form_id}.survey-form.impress .slide.icons i,
                    #{$form_id}.survey-form.passage .slide.icons .icon-button,
                    #{$form_id}.survey-form.precise .slide.icons .icon-button,
                    #{$form_id}.survey-form.disk .slide.icons .icon-button i {
                        background-color: $icon_color;
                    }
                ";

				$css .= "
                    #{$form_id}.survey-form.sphere .slide.icons i {
                        color: #FFFFFF;
                        background: radial-gradient(circle at 60px 60px, $icon_color 55.46%, #333);
                    }
                ";
			}

			if ( ! empty( $icon_hover ) ) {

				$css .= "
					#{$form_id}.survey-form.default .slide.icons i:hover,
					#{$form_id}.survey-form.default-dark .slide.icons i:hover,
					#{$form_id}.survey-form.authentic .slide.icons .icon-button:hover i,
					#{$form_id}.survey-form.passage .slide.icons .icon-button:hover i,
					#{$form_id}.survey-form.realistic .slide.icons .icon-button i:hover,
					#{$form_id}.survey-form.precise .slide.icons .icon-button:hover i {
                        color: $icon_hover !important;
                    }
                ";

				$css .= "
					 #{$form_id}.survey-form.impress .slide.icons i:hover,
					 #{$form_id}.survey-form.disk .slide.icons i:hover {
                        background-color: $icon_hover;
                    }
                ";

				$css .= "
					#{$form_id}.survey-form.passage .slide.icons .icon-button:hover,
					#{$form_id}.survey-form.precise .slide.icons .icon-button:hover {
                        background-color: floralwhite;
                    }
                ";

				$css .= "
                    #{$form_id}.survey-form.sphere .slide.icons i:hover {
                        background: radial-gradient(circle at 60px 60px, $icon_hover, #333);
                    }
                ";

				$css .= "
                    #{$form_id}.survey-form.perimeter .slide.icons .icon-button i:hover,
                    #{$form_id}.survey-form.circumference .slide.icons .icon-button i:hover{
                        border-color: $icon_hover !important;
                        color: $icon_hover !important;
                    }
                ";

			}

			if ( ! empty( $button_color ) ) {

				$css .= "
                     #{$form_id}.survey-form .slide .amount .range-slider::-webkit-slider-thumb,
                     #{$form_id}.survey-form .slide .amount .range-slider::-ms-thumb { 
                        background: $button_color;
                        background-color: $button_color;
                    }
                ";

				$css .= "
                    #{$form_id}.survey-form .slide .button { 
                        background: $button_color;
                        background-color: $button_color;
                    }
                ";

				$css .= "
                     #{$form_id}.survey-form .slide .amount .range-slider::-webkit-slider-thumb { 
                        background: $button_color;
                        background-color: $button_color;
                    }
                ";

				$css .= "
                     #{$form_id}.survey-form .slide .amount .range-slider::-ms-thumb { 
                        background: $button_color;
                        background-color: $button_color;
                    }
                ";

				$css .= "
                     #{$form_id}.survey-form .slide .amount .range-slider::-moz-range-thumb { 
                        background: $button_color !important;
                        background-color: $button_color;
                    }
                ";

			}

			if ( ! empty( $button_hover ) ) {

				$css .= "
                    #{$form_id}.survey-form .slide .button:hover { 
                        background: $button_hover;
                        background-color: $button_hover;
                    }
                ";

			}

			if ( ! empty( $button_text_color ) ) {

				$css .= "
                    #{$form_id}.survey-form .slide .button { 
                        color: $button_text_color;
                    }
                ";

			}

		}

		return $css;

	}

	/**
	 * Should we load the survey form?
	 *
	 * @return bool
	 */

	public function load() {

		if ( isset( $_COOKIE[Core::cookie_name()] ) ) {
			return FALSE;
		}

		if ( ( is_single() || is_page() ) ) {

			if ( $survey_id = get_post_meta( get_the_ID(), 'wpq_survey_form', TRUE ) ) {

				if ( 0 < intval( $survey_id ) && $this->post_type === get_post_type( $survey_id ) ) {
					return TRUE;
				}

			}

		}

		if ( is_front_page() && Settings::is_checked( 'wprequal_show_home' ) ) {
			return TRUE;
		}

		if ( is_page() && Settings::is_checked( 'wprequal_show_page' ) ) {

			if ( $page_on_front = get_option( 'page_on_front' ) ) {

				if ( is_page( $page_on_front ) ) {
						return FALSE;
				}

			}

			return TRUE;

		}

		if ( is_single() && Settings::is_checked( 'wprequal_show_post' ) ) {
			return TRUE;
		}


		return FALSE;

	}

	/**
	 * Get cookie.
	 *
	 * @return bool|string
	 */

	public function get_cookie() {

		if( Settings::is_checked( 'wprequal_force' ) ) {
			return FALSE;
		}

		if ( $this->load() ) {

			$between = get_option( 'wprequal_between', 0 );
			$expires = time() + ( 60 * intval( $between ) );

			return Core::cookie( $expires, 'loaded' );

		}

		return FALSE;

	}

	/**
	 * Popup Form.
	 *
	 * @return array|bool
	 */

	public function popup_form() {

		if ( $this->load() ) {

			add_action( 'wp_footer', array( $this, 'popup' ) );

			$force = get_option( 'wprequal_force' ) ? FALSE : TRUE;

			return array(
				'items' => array(
					'src'  => '.wprequal-form-popup .wprequal-form',
					'type' => 'inline'
				),
				'closeOnBgClick' => $force,
				'showCloseBtn'   => FALSE
			);

		}

		return FALSE;

	}

	/**
	 * Delay
	 *
	 * Seconds to delay form popup
	 *
	 * @since 6.0
	 *
	 * @return int
	 */

	public function delay() {
		$delay = get_option( 'wprequal_delay', 0 );
		return intval( $delay ) * 1000;
	}

	/**
	 * Button.
	 *
	 * @param $post_id
	 * @param $instance
	 */

	public function button( $post_id, $args ) {

		$args = [
			'post_id' => $post_id,
			'args'    => $args
		];

		view( self::view_path, 'popup-button', $args );

	}

	/**
	 * Button shortcode.
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */

	public function button_shortcode( $atts ) {

		$args = shortcode_atts( array(
			'post_id' => FALSE,
			'text'    => __( 'Popup Form', 'wprequal' ),
			'align'   => 'none'
		), $atts, 'wprequal_survey_form_button' );

		ob_start();
		$this->button( $args['post_id'], $args );
		return ob_get_clean();

	}

    /**
     * Get Form
     *
     * @param $file_name
     *
     * @return array|mixed
     */

    public static function get_settings( $file_name ) {

        $file = WPREQUAL_ASSETS_PATH . "json/$file_name.json";

        if ( file_exists( $file ) ) {

            $json  = file_get_contents( $file );
            $data  = json_decode( $json, TRUE );
            $value = Core::sanitize_array( $data );

           return $value;

        }

        return false;

    }
	
}