function guardarDatos(){
    Swal.fire({
        title: "¿Estás seguro que deseas realizar esta acción?",
        text: "Recuerda que una vez ENVIADA la información no podrás volver a editar",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
        
    }).then(function(result) {
        if (result.value) {   
            ajaxRequest($('#action_save').val(), {
                type: 'POST',
                success_callback: function(data) {
                    loadCoverages();
                },
              });
              Swal.fire({
                icon: "success",
                title: "Guardador correctamente",
                showConfirmButton: false,
                timer: 1500
            })

        }
    });
}

