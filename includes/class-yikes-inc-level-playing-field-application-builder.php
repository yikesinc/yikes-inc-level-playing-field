<?php
/**
 * Application Builder Class
 * @since 1.0.0
 */
class Yikes_Inc_Level_Playing_Field_Application_Builder extends Yikes_Inc_Level_Playing_Field_Public {

	private $helpers;

	// Constructor
	public function __construct( $helpers ) {
		// Store our helpers, for later use
		$this->helpers = $helpers;
		$this->enqueue_application_builder_scripts_and_styles();
		$this->generate_application_builder_containers();
	}

	/**
	 * Enqueue Scripts and Styles on our application builder page
	 * @return null
	 */
	public function enqueue_application_builder_scripts_and_styles() {
		wp_enqueue_style( 'level-playing-field-application-builder-styles', YIKES_LEVEL_PLAYING_FIELD_URL . 'admin/css/min/yikes-inc-level-playing-field-application-builder.min.css' );

		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'level-playing-field-application-builder-scripts', YIKES_LEVEL_PLAYING_FIELD_URL . 'admin/js/min/yikes-inc-level-playing-field-application-builder.min.js', array( 'jquery-ui-droppable', 'jquery-ui-draggable', 'jquery-ui-core' ), YIKES_LEVEL_PLAYING_FIELD_VERSION, true );
		wp_localize_script( 'level-playing-field-application-builder-scripts', 'script_data', array(
			'preloader' => wp_kses_post( '<img src="' . esc_url( admin_url( '/images/wpspin_light.gif' ) ) . '" class="application_builder_preloader">' ),
		) );
	}
	/**
	 * Generate the application builder containers/wrappers
	 * @return mixed HTML content for the application builder.
	 */
	public function generate_application_builder_containers() {
		?>
		<div class="wrap">

			<div id="icon-options-general" class="icon32"></div>

			<div id="poststuff">

				<div id="post-body" class="metabox-holder columns-2">

					<!-- main content -->
					<div id="post-body-content">

						<div class="meta-box-sortables ui-sortable">

							<div class="postbox">

								<h2><span><?php esc_attr_e( 'Job Application', 'yikes-inc-level-playing-field' ); ?></span></h2>

								<div class="inside">
									<div id="droppable"></div>
								</div>
								<!-- .inside -->

							</div>
							<!-- .postbox -->

						</div>
						<!-- .meta-box-sortables .ui-sortable -->

					</div>
					<!-- post-body-content -->

					<!-- sidebar -->
					<div id="postbox-container-1" class="postbox-container">

						<div class="meta-box-sortables">

							<div class="postbox">

								<div class="inside">
									<?php	$this->generate_available_application_fields(); ?>
								</div>
								<!-- .inside -->

							</div>
							<!-- .postbox -->

						</div>
						<!-- .meta-box-sortables -->

					</div>
					<!-- #postbox-container-1 .postbox-container -->

				</div>
				<!-- #post-body .metabox-holder .columns-2 -->

				<br class="clear">
			</div>
			<!-- #poststuff -->

		</div> <!-- .wrap -->
		<?php
	}

	/** Generate the available fields, so the user can add them to their form **/
	public function generate_available_application_fields() {
		$available_fields = $this->helpers->get_available_application_fields();
		if ( ! $available_fields || empty( $available_fields ) ) {
			return __( 'There was an error retreiving the application fields.', 'yikes-inc-level-playing-field' );
		}
		$count = 1;
		?>
		<ul id="sidebarmenu1" class="menu collapsible expandfirst">
			<?php
			// Loop over and display the possible application fields
			foreach ( $available_fields as $field_section => $fields_data ) {
				$field_section_title_slug = str_replace( '-', '_', sanitize_title( $field_section ) );
				$active_class = ( 1 === $count ) ? ' yikes_button_title_active' : '';
				?>
					<li id="add_<?php echo esc_attr( $field_section_title_slug ); ?>" class="add_field_button_container">

						<!-- Begin Section Title -->
						<div class="button-title-link<?php echo esc_attr( $active_class ); ?>">
							<div class="add-buttons-title">
								<!-- Section Title -->
								<?php echo esc_html( $field_section ); ?>
								<a href="#" onclick="return false;" onkeypress="return false;" class="yikes_tooltip tooltip_bottomleft tooltip_form_<?php echo esc_attr( $field_section_title_slug ); ?>" title="<h6>Standard Fields</h6>Standard Fields provide basic form functionality.">
									<i class="dashicons dashicons-editor-help"></i>
								</a>
								<!-- Tooltip Icon -->
								<span class="right-pull">
									<i class="dashicons dashicons-arrow-down"></i>
								</span>
							</div>
						</div>
						<!-- End Section Title -->

						<!-- Begin Button Containers -->
						<ul style="display: block;">
							<li class="add-buttons">
								<ol class="field_type">
									<?php
									foreach ( $fields_data as $field ) {
										?>
										<li>
											<input type="button" class="button draggable" data-type="<?php echo esc_attr( $field['type'] ); ?>" value="<?php echo esc_attr( $field['label'] ); ?>">
										</li>
									<?php
									}
									?>
							</li>
						</ul>
						<!-- End Button Containers -->

					</li>
				<?php
				$count++;
			}
		?></ul><?php
	}

	public function render_application_field() {
		echo 'test';
	}
}
