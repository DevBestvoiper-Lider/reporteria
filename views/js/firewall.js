/*********************************
 REVISAR USUARIO
********************************/
$("#nuevoUsuario").change(function() {

    $(".alert").remove();

    var usuario = $(this).val();

    var datos = new FormData();
    datos.append("validarUsuario", usuario);

    $.ajax({

        url: "ajax/usuario.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            if (respuesta) {
                $("#nuevoUsuario").parent().after('<div class="alert alert-warning"> ¡ESTE USUARIO YA EXISTE EN LA BASE DE DATOS! </div>');

                $("#nuevoUsuario").val("");

            }
        }

    })

})

/*********************************
 ELIMINAR IP
********************************/

$(".btnEliminarIP").click(function() {

    var idIp = $(this).attr("idIp");


    swal.fire({
        title: '¿Estas seguro de eliminar la IP?',
        text: '¡Si no lo estas puedes cancelar esta opcion!',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#d33',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'eliminar',
        cancelmButtonText: 'cancelar'

    }).then((result) => {

        if (result.value) {

            window.location = "index.php?ruta=Anti-hack&idIp=" + idIp;

        }
    });
})