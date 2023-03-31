var modalCategory = null;
var formCategory = null;
var dataTable = null;
var imagesDeleted = [];
$(function () {
    dataTable = initDataTableAjax(
        $('#category_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_categorys').val(),
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
                        return '<button class="btn btn-dark btn-sm" onclick="editCategory(' +
                        row.id + ')">Editar</button>';
                    }
                }
            ]
        });

    modalCategory = $('#category_modal'); // busca un elemento con id modal
});

function editCategory(id) {
    modalCategory.find('.modal-title').html('Editar categoría');
    getformCategory($('#action_get_form').val() + '/' + id);
}

function newCategory() {
    modalCategory.find('.modal-title').html('Crear categoría');
    getformCategory($('#action_get_form').val());
}

function saveCategory() {
    if (formCategory.valid()) {
        var data = formCategory.serializeArray();
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
        
        ajaxRequest(
            $('#action_save_category').val(),
            {
                type: 'POST',
                data: dataForm, // datos del formulario .serialize()
                blockElement: '#category_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar la categoría',
                success_message: 'La categoría se guardó correctamente',
                success_callback: function (data) {
                    modalCategory.modal('hide'); // ocultar modal
                    dataTable.ajax.reload();
                }
            }, true ); // cuando ese enviando archivos 
    }
}
function getformCategory(url) {
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalCategory.find('.container_modal').html('');
            modalCategory.find('.container_modal').html(data.html);
            formCategory = $("#category_form");
            validateformCategory();
            $('#categories').select2({
                dropdownParent: $('#category_form'),
                width: '100%',
                placeholder: '-Seleccione-',
            });
            modalCategory.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
            initDropZones();
        }
    });
}

function validateformCategory() {
    formCategory.validate({
        rules: {
            name: {
                required: true,
                maxlength: 45,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#category_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $("#name").val().trim();
                        },
                       
                    }
                }
                
            }
        },
        messages: {
            name: {
                remote: 'Ya existe una categoría con ese nombre.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveCategory();
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
