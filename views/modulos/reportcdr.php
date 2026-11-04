<?php
include_once 'functions/miaccount.functions.php';
include_once 'functions/reportcdr.functions.php';

$fechaInicio = isset($_POST['fechainicio']) ? $_POST['fechainicio'] : date('Y-m-d');
$fechaFin = isset($_POST['fechafin']) ? $_POST['fechafin'] : date('Y-m-d');
$caller = isset($_POST['caller']) ? $_POST['caller'] : '';
$callee = isset($_POST['callee']) ? $_POST['callee'] : '';
$callerSolic = isset($_POST['callerSolic']) ? $_POST['callerSolic'] : '';
$op1 = isset($_POST['operador_duracion1']) ? $_POST['operador_duracion1'] : '';
$op2 = isset($_POST['operador_duracion2']) ? $_POST['operador_duracion2'] : '';
$dur1 = isset($_POST['duracion_valor1']) ? $_POST['duracion_valor1'] : '';
$dur2 = isset($_POST['duracion_valor2']) ? $_POST['duracion_valor2'] : '';
$hasta1 = isset($_POST['duracion_hasta1']) ? $_POST['duracion_hasta1'] : '';
$hasta2 = isset($_POST['duracion_hasta2']) ? $_POST['duracion_hasta2'] : '';
$logica = isset($_POST['logica_duracion']) ? $_POST['logica_duracion'] : 'AND';
$totalCdr = isset($dataArray) && is_array($dataArray) ? count($dataArray) : 0;

if (!function_exists('bvOpOptions')) {
function bvOpOptions($selected) {
    $ops = array(
        '' => 'Sin filtro',
        '>' => 'Mayor que',
        '<' => 'Menor que',
        '=' => 'Igual a',
        '>=' => 'Mayor o igual',
        '<=' => 'Menor o igual',
    );
    $html = '';
    foreach ($ops as $val => $label) {
        $sel = ((string) $selected === (string) $val) ? ' selected' : '';
        $html .= '<option value="' . htmlspecialchars($val, ENT_QUOTES, 'UTF-8') . '"' . $sel . '>' . $label . '</option>';
    }
    return $html;
}
}
?>

<main id="main" class="main">

    <div class="bv-page-head">
        <div>
            <div class="bv-crumb">Inicio / CDR</div>
            <h1>CDR Reportes</h1>
            <p>Consulta llamadas por fecha, número y duración.</p>
        </div>
        <div class="bv-actions">
            <span class="bv-date-pill"><i class="bi bi-calendar3"></i> <?php echo htmlspecialchars($fechaInicio, ENT_QUOTES, 'UTF-8'); ?> – <?php echo htmlspecialchars($fechaFin, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
    </div>

    <section class="section">
        <form method="post" class="bv-panel bv-filters mb-3">
            <h5 class="card-title">Filtros</h5>
            <input type="hidden" name="archivo" id="accion" value="reportcdr">

            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" name="fechainicio" value="<?php echo htmlspecialchars($fechaInicio, ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" name="fechafin" value="<?php echo htmlspecialchars($fechaFin, ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Número mostrado</label>
                    <input type="text" name="caller" value="<?php echo htmlspecialchars($caller, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" placeholder="Ej. 57316…">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Número marcado</label>
                    <input type="text" name="callee" value="<?php echo htmlspecialchars($callee, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" placeholder="Destino">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Número solicitado</label>
                    <input type="text" name="callerSolic" value="<?php echo htmlspecialchars($callerSolic, ENT_QUOTES, 'UTF-8'); ?>" class="form-control" placeholder="Línea de origen">
                </div>
            </div>

            <div class="bv-duration mt-3">
                <label class="form-label mb-2">Duración (segundos)</label>
                <div class="bv-duration-row">
                    <select name="operador_duracion1" class="form-select">
                        <?php echo bvOpOptions($op1); ?>
                    </select>
                    <input type="number" name="duracion_valor1" class="form-control" placeholder="Valor" value="<?php echo htmlspecialchars($dur1, ENT_QUOTES, 'UTF-8'); ?>">
                    <div id="duracion_hasta1" style="<?php echo ($op1 === 'BETWEEN') ? '' : 'display:none;'; ?>">
                        <input type="number" name="duracion_hasta1" class="form-control" placeholder="Hasta" value="<?php echo htmlspecialchars($hasta1, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="bv-logic">
                        <label><input type="radio" name="logica_duracion" value="AND" <?php echo ($logica !== 'OR') ? 'checked' : ''; ?>> Y</label>
                        <label><input type="radio" name="logica_duracion" value="OR" <?php echo ($logica === 'OR') ? 'checked' : ''; ?>> O</label>
                    </div>
                </div>
                <div class="bv-duration-row mt-2">
                    <select name="operador_duracion2" class="form-select">
                        <?php echo bvOpOptions($op2); ?>
                    </select>
                    <input type="number" name="duracion_valor2" class="form-control" placeholder="Valor" value="<?php echo htmlspecialchars($dur2, ENT_QUOTES, 'UTF-8'); ?>">
                    <div id="duracion_hasta2" style="<?php echo ($op2 === 'BETWEEN') ? '' : 'display:none;'; ?>">
                        <input type="number" name="duracion_hasta2" class="form-control" placeholder="Hasta" value="<?php echo htmlspecialchars($hasta2, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <span class="bv-duration-hint">Filtro adicional (opcional)</span>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary px-4">Buscar</button>
                <button type="submit" class="btn btn-secondary" name="accion" value="exportar">Exportar</button>
            </div>
        </form>

        <?php if (!empty($motivoChart)) { ?>
        <div class="row g-3 mb-3">
            <div class="col-lg-5">
                <div class="bv-panel h-100">
                    <h5 class="card-title">Motivo de cuelgue</h5>
                    <div id="hangupChart" style="min-height: 260px;"></div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="bv-panel h-100">
                    <h5 class="card-title">Distribución</h5>
                    <table class="table table-borderless align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Motivo</th>
                                <th>Llamadas</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($motivoCuelgue as $name => $count) {
                                $pct = $totalCdr > 0 ? round(($count / $totalCdr) * 100, 1) : 0;
                                echo '<tr>
                                    <td>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</td>
                                    <td>' . number_format($count, 0, ',', '.') . '</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;background:#eef2f7"><div class="progress-bar" style="width:' . $pct . '%;background:#2563eb"></div></div>
                                            <span>' . $pct . '%</span>
                                        </div>
                                    </td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="bv-panel" id="datoscontent">
            <h5 class="card-title">Resultados <?php if ($totalCdr > 0) { ?><span>| <?php echo number_format($totalCdr, 0, ',', '.'); ?> registros</span><?php } ?></h5>
            <div class="table-responsive">
                <table id="table2" class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Número solicitado</th>
                            <th>Número mostrado</th>
                            <th>Número marcado</th>
                            <th>Destino</th>
                            <th>Servidor origen</th>
                            <th>Hora inicio</th>
                            <th>Duración (s)</th>
                            <th>Motivo cuelgue</th>
                            <th>Costo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($dataArray) && is_array($dataArray)) {
                            echo '<script>
                            swal.fire({
                                icon: "info",
                                title: "Solo verás 500 registros por consulta. Usa Exportar para descargar el resto.",
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar"
                            });
                            </script>';

                            foreach ($dataArray as $data) {
                                $timestamp = $data['starttime'] / 1000;
                                $starttime = date('Y-m-d H:i:s', (int) $timestamp);
                                echo '<tr>
                                <td>' . htmlspecialchars($data['calleraccesse164'], ENT_QUOTES, 'UTF-8') . '</td>
                                <td>' . htmlspecialchars($data['callertogatewaye164'], ENT_QUOTES, 'UTF-8') . '</td>
                                <td>' . htmlspecialchars($data['calleee164'], ENT_QUOTES, 'UTF-8') . '</td>
                                <td>' . htmlspecialchars($data['areacode'], ENT_QUOTES, 'UTF-8') . '</td>
                                <td>' . htmlspecialchars($data['callergatewayid'], ENT_QUOTES, 'UTF-8') . '</td>
                                <td>' . $starttime . '</td>
                                <td>' . htmlspecialchars($data['holdtime'], ENT_QUOTES, 'UTF-8') . '</td>
                                <td>' . htmlspecialchars($data['endreason'], ENT_QUOTES, 'UTF-8') . '</td>
                                <td>$ ' . NumberHelper::formatFeeClean($data['fee']) . '</td>
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

<script>
    function toggleDuracionHasta(selectElement, hastaElementId) {
        const hastaElement = document.getElementById(hastaElementId);
        if (!hastaElement) return;
        hastaElement.style.display = selectElement.value === 'BETWEEN' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        var hangupEl = document.querySelector('#hangupChart');
        if (hangupEl) {
            var hangupData = <?php echo isset($motivoCuelgueJson) ? $motivoCuelgueJson : '[]'; ?>;
            echarts.init(hangupEl).setOption({
                tooltip: { trigger: 'item', formatter: '{b}: {c} ({d}%)' },
                legend: { bottom: 0 },
                color: ['#2563eb', '#f97316', '#22c55e', '#ef4444', '#a855f7', '#14b8a6'],
                series: [{
                    name: 'Motivo',
                    type: 'pie',
                    radius: ['48%', '72%'],
                    itemStyle: { borderRadius: 6, borderColor: '#fff', borderWidth: 2 },
                    label: { show: false },
                    data: hangupData.length ? hangupData : [{ name: 'Sin datos', value: 0 }]
                }]
            });
        }

        const operador1 = document.querySelector('select[name="operador_duracion1"]');
        const operador2 = document.querySelector('select[name="operador_duracion2"]');
        if (operador1) {
            operador1.addEventListener('change', function () {
                toggleDuracionHasta(this, 'duracion_hasta1');
            });
        }
        if (operador2) {
            operador2.addEventListener('change', function () {
                toggleDuracionHasta(this, 'duracion_hasta2');
            });
        }
    });
</script>
