<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Model\MetaLinks;

/**
 * Trait JobDropdown
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait JobDropdown {

	/**
	 * Create a dropdown of jobs.
	 *
	 * @since %VERSION%
	 *
	 * @param array  $jobs     An array of Job objects. If no job objects are passed in, the function will fetch them all.
	 * @param string $selected The item that should be pre-selected in the dropdown.
	 *
	 * @return string An HTML dropdown of jobs.
	 */
	private function job_dropdown( $jobs = [], $selected = '' ) {
		if ( empty( $jobs ) || ! is_array( $jobs ) ) {
			$jobs = ( new JobRepository() )->find_all();
		}

		ob_start();
		?>
		<select name="<?php echo esc_attr( MetaLinks::JOB ); ?>" id="<?php echo esc_attr( MetaLinks::JOB ); ?>">
			<option value="all" <?php selected( 'all', $selected ); ?>><?php esc_html_e( 'All Jobs', 'yikes-level-playing-field' ); ?></option>
			<?php foreach ( $jobs as $job_id => $job ) : ?>
				<option value="<?php echo esc_attr( $job_id ); ?>"><?php echo esc_html( $job->get_title() ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		return ob_get_clean();
	}
}
