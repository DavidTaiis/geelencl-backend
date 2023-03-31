var modal_user = null;
var form_user = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#user_table'),
        {
            'processing': true,
            'serverSide': true,
            ajax: {
                url: $('#action_list').val(),
                data: function (filterDateTable) {
                    //additional params for ajax request
                    // filterDateTable.vendor_id = 3;
                },
            },
            'responsive': true,
            'language': {
                'paginate': {
                    'previous': '<i class="demo-psi-arrow-left"></i>',
                    'next': '<i class="demo-psi-arrow-right"></i>',
                },
            },
            columns: [
                {
                    data: 'name',
                    title: 'Nombre',
                },
                {
                    data: 'email',
                    title: 'Email',
                },
                {
                    data: null,
                    title: 'Emprendedor',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        if (row.hasOwnProperty('provider') && row.provider) {
                            return row.provider.name;
                        }
                        return '';
                    },
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
                        if (row.id === 1) {
                            return '';
                        }
                        return '<button class="btn btn-dark btn-sm" onclick="editUser(' +
                            row.id + ')">Editar</button>';
                    },
                },
            ],
        });
    modal_user = $('#modal');
});

function editUser(id) {
    modal_user.find('.modal-title').html('Editar Usuario');
    getForm($('#action_get_form').val() + '/' + id);
}

function newUser() {
    modal_user.find('.modal-title').html('Crear Usuario');
    getForm($('#action_get_form').val());
}

function saveUser() {
    if (form_user.valid()) {
        ajaxRequest($('#action_save').val(), {
            type: 'POST',
            data: form_user.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar el usuario',
            success_message: 'El usuario se guardo correctamente',
            success_callback: function (data) {
                modal_user.modal('hide');
                dataTable.ajax.reload();
            },
        });
    }
}

function getForm(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modal_user.find('.container_modal').html('');
            modal_user.find('.container_modal').html(data.html);
            form_user = $('#user_form');
            validateForm();
            $('#role').select2({
                dropdownParent: $('#modal'),
                width: '100%',
                placeholder: '-Seleccione-',
            });
            $('#provider_id').select2({
                dropdownParent: $('#modal'),
                width: '100%',
                placeholder: '-Seleccione-',
                allowClear: true
            });
            modal_user.modal({
                show: true,
                backdrop: 'static',
                keyboard: false, // to prevent closing with Esc button (if you want this too)
            });
        },
    });
}

function validateForm() {
    form_user.validate({
        rules: {
            name: {
                required: true,
                maxlength: 64
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: $('#action_unique_email').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#user_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $('#email').val().trim();
                        },
                    },
                },
            },
            password: {
                required: true,
            },
            'role[]': {
                required: true,
            },
            confirm_password: {
                required: true,
                equalTo: '#password',
            },
        },
        messages: {
            email: {
                remote: 'Ya existe una usuario con ese email.',
            },
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveUser();
        },
    });
}