var modal_section = null;
var section_form = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax($('#section_table'),
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
                    title: 'Nombre',
                    orderable: true
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
                       
                        return '<button class="btn btn-dark btn-sm" onclick="editSection(' +
                            row.id + ')">Editar</button> <a href="'+ $('#action_index_question').val() +'/'+ row.id+'" target=""><span class="btn btn btn-outline-dark btn-sm">Añadir pregunta</span></a> <a href="'+ $('#action_index_answer').val() +'/'+ row.id+'" target=""><span class="btn btn btn-outline-dark btn-sm">Añadir respuesta</span></a>';
                    },
                },
            ],
        });
        modal_section = $('#modal');
});

function editSection(id) {
    modal_section.find('.modal-title').html('Editar sección');
    getForm($('#action_get_form').val() + '/' + id);
}

function newSection() {
    modal_section.find('.modal-title').html('Crear sección');
    getForm($('#action_get_form').val());
}

function saveSection() {
    if (section_form.valid()) {
        ajaxRequest($('#action_save').val(), {
            type: 'POST',
            data: section_form.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar la respuesta',
            success_message: 'La respuesta se guardo correctamente',
            success_callback: function (data) {
                modal_section.modal('hide');
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
            modal_section.find('.container_modal').html('');
            modal_section.find('.container_modal').html(data.html);
            section_form = $('#section_form');    
            validateForm();      
            $('#typeProviders_id').select2({
                dropdownParent: $('#section_form'),
                width: '100%',
                placeholder: '-Seleccione-',
            });
            modal_section.modal({
                show: true,
                backdrop: 'static',
                keyboard: false, // to prevent closing with Esc button (if you want this too)
            });
        },
    });
}

function validateForm() {
    section_form.validate({
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
            saveSection();
        },
    });
}