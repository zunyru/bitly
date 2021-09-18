$(document).ready(function () {

    // enable fileuploader plugin
    $('input[name="filesfull"]').fileuploader({
        theme: 'onebutton',
        maxSize: 7,
        fileMaxSize: 7,
        extensions: ['jpg', 'jpeg'],
        limit: 1,
    });

    $('input[name="fileshalf"]').fileuploader({
        theme: 'onebutton',
        maxSize: 7,
        fileMaxSize: 7,
        extensions: ['jpg', 'jpeg'],
        limit: 1,
    });

    $('input[name="filescloseup"]').fileuploader({
        theme: 'onebutton',
        maxSize: 7,
        fileMaxSize: 7,
        extensions: ['jpg', 'jpeg'],
        limit: 1,
    });

});
