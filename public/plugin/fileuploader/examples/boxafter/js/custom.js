$(document).ready(function() {
    
	// enable fileuploader plugin
	$('input[name="files"]').fileuploader({
        changeInput: ' ',
        theme: 'boxafter',
        enableApi: true,
        thumbnails: {
            box: '<div class="fileuploader-items">' +
                      '<ul class="fileuploader-items-list"></ul>' +
                  '</div>' +
                  '<div class="fileuploader-input">' +
                      '<div class="fileuploader-input-inner">' +
                          '<h3>${captions.feedback} ${captions.or} <a>${captions.button}</a></h3>' +
                      '</div>' +
                      '<button type="button" class="fileuploader-input-button">&plus;</button>' +
                  '</div>',
            item: '<li class="fileuploader-item">' +
                       '<div class="columns">' +
                           '<div class="column-thumbnail">${image}<span class="fileuploader-action-popup"></span></div>' +
                           '<div class="column-title">' +
                               '<div title="${name}">${name}</div>' +
                               '<span>${size2}</span>' +
                           '</div>' +
                           '<div class="column-actions">' +
                               '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
                           '</div>' +
                           '${progressBar}' +
                       '</div>' +
                   '</li>',
        },
        afterRender: function(listEl, parentEl, newInputEl, inputEl) {
            var plusInput = parentEl.find('.fileuploader-input'),
                api = $.fileuploader.getInstance(inputEl.get(0));

            plusInput.on('click', function() {
                api.open();
            });
            
            api.getOptions().dragDrop.container = plusInput;
        },
		upload: {
            url: 'php/ajax_upload_file.php',
            data: null,
            type: 'POST',
            enctype: 'multipart/form-data',
            start: true,
            synchron: true,
            beforeSend: null,
            onSuccess: function(result, item) {
                var data = {};
				
				if (result && result.files)
                    data = result;
                else
					data.hasWarnings = true;
                
				// if success
                if (data.isSuccess && data.files[0]) {
                    item.name = data.files[0].name;
					item.html.find('.column-title > div:first-child').text(data.files[0].name).attr('title', data.files[0].name);
                }
				
				// if warnings
				if (data.hasWarnings) {
					for (var warning in data.warnings) {
						alert(data.warnings[warning]);
					}
					
					item.html.removeClass('upload-successful').addClass('upload-failed');
					return this.onError ? this.onError(item) : null;
				}
                
                item.html.find('.fileuploader-action-remove').addClass('fileuploader-action-success');
                
                item.html.find('.column-title span').html(item.size2);
                setTimeout(function() {
                    item.progressBar.fadeOut(400);
                }, 400);
            },
            onError: function(item) {
				if (item.progressBar) {
					item.html.find('.column-title span').html(item.size2);
                    item.progressBar.hide().find('.bar').width(0);
				}
                
                item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
                    '<button type="button" class="fileuploader-action fileuploader-action-retry" title="Retry"><i class="fileuploader-icon-retry"></i></button>'
                ) : null;
            },
            onProgress: function(data, item) {
                item.html.find('.column-title span').html(data.percentage == 99 ? 'Uploading...' : data.loadedInFormat + ' / ' + data.totalInFormat);
                item.progressBar.show().find('.bar').width(data.percentage + "%");
            },
            onComplete: null,
        },
		onRemove: function(item) {
			$.post('./php/ajax_remove_file.php', {
				file: item.name
			});
		},
		captions: $.extend(true, {}, $.fn.fileuploader.languages['en'], {
            feedback: 'Drag files here',
            or: 'or',
            button: 'Browse',
        }),
	});
	
});