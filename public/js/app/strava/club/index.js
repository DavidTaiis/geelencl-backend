var modal_model = null;
var form_model = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#model_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_models').val(),
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
                    data: "aplication_name",
                    title: 'Aplicación'
                },
                {
                    data: "active_race",
                    title: 'Carrera activa'
                },
                {
                    data: "status",
                    title: 'Estado sincronización',
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
                        var activityValidator = (row.active && row.active_race_id) ? ' <a class="btn btn-info btn-sm" href="' + $('#action_view').val() + '/' + row.active_race_id + '" >Ver actividades</a>' : '';
                        return '<button class="btn btn-dark btn-sm" onclick="editModel(' + row.id + ')">Editar</button>' + activityValidator;

                    }
                }
            ]
        });
    modal_model = $('#modal');
});

function editModel(id) {
    modal_model.find('.modal-title').html('Editar Club');
    getFormModel($('#action_get_form').val() + '/' + id);
}

function newModel() {
    modal_model.find('.modal-title').html('Crear Club');
    getFormModel($('#action_get_form').val());
}

function saveModel() {
    if (form_model.valid()) {
        ajaxRequest($('#action_save_model').val(), {
            type: 'POST',
            data: form_model.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar la aplicación',
            success_message: 'El aplicación se guardo correctamente',
            success_callback: function (data) {
                modal_model.modal('hide');
                dataTable.ajax.reload();
            }
        });
    }
}

function getFormModel(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modal_model.find('.container_modal').html('');
            modal_model.find('.container_modal').html(data.html);
            form_model = $("#model_form");
            validateFormModel();
            modal_model.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateFormModel() {
    form_model.validate({
        rules: {
            name: {
                required: true,
                maxlength: 64,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#model_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $("#name").val().trim();
                        },
                    }
                }
            },
            strava_id: {
                required: true
            },
            aplication_id: {
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
            saveModel();
        }
    });
}