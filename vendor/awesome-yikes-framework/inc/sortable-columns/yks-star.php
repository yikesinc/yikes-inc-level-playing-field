<?php
/**** Star Columns Single Checkbox ****/
/**
*
* How data will be displayed in columns.  Default text.  
*
*
*/
if ( empty ( $this->_column['dashicon'] ) ) {
	$this->_column['dashicon'] = 'dashicons-star'; 
}
if ( ! empty( $value ) ) {
	$column_html = '<div id="yks-' . $this->_column['id'] . '-'.$post->ID.'" class="dashicons ' . $this->_column['dashicon'] . '-filled yks-' . $this->_column['id'] . ' yks-' . $this->_column['id'] . '-on"></div>';
} else {
	$column_html = '<div id="yks-' . $this->_column['id'] . '-'.$post->ID.'" class="dashicons ' . $this->_column['dashicon'] . '-empty yks-' . $this->_column['id'] . ' yks-' . $this->_column['id'] . '-off"></div>';
}
echo $column_html;