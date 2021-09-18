<script>
    var check_dup_title_page = true;
    var check_dup_slug= true;
	$('document').ready(function () {

        $('#title_page').append('<label id="title-page-error-dup" class="dup-error" for="title" style="color:red">ชื่อ ซ้ำ</label>');

        $('#title-page-error-dup').hide()
		var typingTimer;
		var doneTypingInterval = 300;

		var $slug = $('#slug');
		var $title_page = $('#title_page input')
		var DB = $('#slug').attr('data-db')

		$slug.on('keyup', function () {
			clearTimeout(typingTimer);
			typingTimer = setTimeout(doneTypingSlug, doneTypingInterval);
			$('#slug').val(slug($('#slug').val()));
			$('#slug').removeClass('error');
			$('#slug-error').hide();
		});
        //on keyup, start the countdown
        $title_page.on('keyup', function () {
            clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTypingTitle, doneTypingInterval);
            typingTimer = setTimeout(doneTypingSlug, doneTypingInterval);
            $('#slug').val(slug($title_page.val()));
            $('#title_page').removeClass('error');
            //$('#title-page-error').hide();
        });
        //on keydown, clear the countdown
        $title_page.on('keydown', function () {
            clearTimeout(typingTimer);
        });

        function doneTypingTitle () {
            $.post('{{ route('check.check-dup-title') }}',{ _method:"POST" , title_page : $title_page.val(),form_id : $('#edit_').val()  , field : $title_page.attr("name") ,
                _db : DB , _token: '{{ csrf_token() }}' })
            .done(function(data){
                setTimeout(function(){

                    if(data == 0){
                        $('#title_page').removeClass('error');
                        $('#title-page-error-dup').hide()
                        check_dup_title_page=true;
                        $("button:submit").prop('disabled', false);
                    }else{
                        $('#title-page-error-dup').show()
                        check_dup_title_page=false;
                        $("button:submit").prop('disabled', true);
                    }
                }, 300);
            });
        }

        function doneTypingSlug () {
            $.post('{{ route('check.check-dup-slug') }}',{ _method:"POST" , slug : $slug.val(),form_id : $('#edit_').val()  , field : $slug.attr("name") ,
                _db : DB , _token: '{{ csrf_token() }}' })
            .done(function(data){
                setTimeout(function(){

                    if(data == 0){
                        $('#slug').removeClass('error');
                        $('#slug-error-dup').hide()
                        check_dup_slug=true;
                        $("button:submit").prop('disabled', false);

                    }else{
                        $('#slug-error-dup').show()
                        $('#slug').addClass('error');
                        check_dup_slug=false;
                        $("button:submit").prop('disabled', true);
                    }
                }, 300);
            });
        }

    });

	var slug = function(str) {
		var $slug = '';
		var trimmed = $.trim(str);
		$slug = trimmed.replace(/[^a-z0-9ก-๙-]/gi, '-').
		replace(/-+/g, '-').
		replace(/^-|-$/g, '');
		return $slug.toLowerCase();
	}
</script>
