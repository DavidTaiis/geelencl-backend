var modal_country = null;
var form_country = null;
var dataTable = null;
$(function () {

    $('#dataTable').dataTable({
        "responsive": true,
        "language": {
            "paginate": {
                "previous": '<i class="demo-psi-arrow-left"></i>',
                "next": '<i class="demo-psi-arrow-right"></i>'
            }
        },
        order: [[0, "desc"]]
    });
});

