(function( $ ) {
    $(function() {

        $('.main-settings-dcf input, .main-settings-dcf textarea').on('input', function () {
            var el = $(this);
            var rule = el.closest('.form-group');

            if( rule.data('validate') === 'minlength' ) {
                validateDCF = el.val().length >= rule.data('minlength') || !el.val();
                if (!validateDCF) { textErrorDCF = 'Минимальная длина ' + rule.data('minlength') + ' символа'; }
            }

            if( rule.data('validate') === 'email' ) {
                validateDCF = emailRegDCF.test(el.val()) || !el.val();
                if(!validateDCF) { textErrorDCF = 'Не корректный email'; }
            }

            if(!validateDCF) {
                el.css('border', '3px solid red');
            } else {
                el.css('border', '1px solid grey');
            }
        });

        $('.create-main-setting-dcf').on('click', function () {
            if( validateDCF === false ) {
                simpleNotifyDCF(textErrorDCF, 'error', 8000, 'bottom right');
                return;
            }

            var needId = $(this).data('handler');
            var val = $('#'+needId).val();
            var mainId = $('.main-settings-dcf').data('id');

            if( !$.trim(val) ) { simpleNotifyDCF('Пустое значение', 'error', 8000, 'bottom right'); return; }

            var main_data = {
                id: mainId,
                field: needId,
                value: val
            };

            var data = {
                action: 'route',
                controller: 'Admin',
                method: 'pluginMainSettings',
                main_data: main_data,
                security: DFCAjax.security
            };

            $.post(ajaxurl, data, function (data) {
                if(data.success){
                    simpleNotifyDCF('Данные успешно сохранены', 'success', 8000, 'bottom right');
                } else {
                    simpleNotifyDCF(data.data.response, 'error', 8000, 'bottom right');
                }

            }).fail(function(){ simpleNotifyDCF('Ошибка при запросе', 'error', 8000, 'bottom right'); });
        });
    });
})( jQuery );