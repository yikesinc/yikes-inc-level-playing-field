<?php
/**
 * Message Field.
 *
 * @package YIKES Awesome Framework
 */

$field_desc = isset( $field['desc'] ) ? $field['desc'] : '';

echo '<p class="yks_mbox_description">' . $field_desc . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput
return;
