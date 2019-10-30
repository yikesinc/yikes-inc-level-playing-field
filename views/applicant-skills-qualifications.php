<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Model\ApplicantMeta;
use Yikes\LevelPlayingField\Model\Applicant;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/** @var Applicant $applicant */
$applicant = $this->applicant;
?>
<!-- Applicant Skills and Qualifications -->
<div id="applicant-skills-qualifications">
	<h2 class="lpf_mbox_title"><?php esc_html_e( 'Skills and Qualifications', 'level-playing-field' ); ?></h2>
	<?php if ( ! empty( $applicant->get_schooling() ) || ! empty( $applicant->get_certifications() ) ) : ?>
	<section id="education">
		<h4 class="lpf_mbox_subtitle"><?php esc_html_e( 'Education', 'level-playing-field' ); ?></h4>
		<div class="applicant-skills-container">
		<?php if ( ! empty( $applicant->get_schooling() ) ) : ?>
			<h5><?php esc_html_e( 'Schooling', 'level-playing-field' ); ?></h5>
			<ol class="applicant-skills-schooling">
				<?php
				$type_selections = $applicant->get_schooling_options();
				foreach ( $applicant->get_schooling() as $school ) {
					echo $school;
				}
				?>
			</ol>
		<?php endif; ?>

		<?php if ( ! empty( $applicant->get_certifications() ) ) : ?>
			<h5><?php esc_html_e( 'Certifications', 'level-playing-field' ); ?></h5>
			<ol>
				<?php
				foreach ( $applicant->get_certifications() as $certification ) {
					echo $certification;
				}
				?>
			</ol>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( ! empty( $applicant->get_skills() ) ) : ?>
	<section id="skills">
		<h4 class="lpf_mbox_subtitle"><?php esc_html_e( 'Skills', 'level-playing-field' ); ?></h4>
		<div class="applicant-skills-container">
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Skill', 'level-playing-field' ); ?></th>
						<th><?php esc_html_e( 'Proficiency', 'level-playing-field' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ( $applicant->get_skills() as $skill ) {
						printf(
							'<tr><td>%s</td><td>%s</td></tr>',
							esc_html( $skill[ ApplicantMeta::SKILL ] ),
							esc_html( $skill[ ApplicantMeta::PROFICIENCY ] )
						);
					}
					?>
				</tbody>
			</table>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( ! empty( $applicant->get_languages() ) ) : ?>
	<section id="languages">
		<?php $languages = $applicant->get_languages(); ?>
		<h4 class="lpf_mbox_subtitle"><?php esc_html_e( 'Languages', 'level-playing-field' ); ?></h4>
			<div class="applicant-skills-container">
			<?php if ( $applicant->is_anonymized() ) : ?>
				<?php
				$is_multilingual = count( $languages ) > 1;

				// Build up language proficiency data.
				$proficiency_labels = $applicant->get_language_options();
				$proficiency_counts = [];

				foreach ( $languages as $language ) {
					if ( ! array_key_exists( $language[ ApplicantMeta::PROFICIENCY ], $proficiency_counts ) ) {
						$proficiency_counts[ $language[ ApplicantMeta::PROFICIENCY ] ] = 1;
						continue;
					}

					$proficiency_counts[ $language[ ApplicantMeta::PROFICIENCY ] ]++;
				}

				// Set up a counter for when we need to output a comma.
				$needs_comma = count( $proficiency_counts ) - 1;
				?>
					<p>
						<?php
						echo $is_multilingual ? esc_html__( 'Multilingual', 'level-playing-field' ) . ' &ndash; ' : '';
						foreach ( $proficiency_counts as $proficiency => $count ) {
							echo esc_html( $proficiency_labels[ $proficiency ] ), ' ';
							echo esc_html(
								sprintf(
									/* translators: %d is the number of languages for the given fluency level */
									_n( 'in %d language', 'in %d languages', $count, 'level-playing-field' ),
									$count
								)
							);
							echo $needs_comma ? ', ' : ' ';
							$needs_comma--;
						}
						?>
					</p>
			<?php else : ?>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Language', 'level-playing-field' ); ?></th>
							<th><?php esc_html_e( 'Proficiency', 'level-playing-field' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $languages as $language ) {
							printf(
								'<tr><td>%s</td><td>%s</td></tr>',
								esc_html( $language[ ApplicantMeta::LANGUAGE ] ),
								esc_html( $language[ ApplicantMeta::PROFICIENCY ] )
							);
						}
						?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( ! empty( $applicant->get_experience() ) ) : ?>
	<section id="experience">
		<h4 class="lpf_mbox_subtitle"><?php esc_html_e( 'Experience', 'level-playing-field' ); ?></h4>
		<div class="applicant-skills-container">
			<ol>
				<?php
				foreach ( $applicant->get_experience() as $experience ) {

					if ( empty( array_filter( $experience ) ) ) {
						continue;
					}

					if ( $applicant->is_anonymized() ) {
						?>
						<li>
							<?php
							echo esc_html( $experience[ ApplicantMeta::POSITION ] );
							echo ! empty( $experience[ ApplicantMeta::POSITION ] ) ? esc_html__( ' in ', 'level-playing-field' ) : '';
							echo esc_html( $experience[ ApplicantMeta::INDUSTRY ] );
							echo ! empty( $experience[ ApplicantMeta::YEAR_DURATION ] ) ? esc_html__( ' for ', 'level-playing-field' ) : '';
							echo ! empty( $experience[ ApplicantMeta::YEAR_DURATION ] ) ? esc_html( $experience[ ApplicantMeta::YEAR_DURATION ] ) : '';
							?>
						</li>
						<?php
					} else {
						?>
						<li>
							<?php
							echo esc_html( $experience[ ApplicantMeta::POSITION ] );
							echo ! empty( $experience[ ApplicantMeta::POSITION ] ) ? esc_html__( ' in ', 'level-playing-field' ) : '';
							echo esc_html( $experience[ ApplicantMeta::INDUSTRY ] );
							echo ! empty( $experience[ ApplicantMeta::ORGANIZATION ] ) ? esc_html__( ' at ', 'level-playing-field' ) : '';
							echo esc_html( $experience[ ApplicantMeta::ORGANIZATION ] );
							echo ! empty( $experience[ ApplicantMeta::START_DATE ] ) ? esc_html__( ' from ', 'level-playing-field' ) : '';
							echo esc_html( date( 'm/d/Y', strtotime( $experience[ ApplicantMeta::START_DATE ] ) ) );
							echo ! empty( $experience[ ApplicantMeta::PRESENT_POSITION ] ) || ! empty( $experience[ ApplicantMeta::END_DATE ] ) ? esc_html__( ' to ', 'level-playing-field' ) : '';
							echo esc_html( ! empty( $experience[ ApplicantMeta::PRESENT_POSITION ] ) ? __( 'the present time.', 'level-playing-field' ) : date( 'm/d/Y', strtotime( $experience[ ApplicantMeta::END_DATE ] ) ) );
							?>
						</li>
						<?php
					}
				}
				?>
			</ol>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( ! empty( $applicant->get_volunteer() ) ) : ?>
	<section id="volunteer-work">
		<h4 class="lpf_mbox_subtitle"><?php esc_html_e( 'Volunteer Work', 'level-playing-field' ); ?></h4>
		<div class="applicant-skills-container">
			<ol>
				<?php
				foreach ( $applicant->get_volunteer() as $experience ) {

					if ( empty( array_filter( $experience ) ) ) {
						continue;
					}

					if ( $applicant->is_anonymized() ) {
						?>
						<li>
							<?php
							echo esc_html( $experience[ ApplicantMeta::POSITION ] );
							echo ! empty( $experience[ ApplicantMeta::POSITION ] ) ? esc_html__( ' in ', 'level-playing-field' ) : '';
							echo esc_html( $experience[ ApplicantMeta::INDUSTRY ] );
							echo ! empty( $experience[ ApplicantMeta::YEAR_DURATION ] ) ? esc_html__( ' for ', 'level-playing-field' ) : '';
							echo ! empty( $experience[ ApplicantMeta::YEAR_DURATION ] ) ? esc_html( $experience[ ApplicantMeta::YEAR_DURATION ] ) : '';
							?>
						</li>
						<?php
					} else {
						?>
						<li>
							<?php
							echo esc_html( $experience[ ApplicantMeta::POSITION ] );
							echo ! empty( $experience[ ApplicantMeta::POSITION ] ) ? esc_html__( ' in ', 'level-playing-field' ) : '';
							echo esc_html( $experience[ ApplicantMeta::INDUSTRY ] );
							echo ! empty( $experience[ ApplicantMeta::ORGANIZATION ] ) ? esc_html__( ' at ', 'level-playing-field' ) : '';
							echo esc_html( $experience[ ApplicantMeta::ORGANIZATION ] );
							echo ! empty( $experience[ ApplicantMeta::START_DATE ] ) ? esc_html__( ' from ', 'level-playing-field' ) : '';
							echo esc_html( date( 'm/d/Y', strtotime( $experience[ ApplicantMeta::START_DATE ] ) ) );
							echo ! empty( $experience[ ApplicantMeta::PRESENT_POSITION ] ) || ! empty( $experience[ ApplicantMeta::END_DATE ] ) ? esc_html__( ' to ', 'level-playing-field' ) : '';
							echo esc_html( ! empty( $experience[ ApplicantMeta::PRESENT_POSITION ] ) ? __( 'the present time.', 'level-playing-field' ) : date( 'm/d/Y', strtotime( $experience[ ApplicantMeta::END_DATE ] ) ) );
							?>
						</li>
						<?php
					}
				}
				?>
			</ol>
		</div>
	</section>
	<?php endif; ?>
</div><!-- /applicant-skills-qualification -->
