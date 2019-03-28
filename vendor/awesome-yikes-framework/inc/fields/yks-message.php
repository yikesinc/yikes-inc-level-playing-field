<?php
/**
 * Message Field.
 *
 * @package YIKES Awesome Framework
 */

$field_desc = isset( $field['desc'] ) ? $field['desc'] : '';

echo '<p class="yks_mbox_description">' . esc_html( $field_desc ) . '</p>';
return;
