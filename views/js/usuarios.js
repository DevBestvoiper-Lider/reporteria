// SUBIR FOTO DE USUARIO

$(".nuevaFoto").change(function() {

    var imagen = this.files[0];

    if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png" && imagen["type"] != "image/jpg") {

        $(".nuevaFoto").val("");
        swal.fire({
            icon: 'error',
            title: "Error al subir la imagen",
            text: "¡La imagen debe estar en formato JPEG o PNG!",
            confirmButtonText: "¡Cerrar!"
        });

    } else if (imagen["size"] > 2000000) {

        $(".nuevaFoto").val("");
        swal.fire({
            icon: 'error',
            title: "Error al subir la imagen",
            text: "¡La imagen debe pesar menos de 2MB!",
            confirmButtonText: "¡Cerrar!"
        });
    } else {
        var datosImagen = new FileReader;
        datosImagen.readAsDataURL(imagen);

        $(datosImagen).on("load", function(event) {
            var rutaImagen = event.target.result;

            $(".previsualizar").attr("src", rutaImagen);
        })
    }

})

// EDITAR USUARIO

$(".btnEditarUsuario").click(function() {
    var idUsuario = $(this).attr("idUsuario");

    var datos = new FormData();
    datos.append("idUsuario", idUsuario);

    $.ajax({

        url: "ajax/usuario.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            $("#editarNombre").val(respuesta["nombre"]);
            $("#editarUsuario").val(respuesta["usuario"]);
            $("#editarPerfil").html(respuesta["perfil"]);
            $("#editarPerfil").val(respuesta["perfil"]);
            $("#passwordActual").val(respuesta["password"]);
            $("#fotoActual").val(respuesta["foto"]);


            if (respuesta["perfil"] != "") {
                $(".previsualizar").attr("src", respuesta["foto"])
            }
        }

    });
})


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
 ELIMINAR USUARIO
********************************/

$(".btnEliminarUsuario").click(function() {

    var idUsuario = $(this).attr("idUsuario");
    var fotoUsuario = $(this).attr("fotoUsuario");
    var usuario = $(this).attr("usuario");


    swal.fire({
        title: '¿Estas seguro de eliminar el usuario?',
        text: '¡Si no lo estas puedes cancelar esta opcion!',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#d33',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'eliminar',
        cancelmButtonText: 'cancelar'

    }).then((result) => {

        if (result.value) {

            window.location = "index.php?ruta=usuarios&idUsuario=" + idUsuario + "&fotoUsuario=" + fotoUsuario + "&usuario=" + usuario;

        }
    });
})


// Definir una función que aplique los cambios basados en el valor del texto
function aplicarEstilos(textoElement, iconElement) {
    const valorTexto = parseFloat(textoElement.textContent);

    if (valorTexto >= 0) {
        textoElement.classList.add('text-success');
        iconElement.classList.add('text-success');
        iconElement.innerHTML = '<i class="fa fa-caret-up fw-bold" aria-hidden="true"></i> Up';
    } else if (valorTexto <= 0) {
        textoElement.classList.add('text-danger');
        iconElement.classList.add('text-danger');
        iconElement.innerHTML = '<i class="fa fa-caret-down fw-bold" aria-hidden="true"></i> Down';
    }
}

// Llamar a la función para aplicar estilos al cargar la página
aplicarEstilos(document.getElementById('porcentaje'), document.getElementById('incdis'));
aplicarEstilos(document.getElementById('porcentaje2'), document.getElementById('incdis2'));


