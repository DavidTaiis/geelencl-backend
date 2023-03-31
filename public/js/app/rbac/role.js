var modal_role = null;
var form_role = null;
var dataTable = null;
var table_permission = null;
$(function () {
    dataTable = initDataTableAjax($('#role_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_roles').val(),
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
                    data: "guard_name",
                    title: 'Guard'
                    // render: function (data, type, row, meta){
                    //     return
                    //
                    // }
                },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="editRole(' + row.id + ')">Editar</button>';
                    }
                }
            ]
        });
    modal_role = $('#modal');
});

function editRole(id) {
    modal_role.find('.modal-title').html('Editar rol');
    getFormRole($('#action_get_form').val() + '/' + id);
}

function newRole() {
    modal_role.find('.modal-title').html('Crear rol');
    getFormRole($('#action_get_form').val());
}

function saveRole() {
    if (form_role.valid()) {
        ajaxRequest($('#action_save_role').val(), {
            type: 'POST',
            data: table_permission.$(':checkbox').serialize() + String.fromCharCode(38) + form_role.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar el rol',
            success_message: 'El rol se guardo correctamente',
            success_callback: function (data) {
                modal_role.modal('hide');
                dataTable.ajax.reload();
            }
        });
    }
}

function getFormRole(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modal_role.find('.container_modal').html('');
            modal_role.find('.container_modal').html(data.html);
            form_role = $("#role_form");
            validateFormRole();
            table_permission = initDataTable($('#table_permission'), {
                initComplete: function (settings, json) {
                    $('#checkbox_all').off('click');
                    $('#checkbox_all').on('click', function () {
                        var cells = table_permission.cells({'search': 'applied'}).nodes();
                        $(cells).find(':checkbox').prop('checked', $(this).is(':checked'));
                    })
                }
            });

            modal_role.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateFormRole() {
    form_role.validate({
        rules: {
            name: {
                required: true,
                maxlength: 64,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#role_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $("#name").val().trim();
                        },
                    }
                }
            },
            guard_name: {
                required: true
            }
        },
        messages: {
            name: {
                remote: 'Ya existe una rol con ese nombre.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveRole();
        }
    });
}