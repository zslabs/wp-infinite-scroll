(function($) {

	// Numeric only control handler
	$("input.numbers-only").keydown(function(event) {
		// Allow: backspace, delete, tab, escape, and enter
		if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
			 // Allow: Ctrl+A
			(event.keyCode == 65 && event.ctrlKey === true) || 
			 // Allow: home, end, left, right
			(event.keyCode >= 35 && event.keyCode <= 39)) {
				 // let it happen, don't do anything
				 return;
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
				event.preventDefault(); 
			}   
		}
	});

	var WPIS_Configuration = {
		init : function() {
			this.files();
		},

		files : function() {
			if ( $( '.wpis_upload_image_button' ).length > 0 ) {
				window.formfield = '';

				$('.wpis_upload_image_button').on('click', function(e) {
					e.preventDefault();
					window.formfield = $(this).parent().prev();
					window.tbframe_interval = setInterval(function() {
						jQuery('#TB_iframeContent').contents().find('.savesend .button').val(wpis_vars.use_this_file).end().find('#insert-gallery, .wp-post-thumbnail').hide();
					}, 2000);
				if (wpis_vars.post_id != null ) {
					var post_id = 'post_id=' + wpis_vars.post_id + '&';
				}
					tb_show(wpis_vars.add_new_file, 'media-upload.php?' + post_id +'TB_iframe=true');
				});

				window.original_send_to_editor = window.send_to_editor;
				window.send_to_editor = function (html) {
					if (window.formfield) {
						imgurl = $('a', '<div>' + html + '</div>').attr('href');
						window.formfield.val(imgurl);
						window.clearInterval(window.tbframe_interval);
						tb_remove();
						$('.preview-image img').attr('src',imgurl);
					} else {
						window.original_send_to_editor(html);
					}
					window.formfield = '';
					window.imagefield = false;
				}
			}
		}
	}

	WPIS_Configuration.init();

})(jQuery);