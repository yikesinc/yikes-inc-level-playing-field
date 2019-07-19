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
$applicant        = $this->applicant;
$interview_status = $applicant->get_interview_status();
?>

<div id="test"></div>

<!-- Interview details sidebar -->
<div id="interview" class="postbox"></div>
