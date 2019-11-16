<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\CustomPostType;

use Yikes\LevelPlayingField\Exception\MustExtend;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Taxonomy\JobStatus;

/**
 * Class LimitedJobManager
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class LimitedJobManager extends JobManager {

	/**
	 * Number of active jobs allowed.
	 *
	 * @since 1.0.0
	 * @var int
	 */
	private $limit = 4;

	/**
	 * Register the WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		parent::register();
		$this->add_terms_action();
		add_action( JobStatus::SLUG . '_metabox_before', [ $this, 'metabox_limit_message' ] );
		add_action( 'yks_render_taxonomy-select', [ $this, 'metabox_limit_message' ] );
	}

	/**
	 * Hook to the set_object_terms action.
	 *
	 * @since 1.0.0
	 */
	private function add_terms_action() {
		add_action( 'set_object_terms', [ $this, 'modify_post_terms' ], 10, 4 );
	}

	/**
	 * Modify the terms for a given job.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $object_id Object ID.
	 * @param array  $terms     An array of object terms.
	 * @param array  $tt_ids    An array of term taxonomy IDs.
	 * @param string $taxonomy  Taxonomy slug.
	 *
	 * @throws MustExtend When get_slug() throws an exception.
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
	 * Determine if we're past the number of active jobs.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	private function active_past_limit() {
		return $this->count_active() > $this->limit;
	}

	/**
	 * Determine if we're at the limit available jobs.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	private function active_at_limit() {
		return $this->count_active() === $this->limit;
	}

	/**
	 * Return the count of active Jobs.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	private function count_active() {
		static $repo = null;
		if ( null === $repo ) {
			$repo = new JobRepository();
		}

		return $repo->count_active();
	}

	/**
	 * Unhook our set_object_terms action.
	 *
	 * @since 1.0.0
	 */
	private function remove_terms_action() {
		remove_action( 'set_object_terms', [ $this, 'modify_post_terms' ], 10 );
	}

	/**
	 * Display a notice when there are too many items active.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field Array of field data on the Yikes Framework hook.
	 */
	public function metabox_limit_message( array $field = [] ) {
		if ( doing_action( 'yks_render_taxonomy-select' ) && JobStatus::SLUG !== $field['taxonomy'] ) {
			return;
		} elseif ( ! $this->active_at_limit() ) {
			return;
		}

		$message = sprintf(
			/* translators: %d is the number of active jobs */
			_n( 'You are limited to %d active job.', 'You are limited to %d active jobs.', $this->limit, 'level-playing-field' ),
			number_format_i18n( $this->limit )
		);
		?>
		<div class="lpf-limit-jobs">
			<p><?php echo esc_html( $message ); ?></p>
		</div>
		<?php
	}
}
