var modal_country = null;
var form_country = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#country_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_countries').val(),
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
                        return '<button class="btn btn-dark btn-sm" onclick="editCountry(' + row.id + ')">Editar</button>';
                    }
                }
            ]
        });
    modal_country = $('#modal');
});

function editCountry(id) {
    modal_country.find('.modal-title').html('Editar país');
    getFormCountry($('#action_get_form').val() + '/' + id);
}

function newCountry() {
    modal_country.find('.modal-title').html('Crear país');
    getFormCountry($('#action_get_form').val());
}

function saveCountry() {
    if (form_country.valid()) {
        ajaxRequest($('#action_save_country').val(), {
            type: 'POST',
            data: form_country.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar el país',
            success_message: 'El país se guardo correctamente',
            success_callback: function (data) {
                modal_country.modal('hide');
                dataTable.ajax.reload();
            }
        });
    }
}

function getFormCountry(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modal_country.find('.container_modal').html('');
            modal_country.find('.container_modal').html(data.html);
            form_country = $("#country_form");
            validateFormCountry();
            modal_country.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateFormCountry() {
    form_country.validate({
        rules: {
            name: {
                required: true,
                maxlength: 64,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#country_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $("#name").val().trim();
                        },
                    }
                }
            }
        },
        messages: {
            name: {
                remote: 'Ya existe una país con ese nombre.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveCountry();
        }
    });
}