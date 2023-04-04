var modal_answers = null;
var answers_form = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#answers_table'),
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
                    data: 'answer',
                    title: 'Pregunta',
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
                       
                        return '<button class="btn btn-dark btn-sm" onclick="editAnswers(' +
                            row.id + ')">Editar</button>';
                    },
                },
            ],
        });
        modal_answers = $('#modal');
});

function editAnswers(id) {
    modal_answers.find('.modal-title').html('Editar respuesta');
    getForm($('#action_get_form').val() + '/' + id);
}

function newAnswers() {
    modal_answers.find('.modal-title').html('Crear respuesta');
    getForm($('#action_get_form').val());
}

function saveAnswers() {
    if (answers_form.valid()) {
        ajaxRequest($('#action_save').val(), {
            type: 'POST',
            data: answers_form.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar la respuesta',
            success_message: 'La respuesta se guardo correctamente',
            success_callback: function (data) {
                modal_answers.modal('hide');
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
            modal_answers.find('.container_modal').html('');
            modal_answers.find('.container_modal').html(data.html);
            answers_form = $('#answers_form');    
            validateForm();      
            modal_answers.modal({
                show: true,
                backdrop: 'static',
                keyboard: false, // to prevent closing with Esc button (if you want this too)
            });
        },
    });
}

function validateForm() {
    answers_form.validate({
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
            saveAnswers();
        },
    });
}