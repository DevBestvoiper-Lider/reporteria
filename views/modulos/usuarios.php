<?php

$active = "Administrar Usuarios";

?>

<main id="main" class="main">

        <?php include_once 'views/modulos/includes/ruta.php'; ?>
        <!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                          <br>

                            <!-- Table with stripped rows -->
                            <table id="table" class="table table-bordered table-striped">
                            <br>
                            <thead>
                              <tr>
                                <th style="width:10px">#</th>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Account Name</th>
                                <th>Foto</th>
                                <th>Perfil</th>
                                <th>Ultimo login</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                                <tbody>
                                <?php 

                                  $valor = null;
                                  $item = null;
                                  $usuarios = ControllersUsuarios::ctrMostrarUsuarios($item, $valor);

                                  foreach ($usuarios as $key => $value){
                                    echo '
                                    <tr>
                                      <td>'.$value["id"].'</td>
                                      <td>'.$value["nombre"].'</td>
                                      <td>'.$value["usuario"].'</td>
                                      <td>'.$value["customeraccountname"].'</td>'
                                      ;

                                      if ($value["foto"] != ""){
                                        echo '<td><img src="'.$value["foto"].'" class="img-thumbnail" width="40px" alt=""></td>';
                                      }else {
                                        echo '<td><img src="views/img/users/avatar.png" class="img-thumbnail" width="40px" alt=""></td>';
                                      }
                                      
                                      echo '<td>'.$value["perfil"].'</td>';

                                      echo '<td>'.$value["ultimo_login"].'</td>
                                      <td>

                                        <div class="btn-group">
                                            <botton class="btn btn-warning btnEditarUsuario" idUsuario="'.$value["id"].'" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario"><i class="fa fa-pencil"></i></botton>
                                        </div>

                                      </td>
                                    
                                    </tr>';
                                    
                                  }

                                ?>
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>


<!-- MODAL CREAR USUARIO -->
<div class="modal fade" id="modalAgregarUsuario" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

    <!-- MODAL HEADER -->
      <div class="modal-header">
        <h5 class="modal-title">Crear Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- MODAL BODY -->
      <div class="modal-body">
      <form role="form" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Nombre</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="nuevoNombre" placeholder="Ingresar Nombre" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Usuario</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="nuevoUsuario" name="nuevoUsuario" placeholder="Ingresar Usuario" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Account Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="nuevoAccountName" name="nuevoAccountName" placeholder="Account Name" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">Contraseña</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" name="nuevoPassword" placeholder="Ingresar Contraseña" required>
                  </div>
                </div>
                <!-- <div class="row mb-3">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">Estado</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="nuevoEstado" placeholder="estado" value="1" readonly required>
                  </div>
                </div> -->
                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Perfil</label>
                    <div class="col-sm-10">
                        <select name="nuevoPerfil" class="form-select">
                          <option value="">seleccionar Perfil</option>
                          <option value="Administrador">Administrador</option>
                          <option value="Cliente">Cliente</option>
                          <option value="Vendedor">Vendedor</option>
                          <option value="Asesor Virtual">Asesor virtual</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Subir Foto</label>
                  <div class="col-sm-10">
                    <input class="form-control nuevaFoto" type="file" name="nuevaFoto">
                    <img src="views/img/users/avatar.png" class="img-thumbnail previsualizar" width="100px">
                  </div>
                </div>
                      <!-- MODAL FOOTER -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                  <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                </div>

                <?php

                $crearUsuario = new ControllersUsuarios();
                $crearUsuario -> ctrCrearUsuario();

                ?>
              </form>
      </div>

    </div>
  </div>
</div><!-- End Large Modal-->

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

<?php
$borrarUsuario = new ControllersUsuarios();
$borrarUsuario -> ctrBorrarUsuario();
?>

