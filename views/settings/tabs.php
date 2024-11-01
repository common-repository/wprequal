<?php
/**
 * Admin Tabs
 *
 * Tabs for admin settings page.
 *
 * @since   6.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<h2 class="nav-tab-wrapper"><?php

    foreach ( $tabs as $id => $tab ) {

    	$status = ( $active_tab === $id ) ? 'nav-tab-active' : $tab['status'];

    	if ( Core::extend( $tab['extends'] ) ) { ?>

	        <a href="<?php echo esc_url( $href ) ?><?php esc_attr_e( $id ); ?>" class="nav-tab <?php esc_attr_e( $status ); ?>">
		        <?php esc_html_e( $tab['label'] ); ?>
		    </a><?php

	    }

    } ?>

</h2>