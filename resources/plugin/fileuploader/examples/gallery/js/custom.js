$(document).ready(function() {
	
	// enable fileuploader plugin
	var $fileuploader = $('input.gallery_media').fileuploader({
		limit: 100,
		fileMaxSize: 20,
		extensions: ['image/*', 'video/*', 'audio/*'],
		changeInput: ' ',
		theme: 'gallery',
		enableApi: true,
		thumbnails: {
			box: '<div class="fileuploader-items">' +
					  '<ul class="fileuploader-items-list">' +
						  '<li class="fileuploader-input"><button type="button" class="fileuploader-input-inner"><i class="fileuploader-icon-main"></i> <span>${captions.feedback}</span></button></li>' +
					  '</ul>' +
				  '</div>',
			item: '<li class="fileuploader-item">' +
					   '<div class="fileuploader-item-inner">' +
						   '<div class="actions-holder">' +
							   '<button type="button" class="fileuploader-action fileuploader-action-sort is-hidden" title="${captions.sort}"><i class="fileuploader-icon-sort"></i></button>' +
							   '<button type="button" class="fileuploader-action fileuploader-action-settings is-hidden" title="${captions.edit}"><i class="fileuploader-icon-settings"></i></button>' +
							   '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
							   '<div class="gallery-item-dropdown">' +
								   '<a class="fileuploader-action-popup">${captions.setting_edit}</a>' +
								   '<a class="gallery-action-rename">${captions.setting_rename}</a>' +
								   '<a class="gallery-action-asmain">${captions.setting_asMain}</a>' +
							   '</div>' +
						   '</div>' +
						   '<div class="thumbnail-holder">' +
							   '${image}' +
							   '<span class="fileuploader-action-popup"></span>' +
							   '<div class="progress-holder"><span></span>${progressBar}</div>' +
						   '</div>' +
						   '<div class="content-holder"><h5 title="${name}">${name}</h5><span>${size2}</span></div>' +
						   '<div class="type-holder">${icon}</div>' +
					   '</div>' +
				  '</li>',
			item2: '<li class="fileuploader-item file-main-${data.isMain}">' +
					   '<div class="fileuploader-item-inner">' +
						   '<div class="actions-holder">' +
							   '<button type="button" class="fileuploader-action fileuploader-action-sort" title="${captions.sort}"><i class="fileuploader-icon-sort"></i></button>' +
							   '<button type="button" class="fileuploader-action fileuploader-action-settings" title="${captions.edit}"><i class="fileuploader-icon-settings"></i></button>' +
							   '<button type="button" class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="fileuploader-icon-remove"></i></button>' +
							   '<div class="gallery-item-dropdown">' +
								   '<a href="${data.url}" target="_blank">${captions.setting_open}</a>' +
								   '<a href="${data.url}" download>${captions.setting_download}</a>' +
								   '<a class="fileuploader-action-popup">${captions.setting_edit}</a>' +
								   '<a class="gallery-action-rename">${captions.setting_rename}</a>' +
								   '<a class="gallery-action-asmain">${captions.setting_asMain}</a>' +
							   '</div>' +
						   '</div>' +
						   '<div class="thumbnail-holder">' +
							   '${image}' +
							   '<span class="fileuploader-action-popup"></span>' +
						   '</div>' +
						   '<div class="content-holder"><h5 title="${name}">${name}</h5><span>${size2}</span></div>' +
						   '<div class="type-holder">${icon}</div>' +
					   '</div>' +
				  '</li>',
			itemPrepend: true,
			startImageRenderer: true,
			canvasImage: false,
			onItemShow: function(item, listEl, parentEl, newInputEl, inputEl) {
				var api = $.fileuploader.getInstance(inputEl),
					color = api.assets.textToColor(item.format),
					$plusInput = listEl.find('.fileuploader-input'),
					$progressBar = item.html.find('.progress-holder');

				// put input first in the list
				$plusInput.prependTo(listEl);

				// color the icon and the progressbar with the format color
				item.html.find('.type-holder .fileuploader-item-icon')[api.assets.isBrightColor(color) ? 'addClass' : 'removeClass']('is-bright-color').css('backgroundColor', color);
				$progressBar.css('backgroundColor', color);
			},
			onImageLoaded: function(item, listEl, parentEl, newInputEl, inputEl) {
				var api = $.fileuploader.getInstance(inputEl);
				
				// add icon
				item.image.find('.fileuploader-item-icon i').html('')
					.addClass('fileuploader-icon-' + (['image', 'video', 'audio'].indexOf(item.format) > -1 ? item.format : 'file'));

				// check the image size
				if (item.format == 'image' && item.upload && !item.imU) {
					if (item.reader.node && (item.reader.width < 100 || item.reader.height < 100)) {
						alert(api.assets.textParse(api.getOptions().captions.imageSizeError, item));
						return item.remove();
					}

					item.image.hide();
					item.reader.done = true;
					item.upload.send();
				}

			},
			onItemRemove: function(html) {
				html.fadeOut(250);	
			}
		},
		dragDrop: {
			container: '.fileuploader-theme-gallery .fileuploader-input'
		},
		upload: {
			url: './php/ajax.php?type=upload',
			data: null,
			type: 'POST',
			enctype: 'multipart/form-data',
			start: true,
			synchron: true,
			beforeSend: function(item) {
				// check the image size first (onImageLoaded)
				if (item.format == 'image' && !item.reader.done)
					return false;

				// add editor to upload data after editing
				if (item.editor && (typeof item.editor.rotation != "undefined" || item.editor.crop)) {
					item.imU = true;
					item.upload.data.name = item.name;
					item.upload.data.id = item.data.listProps.id;
					item.upload.data._editorr = JSON.stringify(item.editor);
				}

				item.html.find('.fileuploader-action-success').removeClass('fileuploader-action-success');
			},
			onSuccess: function(result, item) {
				var data = {};

				try {
					data = JSON.parse(result);
				} catch (e) {
					data.hasWarnings = true;
				}

				// if success update the information
				if (data.isSuccess && data.files.length) {
					if (!item.data.listProps)
						item.data.listProps = {};
					item.title = data.files[0].title;
					item.name = data.files[0].name;
					item.size = data.files[0].size;
					item.size2 = data.files[0].size2;
					item.data.url = data.files[0].url;
					item.data.listProps.id = data.files[0].id;

					item.html.find('.content-holder h5').attr('title', item.name).text(item.name);
					item.html.find('.content-holder span').text(item.size2);
					item.html.find('.gallery-item-dropdown [download]').attr('href', item.data.url);
				}

				// if warnings
				if (data.hasWarnings) {
					for (var warning in data.warnings) {
						alert(data.warnings[warning]);
					}

					item.html.removeClass('upload-successful').addClass('upload-failed');
					return this.onError ? this.onError(item) : null;
				}

				delete item.imU;
				item.html.find('.fileuploader-action-remove').addClass('fileuploader-action-success');

				setTimeout(function() {
					item.html.find('.progress-holder').hide();

					item.html.find('.fileuploader-action-popup, .fileuploader-item-image').show();
					item.html.find('.fileuploader-action-sort').removeClass('is-hidden');
					item.html.find('.fileuploader-action-settings').removeClass('is-hidden');
				}, 400);
			},
			onError: function(item) {
				item.html.find('.progress-holder, .fileuploader-action-popup, .fileuploader-item-image').hide();

				// add retry button
				item.upload.status != 'cancelled' && !item.imU && !item.html.find('.fileuploader-action-retry').length ? item.html.find('.actions-holder').prepend(
					'<button type="button" class="fileuploader-action fileuploader-action-retry" title="Retry"><i class="fileuploader-icon-retry"></i></button>'
				) : null;
			},
			onProgress: function(data, item) {
				var $progressBar = item.html.find('.progress-holder');

				if ($progressBar.length) {
					$progressBar.show();
					$progressBar.find('span').text(data.percentage >= 99 ? 'Uploading...' : data.percentage + '%');
					$progressBar.find('.fileuploader-progressbar .bar').height(data.percentage + '%');
				}

				item.html.find('.fileuploader-action-popup, .fileuploader-item-image').hide();
			}
		},
		editor: {
			cropper: {
				showGrid: true,
				minWidth: 100,
				minHeight: 100
			},
			onSave: function(dataURL, item) {
				// if no editor
				if (!item.editor || !item.reader.width)
					return;

				// if uploaded
				// resend upload
				if (item.upload && item.upload.resend)
					item.upload.resend();

				// if preloaded
				// send request
				if (item.appended && item.data.listProps) {
					// hide current thumbnail
					item.imU = true;
					item.image.addClass('fileuploader-loading').find('img, canvas').hide();
					item.html.find('.fileuploader-action-popup').hide();

					$.post('php/ajax.php?type=resize', {name: item.name, id: item.data.listProps.id, _editor: JSON.stringify(item.editor)}, function() {
						// update the image
						item.reader.read(function() {
							delete item.imU;

							item.image.removeClass('fileuploader-loading').find('img, canvas').show();
							item.html.find('.fileuploader-action-popup').show();
							item.editor.rotation = item.editor.crop = null;
							item.popup = {open: item.popup.open};
						}, null, true);
					});
				}
			}	
		},
		sorter: {
			onSort: function(list, listEl, parentEl, newInputEl, inputEl) {
				var api = $.fileuploader.getInstance(inputEl),
					fileList = api.getFiles(),
					list = [];

				// prepare the sorted list
				api.getFiles().forEach(function(item) {
					if (item.data.listProps)
						list.push({
							name: item.name,
							id: item.data.listProps.id,
							index: item.index
						});
				});

				// send request
				$.post('php/ajax.php?type=sort', {
					list: JSON.stringify(list)
				});
			}
		},
		afterRender: function(listEl, parentEl, newInputEl, inputEl) {
			var api = $.fileuploader.getInstance(inputEl),
				$plusInput = listEl.find('.fileuploader-input');

			// bind input click
			$plusInput.on('click', function() {
				api.open();
			});
            
            // set drop container
            api.getOptions().dragDrop.container = $plusInput;

			// bind dropdown buttons
			$('body').on('click', function(e) {
				var $target = $(e.target),
					$item = $target.closest('.fileuploader-item'),
					item = api.findFile($item);

				// toggle dropdown
				$('.gallery-item-dropdown').hide();
				if ($target.is('.fileuploader-action-settings') || $target.parent().is('.fileuploader-action-settings')) {
					$item.find('.gallery-item-dropdown').show(150);
				}

				// rename
				if ($target.is('.gallery-action-rename')) {
					var x = prompt(api.getOptions().captions.rename, item.title);

					if (x && item.data.listProps) {
						$.post('php/ajax.php?type=rename', {name: item.name, id: item.data.listProps.id, title: x}, function(result) {
							try {
								var j = JSON.parse(result);

								// update the file name and url
								if (j.title) {
									item.title = j.title;
									item.name = item.title + (item.extension.length ? '.' + item.extension : '');
									$item.find('.content-holder h5').attr('title', item.name).html(item.name);
									$item.find('.gallery-item-dropdown [download]').attr('href', item.data.url);

									if (item.popup.html)
										item.popup.html.find('h5:eq(0)').text(item.name);

									if (j.url)
										item.data.url = j.url;
									if (item.appended && j.file)
										item.file = j.file;

									api.updateFileList();
								}

							} catch(e) {
								alert(api.getOptions().captions.renameError);
							}
						});
					}
				}

				// set main
				if ($target.is('.gallery-action-asmain') && item.data.listProps) {
					$.post('php/ajax.php?type=asmain', {name: item.name, id: item.data.listProps.id}, function() {
						api.getFiles().forEach(function(val) {
							delete val.data.isMain;
							val.html.removeClass('file-main-0 file-main-1');
						});
						item.html.addClass('file-main-1');
						item.data.isMain = true;

						api.updateFileList();
					});
				}
			});
		},
		onRemove: function(item) {
			// send request
			if (item.data.listProps)
				$.post('php/ajax.php?type=remove', {
					name: item.name,
					id: item.data.listProps.id
				});
		},
		captions: $.extend(true, {}, $.fn.fileuploader.languages['en'], {
			feedback: 'Drag & Drop',
			setting_asMain: 'Use as main',
			setting_download: 'Download',
			setting_edit: 'Edit',
			setting_open: 'Open',
			setting_rename: 'Rename',
			rename: 'Enter the new file name:',
			renameError: 'Please enter another name.',
			imageSizeError: 'The image ${name} is too small.',
		})
	});
	
	// preload the files
	$.post('php/ajax.php?type=preload', null, function(result) {
		var api = $.fileuploader.getInstance($fileuploader),
			preload = [];
		
		try {
			// preload the files
			preload = JSON.parse(result);
			
			api.append(preload);
		} catch(e) {}
	});
});