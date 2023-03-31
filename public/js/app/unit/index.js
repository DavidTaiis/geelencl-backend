var modalUnit = null;
var formUnit = null;
var dataTable = null;
$(function () {
    dataTable = initDataTableAjax(
        $('#unit_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_units').val(),
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
                    data: "values",
                    title: 'Valores'
                },
                {
                    data: null,
                    title: 'Acciones',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-dark btn-sm" onclick="editUnit(' +
                        row.id + ')">Editar</button>';
                    }
                }
            ]
        });

    modalUnit = $('#unit_modal'); // busca un elemento con id modal
});

function editUnit(id) {
    modalUnit.find('.modal-title').html('Editar unidad de medida');
    getFormUnit($('#action_get_form').val() + '/' + id);
}

function newUnit() {
    modalUnit.find('.modal-title').html('Crear unidad de medida');
    getFormUnit($('#action_get_form').val());
}

function saveUnit() {
    if (formUnit.valid()) {
        ajaxRequest(
            $('#action_save_unit').val(),
            {
                type: 'POST',
                data: formUnit.serialize(), // datos del formulario .serialize()
                blockElement: '#unit_modal .modal-content',//opcional: es para bloquear el elemento
                loading_message: 'Guardando...',
                error_message: 'Error al guardar el unidad de medida',
                success_message: 'La unidad de medida se guardó correctamente',
                success_callback: function (data) {
                    modalUnit.modal('hide'); // ocultar modal
                    dataTable.ajax.reload();
                }
            });
    }
}
function getFormUnit(url) {
    ajaxRequest(url, {
        type: 'GET',
        error_message: 'Error al cargar formulario',
        success_callback: function (data) {
            modalUnit.find('.container_modal').html('');
            modalUnit.find('.container_modal').html(data.html);
            formUnit = $("#unit_form");
            validateFormUnit();
            modalUnit.modal({
                show: true,
                backdrop: 'static',
                keyboard: false // to prevent closing with Esc button (if you want this too)
            });
        }
    });
}

function validateFormUnit() {
    formUnit.validate({
        rules: {
            name: {
                required: true,
                maxlength: 45,
                remote: {
                    url: $('#action_unique_name').val(),
                    type: 'POST',
                    data: {
                        id: function () {
                            return $('#unit_id').val();
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
                remote: 'Ya existe una unidad de medida con ese nombre.'
            }
        },
        errorElement: 'small',
        errorClass: 'help-block',
        highlight: validationHighlight,
        success: validationSuccess,
        errorPlacement: validationErrorPlacement,
        submitHandler: function (form) {
            saveUnit();
        }
    });
}