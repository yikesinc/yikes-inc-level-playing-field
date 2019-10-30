<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Ebonie Butler
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

?>
<ul class="yikes_lpf_applicant_widget-notifications">
	<li class="yikes_lpf_applicant_widget-notifications-icon">
		<span class="dashicons dashicons-email"></span>
	</li>
	<li class="yikes_lpf_applicant_widget-notifications-message">
		<a href="<?php echo esc_url( $this->applicants_url ); ?>"><?php echo esc_html( $this->msg_count ); ?> New Messages</a>
	</li>
</ul>
<table>
	<thead>
		<tr>
			<th><?php esc_html_e( 'Job Title', 'level-playing-field' ); ?></th>
			<th><?php esc_html_e( 'New', 'level-playing-field' ); ?></th>
			<th><?php esc_html_e( 'Total', 'level-playing-field' ); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ( $this->records as $record ) {
		?>
		<tr>
			<td>
				<a href="<?php echo esc_url( $record['job_link'] ); ?>"><?php echo esc_html( $record['job_name'] ); ?></a>
			</td>
			<td>
				<a href="<?php echo esc_url( $record['new_link'] ); ?>"><?php echo esc_html( $record['new_applicants'] ); ?></a>
			</td>
			<td>
				<a href="<?php echo esc_url( $record['total_link'] ); ?>"><?php echo esc_html( $record['total_applicants'] ); ?></a>
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
<a href="<?php echo esc_url( $this->jobs_url ); ?>" class="button"><?php esc_html_e( 'View All Job Listings', 'level-playing-field' ); ?></a>
<a href="<?php echo esc_url( $this->applicants_url ); ?>" class="button"><?php esc_html_e( 'View All Applicants', 'level-playing-field' ); ?></a>
