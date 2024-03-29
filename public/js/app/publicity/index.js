var tablePublicity = null;
var imagesDeleted = [];
$(function () {
    tablePublicity = initDataTableAjax(
        $('#publicity_table'),
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
                    title: 'Nombre'
                },
                {
                    data: "link",
                    title: 'Link'
                },
                {
                    data: "status",
                    title: 'Estado',
                    render: function (data, type, row, meta) {
                        if (row.status === 'ACTIVE') {
                            return '<span class="label label-primary label-inline font-weight-lighter">Activo</span>';
                        } else {
                            return '<span class="label label-danger label-pill label-inline">Inactivo</span>';
                        }
                    }
                },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return ' <button class="btn btn-dark btn-sm" onclick="editPublicity(' + row.id + ')">Editar</button>';
                    }
                }
            ]
        });
});

function editPublicity(publicityId) {
    $('#publicity_modal').find('.modal-title').html('Editar Publicidad');
    var url = $('#action_get_form').val() + '/' + publicityId;
    getForm(url);
}

function newPublicity() {
    $('#publicity_modal').find('.modal-title').html('Crear Publicidad');
    getForm($('#action_get_form').val());
}

function getForm(url) {
    ajaxRequest(
        url,
        {
            type: 'GET',
            error_message: 'Error al cargar formulario',
            success_callback: function (data) {
                $('#publicity_modal').find('.container_modal').html('');
                $('#publicity_modal').find('.container_modal').html(data.html);

                initValidationForm();

                $('#publicity_modal').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false // to prevent closing with Esc button (if you want this too)
                });

                initDropZones();
                imagesDeleted = [];
            }
        });
}

function initValidationForm() {
    $('#publicity_form').validate(
        {
            rules: {
                name: {
                    required: true,
                    maxlength: 45,
                },
                link: {
                    required: true,
                    url: true
                },
            },
            messages: {},
            errorElement: 'small',
            errorClass: 'help-block',
            highlight: validationHighlight,
            success: validationSuccess,
            errorPlacement: validationErrorPlacement,
            submitHandler: function (form) {
                savePublicity();
            }
        }
    );
}

function savePublicity() {

    if ($('#publicity_form').valid()) {
        var data = $('#publicity_form').serializeArray();
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
                blockElement: '#publicity_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar el publicidad',
                success_message: 'Se guardó correctamente',
                success_callback: function (data) {
                    $('#publicity_modal').modal('hide');
                    tablePublicity.ajax.reload();
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