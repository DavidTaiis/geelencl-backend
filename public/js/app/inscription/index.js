var modal_model = null;
var form_model = null;
var dataTable = null;
var imagesDeleted = [];

var translate_payment_status = {
    'PENDING': 'Pendiente Pago',
    'PAID': 'Pagado'
};
var translate_status = {
    'PENDING_APPROVAL': 'Pendiente de aprobación',
    'IN_PROGRESS': 'En progreso',
    'COMPLETE': 'Completado',
    'INCOMPLETE': 'Incompletado',
    'INACTIVE': 'Inactivo'
};

$(function () {
    dataTable = initDataTableAjax($('#model_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_models').val(),
                data: function (filterDateTable) {
                    filterDateTable.eventId = $('#select_event').val();
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
                    data: "inscription.id",
                    title: 'Código Inscripción',
                    render: function (data, type, row, meta) {
                        return row.id;
                    }
                },
                {
                    data: "event.title",
                    title: 'Evento',
                    render: function (data, type, row, meta) {
                        return row.title;
                    }
                },
                {
                    data: "inscription.status",
                    title: 'Estado de la inscripción',
                    render: function (data, type, row, meta) {
                        return translate_status[row.status];
                    }
                },
                {
                    data: "customer.name",
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
                    data: "customer.email",
                    title: 'Email cliente',
                    render: function (data, type, row, meta) {
                        return row.customerEmail;
                    }
                },
                {
                    data: "inscription.created_at",
                    title: 'Fecha inscripción',
                    render: function (data, type, row, meta) {
                        return moment(row.created_at).format('YYYY/MM/DD HH:mm:ss');
                    }
                },
                {
                    data: "inscription.payment_status",
                    title: 'Estado del pago',
                    render: function (data, type, row, meta) {
                        return translate_payment_status[row.payment_status];
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
    var selectEvent = $('#select_event');
    selectEvent.select2({
        dropdownAutoWidth: true,
        width: 'auto',
        placeholder: 'Seleccione evento',
        allowClear: true
    });
    selectEvent.on('change', function (e) {
        console.log($(this).val());
        dataTable.ajax.reload();
    });
});

function editModel(id) {
    modal_model.find('.modal-title').html('Datos de la inscripción');
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
            initDropZones();
        }
    });
}


function validateFormModel() {
    form_model.validate({
        rules: {
            name: {
                required: true,
                maxlength: 45,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#model_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $("#name").val().trim();
                        },
                    }
                }
            },
        },
        messages: {
            name: {
                remote: 'Ya existe una empresa con ese nombre.'
            }
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

function initDropZones() {
    Dropzone.autoDiscover = false;
    $('.wrapper_image').each(function (i) {
        var config = $(this).data();
        initDropZone($(this), config);
    });
}

function getFiles() {
    var files = [];
    $.each($('.wrapper_image'), function (i) {
        var myDropZone = Dropzone.forElement(this);
        files = files.concat(myDropZone.files);
    });
    return files;
}

function deleteImage(id) {
    imagesDeleted.push(id);
    $('#' + id + '_wrapper_image').remove();
}

function exportExcel() {
    showAlert('info', 'Se está exportando...');
    $('#form_export').attr('action', $('#action_export').val());
    $("#inputEventId").val($('#select_event').val());
    $('#form_export').submit();
}