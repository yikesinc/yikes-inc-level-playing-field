<?php 

/**
 * Check theme for field file - if it does not exist check framework
 *
 * @param array $field The field we're dealing with.
 */
function yikes_get_file_template_location( $field ) {

	$field_name_with_dashes = str_replace( '_', '-', $field['type'] );

	if ( file_exists( ( get_template_directory() . '/inc/cpt/cpt-fields/fields/yks-' . $field_name_with_dashes . '.php' ) ) !== false ) {

		return get_template_directory() . '/inc/cpt/cpt-fields/fields/yks-' . $field_name_with_dashes . '.php';

	} elseif ( stream_resolve_include_path( dirname( dirname( __FILE__ ) ) . '/inc/fields/yks-' . $field_name_with_dashes . '.php' ) !== false ) {

		return stream_resolve_include_path( dirname( dirname( __FILE__ ) ) . '/inc/fields/yks-' . $field_name_with_dashes . '.php' );

	} else {

		$custom_field_locations = apply_filters( 'yikes-awesome-framework-custom-field-directories', array() );

		if ( ! empty( $custom_field_locations ) ) {

			foreach ( $custom_field_locations as $dir ) {

				if ( file_exists( $dir . '/yks-' . $field_name_with_dashes . '.php' ) !== false ) {
					return $dir . '/yks-' . $field_name_with_dashes . '.php';
				}
			}
		}
	}

	return false;
}


add_action( 'wp_ajax_yks_oembed_handler', 'yks_oembed_ajax_results' );
/**
 * Handles our oEmbed ajax request.
 */
function yks_oembed_ajax_results() {

	// Verify our nonce
	if ( isset( $_POST['yks_ajax_nonce'] ) ) {

		// If we have the nonce, verify it
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['yks_ajax_nonce'] ) ), 'ajax_nonce' ) ) {
			wp_die();
		}
	} else {

		// If we don't have the nonce, die
		wp_die();
	}

	// Capture and sanitize the oembed_url
	$oembed_url = '';
	if( isset( $_POST['oembed_url'] ) && ! empty( $_POST['oembed_url'] ) ) {
		$oembed_url = filter_var( esc_url( $_POST['oembed_url'] ), FILTER_VALIDATE_URL );
	}

	// If oembed_url is empty (either it wasn't passed in, or it failed validation ) then die
	if ( empty( $oembed_url ) ) {
		wp_die();
	}

	// Default options, filtered
	$embed_options = apply_filters( 'yikes-awesome-framework-oembed-options', array( 'width' => '400' ) );

	// Ping WordPress for the embed code -- will return false if the URL 
	$embed_code = wp_oembed_get( $oembed_url, $embed_options );

	// Send back the result
	echo json_encode( array( 'result' => $embed_code ) );
	exit;
}

/**
 *
 * Function to get post in array
 *
 * @param string $post_type The post type to look for.
 **/
function get_posts_by_posttype( $post_type ) {

	$posts = array();

	if ( empty ( $post_type ) ) {
		return $posts;
	}

	// Fetch all the posts that have the post type specified by $post_type
	$wp_query_args = array(
		'post_status'		=> 'publish',
		'post_type' 		=> $post_type,
		'posts_per_page'	=> '-1', // Grab all posts
		'order'				=> 'ASC',
		'orderby'			=> 'post_title'
	);
	$wp_query_results = new WP_Query( $wp_query_args );
	$query_posts = $wp_query_results->posts;

	
	foreach( $query_posts as $post ) {
		$posts[] = array(
			'name' => $post->post_title,
			'value'=> (string) $post->ID,
			'slug' => $post->post_name
		);
	}

	return $posts;
}

/**
 *
 * Retrieve all post ids for the specified post types.
 *
 * Note: this function returns only the post IDs for performance reasons
 *
 * @param array  | $post_type | An array of post types we're looking for, default is all post types
 * @return array | array of each post's ID
 **/
function get_post_ids_by_post_types( $post_types = array( 'any' ) ) {

	// Fetch all the posts that have the post type specified by $post_type
	$wp_query_args = array(
		'post_status'		=> 'publish',
		'post_type' 		=> $post_types,
		'fields'			=> 'ids',
		'posts_per_page'	=> '-1', // Grab all posts
		'order'				=> 'ASC',
		'orderby'			=> 'post_title'
	);
	$wp_query_results = new WP_Query( $wp_query_args );

	return $wp_query_results->posts;
}

/*
*  get_selected_taxonomies( $meta_field_ID , $taxonomy_name , $return_value='name' , $return_type='string' );
*  return the selected taxonomies for a given post. this can return a comma seperated list of names, or an array of ids , slugs etc.
* -----------------------------------------------------
*  Accepted Parameters :
* @meta_field_id  -- field ID eg: kitchen_mealtype -- *required*
* @taxonomy_name -- the name of the taxonomy eg: mealtype -- *required*
* @return_value -- what would you like returned? see below for options -- **optional / defaults to name**
* @return_type -- how would you like your data returned? strong or array? -- **optional / defaults to string (this really only effects the name field, everything else will be returned as an array)
*
*/
function get_selected_taxonomies( $meta_field_ID, $taxonomy_name, $return_value = 'name', $return_type = 'string' ) {
	/* get our post meta **/
	global $post;
	$tax_meta = get_post_meta( $post->ID, $meta_field_ID, true );
	$storage_container = array();
	foreach ( $tax_meta as $tax ) {
		$tax_data_array 		= get_term_by( 'id', $tax, $taxonomy_name );
		$storage_container[]	= $tax_data_array->$return_value;
	}

	/* check if the array is empty...if it is, abort and return null **/
	if ( empty( $storage_container ) ) {
		return null;
	}

	/**
	 * Possible return values.
	 * name
	 * term_id
	 * slug
	 * term_group
	 * term_taxonomy_id
	 * taxonomy
	 * description
	 * parent
	 * count (number of posts tagged with this)
	 * object_id
	 * filter
	*/
	if ( 'name' == $return_value ) {
		if ( 'string' == $return_type ) {
			/* return a comma seperated list **/
			return implode( ', ', $storage_container );
		} else {
			/* return an array **/
			return $storage_container;
		}
	} else {
		/* return an array to use in a loop **/
		return $storage_container;
	}
}

/**
 * Function to call slide loop
 *
 * @param object $type the type of slides.
 * @param int    $field_id object id.
 * @param int    $limit max amount of slides.
 * @param int    $pid Post.
 **/
function yks_loop_text_group_slides( $type, $field_id, $limit, $pid = '' ) {
	foreach ( range( 1, $limit ) as $number ) {
		$typerange = array( 'one', 'two', 'three', 'four', 'four_id', 'five', 'six' );
		/** Sort Order
		* if page or plugin option
		*/
		if ( 'options' == $type ) {
			$order = get_option( '' . $field_id . '_sort_' . $number . '' );
		} elseif ( 'cpt' == $type ) {
			$order = get_post_meta( $pid, '' . $field_id . '_sort_' . $number . '', true );
		}
		foreach ( $typerange as $thetype ) {
			/* field ID **/
			if ( 'four_id' != $thetype ) {
				$name = '' . $field_id . '_' . $thetype . '_' . $number . '';
			} elseif ( 'four_id' == $thetype ) {
				$name = '' . $field_id . '_four_' . $number . '_id';
			}
			$newval = '';
			/* page or plugin options **/
			if ( 'options' == $type ) {
				$newval = get_option( $name );
			} elseif ( 'cpt' == $type ) { /* if CPT **/
				$newval = get_post_meta( $pid, $name, true );
			}
			/* render value **/
			if ( ! empty( $newval ) ) {
				$value[ $order ][ $thetype ] = $newval;
			}
		}
	}
	if ( ! empty( $value ) ) {
		ksort( $value );

		return $value;
	} else {
		return array();
	}
}

/**
 *  Multisite menu
 *  - Function attempt.
 */
function wp_multisite_nav_menu() {
	global $blog_id;
	$args = array(
		'menu' => 4,
		'container' => '',
		'container_class' => '',
		'container_id' => '',
		'fallback_cb' => false,
		'menu_class' => 'menu',
		'menu_id' => '',
		'echo' => true,
		'depth' => 0,
		'walker' => '',
	);
	if ( ! is_multisite() || 1 == $blog_id ) {
		wp_nav_menu( $args );
		return;
	}
	/* switch to blog, and query **/
	switch_to_blog( 1 );
	/* filter the links to open in a new tab when not on the main site **/
	add_filter( 'wp_nav_menu_how-do-i_items', 'alter_target_on_howdoi_nav_menu_items', 10, 2 );
	/* grab the nav **/
	wp_nav_menu( $args );
	restore_current_blog();
}

function alter_target_on_howdoi_nav_menu_items( $items, $args ) {
	/** Open our links in a new tab */
	$items = str_replace( 'a href="', 'a target="_blank" href="', $items );
	return $items;
}

/** Get Content URL for MU  */
if ( ! function_exists( 'yks_mu_url_path' ) ) {
	function yks_mu_url_path( $basename ) {
		$echo = site_url();
		$echo .= '/wp-content/mu-plugins/';
		$echo .= $basename . '/';

		return $echo;
	}
}

/**
* Check if the hours of operation are empty.
*
* The data structure of hours of operation is not good. 
* It's ugly to check if the hours of operation are empty within a theme's page so centralize that check here.
*
* @param array | $hours_of_operation
*
* @return bool | T if empty || F if ! empty
*/
function yikes_awesome_framework_hours_of_operation_empty( $hours_of_operation ) {

	// Check if every day is empty
	if (  ( ! isset( $hours_of_operation[1] ) || ! isset( $hours_of_operation[2] ) || ! isset( $hours_of_operation[3] ) || ! isset( $hours_of_operation[4] ) 
		    || ! isset( $hours_of_operation[5] ) || ! isset( $hours_of_operation[6] ) || ! isset( $hours_of_operation[7] ) ) 

		    || isset( $hours_of_operation[1] ) && empty( $hours_of_operation[1]['monday_open'] ) && empty( $hours_of_operation[1]['monday_close'] ) 
			&& isset( $hours_of_operation[2] ) && empty( $hours_of_operation[2]['tuesday_open'] ) && empty( $hours_of_operation[2]['tuesday_close'] )
			&& isset( $hours_of_operation[3] ) && empty( $hours_of_operation[3]['wednesday_open'] ) && empty( $hours_of_operation[3]['wednesday_close'] )
			&& isset( $hours_of_operation[4] ) && empty( $hours_of_operation[4]['thursday_open'] ) && empty( $hours_of_operation[4]['thursday_close'] )
			&& isset( $hours_of_operation[5] ) && empty( $hours_of_operation[5]['friday_open'] ) && empty( $hours_of_operation[5]['friday_close'] )
			&& isset( $hours_of_operation[6] ) && empty( $hours_of_operation[6]['saturday_open'] ) && empty( $hours_of_operation[6]['saturday_close'] )
			&& isset( $hours_of_operation[7] ) && empty( $hours_of_operation[7]['sunday_open'] ) && empty( $hours_of_operation[7]['sunday_close'] )

		) {
		return true;
	} else {
		return false;
	}
}

/**
* Display hours of operation as a list w/ trimmed opening 0s.
*
* The data structure of hours of operation is not good. 
* It's ugly to check if the hours of operation are empty within a theme's page so centralize that check here.
*
* @param array | $hours_of_operation
*
* @return echo hours as an HTML list.
*/
function yikes_awesome_framework_display_hours_of_operation( $hours_of_operation ) {
	?>
	<ul>
		<?php if ( isset( $hours_of_operation[1] ) && isset( $hours_of_operation[1]['monday_open'] ) && ! empty( $hours_of_operation[1]['monday_open'] ) && isset( $hours_of_operation[1]['monday_close'] ) && ! empty( $hours_of_operation[1]['monday_close'] ) ): ?>

			<li><span class="day-of-the-week">Monday: </span> <?php echo ltrim( $hours_of_operation[1]['monday_open'], '0' ) . ' - ' . ltrim( $hours_of_operation[1]['monday_close'], '0' ); ?></li>

		<?php endif; ?>

		<?php if ( isset( $hours_of_operation[2] ) && isset( $hours_of_operation[2]['tuesday_open'] ) && ! empty( $hours_of_operation[2]['tuesday_open'] ) && isset( $hours_of_operation[2]['tuesday_close'] ) && ! empty( $hours_of_operation[2]['tuesday_close'] ) ): ?>

			<li><span class="day-of-the-week">Tuesday: </span> <?php echo ltrim( $hours_of_operation[2]['tuesday_open'], '0' ) . ' - ' . ltrim( $hours_of_operation[2]['tuesday_close'], '0' ); ?></li>

		<?php endif; ?>

		<?php if ( isset( $hours_of_operation[1] ) && isset( $hours_of_operation[1]['wednesday_open'] ) && ! empty( $hours_of_operation[1]['wednesday_open'] ) && isset( $hours_of_operation[3]['wednesday_close'] ) && ! empty( $hours_of_operation[3]['wednesday_close'] ) ): ?>

			<li><span class="day-of-the-week">Wednesday: </span> <?php echo ltrim( $hours_of_operation[1]['wednesday_open'], '0' ) . ' - ' . ltrim( $hours_of_operation[3]['wednesday_close'], '0' ); ?></li>

		<?php endif; ?>

		<?php if ( isset( $hours_of_operation[1] ) && isset( $hours_of_operation[4]['thursday_open'] ) && ! empty( $hours_of_operation[4]['thursday_open'] ) && isset( $hours_of_operation[4]['thursday_close'] ) && ! empty( $hours_of_operation[4]['thursday_close'] ) ): ?>

			<li><span class="day-of-the-week">Thursday: </span> <?php echo ltrim( $hours_of_operation[4]['thursday_open'], '0' ) . ' - ' . ltrim( $hours_of_operation[4]['thursday_close'], '0' ); ?></li>

		<?php endif; ?>

		<?php if ( isset( $hours_of_operation[1] ) && isset( $hours_of_operation[5]['friday_open'] ) && ! empty( $hours_of_operation[5]['friday_open'] ) && isset( $hours_of_operation[5]['friday_close'] ) && ! empty( $hours_of_operation[5]['friday_close'] ) ): ?>

			<li><span class="day-of-the-week">Friday: </span> <?php echo ltrim( $hours_of_operation[5]['friday_open'], '0' ) . ' - ' . ltrim( $hours_of_operation[5]['friday_close'], '0' ); ?></li>

		<?php endif; ?>

		<?php if ( isset( $hours_of_operation[1] ) && isset( $hours_of_operation[6]['saturday_open'] ) && ! empty( $hours_of_operation[6]['saturday_open'] ) && isset( $hours_of_operation[6]['saturday_close'] ) && ! empty( $hours_of_operation[6]['saturday_close'] ) ): ?>

			<li><span class="day-of-the-week">Saturday: </span> <?php echo ltrim( $hours_of_operation[6]['saturday_open'], '0' ) . ' - ' . ltrim( $hours_of_operation[6]['saturday_close'], '0' ); ?></li>

		<?php endif; ?>

		<?php if ( isset( $hours_of_operation[1] ) && isset( $hours_of_operation[7]['sunday_open'] ) && ! empty( $hours_of_operation[7]['sunday_open'] ) && isset( $hours_of_operation[7]['sunday_close'] ) && ! empty( $hours_of_operation[7]['sunday_close'] ) ): ?>

			<li><span class="day-of-the-week">Sunday: </span> <?php echo ltrim( $hours_of_operation[7]['sunday_open'], '0' ) . ' - ' . ltrim( $hours_of_operation[7]['sunday_close'], '0' ); ?></li>

		<?php endif; ?>
	</ul>

	<?php
}

/**
* Get an image URL of the specified size from our awesome framework.
*
* @param string  | $meta_value | The array returned from a `get_post_meta()` or `get_option()` for a file field, e.g. `get_option( 'homepage_home_image' )`
* @param string  | $image_size | The desired image size. Defaults to thumbnail.
*
* @return string | The URL of the specified image size.
*/
function yks_get_image_url_from_file_field( $meta_value, $image_size = 'thumbnail' ) {

	$image_id    = isset( $meta_value[0] ) && isset( $meta_value[0]['id'] ) ? $meta_value[0]['id'] : '';
	$image_attrs = wp_get_attachment_image_src( $image_id, $image_size );

	// The URL to the specified size of the image
	return isset( $image_attrs[0] ) ? $image_attrs[0] : '';
}

/**
* Take a 24-hour time string and return a 12-hour-based hour
*
* @param string | $time_string_24	| A 24-hour time string, e.g. 1815
* @return string| $hour				| A 2-digit, 12-hour-based hour, e.g. '06'
*/
function yks_mbox_text_time_formatted_get_hour( $time_string_24 ) {

	// 1815 -> 18
	$hour = ( ! empty( substr( $time_string_24, 0, 2 ) ) ) ? (int) substr( $time_string_24, 0, 2 ) : '';

	// 18 -> 6
	$hour = ( $hour > 11 ) ? $hour - 12 : $hour;
	
	// 0 -> '00'
	$hour = ( $hour === 0 ) ? '00' : $hour;

	// 6 -> '06'
	return strlen( $hour ) === 1 ? '0' . $hour : (string) $hour; 
}

/**
* Take a 24-hour time string and return the minutes (return the last two digits)
*
* @param string | $time_string_24	| A 24-hour time string, e.g. 1815
* @return int	| $minute			| The last two digits of the time string, e.g. 15
*/
function yks_mbox_text_time_formatted_get_minute( $time_string_24 ) {

	// 1815 -> 15 --- convert $minute to a string to preserve '00', '05'
	return ( ! empty( substr( $time_string_24, 2, 2 ) ) ) ? (string) substr( $time_string_24, 2, 2 ) : '';
}

/**
* Take a 24-hour time string and determine if AM or PM
*
* @param string | $time_string_24	| A 24-hour time string, e.g. 1815
* @param bool	| $default_empty	| A boolean indicating whether we should return '' if an empty value is passed in
* @return string| $am_or_pm			| '2' for PM, '1' for AM, '' if no $hour
*/
function yks_mbox_text_time_formatted_get_am_pm( $time_string_24, $default_empty = false ) {

	// 1815 -> 18
	$hour = ( ! empty( substr( $time_string_24, 0, 2 ) ) ) ? substr( $time_string_24, 0, 2 ) : '';

	// 18 -> '2' (PM) || ''
	if ( $default_empty === true && empty( $hour ) && $hour !== '00' ) {
		return '';
	} else {
		return ( (int) $hour > 11 ) ? '2' : '1';
	}
}

/**
* Return an array of all the U.S. states
*
* @return array | $states | e.g. array( array( 'name' => 'Pennsylvania', 'value' => 'PA' ), array(...) );
*/
function yks_awesome_framework_states_array( $include_na = true ) {
	$states = array(
		array(
			'name' => 'Alabama',
			'value' => 'AL'
		),
		array(
			'name' => 'Alaska',
			'value' => 'AK',
		),
		array(
			'name' => 'Arizona',
			'value' => 'AZ',
		),
		array(
			'name' => 'Arkansas',
			'value' => 'AR',
		),
		array(
			'name' => 'California',
			'value' => 'CA',
		),
		array(
			'name' => 'Colorado',
			'value' => 'CO',
		),
		array(
			'name' => 'Connecticut',
			'value' => 'CT',
		),
		array(
			'name' => 'Delaware',
			'value' => 'DE',
		),
		array(
			'name' => 'District of Columbia',
			'value' => 'DC',
		),
		array(
			'name' => 'Florida',
			'value' => 'FL',
		),
		array(
			'name' => 'Georgia',
			'value' => 'GA',
		),
		array(
			'name' => 'Hawaii',
			'value' => 'HI',
		),
		array(
			'name' => 'Idaho',
			'value' => 'ID',
		),
		array(
			'name' => 'Illinois',
			'value' => 'IL',
		),
		array(
			'name' => 'Indiana',
			'value' => 'IN',
		),
		array(
			'name' => 'Iowa',
			'value' => 'IA',
		),
		array(
			'name' => 'Kansas',
			'value' => 'KS',
		),
		array(
			'name' => 'Kentucky',
			'value' => 'KY',
		),
		array(
			'name' => 'Louisiana',
			'value' => 'LA',
		),
		array(
			'name' => 'Maine',
			'value' => 'ME',
		),
		array(
			'name' => 'Maryland',
			'value' => 'MD',
		),
		array(
			'name' => 'Massachusetts',
			'value' => 'MA',
		),
		array(
			'name' => 'Michigan',
			'value' => 'MI',
		),
		array(
			'name' => 'Minnesota',
			'value' => 'MN',
		),
		array(
			'name' => 'Mississippi',
			'value' => 'MS',
		),
		array(
			'name' => 'Missouri',
			'value' => 'MO',
		),
		array(
			'name' => 'Montana',
			'value' => 'MT',
		),
		array(
			'name' => 'Nebraksa',
			'value' => 'NE',
		),
		array(
			'name' => 'Nevada',
			'value' => 'NV',
		),
		array(
			'name' => 'New Hampshire',
			'value' => 'NH',
		),
		array(
			'name' => 'New Jersey',
			'value' => 'NJ',
		),
		array(
			'name' => 'New Mexico',
			'value' => 'NM',
		),
		array(
			'name' => 'New York',
			'value' => 'NY',
		),
		array(
			'name' => 'North Carolina',
			'value' => 'NC',
		),
		array(
			'name' => 'North Dakota',
			'value' => 'ND',
		),
		array(
			'name' => 'Ohio',
			'value' => 'OH',
		),
		array(
			'name' => 'Oklahoma',
			'value' => 'OK',
		),
		array(
			'name' => 'Oregon',
			'value' => 'OR',
		),
		array(
			'name' => 'Pennsylvania',
			'value' => 'PA',
		),
		array(
			'name' => 'Rhode Island',
			'value' => 'RI',
		),
		array(
			'name' => 'South Carolina',
			'value' => 'SC',
		),
		array(
			'name' => 'South Dakota',
			'value' => 'SD',
		),
		array(
			'name' => 'Tennessee',
			'value' => 'TN',
		),
		array(
			'name' => 'Texas',
			'value' => 'TX',
		),
		array(
			'name' => 'Utah',
			'value' => 'UT',
		),
		array(
			'name' => 'Vermont',
			'value' => 'VT',
		),
		array(
			'name' => 'Virginia',
			'value' => 'VA',
		),
		array(
			'name' => 'Washington',
			'value' => 'WA',
		),
		array(
			'name' => 'West Virginia',
			'value' => 'WV',
		),
		array(
			'name' => 'Wisconsin',
			'value' => 'WI',
		),
		array(
			'name' => 'Wyoming',
			'value' => 'WY',
		)
	);

	if ( $include_na === true ) {
		array_unshift( $states, array(
			'name' => 'N/A',
			'value' => 'n/a'
		) );
	}

	return apply_filters( 'yikes-awesome-framework-states-select', $states );
}

/**
* Return an array of all the countries
*
* @return array | $countries | e.g. array( array( 'name' => 'United Kingdon', 'value' => 'GB' ), array(...) );
*/
function yks_awesome_framework_countries_array() {
	$countries = array(
		array(
			'name' => 'United States of America',
			'value' => 'US'
		),
		array(
			'name' => 'Afghanistan',
			'value' => 'AF'
		),
		array(
			'name' => 'Åland Islands',
			'value' => 'AX'
		),
		array(
			'name' => 'Albania',
			'value' => 'AL'
		),
		array(
			'name' => 'Algeria',
			'value' => 'DZ'
		),
		array(
			'name' => 'American Samoa',
			'value' => 'AS'
		),
		array(
			'name' => 'Andorra',
			'value' => 'AD'
		),
		array(
			'name' => 'Angola',
			'value' => 'AO'
		),
		array(
			'name' => 'Anguilla',
			'value' => 'AI'
		),
		array(
			'name' => 'Antarctica',
			'value' => 'AQ'
		),
		array(
			'name' => 'Antigua and Barbuda',
			'value' => 'AG'
		),
		array(
			'name' => 'Argentina',
			'value' => 'AR'
		),
		array(
			'name' => 'Armenia',
			'value' => 'AM'
		),
		array(
			'name' => 'Aruba',
			'value' => 'AW'
		),
		array(
			'name' => 'Australia',
			'value' => 'AU'
		),
		array(
			'name' => 'Austria',
			'value' => 'AT'
		),
		array(
			'name' => 'Azerbaijan',
			'value' => 'AZ'
		),
		array(
			'name' => 'Bahamas',
			'value' => 'BS'
		),
		array(
			'name' => 'Bahrain',
			'value' => 'BH'
		),
		array(
			'name' => 'Bangladesh',
			'value' => 'BD'
		),
		array(
			'name' => 'Barbados',
			'value' => 'BB'
		),
		array(
			'name' => 'Belarus',
			'value' => 'BY'
		),
		array(
			'name' => 'Belgium',
			'value' => 'BE'
		),
		array(
			'name' => 'Belize',
			'value' => 'BZ'
		),
		array(
			'name' => 'Benin',
			'value' => 'BJ'
		),
		array(
			'name' => 'Bermuda',
			'value' => 'BM'
		),
		array(
			'name' => 'Bhutan',
			'value' => 'BT'
		),
		array(
			'name' => 'Bolivia, Plurinational State of',
			'value' => 'BO'
		),
		array(
			'name' => 'Bonaire, Sint Eustatius and Sab',
			'value' => 'BQ'
		),
		array(
			'name' => 'Bosnia and Herzegovina',
			'value' => 'BA'
		),
		array(
			'name' => 'Botswana',
			'value' => 'BW'
		),
		array(
			'name' => 'Bouvet Island',
			'value' => 'BV'
		),
		array(
			'name' => 'Brazil',
			'value' => 'BR'
		),
		array(
			'name' => 'British Indian Ocean Territory',
			'value' => 'IO'
		),
		array(
			'name' => 'Brunei Darussalam',
			'value' => 'BN'
		),
		array(
			'name' => 'Bulgaria',
			'value' => 'BG'
		),
		array(
			'name' => 'Burkina Faso',
			'value' => 'BF'
		),
		array(
			'name' => 'Burundi',
			'value' => 'BI'
		),
		array(
			'name' => 'Cambodia',
			'value' => 'KH'
		),
		array(
			'name' => 'Cameroon',
			'value' => 'CM'
		),
		array(
			'name' => 'Canada',
			'value' => 'CA'
		),
		array(
			'name' => 'Cape Verde',
			'value' => 'CV'
		),
		array(
			'name' => 'Cayman Islands',
			'value' => 'KY'
		),
		array(
			'name' => 'Central African Republic',
			'value' => 'CF'
		),
		array(
			'name' => 'Chad',
			'value' => 'TD'
		),
		array(
			'name' => 'Chile',
			'value' => 'CL'
		),
		array(
			'name' => 'China',
			'value' => 'CN'
		),
		array(
			'name' => 'Christmas Island',
			'value' => 'CX'
		),
		array(
			'name' => 'Cocos (Keeling) Islands',
			'value' => 'CC'
		),
		array(
			'name' => 'Colombia',
			'value' => 'CO'
		),
		array(
			'name' => 'Comoros',
			'value' => 'KM'
		),
		array(
			'name' => 'Congo',
			'value' => 'CG'
		),
		array(
			'name' => 'Congo, the Democratic Republic of the',
			'value' => 'CD'
		),
		array(
			'name' => 'Cook Islands',
			'value' => 'CK'
		),
		array(
			'name' => 'Costa Rica',
			'value' => 'CR'
		),
		array(
			'name' => 'Côte d\'Ivoire',
			'value' => 'CI'
		),
		array(
			'name' => 'Croatia',
			'value' => 'HR'
		),
		array(
			'name' => 'Cuba',
			'value' => 'CU'
		),
		array(
			'name' => 'Curaçao',
			'value' => 'CW'
		),
		array(
			'name' => 'Cyprus',
			'value' => 'CY'
		),
		array(
			'name' => 'Czech Republic',
			'value' => 'CZ'
		),
		array(
			'name' => 'Denmark',
			'value' => 'DK'
		),
		array(
			'name' => 'Djibouti',
			'value' => 'DJ'
		),
		array(
			'name' => 'Dominica',
			'value' => 'DM'
		),
		array(
			'name' => 'Dominican Republic',
			'value' => 'DO'
		),
		array(
			'name' => 'Ecuador',
			'value' => 'EC'
		),
		array(
			'name' => 'Egypt',
			'value' => 'EG'
		),
		array(
			'name' => 'El Salvador',
			'value' => 'SV'
		),
		array(
			'name' => 'Equatorial Guinea',
			'value' => 'GQ'
		),
		array(
			'name' => 'Eritrea',
			'value' => 'ER'
		),
		array(
			'name' => 'Estonia',
			'value' => 'EE'
		),
		array(
			'name' => 'Ethiopia',
			'value' => 'ET'
		),
		array(
			'name' => 'Falkland Islands (Malvinas)',
			'value' => 'FK'
		),
		array(
			'name' => 'Faroe Islands',
			'value' => 'FO'
		),
		array(
			'name' => 'Fiji',
			'value' => 'FJ'
		),
		array(
			'name' => 'Finland',
			'value' => 'FI'
		),
		array(
			'name' => 'France',
			'value' => 'FR'
		),
		array(
			'name' => 'French Guiana',
			'value' => 'GF'
		),
		array(
			'name' => 'French Polynesia',
			'value' => 'PF'
		),
		array(
			'name' => 'French Southern Territories',
			'value' => 'TF'
		),
		array(
			'name' => 'Gabon',
			'value' => 'GA'
		),
		array(
			'name' => 'Gambia',
			'value' => 'GM'
		),
		array(
			'name' => 'Georgia',
			'value' => 'GE'
		),
		array(
			'name' => 'Germany',
			'value' => 'DE'
		),
		array(
			'name' => 'Ghana',
			'value' => 'GH'
		),
		array(
			'name' => 'Gibraltar',
			'value' => 'GI'
		),
		array(
			'name' => 'Greece',
			'value' => 'GR'
		),
		array(
			'name' => 'Greenland',
			'value' => 'GL'
		),
		array(
			'name' => 'Grenada',
			'value' => 'GD'
		),
		array(
			'name' => 'Guadeloupe',
			'value' => 'GP'
		),
		array(
			'name' => 'Guam',
			'value' => 'GU'
		),
		array(
			'name' => 'Guatemala',
			'value' => 'GT'
		),
		array(
			'name' => 'Guernsey',
			'value' => 'GG'
		),
		array(
			'name' => 'Guinea',
			'value' => 'GN'
		),
		array(
			'name' => 'Guinea-Bissau',
			'value' => 'GW'
		),
		array(
			'name' => 'Guyana',
			'value' => 'GY'
		),
		array(
			'name' => 'Haiti',
			'value' => 'HT'
		),
		array(
			'name' => 'Heard Island and McDonald Islands',
			'value' => 'HM'
		),
		array(
			'name' => 'Holy See (Vatican City State)',
			'value' => 'VA'
		),
		array(
			'name' => 'Honduras',
			'value' => 'HN'
		),
		array(
			'name' => 'Hong Kong',
			'value' => 'HK'
		),
		array(
			'name' => 'Hungary',
			'value' => 'HU'
		),
		array(
			'name' => 'Iceland',
			'value' => 'IS'
		),
		array(
			'name' => 'India',
			'value' => 'IN'
		),
		array(
			'name' => 'Indonesia',
			'value' => 'ID'
		),
		array(
			'name' => 'Iran, Islamic Republic of',
			'value' => 'IR'
		),
		array(
			'name' => 'Iraq',
			'value' => 'IQ'
		),
		array(
			'name' => 'Ireland',
			'value' => 'IE'
		),
		array(
			'name' => 'Isle of Man',
			'value' => 'IM'
		),
		array(
			'name' => 'Israel',
			'value' => 'IL'
		),
		array(
			'name' => 'Italy',
			'value' => 'IT'
		),
		array(
			'name' => 'Jamaica',
			'value' => 'JM'
		),
		array(
			'name' => 'Japan',
			'value' => 'JP'
		),
		array(
			'name' => 'Jersey',
			'value' => 'JE'
		),
		array(
			'name' => 'Jordan',
			'value' => 'JO'
		),
		array(
			'name' => 'Kazakhstan',
			'value' => 'KZ'
		),
		array(
			'name' => 'Kenya',
			'value' => 'KE'
		),
		array(
			'name' => 'Kiribati',
			'value' => 'KI'
		),
		array(
			'name' => 'Korea, Democratic People\'s Republic of',
			'value' => 'KP'
		),
		array(
			'name' => 'Korea, Republic of',
			'value' => 'KR'
		),
		array(
			'name' => 'Kuwait',
			'value' => 'KW'
		),
		array(
			'name' => 'Kyrgyzstan',
			'value' => 'KG'
		),
		array(
			'name' => 'Lao People\'s Democratic Republic',
			'value' => 'LA'
		),
		array(
			'name' => 'Latvia',
			'value' => 'LV'
		),
		array(
			'name' => 'Lebanon',
			'value' => 'LB'
		),
		array(
			'name' => 'Lesotho',
			'value' => 'LS'
		),
		array(
			'name' => 'Liberia',
			'value' => 'LR'
		),
		array(
			'name' => 'Libya',
			'value' => 'LY'
		),
		array(
			'name' => 'Liechtenstein',
			'value' => 'LI'
		),
		array(
			'name' => 'Lithuania',
			'value' => 'LT'
		),
		array(
			'name' => 'Luxembourg',
			'value' => 'LU'
		),
		array(
			'name' => 'Macao',
			'value' => 'MO'
		),
		array(
			'name' => 'Macedonia, the former Yugoslav Republic of',
			'value' => 'MK'
		),
		array(
			'name' => 'Madagascar',
			'value' => 'MG'
		),
		array(
			'name' => 'Malawi',
			'value' => 'MW'
		),
		array(
			'name' => 'Malaysia',
			'value' => 'MY'
		),
		array(
			'name' => 'Maldives',
			'value' => 'MV'
		),
		array(
			'name' => 'Mali',
			'value' => 'ML'
		),
		array(
			'name' => 'Malta',
			'value' => 'MT'
		),
		array(
			'name' => 'Marshall Islands',
			'value' => 'MH'
		),
		array(
			'name' => 'Martinique',
			'value' => 'MQ'
		),
		array(
			'name' => 'Mauritania',
			'value' => 'MR'
		),
		array(
			'name' => 'Mauritius',
			'value' => 'MU'
		),
		array(
			'name' => 'Mayotte',
			'value' => 'YT'
		),
		array(
			'name' => 'Mexico',
			'value' => 'MX'
		),
		array(
			'name' => 'Micronesia, Federated States of',
			'value' => 'FM'
		),
		array(
			'name' => 'Moldova, Republic of',
			'value' => 'MD'
		),
		array(
			'name' => 'Monaco',
			'value' => 'MC'
		),
		array(
			'name' => 'Mongolia',
			'value' => 'MN'
		),
		array(
			'name' => 'Montenegro',
			'value' => 'ME'
		),
		array(
			'name' => 'Montserrat',
			'value' => 'MS'
		),
		array(
			'name' => 'Morocco',
			'value' => 'MA'
		),
		array(
			'name' => 'Mozambique',
			'value' => 'MZ'
		),
		array(
			'name' => 'Myanmar',
			'value' => 'MM'
		),
		array(
			'name' => 'Namibia',
			'value' => 'NA'
		),
		array(
			'name' => 'Nauru',
			'value' => 'NR'
		),
		array(
			'name' => 'Nepal',
			'value' => 'NP'
		),
		array(
			'name' => 'Netherlands',
			'value' => 'NL'
		),
		array(
			'name' => 'New Caledonia',
			'value' => 'NC'
		),
		array(
			'name' => 'New Zealand',
			'value' => 'NZ'
		),
		array(
			'name' => 'Nicaragua',
			'value' => 'NI'
		),
		array(
			'name' => 'Niger',
			'value' => 'NE'
		),
		array(
			'name' => 'Nigeria',
			'value' => 'NG'
		),
		array(
			'name' => 'Niue',
			'value' => 'NU'
		),
		array(
			'name' => 'Norfolk Island',
			'value' => 'NF'
		),
		array(
			'name' => 'Northern Mariana Islands',
			'value' => 'MP'
		),
		array(
			'name' => 'Norway',
			'value' => 'NO'
		),
		array(
			'name' => 'Oman',
			'value' => 'OM'
		),
		array(
			'name' => 'Pakistan',
			'value' => 'PK'
		),
		array(
			'name' => 'Palau',
			'value' => 'PW'
		),
		array(
			'name' => 'Palestinian Territory, Occupied',
			'value' => 'PS'
		),
		array(
			'name' => 'Panama',
			'value' => 'PA'
		),
		array(
			'name' => 'Papua New Guinea',
			'value' => 'PG'
		),
		array(
			'name' => 'Paraguay',
			'value' => 'PY'
		),
		array(
			'name' => 'Peru',
			'value' => 'PE'
		),
		array(
			'name' => 'Philippines',
			'value' => 'PH'
		),
		array(
			'name' => 'Pitcairn',
			'value' => 'PN'
		),
		array(
			'name' => 'Poland',
			'value' => 'PL'
		),
		array(
			'name' => 'Portugal',
			'value' => 'PT'
		),
		array(
			'name' => 'Puerto Rico',
			'value' => 'PR'
		),
		array(
			'name' => 'Qatar',
			'value' => 'QA'
		),
		array(
			'name' => 'Réunion',
			'value' => 'RE'
		),
		array(
			'name' => 'Romania',
			'value' => 'RO'
		),
		array(
			'name' => 'Russian Federation',
			'value' => 'RU'
		),
		array(
			'name' => 'Rwanda',
			'value' => 'RW'
		),
		array(
			'name' => 'Saint Barthélemy',
			'value' => 'BL'
		),
		array(
			'name' => 'Saint Helena, Ascension and Tristan da Cunha',
			'value' => 'SH'
		),
		array(
			'name' => 'Saint Kitts and Nevis',
			'value' => 'KN'
		),
		array(
			'name' => 'Saint Lucia',
			'value' => 'LC'
		),
		array(
			'name' => 'Saint Martin (French part)',
			'value' => 'MF'
		),
		array(
			'name' => 'Saint Pierre and Miquelon',
			'value' => 'PM'
		),
		array(
			'name' => 'Saint Vincent and the Grenadines',
			'value' => 'VC'
		),
		array(
			'name' => 'Samoa',
			'value' => 'WS'
		),
		array(
			'name' => 'San Marino',
			'value' => 'SM'
		),
		array(
			'name' => 'Sao Tome and Principe',
			'value' => 'ST'
		),
		array(
			'name' => 'Saudi Arabia',
			'value' => 'SA'
		),
		array(
			'name' => 'Senegal',
			'value' => 'SN'
		),
		array(
			'name' => 'Serbia',
			'value' => 'RS'
		),
		array(
			'name' => 'Seychelles',
			'value' => 'SC'
		),
		array(
			'name' => 'Sierra Leone',
			'value' => 'SL'
		),
		array(
			'name' => 'Singapore',
			'value' => 'SG'
		),
		array(
			'name' => 'Sint Maarten (Dutch part)',
			'value' => 'SX'
		),
		array(
			'name' => 'Slovakia',
			'value' => 'SK'
		),
		array(
			'name' => 'Slovenia',
			'value' => 'SI'
		),
		array(
			'name' => 'Solomon Islands',
			'value' => 'SB'
		),
		array(
			'name' => 'Somalia',
			'value' => 'SO'
		),
		array(
			'name' => 'South Africa',
			'value' => 'ZA'
		),
		array(
			'name' => 'South Georgia and the South Sandwich Islands',
			'value' => 'GS'
		),
		array(
			'name' => 'South Sudan',
			'value' => 'SS'
		),
		array(
			'name' => 'Spain',
			'value' => 'ES'
		),
		array(
			'name' => 'Sri Lanka',
			'value' => 'LK'
		),
		array(
			'name' => 'Sudan',
			'value' => 'SD'
		),
		array(
			'name' => 'Suriname',
			'value' => 'SR'
		),
		array(
			'name' => 'Svalbard and Jan Mayen',
			'value' => 'SJ'
		),
		array(
			'name' => 'Swaziland',
			'value' => 'SZ'
		),
		array(
			'name' => 'Sweden',
			'value' => 'SE'
		),
		array(
			'name' => 'Switzerland',
			'value' => 'CH'
		),
		array(
			'name' => 'Syrian Arab Republic',
			'value' => 'SY'
		),
		array(
			'name' => 'Taiwan, Province of China',
			'value' => 'TW'
		),
		array(
			'name' => 'Tajikistan',
			'value' => 'TJ'
		),
		array(
			'name' => 'Tanzania, United Republic of',
			'value' => 'TZ'
		),
		array(
			'name' => 'Thailand',
			'value' => 'TH'
		),
		array(
			'name' => 'Timor-Leste',
			'value' => 'TL'
		),
		array(
			'name' => 'Togo',
			'value' => 'TG'
		),
		array(
			'name' => 'Tokelau',
			'value' => 'TK'
		),
		array(
			'name' => 'Tonga',
			'value' => 'TO'
		),
		array(
			'name' => 'Trinidad and Tobago',
			'value' => 'TT'
		),
		array(
			'name' => 'Tunisia',
			'value' => 'TN'
		),
		array(
			'name' => 'Turkey',
			'value' => 'TR'
		),
		array(
			'name' => 'Turkmenistan',
			'value' => 'TM'
		),
		array(
			'name' => 'Turks and Caicos Islands',
			'value' => 'TC'
		),
		array(
			'name' => 'Tuvalu',
			'value' => 'TV'
		),
		array(
			'name' => 'Uganda',
			'value' => 'UG'
		),
		array(
			'name' => 'Ukraine',
			'value' => 'UA'
		),
		array(
			'name' => 'United Arab Emirates',
			'value' => 'AE'
		),
		array(
			'name' => 'United Kingdom',
			'value' => 'GB'
		),
		array(
			'name' => 'United States Minor Outlying Islands',
			'value' => 'UM'
		),
		array(
			'name' => 'Uruguay',
			'value' => 'UY'
		),
		array(
			'name' => 'Uzbekistan',
			'value' => 'UZ'
		),
		array(
			'name' => 'Vanuatu',
			'value' => 'VU'
		),
		array(
			'name' => 'Venezuela, Bolivarian Republic of',
			'value' => 'VE'
		),
		array(
			'name' => 'Viet Nam',
			'value' => 'VN'
		),
		array(
			'name' => 'Virgin Islands, British',
			'value' => 'VG'
		),
		array(
			'name' => 'Virgin Islands, U.S.',
			'value' => 'VI'
		),
		array(
			'name' => 'Wallis and Futuna',
			'value' => 'WF'
		),
		array(
			'name' => 'Western Sahara',
			'value' => 'EH'
		),
		array(
			'name' => 'Yemen',
			'value' => 'YE'
		),
		array(
			'name' => 'Zambia',
			'value' => 'ZM'
		),
		array(
			'name' => 'Zimbabwe',
			'value' => 'ZW'
		),
	);

	return apply_filters( 'yikes-awesome-framework-countries-select', $countries );
}

/**
 * Take ID of media attachment and return appropriate preview HTML
 *
 * @param int    | $attachment_id  | ID for media object
 * @param string | $attachment_url | URL for media object
 *
 * @return string $preview_html
 */
function yks_get_preview_html_from_file_type( $attachment_id, $attachment_url ) {
	$type = get_post_mime_type( $attachment_id );
	$type = explode( '/', $type );
	$type = is_array( $type ) && isset( $type[0] ) ? $type[0] : '';
	$preview_html = '<span class="dashicons dashicons-media-default"></span>';
	switch ( $type ) {
		case 'image':
			$preview_html = '<img src="' . htmlspecialchars( $attachment_url ) . '">';
			break;
		case 'video':
			$preview_html = '<span class="dashicons dashicons-media-video"></span>';
			break;
	}
	return $preview_html;
}

/**
 * Return an escaped HTML attribute.
 *
 * @param string $key   The attribute key.
 * @param string $value The attribute value.
 *
 * @return string
 */
function yks_return_attribute( $key, $value ) {
	return sprintf( '%1$s="%2$s" ', $key, esc_attr( $value ) );
}
