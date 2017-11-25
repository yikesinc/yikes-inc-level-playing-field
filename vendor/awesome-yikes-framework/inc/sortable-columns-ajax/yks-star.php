<?php
/**** Star Columns Single ajax ****/
/**
*
*
*
*/
$id = $_POST['star-id'];
$column_id = $_POST['star-column-id'];
$action = $_POST['star-action'];
$value = $_POST['value'];
$posttype = $_POST['post_type'];
if ($action == 'enable') {
	update_post_meta($id, $column_id, $value );
	if ( isset(  $_POST['limit'] ) ) {
		$this->limit_post_with_meta( $id, $posttype, $column_id, $value );
	}
	echo $action;
} else if ( $action == 'disable' ) {
	delete_post_meta($id, $column_id );
	echo $action;
}