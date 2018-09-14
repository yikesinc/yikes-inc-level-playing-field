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
		$applicant = new Applicant( get_post() );

		// Trigger loading of applicant data.
		$applicant->schooling;

		// @todo: this isn't the proper way to display the taxonomy box; change it.
		$status = new ApplicantStatus();
		$status->meta_box_cb( get_post() );

		// Placeholder data.
		$applicant->schooling = [
			[
				'degree' => 'Diploma',
				'type'   => 'High School',
				'major'  => 'n/a',
			],
			[
				'degree' => 'B.S.',
				'type'   => 'College',
				'major'  => 'Accounting',
			],
		];
		?>

		<article id="single-applicant-view">
			<section id="header">
				<?php echo $applicant->get_avatar_img( 350 ); // XSS ok. ?>
				<h5>Nickname 123</h5>
				<h5><span class="label">Job:</span>
					Job Title</h5>
			</section>
			<section id="basic-info">
				<h2>Basic Info</h2>
				<p class="location"><span class="label">Location:</span>
					City,
					State</p>
			</section>
			<section id="education">
				<h2>Education</h2>
				<h5>Schooling</h5>
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
				<h5>Certifications</h5>
				<ol>
					<li>Certified in [ certification ] from [ institution type ]. Status: [ status ]</li>
					<li>Certified in [ certification ] from [ institution type ]. Status: [ status ]</li>
					<li>Certified in [ certification ] from [ institution type ]. Status: [ status ]</li>
				</ol>
			</section>
			<section id="skills">
				<h2>Skills</h2>
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
			<section id="languages">
				<h2>Languages</h2>
				<h5>Multingual</h5>
				<ol>
					<li>[ fluency ] x languages</li>
					<li>Fluent in 2 languages</li>
					<li>Limited proficiency in 1 language</li>
				</ol>
			</section>
			<section id="experience">
				<h2>Experience</h2>
				<ol>
					<li>[ position ] in [ industry ] for x years</li>
					<li>[ position ] in [ industry ] for x years</li>
					<li>[ position ] in [ industry ] for x years</li>
				</ol>
			</section>
			<section id="volunteer-work">
				<h2>Volunteer Work</h2>
				<ol>
					<li>[ position ] in [ organization type ] for x years</li>
					<li>[ position ] in [ organization type ] for x years</li>
				</ol>
			</section>
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
		</article>
		
		
		<?php
	}

	/**
	 * Set the number of screen columns to 1.
	 *
	 * @since %VERSION%
	 */
	private function set_screen_columns() {
		$screen = get_current_screen();
		if ( $this->get_post_type() !== $screen->post_type ) {
			return;
		}

		$screen->add_option( 'layout_columns', [
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
}
