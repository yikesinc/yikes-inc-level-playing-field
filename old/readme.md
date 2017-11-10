Level Playing field by YIKES Inc.
=====================

<em>This plugin is currently in development. Stay tuned ya'll.</em>


### Temporary Documentation:

##### Shortcodes
* `[lpf-jobs]` - List the current, active, jobs in a nice table. Possible Parameters: type="list/table". Defaults to table. (`[lpf-jobs type="table"]`)
* `[lpf-application]` - Display the application for a specific job.

##### Filters
* `yikes_level_playing_field_template_path`
* `yikes_level_playing_field_submit_application_button_class`
* `yikes_level_playing_field_get_template_part`
* `yikes_level_playing_field_breadcrumb_defaults`
* `yikes_level_playing_field_breadcrumb_home_url`
* `yikes_level_playing_field_job_types`
* `yikes_level_playing_field_job_application_action_buttons`
* `yikes_level_playing_field_job_posting_details_menu_items`
* `yikes_level_playing_field_job_posting_details_fields`
* `yikes_level_playing_field_job_query_args`
* `yikes_level_playing_field_job_table_headers`
* `yikes_level_playing_whitelisted_options`
* `yikes_level_playing_user_roles`
* `yikes_level_playing_user_application_reveal`

##### Actions

<strong>Single Job Template:</strong>
* `yikes_level_playing_field_before_main_content`
* `yikes_level_playing_field_after_main_content`
* `yikes_level_playing_field_sidebar`
* `yikes_level_playing_field_before_single_job`
* `yikes_level_playing_field_before_single_job_summary`
* `yikes_level_playing_field_single_job_summary`
* `yikes_level_playing_field_after_single_job_summary`
* `yikes_level_playing_field_after_single_job`

<strong>Application Submission:</strong>
* `yikes_inc_level_playing_field_process_application_submission`

##### Constants

* `LPF_TEMPLATE_DEBUG_MODE` - Template debugging.


##### Helper Functions

There are a few helper functions built into the plugin (located in /includes/class-yikes-inc-level-playing-field-helpers.php), which aid in the retrieval/storage/parsing of data from the database. Below you'll find a list of some of the helper functions built into the plugin, and what they do.

It is worth noting that the filters tagged below as *pluggable* can be overwritten, meaning they can be re-defined in your themes functions.php (or elsewhere) without fear of errors.

`yikes_format_money` - *pluggable* - This function will format an integer value into the appropriate money format.

##### Customizations

Users can customize both the table and the list of available jobs, by copying the associated template into your theme root.

*Table* - To customize the table layout, you will want to copy over the table template file (`job-listing-table-template.php`) from '/yikes-inc-level-playing-field/templates/' into your theme root, inside of a `yikes-level-playing-field/` directory (`/wp-content/themes/theme_name/yikes-level-playing-field/job-listing-table-template.php`).

*List* - To customize the list layout, you will want to copy over the list template file (`job-listing-list-template.php`) from '/yikes-inc-level-playing-field/templates/' into your theme root, inside of a `yikes-level-playing-field/` directory (`/wp-content/themes/theme_name/yikes-level-playing-field/job-listing-list-template.php`).


##### Re-arranging Content

Out of the box, this plugin may not display things exactly how you watn them to. Let's say you wanted to move the 'Apply' link from it's original location below the job description - up to above the job title.

You'll need to unhook the function from it's default location, and then define a new function or hook the existing function into a new location.

**Example:**
```php
/**
 * Move the 'Apply' link/form from the standard location (after the description)
 * To the top of the page (pre content/tags/category etc.)
 * @return mixed HTML content for the job application link/form
 */
remove_action( 'yikes_level_playing_field_after_single_job_summary', 'append_job_listing_application', 10 );
// Hook in to a new location, and re-render the link/form
add_action( 'yikes_level_playing_field_before_single_job', 'append_job_listing_application', 10 );
```
