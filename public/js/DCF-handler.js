(function( $ ) {
    $(function () {

        $('#DCF input, #DCF textarea').on('input', function () {
            var el = $(this);
            var validate;

            if(el.attr('type') === 'email') {
                if( !(emailRegDCF.test(el.val())) && el.val() ) {
                    validate = false;
                    textErrorDCF = 'Был введён не корректный email';
                }
            }

            if(el.attr('type') === 'file') {
                var maxSize = "8388608"; // 8Mb
                var pictureMimes = ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'];

                if( !(!el.val() || el[0].files[0].size < maxSize) ) {
                    validate = false;
                    textErrorDCF = 'Максимальный размер файла 8 Mb';
                    simpleNotifyDCF(textErrorDCF, 'error', 8000, 'bottom right');
                }

                if( !($.inArray( el[0].files[0].type, pictureMimes ) !== -1) ) {
                    validate = false;
                    textErrorDCF = 'Загружать можно только картинки (png, jpg, gif)';
                    simpleNotifyDCF(textErrorDCF, 'error', 8000, 'bottom right');
                }
            }

            if(validate === false) { el.addClass('errorDCF'); }
            else { el.removeClass('errorDCF'); }
        });

        $(document).on('submit', '#DCF', function (e) {
            e.preventDefault();

            var el = $(this);

            var endCheck = [];
            var types = [];
            var checkEmpty = '';

            el.find('input, textarea').not('[type="submit"]').each(function () {
                checkEmpty += $(this).val();
                if($(this).attr('type') !== 'file'){
                    types.push($(this).attr( 'type') ? $(this).attr( 'type') : 'textarea' );
                }

                if($(this).hasClass('errorDCF')) { endCheck.push(1); }
            });

            if(endCheck.length) { simpleNotifyDCF(textErrorDCF, 'error', 8000, 'bottom right'); return; }
            if(!checkEmpty.length) { simpleNotifyDCF('Форма - пустая', 'error', 8000, 'bottom right'); return; }

            var fd = new FormData();

            var data = $(this).serializeArray();
            $.each(data,function(key, input){
                fd.append(input.name, [ input.value, types[key] ]);
            });

            el.find('input[type="file"]').each(function () {
                if($(this)[0].files[0]) {
                    fd.append( $(this).attr('name'), $(this)[0].files[0] );
                }
            });

            fd.append('security', DFCAjax.security);
            fd.append('action', 'route');
            fd.append('controller', 'Simple');
            fd.append('method', 'mainHandler');

            // without jquery, because not work contentType = false and processData = false for upload files. Maybe I have bad version Wordpress.
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    DCF_Callback(response);
                }
            };
            xhttp.open("POST", DFCAjax.ajax_url, true);
            xhttp.setRequestHeader("enctype","multipart/form-data");
            xhttp.send(fd);
        });

        function DCF_Callback(data) {
            if(data.success) {
                simpleNotifyDCF("Успешно отправлено", 'success', 8000, 'bottom right');
                $('#DCF').find('input, textarea').not('input[type="submit"]').each(function () {
                    $(this).val('');
                    $(this).prop('checked', false);
                });
            }
            else { simpleNotifyDCF(data.data.response, 'error', 8000, 'bottom right'); }
        }
    });
})( jQuery );