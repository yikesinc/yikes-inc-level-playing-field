					<style>
						#ui-datepicker-div { z-index: 99999 !important; }
						#wpbody-content { overflow: hidden !important; }
						.cmb_id_announcement_image td .cmb_upload_button { height: 32px !important; }
					</style> 
					<?php
					if ( $meta && isset( $meta ) ) {
						echo '<input class="cmb_text_small cmb_datepicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="', '' !== $meta ? date( 'm/d/Y', $meta ) : $field['default'], '" />';
						echo '<p class="cmb_metabox_description">' . $field['desc'] . '</p>';
					} else {
						echo '<input class="cmb_text_small cmb_datepicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="' . date( 'm/d/Y' ) . '" />';
						echo '<p class="cmb_metabox_description">' . $field['desc'] . '</p>';
					} ?>
