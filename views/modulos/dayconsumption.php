<?php
include_once 'functions/consumption.functions.php';

$fechaDia = isset($_POST['fechabusc']) ? $_POST['fechabusc'] : date('Y-m-d');
$tipoTablaSel = isset($_POST['tipoTabla']) ? $_POST['tipoTabla'] : 'gatewaymapping';
?>

<main id="main" class="main">

    <div class="bv-page-head">
        <div>
            <div class="bv-crumb">Inicio / Consumo diario</div>
            <h1>Consumo diario</h1>
            <p>Saldo, tiempo y consumo por servidor para el día consultado.</p>
        </div>
        <div class="bv-actions">
            <span class="bv-date-pill"><i class="bi bi-calendar3"></i> <?php echo htmlspecialchars($fechaDia, ENT_QUOTES, 'UTF-8'); ?></span>
            <a class="bv-export" href="#" id="exportar-pdf"><i class="bi bi-download"></i> Exportar</a>
        </div>
    </div>

    <section class="section">
        <form method="post" class="bv-panel bv-filters mb-3">
            <h5 class="card-title">Filtros</h5>
            <input type="hidden" name="archivo" id="accion" value="reportcdr">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Consultar día</label>
                    <input type="date" name="fechabusc" value="<?php echo htmlspecialchars($fechaDia, ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipo de reporte</label>
                    <select name="tipoTabla" id="tipoTabla" class="form-select">
                        <option value="todas" <?php echo ($tipoTablaSel == 'todas') ? 'selected' : ''; ?>>Todas</option>
                        <option value="gatewaymapping" <?php echo ($tipoTablaSel == 'gatewaymapping') ? 'selected' : ''; ?>>Gateway Mapping</option>
                        <option value="gatewayrouting" <?php echo ($tipoTablaSel == 'gatewayrouting') ? 'selected' : ''; ?>>Gateway Routing</option>
                        <option value="phone" <?php echo ($tipoTablaSel == 'phone') ? 'selected' : ''; ?>>Phone Fee</option>
                        <option value="customerclearinglocation" <?php echo ($tipoTablaSel == 'customerclearinglocation') ? 'selected' : ''; ?>>Customer Clearing Location</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Buscar</button>
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
                        <div class="left"><span class="bv-kpi-ico purple"><i class="bi bi-graph-up"></i></span> Consumo del día</div>
                    </div>
                    <div class="bv-kpi-value">$<?php echo $numeroFormateadoTotal2; ?></div>
                </div>
                <div class="bv-kpi">
                    <div class="bv-kpi-head">
                        <div class="left"><span class="bv-kpi-ico orange"><i class="bi bi-clock"></i></span> Tiempo total</div>
                    </div>
                    <div class="bv-kpi-value"><?php echo $formathor2; ?></div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-lg-6">
                    <div class="bv-panel">
                        <h5 class="card-title">Consumo de saldo por servidor</h5>
                        <canvas id="lineChart" style="max-height: 320px;"></canvas>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bv-panel">
                        <h5 class="card-title">Duración de llamadas por servidor</h5>
                        <canvas id="barChart" style="max-height: 320px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="bv-panel">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                    <h5 class="card-title mb-0 border-0">Consumo por servidor</h5>
                    <form method="post">
                        <input type="hidden" name="fechabusc" value="<?php echo htmlspecialchars($fechaDia, ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" name="exportarDay" class="btn btn-secondary btn-sm">Exportar datos</button>
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
                            if (isset($dataArray)) {
                                foreach ($dataArray as $row) {
                                    $consumototal += abs($row['fee']);
                                    $sum_holdtime = $row['feetime'];
                                    $horas = floor($sum_holdtime / 3600);
                                    $sum_holdtime %= 3600;
                                    $minutos = floor($sum_holdtime / 60);
                                    $sum_holdtime %= 60;
                                    $fee = NumberHelper::formatFeeClean($row['fee']);
                                    $formathor = $horas . ':' . $minutos . ':' . $sum_holdtime;
                                    echo '<tr>
                                    <td>' . htmlspecialchars($row['ip'], ENT_QUOTES, 'UTF-8') . '</td>
                                    <td>' . htmlspecialchars($row['gatewayid'], ENT_QUOTES, 'UTF-8') . '</td>
                                    <td>' . htmlspecialchars($row['feetime'], ENT_QUOTES, 'UTF-8') . '</td>
                                    <td>' . $formathor . '</td>
                                    <td>$ ' . $fee . '</td>
                                    </tr>';
                                }
                                $numeroFormateadoTotal = NumberHelper::formatFeeClean($consumototal);
                                echo '<tr>
                                <td></td><td></td><td></td>
                                <td class="bold">Total</td>
                                <td class="bold">$ ' . $numeroFormateadoTotal . '</td>
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
        const elemento = document.getElementById('elemento-a-exportar');
        html2canvas(elemento, { scrollX: 0, scrollY: 0, scale: 2 }).then(function (canvas) {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('l', 'mm', 'a4');
            pdf.setTextColor(80, 119, 186);
            pdf.setFontSize(14);
            pdf.text(110, 10, 'Reporte Consumo Diario');
            pdf.addImage(imgData, 'PNG', 25, 20, 250, 175);
            pdf.save('consumo-diario.pdf');
        });
    });

    var serversarray = <?php echo $serverdayconsum; ?>;
    var feesarray = <?php echo $feesdayconsum; ?>;
    var holdtimedayconsum = <?php echo $feestimedayconsum; ?>;

    document.addEventListener('DOMContentLoaded', () => {
        new Chart(document.querySelector('#lineChart'), {
            type: 'line',
            data: {
                labels: serversarray,
                datasets: [{
                    label: 'Consumo',
                    data: feesarray,
                    fill: true,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    tension: 0.3
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });

        new Chart(document.querySelector('#barChart'), {
            type: 'bar',
            data: {
                labels: serversarray,
                datasets: [{
                    label: 'Segundos',
                    data: holdtimedayconsum,
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
    });
</script>
