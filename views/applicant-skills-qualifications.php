<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Model\ApplicantMeta;

$applicant = $this->applicant;
?>
<!-- Applicant Skills and Qualifications -->
<div class="postbox">
	<h2 class="hndle ui-sortable-handle">
		<span>
			<?php esc_html_e( 'Skills and Qualifications', 'yikes-level-playing-field' ); ?>
		</span>
	</h2>
	<div class="inside">
		<div class="yks_mbox">
			<?php
			if ( ! empty( $applicant->get_schooling() ) || ! empty( $applicant->get_certifications() ) ) :
				?>
			<section id="education">
				<h4 class="yks_mbox_title"><?php esc_html_e( 'Education', 'yikes-level-playing-field' ); ?></h4>
				<div class="applicant-skills-container">
					<?php
					if ( ! empty( $applicant->get_schooling() ) ) :
						?>
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
						<?php
					endif;
					if ( ! empty( $applicant->get_certifications() ) ) :
						?>
					<h5><?php esc_html_e( 'Certifications', 'yikes-level-playing-field' ); ?></h5>
					<ol>
						<?php
						foreach ( $applicant->get_certifications() as $certification ) {
							printf(
								'<li>Certified in %s from %s. Status: %s</li>',
								esc_html( $certification['certification_type'] ),
								esc_html( $certification['type'] ),
								esc_html( $certification['status'] )
							);
						}
						?>
					</ol>
						<?php
					endif;
					?>
				</div>
			</section>
				<?php
			endif;
			if ( ! empty( $applicant->get_skills() ) ) :
				?>
			<section id="skills">
				<h4 class="yks_mbox_title"><?php esc_html_e( 'Skills', 'yikes-level-playing-field' ); ?></h4>
				<div class="applicant-skills-container">
					<table class="wp-list-table widefat fixed striped users">
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
									esc_html( $skill['skill'] ),
									esc_html( $skill['proficiency'] )
								);
							}
							?>
						</tbody>
					</table>
				</div>
			</section>
				<?php
			endif;
			if ( ! empty( $applicant->get_languages() ) ) :
				?>
			<section id="languages">
				<?php
				$languages       = $applicant->get_languages();
				$is_multilingual = count( $languages ) > 1;

				// Build up language proficiency data.
				$proficiency_labels = [
					'fluent'       => __( 'Fluent', 'yikes-level-playing-field' ),
					'professional' => __( 'Working Professional proficiency', 'yikes-level-playing-field' ),
					'limited'      => __( 'Limited proficiency', 'yikes-level-playing-field' ),
					'elementary'   => __( 'Elementary proficiency', 'yikes-level-playing-field' ),
				];

				$proficiency_counts = [];
				foreach ( $languages as $language ) {
					if ( ! array_key_exists( $language['proficiency'], $proficiency_counts ) ) {
						$proficiency_counts[ $language['proficiency'] ] = 1;
						continue;
					}

					$proficiency_counts[ $language['proficiency'] ]++;
				}

				// Set up a counter for when we need to output a comma.
				$needs_comma = count( $proficiency_counts ) - 1;

				?>
				<h4 class="yks_mbox_title"><?php esc_html_e( 'Languages', 'yikes-level-playing-field' ); ?></h4>
				<div class="applicant-skills-container">
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
				</div>
			</section>
				<?php
			endif;
			if ( ! empty( $applicant->get_experience() ) ) :
				?>
			<section id="experience">
				<h4 class="yks_mbox_title"><?php esc_html_e( 'Experience', 'yikes-level-playing-field' ); ?></h4>
				<div class="applicant-skills-container">
					<ol>
						<?php
						foreach ( $applicant->get_experience() as $experience ) {
							printf(
								'<li>%s in %s for %s</li>',
								esc_html( $experience[ ApplicantMeta::POSITION ] ),
								esc_html( $experience[ ApplicantMeta::INDUSTRY ] ),
								esc_html( $experience[ ApplicantMeta::YEAR_DURATION ] )
							);
						}
						?>
					</ol>
				</div>
			</section>
				<?php
			endif;
			if ( ! empty( $applicant->get_volunteer() ) ) :
				?>
			<section id="volunteer-work">
				<h4 class="yks_mbox_title"><?php esc_html_e( 'Volunteer Work', 'yikes-level-playing-field' ); ?></h4>
				<div class="applicant-skills-container">
					<ol>
						<?php
						foreach ( $applicant->get_volunteer() as $experience ) {
							printf(
								'<li>%s in %s for x years</li>',
								esc_html( $experience['position'] ),
								esc_html( $experience['industry'] )
							);
						}
						?>
					</ol>
				</div>
			</section>
				<?php
			endif;
				?>
		</div><!-- /yks_mbox -->
	</div><!-- /inside -->
</div><!-- /postbox -->
