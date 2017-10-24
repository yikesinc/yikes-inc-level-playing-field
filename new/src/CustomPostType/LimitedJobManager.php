<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\CustomPostType;

use Yikes\LevelPlayingField\Model\JobManagerRepository;
use Yikes\LevelPlayingField\Taxonomy\JobStatus;

/**
 * Class LimitedJobManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class LimitedJobManager extends JobManager {

	/**
	 * Number of active jobs allowed.
	 *
	 * @since %VERSION%
	 * @var int
	 */
	private $limit = 4;

	/**
	 * Register the WordPress hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		parent::register();
		$this->add_terms_action();
		add_action( JobStatus::SLUG . '_metabox_before', array( $this, 'metabox_limit_message' ) );
	}

	/**
	 * Modify the terms for a given job.
	 *
	 * @since %VERSION%
	 *
	 * @param int    $object_id Object ID.
	 * @param array  $terms     An array of object terms.
	 * @param array  $tt_ids    An array of term taxonomy IDs.
	 * @param string $taxonomy  Taxonomy slug.
	 */
	public function modify_post_terms( $object_id, $terms, $tt_ids, $taxonomy ) {
		if ( JobStatus::SLUG !== $taxonomy ) {
			return;
		}

		if ( $this->get_slug() !== get_post_type( $object_id ) ) {
			return;
		}

		if ( empty( $terms ) ) {
			return;
		}

		// There shouldn't be more than one term set, so we only look at the first one.
		$term = get_term( $terms[0] );

		// If the status is inactive, don't check anything.
		if ( 'inactive' === $term->slug ) {
			return;
		}

		// See if we need to modify the terms for this post.
		if ( $this->active_past_limit() ) {
			// Prevent action recursion.
			$this->remove_terms_action();

			// Set the item to inactive.
			$inactive = get_term_by( 'slug', 'inactive', $taxonomy );
			wp_set_object_terms( $object_id, $inactive->term_id, $taxonomy );

			// Add our action again.
			$this->add_terms_action();
		}
	}

	/**
	 * Display a notice when there are too many items active.
	 *
	 * @since %VERSION%
	 */
	public function metabox_limit_message() {
		if ( ! $this->active_past_limit() ) {
			return;
		}
		$message = sprintf(
			_n( 'Jobs are limited to %d active job.', 'Jobs are limited to %s active jobs.', $this->limit, 'yikes-level-playing-field' ),
			number_format_i18n( $this->limit )
		);
		?>
		<div class="lpf-limit-jobs">
			<p><?php echo esc_html( $message ); ?></p>
		</div>
		<?php
	}

	/**
	 * Determine if we're past the number of active jobs.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	private function active_past_limit() {
		static $repo = null;
		if ( null === $repo ) {
			$repo = new JobManagerRepository();
		}

		return $repo->count_active() > $this->limit;
	}

	/**
	 * Hook to the set_object_terms action.
	 *
	 * @since %VERSION%
	 */
	private function add_terms_action() {
		add_action( 'set_object_terms', array( $this, 'modify_post_terms' ), 10, 4 );
	}

	/**
	 * Unhook our set_object_terms action.
	 *
	 * @since %VERSION%
	 */
	private function remove_terms_action() {
		remove_action( 'set_object_terms', array( $this, 'modify_post_terms' ), 10 );
	}
}
