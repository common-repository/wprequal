<?php
/**
 * Page
 *
 * Admin settings page.
 *
 * @since   6.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="wprequal-wrap">

	<h1 class="wprequal-admin-h1"><?php _e( 'WPrequal Settings', 'wprequal' ); ?></h1><?php

	do_action( 'wprequal_help_buttons' ); ?>

	<form method="post" action="options.php?active_tab=<?php esc_attr_e( $active_tab ); ?>">

		<table class="form-table">

			<tbody>

				<?php echo $tabs;

				settings_fields( $active_tab );
				do_settings_fields( WPREQUAL_CAP, 'default' ); ?>

			</tbody>

		</table>

		<?php submit_button(); ?>

	</form>

</div>

<script>
	jQuery( document ).ready( function( $ ) {
		$( '.reset-defaults' ).on( 'click', function( e ) {
			//e.preventDefault();
			if ( confirm( 'Are you sure you want to reset all WPrequal plugin settings to their default value?' ) ) {
				return true;
			}
			return false;
		} );
	} );
</script>

<div class="wprequal-wrap">
	<?php $href = admin_url( 'admin.php?active_tab=dashboard&wprequal_reset_defaults=true&page=' . WPREQUAL_OPTIONS ); ?>
	<a href="<?php echo esc_url( $href ); ?>" class="reset-defaults">
		<?php _e( 'Reset all settings to default'); ?>
	</a>
</div>