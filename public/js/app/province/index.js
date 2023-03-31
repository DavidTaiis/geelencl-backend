var modalProvince = null;
var formProvince = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#province_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_provinces').val(),
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
                        return '<button class="btn btn-dark btn-sm" onclick="editProvince(' + row.id + ')">Editar</button>';
                    }
                }
            ]
        });
    modalProvince= $('#modal');
});

function editProvince(id) {
    modalProvince.find('.modal-title').html('Editar provincia');
    getFormProvince($('#action_get_form').val() + '/' + id);
}

function newProvince() {
    modalProvince.find('.modal-title').html('Crear provincia');
    getFormProvince($('#action_get_form').val());
}

function saveProvince() {
    if (formProvince.valid()) {
        ajaxRequest($('#action_save_province').val(), {
            type: 'POST',
            data: formProvince.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar el provincia',
            success_message: 'El provincia se guardo correctamente',
            success_callback: function (data) {
                modalProvince.modal('hide');
                dataTable.ajax.reload();
            }
        });
    }
}

function getFormProvince(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalProvince.find('.container_modal').html('');
            modalProvince.find('.container_modal').html(data.html);
            formProvince = $("#province_form");
            validateFormProvince();
            $('#country_id').select2({
                dropdownParent: $('#modal'),
                width: '100%',
                placeholder: '-Seleccione-',
              });
            modalProvince.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateFormProvince() {
    formProvince.validate({
        rules: {
            name: {
                required: true,
                maxlength: 64,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#province_id').val();
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
                remote: 'Ya existe una provincia con ese nombre.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveProvince();
        }
    });
}