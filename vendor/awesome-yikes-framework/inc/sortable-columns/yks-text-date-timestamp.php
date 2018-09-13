<?php
/**** Date Unix timestamp Columns ****/
/**
*
* How data will be displayed in columns.  Default text.  
*
*
*/

if (isset($value) && !empty($value) ) {
	$date = date ( 'F j, Y', $value);
	echo $date;
}