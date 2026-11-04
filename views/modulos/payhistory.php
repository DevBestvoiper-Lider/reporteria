<?php
include_once 'functions/payhistory.functions.php';

$fechaInicio = isset($_POST['fechainicio']) ? $_POST['fechainicio'] : date('Y-m-d');
$fechaFin = isset($_POST['fechafin']) ? $_POST['fechafin'] : date('Y-m-d');

if (!isset($dataArray) || !is_array($dataArray)) {
    $dataArray = array();
}

$totalPagos = 0;
$countPagos = 0;
$ultimoMonto = 0;
$ultimoFecha = 'Sin registros';
$byDay = array();

foreach ($dataArray as $data) {
    $totalPagos += (float) $data['paymoney'];
    $countPagos++;
    $timestamp = $data['time'] / 1000;
    $day = date('Y-m-d', $timestamp);
    if (!isset($byDay[$day])) {
        $byDay[$day] = 0;
    }
    $byDay[$day] += (float) $data['paymoney'];
}

if ($countPagos > 0) {
    $last = $dataArray[0];
    $ultimoMonto = (float) $last['paymoney'];
    $ultimoFecha = date('Y-m-d H:i', $last['time'] / 1000);
}

ksort($byDay);
$payLabels = json_encode(array_keys($byDay));
$payValues = json_encode(array_values($byDay));
$promedioPago = $countPagos > 0 ? ($totalPagos / $countPagos) : 0;
?>

<main id="main" class="main">

    <div class="bv-page-head">
        <div>
            <div class="bv-crumb">Inicio / Histórico de pagos</div>
            <h1>Histórico de pagos</h1>
            <p>Recargas y movimientos de saldo en el rango consultado.</p>
        </div>
        <div class="bv-actions">
            <span class="bv-date-pill"><i class="bi bi-calendar3"></i> <?php echo htmlspecialchars($fechaInicio, ENT_QUOTES, 'UTF-8'); ?> – <?php echo htmlspecialchars($fechaFin, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
    </div>

    <section class="section">
        <form method="post" class="bv-panel bv-filters mb-3">
            <h5 class="card-title">Filtros</h5>
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" name="fechainicio" value="<?php echo htmlspecialchars($fechaInicio, ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" name="fechafin" value="<?php echo htmlspecialchars($fechaFin, ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search"></i> Buscar</button>
                    <button type="submit" class="btn btn-secondary" name="accion" value="exportarPagos">Exportar</button>
                </div>
            </div>
        </form>

        <div class="bv-kpi-row">
            <div class="bv-kpi">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico green"><i class="bi bi-cash-stack"></i></span> Total recargado</div>
                </div>
                <div class="bv-kpi-value pos-blue">$<?php echo number_format($totalPagos, 0, ',', '.'); ?></div>
                <div class="bv-kpi-sub"><?php echo (int) $countPagos; ?> pagos en el periodo</div>
            </div>
            <div class="bv-kpi">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico blue"><i class="bi bi-graph-up"></i></span> Promedio por pago</div>
                </div>
                <div class="bv-kpi-value">$<?php echo number_format($promedioPago, 0, ',', '.'); ?></div>
                <div class="bv-kpi-sub">Según los movimientos filtrados</div>
            </div>
            <div class="bv-kpi">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico orange"><i class="bi bi-clock-history"></i></span> Último pago</div>
                </div>
                <div class="bv-kpi-value">$<?php echo number_format($ultimoMonto, 0, ',', '.'); ?></div>
                <div class="bv-kpi-sub"><?php echo htmlspecialchars($ultimoFecha, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
        </div>

        <div class="bv-panel mb-3">
            <h5 class="card-title mb-0 border-0">Pagos por día</h5>
            <p class="bv-chart-sub">Monto recargado en el rango</p>
            <canvas id="payChart" style="max-height: 280px;"></canvas>
        </div>

        <div class="bv-panel">
            <h5 class="card-title mb-0 border-0">Detalle de pagos</h5>
            <div class="table-responsive mt-2">
                <table id="table2" class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Fecha pago</th>
                            <th>Cuenta</th>
                            <th>Monto</th>
                            <th>Monto después de la carga</th>
                            <th>Usuario</th>
                            <th>Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($dataArray as $data) {
                            $timestamp = $data['time'] / 1000;
                            $starttime = date('Y-m-d H:i:s', $timestamp);
                            $PagoFormateado = number_format($data['paymoney'], 2, ',', '.');
                            $numeroFormateado = number_format($data['customermoney'], 2, ',', '.');
                            echo '<tr>
                            <td>' . htmlspecialchars($starttime, ENT_QUOTES, 'UTF-8') . '</td>
                            <td>' . htmlspecialchars($data['customeraccount'], ENT_QUOTES, 'UTF-8') . '</td>
                            <td>$ ' . $PagoFormateado . '</td>
                            <td>$ ' . $numeroFormateado . '</td>
                            <td>' . htmlspecialchars($data['loginname'], ENT_QUOTES, 'UTF-8') . '</td>
                            <td>' . htmlspecialchars($data['memo'], ENT_QUOTES, 'UTF-8') . '</td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<script>
    var payLabels = <?php echo $payLabels; ?>;
    var payValues = <?php echo $payValues; ?>;
    document.addEventListener('DOMContentLoaded', function () {
        new Chart(document.querySelector('#payChart'), {
            type: 'bar',
            data: {
                labels: payLabels,
                datasets: [{
                    label: 'Monto',
                    data: payValues,
                    backgroundColor: 'rgba(37, 99, 235, 0.85)',
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
