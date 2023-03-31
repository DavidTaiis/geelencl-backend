var modal_model = null;
var modal_model_goal = null;
var modal_list_goal = null;
var form_model = null;
var form_model_goal = null;
var dataTable = null;
var dataTableGoal = null;
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
                    data: "title",
                    title: 'Nombre'
                },
                {
                    data: "type",
                    title: 'Tipo',
                    render: function (data, type, row, meta) {
                        if (row.type === 'RACE') {
                            return 'Carrera';
                        } else {
                            return 'Reto';
                        }
                    }
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
                    data: "start_date",
                    title: 'Fecha de inicio',
                    render: function (data, type, row, meta) {
                        return moment(row.start_date).format('YYYY/MM/DD HH:mm:ss');
                    }
                },
                {
                    data: "end_date",
                    title: 'Fecha de finlaización',
                    render: function (data, type, row, meta) {
                        return moment(row.end_date).format('YYYY/MM/DD HH:mm:ss');
                    }
                },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="viewGoals(' + row.id + ')">Configurar objetivos</button>' +
                            ' <button class="btn btn-dark btn-sm" onclick="editModel(' + row.id + ',\'' + row.type + '\')">Editar</button>'+
                            ' <button class="btn btn-success btn-sm" onclick="exportRanking(' + row.id + ')">Exportar RANKING</button>';
                    }
                }
            ]
        });
    dataTableGoal = initDataTableAjax($('#table_goals'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_get_list_goal').val(),
                data: function (filterDateTable) {
                    filterDateTable.event_id = currentEvent;
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
                        return '<button class="btn btn-dark btn-sm" onclick="editModelGoal(' + row.id + ',' + row.event_id + ')">Editar</button>';
                    }
                }
            ]
        });
    modal_model = $('#modal');
    modal_model_goal = $('#modal_goal');
    modal_list_goal = $('#modal_list_goal');
});

function editModel(id, type) {
    currentEvent = id;
    modal_model.find('.modal-title').html('Editar Evento');
    getFormModel($('#action_get_form').val() + '/' + id + '?type=' + type);
}

function editModelGoal(idGoal, idEvent) {
    modal_model_goal.find('.modal-title').html('Editar Objetivo');
    modal_list_goal.modal('hide');
    getFormModelGoal($('#action_get_form_goal').val() + '/' + idEvent + '/' + idGoal);
}

function addGoal() {
    modal_list_goal.modal('hide');
    modal_model_goal.find('.modal-title').html('Agregar objetivo');
    getFormModelGoal($('#action_get_form_goal').val() + '/' + currentEvent);
}

function viewGoals(id) {
    currentEvent = id;
    dataTableGoal.ajax.reload();
    modal_list_goal.find('.modal-title').html('Ver objetivos');
    modal_list_goal.modal({
        show: true,
        backdrop: 'static',
        keyboard: false // to prevent closing with Esc button (if you want this too)
    });
}

function newModel(type) {
    modal_model.find('.modal-title').html('Crear Evento');
    getFormModel($('#action_get_form').val() + '?type=' + type);
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

function saveModelGoal() {
    if (form_model_goal.valid()) {
        ajaxRequest($('#action_save_model_goal').val(), {
            type: 'POST',
            data: form_model_goal.serialize(),
            blockElement: '#modal_goal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar el objetivo',
            success_message: 'El aplicación se guardo correctamente',
            success_callback: function (data) {
                modal_model_goal.modal('hide');
                viewGoals(currentEvent);
                // dataTable.ajax.reload();
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
            initDatePicker('range_date', 'start_date', 'end_date', 'modal');
            $('#select_product').select2({
                dropdownParent: $('#model_form'),
                width: '100%',
                placeholder: 'Seleccione productos',
                multiple: true,
            });
            $('#description,#rule,#award,#terms').summernote({
                fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana', 'Muli, sans-serif'],
                fontNamesIgnoreCheck: ['Muli, sans-serif'],
                height: 200,   //set editable area's height
            });

            $('#description,#rule,#award,#terms').summernote('fontName', 'Muli, sans-serif');
            initDropZones();
        }
    });
}

function getFormModelGoal(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modal_model_goal.find('.container_modal').html('');
            modal_model_goal.find('.container_modal').html(data.html);
            form_model_goal = $("#model_form_goal");
            validateFormModelGoal();
            modal_model_goal.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
            $('#description_goal').summernote({
                height: 150,   //set editable area's height
            });
            $('#select_type').select2({
                dropdownParent: $('#modal_goal'),
                width: '100%',
                placeholder: '   Seleccione actividades',
                multiple: true,
            });
            setLogicRequiredRule('#time_rule', '#time_rule_value', '#logic_time');
            setLogicRequiredRule('#distance_rule', '#distance_rule_value', '#logic_distance');
            setLogicRequiredRule('#elevation_gain_rule', '#elevation_gain_rule_value', null);

            $('#time_rule').on('change', function (e) {
                setLogicRequiredRule('#time_rule', '#time_rule_value', '#logic_time');
            });

            $('#distance_rule').on('change', function (e) {
                setLogicRequiredRule('#distance_rule', '#distance_rule_value', '#logic_distance');
            });
            $('#elevation_gain_rule').on('change', function (e) {
                setLogicRequiredRule('#elevation_gain_rule', '#elevation_gain_rule_value', null);
            });
        }
    });
}

function validateFormModel() {
    form_model.validate({
        rules: {
            title: {
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
                        title: function () {
                            return $("#title").val().trim();
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
        },
        messages: {
            name: {
                remote: 'Ya existe un evento con ese título.'
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

function validateFormModelGoal() {
    form_model_goal.validate({
        rules: {
            name: {
                required: true,
                maxlength: 45
            },
            rule_goal_id: {
                required: true
            },
            activity_type_id: {
                required: true
            },
        },
        messages: {
            name: {
                remote: 'Ya existe un evento con ese título.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveModelGoal();
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
        drops: 'up',
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

function removeQuestion(idWrapperQuestion) {
    $(idWrapperQuestion).remove();
}

function setLogicRequiredRule(inputRuleCondition, inputRuleValue, inputRuleLogic) {
    var condition = $(inputRuleCondition).val();
    if (condition === 'YES') {
        $(inputRuleValue).rules("add", "required");
        if (inputRuleLogic) {
            $(inputRuleLogic).rules("add", "required");
        }
    } else {
        $(inputRuleValue).rules("remove", "required");
        if (inputRuleLogic) {
            $(inputRuleLogic).rules("remove", "required");
        }
    }
}


function exportRanking(eventId) {
    console.log(eventId);
    showAlert('info', 'Se está exportando...');
    $('#form_export_ranking').attr('action', $('#action_export_by_ranking').val());
    $("#eventId").val(eventId);
    $('#form_export_ranking').submit();
}
