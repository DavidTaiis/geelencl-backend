var modal = null;
var form_parameter = null;
var dataTable = null;
$(function() {
  dataTable = initDataTableAjax($('#image_parameter_table'),
      {
        'processing': true,
        'serverSide': true,
        ajax: {
          url: $('#action_list').val(),
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
            data: 'label',
            title: 'Etiqueta',
          },
          {
            data: 'width',
            title: 'Ancho(px)',
          },
          {
            data: 'height',
            title: 'Alto(px)',
          },
          {
            data: 'entity',
            title: 'Categoría',
            render: function(data, type, row, meta) {
              switch (row.entity) {
                case 'CATEGORY':
                  return 'Categoría';
                case 'PUBLICITY':
                  return 'Publicidad';
                case 'PRODUCT':
                  return 'Producto';
                case 'PROVIDER':
                  return 'Proveedor';
                case 'COUPON':
                  return 'Cupón';
                default:
                  return row.entity;
              }
            },
          },
          {
            data: 'extension',
            title: 'Formatos válidos',
          },
          {
            data: null,
            title: 'Acciones',
            orderable: false,
            render: function(data, type, row, meta) {
              return '<button class="btn btn-dark btn-sm" onclick="editListener(' +
                  row.id + ')">Editar</button>';
            },
          },
        ],
      });
  modal = $('#modal');
});

function editListener(id) {
  modal.find('.modal-title').html('Editar parámetro de imagen');
  getForm($('#action_get_form').val() + '/' + id);
}

function newListener() {
  modal.find('.modal-title').html('Crear parámetro de imagen');
  getForm($('#action_get_form').val());
}

function deleteListener(id) {
  bootbox.confirm('¿Esta seguro de eliminar el parámetro de imagen?',
      function(result) {
        if (result) {
          deleteFeature(id);
        }
      });
}

function deleteFeature(id) {
  ajaxRequest($('#action_delete').val() + '/' + id, {
    type: 'DELETE',
    loading_message: 'Eliminando...',
    error_message: 'Error al eliminar el parámetro de imagen',
    success_message: 'El parámetro de imagen se eliminó correctamente',
    success_callback: function(data) {
      dataTable.ajax.reload();
    },
  });
}

function save() {
  if (form_parameter.valid()) {
    ajaxRequest($('#action_save').val(), {
      type: 'POST',
      data: form_parameter.serialize(),
      blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
      loading_message: 'Guardando...',
      error_message: 'Error al guardar el parámetro de imagen',
      success_message: 'La parámetro de imagen se guardo correctamente',
      success_callback: function(data) {
        modal.modal('hide');
        dataTable.ajax.reload();
      },
    });
  }
}

function getForm(action) {
  ajaxRequest(action, {
    type: 'GET',
    error_message: 'Error al cargar formulario',
    success_callback: function(data) {
      modal.find('.container_modal').html('');
      modal.find('.container_modal').html(data.html);
      form_parameter = $('#image_parameter_form');
      validateForm();
      $('#entity').select2({
        dropdownParent: $('#modal'),
        width: '100%',
        placeholder: '-Seleccione-',
      });
      $('#extension').select2({
        dropdownParent: $('#modal'),
        width: '100%',
        placeholder: '-Seleccione-',
      });
      modal.modal({
        show: true,
        backdrop: 'static',
        keyboard: false, // to prevent closing with Esc button (if you want this too)
      });
    },
  });
}

function validateForm() {
  form_parameter.validate({
    rules: {
      name: {
        required: true,
        maxlength: 64,
        remote: {
          url: $('#action_unique_name').val(),
          type: 'POST',
          data: {
            id: function() {
              return $('#parameter_id').val();
            },
            _token: $('meta[name="csrf-token"]').attr('content'),
            name: function() {
              return $('#name').val().trim();
            },
            entity: function() {
              return $('#entity').val().trim();
            },
          },
        },
      },
      label: {
        required: true,
        maxlength: 64,
      },
      width: {
        required: true,
        digits: true,
      },
      height: {
        required: true,
        digits: true,
      },
      entity: {
        required: true,
      },
      'extension[]': {
        required: true,
      },
    },
    messages: {
      name: {
        remote: 'Ya existe un parámetro de imagen con ese nombre.',
      },
      entity: {
        remote: 'Ya existe un parámetro de imagen con esa categoría.',
      },
    },
    errorElement: 'small',
    errorClass: 'help-block',
    highlight: validationHighlight,
    success: validationSuccess,
    errorPlacement: validationErrorPlacement,
    submitHandler: function(form) {
      save();
    },
  });
}