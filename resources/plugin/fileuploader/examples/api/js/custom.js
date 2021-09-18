$(document).ready(function() {
	
	// enable fileuploader plugin
	var input = $('input:file').fileuploader({
		enableApi: true
	});
	
	// get API methods
	window.api = $.fileuploader.getInstance(input);
	
	
	console.log('var api = $.fileuploader.getInstance(input);');
	console.info('Here are the API methods:', api);
	console.info('Type in the console for example: api.disable()');
	alert('Open the Browser\'s console for more information!');
});