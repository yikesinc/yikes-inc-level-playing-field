<?php
/**** Star Columns Header file ****/
?>
<script>
jQuery( document ).ready( function ($) {
	//ajax urlx
	var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>'; 
	$('.yks-<?php echo $this->_column['id']; ?>').click ( function () {
		//variables
		var starid = $(this).attr('id').replace('yks-<?php echo $this->_column['id']; ?>-','');
		if ($(this).hasClass('yks-<?php echo $this->_column['id']; ?>-on'))
			{
			var data = {
				'action': 'yks_star_action',
				'star-column-id': '<?php echo $this->_column['id']; ?>',
				'security': '<?php echo $my_nonce; ?>',
				'star-id': starid,      
				'star-action' : 'disable'
				};
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			jQuery.post(ajaxurl, data, function(response) 
				{
				//function...
				var validator = response.replace(/\W/g, '');
				if (validator == "disable")
					{
					$("#yks-<?php echo $this->_column['id']; ?>-" + starid).removeClass('yks-<?php echo $this->_column['id']; ?>-on');
					$("#yks-<?php echo $this->_column['id']; ?>-" + starid).removeClass('dashicons-star-filled');
					$("#yks-<?php echo $this->_column['id']; ?>-" + starid).addClass('yks-<?php echo $this->_column['id']; ?>-off');
					$("#yks-<?php echo $this->_column['id']; ?>-" + starid).addClass('dashicons-star-empty');
					}
				else
					{
					alert ('error');
					}
				});		
			}
		else if ($(this).hasClass('yks-<?php echo $this->_column['id']; ?>-off'))
			{
			var data = {
				'action': 'yks_star_action',
				'star-column-id': '<?php echo $this->_column['id']; ?>',
				'security': '<?php echo $my_nonce; ?>',
				'star-id': starid,      
				'star-action' : 'enable'
				};
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			jQuery.post(ajaxurl, data, function(response) 
				{
				//function...
				var validator = response.replace(/\W/g, '');
				if (validator == "enable")
					{
					$("#yks-<?php echo $this->_column['id']; ?>-" + starid).removeClass('yks-<?php echo $this->_column['id']; ?>-off');
					$("#yks-<?php echo $this->_column['id']; ?>-" + starid).removeClass('dashicons-star-empty')
					$("#yks-<?php echo $this->_column['id']; ?>-" + starid).addClass('yks-<?php echo $this->_column['id']; ?>-on');
					$("#yks-<?php echo $this->_column['id']; ?>-" + starid).addClass('dashicons-star-filled');	
					}
				else
					{
					alert ('error');
					}
				});		
			}
	});	
	
});

</script>
<style>

.yks-<?php echo $this->_column['id']; ?> {
	<?php if ( ! empty( $this->_column['star-color'] ) ) { ?>
	color: <?php echo $this->_column['star-color']; ?>; 
	<?php } ?>
	cursor: pointer;
	}	
</style>
<?php
