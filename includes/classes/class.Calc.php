<?php
/**
 * Calc Class
 *
 * @since 4.0.0
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class Calc {

	/**
	 * @var Calc
	 */

	public static $instance;

	/**
	 * Calc constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Instance.
	 *
	 * @return Calc
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Action Hooks
	 */

	public function actions() {

		add_action( 'wprequal_calc', array( $this, 'calc' ), 10, 3 );
		add_action( 'wprequal_calc_button', array( $this, 'calc_button' ) );
		add_action( 'wprequal_amortize', array( $this, 'amortize_calc' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'localize_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'inline_styles' ), 999 );

		add_shortcode( 'wprequal_calc', array( $this, 'calc_shortcode' ) );
		add_shortcode( 'wprequal_calc_button', array( $this, 'calc_button_shortcode' ) );
		add_shortcode( 'wprequal_amortize', array( $this, 'amortize' ) );

	}

	/**
	 * Localize Scripts
	 */

	public function localize_scripts() {

		wp_localize_script( 'wprequal_js', 'wprequalCalc', array(
			'loanTermType' => $this->term_type(),
		));

		wp_localize_script( 'wprequal_js', 'wprequal_calc_popup', array(
			'popupCalc' => $this->load_calc(),
		));

		wp_localize_script( 'wprequal_js', 'Amortize', $this->amortize_settings() );

	}

	/**
	 * Enqueue Inline Styles.
	 */

	public function inline_styles() {
		wp_add_inline_style( 'wprequal_app_min', $this->styles() );
	}

	/**
	 * Amortize calc settings.
	 *
	 * @return array
	 */

	public function amortize_settings() {

		$principal_color  = get_option( 'wprequal_amortize_principal_color', '#3cba9f' );
		$principal_border = get_option( 'wprequal_amortize_principal_border', '#379a7f' );
		$principal_label  = get_option( 'wprequal_amortize_principal_label', 'Principal' );

		$interest_color  = get_option( 'wprequal_amortize_interest_color', '#3e95cd' );
		$interest_border = get_option( 'wprequal_amortize_interest_border', '#366e9e' );
		$interest_label  = get_option( 'wprequal_amortize_interest_label', 'Interest' );

		return array(
			'backgroundColor' => array(
				empty( $principal_color ) ? '#3cba9f' : $principal_color,
				empty( $interest_color ) ? '#3e95cd' : $interest_color
			),
			'borderColor'     => array(
				empty( $principal_border ) ? '#379a7f' : $principal_border,
				empty( $interest_border ) ? '#366e9e' : $interest_border
			),
			'labels'          => array(
				empty( $principal_label ) ? 'Principal' : $principal_label,
				empty( $interest_label ) ? 'Interest'  : $interest_label
			)
		);

	}

	/**
	 * Find and include the view file.
	 *
	 * @param        $view
	 * @param bool   $key
	 * @param string $size
	 */

	public static function view( $view, $key = FALSE, $size = '', $shade = '' ) {

		view( 'calc', $view, [
			'option' => $key ? get_option( $key ) : [],
			'size'   => $size,
			'shade'  => $shade
		]);

	}

	/**
	 * Get the view for the calc.
	 *
	 * @param string $template
	 * @param string $size
	 * @param string $shade
	 */

	public function calc( $template = 'calc', $size = '', $shade = '' ) {

		wp_enqueue_script( 'wprequal_js' );

		self::view( $template, FALSE, $size, $shade );

	}

	/**
	 * WPrequal Calc Button
	 *
	 * Button to popup the mortgage calc.
	 *
	 * @since 4.0.0
	 */

	public function calc_button() {

		add_action( 'wp_footer', array( $this, 'calc_popup' ) );

		self::view( 'button' );

	}

	/**
	 * Load Calc
	 *
	 * Load the JS settings for Calc Popup
	 *
	 * @since 6.0
	 *
	 * @return array
	 */

	public function load_calc() {

		return array(
			'items'    => array(
				'src'  => '.calc-hide .wprequal-calc',
				'type' => 'inline'
			),
			'closeOnBgClick' => TRUE,
			'showCloseBtn'   => FALSE
		);

	}

	/**
	 * WPrequal Calc Popup
	 *
	 * Display for the Calc
	 *
	 * @since 4.0.0
	 */

	public function calc_popup() { ?>
		<!-- Start Calc Popup Section -->
		<div class="calc-hide"><?php
			$this->calc(); ?>
		</div>
		<!-- End Calc Popup Section --><?php
	}

	/**
	 * WPrequal Calc Shortcode
	 *
	 * Display for the Calc with a shortcode.
	 *
	 * @since 4.1.0
	 */

	public function calc_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'template' => 'calc',
			'size'     => '',
			'shade'    => ''
		), $atts, 'wprequal_calc' );

		ob_start(); ?>
		<!-- Start Calc Shortcode Section --><?php
		$this->calc( $atts['template'], $atts['size'], $atts['shade'] ); ?>
		<!-- End Calc Shortcode Section --><?php
		return ob_get_clean();
	}

	/**
	 * WPrequal Calc Button Shortcode
	 *
	 * Display for the Calc Button with a shortcode.
	 *
	 * @since 4.1.0
	 */

	public function calc_button_shortcode( $atts ) {

		$a = shortcode_atts( array(
			'align' => 'center'
		), $atts );

		ob_start(); ?>
		<!-- Start Calc Button Shortcode Section -->
		<div class="calc-button-shortcode <?php esc_attr_e( $a['align'] ); ?>"><?php
			$this->calc_button(); ?>
		</div>
		<!-- End Calc Button Shortcode Section --><?php
		return ob_get_clean();
	}

	/**
	 * Shortcode
	 *
	 * Display the amortize form
	 *
	 * @since 6.3
	 */

	public function amortize() {

		ob_start();

		$this->amortize_calc();

		return ob_get_clean();

	}

	/**
	 * Amortize Calc
	 *
	 * Display the amortize form
	 *
	 * @since 6.3
	 */

	public function amortize_calc() {

		wp_enqueue_script( 'wprequal_js' );

		self::view( 'amortize' );

	}

	/**
	 * Currency
	 *
	 * @param string $content
	 */

	public static function currency( $content = '' ) {

		if ( $currency = get_option( 'wprequal_currency' ) ) {

			$symbol   = self::symbol( $currency );
			$position = get_option( 'wprequal_currency_position', 'before' );
			$content  = ( 'after' === $position ) ? "{$content}{$symbol}" : "{$symbol}{$content}";

		}

		echo apply_filters( 'wprequal_calculator_currency_output', $content );

	}

	/**
	 * Get the currency symbol.
	 *
	 * @param $curreny
	 *
	 * @return mixed|string
	 */

	public static function symbol( $currency ) {

		$symbols = Core::currency_symbols();

		return empty( $symbols[$currency] ) ? '$' : $symbols[$currency];

	}

	/**
	 * Ranges
	 *
	 * Set the ranges to 1 if no value is returned.
	 *
	 * @param $key
	 */

	public static function ranges( $key ) {

		$min   = get_option( "wprequal_{$key}_min", 1 );
		$max   = get_option( "wprequal_{$key}_max", 1 );
		$step  = get_option( "wprequal_{$key}_step", 1 );
		$value = get_option( "wprequal_{$key}_default", 1 );

		echo ' min="' . $min . '" max="' . $max . '" value="' . $value . '" step="' . $step . '" ';

	}

	/**
	 * Calculator inline styles.
	 *
	 * @return string
	 */

	public function styles() {

		$css = '';

		if( $background_color = get_option( 'wprequal_focus_calc_background_color' ) ) {


			if ( ! empty( $background_color ) ) {

				$css .= "
					.wprequal-calc.focus .wpq-results {
                        background-color: $background_color;
                    }
                ";

			}

		}

		if( $button_color = get_option( 'wprequal_get_quote_button_color' ) ) {


			if ( ! empty( $button_color ) ) {

				$css .= "
					.wprequal-calc .get-quote .button {
                        background-color: $button_color;
                    }
                ";

			}

		}

		if( $font_color = get_option( 'wprequal_get_quote_button_font_color' ) ) {


			if ( ! empty( $font_color ) ) {

				$css .= "
					.wprequal-calc .get-quote .button {
                        color: $font_color;
                    }
                ";

			}

		}

		if( $button_hover = get_option( 'wprequal_get_quote_button_hover_color' ) ) {


			if ( ! empty( $button_hover ) ) {

				$css .= "
					.wprequal-calc .get-quote .button:hover {
                        background-color: $button_hover;
                    }
                ";

			}

		}

		if( $hover_font_color = get_option( 'wprequal_get_quote_button_hover_font_color' ) ) {


			if ( ! empty( $hover_font_color ) ) {

				$css .= "
					.wprequal-calc .get-quote .button:hover {
                        color: $hover_font_color;
                    }
                ";

			}

		}

		return $css;

	}

	/**
	 * The type of loan term.
	 *
	 * @return int
	 */

	public function term_type() {

		if ( $loan_term_type = get_option( 'wprequal_term_type' ) ) {
			return ( 'months' === $loan_term_type ) ? 12 : 1;
		}

		return 12;

	}

}