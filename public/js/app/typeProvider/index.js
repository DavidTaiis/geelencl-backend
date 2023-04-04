var modal_typeProvider = null;
var typeProvider_form = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#typeProvider_table'),
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
                       
                        return '<button class="btn btn-dark btn-sm" onclick="editTypeProvider(' +
                            row.id + ')">Editar</button>';
                    },
                },
            ],
        });
        modal_typeProvider = $('#modal');
});

function editTypeProvider(id) {
    modal_typeProvider.find('.modal-title').html('Editar tipo de proveedor');
    getForm($('#action_get_form').val() + '/' + id);
}

function newTypeProvider() {
    modal_typeProvider.find('.modal-title').html('Crear tipo de proveedor');
    getForm($('#action_get_form').val());
}

function saveTypeProvider() {
    if (typeProvider_form.valid()) {
        ajaxRequest($('#action_save').val(), {
            type: 'POST',
            data: typeProvider_form.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar el tipo de proveedor',
            success_message: 'El tipo de proveedor se guardo correctamente',
            success_callback: function (data) {
                modal_typeProvider.modal('hide');
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
            modal_typeProvider.find('.container_modal').html('');
            modal_typeProvider.find('.container_modal').html(data.html);
            typeProvider_form = $('#typeProvider_form');    
            validateForm();      
            modal_typeProvider.modal({
                show: true,
                backdrop: 'static',
                keyboard: false, // to prevent closing with Esc button (if you want this too)
            });
        },
    });
}

function validateForm() {
    typeProvider_form.validate({
        rules: {
            name: {
                required: true,
                maxlength: 64
            },
            
        },
        messages: {
            
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveTypeProvider();
        },
    });
}