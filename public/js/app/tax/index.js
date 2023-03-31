var modal_tax = null;
var form_tax = null;
var dataTable = null;
$(function() {
  dataTable = initDataTableAjax($('#tax_table'),
      {
        'processing': true,
        'serverSide': true,
        ajax: {
          url: $('#action_load_taxes').val(),
          data: function(filterDateTable) {
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
          },
          {
            data: 'status',
            title: 'Estado',
            render: function(data, type, row, meta) {
              if (row.status === 'ACTIVE') {
                return '<span class="label label-sm label-success">Activo</span>';
              } else {
                return '<span class="label label-sm label-warning">Inactivo</span>';
              }
            },
          },
          {
            data: null,
            title: 'Acciones',
            orderable: false,
            render: function(data, type, row, meta) {
              return '<button class="btn btn-dark btn-sm" onclick="editTax(' +
                  row.id + ')">Editar</button>';
            },
          },
        ],
      });
  modal_tax = $('#modal');
});

function editTax(id) {
  modal_tax.find('.modal-title').html('Editar impuesto');
  getFormTax($('#action_get_form').val() + '/' + id);
}

function newTax() {
  modal_tax.find('.modal-title').html('Crear impuesto');
  getFormTax($('#action_get_form').val());
}

function saveTax() {
  if (form_tax.valid()) {
    ajaxRequest($('#action_save_tax').val(), {
      type: 'POST',
      data: form_tax.serialize(),
      blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
      loading_message: 'Guardando...',
      error_message: 'Error al guardar el impuesto',
      success_message: 'El Impuesto se guardo correctamente',
      success_callback: function(data) {
        modal_tax.modal('hide');
        dataTable.ajax.reload();
      },
    });
  }
}

function getFormTax(action) {
  ajaxRequest(action, {
    type: 'GET',
    error_message: 'Error al cargar formulario',
    success_callback: function(data) {
      modal_tax.find('.container_modal').html('');
      modal_tax.find('.container_modal').html(data.html);
      form_tax = $('#tax_form');
      validateFormTax();
      modal_tax.modal({
        show: true,
        backdrop: 'static',
        keyboard: false, // to prevent closing with Esc button (if you want this too)
      });
      $('#cities').multiSelect({selectableOptgroup: true});
    },
  });
}

function validateFormTax() {
  form_tax.validate({
    rules: {
      name: {
        required: true,
        maxlength: 64,
        remote: {
          url: $('#action_unique_name').val(),
          type: 'POST',
          data: {
            id: function() {
              return $('#tax_id').val();
            },
            _token: $('meta[name="csrf-token"]').attr('content'),
            name: function() {
              return $('#name').val().trim();
            },
          },
        },
      },
      value: {
        required: true,
      },
    },
    messages: {
      name: {
        remote: 'Ya existe un impuesto con ese nombre.',
        required: 'Nombre es obligatorio.',
      },
      value: {
        required: 'Valor es obligatorio.',
      },
    },
    errorElement: 'small',
    errorClass: 'help-block',
    highlight: validationHighlight,
    success: validationSuccess,
    errorPlacement: validationErrorPlacement,
    submitHandler: function(form) {
      saveTax();
    },
  });
}