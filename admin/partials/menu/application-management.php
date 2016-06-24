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
		'admin.php?page=manage-applicants',
		'render_level_playing_field_dashboard'
	);
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

/**
 * Render the Level Playing Field Dashboard Managemenet Page
 */
function render_level_playing_field_dashboard() {
	?>
		<h2>Applicants Template</h2>
		<ul>
			<li>- List out the job CPTs here in custom view format.</li>
			<li>- Make each job clicakble, to query current applicants, again in a custom view.</li>
		</ul>
	<?php
}
