var modal_model = null;
var form_model = null;
var dataTable = null;
var imagesDeleted = [];

var translate_payment_status = {
    'PENDING': 'Pendiente Pago',
    'PAID': 'Pagado'
};
var translate_status = {
    'IN_PROGRESS': 'Por gestionar',
    'COMPLETE': 'Gestionado',
};

$(function () {
    dataTable = initDataTableAjax($('#model_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_models').val(),
                data: function (filterDateTable) {
                    //additional params for ajax request
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
                    data: "id",
                    title: '# Pedido',
                    render: function (data, type, row, meta) {
                        return row.id;
                    }
                },
                {
                    data: "providerName",
                    title: 'Emprendedor',
                    render: function (data, type, row, meta) {
                        return row.providerName;
                    }
                },
                {
                    data: "status",
                    title: 'Estado',
                    render: function (data, type, row, meta) {
                        return translate_status[row.status];
                    }
                },
                {
                    data: 'customerName',
                    title: 'Cliente',
                    render: function (data, type, row, meta) {
                        if (row.customerLastName) {

                            return row.customerName + " " + row.customerLastName;
                        } else {
                            return row.customerName;
                        }
                    }
                },
                {
                    data: "customerEmail",
                    title: 'Email cliente',
                    render: function (data, type, row, meta) {
                        return row.customerEmail;
                    }
                },
                {
                    data: "created_at",
                    title: 'Fecha pedido',
                    render: function (data, type, row, meta) {
                        return moment(row.created_at).format('YYYY/MM/DD HH:mm:ss');
                    }
                },
                // {
                //     data: "status",
                //     title: 'Estado',
                //     // render: function (data, type, row, meta) {
                //     //     if (row.status === 'ACTIVE') {
                //     //         return '<span class="label label-sm label-success">Activo</span>';
                //     //     } else {
                //     //         return '<span class="label label-sm label-warning">Inactivo</span>';
                //     //     }
                //     // }
                // },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return ' <button class="btn btn-dark btn-sm" onclick="editModel(' + row.id + ')">Ver</button>';
                    }
                }
            ]
        });
    modal_model = $('#modal');

});

function editModel(id) {
    modal_model.find('.modal-title').html('Datos del pedido');
    getFormModel($('#action_get_form').val() + '/' + id);
}

function newModel() {
    modal_model.find('.modal-title').html('Crear Empresa');
    getFormModel($('#action_get_form').val());
}

function saveModel() {
    if (form_model.valid()) {
        ajaxRequest($('#action_save_model').val(), {
            type: 'POST',
            data: form_model.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar los cambios',
            success_message: 'El cambio se guardó correctamente',
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

        },
        messages: {

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

function exportExcel() {
    showAlert('info', 'Se está exportando...');
    $('#form_export').attr('action', $('#action_export').val());
    $("#inputEventId").val($('#select_event').val());
    $('#form_export').submit();
}