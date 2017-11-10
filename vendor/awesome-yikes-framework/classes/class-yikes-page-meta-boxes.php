<?php
/**
 * Class File
 *
 * Description.
 *
 * @link http://www.yikesinc.com
 *
 * @package WordPress
 * @subpackage Component
 * @since 0.9
 **/

/**
 * YIKES_Page_Meta_Boxes Class File
 *
 * Create Custom Metaboxes for Pages/Options
 */
class YIKES_Page_Meta_Boxes {

	/**
	 * The pages where our metaboxes will be displayed.
	 *
	 * @var array $option_pages
	 */
	public $option_pages;

	/**
	 * The metaboxes for our pages.
	 *
	 * @var array $poboxs
	 */
	public $poboxs;

	/**
	 * The fields that do not have data stored with them.
	 *
	 * @var array $no_data_fields
	 */
	public $no_data_fields;

	/**
	 * Construct Function
	 */
	public function __construct() {

		// Define our class properties.
		$this->option_pages   = apply_filters( 'yks_option_pages', array() );
		$this->poboxs         = apply_filters( 'yks_mbox_fields', array() );
		$this->no_data_fields = array( 'title' => 'title', 'message' => 'message' );

		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'register_mysetting' ) );
	}

	/**
	 * Add menu/submenu pages
	 */
	public function add_plugin_page() {

		foreach ( $this->option_pages as $mbox ) {

			if ( 'plugin' === $mbox['type'] ) {

				// Create a new top level menu item.
				add_menu_page( $mbox['page_title'], $mbox['menu_title'], 'administrator', $mbox['id'], array( $this, 'the_plugin_page' ), $mbox['icon'], $mbox['position'] );
			} elseif ( 'theme' === $mbox['type'] ) {

				// Add a submenu item to themes.
				add_submenu_page( 'themes.php', $mbox['page_title'], $mbox['menu_title'], 'administrator', $mbox['id'], array( $this, 'the_plugin_page' ) );
			} elseif ( 'settings' === $mbox['type'] ) {

				// Add a submenu item to options.
				add_submenu_page( 'options-general.php', $mbox['page_title'], $mbox['menu_title'], 'administrator', $mbox['id'], array( $this, 'the_plugin_page' ) );
			}
		}
	}

	/**
	 * Register new settings for page function
	 */
	public function register_mysetting() {

		foreach ( $this->poboxs as $mbox ) {
			if ( isset( $mbox['id'] ) && ! empty( $mbox['id'] ) ) {
				if ( isset( $mbox['fields'] ) && ! empty( $mbox['fields'] ) ) {
					foreach ( $mbox['fields'] as $field ) {

						$field['id'] = isset( $field['id'] ) ? $field['id'] : '';

						// We do not need to register the setting or add an option for these fields. Skip them.
						if ( isset( $this->no_data_fields[ $field['type'] ] ) ) {
							continue;
						}

						add_option( $field['id'] );

						if ( function_exists( 'yks_save_' . $field['type'] ) ) {

							register_setting( $mbox['page'] . '-set-group', $field['id'], array( 'sanitize_callback' => 'yks_save_' . $field['type'] ) );
						} elseif ( 'file' === $field['type'] ) {

							register_setting( $mbox['page'] . '-set-group', $field['id'] );

							add_option( $field['id'] . '_id' );
							register_setting( $mbox['page'] . '-set-group', $field['id'] . '_id' );
						} elseif ( 'text_group_slides' === $field['type'] ) {
							foreach ( range( 1, $field['limit'] ) as $number ) {
								register_setting( $mbox['page'] . '-set-group', $field['id'] . '_sort_' . $number );
								register_setting( $mbox['page'] . '-set-group', $field['id'] . '_one_' . $number );
								register_setting( $mbox['page'] . '-set-group', $field['id'] . '_two_' . $number );
								register_setting( $mbox['page'] . '-set-group', $field['id'] . '_three_' . $number );
								register_setting( $mbox['page'] . '-set-group', $field['id'] . '_four_' . $number );
								register_setting( $mbox['page'] . '-set-group', $field['id'] . '_five_' . $number );
								register_setting( $mbox['page'] . '-set-group', $field['id'] . '_six_' . $number );
							}
						} else {
							register_setting( $mbox['page'] . '-set-group', $field['id'] );
						}
					}
				}
			}
		}
	}

	/**
	 * Register new plugin page function
	 */
	public function the_plugin_page() {

		foreach ( $this->option_pages as $mbox ) :

			if ( isset( $_GET['page'] ) ) {
				// @codingStandardsIgnoreStart
				if ( ! preg_match( '/^[\w\-]+$/', $_GET['page'] ) ) { // Input var okay.
					// @codingStandardsIgnoreEnd
					wp_die();
				}
			}
			if ( isset( $_GET['settings-updated'] ) ) {

				// @codingStandardsIgnoreStart
				if ( ! preg_match( '/^[\w\-]+$/',  $_GET['settings-updated'] ) ) { // Input var okay.
					// @codingStandardsIgnoreEnd
					wp_die();
				}
			}

			if ( (string) $_GET['page'] === (string) $mbox['id'] ) {
				echo '<div class="wrap">';
				echo '<h2>' . esc_html( $mbox['page_title'] ) . '</h2>';
				echo '<form method="post" action="options.php">';
				echo '<div id="poststuff">';
				if ( isset( $_GET['settings-updated'] ) && ( 'theme' === $mbox['type'] || 'plugin' === $mbox['type'] ) ) {
					echo '<div id="message" class="updated">';
					echo '<p><strong>Settings saved.</strong></p>';
					echo '</div>';
				}
				echo '<div id="post-body" class="metabox-holder columns-2">';
				echo '<div id="post-body-content">';
				wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
				wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
				settings_fields( $mbox['id'] . '-set-group' );
				do_settings_sections( $mbox['id'] . '-set-group' );
				$this->yks_add( $mbox['id'], 'normal' );
				echo '</div>'; /* post-body-content **/
				echo '<div id="postbox-container-1" class="postbox-container">';
				$mbox['page'] = isset( $mbox['page'] ) ? $mbox['page'] : '';
				add_meta_box( $mbox['id'] . 'save', 'Save Options', array( &$this, 'yks_submit_btn' ), $mbox['page'], 'submit-btn', null );
				do_meta_boxes( $mbox['page'], 'submit-btn', null );
				$this->yks_add( $mbox['id'], 'side' );
				echo '</div>'; /* postbox-container-1 **/
				echo '</div>'; /* post-body **/
				echo '</div>'; /* postuff **/
				echo '</form>';
				echo '</div>'; /* wrap **/
			}
		endforeach;
	}
	/**
	 * Submit Button
	 **/
	public function yks_submit_btn() {
		submit_button();
	}

	/**
	 * Add metaboxes
	 *
	 * @param protected $the_id Page id.
	 * @param protected $position Placement of metabox in page.
	 **/
	public function yks_add( $the_id, $position ) {
		$metaboxesdone = array();
		foreach ( $this->poboxs as $page ) {

			if ( (string) $the_id === $page['page'] && (string) $position === $page['context'] ) {

				$this->_mbox['page']    = $page['page'];
				$this->_mbox['id']      = $page['id'];
				$this->_mbox['context'] = $page['context'];

				if ( ! in_array( $page['id'], $metaboxesdone, true ) ) {
					add_meta_box( $page['id'], $page['title'], array( &$this, 'displayfields' ), $page['page'], $page['id'], $page['priority'] );
					$metaboxesdone[] = $page['id'];
					do_meta_boxes( $page['page'], $page['id'], null );
				}
			}
		}
	}
	/** Show fields **/
	public function displayfields() {

		/* Use nonce for verification **/
		foreach ( $this->poboxs as $page ) :
			if ( $page['page'] === $this->_mbox['page'] && (string) $this->_mbox['id'] === (string) $page['id'] && $this->_mbox['context'] === $page['context'] && ! empty( $page['context'] ) ) :
				echo '<input type="hidden" name="wp_meta_box_nonce" value="' . esc_attr( wp_create_nonce( basename( __FILE__ ) ) ) . '" />';
				echo '<table class="form-table yks_mbox">';
				foreach ( $page['fields'] as $field ) :

					// Check if template for field exsists.
					if ( stream_resolve_include_path( dirname( dirname( __FILE__ ) ) . '/inc/fields/yks-' . str_replace( '_', '-', $field['type'] ) . '.php' ) !== false ) :

						// Set up blank or default values for empty ones.
						$field['name'] = isset( $field['name'] ) ? $field['name'] : '';
						$field['desc'] = isset( $field['desc'] ) ? $field['desc'] : '';
						$field['std']  = isset( $field['std'] ) ? $field['std'] : '';
						$field['id']   = isset( $field['id'] ) ? $field['id'] : '';
						$meta          = get_option( $field['id'], '' );

						echo '<tr>';

						if ( 'title' === $field['type'] ) {
							echo '<td colspan="2">';
						} else {
							if ( true === $page['show_names'] ) {
								echo '<th style="width:18%"><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['name'] ) . '</label></th>';
							}
							echo '<td>';
						}
						include dirname( dirname( __FILE__ ) ) . '/inc/fields/yks-' . str_replace( '_', '-', $field['type'] ) . '.php';
						do_action( 'yks_render_' . $field['type'], $field, $meta );
						echo '</td>','</tr>';
					endif;

				endforeach;

			endif;

		endforeach;

		echo '</table>';
	}
}
/* End class */

/* init */
if ( current_user_can( 'manage_options' ) ) {
	$yks_opages       = apply_filters( 'yks_option_pages', array() );
	$yks_opages_mboxs = apply_filters( 'yks_mbox_fields', array() );

	if ( ! empty( $yks_opages ) && ! empty( $yks_opages_mboxs ) ) {
		new YIKES_Page_Meta_Boxes();
	}
}
