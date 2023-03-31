var modal_model = null;
var form_model = null;
var dataTable = null;
var imagesDeleted = [];

$(function () {
    $.fn.modal.Constructor.prototype.enforceFocus = function() {};
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
                        return ' <button class="btn btn-dark btn-sm" onclick="editModel(' + row.id + ')">Editar</button>';
                    }
                }
            ]
        });
    modal_model = $('#modal');
});

function editModel(id) {
    modal_model.find('.modal-title').html('Editar Formulario');
    getFormModel($('#action_get_form').val() + '/' + id);
}

function newModel() {
    modal_model.find('.modal-title').html('Crear Formulario');
    getFormModel($('#action_get_form').val());
}

function saveModel() {
    if (form_model.valid()) {
        ajaxRequest($('#action_save_model').val(), {
            type: 'POST',
            data: form_model.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar el formulario',
            success_message: 'El formulario se guardo correctamente',
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
            $('.select_values').select2({
                dropdownParent: $('#model_form'),
                width: '100%',
                placeholder: '   Agregue valores',
                tags: true,
                tokenSeparators: [',']
            });
            initDropZones();
            $('#btn_add_question').on('click', function (e) {
                e.preventDefault();
                var html = $('#wrapper_inputs').html();
                var randomId = Math.floor((Math.random() * 10000) + 1000);
                html = html.split('wrapper_question_temp').join('wrapper_question_' + randomId);
                html = html.split('label_temp').join('label[' + randomId + ']');
                html = html.split('hash_temp').join('hash[' + randomId + ']');
                html = html.split('hash_id_temp').join('hash_' + randomId);
                html = html.split('type_temp').join('type[' + randomId + ']');
                html = html.split('rule_temp').join('rule[' + randomId + ']');
                html = html.split('value_temp').join('value[' + randomId + ']');
                html = html.split('value_name_temp').join('value[' + randomId + '][]');
                html = html.split('value_id_temp').join('value_' + randomId);

                $('#wrapper_questions').append(html);

                setTimeout(function () {
                    $('#hash_' + randomId).val(guid());
                    $('#value_' + randomId).select2({
                        dropdownParent: $('#model_form'),
                        width: '100%',
                        placeholder: '   Agregue valores',
                        tags: true,
                        tokenSeparators: [',', ' ']
                    });
                }, 1000);
            });
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

function removeQuestion(idWrapperQuestion) {
    $(idWrapperQuestion).remove();
}

function guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return 'question_' + s4() + s4() + s4() + s4();
}