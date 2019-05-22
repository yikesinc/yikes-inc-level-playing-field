<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Model\Applicant;

/** @var Applicant $applicant */
$applicant = $this->applicant; ?>

<!-- Basic info and cover letter sidebar -->
<div id="basic-info" class="postbox">
	<div class="inside">
		<?php if ( ! $applicant->is_anonymized() && ! empty( $applicant->get_email() ) ) : ?>
			<p class="email">
				<span class="label"><?php esc_html_e( 'Email:', 'yikes-level-playing-field' ); ?></span>
				<a href="mailto:<?php echo esc_attr( $applicant->get_email() ); ?>"><?php echo esc_html( $applicant->get_email() ); ?></a>
			</p>
		<?php endif; ?>

		<?php if ( ! $applicant->is_anonymized() && ! empty( $applicant->get_phone() ) ) : ?>
			<p class="email">
				<span class="label"><?php esc_html_e( 'Phone:', 'yikes-level-playing-field' ); ?></span>
				<a href="tel:<?php echo esc_attr( $applicant->get_phone() ); ?>"><?php echo esc_html( $applicant->get_phone() ); ?></a>
			</p>
		<?php endif; ?>

		<p class="location">
			<span class="label"><?php esc_html_e( 'Address:', 'yikes-level-playing-field' ); ?></span>
			<?php
			$address = array_filter( $applicant->get_address() );
			if ( ! empty( $address ) ) {
				foreach ( $address as $field ) {
					echo '<span class="address-field">', esc_html( $field ), '<span class="address-field-comma">,</span> </span>';
				}
			}
			?>
		</p>
		<?php
		if ( ! empty( $applicant->get_cover_letter() ) ) :
			?>
			<p class="cover-letter">
				<span class="label"><?php esc_html_e( 'Cover Letter:', 'yikes-level-playing-field' ); ?></span>
				<a href="#"><?php esc_html_e( 'View Cover Letter', 'yikes-level-playing-field' ); ?></a>
			</p>
			<div class="cover-letter-content">
				<?php echo wp_kses_post( apply_filters( 'lpf_the_content', $applicant->get_cover_letter() ) ); ?>
			</div>
		<?php endif; ?>
	</div><!-- /inside -->
</div><!-- /postbox -->
