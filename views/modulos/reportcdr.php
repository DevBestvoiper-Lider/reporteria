<?php

include_once 'functions/miaccount.functions.php';
include_once 'functions/reportcdr.functions.php';


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
                        <form method="post">
                            <div class="row g-3" style=" max-width: 50%;">

                                <div class="col-md-6">
                                    <label for="inputNumber" class="col-form-label">Fecha Inicio</label>
                                    <input type="date" name="fechainicio" value="<?php if (isset($_POST['fechainicio'])) {
                                                                                        echo $_POST['fechainicio'];
                                                                                    } else {
                                                                                        echo date('Y-m-d');
                                                                                    } ?>" id="" class="form-control">
                                </div>
                                <input type="hidden" name="archivo" id="accion" value="reportcdr">

                                <div class="col-md-6">
                                    <label for="inputNumber" class="col-form-label">Fecha Fin</label>
                                    <input type="date" name="fechafin" value="<?php if (isset($_POST['fechafin'])) {
                                                                                    echo $_POST['fechafin'];
                                                                                } else {
                                                                                    echo date('Y-m-d');
                                                                                } ?>" id="" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputEmail5" class="form-label">Numero Mostrado</label>
                                    <input type="text" name="caller" value="<?php if (isset($_POST['caller'])) {
                                                                                echo $_POST['caller'];
                                                                            } ?>" class="form-control" id="inputEmail5">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputEmail5" class="form-label">Numero Marcado</label>
                                    <input type="text" name="callee" value="<?php if (isset($_POST['callee'])) {
                                                                                echo $_POST['callee'];
                                                                            } ?>" class="form-control" id="inputEmail5">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputEmail5" class="form-label">Numero Solicitado</label>
                                    <input type="text" name="callerSolic" value="<?php if (isset($_POST['callerSolic'])) {
                                                                                echo $_POST['callerSolic'];
                                                                            } ?>" class="form-control" id="inputEmail5">
                                </div>
                                
                                <!-- Filtros de duración -->
                                <div class="col-md-12">
                                    <label class="form-label"><strong>Filtros de Duración (en seg)</strong></label>
                                    
                                    <!-- Primer filtro de duración -->
                                    <div class="card mb-3">
                                        <div class="card-body p-3">
                                            <div class="row align-items-center">
                                                <div class="col-md-2">
                                                    <select name="operador_duracion1" class="form-select form-select-sm">
                                                        <option value="">-- Sin filtro --</option>
                                                        <option value=">" <?php if (isset($_POST['operador_duracion1']) && $_POST['operador_duracion1'] == '>') echo 'selected'; ?>>Mayor que</option>
                                                        <option value="<" <?php if (isset($_POST['operador_duracion1']) && $_POST['operador_duracion1'] == '<') echo 'selected'; ?>>Menor que</option>
                                                        <option value="=" <?php if (isset($_POST['operador_duracion1']) && $_POST['operador_duracion1'] == '=') echo 'selected'; ?>>Igual a</option>
                                                        <option value=">=" <?php if (isset($_POST['operador_duracion1']) && $_POST['operador_duracion1'] == '>=') echo 'selected'; ?>>Mayor o igual</option>
                                                        <option value="<=" <?php if (isset($_POST['operador_duracion1']) && $_POST['operador_duracion1'] == '<=') echo 'selected'; ?>>Menor o igual</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" name="duracion_valor1" class="form-control form-control-sm" 
                                                           placeholder="Valor" 
                                                           value="<?php if (isset($_POST['duracion_valor1'])) echo $_POST['duracion_valor1']; ?>">
                                                </div>
                                                <div class="col-md-2" id="duracion_hasta1" style="<?php echo (isset($_POST['operador_duracion1']) && $_POST['operador_duracion1'] == 'BETWEEN') ? '' : 'display:none;'; ?>">
                                                    <input type="number" name="duracion_hasta1" class="form-control form-control-sm" 
                                                           placeholder="Hasta" 
                                                           value="<?php if (isset($_POST['duracion_hasta1'])) echo $_POST['duracion_hasta1']; ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="logica_duracion" value="AND" id="logica_Y" 
                                                               <?php if (!isset($_POST['logica_duracion']) || $_POST['logica_duracion'] == 'AND') echo 'checked'; ?>>
                                                        <label class="form-check-label" for="logica_Y">Y</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="logica_duracion" value="OR" id="logica_O" 
                                                               <?php if (isset($_POST['logica_duracion']) && $_POST['logica_duracion'] == 'OR') echo 'checked'; ?>>
                                                        <label class="form-check-label" for="logica_O">O</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Segundo filtro de duración -->
                                    <div class="card mb-3">
                                        <div class="card-body p-3">
                                            <div class="row align-items-center">
                                                <div class="col-md-2">
                                                    <select name="operador_duracion2" class="form-select form-select-sm">
                                                        <option value="">-- Sin filtro --</option>
                                                        <option value=">" <?php if (isset($_POST['operador_duracion2']) && $_POST['operador_duracion2'] == '>') echo 'selected'; ?>>Mayor que</option>
                                                        <option value="<" <?php if (isset($_POST['operador_duracion2']) && $_POST['operador_duracion2'] == '<') echo 'selected'; ?>>Menor que</option>
                                                        <option value="=" <?php if (isset($_POST['operador_duracion2']) && $_POST['operador_duracion2'] == '=') echo 'selected'; ?>>Igual a</option>
                                                        <option value=">=" <?php if (isset($_POST['operador_duracion2']) && $_POST['operador_duracion2'] == '>=') echo 'selected'; ?>>Mayor o igual</option>
                                                        <option value="<=" <?php if (isset($_POST['operador_duracion2']) && $_POST['operador_duracion2'] == '<=') echo 'selected'; ?>>Menor o igual</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" name="duracion_valor2" class="form-control form-control-sm" 
                                                           placeholder="Valor" 
                                                           value="<?php if (isset($_POST['duracion_valor2'])) echo $_POST['duracion_valor2']; ?>">
                                                </div>
                                                <div class="col-md-2" id="duracion_hasta2" style="<?php echo (isset($_POST['operador_duracion2']) && $_POST['operador_duracion2'] == 'BETWEEN') ? '' : 'display:none;'; ?>">
                                                    <input type="number" name="duracion_hasta2" class="form-control form-control-sm" 
                                                           placeholder="Hasta" 
                                                           value="<?php if (isset($_POST['duracion_hasta2'])) echo $_POST['duracion_hasta2']; ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Filtro adicional (opcional)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Buscar</button>
                                    <button type="bottom" class="btn btn-secondary" name="accion" value="exportar">Exportar</button>
                                </div>
                            </div>
                        </form>


                        <!-- <div id="load"><img src="views/img/logos/load.gif" alt="loading...."></div> -->

                        <div id="datoscontent">
                            <!-- Table with stripped rows -->
                            <table id="table2" class="table table-bordered table-striped">
                                <br>
                                <thead>
                                    <tr>
                                        <th>Numero solicitado</th>
                                        <th>Numero Mostrado</th>
                                        <th>Numero Marcado</th>
                                        <th>Destino Marcado</th>
                                        <th>Servidor Origen</th>
                                        <th>Hora Inicio</th>
                                        <th>Duracion (seg)</th>
                                        <th>Motivo Cuelgue</th>
                                        <th>Costo de la Llamada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if (isset($dataArray)) {

                                        echo '<script>
                    
                                        swal.fire ({
                                            icon: "error",
                                            title: "Solo tendra 500 registros por dia para ver mas use la opcion Exportar!",
                                            showConfirmButton: true,
                                            confirmButtonText: "cerrar",
                                            closeOnConfirm: false
                        
                                        })
                                        </script>';

                                        foreach ($dataArray as $data) {

                                            $timestamp = $data['starttime'] / 1000; // Convertir a segundos
                                            $colombiaOffset = -1 * 3600; // Offset en segundos (5 horas atrás)

                                            $timestampColombia = (int)$timestamp; // Convertir a entero para evitar el warning

                                            $starttime = date("Y-m-d H:i:s", $timestampColombia);

                                            echo '<tr>
                                            <td>' . $data['calleraccesse164'] . '</td>
                                            <td>' . $data['callertogatewaye164'] . '</td>
                                            <td>' . $data['calleee164'] . '</td>
                                            <td>' . $data['areacode'] . '</td>
                                            <td>' . $data['callergatewayid'] . '</td>
                                            <td>' . $starttime . '</td>
                                            <td>' . $data['holdtime'] . '</td>
                                            <td>' . $data['endreason'] . '</td>
                                            <td>$ ' . $data['fee'] . '</td>
                                            </tr>';
                                        }
                                    }

                                    ?>
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<script>
    // Simulación de tiempo de carga (en milisegundos)
    // var tiempoDeCarga = 1000;

    // // Función para mostrar los datos y ocultar el indicador de carga
    // function mostrarDatos() {
    // //   const loadDiv = document.getElementById("load");
    //   var datosContainer = document.getElementById("datoscontent");

    // //   loadDiv.classList.add("hidden");
    //   datosContainer.style.display = "block";
    // }

    // // Simular carga de datos después de un tiempo determinado
    // setTimeout(mostrarDatos, tiempoDeCarga);


    $("#buscar").click(function() {
        var datosContainer = document.getElementById("datoscontent");
        datosContainer.style.display = "block";
    });

    // Función para mostrar/ocultar campo "Hasta" en filtros de duración
    function toggleDuracionHasta(selectElement, hastaElementId) {
        const hastaElement = document.getElementById(hastaElementId);
        if (selectElement.value === 'BETWEEN') {
            hastaElement.style.display = 'block';
        } else {
            hastaElement.style.display = 'none';
        }
    }

    // Event listeners para los selectores de operador de duración
    document.addEventListener('DOMContentLoaded', function() {
        const operador1 = document.querySelector('select[name="operador_duracion1"]');
        const operador2 = document.querySelector('select[name="operador_duracion2"]');
        
        if (operador1) {
            operador1.addEventListener('change', function() {
                toggleDuracionHasta(this, 'duracion_hasta1');
            });
        }
        
        if (operador2) {
            operador2.addEventListener('change', function() {
                toggleDuracionHasta(this, 'duracion_hasta2');
            });
        }
    });
</script>
