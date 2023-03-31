var modalCity = null;
var formCity = null;
var dataTable = null;

$(function () {
    dataTable = initDataTableAjax($('#city_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_Cities').val(),
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
                        return '<button class="btn btn-dark btn-sm" onclick="editCity(' + row.id + ')">Editar</button>';
                    }
                }
            ]
        });
    modalCity = $('#modal');
});

function editCity(id) {
    current_city = id;
    modalCity.find('.modal-title').html('Editar ciudad');
    getFormCity($('#action_get_form').val() + '/' + id);
}

function newCity() {
    current_city = null;
    modalCity.find('.modal-title').html('Crear ciudad');
    getFormCity($('#action_get_form').val());
}

function saveCity() {
    
    if (formCity.valid()) {
        ajaxRequest(
            $('#action_save_City').val(),
            {
                type: 'POST',
                data: formCity.serialize(), // datos del formulario .serialize()
                blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar la ciudad',
                success_message: 'La ciudad se guardó correctamente',
                success_callback: function (data) {
                    modalCity.modal('hide'); // ocultar modal
                    dataTable.ajax.reload();
                }
            });
    }
}

function getFormCity(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalCity.find('.container_modal').html('');
            modalCity.find('.container_modal').html(data.html);
            formCity = $("#city_form");
            validateFormCity();
            $('#province_id').select2({
                dropdownParent: formCity,
                width: '100%',
                placeholder: '-Seleccione-',
            });
            modalCity.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateFormCity() {
    formCity.validate({
        rules: {
            name: {
                required: true,
                maxlength: 64,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#city_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $("#name_city").val().trim();
                        },
                    }
                }
            },
            province_id: {
                required: true,
            }

        },
        messages: {
            name: {
                remote: 'Ya existe una ciudad con ese nombre.'
            },
            province_id: {
                required: 'Seleccione una provincia.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveCity();
        }
    });
}

