$(document).ready(function() {
	
	// enable fileuploader plugin
	$('input[name="files"]').fileuploader({
		extensions: ['image/*'],
		addMore: true,
        thumbnails: {
            onImageLoaded: function(item) {
                if (!item.html.find('.fileuploader-action-edit').length)
                    item.html.find('.fileuploader-action-remove').before('<button type="button" class="fileuploader-action fileuploader-action-popup fileuploader-action-edit" title="Edit"><i class="fileuploader-icon-edit"></i></button>');
            }
        },
		editor: {
			cropper: {
				ratio: '1:1',
				minWidth: 100,
				minHeight: 100,
				showGrid: true
			}
		}
	});
	
});