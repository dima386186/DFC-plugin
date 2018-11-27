(function( $ ) {
    $(function() {
        var commonSendData = {
            action: 'route',
            security: DFCAjax.security
        };
        var addonForSendData= '&action=route&controller=Admin&security='+DFCAjax.security+'&method=';

        $(".dcf-create").click(function(){
            $("#ModalDCF-for-create").modal();
        });

        $(document).on('click', '.dcf-edit', function(){
            var el = $(this);
            var mainEl = el.closest('.wrap-block-dcf');
            var id = mainEl.data('id');
            var sendData = {
                controller: 'Admin',
                method: 'getFieldById',
                id: id
            };
            $.get(ajaxurl, Object.assign(sendData, commonSendData), function (data) {
                if(data.success === false) {
                    simpleNotifyDCF(data.data.response, 'error', 8000, 'right bottom');
                } else {
                    $('#edit-name-field-dcf').text(mainEl.data('name'));
                    $('.dcf-modal-edit-body').html(data);
                    $("#ModalDCF-for-edit").modal();
                }
            });
        });

        $(document).on('click', '.dcf-delete', function(){
            var el = $(this);
            var mainEl = el.closest('.wrap-block-dcf');
            $("#ModalDCF-for-delete").modal();
            $('#delete-name-field-dcf').text(mainEl.data('name'));
            $('#delete-field-dcf').attr('data-id', mainEl.data('id'));
            $('#delete-field-dcf').attr('data-place', mainEl.data('place'));
        });

        $(document).on('input', '#DCF-add-new-field #label, #DCF-edit-field #label', function () {
            var el = $(this);
            var dependField = el.closest('.checkbox').next();
            if(el.is(':checked')) {
                dependField.show();
            } else {
                dependField.hide();
                dependField.find('input').val('');
            }
        });

        $(document).on('change', '#DCF-add-new-field #type', function () {
            var el = $(this);

            if(el.val() === 'file') {
                $('.FCD-value-field, .FCD-name-field, .FCD-placeholder-field').hide();
                $('#name').removeAttr('required');
            } else {
                $('.FCD-value-field, .FCD-name-field, .FCD-placeholder-field').show();
                $('#name').attr('required', true);
            }
        });

        $(document).on('submit', '#DCF-add-new-field, #DCF-edit-field', function (e) {
            e.preventDefault();
            var el = $(this);
            var elId = el.attr('id');
            var type = el.find('#type');

            if(elId === 'DCF-add-new-field'){
                var nameField = el.find('[name="name"]');

                nameField.val(nameField.val().replace(/ /g, '_')); // replace spaces on _ in name field

                var nameCheck = nameField.val();

                if(/^(?=file_)/.test(nameCheck) && type.val()!=='file') { simpleNotifyDCF('file_ - зарезервированное имя', 'error', 8000, 'bottom right'); return; }

                if(!nameCheck.length && type.val() !== 'file') { simpleNotifyDCF('Имя - обязательное поле', 'error', 8000, 'bottom right'); return; }


                var dataForPlace = $('.wrap-block-dcf').last().data('place');
                var place = dataForPlace ? +dataForPlace + 1 : 1;
                el.find('[name="place"]').val(place);


                if(type.val() === 'file') {
                    var fileLength = [];
                    $(':regex(data-name, ^(?=file_))').each(function () {
                        fileLength.push( +$(this).data('name').split('_')[1] );
                    });
                    var nameFile = fileLength.length ? 'file_' + (fileLength.length + 1) : 'file_1';
                    nameField.val(nameFile);
                }
            }

            var wrapperClasses = el.find('#wrap_classes').val();
            if(/(,|\.).*/.test(wrapperClasses)){
                simpleNotifyDCF('В названиях классов для wrapper не должно быть запятых и точек', 'error', 8000, 'bottom right');
                return;
            }

            var sendData = el.serialize();
            if(elId === 'DCF-add-new-field') { sendData += addonForSendData + 'addField'; }
            else { sendData += addonForSendData + 'editField'; }

            $.post(ajaxurl, sendData, function (data) {
                if(data.success === false) {
                    simpleNotifyDCF(data.data.response, 'error', 8000, 'right bottom');
                } else {
                    successRequest(el.attr('id'), data, el.find('#place'));
                }
            });
        });

        $('#delete-field-dcf').on('click', function () {
            var el = $(this);
            var id = el.attr('data-id');
            var name = $('#delete-name-field-dcf').text();
            var place = el.attr('data-place');

            var sendData = {
                controller: 'Admin',
                method: 'removeField',
                id: id,
                name: name
            };

            $.post(ajaxurl, Object.assign(sendData, commonSendData), function (data) {
                if(data.success === false) {
                    simpleNotifyDCF(data.data.response, 'error', 8000, 'right bottom');
                } else {
                    simpleNotifyDCF('Успешно удалено', 'success', 8000, 'right bottom');
                    $('.wrap-block-dcf[data-place="'+place+'"]').remove();
                    $('#ModalDCF-for-create .close').click();
                }
            });
        });

        $(document).on('click', '.dcf-up', function () {
            var el = $(this);
            var mainEl = el.closest('.wrap-block-dcf');
            var upPlaceEl = mainEl.prev('.wrap-block-dcf');
            var upPlace = upPlaceEl.length ? upPlaceEl.attr('data-place') : false;

            if(upPlace!==false){
                var place = mainEl.attr('data-place');
                var placeId = mainEl.attr('data-id');
                var placeUpId = upPlaceEl.attr('data-id');

                var sendData = {
                    controller: 'Admin',
                    method: 'fieldUp',
                    place: place,
                    upPlace: upPlace,
                    id: placeId,
                    upId: placeUpId
                };

                $.post(ajaxurl, Object.assign(sendData, commonSendData), function (data) {
                    if(data.success === false) {
                        simpleNotifyDCF(data.data.response, 'error', 8000, 'right bottom');
                    } else {
                        mainEl.insertBefore(upPlaceEl);
                        mainEl.attr('data-place', upPlace);
                        upPlaceEl.attr('data-place', place);
                    }
                });
            }
        });

        $(document).on('click', '.dcf-down', function () {
            var el = $(this);
            var mainEl = el.closest('.wrap-block-dcf');
            var downPlaceEl = mainEl.next('.wrap-block-dcf');
            var downPlace = downPlaceEl.length ? downPlaceEl.attr('data-place') : false;

            if(downPlace!==false){
                var place = mainEl.attr('data-place');
                var placeId = mainEl.attr('data-id');
                var placeDownId = downPlaceEl.attr('data-id');

                var sendData = {
                    controller: 'Admin',
                    method: 'fieldDown',
                    place: place,
                    downPlace: downPlace,
                    id: placeId,
                    downId: placeDownId
                };

                $.post(ajaxurl, Object.assign(sendData, commonSendData), function (data) {
                    if(data.success === false) {
                        simpleNotifyDCF(data.data.response, 'error', 8000, 'right bottom');
                    } else {
                        mainEl.insertAfter(downPlaceEl);
                        mainEl.attr('data-place', downPlace);
                        downPlaceEl.attr('data-place', place);
                    }
                });
            }
        });

        function successRequest(id, data, number) {
            if(id==='DCF-add-new-field') {
                simpleNotifyDCF('Успешно добавлено', 'success', 8000, 'right bottom');
                $('.fields-settings-dcf').append(data);
                $('#DCF-add-new-field').find('[type="text"]').val('');
                $('#DCF-add-new-field').find('[type="checkbox"]').prop('checked', false);
                $('#DCF-add-new-field').find('#label_text').closest('div').hide();
                $('#ModalDCF-for-create .close').click();
            } else {
                simpleNotifyDCF('Успешно отредактировано', 'success', 8000, 'right bottom');
                $('.wrap-block-dcf[data-place="'+number+'"]').html(data);
                $('#ModalDCF-for-edit .close').click();
            }
        }
    });
})( jQuery );