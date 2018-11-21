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
use Yikes\LevelPlayingField\Model\ApplicantRepository;
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
	 * Output the Applicant content.
	 *
	 * @since %VERSION%
	 */
	private function do_applicant_content() {
		$applicant = ( new ApplicantRepository() )->find( get_the_ID() );
		$job       = ( new JobRepository() )->find( $applicant->get_job_id() );

		?>
		<article id="single-applicant-view">
			<section id="header">
				<?php echo $applicant->get_avatar_img( 160 ); // XSS ok. ?>
				<h5>
					<span class="label"><?php esc_html_e( 'Nickname:', 'yikes-level-playing-field' ); ?></span>
					<?php echo esc_html( $applicant->get_nickname() ); ?>
				</h5>
				<h5>
					<span class="label">Job:</span>
					<?php echo esc_html( $job->get_title() ); ?>
				</h5>
			</section>
			<?php do_action( "lpf_{$this->get_post_type()}_after_header", $applicant, $job ); ?>
			<section id="basic-info">
				<h2><?php esc_html_e( 'Basic Info', 'yikes-level-playing-field' ); ?></h2>
				<p class="location"><span class="label">Location:</span>
					City,
					State</p>
				<p class="cover-letter">
					<span class="label">Cover Letter:</span>
					<a href="#">View Cover Letter</a>
				</p>
				<div class="cover-letter-content">
					<?php echo esc_html( $applicant->get_cover_letter() ); ?>
				</div>
			</section>
			<?php do_action( "lpf_{$this->get_post_type()}_after_basic_info", $applicant, $job ); ?>
			<section id="education">
				<h2><?php esc_html_e( 'Education', 'yikes-level-playing-field' ); ?></h2>
				<h5><?php esc_html_e( 'Schooling', 'yikes-level-playing-field' ); ?></h5>
				<ol>
					<?php
					foreach ( $applicant->get_schooling() as $schooling ) {
						printf(
							'<li>Graduated with a [%s] from [%s] with a major in [%s]</li>',
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
							'<li>Certified in [%s] from [%s]. Status: [%s]</li>',
							esc_html( $certification['institution'] ),
							esc_html( $certification['type'] ),
							esc_html( $certification['status'] )
						);
					}
					?>
				</ol>
			</section>
			<?php do_action( "lpf_{$this->get_post_type()}_after_education", $applicant, $job ); ?>
			<section id="skills">
				<h2><?php esc_html_e( 'Skills', 'yikes-level-playing-field' ); ?></h2>
				<table>
					<tr>
						<th>Skill</th>
						<th>Proficiency</th>
					</tr>
					<tr>
						<td>[ skill ]</td>
						<td>[ proficiency ]</td>
					</tr>
					<tr>
						<td>[ skill ]</td>
						<td>[ proficiency ]</td>
					</tr>
					<tr>
						<td>[ skill ]</td>
						<td>[ proficiency ]</td>
					</tr>
				</table>
			</section>
			<?php do_action( "lpf_{$this->get_post_type()}_after_skills", $applicant, $job ); ?>
			<section id="languages">
				<h2><?php esc_html_e( 'Languages', 'yikes-level-playing-field' ); ?></h2>
				<h5><?php esc_html_e( 'Multingual', 'yikes-level-playing-field' ); ?></h5>
				<ol>
					<li>[ fluency ] x languages</li>
					<li>Fluent in 2 languages</li>
					<li>Limited proficiency in 1 language</li>
				</ol>
			</section>
			<?php do_action( "lpf_{$this->get_post_type()}_after_languages", $applicant, $job ); ?>
			<section id="experience">
				<h2><?php esc_html_e( 'Experience', 'yikes-level-playing-field' ); ?></h2>
				<ol>
					<?php
					foreach ( $applicant->get_job_experience() as $experience ) {
						printf(
							'<li>[ %s ] in [ %s ] for x years</li>',
							esc_html( $experience['position'] ),
							esc_html( $experience['industry'] ),
							esc_html( $experience['dates'] )
						);
					}
					?>
				</ol>
			</section>
			<?php do_action( "lpf_{$this->get_post_type()}_after_experience", $applicant, $job ); ?>
			<section id="volunteer-work">
				<h2><?php esc_html_e( 'Volunteer Work', 'yikes-level-playing-field' ); ?></h2>
				<ol>
					<li>[ position ] in [ organization type ] for x years</li>
					<li>[ position ] in [ organization type ] for x years</li>
				</ol>
			</section>
			<?php do_action( "lpf_{$this->get_post_type()}_after_volunteer_work", $applicant, $job ); ?>
			<section id="misc">
				<h2>Miscellaneous</h2>
				<p><span class="label">Question:</span>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit?</p>
				<p><span class="label">Answer:</span>
					Vivamus nec ex volutpat, porta libero ut, malesuada lectus.</p>
				<p><span class="label">Question:</span>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit?</p>
				<p><span class="label">Answer:</span>
					Vivamus nec ex volutpat, porta libero ut, malesuada lectus.</p>
			</section>
			<?php do_action( "lpf_{$this->get_post_type()}_after_misc", $applicant, $job ); ?>
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
			'title' => _x( 'Applicants | Applicant ID', 'heading when viewing an applicant', 'yikes-level-playing-field' ),
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
