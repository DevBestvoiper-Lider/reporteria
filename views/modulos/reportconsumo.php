<?php
include_once 'functions/miaccount.functions.php';
include_once 'functions/reportconsumo.functions.php';

$fechaInicio = isset($_POST['fechainicioConsumo']) ? $_POST['fechainicioConsumo'] : date('Y-m-d');
$fechaFin = isset($_POST['fechafinConsumo']) ? $_POST['fechafinConsumo'] : date('Y-m-d');
$callerSolic = isset($_POST['callerSolicConsumo']) ? $_POST['callerSolicConsumo'] : '';
?>

<main id="main" class="main">

    <div class="bv-page-head">
        <div>
            <div class="bv-crumb">Inicio / Consumo por línea</div>
            <h1>Consumo por línea</h1>
            <p>Consumo y duración agrupados por número solicitado.</p>
        </div>
        <div class="bv-actions">
            <span class="bv-date-pill"><i class="bi bi-calendar3"></i> <?php echo htmlspecialchars($fechaInicio, ENT_QUOTES, 'UTF-8'); ?> – <?php echo htmlspecialchars($fechaFin, ENT_QUOTES, 'UTF-8'); ?></span>
            <a class="bv-export" href="#" id="exportar-csv"><i class="bi bi-download"></i> Exportar CSV</a>
        </div>
    </div>

    <section class="section">
        <form method="post" class="bv-panel bv-filters mb-3">
            <h5 class="card-title">Filtros</h5>
            <input type="hidden" name="archivo" id="accion" value="reportcdr">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" name="fechainicioConsumo" value="<?php echo htmlspecialchars($fechaInicio, ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" name="fechafinConsumo" value="<?php echo htmlspecialchars($fechaFin, ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Número solicitado</label>
                    <input type="text" name="callerSolicConsumo" value="<?php echo htmlspecialchars($callerSolic, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" placeholder="Opcional">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                </div>
            </div>
        </form>

        <div id="elemento-a-exportar">
            <div class="bv-kpi-row">
                <div class="bv-kpi">
                    <div class="bv-kpi-head">
                        <div class="left"><span class="bv-kpi-ico green"><i class="bi bi-wallet2"></i></span> Saldo</div>
                    </div>
                    <div class="bv-kpi-value">$<?php echo $saldoactualformat; ?></div>
                </div>
                <div class="bv-kpi">
                    <div class="bv-kpi-head">
                        <div class="left"><span class="bv-kpi-ico purple"><i class="bi bi-graph-up"></i></span> Consumo del periodo</div>
                    </div>
                    <div class="bv-kpi-value">$<?php echo $totalFeeFormat; ?></div>
                    <div class="bv-kpi-sub"><?php echo (int) $totalLineas; ?> líneas</div>
                </div>
                <div class="bv-kpi">
                    <div class="bv-kpi-head">
                        <div class="left"><span class="bv-kpi-ico orange"><i class="bi bi-clock"></i></span> Tiempo total</div>
                    </div>
                    <div class="bv-kpi-value"><?php echo $totalTimeFormat; ?></div>
                    <div class="bv-kpi-sub"><?php echo number_format($totalTime, 0, ',', '.'); ?> segundos</div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-lg-7">
                    <div class="bv-panel">
                        <h5 class="card-title">Consumo por línea</h5>
                        <canvas id="lineFeeChart" style="max-height: 320px;"></canvas>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="bv-panel">
                        <h5 class="card-title">Participación del consumo</h5>
                        <div id="linePieChart" style="min-height: 280px;"></div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="bv-panel">
                        <h5 class="card-title">Duración de llamadas por línea</h5>
                        <canvas id="lineTimeChart" style="max-height: 280px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="bv-panel">
                <h5 class="card-title">Detalle por línea</h5>
                <div class="table-responsive">
                    <table id="table2" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Número solicitado</th>
                                <th>Duración (seg)</th>
                                <th>Duración</th>
                                <th>Consumo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($solicData)) {
                                foreach ($solicData as $numsolic => $row) {
                                    $sum_holdtime = $row['feetime'];
                                    $horas = floor($sum_holdtime / 3600);
                                    $resto = $sum_holdtime % 3600;
                                    $minutos = floor($resto / 60);
                                    $segundos = $resto % 60;
                                    $fee = NumberHelper::formatFeeClean($row['fee']);
                                    $formathor = $horas . ':' . $minutos . ':' . $segundos;
                                    echo '<tr>
                                        <td>' . htmlspecialchars($numsolic, ENT_QUOTES, 'UTF-8') . '</td>
                                        <td>' . $row['feetime'] . '</td>
                                        <td>' . $formathor . '</td>
                                        <td>$ ' . $fee . '</td>
                                    </tr>';
                                }
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
    document.getElementById('exportar-csv').addEventListener('click', function (e) {
        e.preventDefault();
        var table = document.getElementById('table2');
        var rows = table.querySelectorAll('tr');
        var csv = [];
        for (var i = 0; i < rows.length; i++) {
            var cols = rows[i].querySelectorAll('th,td');
            var row = [];
            for (var j = 0; j < cols.length; j++) {
                var data = cols[j].innerText.replace(/"/g, '""');
                row.push('"' + data + '"');
            }
            csv.push(row.join(';'));
        }
        var blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'consumo_linea.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    var lineLabels = <?php echo $lineLabelsJson; ?>;
    var lineFees = <?php echo $lineFeesJson; ?>;
    var lineTimes = <?php echo $lineTimesJson; ?>;
    var consumoPie = <?php echo $consumoPieJson; ?>;

    document.addEventListener('DOMContentLoaded', function () {
        new Chart(document.querySelector('#lineFeeChart'), {
            type: 'bar',
            data: {
                labels: lineLabels,
                datasets: [{
                    label: 'Consumo',
                    data: lineFees,
                    backgroundColor: 'rgba(37, 99, 235, 0.25)',
                    borderColor: '#2563eb',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                scales: { x: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });

        new Chart(document.querySelector('#lineTimeChart'), {
            type: 'bar',
            data: {
                labels: lineLabels,
                datasets: [{
                    label: 'Segundos',
                    data: lineTimes,
                    backgroundColor: 'rgba(249, 115, 22, 0.25)',
                    borderColor: '#f97316',
                    borderWidth: 1
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });

        echarts.init(document.querySelector('#linePieChart')).setOption({
            tooltip: { trigger: 'item', formatter: '{b}: ${c} ({d}%)' },
            legend: { type: 'scroll', bottom: 0 },
            color: ['#2563eb', '#f97316', '#22c55e', '#ef4444', '#a855f7', '#14b8a6', '#eab308', '#64748b'],
            series: [{
                name: 'Consumo',
                type: 'pie',
                radius: ['48%', '72%'],
                itemStyle: { borderRadius: 6, borderColor: '#fff', borderWidth: 2 },
                label: { show: false },
                data: consumoPie.length ? consumoPie : [{ name: 'Sin datos', value: 0 }]
            }]
        });
    });
</script>
