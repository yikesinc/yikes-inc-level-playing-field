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
	$job_type_dropdown_options = '';
	foreach ( $job_types as $val => $label ) {
		$job_type_dropdown_options .= '<option val="' . esc_attr( $val ) . '">' . esc_attr( $label ) . '</option>';
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
	?>
		<!-- TO DO: This needs to be done via loop and arrays, to make extensible (setup class to build defaults etc.) -->
		<div class="panel-wrap product_data">
			<ul class="job_data_tabs yikes-lpf-tabs" style="">
				<li class="company_details general_tab hide_if_grouped active">
					<a href="#company_details"><?php esc_attr_e( 'Company Details', 'yikes-inc-level-playing-field' ); ?></a>
				</li>
				<li class="job_details inventory_tab show_if_simple show_if_variable show_if_grouped" style="display: block;">
					<a href="#job_details"><?php esc_attr_e( 'Job Details', 'yikes-inc-level-playing-field' ); ?></a>
				</li>
				<li class="compensation shipping_tab hide_if_virtual hide_if_grouped hide_if_external">
					<a href="#compensation"><?php esc_attr_e( 'Compensation (optional)', 'yikes-inc-level-playing-field' ); ?></a>
				</li>
				<li class="schedule linked_product_tab">
					<a href="#schedule"><?php esc_attr_e( 'Schedule (optional)', 'yikes-inc-level-playing-field' ); ?></a>
				</li>
				<li class="notifications attribute_tab">
					<a href="#notifications"><?php esc_attr_e( 'Notifications', 'yikes-inc-level-playing-field' ); ?></a>
				</li>
				<li class="advanced_options advanced_tab">
					<a href="#advanced_options">Advanced</a>
				</li>
			</ul>
			<div id="company_details" class="panel yikes_lpf_options_panel" style="display: block;">
				<div class="options_group hide_if_grouped">
					<p class="form-field _sku_field ">
						<label for="_company_name">
							<abbr title="<?php esc_attr__( 'Company Name', 'yikes-inc-level-playing-field' ); ?>">
								<?php esc_attr_e( 'Company Name', 'yikes-inc-level-playing-field' ); ?>
							</abbr>
						</label>
						<input type="text" class="short" style="" name="_company_name" id="_company_name" value="" placeholder="" pmbx_context="FB515532-1068-4393-A201-4C33FCD991BC">
					</p>
					<p class="form-field _regular_price_field ">
						<label for="_company_tag_line">
							<abbr title="<?php esc_attr__( 'Company Tagline', 'yikes-inc-level-playing-field' ); ?>">
								<?php esc_attr_e( 'Company Tagline', 'yikes-inc-level-playing-field' ); ?>
							</abbr>
						</label>
						<input type="text" class="short" style="" name="_company_tag_line" id="_company_tag_line" value="" placeholder="" pmbx_context="1992A4EA-2CBB-4C59-939F-CE2D34AACE89">
					</p>
					<p class="form-field _sale_price_field ">
						<label for="_company_logo">
								<abbr title="<?php esc_attr__( 'Company Logo', 'yikes-inc-level-playing-field' ); ?>">
									<?php printf( esc_attr__( 'Company Logo %s', 'yikes-inc-level-playing-field' ), '<br /><em>(' . esc_attr__( 'optional', 'yikes-inc-level-playing-field' ) . ')</em>' ); ?>
								</abbr>
						</label>
						<input type="text" class="short" style="" name="_company_logo" id="_company_logo" value="" placeholder="" pmbx_context="B5FA2F0A-A238-494A-A1B8-77FBC84B4213">
						<span class="description">
							<br /><?php esc_attr_e( 'Add the associated company logo to this job posting.', 'yikes-inc-level-playing-field' ); ?>
						</span>
					</p>
				</div>

				<!-- Company Details Tab -->
				<div class="options_group pricing show_if_simple show_if_external" style="display: block;">
					<p class="form-field section_title">
						<label for="_company_website">
							<?php esc_attr_e( 'Contact Details', 'yikes-inc-level-playing-field' ); ?>
						</label>
					</p>
					<p class="form-field _company_website_field ">
						<label for="_company_website">
							<?php esc_attr_e( 'Company Website', 'yikes-inc-level-playing-field' ); ?>
						</label>
						<input type="text" class="short" style="" name="_company_website" id="_company_website" value="" placeholder="http://www.yikesinc.com" pmbx_context="1992A4EA-2CBB-4C59-939F-CE2D34AACE89">
					</p>
					<p class="form-field _company_twitter_field ">
						<label for="_company_twitter">
							<?php printf( esc_attr__( 'Company Twitter %s', 'yikes-inc-level-playing-field' ), '<br /><em>(' . esc_attr__( 'optional', 'yikes-inc-level-playing-field' ) . ')</em>' ); ?>
						</label>
						<input type="text" class="short" style="" name="_company_twitter" id="_company_twitter" value="" placeholder="@yikesinc" pmbx_context="B5FA2F0A-A238-494A-A1B8-77FBC84B4213">
						<span class="description">
							<br /><?php esc_attr_e( 'Enter your companys twitter username.', 'yikes-inc-level-playing-field' ); ?>
						</span>
					</p>
				</div>
			</div>
			<!-- End Company Details Tab -->

			<!-- Job Details Tab -->
			<div id="job_details" class="panel yikes_lpf_options_panel" style="display: none;">
					<div class="options_group">
						<p class="form-field _job_details_field" style="display: block;">
							<label for="_manage_stock">
								<?php esc_attr_e( 'Job Details Here', 'yikes-inc-level-playing-field' ); ?>
							</label>
							<span class="description">
								<?php echo __( '<strong>TO DO</strong>: Additional job details will be entered in this box (job location, etc etc.).', 'yikes-inc-level-playing-field' ); ?>
							</span>
						</p>
				</div>
			</div>
			<!-- End Job Details Tab -->

			<!-- Compensation Tab -->
			<div id="compensation" class="panel yikes_lpf_options_panel" style="display: none;">
				<div class="options_group">
					<p class="form-field _compensation_field ">
						<label for="_manage_stock">
							<?php esc_attr_e( 'Compensation Details', 'yikes-inc-level-playing-field' ); ?>
						</label>
						<span class="description">
							<?php echo __( '<strong>TO DO</strong>: Job compensation & benefits.', 'yikes-inc-level-playing-field' ); ?>
						</span>
					</p>
				</div>
			</div>
			<!-- End Compensation Tab -->

			<!-- Notifications Tab -->
			<div id="notifications" class="panel yikes_lpf_options_panel" style="display: none;">
				<div class="options_group">
					<p class="form-field _schedule_field ">
						<label for="_manage_stock">
							<?php esc_attr_e( 'Notifications', 'yikes-inc-level-playing-field' ); ?>
						</label>
						<span class="description">
							<?php echo __( '<strong>TO DO</strong>: Options to email the admins and the current applicant.', 'yikes-inc-level-playing-field' ); ?>
						</span>
					</p>
				</div>
			</div>
			<!-- End Notifications Tab -->

			<!-- Schedule Tab -->
			<div id="schedule" class="panel yikes_lpf_options_panel" style="display: none;">
				<div class="options_group">
					<p class="form-field _notifications_field ">
						<label for="_manage_stock">
							<?php esc_attr_e( 'Schedule', 'yikes-inc-level-playing-field' ); ?>
						</label>
						<span class="description">
							<?php echo __( '<strong>TO DO</strong>: Setup date/time pickers to schedule the job posting.', 'yikes-inc-level-playing-field' ); ?>
						</span>
					</p>
				</div>
			</div>
			<!-- End Shceulde Tab -->

			<!-- Advanced Options Tab -->
			<div id="advanced_options" class="panel yikes_lpf_options_panel" style="display: none;">
				<div class="options_group">
					<p class="form-field _notifications_field ">
						<label for="_manage_stock">
							<?php esc_attr_e( 'Advanced Options', 'yikes-inc-level-playing-field' ); ?>
						</label>
						<span class="description">
							<?php echo __( '<strong>TO DO</strong>: Setup advanced options (not sure yet - may not need this tab).', 'yikes-inc-level-playing-field' ); ?>
						</span>
					</p>
				</div>
			</div>
			<!-- End Advanced Options Tab -->

			<div class="clear"></div>

		</div>
	<?php
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
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function jobs_cpt_save_meta_box( $post_id ) {

}
add_action( 'save_post', 'jobs_cpt_save_meta_box' );
