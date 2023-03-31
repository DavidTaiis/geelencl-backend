var modal_aplication = null;
var form_aplication = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#aplication_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_aplications').val(),
                data: function (filterDateTable) {
                    //additional params for ajax request
                    // filterDateTable.vendor_id = 3;
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
                    data: "client_id",
                    title: 'Client ID'
                },
                {
                    data: "status",
                    title: 'Estado',
                    render: function (data, type, row, meta) {
                        if (row.active === 1) {
                            return '<span class="label label-sm label-success">Activo</span>';
                        } else {
                            return '<span class="label label-sm label-warning">Inactivo</span>';
                        }
                    }
                },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="editAplication(' + row.id + ')">Editar</button>';
                    }
                }
            ]
        });
    modal_aplication = $('#modal');
});

function editAplication(id) {
    modal_aplication.find('.modal-title').html('Editar aplicación');
    getFormAplication($('#action_get_form').val() + '/' + id);
}

function newAplication() {
    modal_aplication.find('.modal-title').html('Crear aplicación');
    getFormAplication($('#action_get_form').val());
}

function saveAplication() {
    if (form_aplication.valid()) {
        ajaxRequest($('#action_save_aplication').val(), {
            type: 'POST',
            data: form_aplication.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar la aplicación',
            success_message: 'El aplicación se guardo correctamente',
            success_callback: function (data) {
                modal_aplication.modal('hide');
                dataTable.ajax.reload();
            }
        });
    }
}

function getFormAplication(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modal_aplication.find('.container_modal').html('');
            modal_aplication.find('.container_modal').html(data.html);
            form_aplication = $("#aplication_form");
            validateFormAplication();
            modal_aplication.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateFormAplication() {
    form_aplication.validate({
        rules: {
            name: {
                required: true,
                maxlength: 64,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#stravaAplication_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $("#name").val().trim();
                        },
                    }
                }
            },
            client_id: {
                required: true
            },
            cient_secret: {
                required: true
            },
            access_token: {
                required: true
            },
            refresh_token: {
                required: true
            }
        },
        messages: {
            name: {
                remote: 'Ya existe una aplicación con ese nombre.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveAplication();
        }
    });
}