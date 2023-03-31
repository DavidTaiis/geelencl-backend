var modal_model = null;
var form_model = null;
var dataTable = null;
var imagesDeleted = [];
var unitsData = [];

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
                    data: "code",
                    title: 'SKU'
                },
                {
                    data: "price",
                    title: 'Precio($)'
                },

                {

                    data: 'provider.name',
                    title: 'Emprendedor',
                    orderable: false,
                },
                {
                    data: "status",
                    title: 'Estado',
                    render: function (data, type, row, meta) {
                        if (row.status === 'ACTIVE') {
                            return '<span class="label label-primary label-inline font-weight-lighter">Activo</span>';
                        } else {
                            return '<span class="label label-danger label-pill label-inline">Inactivo</span>';
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
    modal_model.find('.modal-title').html('Editar Producto');
    getFormModel($('#action_get_form').val() + '/' + id);
}

function newModel() {
    modal_model.find('.modal-title').html('Crear Producto');
    getFormModel($('#action_get_form').val());
}

function saveModel() {
    if (form_model.valid()) {
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
            error_message: 'Error al guardar el producto',
            success_message: 'El producto se guardo correctamente',
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
            unitsData = data.unitJson;
            modal_model.find('.container_modal').html('');
            modal_model.find('.container_modal').html(data.html);
            form_model = $("#model_form");
            validateFormModel();
            imagesDeleted = [];
            modal_model.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
            initDropZones();
            $('#provider_id').select2({
                dropdownParent: form_model,
                width: '100%',
                placeholder: '-Seleccione-',
            });
            $('#categories').select2({
                dropdownParent: form_model,
                width: '100%',
                placeholder: '-Seleccione-',
            });
            $('#select_units').select2({
                dropdownParent: form_model,
                width: '100%',
                allowClear: true,
                placeholder: '-Seleccione-',
            });
            $('#characteristics1').select2({
                dropdownParent: form_model,
                width: '100%',
                placeholder: '-Seleccione-',
                tags: true
            });
            $('#characteristics2').select2({
                dropdownParent: form_model,
                width: '100%',
                placeholder: '-Seleccione-',
                tags: true
            });
            $('#characteristics3').select2({
                dropdownParent: form_model,
                width: '100%',
                placeholder: '-Seleccione-',
                tags: true
            });
            $('#characteristics4').select2({
                dropdownParent: form_model,
                width: '100%',
                placeholder: '-Seleccione-',
                tags: true
            });
            $('#select_unit_values').select2({
                dropdownParent: form_model,
                width: '100%',
                placeholder: '-Seleccione-',
                allowClear: true
            });
            $('#select_units').on('change', function () {
                var unitId = $(this).val();
                if (unitId) {
                    var unitFound = unitsData.find(function (item) {
                        return item.unitId == unitId;
                    });
                    $('#select_unit_values').html('').select2({data: unitFound.values});
                } else {
                    $('#select_unit_values').html('');
                }
            });
        }
    });
}


function validateFormModel() {
    form_model.validate({
        rules: {

            code: {
                required: true,
                maxlength: 45,
                remote: {
                    url: $('#action_unique_code').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#model_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        code: function () {
                            return $("#code").val().trim();
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
