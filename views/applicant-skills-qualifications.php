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
	<h2 class="lpf_mbox_title"><?php esc_html_e( 'Skills and Qualifications', 'yikes-level-playing-field' ); ?></h2>
	<?php if ( ! empty( $applicant->get_schooling() ) || ! empty( $applicant->get_certifications() ) ) : ?>
	<section id="education">
		<h4 class="lpf_mbox_subtitle"><?php esc_html_e( 'Education', 'yikes-level-playing-field' ); ?></h4>
		<div class="applicant-skills-container">
		<?php if ( ! empty( $applicant->get_schooling() ) ) : ?>
			<h5><?php esc_html_e( 'Schooling', 'yikes-level-playing-field' ); ?></h5>
			<ol class="applicant-skills-schooling">
				<?php
				$type_selections = $applicant->get_schooling_options();
				foreach ( $applicant->get_schooling() as $schooling ) {
					if ( 'high_school' === $schooling['type'] ) {
						if ( $applicant->is_anonymized() ) {
							printf(
								'<li>%s</li>',
								esc_html__( 'Graduated from High School or High School equivalent', 'yikes-level-playing-field' )
							);
						} else {
							printf(
								'<li>Graduated from %s (High School or High School equivalent) in %s</li>',
								esc_html( $schooling[ ApplicantMeta::INSTITUTION ] ),
								esc_html( $schooling[ ApplicantMeta::YEAR ] )
							);
						}
					} else {
						if ( $applicant->is_anonymized() ) {
							printf(
								'<li>Graduated with a %s from %s with a major in %s</li>',
								esc_html( $schooling[ ApplicantMeta::DEGREE ] ),
								esc_html( $type_selections[ $schooling['type'] ] ),
								esc_html( $schooling[ ApplicantMeta::MAJOR ] )
							);
						} else {
							printf(
								'<li>Graduated in %s with a %s from %s with a major in %s</li>',
								esc_html( $schooling[ ApplicantMeta::YEAR ] ),
								esc_html( $schooling[ ApplicantMeta::DEGREE ] ),
								esc_html( $schooling[ ApplicantMeta::INSTITUTION ] ),
								esc_html( $schooling[ ApplicantMeta::MAJOR ] )
							);
						}
					}
				}
				?>
			</ol>
		<?php endif; ?>

		<?php if ( ! empty( $applicant->get_certifications() ) ) : ?>
			<h5><?php esc_html_e( 'Certifications', 'yikes-level-playing-field' ); ?></h5>
			<ol>
				<?php
				foreach ( $applicant->get_certifications() as $certification ) {
					if ( $applicant->is_anonymized() ) {
						printf(
							'<li>Certified in %s from %s. Status: %s</li>',
							esc_html( $certification[ ApplicantMeta::CERT_TYPE ] ),
							esc_html( $certification[ ApplicantMeta::TYPE ] ),
							esc_html( $certification[ ApplicantMeta::STATUS ] )
						);
					} else {
						printf(
							'<li>Certified in %s from %s. Status: %s. Year: %s.</li>',
							esc_html( $certification[ ApplicantMeta::CERT_TYPE ] ),
							esc_html( $certification[ ApplicantMeta::INSTITUTION ] ),
							esc_html( $certification[ ApplicantMeta::STATUS ] ),
							esc_html( $certification[ ApplicantMeta::YEAR ] )
						);
					}
				}
				?>
			</ol>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( ! empty( $applicant->get_skills() ) ) : ?>
	<section id="skills">
		<h4 class="lpf_mbox_subtitle"><?php esc_html_e( 'Skills', 'yikes-level-playing-field' ); ?></h4>
		<div class="applicant-skills-container">
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Skill', 'yikes-level-playing-field' ); ?></th>
						<th><?php esc_html_e( 'Proficiency', 'yikes-level-playing-field' ); ?></th>
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
		<h4 class="lpf_mbox_subtitle"><?php esc_html_e( 'Languages', 'yikes-level-playing-field' ); ?></h4>
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
						echo $is_multilingual ? esc_html__( 'Multilingual', 'yikes-level-playing-field' ) . ' &ndash; ' : '';
						foreach ( $proficiency_counts as $proficiency => $count ) {
							echo esc_html( $proficiency_labels[ $proficiency ] ), ' ';
							echo esc_html( sprintf(
								/* translators: %d is the number of languages for the given fluency level */
								_n( 'in %d language', 'in %d languages', $count, 'yikes-level-playing-field' ),
								$count
							) );
							echo $needs_comma ? ', ' : ' ';
							$needs_comma--;
						}
						?>
					</p>
			<?php else : ?>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Language', 'yikes-level-playing-field' ); ?></th>
							<th><?php esc_html_e( 'Proficiency', 'yikes-level-playing-field' ); ?></th>
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
		<h4 class="lpf_mbox_subtitle"><?php esc_html_e( 'Experience', 'yikes-level-playing-field' ); ?></h4>
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
							echo ! empty( $experience[ ApplicantMeta::POSITION ] ) ? esc_html__( ' in ', 'yikes-level-playing-field' ) : '';
							echo esc_html( $experience[ ApplicantMeta::INDUSTRY ] );
							echo ! empty( $experience[ ApplicantMeta::YEAR_DURATION ] ) ? esc_html__( ' for ', 'yikes-level-playing-field' ) : '';
							echo ! empty( $experience[ ApplicantMeta::YEAR_DURATION ] ) ? esc_html( $experience[ ApplicantMeta::YEAR_DURATION ] ) : '';
							?>
						</li>
						<?php
					} else {
						?>
						<li>
							<?php
							echo esc_html( $experience[ ApplicantMeta::POSITION ] );
							echo ! empty( $experience[ ApplicantMeta::POSITION ] ) ? esc_html__( ' in ', 'yikes-level-playing-field' ) : '';
							echo esc_html( $experience[ ApplicantMeta::INDUSTRY ] );
							echo ! empty( $experience[ ApplicantMeta::ORGANIZATION ] ) ? esc_html__( ' at ', 'yikes-level-playing-field' ) : '';
							echo esc_html( $experience[ ApplicantMeta::ORGANIZATION ] );
							echo ! empty( $experience[ ApplicantMeta::START_DATE ] ) ? esc_html__( ' from ', 'yikes-level-playing-field' ) : '';
							echo esc_html( date( 'm/d/Y', strtotime( $experience[ ApplicantMeta::START_DATE ] ) ) );
							echo ! empty( $experience[ ApplicantMeta::PRESENT_POSITION ] ) || ! empty( $experience[ ApplicantMeta::END_DATE ] ) ? esc_html__( ' to ', 'yikes-level-playing-field' ) : '';
							echo esc_html( ! empty( $experience[ ApplicantMeta::PRESENT_POSITION ] ) ? __( 'the present time.', 'yikes-level-playing-field' ) : date( 'm/d/Y', strtotime( $experience[ ApplicantMeta::END_DATE ] ) ) );
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
		<h4 class="lpf_mbox_subtitle"><?php esc_html_e( 'Volunteer Work', 'yikes-level-playing-field' ); ?></h4>
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
							echo ! empty( $experience[ ApplicantMeta::POSITION ] ) ? esc_html__( ' in ', 'yikes-level-playing-field' ) : '';
							echo esc_html( $experience[ ApplicantMeta::INDUSTRY ] );
							echo ! empty( $experience[ ApplicantMeta::YEAR_DURATION ] ) ? esc_html__( ' for ', 'yikes-level-playing-field' ) : '';
							echo ! empty( $experience[ ApplicantMeta::YEAR_DURATION ] ) ? esc_html( $experience[ ApplicantMeta::YEAR_DURATION ] ) : '';
							?>
						</li>
						<?php
					} else {
						?>
						<li>
							<?php
							echo esc_html( $experience[ ApplicantMeta::POSITION ] );
							echo ! empty( $experience[ ApplicantMeta::POSITION ] ) ? esc_html__( ' in ', 'yikes-level-playing-field' ) : '';
							echo esc_html( $experience[ ApplicantMeta::INDUSTRY ] );
							echo ! empty( $experience[ ApplicantMeta::ORGANIZATION ] ) ? esc_html__( ' at ', 'yikes-level-playing-field' ) : '';
							echo esc_html( $experience[ ApplicantMeta::ORGANIZATION ] );
							echo ! empty( $experience[ ApplicantMeta::START_DATE ] ) ? esc_html__( ' from ', 'yikes-level-playing-field' ) : '';
							echo esc_html( date( 'm/d/Y', strtotime( $experience[ ApplicantMeta::START_DATE ] ) ) );
							echo ! empty( $experience[ ApplicantMeta::PRESENT_POSITION ] ) || ! empty( $experience[ ApplicantMeta::END_DATE ] ) ? esc_html__( ' to ', 'yikes-level-playing-field' ) : '';
							echo esc_html( ! empty( $experience[ ApplicantMeta::PRESENT_POSITION ] ) ? __( 'the present time.', 'yikes-level-playing-field' ) : date( 'm/d/Y', strtotime( $experience[ ApplicantMeta::END_DATE ] ) ) );
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
