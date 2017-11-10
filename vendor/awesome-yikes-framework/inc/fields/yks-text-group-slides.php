<?php
wp_enqueue_media();
global $post;
// Description/Value Text Field
// reset count
$yksc = '';

?>
<style>
.sliderlabels {
margin-top: 5px;
display: block;
font-size: 14px;
text-shadow: none;
color: #23282d;
line-height: 1.3;
text-align: left;
font-weight: bold;
}
</style>
<script>

function yks_group_slide_shuffle () {
			jQuery('#<?php echo 'container_' . $field['id']; ?> a').each(function(count) {
				var count = count + 1;
				jQuery(this).attr('id', "<?php echo $field['id']; ?>_delete_" + count + "");
				if (count == 1 )
					{
					jQuery(this).css("display" , "none");
					}
				else
					{
					jQuery(this).css("display" , "inline-block");
					}
			}); 
			//change label
			 jQuery(".yks-slide-labels-<?php echo $field['id']; ?>").each(function(count) {
				var count = count + 1;
				jQuery(this).html("<h3>Slide " + count + "</h3>");
			}); 
			//change other label
			 jQuery(".yks-slide-labels-inside-<?php echo $field['id']; ?>").each(function(count) {
				var count = count + 1;
				jQuery(this).html("<h3>Slide " + count + "</h3>");
			}); 
			//change ID and Names on new sort order
			jQuery(".yks-hidden-sort-<?php echo $field['id']; ?>").each(function(count) {
				var count = count + 1;
				jQuery(this).val(count);
			});  
}
jQuery(document).ready(function ($) {
	//Sort fields
	$( "#<?php echo 'container_' . $field['id']; ?>" ).sortable({
			cancel: "input,textarea,button,select,option,a",
			stop: function () {
			//change delete button on new sort order
			yks_group_slide_shuffle();  
			},
	});
	//add delete function
	$('.<?php echo $field['id']; ?>_delete').live('click',function() {
		$( "#li-container-"+ $(this).data('fid') + "-"+ $(this).data('num') + " input[type=text]").val("");
		$( "#li-container-"+ $(this).data('fid') + "-"+ $(this).data('num') + " input[type=hidden]").val("");
		$( "#li-container-"+ $(this).data('fid') + "-"+ $(this).data('num') + " .img_status").remove();
		   $( "#li-container-"+ $(this).data('fid') + "-"+ $(this).data('num') + " textarea").val("");  
		   $( "#li-container-"+ $(this).data('fid') + "-"+ $(this).data('num') + " textarea").html("");
		if ( $( '.hide-events-box-<?php echo $field['id']; ?>' ).length ) 
			{
			 $( "#li-container-"+ $(this).data('fid') + "-"+ $(this).data('num') + "").detach().insertBefore('.hide-events-box-<?php echo $field['id']; ?>:first');
			} 
		else
			{
			$('.<?php echo $field['id']; ?>_add').show(); 
			$( "#li-container-"+ $(this).data('fid') + "-"+ $(this).data('num') + "").detach().insertAfter('.all-events-box-<?php echo $field['id']; ?>:last');
			}
		   $( "#li-container-"+ $(this).data('fid') + "-"+ $(this).data('num') + "").hide();
		   $( "#li-container-"+ $(this).data('fid') + "-"+ $(this).data('num') + "").addClass('hide-events-box-<?php echo $field['id']; ?>');
		$( "#li-container-"+ $(this).data('fid') + "-"+ $(this).data('num') + "").children(".yks-sortable-slide-content").hide(); 
		$( "#li-container-"+ $(this).data('fid') + "-"+ $(this).data('num') + "").children(".yks-sortable-slide-show").show();
		yks_group_slide_shuffle();    
		//if first slide is deleted.. 
		if ($( '.hide-events-box-<?php echo $field['id']; ?>' ).length == $( '.all-events-box-<?php echo $field['id']; ?>' ).length)
			{
			$('.hide-events-box-<?php echo $field['id']; ?>').first().slideDown().removeClass('hide-events-box-<?php echo $field['id']; ?>');
			}
	});
	//add button function
	$('.<?php echo $field['id']; ?>_add').live('click',function() {
		if ( $( '.hide-events-box-<?php echo $field['id']; ?>' ).length ) 
			{
			$('.hide-events-box-<?php echo $field['id']; ?>').first().slideDown().removeClass('hide-events-box-<?php echo $field['id']; ?>');
			yks_group_slide_shuffle();
			if ( $( '.hide-events-box-<?php echo $field['id']; ?>' ).length ) 
				{
				 //do something               
				}
			else
				{
				$('.<?php echo $field['id']; ?>_add').hide();
				}
			}
	}); 
	//open collapps
	$('.yks-sortable-slide-show').live('click', function () {
		$(".yks-sortable-slide-content").slideUp();
		$(".yks-sortable-slide-show").show();
		var theparent = $(this).parent();
		$(theparent).children(".yks-sortable-slide-content").slideDown();
		$(this).hide();
	});
	// close collapse
	$('.yks-sortable-slide-hide').live('click', function () {
		var theparent = $(this).parent();
		var theparentparent = $(theparent).parent();
		$(theparent).slideUp();
		$(theparentparent).children(".yks-sortable-slide-show").slideDown();
	});    
	
	//hide them
	$( ".hide-events-box-<?php echo $field['id']; ?>").hide();
	});
</script>
<?php
	// end javascript for sortable...
$metaarray = '';
if ( $post->ID ) {
	$meta = yks_loop_text_group_slides( 'cpt', $field['id'],  $field['limit'], $post->ID );
} else {
	$meta = yks_loop_text_group_slides( 'options', $field['id'],  $field['limit'] );
}
$metaarray = ! is_array( $meta ) ? array( $meta ) : $meta;
$thefield = '';
echo '<ul id="container_' . $field['id'] . '">';
foreach ( $metaarray as $metaval ) {
	++$yksc;
	if ( $yksc == 1 || $yksc == $field['limit'] ) {
		if ( $yksc == 1 ) {
			$thestyle = 'style="display:none;"';
		}
		if ( $yksc == $field['limit'] ) {
			$thestyle2 = 'style="display:none;"';
		}
	} else {
		$thestyle = '';
	}
	echo '<li id="li-container-' . $field['id'] . '-' . $yksc . '" class="ui-state-default all-events-box-' . $field['id'] . '">';
	echo '<div class="yks-sortable-slide-show" style="cursor: pointer; width: 96%;"><div data-code="f156" class="dashicons dashicons-arrow-up"></div><label class="sliderlabels yks-slide-labels-' . $field['id'] . '"><h3>Slide ' . $yksc . '</h3></label></div>';
	echo '<div class="yks-sortable-slide-content" style="display: none;"> ';
	echo '<div class="yks-sortable-slide-hide" style="cursor: pointer; width: 96%;"><div data-code="f142" class="dashicons dashicons-arrow-down"></div><label class="sliderlabels yks-slide-labels-inside-' . $field['id'] . '"><h3>Slide ' . $yksc . '</h3></label></div>';
	echo '<input type="hidden" name="' . $field['id'] . '_sort_' . $yksc . '" id="' . $field['id'] . '_sort_' . $yksc . '" value="' . $yksc . '" class="yks-hidden-sort-' . $field['id'] . '"/>';
	echo '<label class="sliderlabels">Slide Image</label>';
	echo '<input class="yks_img_up yks_txt_small_4" type="text" size="45" id="' . $field['id'] . '_four_' . $yksc . '" name="' . $field['id'] . '_four_' . $yksc . '" value="' . $metaval['four'] . '" readonly="readonly" />';
	echo '<input class="yks_img_up_button button" type="button" value="Upload File" />';
	echo '<input class="yks_img_up_id" type="hidden" id="' . $field['id'] . '_four_' . $yksc . '_id" name="' . $field['id'] . '_four_' . $yksc . '_id" value="' . $metaval['four_id'] . '" />';
	echo  '<div id="' . $field['id'] . '_four_' . $yksc . '_status" class="yks_upstat">';
	if ( $metaval['four'] != '' ) {
		$check_image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $metaval['four'] );
		if ( $check_image ) {
			echo '<div class="img_status">';
			echo '<img src="' . $metaval['four'] . '" alt="" />';
			echo '<a href="#" class="yks_hide_ubutton" rel="' . $field['id'] . '_four_' . $yksc . '" data-switch="single">Remove Image</a>';
			echo '</div>';
		} else {
			$parts = explode( '/', $metaval['four'] );
			for ( $i = 0; $i < count( $parts ); ++$i ) {
				$title = $parts[ $i ];
			}
			echo '<div class="img_status">';
			echo 'File: <strong>' . $title . '</strong>&nbsp;&nbsp;&nbsp; (<a href="' . $metaval['four'] . '" target="_blank" rel="external">Download</a> / <a href="#" class="yks_hide_ubutton" rel="' . $field['id'] . '_four_' . $yksc . '" data-switch="single">Remove</a>)';
			echo '</div>';
		}
	}
	echo '</div>';
	echo '<p class="yks_mbox_description">' . $field['slideimage_desc'] . '</p><br />';
	echo '<label class="sliderlabels">Slide Image Description (used for alt tag)</label>';
	echo '<input type="text" name="' . $field['id'] . '_five_' . $yksc . '" id="' . $field['id'] . '_five_' . $yksc . '" value="' . $metaval['five'] . '" class="yks_txt_small_1" />';
	echo '<p class="yks_mbox_description">' . $field['slidedesc_desc'] . '</p><br />';
	echo '<label class="sliderlabels">Slide caption content</label>';
	wp_editor( $metaval['six'], $field['id'] . '_six_' . $yksc . '', isset( $field['options'] ) ? $field['options'] : array() );
	echo '<p class="yks_mbox_description">' . $field['slidecap_desc'] . '</p><br />';
	echo '<label class="sliderlabels">Slide Button URL</label>';
	echo '<input type="text" name="' . $field['id'] . '_one_' . $yksc . '" id="' . $field['id'] . '_one_' . $yksc . '" value="' . $metaval['one'] . '" class="yks_txt_small_1" />';
	echo '<p class="yks_mbox_description">' . $field['slideurl_desc'] . '</p><br />';
	echo '<label class="sliderlabels">Button Text</label>';
	echo '<input type="text" name="' . $field['id'] . '_two_' . $yksc . '" id="' . $field['id'] . '_two_' . $yksc . '" value="' . $metaval['two'] . '" class="yks_txt_medium yks_txt_small_2" />';
	echo '<p class="yks_mbox_description">' . $field['slidebutton_desc'] . '</p><br />';
	echo '<label class="sliderlabels">Slide Button Screen Reader Text</label>';
	echo '<input type="text" name="' . $field['id'] . '_three_' . $yksc . '" id="' . $field['id'] . '_three_' . $yksc . '" value="' . $metaval['three'] . '" class="yks_txt_small_3" />';
	echo '<p class="yks_mbox_description">' . $field['slidescreen_desc'] . '</p><br />';
	echo '<a  id="' . $field['id'] . '_delete_' . $yksc . '" data-fid="' . $field['id'] . '" data-num="' . $yksc . '" class="button button-primary ' . $field['id'] . '_delete">Delete</a>';
	echo '</div>';
	echo '</li>';
}// End foreach().
	$thestyle = '';
	$thestyle2 = '';
if ( $yksc < $field['limit'] ) {
	++$yksc;
	foreach ( range( $yksc, $field['limit'] ) as $number ) {
		if ( $number == $field['limit'] ) {
			$thestyle2 = 'style="display:none;"';
		} else {
			$thestyle2 = '';
		}
		echo '<li id="li-container-' . $field['id'] . '-' . $number . '" class="ui-state-default all-events-box-' . $field['id'] . ' hide-events-box-' . $field['id'] . '">';
		echo '<div class="yks-sortable-slide-show" style="cursor: pointer; width: 96%;"><div data-code="f156" class="dashicons dashicons-arrow-up"></div><label class="sliderlabels yks-slide-labels-' . $field['id'] . '"><h3>Slide ' . $number . '</h3></label></div>';
		echo '<div class="yks-sortable-slide-content" style="display: none;"> ';
		echo '<div class="yks-sortable-slide-hide" style="cursor: pointer; width: 96%;"><div data-code="f142" class="dashicons dashicons-arrow-down"></div><label class="sliderlabels yks-slide-labels-inside-' . $field['id'] . '"><h3>Slide ' . $number . '</h3></label></div>';
		echo '<input type="hidden" name="' . $field['id'] . '_' . $number . '" id="' . $field['id'] . '_' . $number . '" value="' . $number . '" class="yks-hidden-sort-' . $field['id'] . '"/>';
		echo '<label class="sliderlabels">Slide Image</label>';
		echo '<input class="yks_img_up yks_txt_small_4" type="text" size="45" id="' . $field['id'] . '_four_' . $number . '" name="' . $field['id'] . '_four_' . $number . '" value="" readonly="readonly" />';
		echo '<input class="yks_img_up_button button" type="button" value="Upload File" />';
		echo '<input class="yks_img_up_id" type="hidden" id="' . $field['id'] . '_four_' . $number . '_id" name="' . $field['id'] . '_four_' . $number . '_id" value="" />';
		echo '<div id="' . $field['id'] . '_four_' . $number . '_status" class="yks_upstat">';
		echo '</div>';
		echo '<p class="yks_mbox_description">' . $field['slideimage_desc'] . '</p><br />';
		echo '<label class="sliderlabels">Slide Image Description</label>';
		echo '<input type="text" name="' . $field['id'] . '_five_' . $number . '" id="' . $field['id'] . '_five_' . $number . '" value="" class="" />';
		echo '<p class="yks_mbox_description">' . $field['slidedesc_desc'] . '</p><br />';
		echo '<label class="sliderlabels">Slide caption content</label>';
		wp_editor( '', $field['id'] . '_six_' . $number . '', isset( $field['options'] ) ? $field['options'] : array() );
		echo '<p class="yks_mbox_description">' . $field['slidecap_desc'] . '</p><br />';
		echo '<label class="sliderlabels">Slide Button URL</label>';
		echo '<input type="text" name="' . $field['id'] . '_one_' . $number . '" id="' . $field['id'] . '_one_' . $number . '" value="" class="" />';
		echo '<p class="yks_mbox_description">' . $field['slideurl_desc'] . '</p><br />';
		echo '<label class="sliderlabels">Button Text</label>';
		echo '<input type="text" name="' . $field['id'] . '_two_' . $number . '" id="' . $field['id'] . '_two_' . $number . '" value="" cclass="yks_txt_medium" />';
		echo '<p class="yks_mbox_description">' . $field['slidebutton_desc'] . '</p><br />';
		echo '<label class="sliderlabels">Slide Button Screen Reader Text</label>';
		echo '<input type="text" name="' . $field['id'] . '_three_' . $number . '" id="' . $field['id'] . '_three_' . $number . '" value="" class="" />';
		echo '<p class="yks_mbox_description">' . $field['slidescreen_desc'] . '</p><br />';
		echo '<a  id="' . $field['id'] . '_delete_' . $number . '" data-fid="' . $field['id'] . '" data-num="' . $number . '" class="button button-primary ' . $field['id'] . '_delete">Delete</a>';
		echo '</div>';
		echo '</li>';
	}// End foreach().
}// End if().
	echo '</ul>';
	echo '<p><a  id="' . $field['id'] . '_add" class="button button-primary ' . $field['id'] . '_add"  style="' . $thestyle2 . '">Add A Slide</a></p>';
	$thestyle = '';
	$thestyle2 = '';
	$yksc = '';
	echo '<p class="yks_mbox_description">' . $field['desc'] . '</p>';
?>
