var modalprovider = null;
var formprovider = null;
var dataTable = null;
var imagesDeleted = [];
let modalTest = null
let formTest = null
$(function () {
    dataTable = initDataTableAjax($('#provider_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_provider').val(),
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
                    data: "legal_name",
                    title: 'Nombre del proveedor'
                },
    
                {
                    data: "statusInformation",
                    title: 'Estado'
                },
                {
                    data: "qualification",
                    title: 'Calificación'

                },
               /*  {
                    data: "status",
                    title: 'Estado',
                    render: function (data, type, row, meta) {
                        if (row.status === 'ACTIVE') {
                            return '<span class="label label-primary label-inline font-weight-lighter">Activo</span>';
                        } else {
                            return '<span class="label label-danger label-pill label-inline">Inactivo</span>';
                        }
                    }
                }, */
                {
                    data: null,
                    title: 'Configuraciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="editprovider(' + row.id + ')">Editar</button> <a href="'+ $('#action_load_sections').val() +'/'+ row.id+'" target=""><span class="btn btn btn-outline-dark btn-sm">Secciones</span></a>';
                    }
                    
                },
                {
                    data: null,
                    title: 'Acciones Evaluación',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        if(row.statusInformation == "Finalizado"){
                            return '<a href="'+ $('#action_index_provider').val() +'/'+ row.id+'" target=""><span class="btn btn btn-outline-dark btn-sm">Ver información</span></a> <a href="'+ $('#action_generate_certificade').val() +'/'+ row.id+'" target=""><span class="btn btn btn-dark btn-sm">Generar certificado</span></a>';

                        }else{
                            return '<a href="'+ $('#action_index_provider').val() +'/'+ row.id+'" target=""><span class="btn btn btn-outline-dark btn-sm">Ver información</span></a> <a href="'+ $('#action_generate_certificade').val() +'/'+ row.id+'" target=""><span class="btn btn btn-dark btn-sm">Generar certificado</span></a> <button class="btn btn-dark btn-sm" onclick="newTest(' + row.id + ')">Finalizar Evaluación</button>';

                        }
                       
                    },
                },
            ]
        });
        
    modalprovider = $('#provider_modal');
    modalTest = $('#test_modal');

});

function editprovider(id) {
    modalprovider.find('.modal-title').html('Editar proveedor');
    getformprovider($('#action_get_form').val() + '/' + id);
}

function newProvider() {
    modalprovider.find('.modal-title').html('Crear proveedor');
    getformprovider($('#action_get_form').val());
}

function saveprovider() {
    if ($('#provider_form').valid()) {
        var data = $('#provider_form').serializeArray();
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
            $('#action_save_provider').val(),
            {
                type: 'POST',
                data: dataForm,
                blockElement: '#provider_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar la proveedor',
                success_message: 'Se guardó correctamente',
                success_callback: function (data) {
                    $('#provider_modal').modal('hide');
                    dataTable.ajax.reload();
                }
            }, true);
    }
}
function getformprovider(url) {
    
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalprovider.find('.container_modal').html('');
            modalprovider.find('.container_modal').html(data.html);
            formprovider = $("#provider_form");
            validateformprovider();
            imagesDeleted = [];
            initDropZones();
            modalprovider.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateformprovider() {
    formprovider.validate({
        rules: {
            name: {
                required: true,
                maxlength: 256,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#provider_id').val();
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
                            return $('#provider_id').val();
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
            },
            company_id:{
                required:true
            }
        },
        messages: {
            name: {
                remote: 'Ya existe una proveedor con ese nombre.'
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
            saveprovider();
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

function newTest(id) {
    modalTest.find('.modal-title').html('Finalizar Evaluacion');
    getformCompany($('#action_form_test').val() + '/' + id);
}

function saveForm() {
    
    Swal.fire({
        title: "¿Estás seguro de enviar tu formulario?",
        text: "No se podrá realizar cambios una vez enviado el formulario ",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
        
    }).then(function(result) {
        if (result.value) {
            const data = $('#form_provider_qualification').serializeArray(); 
            const obj ={
                name: "action",
                value:"Enviar"
            }
            data.push(obj)
            ajaxRequest($('#action_save').val(), {
                type: 'POST',
                data: data,
                success_callback: function(data) {
                window.location.reload()
                },
              });
              Swal.fire({
                icon: "success",
                title: "Formulario enviado correctamente",
                showConfirmButton: false,
                timer: 1500
            })
            setTimeout(() => {
                window.location.reload()
            }, 3000);

        }
    });

}

function getformCompany(url) {
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalTest.find('.container_modal').html('');
            modalTest.find('.container_modal').html(data.html);
            formTest = $("#test_form");
            validateformTest();
            modalTest.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}
function validateformTest() {
    formTest.validate({
        rules: {
        
        },
        messages: {
        
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveTest();
        }
    });
}
function saveTest() {
    if ($('#test_form').valid()) {
        var data = $('#test_form').serializeArray();
        ajaxRequest(
            $('#action_save_test').val(),
            {
                type: 'POST',
                data: data,
                blockElement: '#test_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar el proceso de finalización',
                success_message: 'Se guardó correctamente',
                success_callback: function (data) {
                    $('#test_modal').modal('hide');
                    dataTable.ajax.reload();
                }
            });
    }
}