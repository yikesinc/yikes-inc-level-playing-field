<?php
/**
 * Register a custom menu page.
 */
function wpdocs_register_my_custom_menu_page() {
	add_menu_page(
		__( 'Level Playing Field', 'yikes-inc-level-playing-field' ),
		__( 'Level Playing Field', 'yikes-inc-level-playing-field' ),
		'manage_options',
		'level-playing-field-dashboard',
		'render_level_playing_field_dashboard',
		'',
		6
	);
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

/**
 * Render the Level Playing Field Dashboard Managemenet Page
 */
function render_level_playing_field_dashboard() {
	esc_html_e( 'Testing :)' );
}
