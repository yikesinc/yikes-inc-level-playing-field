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
if ($action == 'enable') {
	update_post_meta($id, $column_id, $this->_column['value'] );
	echo $action;
} else if ( $action == 'disable' ) {
	delete_post_meta($id, $column_id );
	echo $action;
}