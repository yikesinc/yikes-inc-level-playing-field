<?php
/**
 * Register a custom menu page.
 */
function wpdocs_register_my_custom_menu_page() {
	add_submenu_page(
		'edit.php?post_type=jobs',
		__( 'Applicants', 'yikes-inc-level-playing-field' ),
		__( 'Applicants', 'yikes-inc-level-playing-field' ),
		'manage_options',
		esc_url_raw( 'admin.php?page=manage-applicants' ),
		'render_level_playing_field_dashboard'
	);
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

/**
 * Render the Level Playing Field Dashboard Managemenet Page
 */
function render_level_playing_field_dashboard() {
	$post_type = 'applicants';
	$applicant_args = array(
		'post_type' => 'applicants',
		'posts_per_page' => 20,
		'paged' => $paged,
	);
	$applicant_query = new WP_Query( $applicant_args );
	if ( $applicant_query->have_posts() ) {
		while ( $applicant_query->have_posts() ) {
			$applicant_query->the_post();
			echo the_title() . '<br />';
		}
	} else {
		?>
			<h2>Applicants Template</h2>
			<ul>
				<li>- Applicants are a custom post type named <code>applicants</code>.
				<li>- List out the job CPTs here in custom view format.</li>
				<li>- Make each job clicakble, to query current applicants (post type = <code>applicants</code>), again in a custom view.</li>
			</ul>
		<?php
	}
}
