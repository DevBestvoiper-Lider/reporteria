<?php
    include_once 'functions/dashboard.functions.php';
?>

<main id="main" class="main">

    <section class="section dashboard">

        <div class="bv-page-head">
            <div>
                <div class="bv-crumb">Inicio / Dashboard</div>
                <h1><?php echo htmlspecialchars($saludo, ENT_QUOTES, 'UTF-8'); ?>, <?php echo htmlspecialchars($clienteNombre, ENT_QUOTES, 'UTF-8'); ?></h1>
                <p>Así va tu cuenta hoy · actualizado hoy, <?php echo $actualizadoHora; ?></p>
            </div>
            <div class="bv-actions">
                <span class="bv-date-pill"><i class="bi bi-calendar3"></i> <?php echo date('01 M'); ?> – <?php echo date('d M Y'); ?></span>
                <a class="bv-export" href="monthconsumption"><i class="bi bi-download"></i> Exportar reporte</a>
            </div>
        </div>

        <div class="bv-hero" id="saldo">
            <div class="bv-hero-top">
                <div class="bv-hero-label"><i class="bi bi-wallet2"></i> Saldo de la cuenta</div>
                <?php if ($saldoNegativo) { ?>
                    <span class="bv-pill-warn">Saldo en contra</span>
                <?php } else { ?>
                    <span class="bv-pill-ok">Saldo disponible</span>
                <?php } ?>
            </div>
            <div class="bv-hero-amount"><?php echo bvMoney($saldoactual); ?></div>
            <p class="bv-hero-help">
                <?php if ($saldoNegativo) { ?>
                    Equivale a ~<?php echo $diasEquivalentes; ?> días de consumo promedio. Regulariza tu cuenta para evitar restricciones.
                <?php } else { ?>
                    Equivale a ~<?php echo $diasEquivalentes; ?> días de consumo promedio con el ritmo actual.
                <?php } ?>
            </p>
            <div class="bv-hero-btns">
                <a class="bv-btn-green" href="payhistory"><i class="bi bi-plus-lg"></i> Registrar pago</a>
                <a class="bv-btn-navy" href="payhistory">Histórico de pagos</a>
            </div>
            <div class="bv-hero-meta">
                <div>
                    <span>Último pago</span>
                    <strong><?php echo bvMoney($ultimoPagoMonto, false); ?></strong>
                    <span><?php echo htmlspecialchars($ultimoPagoFecha, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <div>
                    <span>Consumo promedio / día</span>
                    <strong><?php echo bvMoney($promedioDia, false); ?></strong>
                    <span>este mes</span>
                </div>
            </div>
        </div>

        <div class="bv-kpi-row">
            <div class="bv-kpi" id="hoy">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico blue"><i class="bi bi-clock"></i></span> Consumo hoy</div>
                    <?php echo bvDeltaHtml($deltaHoyNum); ?>
                </div>
                <div class="bv-kpi-body">
                    <div>
                        <div class="bv-kpi-value" id="consumoValor"><?php echo bvMoney($consumoHoyAbs, false); ?></div>
                        <div class="bv-kpi-sub">Ayer: <?php echo bvMoney($consumoAyerAbs, false); ?></div>
                    </div>
                    <?php echo bvSparkline($sparkHoy, '#60a5fa'); ?>
                </div>
            </div>
            <div class="bv-kpi" id="mes">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico green"><i class="bi bi-graph-up"></i></span> Consumo del mes</div>
                    <?php echo bvDeltaHtml($deltaMesNum); ?>
                </div>
                <div class="bv-kpi-body">
                    <div>
                        <div class="bv-kpi-value"><?php echo bvMoney($consumoMesAbs, false); ?></div>
                        <div class="bv-kpi-sub">Mes anterior: <?php echo bvMoney($valorAntMes, false); ?></div>
                    </div>
                    <?php echo bvSparkline($sparkMes, '#22c55e'); ?>
                </div>
            </div>
            <div class="bv-kpi">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico purple"><i class="bi bi-bullseye"></i></span> Proyección del mes</div>
                    <?php echo bvDeltaHtml($deltaProy); ?>
                </div>
                <div class="bv-kpi-body">
                    <div>
                        <div class="bv-kpi-value"><?php echo bvMoney($proyeccionMes, false); ?></div>
                        <div class="bv-kpi-sub">Ritmo actual × <?php echo $diasMes; ?> días</div>
                    </div>
                    <?php echo bvSparkline($sparkProy, '#a855f7'); ?>
                </div>
            </div>
        </div>

        <div class="bv-kpi-row">
            <div class="bv-kpi">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico orange"><i class="bi bi-telephone"></i></span> Minutos hoy</div>
                </div>
                <div class="bv-kpi-body">
                    <div>
                        <div class="bv-kpi-value"><?php echo number_format($minutosHoy, 0, ',', '.'); ?></div>
                        <div class="bv-kpi-sub"><?php echo $duracionHoyFormat; ?></div>
                    </div>
                    <?php echo bvSparkline($sparkHoy, '#f97316'); ?>
                </div>
            </div>
            <div class="bv-kpi">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico blue"><i class="bi bi-telephone-outbound"></i></span> Llamadas hoy</div>
                </div>
                <div class="bv-kpi-body">
                    <div>
                        <div class="bv-kpi-value"><?php echo number_format($totalLlamadasHoy, 0, ',', '.'); ?></div>
                        <div class="bv-kpi-sub">Registros CDR de hoy</div>
                    </div>
                    <?php echo bvSparkline($sparkHoy, '#60a5fa'); ?>
                </div>
            </div>
            <div class="bv-kpi">
                <div class="bv-kpi-head">
                    <div class="left"><span class="bv-kpi-ico green"><i class="bi bi-calendar3"></i></span> Avance del mes</div>
                </div>
                <div class="bv-kpi-body">
                    <div>
                        <div class="bv-kpi-value"><?php echo $porcentajeAvanceMes; ?>%</div>
                        <div class="bv-kpi-sub"><?php echo $diasRestantes; ?> días restantes</div>
                    </div>
                    <?php echo bvSparkline($sparkMes, '#22c55e'); ?>
                </div>
            </div>
        </div>

        <div class="bv-panel mb-3">
            <h5 class="card-title">Evolución de consumo <span>| Este mes</span></h5>
            <div id="reportsChart"></div>
        </div>

        <div class="row g-3 mt-0">
            <div class="col-lg-8">
                <div class="bv-panel" id="llamadasrea">
                    <h5 class="card-title">Top consumo por IPs <span>| Hoy</span></h5>
                    <table class="table table-borderless align-middle mb-0">
                        <thead>
                            <tr>
                                <th>IP</th>
                                <th>Servidor origen</th>
                                <th>Consumo</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($topIps)) {
                                foreach ($topIps as $row) {
                                    $pct = $maxTopIpFee > 0 ? round(($row['fee'] / $maxTopIpFee) * 100) : 0;
                                    echo '<tr>
                                        <td>' . htmlspecialchars($row['ip'], ENT_QUOTES, 'UTF-8') . '</td>
                                        <td>' . htmlspecialchars($row['desc'], ENT_QUOTES, 'UTF-8') . '</td>
                                        <td>' . bvMoney($row['fee'], false) . '</td>
                                        <td><div class="progress" style="height:6px;background:#eef2f7"><div class="progress-bar" style="width:' . $pct . '%;background:#2563eb"></div></div></td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-muted">Sin registros de consumo hoy.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="bv-panel mb-3">
                    <h5 class="card-title">Consumo por código de área <span>| Hoy</span></h5>
                    <div id="trafficChart" class="bv-chart-box"></div>
                </div>
                <div class="bv-panel">
                    <h5 class="card-title">Motivo de cuelgue <span>| Hoy</span></h5>
                    <div id="hangupChart" class="bv-chart-box"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bv-panel">
                    <h5 class="card-title">Pagos recientes</h5>
                    <table class="table table-borderless table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($tuArray) && is_array($tuArray) && !empty($tuArray)) {
                                foreach ($tuArray as $data) {
                                    $timestamp = ((float)$data['time'] / 1000) - (7 * 3600);
                                    $starttime = date('Y-m-d H:i', (int)$timestamp);
                                    echo '<tr>
                                        <td>' . $starttime . '</td>
                                        <td>' . bvMoney($data['paymoney'], false) . '</td>
                                        <td>' . htmlspecialchars($data['loginname'], ENT_QUOTES, 'UTF-8') . '</td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3" class="text-muted">Sin pagos recientes.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bv-panel">
                    <h5 class="card-title">Accesos rápidos</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-outline-primary btn-sm" href="dayconsumption">Consumo diario</a>
                        <a class="btn btn-outline-primary btn-sm" href="reportcdr">CDR</a>
                        <a class="btn btn-outline-primary btn-sm" href="reportconsumo">Por línea</a>
                        <a class="btn btn-outline-primary btn-sm" href="monthconsumption">Mensual</a>
                        <a class="btn btn-outline-primary btn-sm" href="tarifas">Tarifas</a>
                        <a class="btn btn-outline-primary btn-sm" href="miaccount">Mi cuenta</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var fechasArray = <?php echo $fechas; ?>;
            var data = <?php echo $feemes; ?>;
            var areaData = <?php echo $ConsumAreaCode; ?>;
            var hangupData = <?php echo isset($motivoCuelgueJson) ? $motivoCuelgueJson : '[]'; ?>;

            document.addEventListener("DOMContentLoaded", () => {
                new ApexCharts(document.querySelector("#reportsChart"), {
                    series: [{ name: 'Consumo', data: data }],
                    chart: { height: 220, type: 'area', toolbar: { show: false }, sparkline: { enabled: false } },
                    markers: { size: 3 },
                    colors: ['#2563eb'],
                    fill: { type: "gradient", gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 90, 100] } },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    grid: { borderColor: '#e8eef5' },
                    xaxis: { type: 'datetime', categories: fechasArray, labels: { datetimeUTC: false } },
                    yaxis: { labels: { formatter: function (val) { return '$' + Math.round(val).toLocaleString('es-CO'); } } },
                    tooltip: { x: { format: 'dd/MM/yy' } }
                }).render();

                function bvHasChartData(items) {
                    if (!items || !items.length) return false;
                    for (var i = 0; i < items.length; i++) {
                        if (Number(items[i].value) > 0) return true;
                    }
                    return false;
                }

                function bvRenderDonut(selector, data, tooltip) {
                    var el = document.querySelector(selector);
                    if (!el) return;
                    if (!bvHasChartData(data)) {
                        el.innerHTML = '<p class="bv-kpi-sub mb-0 text-center" style="padding-top:4.5rem">Sin datos para hoy.</p>';
                        return;
                    }
                    echarts.init(el).setOption({
                        tooltip: { trigger: 'item', formatter: tooltip },
                        legend: { bottom: 0, type: 'scroll' },
                        color: ['#2563eb', '#f97316', '#22c55e', '#ef4444', '#a855f7', '#14b8a6'],
                        series: [{
                            type: 'pie',
                            radius: ['48%', '72%'],
                            center: ['50%', '42%'],
                            itemStyle: { borderRadius: 6, borderColor: '#fff', borderWidth: 2 },
                            label: { show: false },
                            data: data
                        }]
                    });
                }

                bvRenderDonut('#trafficChart', areaData, '{b}: ${c}');
                bvRenderDonut('#hangupChart', hangupData, '{b}: {c} ({d}%)');
            });
        </script>

    </section>
</main>
