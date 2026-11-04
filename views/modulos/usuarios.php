<?php
$active = "Administrar Usuarios";
$valor = null;
$item = null;
$usuarios = ControllersUsuarios::ctrMostrarUsuarios($item, $valor);
$totalUsuarios = is_array($usuarios) ? count($usuarios) : 0;
?>

<main id="main" class="main">

    <div class="bv-page-head">
        <div>
            <div class="bv-crumb">Inicio / Usuarios</div>
            <h1>Usuarios</h1>
            <p>Cuentas de acceso al portal de reportería.</p>
        </div>
        <div class="bv-actions">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarUsuario">
                <i class="bi bi-plus-lg"></i> Nuevo usuario
            </button>
        </div>
    </div>

    <section class="section">
        <div class="bv-kpi-row">
            <div class="bv-kpi">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico blue"><i class="bi bi-people"></i></span> Usuarios</div>
                </div>
                <div class="bv-kpi-value"><?php echo (int) $totalUsuarios; ?></div>
                <div class="bv-kpi-sub">Registrados en el sistema</div>
            </div>
        </div>

        <div class="bv-panel">
            <h5 class="card-title mb-0 border-0">Listado de usuarios</h5>
            <div class="table-responsive mt-2">
                <table id="table" class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:10px">#</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Account Name</th>
                            <th>Foto</th>
                            <th>Perfil</th>
                            <th>Último login</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (is_array($usuarios)) {
                            foreach ($usuarios as $value) {
                                echo '<tr>
                                  <td>' . htmlspecialchars($value['id'], ENT_QUOTES, 'UTF-8') . '</td>
                                  <td>' . htmlspecialchars($value['nombre'], ENT_QUOTES, 'UTF-8') . '</td>
                                  <td>' . htmlspecialchars($value['usuario'], ENT_QUOTES, 'UTF-8') . '</td>
                                  <td>' . htmlspecialchars($value['customeraccountname'], ENT_QUOTES, 'UTF-8') . '</td>';

                                if ($value['foto'] != '') {
                                    echo '<td><img src="' . htmlspecialchars($value['foto'], ENT_QUOTES, 'UTF-8') . '" class="rounded" width="40" alt=""></td>';
                                } else {
                                    echo '<td><img src="views/img/users/avatar.png" class="rounded" width="40" alt=""></td>';
                                }

                                echo '<td>' . htmlspecialchars($value['perfil'], ENT_QUOTES, 'UTF-8') . '</td>
                                  <td>' . htmlspecialchars($value['ultimo_login'], ENT_QUOTES, 'UTF-8') . '</td>
                                  <td>
                                    <button class="btn btn-warning btn-sm btnEditarUsuario" idUsuario="' . htmlspecialchars($value['id'], ENT_QUOTES, 'UTF-8') . '" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario"><i class="fa fa-pencil"></i></button>
                                  </td>
                                </tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
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

