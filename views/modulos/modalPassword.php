<?php

$active = "¡BIENVENIDO!";

?>

<main id="main" class="main">

        <?php include_once 'views/modulos/includes/ruta.php'; ?>
        <!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                </div>
            </div>
        </section>

    </main>




<!-- MODAL EDITAR USUARIO -->

<!-- Large Modal -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

    <!-- MODAL HEADER -->
      <div class="modal-header">
        <h5 class="modal-title">Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- MODAL BODY -->
      <div class="modal-body">
      <form role="form" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Nombre</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="editarNombre" placeholder="Ingresar Nombre" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Usuario</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="editarUsuario" name="editarUsuario" value="" readonly>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">Contraseña</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" name="editarPassword" placeholder="Nueva Contraseña" required>
                    <input type="hidden" id="passwordActual" name="passwordActual">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Perfil</label>
                    <div class="col-sm-10">
                        <select name="editarPerfil" class="form-select">
                          <option value="" id="editarPerfil"></option>
                          <option value="Administrador">Administrador</option>
                          <option value="Cliente">Cliente</option>
                          <option value="Vendedor">Vendedor</option>
                          <option value="Asesor Virtual">Asesor virtual</option>
                        </select>
                    </div>
                </div>
                      <!-- MODAL FOOTER -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                  <button type="submit" class="btn btn-primary">Modificar Usuario</button>
                </div>

                <?php

                  $editarUsuario = new ControllersUsuarios();
                  $editarUsuario -> ctrEditarUsuario();

                ?>
              </form>
      </div>

    </div>
  </div>
</div><!-- End Large Modal-->

<!-- MODAL REGISTRO DE CONTRASEÑA NUEVA -->
<div class="modal fade" id="MODALPASSWORD" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cambiar contraseña</h5>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
        </div>
        <div class="modal-body">
                <form id="passwordChangeForm" role="form" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="password" class="col-form-label">Nueva contraseña:<span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="password" placeholder="Contraseña" name="contraseña1" aria-describedby="togglePassword" required>
                        <span class="input-group-text" id="togglePassword">
                            <i class="bi bi-eye-slash" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password2" class="col-form-label">Confirmar contraseña:<span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="password2" name="CambiarPassword" placeholder="Contraseña" aria-describedby="togglePassword" required>
                        <span class="input-group-text" id="togglePassword">
                            <i class="bi bi-eye-slash" id="toggleIcon2"></i>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="validarYCambiarContraseña()">Guardar</button>
            </div>
                    
                    <?php
                    $ActualizarPassword = new ControllersUsuarios();
                    $ActualizarPassword -> ctrActualizarPassword();
                    ?>


                </form>
            </div>
            
        </div>
    </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    const passwordField2 = document.getElementById('password2');
    const toggleIcon2 = document.getElementById('toggleIcon2');
    const passwordChangeForm = document.getElementById('passwordChangeForm');

    toggleIcon.addEventListener('click', function () {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        }
    });

    toggleIcon2.addEventListener('click', function () {
        if (passwordField2.type === 'password') {
            passwordField2.type = 'text';
            toggleIcon2.classList.remove('bi-eye-slash');
            toggleIcon2.classList.add('bi-eye');
        } else {
            passwordField2.type = 'password';
            toggleIcon2.classList.remove('bi-eye');
            toggleIcon2.classList.add('bi-eye-slash');
        }
    });

    $(document).ready(function () {
        $('#MODALPASSWORD').modal('show');
    });
});

</script>
<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        const passwordField2 = document.getElementById('password2');
        const toggleIcon2 = document.getElementById('toggleIcon2');
        const passwordChangeForm = document.getElementById('passwordChangeForm');

        toggleIcon.addEventListener('click', function () {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        });

        toggleIcon2.addEventListener('click', function () {
            if (passwordField2.type === 'password') {
                passwordField2.type = 'text';
                toggleIcon2.classList.remove('bi-eye-slash');
                toggleIcon2.classList.add('bi-eye');
            } else {
                passwordField2.type = 'password';
                toggleIcon2.classList.remove('bi-eye');
                toggleIcon2.classList.add('bi-eye-slash');
            }
        });

        function validarYCambiarContraseña() {
            const password = passwordField.value;
            const confirmPassword = passwordField2.value;

            if (password === confirmPassword) {
                // Las contraseñas coinciden, puedes proceder con el cambio de contraseña
                // Agrega tu lógica aquí

                return true; // Devuelve true si la operación es exitosa
            } else {
                // Las contraseñas no coinciden, muestra un mensaje de error
                                
                swal.fire({
                        icon: "error",
                        title: "¡Las contraseñas no coinciden!",
                        showConfirmButton: true,
                        confirmButtonText: "cerrar",
                        closeOnConfirm: false

                    }).then ((result)=>{

                        if(result.value){

                            window.location = "dashboard";
    
                         }
                    });
                return false; // Devuelve false para evitar el cierre del modal
            }
        }

        $(document).ready(function () {
            $('#MODALPASSWORD').modal('show');
        });
    });
</script> -->


