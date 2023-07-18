var modal_question = null;
var question_form = null;
var dataTable = null;
$(function () {
    
    dataTable = initDataTableAjax($('#question_table'),
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
                    data: 'order',
                    title: 'Nro',
                },
                {
                    data: 'question',
                    title: 'Pregunta',
                },
                {
                    data: 'type_question',
                    title: 'Tipo de pregunta',
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
                       
                        return '<button class="btn btn-dark btn-sm" onclick="editQuestion(' +
                            row.id + ')">Editar</button> <button class="btn btn-danger btn-sm" onclick="deletedQuestion(' + row.id + ')">Eliminar</button>';
                    },
                },
            ],
        });
        modal_question = $('#modal');
});

function editQuestion(id) {
    modal_question.find('.modal-title').html('Editar pregunta');
    getForm($('#action_get_form').val() + '/' + id);
}

function newQuestion() {
    modal_question.find('.modal-title').html('Crear pregunta');
    getForm($('#action_get_form').val());
}

function saveQuestion() {
    if (question_form.valid()) {
        ajaxRequest($('#action_save').val(), {
            type: 'POST',
            data: question_form.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar la pregunta',
            success_message: 'La pregunta se guardo correctamente',
            success_callback: function (data) {
                modal_question.modal('hide');
                dataTable.ajax.reload();
            },
        });
    }
}

function getForm(action) {
    ajaxRequest(action, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modal_question.find('.container_modal').html('');
            modal_question.find('.container_modal').html(data.html);
            question_form = $('#question_form');   
            validateForm();
            $('#answers_id').select2({
                dropdownParent: $('#question_form'),
                width: '100%',
                placeholder: '-Seleccione-',
            });
            if($('#type_question').val() == 'ABIERTA'){
                $('#respuestas').hide();
              
            }
            $('#type_providers_id').select2({
                dropdownParent: $('#question_form'),
                width: '100%',
                placeholder: '-Seleccione-',
            });
            

            modal_question.modal({
                show: true,
                backdrop: 'static',
                keyboard: false, // to prevent closing with Esc button (if you want this too)
            });
        },
    });
}

function validateForm() {
    question_form.validate({
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
            saveQuestion();
        },
    });
}
function loadTypeQuestion(){
    if($('#type_question').val() == 'ABIERTA'){
        $('#answers_id').prop( "disabled", true );
        $('#answers_id').empty();
        $('#respuestas').hide();
    }else{
        $('#answers_id').prop( "disabled", false );
        $('#respuestas').show();

    }
}
function initDropZones() {
    Dropzone.autoDiscover = false;
    $('.wrapper_image').each(function (i) {
        var config = $(this).data();
        initDropZone($(this), config);
    });
}

function deletedQuestion(id) {

    Swal.fire({
        title: "¿Estás seguro?",
        text: "Confirmación para eliminar pregunta",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",

    }).then(function (result) {
        if (result.value) {
            ajaxRequest($('#action_deleted_question').val() + '/' + id, {
                type: 'DELETE',
                success_callback: function (data) {
                    dataTable.ajax.reload();
                },
            });
            Swal.fire({
                icon: "success",
                title: "Eliminado correctamente",
                showConfirmButton: false,
                timer: 1500
            })

        }
    });

}