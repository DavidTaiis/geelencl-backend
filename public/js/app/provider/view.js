var tableProvider = null;
var modalProvider = null;
var formProvider = null;
var imagesDeleted = [];
var url = $('#action_get_form').val();

Dropzone.autoDiscover = false;

$(function () {
    formProvider = $('#user_form');
    initValidationForm();
    initDropZones();
});

function initValidationForm() {
    $('#user_form').validate(
        {
            rules: {
                name: {
                    required: true,
                    maxlength: 128,
                },
                owner: {
                    required: true,
                    maxlength: 128,
                },
                category: {
                    required: true,
                    maxlength: 64
                },
                phone: {
                    required: true,
                    maxlength: 10,
                    digits: true
                },
                address: {
                    maxlength: 64
                },
                provider_code: {
                    required: true,
                    maxlength: 10,
                    remote: {
                        url: $('#action_unique_code').val(),
                        type: 'POST',
                        data: {
                            id: function () {
                                return $('#input_provider_id').val();
                            },
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            code: function () {
                                return $('#provider_code').val().trim();
                            },
                        },
                    },
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: $('#action_unique_email').val(),
                        type: 'POST',
                        data: {
                            id: function () {
                                return $('#user_id').val();
                            },
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            name: function () {
                                return $('#email').val().trim();
                            },
                        },
                    },
                },
                password: {
                    required: true,
                },
                confirm_password: {
                    required: true,
                    equalTo: '#password',
                },
            },
            messages: {
                confirm_password: {
                    equalTo: "La contraseña no coincide"
                }
            },
            errorElement: 'small',
            errorClass: 'help-block',
            highlight: validationHighlight,
            success: validationSuccess,
            errorPlacement: validationErrorPlacement,
            submitHandler: function (form) {
                saveProvider();
            }
        }
    );
}

function saveProvider() {
    if (formProvider.valid()) {
        var data = formProvider.serializeArray();
        var files = getFiles();
        var dataForm = new FormData();

        for (var i = 0; i < data.length; i++) {
            dataForm.append(data[i].name, data[i].value);
        }
        $.each(imagesDeleted, function (index, idImage) {
            dataForm.append('filesDeleted[' + index + ']', idImage);
        });
        $.each(files, function (index, file) {
            dataForm.append('files[' + index + ']', file);
            dataForm.append('filesParams[' + index + ']',
                JSON.stringify(file.params));
        });
        ajaxRequest(
            $('#action_save').val(),
            {
                type: 'POST',
                data: dataForm,
                loading_message: 'Guardando...',
                error_message: 'Error al guardar el emprendedor',
                success_message: 'Se guardó correctamente',
                success_callback: function (data) {

                }
            }, true);
    }
}


function initDropZones() {
    $('.wrapper_image').each(function (i) {
        var config = $(this).data();
        initDropZone($(this), config);
    });
}

function getFiles() {
    var files = [];
    $.each($('.wrapper_image'), function (i) {
        var myDropZone = Dropzone.forElement(this);
        files = files.concat(myDropZone.files);
    });
    return files;
}

function deleteImage(id) {
    imagesDeleted.push(id);
    $('#' + id + '_wrapper_image').remove();
}