var modal_manual = null;
var manual_form = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#manual_table'),
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
                    data: 'name',
                    title: 'Manual',
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
                       
                        return '<button class="btn btn-dark btn-sm mr-2" onclick="editManual(' +
                            row.id + ')">Editar</button><a target="_blank" href= " ' + row.directory + '">Ver documento</a>';
                    },
                },
            ],
        });
        modal_manual = $('#modal');
});

function editManual(id) {
    modal_manual.find('.modal-title').html('Editar manual');
    getForm($('#action_get_form').val() + '/' + id);
}

function newManual() {
    modal_manual.find('.modal-title').html('Crear manual');
    getForm($('#action_get_form').val());
}

function saveManual() {
    if (manual_form.valid()) {
        var data = $('#manual_form').serializeArray();
       
        var filesDocs = getFilesDocs()
        var dataForm = new FormData();
        
        for (var i = 0; i < data.length; i++) {
            dataForm.append(data[i].name, data[i].value);
        }
        $.each(filesDocs, function (index, file) {
            dataForm.append('filesDocs[' + index + ']', file);
        });

        ajaxRequest($('#action_save').val(), {
            type: 'POST',
            data: dataForm,
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar la manual',
            success_message: 'El manual se guardo correctamente',
            success_callback: function (data) {
                modal_manual.modal('hide');
                dataTable.ajax.reload();
            },
        }, true);
    }
}

function getForm(action) {
    
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modal_manual.find('.container_modal').html('');
            modal_manual.find('.container_modal').html(data.html);
            manual_form = $('#manual_form');    
            validateForm();      
            initDropZoneFile();
            modal_manual.modal({
                show: true,
                backdrop: 'static',
                keyboard: false, // to prevent closing with Esc button (if you want this too)
            });
        },
    });
}

function validateForm() {
    manual_form.validate({
        rules: {
            name: {
                required: true,
                maxlength: 64
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
            saveManual();
        },
    });
}
function initDropZoneFile() {
    Dropzone.autoDiscover = false;
    $("div#wrapper_file").dropzone({
        url: $('#action_get_form').val(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        uploadMultiple: false,
        autoProcessQueue: true,
        maxFiles: 1,
        acceptedFiles: '.pdf',
        maxfilesexceeded: 1,
        previewTemplate: document
            .querySelector('#preview_file')
            .innerHTML,
        init: function () {
            var myDropzone = this;
            myDropzone.accept = function (file, done) { 
                if (!Dropzone.isValidFile(file, this.options.acceptedFiles)) {
                    showAlert('warning', 'Solo se permiten archivos en formato: *' + this.options.acceptedFiles);
                    return this.removeFile(file);
                }     
            }
            this.on("addedfile", function() {
                if (this.files[1]!=null){
                  this.removeFile(this.files[0]);
                }
              });
        }   
    });
}
function getFilesDocs() {
    var filesDocs = [];
    $.each($('div#wrapper_file'), function (i) {
        var myDropZone = Dropzone.forElement(this);
        filesDocs = filesDocs.concat(myDropZone.files);
    });
    return filesDocs;
}