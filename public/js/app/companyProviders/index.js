var dataTable = null;
const form_parameter = null
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
                       
                        return '<a href="'+ $('#action_index_provider').val() +'/'+ row.id+'" target=""><span class="btn btn btn-outline-dark btn-sm">Ver información</span></a>';
                    },
                },
            ],
        });
});

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
