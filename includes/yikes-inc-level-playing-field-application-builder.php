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
		wp_enqueue_script( 'level-playing-field-application-builder-scripts', YIKES_LEVEL_PLAYING_FIELD_URL . 'admin/js/min/yikes-inc-level-playing-field-application-builder.min.js', array( 'jquery'), YIKES_LEVEL_PLAYING_FIELD_VERSION, true );
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
									<p><?php echo 'Application builder here.'; ?></p>
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

								<h2><span><?php esc_attr_e(
									'Application Fields', 'yikes-inc-level-playing-field'
								); ?></span></h2>

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
			return 'There was an error retreiving the application fields.';
		}
		// Loop over and display the possible application fields
		foreach ( $available_fields as $application_field => $field_data ) {
			echo $field_data['type'] . '<br />';
		}
	}

	public function render_application_field() {
		echo 'test';
	}
}
