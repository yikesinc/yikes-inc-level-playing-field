<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * This is the template for the [lpf_all_jobs] shortcode.
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
<ul class="lpf-jobs-list">
	<?php echo $this->render_partial( $this->partials['jobs_loop'] ); ?>
</ul>
<?php
