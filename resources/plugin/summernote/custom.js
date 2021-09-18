$(document).ready(function () {

    //summernote

    $('.summernote').summernote({

        fontSizes: ['10', '11', '12', '14', '16', '18', '20', '22', '24', '36', '48', '64', '82', '100'],
        placeholder: 'Detail this',
        height: 300,
        tabsize: 2,
        toolbar: [
            ['style'],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['link'],
            ['table'],
            ['media', ['picture', 'video']],
            ['hr'],
            ['misc', ['fullscreen', 'codeview', 'undo', 'redo', 'help']]
        ],
        callbacks: {
            onImageUpload: function (files) {
                sendFile(files);
            }
        },

        //summernote


    });


    function sendFile(file, editor, welEditable) {

        data = new FormData();

        //console.log(file[0]);
        data.append("file", file[0]);
        //  data.append("_token","{{ csrf() }}");
        /*   data.append("csrfToken", get_cookie('csrfCookie')); */

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
            type: 'POST',
            url: "/admin/summernote",
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                //console.log(data);
                $('.summernote').summernote('editor.insertImage', data);
            },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                //console.log(msg)
            },
        });
    }
});


function sendFile(file, editor, welEditable) {

    data = new FormData();

    //console.log(file[0]);
    data.append("file", file[0]);
    /*  data.append("_token","{{ csrf() }}"); */
    /*   data.append("csrfToken", get_cookie('csrfCookie')); */
    ;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,
        type: 'POST',
        url: "/admin/summernote",
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            //console.log(data);
            $('.summernote').summernote('editor.insertImage', data);
        },
        error: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            //console.log(msg)
        },
    });
}
