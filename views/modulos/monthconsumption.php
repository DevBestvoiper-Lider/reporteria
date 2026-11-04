<?php
include_once 'functions/monthconsumption.functions.php';

$fechaInicio = isset($_POST['FechaInicio']) ? $_POST['FechaInicio'] : date('Y-m-d');
$fechaFin = isset($_POST['FechaFin']) ? $_POST['FechaFin'] : date('Y-m-d');
$tipoTablaSel = isset($_POST['tipoTabla']) ? $_POST['tipoTabla'] : 'gatewaymapping';
if (!isset($legendServidores)) {
    $legendServidores = array();
}
if (!isset($paletteJson)) {
    $paletteJson = '[]';
}
?>

<main id="main" class="main">

    <div class="bv-page-head">
        <div>
            <div class="bv-crumb">Inicio / Consumo mensual</div>
            <h1>Consumo mensual</h1>
            <p>Saldo, tiempo y consumo por servidor en el rango consultado.</p>
        </div>
        <div class="bv-actions">
            <span class="bv-date-pill"><i class="bi bi-calendar3"></i> <?php echo htmlspecialchars($fechaInicio, ENT_QUOTES, 'UTF-8'); ?> – <?php echo htmlspecialchars($fechaFin, ENT_QUOTES, 'UTF-8'); ?></span>
            <a class="bv-export" href="#" id="exportar-pdf"><i class="bi bi-download"></i> Exportar</a>
        </div>
    </div>

    <section class="section">
        <form method="post" class="bv-panel bv-filters mb-3">
            <input type="hidden" name="archivo" id="accion" value="reportcdr">
            <div class="bv-filter-split">
                <div>
                    <span class="bv-filter-label">Rango de fechas</span>
                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <input type="date" name="FechaInicio" value="<?php echo htmlspecialchars($fechaInicio, ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
                        </div>
                        <div class="col-12">
                            <input type="date" name="FechaFin" value="<?php echo htmlspecialchars($fechaFin, ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search"></i> Buscar</button>
                </div>
                <div>
                    <span class="bv-filter-label">Tipo de reporte</span>
                    <?php include 'views/modulos/includes/tipo-pills.php'; ?>
                </div>
            </div>
        </form>

        <div id="elemento-a-exportar">
            <div class="bv-kpi-row">
                <div class="bv-kpi">
                    <div class="bv-kpi-head">
                        <div class="left"><span class="bv-kpi-ico peach"><i class="bi bi-wallet2"></i></span> Saldo</div>
                    </div>
                    <div class="bv-kpi-value <?php echo $saldoNegativo ? 'neg' : ''; ?>"><?php echo $saldoMostrar; ?></div>
                    <div class="bv-kpi-sub"><?php echo $saldoNegativo ? 'Saldo en contra' : 'Saldo disponible'; ?></div>
                </div>
                <div class="bv-kpi">
                    <div class="bv-kpi-head">
                        <div class="left"><span class="bv-kpi-ico blue"><i class="bi bi-graph-up"></i></span> Total saldo consumido</div>
                    </div>
                    <div class="bv-kpi-value pos-blue"><?php echo $consumoMostrar; ?></div>
                    <div class="bv-kpi-sub"><?php echo htmlspecialchars($tiposReporte[$tipoTablaSel], ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
                <div class="bv-kpi">
                    <div class="bv-kpi-head">
                        <div class="left"><span class="bv-kpi-ico green"><i class="bi bi-clock"></i></span> Tiempo total</div>
                    </div>
                    <div class="bv-kpi-value"><?php echo $formathor2; ?></div>
                    <div class="bv-kpi-sub"><?php echo $minutosMostrar; ?> minutos</div>
                </div>
            </div>
            <div class="bv-kpi-row" style="grid-template-columns: minmax(240px, 1fr) 2fr;">
                <div class="bv-kpi">
                    <div class="bv-kpi-head">
                        <div class="left"><span class="bv-kpi-ico yellow"><i class="bi bi-tag"></i></span> Costo promedio / min</div>
                    </div>
                    <div class="bv-kpi-value"><?php echo $costoMostrar; ?></div>
                    <div class="bv-kpi-sub"><?php echo (int) $servidoresTrafico; ?> servidores con tráfico</div>
                </div>
            </div>

            <div class="bv-panel mb-3">
                <h5 class="card-title mb-0 border-0">Consumo de saldo por servidor</h5>
                <p class="bv-chart-sub">Participación del costo del periodo</p>
                <div class="bv-donut-layout">
                    <div>
                        <canvas id="donutChart" style="max-height: 260px;"></canvas>
                    </div>
                    <div>
                        <?php
                        if (count($legendServidores) === 0) {
                            echo '<p class="bv-kpi-sub mb-0">Sin tráfico para el rango consultado.</p>';
                        }
                        foreach ($legendServidores as $item) {
                            echo '<div class="bv-legend-row">
                                <div class="bv-legend-name"><span class="bv-legend-dot" style="background:' . htmlspecialchars($item['color'], ENT_QUOTES, 'UTF-8') . '"></span>' . htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') . '</div>
                                <div class="bv-legend-meta"><span>$' . number_format($item['fee'], 0, ',', '.') . '</span><strong>' . (int) $item['pct'] . '%</strong></div>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="bv-panel mb-3">
                <h5 class="card-title mb-0 border-0">Duración de llamadas por servidor</h5>
                <p class="bv-chart-sub">Tiempo total en segundos</p>
                <canvas id="barChart" style="max-height: 280px;"></canvas>
            </div>

            <div class="bv-panel">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                    <h5 class="card-title mb-0 border-0">Consumo por servidor</h5>
                    <form method="post">
                        <input type="hidden" name="fechainicio" value="<?php echo htmlspecialchars($fechaInicio, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="fechafin" value="<?php echo htmlspecialchars($fechaFin, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="tipoTabla" value="<?php echo htmlspecialchars($tipoTablaSel, ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" name="exportarMonth" class="btn btn-secondary btn-sm">Exportar datos</button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table id="table2" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>IP</th>
                                <th>Servidor origen</th>
                                <th>Duración (seg)</th>
                                <th>Duración</th>
                                <th>Consumo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $consumototal = 0;
                            if (isset($datosAgrupados)) {
                                foreach ($datosAgrupados as $ip => $gateways) {
                                    foreach ($gateways as $gateway => $data) {
                                        $sum_holdtime = $data['sum_holdtime'];
                                        $sum_fee = $data['sum_fee'];
                                        $numeroFormateado = NumberHelper::formatFeeClean(abs($sum_fee));
                                        $horas = floor($sum_holdtime / 3600);
                                        $sum_holdtime %= 3600;
                                        $minutos = floor($sum_holdtime / 60);
                                        $sum_holdtime %= 60;
                                        $formathor = $horas . ':' . $minutos . ':' . $sum_holdtime;
                                        echo '<tr>
                                        <td>' . htmlspecialchars($ip, ENT_QUOTES, 'UTF-8') . '</td>
                                        <td>' . htmlspecialchars($gateway, ENT_QUOTES, 'UTF-8') . '</td>
                                        <td>' . htmlspecialchars($data['sum_holdtime'], ENT_QUOTES, 'UTF-8') . '</td>
                                        <td>' . $formathor . '</td>
                                        <td>$ ' . $numeroFormateado . '</td>
                                        </tr>';
                                        $consumototal += $sum_fee;
                                    }
                                }
                                $consumototalformat = NumberHelper::formatFeeClean(abs($consumototal));
                                echo '<tr>
                                <td></td><td></td><td></td>
                                <td class="bold">Total</td>
                                <td class="bold">$ ' . $consumototalformat . '</td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    document.getElementById('exportar-pdf').addEventListener('click', function (e) {
        e.preventDefault();
        var elemento = document.getElementById('elemento-a-exportar');
        html2canvas(elemento, { scrollX: 0, scrollY: 0, scale: 2 }).then(function (canvas) {
            var imgData = canvas.toDataURL('image/png');
            var pdf = new jsPDF('l', 'mm', 'a4');
            pdf.setTextColor(80, 119, 186);
            pdf.setFontSize(14);
            pdf.text(110, 10, 'Reporte Consumo Mensual');
            pdf.addImage(imgData, 'PNG', 25, 20, 250, 175);
            pdf.save('consumo-mensual.pdf');
        });
    });

    var serversarray = <?php echo $serverdayconsum; ?>;
    var feesarray = <?php echo $feesdayconsum; ?>;
    var holdtimedayconsum = <?php echo $feestimedayconsum; ?>;
    var palette = <?php echo $paletteJson; ?>;
    var consumoTotalLabel = <?php echo json_encode($consumoMostrar); ?>;

    document.addEventListener('DOMContentLoaded', function () {
        var donutEl = document.querySelector('#donutChart');
        if (donutEl) {
            new Chart(donutEl, {
                type: 'doughnut',
                data: {
                    labels: serversarray,
                    datasets: [{
                        data: feesarray,
                        backgroundColor: palette,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    cutout: '72%',
                    plugins: { legend: { display: false } }
                },
                plugins: [{
                    id: 'centerText',
                    afterDraw: function (chart) {
                        var meta = chart.getDatasetMeta(0);
                        if (!meta.data.length) return;
                        var ctx = chart.ctx;
                        var x = meta.data[0].x;
                        var y = meta.data[0].y;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillStyle = '#64748b';
                        ctx.font = '12px Inter, sans-serif';
                        ctx.fillText('Total', x, y - 12);
                        ctx.fillStyle = '#0f172a';
                        ctx.font = '700 18px Inter, sans-serif';
                        ctx.fillText(consumoTotalLabel, x, y + 10);
                        ctx.restore();
                    }
                }]
            });
        }

        new Chart(document.querySelector('#barChart'), {
            type: 'bar',
            data: {
                labels: serversarray,
                datasets: [{
                    label: 'Segundos',
                    data: holdtimedayconsum,
                    backgroundColor: palette,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, grid: { color: '#eef2f7' } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
