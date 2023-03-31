var tableProvider = null;
var modalProvider = null;
var formProvider = null;
var imagesDeleted = [];
$(function () {
    tableProvider = initDataTableAjax(
        $('#provider_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_get_list_data').val(),
                data: function (filterDateTable) {
                    //additional params for ajax request
                }
            },
            "responsive": true,
            "language": {
                "paginate": {
                    "previous": '<i class="demo-psi-arrow-left"></i>',
                    "next": '<i class="demo-psi-arrow-right"></i>'
                }
            },
            columns: [
                {
                    data: "name",
                    title: 'Emprendedor'
                },
                {
                    data: "code",
                    title: 'Código'
                },
                {
                    data: "phone",
                    title: 'Teléfono'
                },
                {
                    data: 'status',
                    title: 'Estado',
                    render: function (data, type, row, meta) {
                        if (row.status === 'ACTIVE') {
                            return '<span class="label label-primary label-inline font-weight-lighter">Activo</span>';
                        } else {
                            return '<span class="label label-danger label-pill label-inline">Inactivo</span>';
                        }
                    },
                },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="editProvider(' + row.id + ')">Editar</button>';
                    }
                }
            ]
        });
    modalProvider = $('#provider_modal');
});

function editProvider(providerId) {
    modalProvider.find('.modal-title').html('Editar emprendedor');
    var url = $('#action_get_form').val() + '/' + providerId;
    getForm(url);
}

function newProvider() {
    modalProvider.find('.modal-title').html('Crear emprendedor');
    getForm($('#action_get_form').val());
}

function getForm(url) {
    ajaxRequest(
        url,
        {
            type: 'GET',
            error_message: 'Error al cargar formulario',
            success_callback: function (data) {
                modalProvider.find('.container_modal').html('');
                modalProvider.find('.container_modal').html(data.html);

                formProvider = $('#provider_form');
                initValidationForm();
                initDropZones();
                imagesDeleted = [];
                modalProvider.modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false // to prevent closing with Esc button (if you want this too)
                });
            }
        });
}

function initValidationForm() {
    formProvider.validate(
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
                code: {
                    required: true,
                    maxlength: 10,
                    remote: {
                        url: $('#action_unique_code').val(),
                        type: 'POST',
                        data: {
                            id: function () {
                                return $('#provider_id').val();
                            },
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            code: function () {
                                return $('#code').val().trim();
                            },
                        },
                    },
                },
            },
            messages: {
                name: {
                    required: "El campo nombre es requerido"
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
                blockElement: '#provider_modal .modalProvider-content',
                loading_message: 'Guardando...',
                error_message: 'Error al guardar el emprendedor',
                success_message: 'Se guardó correctamente',
                success_callback: function (data) {
                    modalProvider.modal('hide');
                    tableProvider.ajax.reload();
                }
            }, true);
    }
}


function initDropZones() {
    Dropzone.autoDiscover = false;
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