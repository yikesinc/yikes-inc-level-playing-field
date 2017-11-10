<?php
/**** Hidden field ****/

/**
*
* Simple hidden field - we can use this to store custom values for use in e.g. JavaScript without the client seeing anything
*
*/
$field_id = isset( $field['id'] ) ? $field['id'] : '';
echo '<input type="hidden" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ) . '" value="" />';
