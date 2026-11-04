<?php
$vosRuta = isset($_GET['ruta']) ? $_GET['ruta'] : 'dashboard';

function vosNavActive($rutas, $actual) {
    if (!is_array($rutas)) {
        $rutas = array($rutas);
    }
    return in_array($actual, $rutas, true) ? ' active' : '';
}
?>

<aside id="sidebar" class="sidebar">

    <div class="bv-brand">
        <div class="bv-logo-mark"><i class="bi bi-globe2"></i></div>
        <div>
            <strong>BEST<span>VOIPER</span></strong>
            <small>Reportería de clientes</small>
        </div>
    </div>

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link<?php echo vosNavActive('dashboard', $vosRuta); ?>" href="dashboard">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-heading">Reportes</li>

        <li class="nav-item">
            <a class="nav-link<?php echo vosNavActive('reportcdr', $vosRuta); ?>" href="reportcdr">
                <i class="bi bi-telephone"></i>
                <span>CDR</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php echo vosNavActive('dayconsumption', $vosRuta); ?>" href="dayconsumption">
                <i class="bi bi-file-earmark-text"></i>
                <span>Consumo diario</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php echo vosNavActive('reportconsumo', $vosRuta); ?>" href="reportconsumo">
                <i class="bi bi-graph-up"></i>
                <span>Consumo por línea</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php echo vosNavActive('monthconsumption', $vosRuta); ?>" href="monthconsumption">
                <i class="bi bi-bar-chart"></i>
                <span>Consumo mensual</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php echo vosNavActive('payhistory', $vosRuta); ?>" href="payhistory">
                <i class="bi bi-receipt"></i>
                <span>Histórico de pagos</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php echo vosNavActive('tarifas', $vosRuta); ?>" href="tarifas">
                <i class="bi bi-tag"></i>
                <span>Prefijos y tarifas</span>
            </a>
        </li>

        <?php if ($_SESSION["perfil"] == "Administrador") { ?>
        <li class="nav-heading">Administración</li>
        <li class="nav-item">
            <a class="nav-link<?php echo vosNavActive('usuarios', $vosRuta); ?>" href="usuarios">
                <i class="bi bi-people"></i>
                <span>Usuarios</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php echo vosNavActive('Anti-hack', $vosRuta); ?>" href="Anti-hack">
                <i class="bi bi-shield-lock"></i>
                <span>Anti-Hack</span>
            </a>
        </li>
        <?php } ?>
    </ul>
</aside>
