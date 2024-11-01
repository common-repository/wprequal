<?php
/**
 * Submit Meta Box
 *
 * Submit metatbox for post types
 *
 * @since   6.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="submitbox wpq-submitbox" id="submitpost">

	<div id="major-publishing-actions"><?php

		do_action( 'post_submitbox_start' ); ?>

		<div id="publishing-action">

			<span class="spinner"></span><?php

			if ( ! in_array( $post_status, array( 'publish' ) ) || 0 == $post_id ) {

				if ( $can_publish ) : ?>

					<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Add Tab' ) ?>" /><?php
					submit_button( __( "Add {$label_name}" ), 'primary button-large wpq-post-submit', 'publish', FALSE, array( 'accesskey' => 'p' ) );

				endif;

			} else { ?>

				<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( "Update {$label_name}" ); ?>" />
				<input name="save" type="submit" class="button button-primary button-large wpq-post-submit" id="publish" accesskey="p" value="<?php esc_attr_e( "Update {$label_name}" ); ?>" /><?php

			} ?>

		</div><?php

		if( $can_delete ) {

			$delete_link = get_delete_post_link( $post_id, '', TRUE ); ?>

			<div id="delete-action" class="delete-action">

				<a class="submitdelete deletion" href="<?php printf( esc_attr__( '%s' ), $delete_link ); ?>">
					<?php _e( 'Delete Permanently', 'wprequal' ); ?>
				</a>

			</div><?php

		} ?>

		<div class="clear"></div>

	</div>

</div>