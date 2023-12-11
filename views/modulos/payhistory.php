<?php

include_once 'functions/payhistory.functions.php';


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
                            <div class="row g-3"  style=" max-width: 50%;">
                                
                                <div class="col-md-6">
                                    <label for="inputNumber" class="col-form-label">Fecha Inicio</label>        
                                    <input type="date" name="fechainicio" value="<?php if(isset($_POST['fechainicio'])){echo $_POST['fechainicio']; }else {echo date('Y-m-d');}?>" id="" class="form-control">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="inputNumber" class="col-form-label">Fecha Fin</label>  
                                    <input type="date" name="fechafin" value="<?php if(isset($_POST['fechafin'])){echo $_POST['fechafin']; }else {echo date('Y-m-d');}?>" id="" class="form-control">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Buscar</button>
                                    <button type="bottom" class="btn btn-secondary" name="accion" value="exportarPagos">Exportar</button>
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
                                <th>Fecha Pago</th>
                                <th>Cuenta</th>
                                <th>Monto</th>
                                <th>Monto Después de la Carga</th>
                                <th>Usuario</th>
                                <th>Observacion</th>
                              </tr>
                            </thead>
                            <tbody>
                                    <?php

                                    if(isset($dataArray)) {

                                        // echo '<script>
                    
                                        // swal.fire ({
                                        //     icon: "error",
                                        //     title: "Solo tendra 500 registros por dia para ver mas use la opcion Exportar!",
                                        //     showConfirmButton: true,
                                        //     confirmButtonText: "cerrar",
                                        //     closeOnConfirm: false
                        
                                        // })
                                        // </script>';
                        

                                        foreach ($dataArray as $data) {
                                        
                                            $timestamp = $data['time'] / 1000; // Convertir a segundos
                                            $colombiaOffset = -7 * 3600; // Offset en segundos (5 horas atrás)
                                            
                                            $timestampColombia = $timestamp + $colombiaOffset;
                                            
                                            $starttime = date("Y-m-d H:i:s", $timestampColombia);

                                            $PagoFormateado = number_format($data['paymoney'], 2, ',', '.');

                                            $numeroFormateado = number_format($data['customermoney'], 2);
                                        
                                            echo '<tr>
                                            <td>' . $starttime . '</td>
                                            <td>' . $data['customeraccount'] . '</td>
                                            <td>$ ' . $PagoFormateado . '</td>
                                            <td>$ ' . $numeroFormateado . '</td>
                                            <td>' . $data['loginname'] . '</td>
                                            <td>' . $data['memo'] . '</td>
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


document.addEventListener("DOMContentLoaded", function () {
        var exportarBtn = document.getElementById("exportarBtn");
        exportarBtn.addEventListener("click", function () {
            // Llama a la función exportar() cuando se hace clic en el botón
            document.getElementById('accion').value = 'exportarcdr.php';

            // Envía el formulario
            document.getElementById('redireccionForm').submit();
        });
    });


$("#buscar").click(function() {
    var datosContainer = document.getElementById("datoscontent");
    datosContainer.style.display = "block";
});
</script>