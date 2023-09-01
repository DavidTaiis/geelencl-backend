var modal_producto = null;
var producto_form = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#producto_table'),
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
                    data: 'nombre',
                    title: 'Producto / Servicio',
                },
                {
                    data:'costo',
                    title:'Costo'
                },
                /* {
                    data: 'status',
                    title: 'Estado',
                    render: function (data, type, row, meta) {
                        if (row.status === 'ACTIVE') {
                            return '<span class="label label-primary label-inline font-weight-lighter">Activo</span>';
                        } else {
                            return '<span class="label label-danger label-pill label-inline">Inactivo</span>';
                        }
                    },
                }, */
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                       
                        return '<button class="btn btn-dark btn-sm" onclick="editProducto(' +
                            row.id + ')">Editar</button>';
                    },
                },
            ],
        });
        modal_producto = $('#modal');
});

function editProducto(id) {
    modal_producto.find('.modal-title').html('Editar respuesta');
    getForm($('#action_get_form').val() + '/' + id);
}

function newProducto() {
    modal_producto.find('.modal-title').html('Crear respuesta');
    getForm($('#action_get_form').val());
}

function saveProduct() {
    if (producto_form.valid()) {
        ajaxRequest($('#action_save').val(), {
            type: 'POST',
            data: producto_form.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar la respuesta',
            success_message: 'El producto se guardo correctamente',
            success_callback: function (data) {
                modal_producto.modal('hide');
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
            modal_producto.find('.container_modal').html('');
            modal_producto.find('.container_modal').html(data.html);
            producto_form = $('#producto_form');    
            validateForm();      
            modal_producto.modal({
                show: true,
                backdrop: 'static',
                keyboard: false, // to prevent closing with Esc button (if you want this too)
            });
        },
    });
}

function validateForm() {
    producto_form.validate({
        rules: {
            name: {
                required: true,
                maxlength: 64
            },
            
        },
        messages: {
            puntaje: "Ingrese un valor entero, no se aceptan decimales."
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveProduct();
        },
    });
}