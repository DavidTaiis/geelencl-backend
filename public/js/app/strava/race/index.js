var modal_model = null;
var form_model = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#model_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_models').val(),
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
                    data: "type",
                    title: 'Tipo'
                },
                {
                    data: "club_name",
                    title: 'Club'
                },
                {
                    data: "status",
                    title: 'Estado',
                    render: function (data, type, row, meta) {
                        if (row.active === 1) {
                            return '<span class="label label-sm label-success">Activo</span>';
                        } else {
                            return '<span class="label label-sm label-warning">Inactivo</span>';
                        }
                    }
                },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="editModel(' + row.id + ')">Editar</button>' +
                            ' <a class="btn btn-info btn-sm" href="' + $('#action_view').val() + '/' + row.id + '" >Ver actividades</a>';
                    }
                }
            ]
        });
    modal_model = $('#modal');
    // initDatePicker();
});

function editModel(id) {
    modal_model.find('.modal-title').html('Editar carrera');
    getFormModel($('#action_get_form').val() + '/' + id);
}

function newModel() {
    modal_model.find('.modal-title').html('Crear carrera');
    getFormModel($('#action_get_form').val());
}

function initDatePicker() {
    var beginDate = new Date($('#begin_date').val());
    var endDate = new Date($('#end_date').val());
    var flagChange = false;
    if (!isValidDate(beginDate)) {
        beginDate = new Date();
        endDate = new Date();
        flagChange = true;
    }
    console.log(beginDate, endDate);
    // $('#range_date').val()
    $('#range_date').daterangepicker({
        autoUpdateInput: false,
        "timePicker": true,
        "locale": {
            "format": "DD-MM-YYYY HH:mm",
            "separator": " - ",
            "applyLabel": "Applicar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Custom",
            "weekLabel": "W",
            "daysOfWeek": [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        },
        "startDate": beginDate,
        "endDate": endDate,
        opens: 'center',
        drops: 'up',
        parentEl: '#modal'
    }, function (start, end, label) {
        $('#begin_date').val(start.format('YYYY-MM-DD HH:mm'));
        $('#end_date').val(end.format('YYYY-MM-DD HH:mm'));

        $('#range_date').val(start.format('DD/MM/YYYY HH:mm')+' - '+end.format('DD/MM/YYYY HH:mm'));
        console.log("entro");
    });

    if(flagChange){

    }


}

function isValidDate(d) {
    return d instanceof Date && !isNaN(d);
}

function saveModel() {
    if (form_model.valid()) {
        ajaxRequest($('#action_save_model').val(), {
            type: 'POST',
            data: form_model.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar la aplicación',
            success_message: 'El aplicación se guardo correctamente',
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
            initDatePicker();
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
            name: {
                required: true,
                maxlength: 64,
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
            club_id: {
                required: true
            },
            type: {
                required: true
            },
            range_date: {
                required: true
            }

        },
        messages: {
            name: {
                remote: 'Ya existe una aplicación con ese nombre.'
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