<?php
$vosNombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
$vosCuenta = isset($_SESSION['customeraccountname']) ? $_SESSION['customeraccountname'] : $vosNombre;
$vosIniciales = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $vosCuenta), 0, 2));
if ($vosIniciales === '') {
    $vosIniciales = 'BV';
}
?>

<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center gap-2">
        <a href="dashboard" class="logo d-flex align-items-center d-xl-none">
            <img src="views/img/logos/logo.png" alt="BestVoiper">
        </a>
        <i class="bi bi-list toggle-sidebar-btn d-xl-none"></i>
    </div>

    <form class="bv-search flex-grow-1 d-none d-md-block" action="reportcdr" method="post">
        <i class="bi bi-search"></i>
        <input type="text" name="callerSolic" placeholder="Buscar IP, prefijo, país o reporte" autocomplete="off">
        <input type="hidden" name="fechainicio" value="<?php echo date('Y-m-d'); ?>">
        <input type="hidden" name="fechafin" value="<?php echo date('Y-m-d'); ?>">
        <input type="hidden" name="archivo" value="reportcdr">
    </form>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center gap-2">
            <li class="nav-item">
                <a class="bv-icon-btn" href="payhistory" title="Notificaciones">
                    <i class="bi bi-bell"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="bv-user-chip dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <span class="bv-avatar"><?php echo htmlspecialchars($vosIniciales, ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="d-none d-md-block text-start">
                        <strong><?php echo htmlspecialchars($vosCuenta, ENT_QUOTES, 'UTF-8'); ?></strong>
                        <small>Cliente</small>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?php echo htmlspecialchars($vosNombre, ENT_QUOTES, 'UTF-8'); ?></h6>
                        <span><?php echo htmlspecialchars($_SESSION['perfil'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="miaccount">
                            <i class="bi bi-person"></i>
                            <span>Mi cuenta</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="salir">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Cerrar sesión</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
