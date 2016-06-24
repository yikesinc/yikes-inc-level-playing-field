<?php
/**
 * Register meta box(es).
 */
function wpdocs_register_meta_boxes() {
	add_meta_box( 'meta-box-id', __( 'Job Posting Details', 'yikes-inc-level-playing-field' ), 'jobs_cpt_metabox_callback', 'jobs' );
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function jobs_cpt_metabox_callback( $post ) {
	?>
		<h2>This is a test</h2>
		<ul style="padding-left:25px;list-style:inherit;">
			<li>Example: <a href="https://employers.indeed.com/post-job">Indeed Job Posting</a></li>
			<li>Job Title</li>
			<li>Job Description</li>
			<li>City/State/Postal Code</li>
			<li>Job Type</li>
			<li>Resume Requirement</li>
			<li>Salary (optional)</li>
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
