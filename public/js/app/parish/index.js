var modalParish = null;
var formParish = null;
var dataTable = null;

$(function () {
    dataTable = initDataTableAjax($('#parish_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_Parishes').val(),
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
                        return '<button class="btn btn-dark btn-sm" onclick="editParish(' + row.id + ')">Editar</button>';
                    }
                }
            ]
        });
    modalParish = $('#modal');
});

function editParish(id) {
    console.log(id);
    modalParish.find('.modal-title').html('Editar parroquia');
    getFormParish($('#action_get_form').val() + '/' + id);
}

function newParish() {
    modalParish.find('.modal-title').html('Crear parroquia');
    getFormParish($('#action_get_form').val());
}

function saveParish() {
    
    if (formParish.valid()) {
        ajaxRequest(
            $('#action_save_Parish').val(),
            {
                type: 'POST',
                data: formParish.serialize(), // datos del formulario .serialize()
                blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar la parroquia',
                success_message: 'La ciudad se guardó correctamente',
                success_callback: function (data) {
                    modalParish.modal('hide'); // ocultar modal
                    dataTable.ajax.reload();
                }
            });
    }
}

function getFormParish(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalParish.find('.container_modal').html('');
            modalParish.find('.container_modal').html(data.html);
            formParish = $("#parish_form");
            validateFormParish();
            $('#city_id').select2({
                dropdownParent: formParish,
                width: '100%',
                placeholder: '-Seleccione-',
            });
            modalParish.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateFormParish() {
    formParish.validate({
        rules: {
            name: {
                required: true,
                maxlength: 64,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#parish_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $("#name_parish").val().trim();
                        },
                    }
                }
            },
            city_id: {
                required: true,
            }

        },
        messages: {
            name: {
                remote: 'Ya existe una parroquia con ese nombre.'
            },
            city_id: {
                required: 'Seleccione una ciudad.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveParish();
        }
    });
}

