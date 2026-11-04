<section class="bv-login-shell">
    <div class="bv-login-card">
        <div class="bv-login-logo">
            <a href="index">
                <img src="views/img/logos/logo.png" alt="BestVoiper">
            </a>
        </div>
        <div class="text-center mb-4">
            <p class="text-uppercase small mb-1 bv-login-kicker">BestVoiper</p>
            <h5 class="card-title text-center pb-0 fs-4 border-0 mb-1">Inicia sesión</h5>
            <p class="small mb-0 text-muted">Reportería de clientes</p>
        </div>

        <form class="row g-3 needs-validation" method="POST">
            <div class="col-12">
                <label for="yourUsername" class="form-label">Usuario</label>
                <input type="text" name="ingUsuario" class="form-control" id="yourUsername" required>
            </div>
            <div class="col-12">
                <label for="yourPassword" class="form-label">Contraseña</label>
                <div class="input-group mb-1">
                    <input type="password" name="ingPassword" class="form-control" id="yourPassword" required>
                    <span class="input-group-text" id="togglePassword">
                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                    </span>
                </div>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Recordarme</label>
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">Ingresar</button>
            </div>
        </form>

        <?php
        $login = new ControllersUsuarios();
        $login->ctrIngresoUsuario();
        ?>

        <p class="text-center small text-muted mt-4 mb-0">By BV</p>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var passwordField = document.getElementById('yourPassword');
        var toggleIcon = document.getElementById('toggleIcon');
        toggleIcon.addEventListener('click', function () {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        });
    });
</script>
