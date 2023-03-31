function blockPage(label) {
  $.blockUI({
    overlayColor: '#000000',
    type: 'loader',
    state: 'success',
    message: '<div class="cssload-thecube"><div class="cssload-cube cssload-c1"></div> <div class="cssload-cube cssload-c2"></div> <div class="cssload-cube cssload-c4"></div><div class="cssload-cube cssload-c3"></div></div><h3> ' +
        label + '</h3>',
    css: {backgroundColor: 'rgba(255, 255, 255, 0.89)'},
  });

}

function blockContainer(el, label) {
  $(el).block({
    overlayColor: '#000000',
    type: 'loader',
    state: 'success',
    message: '<div class="cssload-thecube-mini"><div class="cssload-cube cssload-c1"></div> <div class="cssload-cube cssload-c2"></div> <div class="cssload-cube cssload-c4"></div><div class="cssload-cube cssload-c3"></div></div><h5> ' +
        label + '</h5>',
  });
}

function unblockPage() {
  $.unblockUI();
}

function unblockContainer(el) {
    $(el).unblock();
}

function ajaxRequest(url, params, hasFileUpload) {
    var type = params.hasOwnProperty('type') ? params.type : 'GET';
    var blockElement = params.hasOwnProperty('blockElement') ? params.blockElement
        : null;
    var data = params.hasOwnProperty('data') ? params.data : [];
    var error_message =
        params.hasOwnProperty('error_message') ? params.error_message
            : 'Ha ocurrido un error durante la petición, inténtelo nuevamente.';
    var loading_message =
        params.hasOwnProperty('loading_message') ? params.loading_message
            : 'Cargando..... ';
    var contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
    var processData = true;
    if (typeof hasFileUpload !== 'undefined' && hasFileUpload) {
        contentType = false;
        processData = false;
    }
    var blockScreen = params.hasOwnProperty('blockScreen') ? params.blockScreen : true;
    if (blockScreen) {
        blockElement ? blockContainer(blockElement, loading_message) : blockPage(loading_message);
    }

    $.ajax({
        type: type,
        url: url,
        dataType: 'json',
        data: data,
        contentType: contentType,
        processData: processData,
        beforeSend: function (jqXHR, settings) {
            if (params.hasOwnProperty('beforeSend')) {
                params.beforeSend(jqXHR, settings);
            }
        },
        error: function (data) {
            if (blockScreen) {
                blockElement ? unblockContainer(blockElement) : unblockPage();
            }
            //Error messages from server
            if (data.hasOwnProperty('status') && data.hasOwnProperty('message')) {
                showAlert(data.status, data.message);
            } else { //Error messages from frontend
                showAlert('danger', error_message);
            }
            if (params.hasOwnProperty('error_callback')) {
                params.error_callback(data);
            }
        },
        success: function (data) {
            if (blockScreen) {
                blockElement ? unblockContainer(blockElement) : unblockPage();
            }
            //Error messages from server
            if (data.hasOwnProperty('status') && data.hasOwnProperty('message')) {
                showAlert(data.status, data.message);
            } else { //Error messages from frontend
                if (!data.hasOwnProperty('success') || data.success) {
                    if (params.hasOwnProperty('success_message')) {
                        showAlert('success', params.success_message);
                    }
                } else {
                    if (data.hasOwnProperty('error_message') &&
                        !params.hasOwnProperty('error_message')) {
                        showAlert('success', data.error_message);
                    } else {
                        showAlert('danger', error_message);
                    }
                }
            }
            if (params.hasOwnProperty('success_callback')) {
                params.success_callback(data);
            }
        },
        complete: function () {
            if (blockScreen) {
                blockElement ? unblockContainer(blockElement) : unblockPage();
            }
            if (params.hasOwnProperty('complete_callback')) {
                params.complete_callback();
            }
        },
    });
}

function showAlert(type, message) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    switch (true) {
        case (type === 'success'):
            toastr.success(message, "Aviso!");
            break;
        case (type === 'info'):
            toastr.info(message, "Aviso!");
            break;
        case (type === 'warning'):
            toastr.warning(message, "Aviso!");
            break;
        case (type === 'error'):
            toastr.error(message, "Aviso!");
            break;
    }

}

function validationHighlight(element) {

    $(element).closest('.form-group').addClass('has-error').removeClass('has-success');
}

function validationSuccess(element) {

    $(element).closest('.form-group').removeClass('has-error').removeClass('has-success');
}

function validationErrorPlacement(error, element) {
    if (element.parent('.input-group').length) {
        error.insertAfter(element.parent());
    } else {
        element.parent().append(error);
    }
}

function initSelect2(el, options) {
    var default_options = {
        placeholder: options.placeholder ? options.placeholder : '- Seleccione -',
        disabled: options.disabled ? options.disabled : false,
        multiple: options.multiple ? options.multiple : false,
        minimumInputLength:
            options.minimumInputLength ? options.minimumInputLength
                : false,
        allowClear: true,
        language: 'es',
        ajax: {
            url: options.ajax.url,
            dataType: options.ajax.dataType ? options.ajax.dataType : 'json',
            delay: 250,
            data: function (params) {
                if (typeof options.ajax.params === 'function') {
                    options.ajax.params(params);
                }
                return params;
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                return {
                    results: data,
                };
                // params.page = params.page || 1;
                //
                // return {
                //     results: data.items,
                //     pagination: {
                //         more: (params.page * 30) < data.total_count
                //     }
                // };
            },
            cache: false,
        },
        width: options.width ? options.width : '100%',
        // escapeMarkup: function (markup) {
        //     return markup;
        // }, // let our custom formatter work
        // minimumInputLength: 1,
        // templateResult: formatRepo,
        // templateSelection: formatRepoSelection
    };
    return el.select2(default_options);
}

function setSelectedValueSelect2(el, url, selectedValue) {
  var parameter = '';
  if (el[0].multiple) {
    parameter = url + '?ids=' + selectedValue;
  } else {
    parameter = url + '?id=' + selectedValue;
  }
  ajaxRequest(parameter, {
    type: 'GET',
    error_message: 'Error al cargar elemento seleccionado',
    success_callback: function(data) {
      // create the option and append to Select2
      if (data.length > 0) {
        $.each(data, function(index, value) {
          var option = new Option(data[index].text, data[index].id, true, true);
          el.append(option).trigger('change');
        });

            }
        },
    });
}

function initDataTableAjax(el, config) {
    return el.DataTable({
        processing: config.processing ? config.processing : false,
        serverSide: config.serverSide ? config.serverSide : false,
        pagingType: 'full_numbers',
        ajax: config.ajax,
        responsive: true,
        language: {
            sProcessing: 'Cargando...',
            sLengthMenu: 'Mostrar _MENU_ registros',
            sZeroRecords: 'No se encontraron resultados',
            sEmptyTable: 'Ningún dato disponible en esta tabla',
            sInfo: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
            sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
            sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
            sInfoPostFix: '',
            sSearch: 'Buscar:',
            sUrl: '',
            sInfoThousands: ',',
            sLoadingRecords: 'Cargando...',
            oPaginate: {
                sFirst: 'Primero',
                sLast: 'Último',
                sNext: 'Siguiente',
                sPrevious: 'Anterior',
            },
            oAria: {
                sSortAscending: ': Activar para ordenar la columna de manera ascendente',
                sSortDescending: ': Activar para ordenar la columna de manera descendente',
            },
            paginate: {
                previous: '<i class="demo-psi-arrow-left"></i>',
                next: '<i class="demo-psi-arrow-right"></i>',
            },
        },
        columns: config.columns,
        rowCallback: function (row, data, dataIndex) {
            if (typeof config.rowCallback !== 'undefined' &&
                typeof config.rowCallback === 'function') {
                config.rowCallback(row, data, dataIndex);
            }
        },
        initComplete: function (settings, json) {
            if (typeof config.initComplete !== 'undefined' &&
                typeof config.initComplete === 'function') {
                config.initComplete(settings, json);
            }
        },
        drawCallback: function (settings) {
            var api = this.api();
            if (typeof config.drawCallback !== 'undefined' &&
                typeof config.drawCallback === 'function') {
                config.drawCallback(settings, api);
            }
        },
    });
}

function initDataTable(el, config) {
    config = config || {};
    return el.DataTable({
        responsive: true,
        language: {
            sProcessing: 'Cargando...',
            sLengthMenu: 'Mostrar _MENU_ registros',
            sZeroRecords: 'No se encontraron resultados',
            sEmptyTable: 'Ningún dato disponible en esta tabla',
            sInfo: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
            sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
            sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
            sInfoPostFix: '',
            sSearch: 'Buscar:',
            sUrl: '',
            sInfoThousands: ',',
            sLoadingRecords: 'Cargando...',
            oPaginate: {
                sFirst: 'Primero',
                sLast: 'Último',
                sNext: 'Siguiente',
                sPrevious: 'Anterior',
            },
            oAria: {
                sSortAscending: ': Activar para ordenar la columna de manera ascendente',
                sSortDescending: ': Activar para ordenar la columna de manera descendente',
            },
            paginate: {
                previous: '<i class="demo-psi-arrow-left"></i>',
                next: '<i class="demo-psi-arrow-right"></i>',
            },
        },
        rowCallback: function (row, data, dataIndex) {
            if (typeof config.rowCallback !== 'undefined' &&
                typeof config.rowCallback === 'function') {
                config.rowCallback(row, data, dataIndex);
            }
        },
        initComplete: function (settings, json) {
            if (typeof config.initComplete !== 'undefined' &&
                typeof config.initComplete === 'function') {
                config.initComplete(settings, json);
            }
        },
        drawCallback: function (settings) {
            var api = this.api();
            if (typeof config.drawCallback !== 'undefined' &&
                typeof config.drawCallback === 'function') {
                config.drawCallback(settings, api);
            }
        },
    });
}

function initDropZone(el, config) {
    return el.dropzone({
        url: config.url || '#',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        params: config,
        uploadMultiple: true,
        autoProcessQueue: config.autoProcessQueue === 'yes',
        dictInvalidFileType: 'Formato de la imagen no es válido',
        dictRemoveFile: 'Eliminar',
        addRemoveLinks: true,
        acceptedFiles: config.accepteFiles,
        init: function () {
            var myDropzone = this;
            myDropzone.on('addedfile', function (file) {
                var d = this.options.params;
                /**Control for duplicate file*/
                if (this.files.length) {
                    var _i, _len;
                    for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) // -1 to exclude current file
                    {
                        if (this.files[_i].name === file.name && this.files[_i].size ===
                            file.size && this.files[_i].lastModified.toString() ===
                            file.lastModified.toString()) {
                            this.removeFile(file);
                        }
                    }
                }

                //test drop
                //   $('.wrapper_image').sortable('enable');

            });
            myDropzone.on('error', function (file) {
                this.removeFile(file);
            });
            this.on('thumbnail', function (file) {
                var d = this.options.params;
                var diff = d.maxWidth - d.maxHeight;
                var diff_file = file.width - file.height;
                //todo controlar por
                if (diff === diff_file) {
                    file.acceptDimensions();
                } else {
                    file.rejectDimensions();
                    return this.removeFile(file);
                }
            });
            myDropzone.on('removedfile', function (file) {
                // var d = this.options.params;
            });
            /* control for accepted Files Type*/
            myDropzone.accept = function (file, done) {
                var d = this.options.params;
                /*control for accepted types of images and dimentions*/
                if (!Dropzone.isValidFile(file, this.options.acceptedFiles)) {
                    showAlert('warning', 'Solo se permiten imágenes en formato: *' +
                        this.options.acceptedFiles);
                    return this.removeFile(file);
                } else {
                    file.rejectDimensions = function () {
                        showAlert('warning',
                            'La imagen no cumple con la proporción en dimensiones requeridas (ancho: ' +
                            d.maxWidth + 'px * alto:' + d.maxHeight + 'px)');
                    };
                    file.params = d;
                    file.acceptDimensions = function () {
                        return done();
                    };
                }
            };
        },
    });
}

function initSchedule(el, options) {
    el.jqs(options);
}

function validateNumber(e) {
    if ($.isNumeric($(e).val())) {
        (parseFloat($(e).val()).toFixed(2) === 'NaN' ||
            parseFloat($(e).val()).toFixed(2) < 0) ? $(e).val('') : $(e).val(parseFloat($(e).val()).toFixed(2));
    } else {
        $(e).val('');
    }
}

function validateTime(e) {
    var regExp = /^(\d+d)?\s*((?:[01]?\d|2[0-3])h)?\s*((?:[0-5]?\d)m)?$/;
    if (regExp.test($(e).val()) === false) {
        $(e).val('');
    }
}

$(function () {
    jQuery.extend(jQuery.validator.messages, {
        required: 'Este campo es obligatorio.',
        textOnly: 'Este campo admite s&oacute;lo texto.',
        alphaNumeric: 'Este campo admite s&oacute;lo caracteres alfa - num&eacute;ricos.',
        date: 'Este campo tiene un formato dd/mm/YYYY.',
        dateISO: 'Este campo tiene un formato YYYY-mm-dd.',
        digits: 'Este campo admite solo d&iacute;gitos.',
        number: 'Este campo admite solo n&uacute;meros enteros o decimales.',
        alphaNumericSpecial: 'Este campo admite s&oacute;lo caracteres alfa - num&eacute;ricos.',
        email: 'Este campo admite el formato <i>direccion@dominio.com</i>.',
        url: 'Ingrese un URL v&aacute;lido.',
        numberDE: 'Bitte geben Sie eine Nummer ein.',
        percentage: 'Este campo debe tener un porcentaje v&aacute;lido.',
        validarUserName: 'Nombre de Usuario no v\u00E1lido.',
        creditcard: 'Ingrese un n&uacute;mero de tarjeta de cr&eacute;dito v&aacute;lido.',
        equalTo: 'Las direcciones de correo no coinciden.',
        notEqualTo: 'Ingrese un valor diferente.',
        accept: 'Ingrese un valor con una extensi&oacute;n v&aacute;lida.',
        maxlength: $.validator.format(
            'Este campo debe tener m&aacute;ximo {0} caracteres.'),
        minlength: $.validator.format(
            'Este campo debe tener m&iacute;nimo {0} caracteres.'),
        rangelength: $.validator.format(
            'Ingrese un valor entre {0} y {1} caracteres.'),
        range: $.validator.format('Ingrese un valor entre {0} y {1}.'),
        max: $.validator.format('Ingrese un valor menor o igual a {0}.'),
        min: $.validator.format('Ingrese un valor mayor o igual a {0}.'),
        cedulaEcuador: 'Por favor ingrese una c&eacute;dula v&aacute;lida.',
        dateLessThan: $.validator.format('Ingrese una fecha menor o igual a {0}.'),
        dateMoreThan: $.validator.format('Ingrese una fecha mayor o igual a {0}.'),
        minStrict_zero: 'El valor debe ser mayor o igual a cero',
        minStrict: 'Ingrese un valor mayor a cero',
        dateLessThanDate: 'La fecha "Desde" debe ser menor o igual a la fecha en el campo "Hasta".',
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });
    $('.active-link').parents('lu').addClass('in');
    // $.fn.daterangepicker.dates['en'] = {
    //     days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
    //     daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
    //     daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
    //     months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    //     monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
    //     today: "Hoy",
    //     clear: "Limpiar",
    //     format: "yyyy-mm-dd",
    //     titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
    //     weekStart: 0
    // };
//Object forEachDone

    Object.defineProperty(Array.prototype, 'forEachDone', {
        enumerable: false,
        value: function (task, cb) {
            var counter = 0;
            this.forEach(function (item, index, array) {
                task(item, index, array);
                if (array.length === ++counter) {
                    if (cb) {
                        cb();
                    }
                }
            });
        },
    });

//Array forEachDone

    Object.defineProperty(Object.prototype, 'forEachDone', {
        enumerable: false,
        value: function (task, cb) {
            var obj = this;
            var counter = 0;
            Object.keys(obj).forEach(function (key, index, array) {
                task(obj[key], key, obj);
                if (array.length === ++counter) {
                    if (cb) {
                        cb();
                    }
                }
            });
        },
    });
    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
    $.fn.serializeToJSON = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
});

function validateImages(classWrapper) {
    var valid = true;
    $(classWrapper).each(function (i) {
        var id = $(this).data('id');
        var countFile = $(this).find('.dz-image-preview').length;
        if (countFile === 0) {
            $('#' + id + '_error').show();
            $('#' + id + '_title').hide();
            valid = false;
        } else {
            $('#' + id + '_error').hide();
            $('#' + id + '_title').show();
        }
    });
    return valid;
}
