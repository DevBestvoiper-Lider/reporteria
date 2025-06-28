<aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link " href="dashboard">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Reportes</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="reportcdr">
                            <i class="bi bi-circle"></i><span>CDR Reportes</span>
                        </a>
                    </li>
                    <li>
                        <a href="dayconsumption">
                            <i class="bi bi-circle"></i><span>Reporte Consumo Diario</span>
                        </a>
                    </li>
                    <li>
                        <a href="reportconsumo">
                            <i class="bi bi-circle"></i><span>Reporte de Consumo por Linea</span>
                        </a>
                    </li>
                    <li>
                        <a href="monthconsumption">
                            <i class="bi bi-circle"></i><span>Reporte Consumo Mensual</span>
                        </a>
                    </li>
                    <li>
                        <a href="payhistory">
                            <i class="bi bi-circle"></i><span>Historico de Pagos</span>
                        </a>
                    </li>
                    <li>
                        <a href="tarifas">
                            <i class="bi bi-circle"></i><span>Prefijos y Tarifas</span>
                        </a>
                    </li>

                </ul>
            </li>

            
            <?php
                    

                    if($_SESSION["perfil"] == "Administrador"){

                    ?>

                    <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                            <i class="bi bi-journal-text"></i><span>Administracion</span><i class="bi bi-chevron-down ms-auto"></i>
                            </a>
                            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                            <li>
                                <a href="usuarios">
                                <i class="bi bi-circle"></i><span>Usuarios</span>
                                </a>
                            </li>
                            <li>
                                <a href="Anti-hack">
                                <i class="bi bi-circle"></i><span>Anti-Hack</span>
                                </a>
                            </li>
                            </ul>
                        </li><!-- End Forms Nav -->


                    <?php
                    }?>
            
        </ul>

    </aside>
    <!-- End Sidebar-->