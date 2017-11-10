<?php

// Function name must match file name minus ".php
function yks_save_hidden_pass( $data, $post_id = '', $field_id = '' ) {
	if ( ! empty( $post_id ) ) {
		$new = get_post_meta( $post_id, $field_id, true );

		return $new;
	} else {
		return '';
	}
}
