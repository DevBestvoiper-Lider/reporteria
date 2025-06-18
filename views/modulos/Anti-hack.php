<?php

$active = "Administrar Anti-Hack";

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

                          <div class="box">
                            <div class="box-header with-border">

                              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarIP">

                                Agregar IP

                              </button>

                            </div>
                            </div>

                            <!-- Table with stripped rows -->
                            <table class="table table-bordered">
                            <br>
                            <thead>
                              <tr>
                                <th style="width:200px"></th>
                                <th>count</th>
                                <th>ip</th>
                                <th style="width:200px">Acciones</th>
                              </tr>
                            </thead>
                                <tbody>
                                <?php 

                                  $valor = null;
                                  $item = null;
                                  $usuarios = ControllersFirewall::ctrMostrarIps($item, $valor);

                                  $countip = 1;

                                  foreach ($usuarios as $key => $value){
                                    echo '
                                    <tr>
                                    <td><input type="checkbox" ></input></td>
                                      <td>'. $countip++ .'</td>
                                      <td>'.$value["ip_address"].'</td>'
                                      ;
                                      echo '<td>

                                        <div class="btn-group">
                                            <botton class="btn btn-danger btnEliminarIP" idIp="'.$value["id"].'"><i class="fa fa-times"></i></botton>
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


<!-- MODAL EDITAR USUARIO -->

<!-- Large Modal -->
<div class="modal fade" id="modalAgregarIP" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

    <!-- MODAL HEADER -->
      <div class="modal-header">
        <h5 class="modal-title">Agregar ip</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- MODAL BODY -->
      <div class="modal-body">
      <form role="form" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="Nuevaip" placeholder="Ingresar ip" required>
                  </div>
                </div>
                      <!-- MODAL FOOTER -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                  <button type="submit" class="btn btn-primary">Guardar ip</button>
                </div>

                <?php

                  $NuevaIP = new ControllersFirewall();
                  $NuevaIP -> ctrAgregarIP();

                ?>
              </form>
      </div>

    </div>
  </div>
</div><!-- End Large Modal-->

<?php
$borrarIP = new ControllersFirewall();
$borrarIP -> ctrBorrarIP();
?>