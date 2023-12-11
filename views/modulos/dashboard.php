<?php
    include_once 'functions/dashboard.functions.php';
?>

<main id="main" class="main">

        <?php include_once 'views/modulos/includes/ruta.php'; ?>
        <!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-8">
                    <div class="row" id="content">

                    <!-- Customers Card -->
                        <div class="col-xxl-4 col-xl-12">

                            <div class="card info-card revenue-card">

                                <div class="card-body" id="saldo">
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

                                <div class="card-body" id="hoy">
                                    <h5 class="card-title">Consumo <span>| Hoy</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6 id="consumoValor">$<?php echo $consumohoyformat; ?></h6>
                                            <span class="small pt-1 fw-bold" id="porcentaje"><?php echo $porcentajeSet;?></span> <span id="incdis" class="updown small pt-2 ps-1"></span>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- End Sales Card -->

                        <!-- Revenue Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">
                                <div class="card-body" id="mes">
                                    <h5 class="card-title">Consumo <span>| Este Mes</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>$<?php echo $consumoEsteMesFormat;?></h6>
                                            <span class="small pt-1 fw-bold" id="porcentaje2"><?php echo $porcentajeSetMes;?></span><span id="incdis2" class="updown small pt-2 ps-1"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- End Revenue Card -->

                        <!-- Reports -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Reporte <span>/Este Mes</span></h5>

                                    <!-- Line Chart -->
                                    <div id="reportsChart"></div>

                                    <script>

                                        var fechasArray = <?php echo $fechas; ?>;

                                        var data = <?php echo $feemes; ?>
                                        
                                        document.addEventListener("DOMContentLoaded", () => {
                                            new ApexCharts(document.querySelector("#reportsChart"), {
                                                series: [{
                                                    name: 'Sw3',
                                                    data: data,
                                                }],
                                                chart: {
                                                    height: 350,
                                                    type: 'area',
                                                    toolbar: {
                                                        show: false
                                                    },
                                                },
                                                markers: {
                                                    size: 4
                                                },
                                                colors: ['#4154f1', '#2eca6a', '#ff771d'],
                                                fill: {
                                                    type: "gradient",
                                                    gradient: {
                                                        shadeIntensity: 1,
                                                        opacityFrom: 0.3,
                                                        opacityTo: 0.4,
                                                        stops: [0, 90, 100]
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                stroke: {
                                                    curve: 'smooth',
                                                    width: 2
                                                },
                                                xaxis: {
                                                    type: 'date',
                                                    categories: fechasArray
                                                },
                                                tooltip: {
                                                    x: {
                                                        format: 'dd/MM/yy'
                                                    },
                                                }
                                            }).render();
                                        });
                                    </script>
                                    <!-- End Line Chart -->

                                </div>

                            </div>
                        </div>
                        <!-- End Reports -->

                        <!-- Recent Sales -->
                        <div class="col-12">
                            <div class="card recent-sales overflow-auto">

                                <!-- <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="#">Today</a></li>
                                        <li><a class="dropdown-item" href="#">This Month</a></li>
                                        <li><a class="dropdown-item" href="#">This Year</a></li>
                                    </ul>
                                </div> -->

                                <div class="card-body" id="llamadasrea">
                                    <h5 class="card-title">Consumo por Servidor <span>| Hoy</span></h5>

                                    <table class="table table-borderless datatable">
                                        <thead>
                                            <tr>
                                                <th scope="col">Ip Servidor</th>
                                                <th scope="col">Servidor Origen</th>
                                                <!-- <th scope="col">Llamadas ealizadas</th> -->
                                                <th scope="col">Duracion Llamadas (seg)</th>
                                                <th scope="col">Costo Consumo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php

                                                $consumototal = 0;

                                                foreach ($datosAgrupados as $ip => $gateways) {
                                                    foreach ($gateways as $gateway => $data) {
                                                        $sum_holdtime = $data['sum_holdtime'];
                                                        $sum_fee = $data['sum_fee'];

                                                        $sum_feeTotal = $data['sum_fee'];

                                                        $numeroFormateado = number_format($sum_fee, 2);

                                                        echo '<tr>
                                                        <td>' . $ip . '</td>
                                                        <td>' . $gateway . '</td>
                                                        <td>' . $sum_holdtime . '</td>
                                                        <td>$ ' . $numeroFormateado . '</td>
                                                        </tr>';


                                                        if(isset($numeroFormateado)){
                                                            $consumototal += $sum_feeTotal;
                                                        }
                                                    }
                                                }

                                                $consumototalFormat = number_format($consumototal, 2, ',', '.');
                                                
                                                echo '<tr scope="col"><td></td>
                                                <td></td>
                                                <td class="bold">Total</td>
                                                <td class="bold">'.$consumototalFormat.'</td>
                                                </tr>';
                                                

                                           ?>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                        <!-- End Recent Sales -->

                    </div>
                </div>
                <!-- End Left side columns -->

                <!-- Right side columns -->
                <div class="col-lg-4">

                    <!-- Recent Activity -->
                    <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Pagos recientes <span>| Hoy</span></h5>

                            <table class="table table-borderless datatable">
                                        <thead>
                                            <tr>
                                                <th scope="col">fecha</th>
                                                <th scope="col">monto</th>
                                                <th scope="col">usuario</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php

                                                if(isset($tuArray)) {
                                                    
                                                    foreach ($tuArray as $data) {
                                                    
                                                        $timestamp = $data['time'] / 1000; // Convertir a segundos
                                                        $colombiaOffset = -7 * 3600; // Offset en segundos (5 horas atrás)
                                                        
                                                        $timestampColombia = $timestamp + $colombiaOffset;
                                                        
                                                        $starttime = date("Y-m-d H:i:s", $timestampColombia);

                                                        $PagoFormateado = number_format($data['paymoney'], 2, ',', '.');

                                                        $numeroFormateado = number_format($data['customermoney'], 2);
                                                    
                                                        echo '<tr>
                                                        <td>' . $starttime . '</td>
                                                        <td>$ ' . $PagoFormateado . '</td>
                                                        <td>' . $data['loginname'] . '</td>
                                                        </tr>';
                                                    
                                                    }
                                                }
                                            
                                            ?>
                                        </tbody>
                                    </table>


                        </div>
                    </div>
                    <!-- End Recent Activity -->

                    <!-- Website Traffic -->
                    <div class="card">
                        <div class="card-body pb-0">
                            <h5 class="card-title">Consumo por Codigo de Pais <span>| Hoy</span></h5>

                            <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

                            <script>

                                data2 = <?php echo $ConsumAreaCode ?>;

                                console.log(data2);

                                document.addEventListener("DOMContentLoaded", () => {
                                    echarts.init(document.querySelector("#trafficChart")).setOption({
                                        tooltip: {
                                            trigger: 'item'
                                        },
                                        legend: {
                                            top: '5%',
                                            left: 'center'
                                        },
                                        series: [{
                                            name: 'Access From',
                                            type: 'pie',
                                            radius: ['40%', '70%'],
                                            avoidLabelOverlap: false,
                                            label: {
                                                show: false,
                                                position: 'center'
                                            },
                                            emphasis: {
                                                label: {
                                                    show: true,
                                                    fontSize: '18',
                                                    fontWeight: 'bold'
                                                }
                                            },
                                            labelLine: {
                                                show: false
                                            },
                                            data: [data2]
                                        }]
                                    });
                                });
                            </script>

                        </div>
                    </div>
                    <!-- End Website Traffic -->

                </div>
                <!-- End Right side columns -->

            </div>
        </section>

    </main>