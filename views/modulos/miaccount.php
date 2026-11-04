<?php
include_once 'functions/miaccount.functions.php';

$foto = (isset($_SESSION['foto']) && $_SESSION['foto'] != '') ? $_SESSION['foto'] : 'views/img/users/avatar.png';
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
$perfil = isset($_SESSION['perfil']) ? $_SESSION['perfil'] : '';
$cuenta = isset($_SESSION['customeraccountname']) ? $_SESSION['customeraccountname'] : '';
?>

<main id="main" class="main">

    <div class="bv-page-head">
        <div>
            <div class="bv-crumb">Inicio / Mi cuenta</div>
            <h1>Mi cuenta</h1>
            <p>Datos de acceso y de la cuenta asociada.</p>
        </div>
    </div>

    <section class="section">
        <div class="row g-3">
            <div class="col-xl-4">
                <div class="bv-panel text-center">
                    <img src="<?php echo htmlspecialchars($foto, ENT_QUOTES, 'UTF-8'); ?>" alt="Usuario" class="rounded-circle mb-3" width="96" height="96" style="object-fit:cover;">
                    <h2 class="h4 mb-1"><?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="bv-kpi-sub mb-0"><?php echo htmlspecialchars($perfil, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="bv-panel">
                    <h5 class="card-title">Detalles de la cuenta</h5>
                    <div class="bv-profile-row">
                        <span class="label">Nombre</span>
                        <span><?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <div class="bv-profile-row">
                        <span class="label">Cuenta</span>
                        <span><?php echo htmlspecialchars($cuenta, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <div class="bv-profile-row">
                        <span class="label">Perfil</span>
                        <span><?php echo htmlspecialchars($perfil, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
