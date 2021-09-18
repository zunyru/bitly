@isset($dataTypeContent->{$row->field})
<script src="{!!url('/js/jquery-3.4.1.min.js')!!}"></script>
<script src="{!!url('/plugin/summernote/custom.js')!!}"></script>
<script>
    $(document).ready(function () {

        //summernote
        $('.summernote').summernote({

            fontSizes: ['10', '11', '12', '14', '16', '18', '20', '22', '24', '36', '48', '64', '82',
                '100'
            ],
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

        });
        $('.summernote').summernote('code', `{!! $dataTypeContent->{$row->field} !!}`);

    });

</script>
@endisset
<textarea class="summernote" name="{{ $row->field }}" data-name="{{ $row->display_name }}" @if($row->required == 1) required @endif
step="any"
placeholder="{{ isset($options->placeholder)? old($row->field, $options->placeholder): $row->display_name }}"
value="@if(isset($dataTypeContent->{$row->field})){{ old($row->field, $dataTypeContent->{$row->field}) }}@else{{old($row->field)}}@endif">
</textarea>
