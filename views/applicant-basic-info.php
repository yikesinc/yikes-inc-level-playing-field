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

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/** @var Applicant $applicant */
$applicant = $this->applicant;

/** @var bool $is_sidebar_empty */
$is_sidebar_empty = true;
?>

<!-- Basic info and cover letter sidebar -->
<div id="basic-info" class="postbox">
	<div class="inside">
		<?php
		if ( ! empty( $applicant->get_email() ) ) :
			$is_sidebar_empty = false;
			?>
			<p class="email">
				<span class="label"><?php esc_html_e( 'Email:', 'level-playing-field' ); ?></span>
				<a href="mailto:<?php echo esc_attr( $applicant->get_email() ); ?>"><?php echo esc_html( $applicant->get_email() ); ?></a>
			</p>
		<?php endif; ?>

		<?php
		if ( ! empty( $applicant->get_phone() ) ) :
			$is_sidebar_empty = false;
			?>
			<p class="email">
				<span class="label"><?php esc_html_e( 'Phone:', 'level-playing-field' ); ?></span>
				<a href="tel:<?php echo esc_attr( $applicant->get_phone() ); ?>"><?php echo esc_html( $applicant->get_phone() ); ?></a>
			</p>
		<?php endif; ?>

		<p class="location">
			<?php
			$address = array_filter( $applicant->get_address() );
			if ( ! empty( $address ) ) {
				$is_sidebar_empty = false;
				?>
				<span class="label"><?php esc_html_e( 'Address:', 'level-playing-field' ); ?></span>
				<?php
				foreach ( $address as $field ) {
					echo '<span class="address-field">', esc_html( $field ), '<span class="address-field-comma">,</span> </span>';
				}
			}
			?>
		</p>
		<?php
		if ( ! empty( $applicant->get_cover_letter() ) ) :
			$is_sidebar_empty = false;
			?>
			<p class="cover-letter">
				<span class="label"><?php esc_html_e( 'Cover Letter:', 'level-playing-field' ); ?></span>
				<a href="#"><?php esc_html_e( 'View Cover Letter', 'level-playing-field' ); ?></a>
			</p>
			<div class="cover-letter-content">
				<?php echo wp_kses_post( apply_filters( 'lpf_the_content', $applicant->get_cover_letter() ) ); ?>
			</div>
		<?php endif; ?>
		<?php if ( $is_sidebar_empty ) : ?>
			<p class="no-info"><?php esc_html_e( 'No information to display.', 'level-playing-field' ); ?></p>
		<?php endif; ?>
	</div><!-- /inside -->
</div><!-- /postbox -->
