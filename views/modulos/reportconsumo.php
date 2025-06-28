<?php

include_once 'functions/miaccount.functions.php';
include_once 'functions/reportconsumo.functions.php';
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
                <div class="row g-3" style=" max-width: 100%;">

                    <div class="col-md-6">
                        <label for="inputNumber" class="col-form-label">Fecha Inicio</label>
                        <input type="date" name="fechainicioConsumo" value="<?php if (isset($_POST['fechainicioConsumo'])) {
                                                                            echo $_POST['fechainicioConsumo'];
                                                                        } else {
                                                                            echo date('Y-m-d');
                                                                        } ?>" id="" class="form-control">
                    </div>
                    <input type="hidden" name="archivo" id="accion" value="reportcdr">

                    <div class="col-md-6">
                        <label for="inputNumber" class="col-form-label">Fecha Fin</label>
                        <input type="date" name="fechafinConsumo" value="<?php if (isset($_POST['fechafinConsumo'])) {
                                                                        echo $_POST['fechafinConsumo'];
                                                                    } else {
                                                                        echo date('Y-m-d');
                                                                    } ?>" id="" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="inputEmail5" class="form-label">Numero Solicitado</label>
                        <input type="text" name="callerSolicConsumo" value="<?php if (isset($_POST['callerSolicConsumo'])) {
                                                                    echo $_POST['callerSolicConsumo'];
                                                                } ?>" class="form-control" id="inputEmail5">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                        <!-- <button type="bottom" class="btn btn-secondary" name="accion" value="exportar">Exportar</button> -->
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
                                            <h6>$<?php echo $totalFee; ?></h6>
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
                                    <h5 class="card-title">Tiempo Total (Segundos)<span></span></h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo $totalTime; ?></h6>
                                            <!-- <span class="small pt-1 fw-bold"><?php // echo $porcentajeSet;?></span> <span id="incdis" class="updown small pt-2 ps-1"></span> -->

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        </div>
                        </div>
                        <!-- End Sales Card -->
        <div class="col-lg-12">

<div class="card">
    <div class="card-body">
          <div class="row">
              <h5 class="card-title col-lg-6">Consumo por Linea</h5>
          </div>
        <!-- Table with stripped rows -->
        <table  id="table2" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">Numero Solicitado</th>
                    <th scope="col">Duracion Llamadas (seg)	</th>
                    <th scope="col">Duracion Llamadas</th>
                    <th scope="col">Consumo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $consumototal = 0;
                if(isset($solicData)){
                    foreach ($solicData as $numsolic => $row) {
                        $consumototal += abs($row['fee']);
                        $sum_holdtime = $row['feetime'];

                        $horas = floor($sum_holdtime / 3600);
                        $resto = $sum_holdtime % 3600;
                        $minutos = floor($resto / 60);
                        $segundos = $resto % 60;

                        $fee = number_format($row['fee'], 0, ',', '.');
                        $formathor = $horas . ":" . $minutos . ":" . $segundos;

                        echo '<tr>
                            <td>' . htmlspecialchars($numsolic) . '</td>
                            <td>' . $row['feetime'] . '</td>
                            <td>' . $formathor .'</td>
                            <td>$ ' . $fee . '</td>
                        </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
        <!-- End Table with stripped rows -->

        <!-- Botón Exportar a CSV -->
        <button id="exportar-csv" class="btn btn-secondary">Exportar a CSV</button>
        <script>
        document.getElementById('exportar-csv').addEventListener('click', function () {
            var table = document.getElementById('table2');
            var rows = table.querySelectorAll('tr');
            var csv = [];
            for (var i = 0; i < rows.length; i++) {
                var cols = rows[i].querySelectorAll('th,td');
                var row = [];
                for (var j = 0; j < cols.length; j++) {
                    // Escapa comillas dobles
                    var data = cols[j].innerText.replace(/"/g, '""');
                    row.push('"' + data + '"');
                }
                csv.push(row.join(';'));
            }
            var csvString = csv.join('\n');
            var blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
            var link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "consumo_linea.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
        </script>
        <!-- Fin Botón Exportar a CSV -->
    </div>
</div>

</div>

</div>
</section>

</main>