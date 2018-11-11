<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager as ApplicantCPT;
use Yikes\LevelPlayingField\Model\Applicant;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;

/**
 * Class ApplicantManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */

final class ApplicantManager implements AssetsAware, Service {

	use AssetsAwareness;

	const CSS_HANDLE = 'lpf-admin-applicant-css';
	const CSS_URI    = 'assets/css/lpf-applicant-admin';

	/**
	 * Register the current Registerable.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		$this->register_assets();

		add_action( 'in_admin_header', function() {
			$this->set_screen_columns();
		} );

		add_action( 'edit_form_top', function() {
			if ( ! $this->is_applicant_screen() ) {
				return;
			}

			$this->enqueue_assets();
			$this->do_applicant_content();
		} );

		add_action( "add_meta_boxes_{$this->get_post_type()}", function() {
			$this->meta_boxes();
		} );

		$save_nickname = function() {
			$this->save_nickname();
		};

		add_action( 'wp_ajax_nopriv_save_nickname', $save_nickname );
		add_action( 'wp_ajax_save_nickname', $save_nickname );
	}

	/**
	 * Register our meta boxes, and remove some default boxes.
	 *
	 * @since %VERSION%
	 */
	private function meta_boxes() {
		// Remove some of the core boxes.
		remove_meta_box( 'submitdiv', $this->get_post_type(), 'side' );
		remove_meta_box( 'slugdiv', $this->get_post_type(), 'normal' );
		remove_meta_box( 'authordiv', $this->get_post_type(), 'normal' );
	}

	/**
	 * Save new nickname upon edit.
	 *
	 * @since %VERSION%
	 */
	private function save_nickname() {
		// Handle nonce.
		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( 'lpf_applicant_nonce', 'nonce', false ) ) { // Input var okay.
			wp_send_json_error();
		}

		$id = isset( $_POST['id'] ) && $_POST['id'] !== 'false' ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : false; // Input var okay.
		$nickname = isset( $_POST['nickname'] ) && $_POST['nickname'] !== 'false' ? sanitize_text_field( wp_unslash( $_POST['nickname'] ) ) : false; // Input var okay.

		$applicant = new Applicant( get_post( $id ) );
		$applicant->set_nickname( $nickname );
	}

	/**
	 * Output the Applicant content.
	 *
	 * @since %VERSION%
	 */
	private function do_applicant_content() {
		// Trigger loading of applicant and job data.
		$applicant = new Applicant( get_post() );
		$job_repo = new JobRepository();
		$job      = $job_repo->find( $applicant->get_job_id() );
		?>

		<article id="single-applicant-view">
			<section id="header">
				<?php echo $applicant->get_avatar_img( 160 ); // XSS ok. ?>
				<h5>
					<span class="label"><?php esc_html_e( 'Nickname:', 'yikes-level-playing-field' ); ?></span>
					<span id="editable-nick-name"><?php echo esc_html( $applicant->get_nickname() ); ?></span>
					<span id="edit-nickname-buttons">
						<button type="button" class="edit-nickname button button-small hide-if-no-js" aria-label="Edit nickname"><?php esc_html_e( 'Edit', 'yikes-level-playing-field' ); ?></button>
					</span>
				</h5>
				<h5>
					<span class="label"><?php esc_html_e( 'Job:', 'yikes-level-playing-field' ); ?></span>
					<?php echo esc_html( $job->get_title() ); ?>
				</h5>
				<?php
				// @todo: this isn't the proper way to display the taxonomy box; change it.
				$status = new ApplicantStatus();
				$status->meta_box_cb( get_post() );
				?>
			</section>
			<section id="basic-info">
				<h2><?php esc_html_e( 'Basic Info', 'yikes-level-playing-field' ); ?></h2>
				<p class="location"><span class="label"><?php esc_html_e( 'Location:', 'yikes-level-playing-field' ); ?></span>
					City,
					State</p>
				<p class="cover-letter">
					<span class="label"><?php esc_html_e( 'Cover Letter:', 'yikes-level-playing-field' ); ?></span>
					<a href="#"><?php esc_html_e( 'View Cover Letter', 'yikes-level-playing-field' ); ?></a>
				</p>
				<?php
				// @todo: Should HTML be allowed in the cover letter?
				?>
				<div class="cover-letter-content">
					<?php echo $applicant->get_cover_letter(); ?>
				</div>
			</section>
			<section id="education">
				<h2><?php esc_html_e( 'Education', 'yikes-level-playing-field' ); ?></h2>
				<h5><?php esc_html_e( 'Schooling', 'yikes-level-playing-field' ); ?></h5>
				<ol>
					<?php
					foreach ( $applicant->get_schooling() as $schooling ) {
						printf(
							'<li>Graduated with a %s from %s with a major in %s</li>',
							esc_html( $schooling['degree'] ),
							esc_html( $schooling['type'] ),
							esc_html( $schooling['major'] )
						);
					}
					?>
				</ol>
				<h5><?php esc_html_e( 'Certifications', 'yikes-level-playing-field' ); ?></h5>
				<ol>
					<?php
					foreach ( $applicant->get_certifications() as $certification ) {
						printf(
							'<li>Certified in %s from %s. Status: %s</li>',
							esc_html( $certification['certification'] ),
							esc_html( $certification['type'] ),
							esc_html( $certification['status'] )
						);
					}
					?>
				</ol>
			</section>
			<section id="skills">
				<h2><?php esc_html_e( 'Skills', 'yikes-level-playing-field' ); ?></h2>
				<table>
					<?php
					foreach ( $applicant->get_skills() as $skill ) {
						?>
						<tr>
							<td><?php echo esc_html( $skill['skill'] ); ?></td>
							<td><?php echo esc_html( $skill['proficiency'] ); ?></td>
						</tr>
						<?php
					}
					?>
				</table>
			</section>
			<section id="languages">
				<h2><?php esc_html_e( 'Languages', 'yikes-level-playing-field' ); ?></h2>
				<h5><?php esc_html_e( 'Multilingual', 'yikes-level-playing-field' ); ?></h5>
				<ol>
					<li>[ fluency ] x languages</li>
					<li>Fluent in 2 languages</li>
					<li>Limited proficiency in 1 language</li>
				</ol>
			</section>
			<section id="experience">
				<h2><?php esc_html_e( 'Experience', 'yikes-level-playing-field' ); ?></h2>
				<ol>
					<?php
					foreach ( $applicant->get_job_experience() as $experience ) {
						printf(
							'<li>%s in %s for x years</li>',
							esc_html( $experience['position'] ),
							esc_html( $experience['industry'] ),
							esc_html( $experience['dates'] )
						);
					}
					?>
				</ol>
			</section>
			<section id="volunteer-work">
				<h2><?php esc_html_e( 'Volunteer Work', 'yikes-level-playing-field' ); ?></h2>
				<ol>
					<?php
					foreach ( $applicant->get_volunteer_work() as $experience ) {
						printf(
							'<li>%s in %s for x years</li>',
							esc_html( $experience['organization'] ),
							esc_html( $experience['position'] )
						);
					}
					?>
				</ol>
			</section>
			<?php
			// @todo: Misc is a pro feature. Might need to add check.
			?>
			<section id="misc"></section>
		</article>
		<?php
	}

	/**
	 * Set the number of screen columns to 1.
	 *
	 * @since %VERSION%
	 */
	private function set_screen_columns() {
		if ( ! $this->is_applicant_screen() ) {
			return;
		}

		add_screen_option( 'layout_columns', [
			'default' => 1,
			'max'     => 1,
		] );
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		$applicant = new ScriptAsset( 'lpf-applicant-manager-js', 'assets/js/applicant-manager', [ 'jquery' ] );
		$applicant->add_localization( 'applicantManager', [
			'cancel'  => _x( 'Cancel', 'yikes-level-playing-field' ),
			'hide'  => _x( 'Hide Cover Letter', 'yikes-level-playing-field' ),
			'ok'  => _x( 'OK', 'yikes-level-playing-field' ),
			'nonce' => wp_create_nonce( 'lpf_applicant_nonce' ),
			'title' => _x( 'Applicants | Applicant ID', 'heading when viewing an applicant', 'yikes-level-playing-field' ),
			'view'  => _x( 'View Cover Letter', 'yikes-level-playing-field' ),
		] );

		return [
			$applicant,
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
		];
	}

	/**
	 * Get the post type.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	private function get_post_type() {
		return ApplicantCPT::SLUG;
	}

	/**
	 * Determine we're on the applicant screen.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	private function is_applicant_screen() {
		if ( ! is_admin() || ! function_exists( 'get_current_screen' ) ) {
			return false;
		}

		return $this->get_post_type() === get_current_screen()->post_type;
	}
}
