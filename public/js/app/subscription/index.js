var dataTable = null;
$(function () {
    dataTable = initDataTableAjax(
        $('#subscription_table'),
        {
            "processing": true,
            "serverSide": true,
            ajax: {
                url: $('#action_load_subscriptions').val(),
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
                    data: "email",
                    title: 'Correo'
                },
                {
                    data: "created_at",
                    title: 'Fecha'
                },
               
            ]
        });
});

