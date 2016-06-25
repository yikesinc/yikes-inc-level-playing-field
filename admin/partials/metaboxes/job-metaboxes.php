<?php
/**
 * Register meta box(es).
 */
function yikes_level_playinf_field_register_meta_boxes() {
	global $post;
	add_meta_box( 'job-posting-details', __( 'Job Posting Details', 'yikes-inc-level-playing-field' ), 'jobs_cpt_details_metabox_callback', 'jobs' );
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
		<ul style="padding-left:25px;list-style:inherit;">
			<li>Example: <a href="https://employers.indeed.com/post-job">Indeed Job Posting</a></li>
			<li>Job Title</li>
			<li>Job Description</li>
			<li>City/State/Postal Code</li>
			<li>Job Type</li>
			<li>Resume Requirement</li>
			<li>Salary (optional)</li>
			<li>Full Time/Part Time/Contract</li>
			<li>Company Logo (optional)</li>
			<li>Company Website (optional)</li>
		</ul>
	<?php
}

/**
 * Job Posting Stats Metabox
 *
 * @param WP_Post $post Current post object.
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
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function jobs_cpt_save_meta_box( $post_id ) {

}
add_action( 'save_post', 'jobs_cpt_save_meta_box' );
