<?php
/**
 * Text Field HTML Markup
 * @since 1.0.0
 */
?>
<li class="yikes-field-container">

	<!-- Field Header -->
	<div class="yikes_admin_icons">
		<div class="yikes_admin_header_title">
			<?php esc_attr_e( 'Single Line Text', 'yikes-inc-level-playing-field' ); ?>
		</div>
		<a class="field_delete_icon" id="yikes_field_delete_1" title="click to delete this field" href="#" onclick="delete_this_application_form_field(this); return false;" onkeypress="delete_this_application_form_field(this); return false;">
			<i class="dashicons dashicons-no-alt large-icon"></i>
		</a>
		<a class="field_duplicate_icon" id="yikes_duplicate_1" title="click to duplicate this field" href="#" onclick="StartDuplicateField(this); return false;" onkeypress="StartDuplicateField(this); return false;">
			<i class="dashicons dashicons-admin-page large-icon"></i>
		</a>
		<a class="field_edit_icon edit_icon_collapsed" title="click to expand and edit the options for this field">
			<i class="dashicons dashicons-arrow-down"></i>
		</a>
	</div>
	<!-- End Field Header -->

	<!-- Begin Tabs Container -->
	<div class="interior_container">
		<!-- Form Type Preview -->
		<label class="field-label"><?php esc_html_e( 'Untitled', 'yikes-inc-level-playing-field' ); ?></label>
		<input type="text" value="" disabled="disabled" />
		<!-- End From Type Preview -->

		<div class="yikes-tabs tab-container">
			<!-- Display Tabs -->
			<ul class="ui-widget-header">
				<li><a href="#general-tab"><?php esc_html_e( 'General', 'yikes-inc-level-playing-field' ); ?></a></li>
				<li><a href="#appearance-tab"><?php esc_html_e( 'Appearance', 'yikes-inc-level-playing-field' ); ?></a></li>
			</ul>
			<!-- End Tabs -->

			<!-- Tab Content/Containers -->
			<div id="general-tab" class="tab-section">
				<ul>

					<!-- Field Label -->
					<li>
						<div class="container">
							<label for="field_label">
								<?php _e( 'Field Label' ,'yikes-inc-level-playing-field' ); ?>
								<a href="#" onclick="return false;" onkeypress="return false;" class="yikes_tooltip tooltip_bottomleft tooltip_form_standard_fields" data-tipso-title="<strong>Field Label</strong>" data-tipso="Enter the label of the form field.  This is the field title the user will see when filling out the form.">
									<i class="dashicons dashicons-editor-help"></i>
								</a>
							</label>
							<input type="text" class="widefat" name="field_label" value="">
						</div>
					</li>
					<!-- End Field Label -->

					<!-- Field Description -->
					<li>
						<div class="container">
							<label for="field_label">
								<?php _e( 'Field Description' ,'yikes-inc-level-playing-field' ); ?>
								<a href="#" onclick="return false;" onkeypress="return false;" class="yikes_tooltip tooltip_bottomleft tooltip_form_standard_fields" data-tipso-title="<strong>Field Description</strong>" data-tipso="Enter the description for the form field.  This will be displayed to the user and provide some direction on how the field should be filled out or selected.">
									<i class="dashicons dashicons-editor-help"></i>
								</a>
							</label>
							<textarea name="field_description" class="widefat field_description"></textarea>
						</div>
					</li>
					<!-- End Field Description -->

					<!-- Input Mask [pattern] -->
					<li>
						<div class="container">
							<label for="field_pattern">
								<?php _e( 'Input Pattern' ,'yikes-inc-level-playing-field' ); ?>
								<a href="#" onclick="return false;" onkeypress="return false;" class="yikes_tooltip tooltip_bottomleft tooltip_form_standard_fields" data-tipso-title="<strong>Input Mask</strong>" data-tipso="Input masks provide a visual guide allowing users to more easily enter data in a specific format such as dates and phone numbers.">
									<i class="dashicons dashicons-editor-help"></i>
								</a>
							</label>
							<input type="checkbox" name="field_pattern" onclick="toggleClosestHiddenContainer( this );" value="1" <?php checked( 0, 1 ); ?>>

							<!-- Hidden patterns -->
							<div class="hidden_section input_pattern_hidden_section">
								<label for="input_pattern[]">
									<input type="radio" name="input_pattern[]" onchange="togglePatternContainer( this, 'standard' );" value="1" <?php checked( 1, 1 ); ?> />
									<?php _e( 'Standard Pattern', 'yikes-inc-level-playing-field' ); ?>
								</label>
								<label for="input_pattern[]">
									<input type="radio" name="input_pattern[]" onchange="togglePatternContainer( this, 'custom' );" value="1" />
									<?php _e( 'Custom Pattern', 'yikes-inc-level-playing-field' ); ?>
								</label>
								<!-- Standard Patterns -->
								<div class="pattern-select standard-pattern" style="display:block;">
									<select class="widefat">
										<option>Test 1</option>
										<option>Test 2</option>
									</select>
								</div>
								<!-- End Standard Patterns -->
								<!-- Custom Patterns -->
								<div class="pattern-select custom-pattern" style="display:none;">
									<input type="text" value="" class="widefat">
								</div>
								<!-- End Custom Patterns -->
							</div>
							<!-- end Hidden patterns -->

						</div>
					</li>
					<!-- End Input Mask [pattern] -->

					<!-- Additional Rules -->
					<li>
						<div class="container">
							<label for="additional_rules">
								<?php _e( 'Additional Rules' ,'yikes-inc-level-playing-field' ); ?>
								<a href="#" onclick="return false;" onkeypress="return false;" class="yikes_tooltip tooltip_bottomleft tooltip_form_standard_fields" data-tipso-title="<strong>Input Mask</strong>" data-tipso="Input masks provide a visual guide allowing users to more easily enter data in a specific format such as dates and phone numbers.">
									<i class="dashicons dashicons-editor-help"></i>
								</a>
							</label>
							<p>
								<input type="checkbox" name="field_required" value="1" <?php checked( 0, 1 ); ?>>
								<?php _e( 'Field Required', 'yikes-inc-level-playing-field' ); ?>
							</p>
							<input type="checkbox" name="field_no_duplicates" value="1" <?php checked( 0, 1 ); ?>>
							<?php _e( 'Reject Duplicates', 'yikes-inc-level-playing-field' ); ?>
						</div>
					</li>
					<!-- End Additional Rules -->

				</ul>
			</div>

			<!-- Begin Appearance Tab -->
			<div id="appearance-tab" class="tab-section">
				<ul>

					<!-- Field Placeholder -->
					<li>
						<div class="container">
							<label for="field_placeholder">
								<?php _e( 'Placeholder' ,'yikes-inc-level-playing-field' ); ?>
								<a href="#" onclick="return false;" onkeypress="return false;" class="yikes_tooltip tooltip_bottomleft tooltip_form_standard_fields" data-tipso-title="<strong>Placeholder</strong>" data-tipso="The Placeholder will not be submitted along with the form. Use the Placeholder to give a hint at the expected value or format.">
									<i class="dashicons dashicons-editor-help"></i>
								</a>
							</label>
							<input type="text" class="widefat" name="field_placeholder" value="">
						</div>
					</li>
					<!-- End Field Placeholder -->

					<!-- Custom CSS Class -->
					<li>
						<div class="container">
							<label for="field_css_classes">
								<?php _e( 'Custom CSS Class' ,'yikes-inc-level-playing-field' ); ?>
								<a href="#" onclick="return false;" onkeypress="return false;" class="yikes_tooltip tooltip_bottomleft tooltip_form_standard_fields" data-tipso-title="<strong>CSS Class Name</strong>" data-tipso="Enter the CSS class name you would like to use in order to override the default styles for this field.">
									<i class="dashicons dashicons-editor-help"></i>
								</a>
							</label>
							<input type="text" class="widefat" name="field_css_classes" value="">
						</div>
					</li>
					<!-- End Custom CSS Class -->

				</ul>
			</div>
			<!-- End Appearance Tab -->

			<!-- End Tab Content/Containers -->
		</div>
		<!-- End Tabs Container -->

	</div>
</li>
