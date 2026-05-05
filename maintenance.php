<?php
// Opcional: enviar código HTTP 503 para indicar mantenimiento
http_response_code(503);
header('Retry-After: 3600');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sitio en Mantenimiento</title>
  <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendor/bootstrap-icons/bootstrap-icons.css">
  <style>
    body {
      background-color: #f6f9ff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .maintenance-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .maintenance-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
      padding: 3rem 2.5rem;
      max-width: 520px;
      width: 100%;
      text-align: center;
    }

    .maintenance-icon {
      font-size: 5rem;
      color: #4154f1;
      margin-bottom: 1.5rem;
      animation: spin 4s linear infinite;
    }

    @keyframes spin {
      0%   { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .maintenance-card h1 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #012970;
      margin-bottom: 0.75rem;
    }

    .maintenance-card p {
      color: #6c757d;
      font-size: 1rem;
      line-height: 1.6;
      margin-bottom: 2rem;
    }

    .progress-label {
      font-size: 0.85rem;
      color: #4154f1;
      font-weight: 600;
      margin-bottom: 0.35rem;
    }

    .logo img {
      max-height: 56px;
      margin-bottom: 2rem;
    }

    .contact-link {
      color: #4154f1;
      text-decoration: none;
      font-weight: 500;
    }

    .contact-link:hover {
      text-decoration: underline;
    }

    /* Countdown */
    .countdown {
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .countdown-block {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .countdown-number {
      font-size: 2rem;
      font-weight: 700;
      color: #012970;
      background: #f0f3ff;
      border-radius: 10px;
      width: 64px;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .countdown-label {
      font-size: 0.75rem;
      color: #6c757d;
      margin-top: 0.3rem;
    }
  </style>
</head>
<body>

<div class="maintenance-wrapper">

  <div class="logo">
    <img src="views/img/logos/logo-Best.png" alt="Logo">
  </div>

  <div class="maintenance-card">

    <div class="maintenance-icon">
      <i class="bi bi-gear-wide-connected"></i>
    </div>

    <h1>Sitio en Mantenimiento</h1>
    <p>
      Estamos realizando tareas de mantenimiento programado para mejorar tu experiencia.
      Estaremos de vuelta muy pronto. ¡Gracias por tu paciencia!
    </p>

    <!-- Contador regresivo -->
    <div class="countdown">
      <div class="countdown-block">
        <div class="countdown-number" id="cd-hours">00</div>
        <span class="countdown-label">Horas</span>
      </div>
      <div class="countdown-block">
        <div class="countdown-number" id="cd-minutes">00</div>
        <span class="countdown-label">Minutos</span>
      </div>
      <div class="countdown-block">
        <div class="countdown-number" id="cd-seconds">00</div>
        <span class="countdown-label">Segundos</span>
      </div>
    </div>

    <!-- Barra de progreso animada -->
    <p class="progress-label">Progreso del mantenimiento</p>
    <div class="progress mb-4" style="height: 10px; border-radius: 10px;">
      <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
           role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
      </div>
    </div>

    <small class="text-muted">
      ¿Necesitas ayuda urgente? Contáctanos en
      <a class="contact-link" href="mailto:desarrollo@bestvoiper.com">desarrollo@bestvoiper.com</a>
    </small>

  </div>

</div>

<script>
  // Cuenta regresiva: ajusta la fecha/hora de retorno
  const returnTime = new Date();
  returnTime.setHours(returnTime.getHours() + 2); // 2 horas desde ahora

  function updateCountdown() {
    const now = new Date();
    const diff = returnTime - now;

    if (diff <= 0) {
      document.getElementById('cd-hours').textContent   = '00';
      document.getElementById('cd-minutes').textContent = '00';
      document.getElementById('cd-seconds').textContent = '00';
      return;
    }

    const hours   = Math.floor(diff / 1000 / 3600);
    const minutes = Math.floor((diff / 1000 % 3600) / 60);
    const seconds = Math.floor(diff / 1000 % 60);

    document.getElementById('cd-hours').textContent   = String(hours).padStart(2, '0');
    document.getElementById('cd-minutes').textContent = String(minutes).padStart(2, '0');
    document.getElementById('cd-seconds').textContent = String(seconds).padStart(2, '0');
  }

  updateCountdown();
  setInterval(updateCountdown, 1000);
</script>

</body>
</html>
