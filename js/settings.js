jQuery(document).ready(function($) {
	$('.has-datepicker').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});	
	$('.add-file').click(function(e) {
		e.preventDefault();
		var custom_uploader = wp.media({
			multiple: false
		})
	.on('select', function() {
		var attachment = custom_uploader.state().get('selection').first().toJSON();
		$('.file-output').text(attachment.filename);
		$('.file-input-url').attr('value', attachment.url);
		$('.file-input-name').attr('value', attachment.filename);
	})
	.open();
	});
	
	$('.remove-file').click(function(e) {
		e.preventDefault();
		$('.file-input-url, .file-input-name').attr('value', '');
	});
});
