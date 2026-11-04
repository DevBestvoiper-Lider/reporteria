<?php
include_once 'views/modulos/functions/miaccount.functions.php';

if (!isset($rateminutearray) || !is_array($rateminutearray)) {
    $rateminutearray = array();
}

$countTarifas = count($rateminutearray);
$minTarifa = null;
$maxTarifa = null;
foreach ($rateminutearray as $value) {
    $rate = isset($value['rate(minute)']) ? (float) $value['rate(minute)'] : 0;
    if ($minTarifa === null || $rate < $minTarifa) {
        $minTarifa = $rate;
    }
    if ($maxTarifa === null || $rate > $maxTarifa) {
        $maxTarifa = $rate;
    }
}
?>

<main id="main" class="main">

    <div class="bv-page-head">
        <div>
            <div class="bv-crumb">Inicio / Prefijos y tarifas</div>
            <h1>Prefijos y tarifas</h1>
            <p>Destinos, prefijos y costo por minuto de la cuenta.</p>
        </div>
        <div class="bv-actions">
            <span class="bv-date-pill"><i class="bi bi-list-ul"></i> <?php echo (int) $countTarifas; ?> destinos</span>
        </div>
    </div>

    <section class="section">
        <div class="bv-kpi-row">
            <div class="bv-kpi">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico blue"><i class="bi bi-geo-alt"></i></span> Destinos</div>
                </div>
                <div class="bv-kpi-value"><?php echo (int) $countTarifas; ?></div>
                <div class="bv-kpi-sub">Prefijos configurados</div>
            </div>
            <div class="bv-kpi">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico green"><i class="bi bi-arrow-down-circle"></i></span> Tarifa mínima</div>
                </div>
                <div class="bv-kpi-value">$<?php echo $minTarifa === null ? '0' : NumberHelper::formatFeeClean($minTarifa); ?></div>
                <div class="bv-kpi-sub">Por minuto</div>
            </div>
            <div class="bv-kpi">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico orange"><i class="bi bi-arrow-up-circle"></i></span> Tarifa máxima</div>
                </div>
                <div class="bv-kpi-value">$<?php echo $maxTarifa === null ? '0' : NumberHelper::formatFeeClean($maxTarifa); ?></div>
                <div class="bv-kpi-sub">Por minuto</div>
            </div>
        </div>

        <div class="bv-panel">
            <h5 class="card-title mb-0 border-0">Listado de tarifas</h5>
            <div class="table-responsive mt-2">
                <table id="table" class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nombre del área</th>
                            <th>Prefijo</th>
                            <th>Tarifa</th>
                            <th>Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($rateminutearray as $value) {
                            echo '<tr>
                            <td>' . htmlspecialchars($value['areaname'], ENT_QUOTES, 'UTF-8') . '</td>
                            <td>' . htmlspecialchars($value['prefix'], ENT_QUOTES, 'UTF-8') . '</td>
                            <td>$ ' . NumberHelper::formatFeeClean($value['rate(minute)']) . '</td>
                            <td>' . htmlspecialchars($value['memo'], ENT_QUOTES, 'UTF-8') . '</td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
