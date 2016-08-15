<?php
/**
 * Register meta box(es).
 */
function yikes_level_playinf_field_register_meta_boxes() {
	global $post;
	// Build job types (employee status) array
	$job_types = apply_filters( 'yikes_level_playing_field_job_types', array(
		'contract' => __( 'Contract', 'yikes-inc-level-playing-field' ),
		'part-time' => __( 'Part Time', 'yikes-inc-level-playing-field' ),
		'full-time' => __( 'Full Time', 'yikes-inc-level-playing-field' ),
	) );
	$selected_job_type = ( get_post_meta( $post->ID, '_position_status', true ) ) ? get_post_meta( $post->ID, '_position_status', true ) : 'full-time';
	$job_type_dropdown_options = '';
	foreach ( $job_types as $val => $label ) {
		$job_type_dropdown_options .= '<option value="' . esc_attr( $val ) . '" ' . selected( $selected_job_type, esc_attr( $val ), false ) . '>' . esc_attr( $label ) . '</option>';
	}
	$job_type_dropdown = '<select name="_position_status" onclick="event.stopPropagation();">' . $job_type_dropdown_options . '</select>';
	// Add job posting details metabox
	add_meta_box(
		'job-posting-details',
		__( 'Job Posting Details', 'yikes-inc-level-playing-field' ) . '&nbsp;&mdash;&nbsp;' . $job_type_dropdown,
		'jobs_cpt_details_metabox_callback',
		'jobs'
	);
	// Build our array of buttons for the 'Job Application' metabox
	$job_application_buttons = apply_filters( 'yikes_level_playing_field_job_application_action_buttons', array(
		'save-application' => __( 'Save', 'yikes-inc-level-playing-field' ),
		'load-application' => __( 'Load', 'yikes-inc-level-playing-field' ),
		'clear-application' => __( 'Clear', 'yikes-inc-level-playing-field' ),
	) );
	$application_action_button_container = '';
	if ( ! empty( $job_application_buttons ) ) {
		foreach ( $job_application_buttons as $class => $button_text ) {
			$application_action_button_container .= '<a href="#' . esc_attr( $class ) . '" class="job-app-action-btn ' . esc_attr( $class ) . '">' . esc_attr( $button_text ) . '</a>';
		}
	}
	// Add application builder metabox
	add_meta_box(
		'job-application-builder',
		__( 'Job Application', 'yikes-inc-level-playing-field' ) . '&nbsp;&mdash;&nbsp;' . $application_action_button_container,
		'jobs_cpt_app_builder_metabox_callback',
		'jobs'
	);
	// only display the stats box once the job posting is published
	if ( isset( $post->ID ) && ( 'publish' === get_post_status( $post->ID ) ) ) {
		add_meta_box( 'job-posting-stats', __( 'Job Posting Stats', 'yikes-inc-level-playing-field' ), 'jobs_cpt_stats_metabox_callback', 'jobs', 'side', 'high' );
	}
}
add_action( 'add_meta_boxes', 'yikes_level_playinf_field_register_meta_boxes' );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function jobs_cpt_details_metabox_callback( $post ) {
	$job_posting_details = new Yikes_Inc_Level_Playing_Field_Job_Posting_Details();
	$main_sections = $job_posting_details->main_sections;
	?>
		<!-- TO DO: This needs to be done via loop and arrays, to make extensible (setup class to build defaults etc.) -->
		<div class="panel-wrap job_data">
			<?php
				render_job_posting_details_sidebar( $job_posting_details );
				render_job_posting_details_main_sections( $job_posting_details );
			?>
			<div class="clear"></div>
		</div>
	<?php
}

function render_job_posting_details_sidebar( $job_posting_details ) {
	// Get our sidebar menu
	$sidebar_menu = $job_posting_details->sidebar_menu;
	ob_start();
	?>
	<!-- Sidebar Menu -->
	<ul class="job_data_tabs yikes-lpf-tabs">
		<?php
		if ( $sidebar_menu && ! empty( $sidebar_menu ) ) {
			$sidebar_menu_length = count( $sidebar_menu );
			for ( $x = 0; $x < $sidebar_menu_length; $x++ ) {
				?>
				<li class="<?php echo esc_attr( $sidebar_menu[ $x ]['id'] ); if ( 0 === $x ) { ?> active<?php } ?>">
					<a href="#<?php echo esc_attr( $sidebar_menu[ $x ]['id'] ); ?>">
						<?php echo esc_attr( $sidebar_menu[ $x ]['text'] ); ?>
					</a>
				</li>
				<?php
			}
		}
		?>
	</ul>
	<!-- End Sidebar Menu -->
	<?php
	$contents = ob_get_contents();
	ob_get_clean();
	echo wp_kses_post( $contents );
}

function render_job_posting_details_main_sections( $job_posting_details ) {
	// Store sidebar to loop and generate associated main section
	$sidebar_menu = $job_posting_details->sidebar_menu;
	ob_start();
	if ( $sidebar_menu && ! empty( $sidebar_menu ) ) {
		$sidebar_menu_length = count( $sidebar_menu );
		for ( $x = 0; $x <= $sidebar_menu_length; $x++ ) {
			?>
			<div id="<?php echo esc_attr( $sidebar_menu[ $x ]['id'] ); ?>" class="panel yikes_lpf_options_panel">
				<?php render_seciton_fields( $job_posting_details, $sidebar_menu[ $x ]['id'] ); ?>
			</div>
			<?php
		}
	}
	$contents = ob_get_contents();
	ob_get_clean();
	// Get the allowed tags
	$allowed_tags = $job_posting_details->allowed_tags;
	echo wp_kses( $contents, $allowed_tags );
}

/**
 * Loop over and render our field sections
 * @param  class  $job_posting_details Job posting details class initialization
 * @param  string $section_id          Section ID to retreive the fields for.
 * @return mixed                       HTML content for the fields to render.
 */
function render_seciton_fields( $job_posting_details, $section_id ) {
	global $post;
	// Get our fields
	$fields = $job_posting_details->fields;
	ob_start();
	if ( isset( $fields[ $section_id ] ) && ! empty( $fields[ $section_id ] ) ) {
		foreach ( $fields[ $section_id ] as $field_data ) {
			// Store our field attributes
			$field_name = ( isset( $field_data['id'] ) ) ? $field_data['id'] : '_' . str_replace( '-', '_', sanitize_title( $field_data['label'] ) );
			$value = ( isset( $field_data['value'] ) ) ? $field_data['value'] : ( ( get_post_meta( $post->ID, $field_name, true ) ) ? get_post_meta( $post->ID, $field_name, true ) : '' );
			$class = ( isset( $field_data['class'] ) ) ? $field_data['class'] : '';
			$type = ( isset( $field_data['type'] ) ) ? $field_data['type'] : 'text';
			$placeholder = ( isset( $field_data['placeholder'] ) ) ? $field_data['placeholder'] : '';
			$description = ( isset( $field_data['description'] ) ) ? $field_data['description'] : false;
			// checked attribute
			$checked = ( 'checkbox' === $type ) ? checked( $value, $field_data['value'], false ) : '';
			?>
			<!-- Display the field -->
			<p class="form-field <?php echo esc_attr( $field_name ); ?>">
				<label for="<?php echo esc_attr( $field_name ); ?>">
					<abbr title="">
						<?php echo esc_attr( $field_data['label'] ); ?>
					</abbr>
				</label>

				<!-- Compensation Details currency sign -->
				<?php if ( '_compensation_details' === $field_name ) { ?>
					<span class="input-group-currency"><?php echo esc_html( get_option( 'yikes_level_playing_field_money_format', '$' ) ); ?></span>
				<?php } ?>

				<!-- Field -->
				<input type="<?php echo esc_attr( $type ); ?>" <?php if ( '_compensation_details' === $field_name ) { ?> min="0" step="0.01" <?php } echo esc_attr( $checked ); ?> class="<?php echo esc_attr( $class ); ?>" name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>">

				<?php
				/* Field Description */
				if ( $description ) {
					?>
					<em class="description">
						<?php echo esc_attr( $description ); ?>
					</em>
					<?php
				}
				?>
			</p>
			<?php
		}
	}
	$contents = ob_get_contents();
	ob_get_clean();
	$allowed_tags = $job_posting_details->allowed_tags;
	echo wp_kses( $contents, $allowed_tags );
}

/**
 * Job Posting Stats Metabox
 *
 * @param WP_Post $post Current post object.
 * @since 1.0.0
 */
function jobs_cpt_stats_metabox_callback( $post ) {
	?>
		<ul>
			<li>42 views</li>
			<li>3 applicants</li>
			<li>0 approved</li>
			<li>0 rejected</li>
			<li>0 maybe</li>
		</ul>
	<?php
}

/**
 * Job Application Form Builder Metabox
 *
 * @param WP_Post $post Current post object.
 * @since 1.0.0
 */
function jobs_cpt_app_builder_metabox_callback( $post ) {
	?>
		<h2>Job Application Builder <em>(TO DO)</em></h2>
	<?php
}

/**
 * Get the job posting fields, and return the IDs
 * This is used to retreive our options whitelist
 * @return array IDs of the fields.
 * @since 1.0.0
 */
function get_whitelisted_options() {
	$job_posting_details = new Yikes_Inc_Level_Playing_Field_Job_Posting_Details();
	$field_ids = array();
	if ( $job_posting_details->fields && ! empty( $job_posting_details->fields ) ) {
		foreach ( $job_posting_details->fields as $job_detail_section_name => $field_data ) {
			foreach ( $field_data as $field_data_array ) {
				if ( isset( $field_data_array['id'] ) && ! empty( $field_data_array['id'] ) ) {
					$field_ids[] = $field_data_array['id'];
				}
			}
		}
	}
	return $field_ids;
}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function jobs_cpt_save_meta_box( $post_id ) {
	// If were on the front end (eg: Submitting an application form)
	// or we're not on the jobs post type
	if ( ! is_admin() || 'jobs' !== get_the_post_type( $post_id ) ) {
		return;
	}
	// Verify the nonce, before continuing
	if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-post_' . $post_id ) ) {
		wp_die( esc_attr__( 'The security check did not pass. Please go back and try again.', 'yikes-inc-level-playing' ) );
		exit;
	}
	$options_whitelist = get_whitelisted_options();
	// if for some reason the options return empty, abort
	if ( empty( $options_whitelist ) ) {
		return;
	}
	foreach ( $options_whitelist as $whitelisted_option ) {
		if ( in_array( $whitelisted_option, $options_whitelist ) ) {
			update_post_meta( $post_id, $whitelisted_option, sanitize_text_field( $_POST[ $whitelisted_option ] ) );
		}
	}
}
add_action( 'save_post', 'jobs_cpt_save_meta_box' );
