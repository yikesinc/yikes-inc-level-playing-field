<?php // AIzaSyAHEFLBjTaeuDz1-tHw-XJN0q1gORmMnfQ
/**** Google Maps Field ****/

	global $post;

	// Get our field values.
	$lng         = isset( $meta['google-maps-lat'] ) ? $meta['google-maps-lat'] : '';
	$lat         = isset( $meta['google-maps-lng'] ) ? $meta['google-maps-lng'] : '';
	$s           = isset( $meta['google-maps-search'] ) ? $meta['google-maps-search'] : '';
	$id          = isset( $field['id'] ) ? $field['id'] : '';
	$desc        = isset( $field['desc'] ) ? $field['desc'] : '';
	$k_desc      = isset( $field['api_key_desc'] ) ? $field['api_key_desc'] : '';
	$desc_type   = isset( $field['desc_type'] ) && $field['desc_type'] === 'inline' ? 'span' : 'p';
	$k_desc_type = isset( $field['api_key_desc_type'] ) && $field['api_key_desc_type'] === 'inline' ? 'span' : 'p';

	// Enqueue our scripts and styles.
	$API_Key = get_option( 'yks-awesome-framework-map-picker-api-key', '' );

	// Enqueue the map picker, map picker styles, and the base Google Maps JS.
	wp_enqueue_script( 'yks-map-picker-scripts', YKS_MBOX_URL . 'js/fields/min/yks-map-picker.min.js', array( 'jquery' ), false, true );
	wp_enqueue_style( 'yks-map-picker-styles', YKS_MBOX_URL . 'css/fields/yks-map-picker.css', array(), false, 'all' );

// If we don't have the API key, don't enqueue the library. Let the JavaScript enqueue it once an API key is entered.
if ( $API_Key ) {
	wp_enqueue_script( 'google-maps-native', "https://maps.googleapis.com/maps/api/js?key={$API_Key}&libraries=places&callback=yks_init_map" );
}

?>
	<div class="google-maps-search-container">

		<!-- Search -->
		<input class="map-search" type="text" name="<?php echo esc_attr( $id ); ?>[google-maps-search]" id="google-maps-search" value="<?php echo esc_attr( $s ); ?>">
		<button type="button" class="map-search-submit button">Search</button>
		<button type="button" class="map-reset button">Reset</button>

		<!-- Hidden Lat/Lng fields -->
		<input type="hidden" name="<?php echo esc_attr( $id ); ?>[google-maps-lat]" id="google-maps-lat" value="<?php echo esc_attr( $lat ); ?>"/>
		<input type="hidden" name="<?php echo esc_attr( $id ); ?>[google-maps-lng]" id="google-maps-lng" value="<?php echo esc_attr( $lng ); ?>"/>

		<!-- Map -->
		<div class="maparea" id="map-canvas"></div>

		<!-- Description -->
		<?php echo "<{$desc_type} class='yks_mbox_description'>{$desc}</{$desc_type}>"; ?>
	</div>

	<div class="google-maps-api-key">
		<!-- API Key -->
		<label for="map-picker-api-key">Google Maps API Key</label>
		<input id="map-picker-api-key" name="map-picker-api-key" class="yks_txt_small" value="<?php echo esc_attr( $API_Key ); ?>" />

		<!-- Description -->
		<?php echo "<{$k_desc_type} class='yks_mbox_description'>{$k_desc}</{$k_desc_type}>"; ?>
	</div>
