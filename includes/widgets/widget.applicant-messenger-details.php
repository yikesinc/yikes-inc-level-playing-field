<?php
/**
 * Applicant Details widget
 * This is the applicant details, with details about the position etc.
 * Displays on the applicant messenger sidebar, else it returns false.
 *
 * @since 1.0.0
 */
class lpf_applicant_details_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'lpf_applicant_details_widget',
			// Widget name will appear in UI
			__( 'Applicant Messenger Details', 'yikes-inc-level-playing-field' ),
			// Widget description
			array(
				'description' => __( 'Display details about the current applicant you are conversing with. This includes submission date, applicant ID, job name etc.', 'yikes-inc-level-playing-field' ),
			)
		);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) ) :
			echo $args['before_title'] . $title . $args['after_title'];
		endif;

		// This is where you run the code and display the output
		echo esc_attr__( 'This is going to be the applicant details.', 'yikes-inc-level-playing-field' );
		echo $args['after_widget'];
	}

	// Widget Backend
	public function form( $instance ) {
		$title = ( isset( $instance['title'] ) ) ? $instance_title : __( 'Applicant Details', 'yikes-inc-level-playing-field' );
		// Widget admin form
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'yikes-inc-level-playing-field' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // Class lpf_applicant_details_widget ends here

// Register and load the widget
function lpf_load_widget() {
	register_widget( 'lpf_applicant_details_widget' );
}
add_action( 'widgets_init', 'lpf_load_widget' );
