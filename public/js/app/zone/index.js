var modal = null;
var form_zone = null;
var dataTable = null;
var select_country = null;
var select_province = null;
var select_city = null;

var current_city = null;
var map = null;
var selected_color;
var selectedShape;
var centerOfPolygon;
var windowZone;
var windowZoneContent = [
  '<b>Ingrese nombre de la zona:</b><br>',
  '<input id=\'input_zone\' type=\'text\'>',
  '<input type=\'submit\' id=\'create_zone\' value=\'Crear\'>',
  '<input type=\'submit\' id=\'update_zone\' value=\'Actualizar\'>',
].join('');
var clean = false;
var vertices;
var coordinates;

var polygons = [];	// array
var polygon = {};	// object
var polygonPosition = 0;

var polygonsDB = [];
var band = false;

$(function() {

  modal = $('#modal');

  select_country = $('#country_id').select2({
    data: countries,
    allowClear: true,
    placeholder: '--Seleccione país--',
  });

  select_province = $('#province_id').select2({
    disabled: true,
    data: provinces,
    allowClear: true,
    placeholder: '--Seleccione provincia--',
    matcher: function(params, data) {
      var countrySelected = select_country.select2('data')[0];

      if (String(data.country_id) !== String(countrySelected.id)) {
        return null;
      }

      if ($.trim(params.term) === '') {
        return data;
      }
      var term = params.term.toLowerCase();

      if (data.text.toLowerCase().indexOf(term) > -1) {
        return data;
      }

      return null;

    },
  });

  select_city = $('#city_id').select2({
    disabled: true,
    data: cities,
    allowClear: true,
    placeholder: '--Seleccione ciudad--',
    matcher: function(params, data) {
      var provinceSelected = select_province.select2('data')[0];

      if (String(data.province_id) !== String(provinceSelected.id)) {
        return null;
      }

      if ($.trim(params.term) === '') {
        return data;
      }

      var term = params.term.toLowerCase();
      if (data.text.toLowerCase().indexOf(term) > -1) {
        return data;
      }

      return null;

    },
  });
  select_country.on('select2:select', function(e) {
    select_province.val(null).trigger('change');
    select_province.select2('enable');
  });
  select_country.on('select2:unselect', function(e) {
    select_province.val(null).trigger('change');
    select_city.val(null).trigger('change');
    select_province.select2('enable', false);
    select_city.select2('enable', false);
  });
  select_province.on('select2:select', function(e) {
    select_city.val(null).trigger('change');
    select_city.select2('enable');
  });
  select_province.on('select2:unselect', function(e) {
    select_city.val(null).trigger('change');
    select_city.select2('enable', false);
  });
  dataTable = initDataTableAjax($('#zone_table'),
      {
        'processing': true,
        'serverSide': true,
        ajax: {
          url: $('#action_list').val(),
          data: function(filterDateTable) {
            filterDateTable.city_id = select_city.val() || -1;
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
            data: 'color',
            title: 'Color',
            render: function(data, type, row, meta) {
              return '<span class="badge" style="background-color: ' +
                  row.color + '; color: ' + row.color + ';">2</span>';
            },
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
              return '<button class="btn btn-dark btn-sm" onclick="editListener(' +
                  row.id + ')">Editar</button>'
                  ;
            },
          },
        ],
      });
  select_city.on('change', function() {
    if (select_city.val() && select_city.val() != null) {
      $('#wrapper_zone').removeClass('hide');
      dataTable.ajax.reload();
      current_city = select_city.select2('data')[0];
      polygons = [];	// array
      polygon = {};	// object
      polygonPosition = 0;
      polygonsDB = [];

      $('#zone_table').css('width', '100%');
      initMap();
    } else {
      $('#wrapper_zone').addClass('hide');
    }
  });
  $('#select_color').colorselector(
      {
        callback: function(value, color, title) {
          selectColor(color);
        },
      });
  initListenerSaveZoneMap();

});

function editListener(id) {
  modal.find('.modal-title').html('Editar zona');
  getForm($('#action_get_form').val() + '/' + id);
}

function newListener() {
  modal.find('.modal-title').html('Crear zona');
  getForm($('#action_get_form').val());
}

function save() {
  if (form_zone.valid()) {
    ajaxRequest($('#action_save').val(), {
      type: 'POST',
      data: form_zone.serialize(),
      blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
      loading_message: 'Guardando...',
      error_message: 'Error al guardar la zona',
      success_message: 'La zona se guardo correctamente',
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
      form_zone = $('#category_form');
      validateForm();
      modal.modal({
        show: true,
        backdrop: 'static',
        keyboard: false, // to prevent closing with Esc button (if you want this too)
      });
    },
  });
}

function validateForm() {
  form_zone.validate({
    rules: {
      name: {
        required: true,
        maxlength: 64,
        remote: {
          url: $('#action_unique_name').val(),
          type: 'POST',
          data: {
            id: function() {
              return $('#zone_id').val();
            },
            _token: $('meta[name="csrf-token"]').attr('content'),
            name: function() {
              return $('#name').val().trim();
            },
          },
        },
      },
    },
    messages: {
      name: {
        remote: 'Ya existe una zona con ese nombre.',
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

function deleteListener(id) {
  bootbox.confirm('¿Esta seguro de eliminar la zona?', function(result) {
    if (result) {
      deleteCategory(id);
    }
  });
}

function deleteCategory(id) {
  ajaxRequest($('#action_delete').val() + '/' + id, {
    type: 'DELETE',
    loading_message: 'Eliminando...',
    error_message: 'Error al eliminar la zona',
    success_message: 'La zona se eliminó correctamente',
    success_callback: function(data) {
      dataTable.ajax.reload();
    },
  });
}

function selectColor(color) {
  selected_color = color;
  // Retrieves the current options from the drawing manager and replaces the
  // stroke or fill color as appropriate.
  var polygonOptions = drawingManager.get('polygonOptions');
  polygonOptions.fillColor = color;
  drawingManager.set('polygonOptions', polygonOptions);
}

function clearSelection() {
  if (selectedShape) {
    if (typeof selectedShape.name === 'undefined') {
      $('#input_zone').addClass('highlight');
      $('#input_zone').focus();
      if (this.drawingMode === 'polygon') {
        unSelectedShape();
      }
      return;
    }
    selectedShape.setEditable(false);
    selectedShape = null;
    windowZone.close();
  }
}

function setSelection(shape) {
  clearSelection();
  selectedShape = shape;
  shape.setEditable(true);
  selectColor(shape.get('fillColor') || shape.get('strokeColor'));
}

function pointUpdated(index) {
  var path = this;
  var btnDelete = getDeleteButton(path.btnDeleteImageUrl);

  if (btnDelete.length === 0) {
    var undoimg = $(
        'img[src$=\'http://maps.gstatic.com/mapfiles/undo_poly.png\']');

    undoimg.parent().css('height', '21px !important');
    undoimg.parent().attr('id', 'undoBtn');
    undoimg.parent().
        parent().
        append(
            '<div style="overflow-x: hidden; overflow-y: hidden; position: absolute; width: 30px; height: 27px;top:21px;"><img src="' +
            path.btnDeleteImageUrl +
            '" class="deletePoly" style="height:auto; width:auto; position: absolute; left:0;"/></div>');

    // now get that button back again!
    btnDelete = getDeleteButton(path.btnDeleteImageUrl);
    btnDelete.hover(function() {
          $(this).css('left', '-30px');
          return false;
        },
        function() {
          $(this).css('left', '0px');
          return false;
        });
    btnDelete.mousedown(function() {
      $(this).css('left', '-60px');
      return false;
    });
    undoimg.click(function() {
      setTimeout(function() {
        updatePolygon();
      }, 100);
    });
  }

  // if we've already attached a handler, remove it
  if (path.btnDeleteClickHandler) {
    btnDelete.unbind('click', path.btnDeleteClickHandler);
  }
  // now add a handler for removing the passed in index
  path.btnDeleteClickHandler = function() {
    if (path.name === polygon.name) {
      if (path.length <= 3) {
        polygons.splice(polygonPosition, 1);
        $('#savedata pre').text(JSON.stringify(polygons, undefined, 2));
        unSelectedShape();
        return;
      }
      path.removeAt(index);
      vertices = path;
      updatePolygon();
      return false;
    }
  };

  btnDelete.one('click', function(e) {
    // This will prevent event triggering more than once
    if (e.handled !== true) {
      path.btnDeleteClickHandler();
      e.handled = true;
    }
  });
}

function getDeleteButton(imageUrl) {
  return $('img[src$=\'' + imageUrl + '\']');
}

function calculateCenterOfPolygon(e) {
  if (typeof e.overlay === 'undefined') {
    centerOfPolygon = e.latLng;
    return;
  }
  var polygonBounds = e.overlay.getPath();
  if (polygonBounds.length <= 2) {
    clean = true;
  }
  var bounds = new google.maps.LatLngBounds();
  polygonBounds.forEach(function(xy, i) {
    bounds.extend(xy);
  });
  centerOfPolygon = bounds.getCenter();
}

function selectPolygon(name) {

  polygons.forEach(function(value, i) {
    if (name === value.name) {
      polygonPosition = i;
      polygon = value;
      return;
    }
  });
}

function unSelectedShape() {
  if (selectedShape) {
    selectedShape.setMap(null);
    windowZone.close();
    if (typeof selectedShape.name !== 'undefined') {
      polygons.splice(polygonPosition, 1);
      $('#savedata pre').text(JSON.stringify(polygons, undefined, 2));
    }
  }
}

function addDeleteButton(polyPaths, imageUrl, zona) {
  var path = polyPaths;
  path['btnDeleteClickHandler'] = {};
  path['btnDeleteImageUrl'] = imageUrl;
  path['zona'] = zona;

  google.maps.event.addListener(polyPaths, 'set_at', pointUpdated);
  google.maps.event.addListener(polyPaths, 'insert_at', pointUpdated);
}

function generaCoordenadasJson(polygonBounds) {
  coordinates = [];
  polygonBounds.forEach(function(xy, i) {
    coordinates.push(new Array(xy.lat(), xy.lng()));
  });
}

function updatePolygon() {
  if (typeof selectedShape.name !== 'undefined') {
    generaCoordenadasJson(vertices);
    polygon.polygon_coordinates = coordinates;
    polygons.splice(polygonPosition, 1, polygon);
    $('#savedata pre').text(JSON.stringify(polygons, undefined, 2));
  }
}

function initMap() {
  var lat = parseFloat(current_city.latitude);
  var lng = parseFloat(current_city.longitude);
  var default_position = {lat: lat, lng: lng};

  map = new GMaps({
    div: '#zones_map',
    zoom: 14,
    center: default_position,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    disableDefaultUI: true,
    zoomControl: true,
    click: function() {
      clearSelection();
    },
  });

  var polyOptions = {
    strokeWeight: 0,
    fillOpacity: 0.40,
    editable: true,
  };

  // Creates a drawing manager attached to the map that allows the user to draw shapes
  drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: null,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
        google.maps.drawing.OverlayType.POLYGON,
      ],
    },
    polygonOptions: polyOptions,
    map: map.map,
  });

  //select first color of select
  $('#select_color').colorselector('setColor', '#e5bedd');
  selectColor('#e5bedd');

  windowZone = new google.maps.InfoWindow({
    content: windowZoneContent,
  });

  google.maps.event.addListener(windowZone, 'closeclick', function() {
    if (typeof selectedShape.name === 'undefined') {
      unSelectedShape();
    }
  });

  google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
    if (e.type == google.maps.drawing.OverlayType.POLYGON) {
      $('#map').css('overflow', 'auto');
      // Switch back to non-drawing mode after drawing a shape.
      drawingManager.setDrawingMode(null);

      // Add an event listener that selects the newly-drawn shape when the user
      // mouses down on it.
      var newShape = e.overlay;
      newShape.type = e.type;
      setSelection(newShape);

      clean = false;
      calculateCenterOfPolygon(e);
      vertices = newShape.getPath();
      if (clean) {
        unSelectedShape();
        return;
      }
      windowZone.setPosition(centerOfPolygon);
      windowZone.open(map.map);
      $('#input_zone').focus();
      $('#update_zone').hide();

      google.maps.event.addListener(newShape, 'rightclick', function() {
        setSelection(newShape);
        selectPolygon(newShape.name);
        calculateCenterOfPolygon(e);
        windowZone.setPosition(centerOfPolygon);
        windowZone.open(map.map);
        $('#input_zone').val(newShape.name);
        $('#create_zone').hide();
      });

      google.maps.event.addListener(newShape, 'click', function() {
        setSelection(newShape);
        selectPolygon(newShape.name);

        google.maps.event.addListener(newShape.getPath(), 'set_at', function() {
          vertices = newShape.getPath();
          updatePolygon();
        });
        google.maps.event.addListener(newShape.getPath(), 'insert_at',
            function() {
              vertices = newShape.getPath();
              updatePolygon();
            });
      });
    }

  });

  // Clear the current selection when the drawing mode is changed, or when the map is clicked.
  google.maps.event.addListener(drawingManager, 'drawingmode_changed',
      clearSelection);

  ajaxRequest($('#action_load_zones_map').val(), {
    data: {
      city_id: select_city.val(),
    },
    success_callback: function(data) {
      $.each(data, function(i) {
        var newCoords = [];
        var color = this.color;
        $.each($.parseJSON(this.polygon_coordinates), function() {
          newCoords.push(new google.maps.LatLng(this[0], this[1]));
        });
        polygonsDB[i] = new google.maps.Polygon({
          path: newCoords,
          strokeWeight: 0,
          fillColor: color,
          fillOpacity: 0.4,
        });
        polygonsDB[i].name = this.name;
        polygonsDB[i].setMap(map.map);
        polygons.push(this);
        addPolygonsDB(polygonsDB[i]);
        // addDeleteButton(polygonsDB[i].getPath(), '{{ asset("media/img/favicon-menuexpress.png") }}', this.name);
      });
      $('#savedata pre').text(JSON.stringify(polygons, undefined, 2));
    },
  });

  function addPolygonsDB(newShape) {
    google.maps.event.addListener(newShape, 'rightclick', function(e) {
      setSelection(newShape);
      selectPolygon(newShape.name);
      calculateCenterOfPolygon(e);
      windowZone.setPosition(centerOfPolygon);
      windowZone.open(map.map);
      $('#input_zone').val(newShape.name);
      $('#create_zone').hide();
    });

    google.maps.event.addListener(newShape, 'click', function() {
      setSelection(newShape);
      selectPolygon(newShape.name);

      google.maps.event.addListener(newShape.getPath(), 'set_at', function() {
        vertices = newShape.getPath();
        updatePolygon();
      });
      google.maps.event.addListener(newShape.getPath(), 'insert_at',
          function() {
            vertices = newShape.getPath();
            updatePolygon();
          });
    });
  }

}

function validateInputZone(crearOeditar) {
  $('#input_zone').val($.trim($('#input_zone').val()));
  // To valid input_zone required
  if (!$('#input_zone').val()) {
    band = true;
  }
  // To valid input_zone unique
  for (var i = 0; i < polygons.length; i++) {
    if ($('#input_zone').val().toUpperCase() ===
        polygons[i].name.toUpperCase()) {
      if (crearOeditar === 'update_zone' &&
          ($('#input_zone').val().toUpperCase() ===
              polygon.name.toUpperCase())) {
        continue;
      }
      showAlert('danger', 'La zona \'' + $('#input_zone').val() +
          '\' ya existe.\nFavor utilice otro nombre.');
      band = true;
      return;
    }
  }
}

function initListenerSaveZoneMap() {

  $('body').on('click', '#create_zone, #update_zone', function(e) {
    e.preventDefault();
    band = false;
    validateInputZone($(this).prop('id'));

    // Message if input_zone is empty or already exists
    if (band) {
      $('#input_zone').addClass('highlight');
      $('#input_zone').focus();
      return;
    }

    if ($(this).prop('id') === 'create_zone') {
      // guarda nombre de la zona y genera coordenadas json
      polygon = new Object();
      selectedShape.name = $('#input_zone').val();
      polygon.name = $('#input_zone').val();
      polygon.color = selected_color;
      generaCoordenadasJson(vertices);
      polygon.polygon_coordinates = coordinates;
      polygons.push(polygon);
      addDeleteButton(vertices, 'btn_eliminar.png', $('#input_zone').val());
    } else {
      selectedShape.name = $('#input_zone').val();
      polygon.name = $('#input_zone').val();
    }
    $('#savedata pre').text(JSON.stringify(polygons, undefined, 2));
    $('#input_zone').removeClass('highlight');
    windowZone.close();
    clearSelection();
  });
  $('#save_button').click(function() {
    var cadena = JSON.stringify(polygons);
    ajaxRequest($('#action_save_zones').val(), {
      type: 'POST',
      data: {
        data: cadena,
        city_id: select_city.val(),
      },
      loading_message: 'Guardando...',
      success_callback: function(data) {
        polygons = [];	// array
        polygon = {};	// object
        polygonPosition = 0;
        polygonsDB = [];
        dataTable.ajax.reload();
        initMap();
        showAlert('success', 'Zonas actualizadas exitosamente.');
      },
      error_callback: function(err) {
        showAlert('error', err.responseText);
      },
    });
  });
}


