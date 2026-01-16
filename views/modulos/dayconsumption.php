<?php 

include_once 'functions/consumption.functions.php';

?>


<main id="main" class="main">

<?php include_once 'views/modulos/includes/ruta.php'; ?>
        <!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
      <div class="col-xxl-4 col-xl-12">

<div class="card info-card revenue-card">

    <div class="card-body">
        <h5 class="card-title">Filtrar</h5>

        <form method="post">
<div class="row g-3"  style=" max-width: 50%;">
    
    <div>
        <label for="inputNumber" class="col-form-label">Consultar Dia</label>        
        <input type="date" name="fechabusc" value="<?php if(isset($_POST['fechabusc'])){echo $_POST['fechabusc']; }else {echo date('Y-m-d');}?>" id="" class="form-control">
    </div>
    <div>
        <label for="tipoTabla" class="col-form-label">Tipo de Reporte</label>        
        <select name="tipoTabla" id="tipoTabla" class="form-select">
            <option value="todas" <?php echo (isset($_POST['tipoTabla']) && $_POST['tipoTabla'] == 'todas') ? 'selected' : ''; ?>>TODO</option>
            <option value="gatewaymapping" <?php echo (isset($_POST['tipoTabla']) && $_POST['tipoTabla'] == 'gatewaymapping') ? 'selected' : (!isset($_POST['tipoTabla']) ? 'selected' : ''); ?>>Gateway Mapping</option>
            <option value="gatewayrouting" <?php echo (isset($_POST['tipoTabla']) && $_POST['tipoTabla'] == 'gatewayrouting') ? 'selected' : ''; ?>>Gateway Routing</option>
            <option value="phone" <?php echo (isset($_POST['tipoTabla']) && $_POST['tipoTabla'] == 'phone') ? 'selected' : ''; ?>>Phone Fee</option>
            <option value="customerclearinglocation" <?php echo (isset($_POST['tipoTabla']) && $_POST['tipoTabla'] == 'customerclearinglocation') ? 'selected' : ''; ?>>Customer Clearing Location Fee</option>
        </select>
    </div>
    <input type="hidden" name="archivo" id="accion" value="reportcdr">
    <div class="text-center">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a class="btn btn-secondary" id="exportar-pdf">Exportar</a>
    </div>
</div>
</form>

    </div>
</div>

</div>
      <div class="col-lg-12">
                    <div class="row" id="elemento-a-exportar">

                    <!-- Customers Card -->

                    <script>
                    document.getElementById('exportar-pdf').addEventListener('click', function () {
                        const elemento = document.getElementById('elemento-a-exportar');
                        const opciones = {
                            scrollX: 0, // Habilita el desplazamiento horizontal si es necesario (ajusta según tus necesidades)
                            scrollY: 0, // Habilita el desplazamiento vertical si es necesario (ajusta según tus necesidades)
                            scale: 2,   // Ajusta la escala para mejorar la calidad de la imagen (ajusta según tus necesidades)
                        };

                        // Captura el elemento y conviértelo a una imagen
                        html2canvas(elemento, opciones).then(function(canvas) {
                            const imgData = canvas.toDataURL('image/png');

                            // Crea un nuevo documento PDF
                            const pdf = new jsPDF('l', 'mm', 'a4');

                            // Divide la imagen en dos partes
                            const pageHeight = 390; // Altura de una página A4 en orientación horizontal
                            const halfHeight = pageHeight / 2;
                            const imgWidth = 250; // Ancho de la imagen
                            const imgHeight = halfHeight - 20; // Altura de la imagen en cada página

                            // Agrega la primera parte de la imagen en la primera página
                            pdf.addImage(imgData, 'PNG', 25, 20, imgWidth, imgHeight);

                            // Agrega un título en la primera página
                            const titulo1 = 'Reporte Consumo Diario';
                            pdf.setTextColor(80, 119, 186); // Establece el color del texto
                            pdf.setFontSize(14); // Establece el tamaño de fuente
                            pdf.text(110, 10, titulo1);

                            // Guarda el archivo PDF resultante
                            pdf.save('archivo.pdf');
                        });
                    });
                    </script>
                      <div id="content2"></div>

                      <!-- Left side columns -->
                      <div class="col-lg-12">
                    <div class="row">

                    <!-- Customers Card -->
                        <div class="col-xxl-4 col-xl-12">

                            <div class="card info-card revenue-card">

                                <div class="card-body">
                                    <h5 class="card-title">Saldo</h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>$<?php echo $saldoactualformat;?></h6>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <!-- End Customers Card -->

                        <!-- Sales Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Saldo Consumido<span></span></h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>$<?php echo $numeroFormateadoTotal2; ?></h6>
                                            <!-- <span class="small pt-1 fw-bold"><?php // echo $porcentajeSet;?></span> <span id="incdis" class="updown small pt-2 ps-1"></span> -->

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                         <!-- Sales Card -->
                         <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">

                                <div class="card-body">
                                    <h5 class="card-title">Tiempo Total <span></span></h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo $formathor2; ?></h6>
                                            <!-- <span class="small pt-1 fw-bold"><?php // echo $porcentajeSet;?></span> <span id="incdis" class="updown small pt-2 ps-1"></span> -->

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        </div>
                        </div>
                        <!-- End Sales Card -->

                        

        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Consumo de Saldo por Servidor</h5>

              <!-- Line Chart -->
              <canvas id="lineChart" style="max-height: 400px;"></canvas>
              <script>
                  var serversarray = <?php echo $serverdayconsum; ?>;
                  var feesarray = <?php echo $feesdayconsum ?>


                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#lineChart'), {
                    type: 'line',
                    data: {
                      labels: serversarray,
                      datasets: [{
                        label: 'SW3',
                        data: feesarray,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                      }]
                    },
                    options: {
                      scales: {
                        y: {
                          beginAtZero: true
                        }
                      }
                    }
                  });
                });
              </script>
              <!-- End Line CHart -->

            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Duracion de Llamadas por Servidor en Segundos</h5>

              <!-- Bar Chart -->
              <canvas id="barChart" style="max-height: 400px;"></canvas>
              <script>

                holdtimedayconsum = <?php echo $feestimedayconsum ?>
                
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#barChart'), {
                    type: 'bar',
                    data: {
                      labels: serversarray,
                      datasets: [{
                         label: 'SW3',
                        data: holdtimedayconsum,
                        backgroundColor: [
                          'rgba(255, 99, 132, 0.2)',
                          'rgba(255, 159, 64, 0.2)',
                          'rgba(255, 205, 86, 0.2)',
                          'rgba(75, 192, 192, 0.2)',
                          'rgba(54, 162, 235, 0.2)',
                          'rgba(153, 102, 255, 0.2)',
                          'rgba(201, 203, 207, 0.2)'
                        ],
                        borderColor: [
                          'rgb(255, 99, 132)',
                          'rgb(255, 159, 64)',
                          'rgb(255, 205, 86)',
                          'rgb(75, 192, 192)',
                          'rgb(54, 162, 235)',
                          'rgb(153, 102, 255)',
                          'rgb(201, 203, 207)'
                        ],
                        borderWidth: 1
                      }]
                    },
                    options: {
                      scales: {
                        y: {
                          beginAtZero: true
                        }
                      }
                    }
                  });
                });
              </script>
              <!-- End Bar CHart -->

            </div>
          </div>
        </div>


        <div class="col-lg-12">

<div class="card">
    <div class="card-body">
          <div class="row">
              <h5 class="card-title col-lg-6">Consumo por Servidor</h5>
              <form method="post" class="col-lg-6 exportconsum">
              <div>      
                  <input type="hidden" name="fechabusc" value="<?php if(isset($_POST['fechabusc'])){echo $_POST['fechabusc']; }else {echo date('Y-m-d');}?>" id="" class="form-control">
              </div>
                <button type="submit" name="exportarDay" class="btn btn-primary">Exportar</button>
              </form>
          </div>
        <!-- Table with stripped rows -->
        <table  id="table2" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">Ip</th>
                    <th scope="col">Servidor Origen</th>
                    <th scope="col">Duracion Llamadas (seg)	</th>
                    <th scope="col">Duracion Llamadas</th>
                    <th scope="col">Consumo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                $consumototal = 0;

                if(isset($dataArray)){
                  foreach ($dataArray as $row) {
                

                    $consumototal += abs($row['fee']);
                    $sum_holdtime = $row['feetime'];

                    $horas = floor($sum_holdtime / 3600); // Calcula las horas completas
                    $sum_holdtime %= 3600; // Obtiene los segundos restantes
                    $minutos = floor($sum_holdtime / 60); // Calcula los minutos completos
                    $sum_holdtime %= 60; // Obtiene los segundos restantes

                    $fee = number_format($row['fee'], 0, ',', '.');

                    $formathor = $horas . ":" . $minutos . ":" . $sum_holdtime;

                    echo '<tr>
                    <td>' . $row['ip'] . '</td>
                    <td>' . $row['gatewayid'] . '</td>
                    <td>' . $row['feetime'] . '</td>
                    <td>' . $formathor .'</td>
                    <td>$ ' . $fee . '</td>
                    </tr>';
            }

            $numeroFormateadoTotal = number_format($consumototal, 0, ',', '.');

            echo '<tr scope="col"><td></td>
            <td></td>
            <td></td>
            <td class="bold">Total</td>
            <td class="bold">'.$numeroFormateadoTotal.'</td>
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

  </main><!-- End #main -->