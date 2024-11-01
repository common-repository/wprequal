<?php
/**
 * Options
 *
 * @since 7.7.9
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
    wp_die( __( 'Access denied!!', 'wprequal' ) );
}

abstract class Options {

    /**
     * Options.
     *
     * @return array
     */

    public function options() {

        return array_merge(
            $this->dashboard(),
            $this->referrer(),
            $this->fontawesome(),
            $this->calculator(),
            $this->amortize(),
            $this->popup(),
            $this->webhook(),
            $this->extensions()
        );

    }

    /**
     * Dashboard options.
     *
     * @return array[]
     */

    public function dashboard() {

        return apply_filters( 'wprequal_dashboard_settings', array(
            'wprequal_access_token'       => array(
                'label'    => __( 'Access Token', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'password',
                    'note'              => '',
                    'class'             => 'regular-text',
                    'placeholder'       => ''
                )
            ),
            'wprequal_default_to_email'   => array(
                'label'    => __( 'To Email (default)', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_email',
                    'type'              => 'email',
                    'note'              => 14000,
                    'class'             => 'regular-text',
                    'placeholder'       => ''
                )
            ),
            'wprequal_connect_active'     => array(
                'label'    => __( 'Activate Connect API', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'checkbox',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'note'              => ( ! Core::status( 2 ) ) ? 'connect-api' : 1000,
                    'class'             => 'no-class'
                )
            ),
            'wprequal_should_auto_update' => array(
                'label'    => __( 'Automatic Updates', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'checkbox',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'note'              => 12000,
                    'class'             => 'auto-update'
                )
            ),
            'wprequal_allow_logging'      => array(
                'label'    => __( 'Allow Error Logging', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'checkbox',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'class'             => 'no-class',
                    'note'              => 8000,
                )
            )
        ) );

    }

    /**
     * Referrer options.
     *
     * @return array[]
     */

    public function referrer() {

        return apply_filters( 'wprequal_referrer_settings', array(
            'wprequal_url_referrer_param'     => array(
                'label'    => __( 'URL Referrer Param', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : 2000,
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_url_referrer_cookie'    => array(
                'label'    => __( 'URL Referrer Cookie', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'number',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : 3000,
                    'class'             => 'small-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_social_referrer_cookie' => array(
                'label'    => __( 'Social Referrer Cookie', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'number',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : 3000,
                    'class'             => 'small-text',
                    'placeholder'       => '',
                )
            )
        ) );

    }

    /**
     * Fontawesome options.
     *
     * @return array[]
     */

    public function fontawesome() {

        return apply_filters( 'wprequal_fontawesome_settings', array(
            'wprequal_section_fa'      => array(
                'label'    => sprintf(
                    '%s <a href="%s" target="_blank">%s</a> %s',
                    __( 'Paid', 'wprequal' ),
                    esc_url( 'https://fontawesome.com/plans/' ),
                    esc_html( 'FontAwesome' ),
                    __( 'license required to use this feature', 'wprequal' )
                ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'heading',
                'args'     => array()
            ),
            'wprequal_fa_pro'          => array(
                'label'    => __( 'FontAwesome Pro', 'wprequal' ),
                'status'   => 1,
                'filter'   => true,
                'callback' => ( ! Core::status( 1 ) ) ? 'hidden' : 'checkbox',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'note'              => ( ! Core::status( 1 ) ) ? 'premium' : '',
                    'class'             => 'no-class'
                )
            ),
            'wprequal_fa_kits_url'     => array(
                'label'    => __( 'Pro Kits URL', 'wprequal' ),
                'status'   => 1,
                'filter'   => true,
                'callback' => ( ! Core::status( 1 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'url',
                    'note'              => ( ! Core::status( 1 ) ) ? 'premium' : '',
                    'class'             => 'regular-text',
                    'placeholder'       => 'https://kit.fontawesome.com/abc123456.js',
                )
            ),
            'wprequal_fa_icons_button' => array(
                'label'    => __( 'Pro Icon Search', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'hidden',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => 'pro-fa-icons',
                    'class'             => '',
                    'placeholder'       => '',
                )
            )
        ) );

    }

    /**
     * Calculator options.
     *
     * @return array
     */

    public function calculator() {

        return apply_filters( 'wprequal_calculator_settings', array(
            'wprequal_section_calc_general'              => array(
                'label'    => sprintf( '<h2>%s</h2>', __( 'General', 'wprequal' ) ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'heading',
                'args'     => array()
            ),
            'wprequal_calc_title'                        => array(
                'label'    => __( 'Calculator Title', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => '',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_currency'                          => array(
                'label'    => __( 'Currency', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'select',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'note'              => '',
                    'class'             => 'currency',
                    'values'            => 'currency_symbols',
                )
            ),
            'wprequal_currency_position'                 => array(
                'label'    => __( 'Currency Position', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'select',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'note'              => '',
                    'class'             => 'currency-position',
                    'values'            => (object) array(
                        'before' => __( 'Before Amount', 'wprequal' ),
                        'after'  => __( 'After Amount', 'wprequal' )
                    ),
                )
            ),
            /**
             * Focus
             */
            'wprequal_section_focus'                     => array(
                'label'    => sprintf( '<h2>%s</h2>', __( 'Focus', 'wprequal' ) ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'heading',
                'args'     => array()
            ),
            'wprequal_focus_title'                       => array(
                'label'    => __( 'Focus - Title', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => '',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_focus_calc_background_color'       => array(
                'label'    => __( 'Focus - Background Color', 'wprequal' ),
                'status'   => 2,
                'filter'   => false,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'wpq-color-picker',
                    'placeholder'       => '',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : 4000
                )
            ),
            'wprequal_focus_msg'                         => array(
                'label'    => __( 'Focus - Message', 'wprequal' ),
                'status'   => 2,
                'filter'   => false,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'editor',
                'args'     => array(
                    'sanitize_callback' => 'wp_kses_post',
                    'type'              => 'text',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : '',
                    'class'             => 'large-text',
                    'placeholder'       => '',
                )
            ),
            /**
             * Get Quote
             */
            'wprequal_section_get_quote'                 => array(
                'label'    => sprintf( '<h2>%s</h2>', __( 'Get Quote', 'wprequal' ) ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'heading',
                'args'     => array()
            ),
            'wprequal_get_quote_post_id'                 => array(
                'label'    => __( 'Get Quote - Contact Form', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'form_select',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'post_type'         => 'wpq_contact_form',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : '',
                    'type'              => 'contact_post_id',
                    'class'             => 'widefat',
                    'placeholder'       => '',
                )
            ),
            'wprequal_show_get_quote'                    => array(
                'label'    => __( 'Get Quote - Show', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'checkbox',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : 5000,
                    'class'             => 'no-class'
                )
            ),
            'wprequal_get_quote_button_text'             => array(
                'label'    => __( 'Get Quote - Button Text', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : '',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_get_quote_button_color'            => array(
                'label'    => __( 'Get Quote - Button Color', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'wpq-color-picker',
                    'placeholder'       => '',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : ''
                )
            ),
            'wprequal_get_quote_button_font_color'       => array(
                'label'    => __( 'Get Quote - Button Font Color', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'wpq-color-picker',
                    'placeholder'       => '',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : ''
                )
            ),
            'wprequal_get_quote_button_hover_color'      => array(
                'label'    => __( 'Get Quote - Button Hover Color', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'wpq-color-picker',
                    'placeholder'       => '',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : ''
                )
            ),
            'wprequal_get_quote_button_hover_font_color' => array(
                'label'    => __( 'Get Quote - Button Hover Font Color', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'wpq-color-picker',
                    'placeholder'       => '',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : ''
                )
            ),
            'wprequal_get_quote_cta'                     => array(
                'label'    => __( 'Get Quote - Call to Action', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'editor',
                'args'     => array(
                    'sanitize_callback' => 'wp_kses_post',
                    'type'              => 'text',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : '',
                    'class'             => 'large-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_get_quote_confirmation'            => array(
                'label'    => __( 'Get Quote Conformation', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'editor',
                'args'     => array(
                    'sanitize_callback' => 'wp_kses_post',
                    'type'              => 'text',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : '',
                    'class'             => 'large-text',
                    'placeholder'       => '',
                )
            ),
            /**
             * Slider defaults
             */
            'wprequal_section_prices'                    => array(
                'label'    => sprintf( '<h2>%s</h2>', __( 'Prices', 'wprequal' ) ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'heading',
                'args'     => array()
            ),
            'wprequal_price_label'                       => array(
                'label'    => __( 'Price Label', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => '',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_price_min'                         => array(
                'label'    => __( 'Price Min', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_price_max'                         => array(
                'label'    => __( 'Price Max', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_price_step'                        => array(
                'label'    => __( 'Price Step', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_price_default'                     => array(
                'label'    => __( 'Price Default', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_section_down_payment'              => array(
                'label'    => sprintf( '<h2>%s</h2>', __( 'Down Payment', 'wprequal' ) ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'heading',
                'args'     => array()
            ),
            'wprequal_down_payment_label'                => array(
                'label'    => __( 'Down Payment Label', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => '',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_down_payment_min'                  => array(
                'label'    => __( 'Down Payment Min', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_down_payment_max'                  => array(
                'label'    => __( 'Down Payment Max', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_down_payment_step'                 => array(
                'label'    => __( 'Down Payment Step', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_down_payment_default'              => array(
                'label'    => __( 'Down Payment Default', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_section_term'                      => array(
                'label'    => sprintf( '<h2>%s</h2>', __( 'Loan Term', 'wprequal' ) ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'heading',
                'args'     => array()
            ),
            'wprequal_term_label'                        => array(
                'label'    => __( 'Term Label', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => '',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_term_suffix'                       => array(
                'label'    => __( 'Term Suffix', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => '',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_term_type'                         => array(
                'label'    => __( 'Term Type', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'select',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => '',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'values'            => (object) array(
                        'years'  => __( 'Years', 'wprequal' ),
                        'months' => __( 'Months', 'wprequal' )
                    ),
                )
            ),
            'wprequal_term_min'                          => array(
                'label'    => __( 'Term Min', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_term_max'                          => array(
                'label'    => __( 'Term Max', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_term_step'                         => array(
                'label'    => __( 'Term Step', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_term_default'                      => array(
                'label'    => __( 'Term Default', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_section_rate'                      => array(
                'label'    => sprintf( '<h2>%s</h2>', __( 'Interest Rate', 'wprequal' ) ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'heading',
                'args'     => array()
            ),
            'wprequal_rate_label'                        => array(
                'label'    => __( 'Rate Label', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => '',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_rate_min'                          => array(
                'label'    => __( 'Rate Min', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'number',
                'args'     => array(
                    'sanitize_callback' => 'floatval',
                    'class'             => 'small-text',
                    'placeholder'       => '',
                    'min'               => '.01',
                    'max'               => '100',
                    'step'              => '0.01',
                    'note'              => 7000
                )
            ),
            'wprequal_rate_max'                          => array(
                'label'    => __( 'Rate Max', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'number',
                'args'     => array(
                    'sanitize_callback' => 'floatval',
                    'class'             => 'small-text',
                    'placeholder'       => '',
                    'min'               => '.01',
                    'max'               => '100',
                    'step'              => '0.01',
                    'note'              => 7000
                )
            ),
            'wprequal_rate_step'                         => array(
                'label'    => __( 'Rate Step', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'number',
                'args'     => array(
                    'sanitize_callback' => 'floatval',
                    'class'             => 'small-text',
                    'placeholder'       => '',
                    'min'               => '.01',
                    'max'               => '100',
                    'step'              => '0.01',
                    'note'              => 7000
                )
            ),
            'wprequal_rate_default'                      => array(
                'label'    => __( 'Rate Default', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'number',
                'args'     => array(
                    'sanitize_callback' => 'floatval',
                    'class'             => 'small-text',
                    'placeholder'       => '',
                    'min'               => '.01',
                    'max'               => '100',
                    'step'              => '0.01',
                    'note'              => 7000
                )
            ),
            'wprequal_section_tax'                       => array(
                'label'    => sprintf( '<h2>%s</h2>', __( 'Taxes', 'wprequal' ) ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'heading',
                'args'     => array()
            ),
            'wprequal_tax_label'                         => array(
                'label'    => __( 'Tax Label', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => '',
                    'class'             => 'regular-text',
                    'placeholder'       => ''
                )
            ),
            'wprequal_tax_min'                           => array(
                'label'    => __( 'Tax Min', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_tax_max'                           => array(
                'label'    => __( 'Tax Max', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_tax_step'                          => array(
                'label'    => __( 'Tax Step', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_tax_default'                       => array(
                'label'    => __( 'Tax Default', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_section_insurance'                 => array(
                'label'    => sprintf( '<h2>%s</h2>', __( 'Insurance', 'wprequal' ) ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'heading',
                'args'     => array()
            ),
            'wprequal_insurance_label'                   => array(
                'label'    => __( 'Insurance Label', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'note'              => '',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_insurance_min'                     => array(
                'label'    => __( 'Insurance Min', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_insurance_max'                     => array(
                'label'    => __( 'Insurance Max', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_insurance_step'                    => array(
                'label'    => __( 'Insurance Step', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            ),
            'wprequal_insurance_default'                 => array(
                'label'    => __( 'Insurance Default', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'type'              => 'number',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => 6000
                )
            )
        ) );

    }

    /**
     * Amortize options.
     *
     * @return array[]
     */

    public function amortize() {

        return apply_filters( 'wprequal_amortize_settings', array(
            'wprequal_amortize_title'            => array(
                'label'    => __( 'Calculator Title', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => ''
                )
            ),
            'wprequal_amortize_label'            => array(
                'label'    => __( 'Months Slider Label', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => ''
                )
            ),
            'wprequal_amortize_principal_label'  => array(
                'label'    => __( 'Graph - Principal Label', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => ''
                )
            ),
            'wprequal_amortize_principal_color'  => array(
                'label'    => __( 'Graph - Principal Color', 'wprequal' ),
                'status'   => 2,
                'filter'   => false,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'wpq-color-picker',
                    'placeholder'       => '',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : ''
                )
            ),
            'wprequal_amortize_principal_border' => array(
                'label'    => __( 'Graph - Principal Border', 'wprequal' ),
                'status'   => 2,
                'filter'   => false,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'wpq-color-picker',
                    'placeholder'       => '',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : ''
                )
            ),
            'wprequal_amortize_interest_label'   => array(
                'label'    => __( 'Graph - Interest Label', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'regular-text',
                    'placeholder'       => '',
                    'note'              => ''
                )
            ),
            'wprequal_amortize_interest_color'   => array(
                'label'    => __( 'Graph - Interest Color', 'wprequal' ),
                'status'   => 2,
                'filter'   => false,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'wpq-color-picker',
                    'placeholder'       => '',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : ''
                )
            ),
            'wprequal_amortize_interest_border'  => array(
                'label'    => __( 'Graph - Interest Border', 'wprequal' ),
                'status'   => 2,
                'filter'   => false,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'text',
                    'class'             => 'wpq-color-picker',
                    'placeholder'       => '',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : ''
                )
            )
        ) );

    }

    /**
     * popup options.
     *
     * @return array[]
     */

    public function popup() {

        return apply_filters( 'wprequal_popup_settings', array(
            'wprequal_popup_post_id' => array(
                'label'    => __( ' Survey Form', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'form_select',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'post_type'         => 'wpq_survey_form',
                    'note'              => '',
                    'type'              => 'popup_post_id',
                    'class'             => 'widefat',
                    'placeholder'       => '',
                )
            ),
            'wprequal_delay'         => array(
                'label'    => __( 'Seconds To Delay Popup', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'note'              => 11000,
                    'type'              => 'number',
                    'class'             => 'small-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_between'       => array(
                'label'    => __( 'Minutes Between Popups', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'input',
                'args'     => array(
                    'sanitize_callback' => 'intval',
                    'note'              => 10000,
                    'type'              => 'number',
                    'class'             => 'small-text',
                    'placeholder'       => '',
                )
            ),
            'wprequal_force'         => array(
                'label'    => __( 'Force Registration', 'wprequal' ),
                'status'   => 1,
                'filter'   => true,
                'callback' => ( ! Core::status( 1 ) ) ? 'hidden' : 'checkbox',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'note'              => ( ! Core::status( 1 ) ) ? 'premium' : 9000,
                    'class'             => 'no-class'
                )
            ),
            'wprequal_show_home'     => array(
                'label'    => __( 'Pop Up on Homepage', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'checkbox',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'class'             => 'no-class',
                    'note'              => ''
                )
            ),
            'wprequal_show_post'     => array(
                'label'    => __( 'Pop Up on Blog Posts', 'wprequal' ),
                'status'   => 1,
                'filter'   => true,
                'callback' => ( ! Core::status( 1 ) ) ? 'hidden' : 'checkbox',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'class'             => 'no-class',
                    'note'              => ( ! Core::status( 1 ) ) ? 'premium' : '',
                )
            ),
            'wprequal_show_page'     => array(
                'label'    => __( 'Pop Up on Pages', 'wprequal' ),
                'status'   => 1,
                'filter'   => true,
                'callback' => ( ! Core::status( 1 ) ) ? 'hidden' : 'checkbox',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'class'             => 'no-class',
                    'note'              => ( ! Core::status( 1 ) ) ? 'premium' : '',
                )
            )
        ) );

    }

    /**
     * Webhook settings.
     *
     * @return array[]
     */

    public function webhook() {

        return apply_filters( 'wprequal_webhook_settings', array(
            'wprequal_webhook_active' => array(
                'label'    => __( 'Activate Webhook', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'checkbox',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'class'             => 'no-class',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : '',
                )
            ),
            'wprequal_webhook_url'    => array(
                'label'    => __( 'Webhook URL', 'wprequal' ),
                'status'   => 2,
                'filter'   => true,
                'callback' => ( ! Core::status( 2 ) ) ? 'hidden' : 'input',
                'args'     => array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'type'              => 'url',
                    'note'              => ( ! Core::status( 2 ) ) ? 'pro' : '',
                    'class'             => 'regular-text',
                    'placeholder'       => 'https://someapi.com/webhook/endpoint',
                )
            )
        ) );

    }

    /**
     * Extensions options.
     *
     * @return array[]
     */

    public function extensions() {

        return apply_filters( 'wprequal_extensions_settings', array(
            'wprequal_mortgage_extension'    => array(
                'label'    => __( 'Mortgage', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'checkbox',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'class'             => 'no-class',
                    'note'              => ''
                )
            ),
            'wprequal_real_estate_extension' => array(
                'label'    => __( 'Real Estate', 'wprequal' ),
                'status'   => 0,
                'filter'   => false,
                'callback' => 'checkbox',
                'args'     => array(
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                    'class'             => 'no-class',
                    'note'              => ''
                )
            )
        ) );

    }

}
