var modalStore = null;
var formStore = null;
var dataTable = null;

$(function () {
    dataTable = initDataTableAjax(
        $('#store_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_stores').val(),
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
                    data: "address",
                    title: 'Dirección'
                },
                {
                    data: 'provider.name',
                    title: 'Emprendedor',
                    orderable: false,
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
                        return '<button class="btn btn-dark btn-sm" onclick="editStore(' +
                        row.id + ')">Editar</button>';
                    }
                }
            ]
        });

    modalStore = $('#store_modal'); // busca un elemento con id modal
});

function editStore(id) {
    modalStore.find('.modal-title').html('Editar tienda');
    getFormStore($('#action_get_form').val() + '/' + id);
}

function newStore() {
    modalStore.find('.modal-title').html('Crear tienda');
    getFormStore($('#action_get_form').val());
}

function saveStore() {
    if (formStore.valid()) {
        ajaxRequest(
            $('#action_save_store').val(),
            {
                type: 'POST',
                data: formStore.serialize(), // datos del formulario .serialize()
                blockElement: '#store_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar la tienda',
                success_message: 'La tienda se guardó correctamente',
                success_callback: function (data) {
                    modalStore.modal('hide'); // ocultar modal
                    dataTable.ajax.reload();
                }
            });
    }
}
function getFormStore(url) {
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalStore.find('.container_modal').html('');
            modalStore.find('.container_modal').html(data.html);
            formStore = $("#store_form");
            validateFormStore();
            $('#provider_id').select2({
                dropdownParent: $('#store_modal'),
                width: '100%',
                placeholder: '-Seleccione-',
                allowClear: true
            });
            $('#city_id').select2({
                dropdownParent: $('#store_modal'),
                width: '100%',
                placeholder: '-Seleccione-',
                allowClear: true
            });
            modalStore.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateFormStore() {
    formStore.validate({
        rules: {
            address: {
                required: true,
                maxlength: 256
            },
            provider_id: {
                required: true,
                maxlength: 11
            },
            city_id: {
                required: true,
                maxlength: 11
            },
            name: {
                required: true,
                maxlength: 128,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#store_id').val();
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
                remote: 'Ya existe una tienda con ese nombre.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveStore();
        }
    });
}