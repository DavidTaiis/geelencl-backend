var dataTable = null;
const form_parameter = null
let modalTest = null
let formTest = null
$(function () {
    dataTable = initDataTableAjax($('#companyProviders_table'),
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
                    data: 'legal_name',
                    title: 'Proveedor',
                },
           
                {
                    data: 'statusInformation',
                    title: 'Estado',
                    render: function (data, type, row, meta) {
                        if (row.statusInformation === null) {
                            return '<span class="label label-primary label-inline font-weight-lighter">Creado</span>';
                        } else {
                            return '<span class="label label-success label-pill label-inline">' + row.statusInformation +'  </span>';
                        }
                    },
                },
                {
                    data: 'qualification',
                    title: 'Calificación',
                },

                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        if(row.statusInformation == "Finalizado"){
                            return '<a href="'+ $('#action_index_provider').val() +'/'+ row.id+'" target=""><span class="btn btn btn-outline-dark btn-sm">Ver información</span></a> <a href="'+ $('#action_generate_certificade').val() +'/'+ row.id+'" target=""><span class="btn btn btn-dark btn-sm">Generar certificado</span></a>';

                        }else{
                            return '<a href="'+ $('#action_index_provider').val() +'/'+ row.id+'" target=""><span class="btn btn btn-outline-dark btn-sm">Ver información</span></a> <a href="'+ $('#action_generate_certificade').val() +'/'+ row.id+'" target=""><span class="btn btn btn-dark btn-sm">Generar certificado</span></a> <button class="btn btn-dark btn-sm" onclick="newTest(' + row.id + ')">Finalizar Evaluación</button>';

                        }
                       
                    },
                },
            ],
        });
        modalTest = $('#test_modal');
});
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