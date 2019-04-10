<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Ebonie Butler
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

?>
<h2>
	<a href="<?php echo esc_url( $this->applicants_url ); ?>"><?php echo esc_html( $this->msg_count ); ?> new messages</a>
</h2>
<table>
	<thead>
		<tr>
			<th><?php esc_html_e( 'Job Title', 'yikes-level-playing-field' ); ?></th>
			<th><?php esc_html_e( 'New', 'yikes-level-playing-field' ); ?></th>
			<th><?php esc_html_e( 'Total', 'yikes-level-playing-field' ); ?></th>
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
<a href="<?php echo esc_url( $this->jobs_url ); ?>" class="button"><?php esc_html_e( 'View All Job Listings', 'yikes-level-playing-field' ); ?></a>
<a href="<?php echo esc_url( $this->applicants_url ); ?>" class="button"><?php esc_html_e( 'View All Applicants', 'yikes-level-playing-field' ); ?></a>
