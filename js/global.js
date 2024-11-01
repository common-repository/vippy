jQuery(function() {
	jQuery('a.confirm-vippy-delete').live('click', function() {
		jQuery(this).closest('#fancybox-content').next().trigger('click');
		var thisID = jQuery(this).siblings('input[name="confirm-vippy-delete"]').val();
		if (thisID) {
			jQuery('#vippy-video-'+thisID).fadeOut(1000, function() { 
				jQuery(this).remove()
			});
		}
		return false;
	});
	jQuery("a.vippy-inline").fancybox({
		'hideOnContentClick': false, 
		'autoDimensions': false, 
		'width': 650, 
		'height': 370, 
		'padding': 10
	});
	jQuery('.insert-vippy').click(function() {
		var oldValue = jQuery('#editorcontainer #content').val();
		var videoID = jQuery(this).parent().siblings('input[name="vippy-video-id"]').val();
		jQuery('#editorcontainer #content').val(oldValue + '\n[vippy id="'+videoID+'"]');
	    jQuery('#content_ifr').contents().find('body').append('<p>[vippy id="'+videoID+'"]</p>');
		//return false;
	});
	jQuery('.embed-vippy').click(function() { 
		/* <![CDATA[ */
		var win = window.dialogArguments || opener || parent || top;
		vippyID = jQuery(this).parent().find('.vippy-embed-video-id').val();
		var vippyWidth = jQuery('#vippy-embed-width').val();
		var vippyHeight = jQuery('#vippy-embed-height').val();
		win.send_to_editor('[vippy id="'+vippyID+'" width="'+vippyWidth+'" height="'+vippyHeight+'"]');
		/* ]]> */
		return false;
	});
});