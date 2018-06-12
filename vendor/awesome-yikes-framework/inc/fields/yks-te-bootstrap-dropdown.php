<?php
if ( is_ssl() ) {
	$http = 'http:';
} else {
	$http = 'https:';
}
						// get the icons out of the css file
						// based on https or http...
						$response = wp_remote_get( $http . '//netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css' );

if ( is_wp_error( $response ) ) {
	wp_die( $response->get_error_message(), __( 'Error', 'timeline-express' ), array( 'back_link' => true ) );
}

						// splot the response body, and store the icon classes in a variable
						$split_dat_response = explode( 'icons */', $response['body'] );

						// empty array for icon array
						$bootstrap_icon_array = array();

						// replace the unecessary stuff
						$data = str_replace( ';', '', str_replace( ':before', '', str_replace( '}', '', str_replace( 'content', '', str_replace( '{', '', $split_dat_response[1] ) ) ) ) );
						$icon_data = explode( '.fa-', $data );
						$i = 1;

foreach ( array_slice( $icon_data, 1 ) as $key => $value ) {
	$split_icon = explode( ':', $value );
	if ( isset( $split_icon[1] ) ) {
		$bootstrap_icon_array[] = array( trim( 'fa-' . $split_icon[0] ) => trim( $split_icon[0] ) );
	}
	++$i;
}

						$flat_bootstrap_icon_array = array();
foreach ( $bootstrap_icon_array as $array ) {
	foreach ( $array as $k => $v ) {
		$flat_bootstrap_icon_array[ $k ] = $v;
	}
}

						// detect ssl url or not..
if ( is_ssl() ) {
							$ssl_url = 'https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js';
} else {
	$ssl_url = 'http://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js';
}
						wp_enqueue_script( 'bootstrap-select', TIMELINE_EXPRESS_URL . 'js/bootstrap-select.js', array( 'jquery' ), 'all' );
						wp_enqueue_script( 'bootstrap-min', $ssl_url, array( 'jquery' ), 'all' );
						wp_enqueue_style( 'bootstrap-select-style',  TIMELINE_EXPRESS_URL . 'css/bootstrap-select.min.css' );
		?>    
						<script>
						jQuery( document ).ready( function() {
							jQuery('.selectpicker').selectpicker({
								style: 'btn-info',
								size: 6
							});
						});
						</script>
						<style>
							.dropdown-toggle { background: transparent !important; border: 1px solid rgb(201, 201, 201) !important; } 
							.dropdown-toggle .caret { border-top-color: #333 !important; }
							.ui-datepicker-prev:hover, .ui-datepicker-next:hover { cursor: pointer; }
						</style> 
						
						<!-- start the font awesome icon select -->
						<select class="selectpicker" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>">
							
		<?php
								/* sort the bootstrap icons alphabetically */
								sort( $flat_bootstrap_icon_array );
		foreach ( $flat_bootstrap_icon_array as $icon ) {
			?>
								
			<option class="fa" data-icon="fa-<?php echo $icon; ?>" <?php selected( 'fa-' . $icon, $meta ); ?>><?php echo $icon; ?></option>
								
								<?php

		}
		?>
							
						</select>
						<!-- end select -->
		<?php
		/*
        *	Check if our WP SVG Icons class exists
        *	for integration, coming soon.

        if( class_exists( 'WP_SVG_Icons_Admin' ) ) {

        }
        */

		echo '<p class="cmb_metabox_description">' . $field['desc'] . '</p>';
?>
