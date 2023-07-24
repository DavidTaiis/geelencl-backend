let formulario = null
$(function () {
formulario = $("#calification_form");
});

function guardarDatos(){
    console.log(formulario.serialize());
    Swal.fire({
        title: "¿Guardar calificación?",
        text: "Recuerda siempre guardar tu progreso de calificación",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
        
    }).then(function(result) {
         console.log(formulario.serialize());
            ajaxRequest($('#action_save').val(), {
                type: 'POST',
                data: formulario.serialize(),
                success_callback: function(data) {
                    reloadFuncion();
                },
              });
              Swal.fire({
                icon: "success",
                title: "Guardador correctamente",
                showConfirmButton: false,
                timer: 1500
            })
            setTimeout(() => {
                window.location.reload()
            }, 3000);
        
    });
}
function saveQuestion() {
        ajaxRequest($('#action_save').val(), {
            type: 'POST',
            data: formulario.serialize(),
            blockElement: '#modal .modal-content',//opcional: es para bloquear el elemento
            loading_message: 'Guardando...',
            error_message: 'Error al guardar la pregunta',
            success_message: 'La pregunta se guardo correctamente',
            success_callback: function (data) {
                
            },
        });
    
}

function reloadFuncion(){
    console.log("llegand");
    window.location.reload()
}