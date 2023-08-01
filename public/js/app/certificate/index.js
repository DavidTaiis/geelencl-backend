var modal_certificate = null;
var certificate_form = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#certificate_table'),
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
                    data: 'nombres',
                    title: 'Nombres',
                },
                {
                    data: 'cargo',
                    title: 'Cargo',
                },
       
                {
                    data: 'status',
                    title: 'Estado',
                    render: function (data, type, row, meta) {
                        if (row.status === 'ACTIVE') {
                            return '<span class="label label-primary label-inline font-weight-lighter">Activo</span>';
                        } else {
                            return '<span class="label label-danger label-pill label-inline">Inactivo</span>';
                        }
                    },
                },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                       
                        return '<button class="btn btn-dark btn-sm mr-2" onclick="editCertificate(' +
                            row.id + ')">Editar</button>';
                    },
                },
            ],
        });
        modal_certificate = $('#modal');
});

function editCertificate(id) {
    modal_certificate.find('.modal-title').html('Editar datos certificado');
    getForm($('#action_get_form').val() + '/' + id);
}

function newCertificate() {
    modal_certificate.find('.modal-title').html('Crear datos certificado');
    getForm($('#action_get_form').val());
}

function saveCertificate() {
    if (certificate_form.valid()) {
        var data = certificate_form.serializeArray();
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
            $('#action_save').val(),
            {
                type: 'POST',
                data: dataForm, // datos del formulario .serialize()
                blockElement: '#restaurant_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar el negocio',
                success_message: 'El negocio se guardó correctamente',
                success_callback: function (data) {
                    modal_certificate.modal('hide'); // ocultar modal
                    dataTable.ajax.reload();
                }
            } , true);
    }
}

function getForm(action) {
    
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modal_certificate.find('.container_modal').html('');
            modal_certificate.find('.container_modal').html(data.html);
            certificate_form = $('#certificate_form');    
            validateForm();      
            imagesDeleted = [];
            initDropZones();
           /*  initDropZoneFile(); */
            modal_certificate.modal({
                show: true,
                backdrop: 'static',
                keyboard: false, // to prevent closing with Esc button (if you want this too)
            });
        },
    });
}

function validateForm() {
    certificate_form.validate({
        rules: {
            nombres: {
                required: true,
                maxlength: 255
            },
            cargo: {
                required: true,
                maxlength: 255
            },
            
        },
        messages: {
            
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveCertificate();
        },
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