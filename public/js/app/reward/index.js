var modal_model = null;
var form_model = null;
var dataTable = null;
var imagesDeleted = [];
var currentEvent = -1;

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
                    data: "name",
                    title: 'Nombre'
                },
                {
                    data: "status",
                    title: 'Estado',
                    render: function (data, type, row, meta) {
                        if (row.status === 'ACTIVE') {
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
                        return '<button class="btn btn-dark btn-sm" onclick="editModel(' + row.id + ')">Editar</button>'+
                            ' <button class="btn btn-success btn-sm" onclick="exportExcelByReward(' + row.id + ')">Exportar cupones</button>';
                    }
                }
            ]
        });
    modal_model = $('#modal');
});

function editModel(id) {
    currentEvent = id;
    modal_model.find('.modal-title').html('Editar Recompensa');
    getFormModel($('#action_get_form').val() + '/' + id);
}

function newModel() {
    modal_model.find('.modal-title').html('Crear Recompensa');
    getFormModel($('#action_get_form').val());
}

function saveModel() {
    if (form_model.valid()) {
        if (!validateImages('.wrapper_image')) {
            return false;
        }
        var data = form_model.serializeArray();
        var files = getFiles();
        var dataForm = new FormData();

        for (var i = 0; i < data.length; i++) {
            dataForm.append(data[i].name, data[i].value);
        }
        $.each(imagesDeleted, function (index, idImage) {
            dataForm.append('filesDeleted[' + index + ']', idImage);
        });
        $.each(files, function (index, file) {
            dataForm.append('files[' + index + ']', file);
            dataForm.append('filesParams[' + index + ']',
                JSON.stringify(file.params));
        });

        ajaxRequest($('#action_save_model').val(), {
            type: 'POST',
            data: dataForm,
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar la aplicación',
            success_message: 'El aplicación se guardo correctamente',
            success_callback: function (data) {
                modal_model.modal('hide');
                dataTable.ajax.reload();
            }
        }, true);
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
            initDatePicker('range_date', 'start_date', 'end_date', 'modal');

            $('#description,#condition').summernote({
                height: 150,   //set editable area's height
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
                maxlength: 64,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#model_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        title: function () {
                            return $("#name").val().trim();
                        },
                    }
                }
            },
            basic_code: {
                required: true,
                maxlength: 64,
                remote: {
                    url: $('#action_unique_code').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#model_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        title: function () {
                            return $('#basic_code').val().trim();
                        },
                    }
                }
            },
            sponsor_id: {
                required: true
            },
            range_date: {
                required: true
            },
            quantity: {
                required: true
            },
            exchange_point: {
                required: true
            }
        },
        messages: {
            name: {
                remote: 'Ya existe una recompensa con ese nombre.'
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


function initDatePicker(idInputRange, idInputStart, idInputEnd, parentModal) {
    var beginDate = new Date($('#' + idInputStart).val());
    var endDate = new Date($('#' + idInputEnd).val());
    if (!isValidDate(beginDate)) {
        beginDate = new Date();
        endDate = new Date();
    }
    $('#' + idInputRange).daterangepicker({
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
        drops: 'auto',
        parentEl: '#' + parentModal
    }, function (start, end, label) {
        $('#' + idInputStart).val(start.format('YYYY-MM-DD HH:mm'));
        $('#' + idInputEnd).val(end.format('YYYY-MM-DD HH:mm'));
        $('#' + idInputRange).val(start.format('DD/MM/YYYY HH:mm') + ' - ' + end.format('DD/MM/YYYY HH:mm'));
    });
}

function isValidDate(d) {
    return d instanceof Date && !isNaN(d);
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
    // $("#inputEventId").val($('#select_event').val());
    $('#form_export').submit();
}

function exportExcelByReward(rewardId) {
    showAlert('info', 'Se está exportando...');
    $('#form_export_by_reward').attr('action', $('#action_export_by_reward').val());
    $("#rewardId").val(rewardId);
    $('#form_export_by_reward').submit();
}