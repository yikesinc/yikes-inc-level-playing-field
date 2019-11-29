<?php
/**
 * Title Field.
 *
 * @package YIKES Awesome Framework
 */

$field_name = isset( $field['name'] ) ? $field['name'] : '';
$field_desc = isset( $field['desc'] ) ? $field['desc'] : '';

echo '<h5 class="yks_mbox_title">' . esc_html( $field_name ) . '</h5>';
echo '<p class="yks_mbox_description">' . esc_html( $field_desc ) . '</p>';
return;
