var modalCompany = null;
var formCompany = null;
var dataTable = null;
var imagesDeleted = [];
$(function () {
    dataTable = initDataTableAjax($('#company_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_company').val(),
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
                    data: "comercial_name",
                    title: 'Nombre Comercial'
                },
                {
                    data: "legal_name",
                    title: 'Nombre Legal'
                },
                {
                    data: "direction",
                    title: 'Dirección'
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
                    width: "140px",
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="editCompany(' + row.id + ')">Editar</button>';
                    }
                    
                }
            ]
        });
        
    modalCompany = $('#company_modal');
});

function editCompany(id) {
    modalCompany.find('.modal-title').html('Editar empresa');
    getformCompany($('#action_get_form').val() + '/' + id);
}

function newCompany() {
    modalCompany.find('.modal-title').html('Crear empresa');
    getformCompany($('#action_get_form').val());
}

function saveCompany() {
    if ($('#company_form').valid()) {
        var data = $('#company_form').serializeArray();
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
            $('#action_save_company').val(),
            {
                type: 'POST',
                data: dataForm,
                blockElement: '#company_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar la empresa',
                success_message: 'Se guardó correctamente',
                success_callback: function (data) {
                    $('#company_modal').modal('hide');
                    dataTable.ajax.reload();
                }
            }, true);
    }
}
function getformCompany(url) {
    
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalCompany.find('.container_modal').html('');
            modalCompany.find('.container_modal').html(data.html);
            formCompany = $("#company_form");
            validateformCompany();
            imagesDeleted = [];
            initDropZones();
            modalCompany.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateformCompany() {
    formCompany.validate({
        rules: {
            name: {
                required: true,
                maxlength: 256,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#company_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: function () {
                            return $("#name").val().trim();
                        },
                       
                    }
                }  
            },
            code: {
                required: true,
                maxlength: 256,
                remote: {
                    url: $('#action_unique_code').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#company_id').val();
                        },
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        code: function () {
                            return $("#code").val().trim();
                        },
                       
                    }
                }    
            },
            status :{
                required: true 
            }
        },
        messages: {
            name: {
                remote: 'Ya existe una empresa con ese nombre.'
            },
            code: {
                remote: 'Ya existe un código con ese nombre'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveCompany();
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

