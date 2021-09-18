$(document).ready(function () {

    // enable fileuploader plugin
    var $fileuploader = $('input.gallery_media').fileuploader({
        limit: 100,
        fileMaxSize: 20,
        extensions: ['image/*'],
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
        },
        dragDrop: {
            container: '.fileuploader-theme-gallery .fileuploader-input'
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
});
