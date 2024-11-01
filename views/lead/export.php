<?php
/**
 * Export Leads
 *
 * Export leads page HTML
 *
 * @since   6.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<h1><?php _e( 'Export Leads', 'wprequal' ); ?></h1>

<?php if ( ! Core::status( 1 ) ) {
	view( 'buttons', 'go-premium' );
	return;
} ?>

<div class="export-leads-wrapper">
	<a href="<?php echo esc_url( $href); ?>" class="button button-primary export">
		<?php _e( 'Export Leads', 'wprequal' ); ?> <i class="far fa-file-export"></i>
	</a>
</div>