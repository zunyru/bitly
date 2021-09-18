$(document).ready(function() {
	
	// enable fileuploader plugin
	$('input:file').fileuploader({
		limit: 100,
		maxSize: 100,
	});
	
});