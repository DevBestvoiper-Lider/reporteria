<?php
$active = "Administrar Anti-Hack";
$valor = null;
$item = null;
$ips = ControllersFirewall::ctrMostrarIps($item, $valor);
$totalIps = is_array($ips) ? count($ips) : 0;
?>

<main id="main" class="main">

    <div class="bv-page-head">
        <div>
            <div class="bv-crumb">Inicio / Anti-hack</div>
            <h1>Anti-hack</h1>
            <p>IPs permitidas para acceder al portal.</p>
        </div>
        <div class="bv-actions">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarIP">
                <i class="bi bi-plus-lg"></i> Agregar IP
            </button>
        </div>
    </div>

    <section class="section">
        <div class="bv-kpi-row">
            <div class="bv-kpi">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico blue"><i class="bi bi-shield-check"></i></span> IPs autorizadas</div>
                </div>
                <div class="bv-kpi-value"><?php echo (int) $totalIps; ?></div>
                <div class="bv-kpi-sub">Registros activos</div>
            </div>
        </div>

        <div class="bv-panel">
            <h5 class="card-title mb-0 border-0">Listado de IPs</h5>
            <div class="table-responsive mt-2">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:80px">#</th>
                            <th>IP</th>
                            <th style="width:140px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $countip = 1;
                        if (is_array($ips)) {
                            foreach ($ips as $value) {
                                echo '<tr>
                                  <td>' . $countip++ . '</td>
                                  <td>' . htmlspecialchars($value['ip_address'], ENT_QUOTES, 'UTF-8') . '</td>
                                  <td>
                                    <button class="btn btn-danger btn-sm btnEliminarIP" idIp="' . htmlspecialchars($value['id'], ENT_QUOTES, 'UTF-8') . '"><i class="fa fa-times"></i></button>
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