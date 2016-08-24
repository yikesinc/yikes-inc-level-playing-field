<?php
/**
 * Text Field HTML Markup
 * @since 1.0.0
 */
?>
<li class="yikes-field-container js-field-container">

	<!-- Field Header -->
	<div class="yikes_admin_icons">
		<div class="yikes_admin_header_title">
			<?php esc_attr_e( 'Single Line Text', 'yikes-inc-level-playing-field' ); ?>
		</div>
		<a class="field_delete_icon" id="yikes_field_delete_1" title="click to delete this field" href="#" onclick="StartDeleteField(this); return false;" onkeypress="StartDeleteField(this); return false;">
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
		<label class="field-label">Untitled</label>
		<input type="text" value="" disabled="disabled" />
		<!-- End From Type Preview -->

		<div class="yikes-tabs tab-container">
			<!-- Display Tabs -->
			<ul class="ui-widget-header">
				<li><a href="#tabs-1"><?php esc_html_e( 'General', 'yikes-inc-level-playing-field' ); ?></a></li>
				<li><a href="#tabs-2"><?php esc_html_e( 'Appearance', 'yikes-inc-level-playing-field' ); ?></a></li>
				<li><a href="#tabs-3"><?php esc_html_e( 'Advanced', 'yikes-inc-level-playing-field' ); ?></a></li>
			</ul>
			<!-- End Tabs -->

			<!-- Tab Content/Containers -->
			<div id="tabs-1">
				<p>This is going to be general settings specific to this field.</p>
			</div>
			<div id="tabs-2">
				<p>This is going to be apperance settings (classes, etc. etc.) specific to this field.</p>
			</div>
			<div id="tabs-3">
				<p>This is going to be advanced settings. Not exactly sure what yet, but something cool.</p>
			</div>
			<!-- End Tab Content/Containrs -->
		</div>
		<!-- End Tabs Container -->

	</div>
</li>
