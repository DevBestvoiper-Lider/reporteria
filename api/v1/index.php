<?php
/**
 * API REST - Reportería
 * Versión: 1.0
 * 
 * Punto de entrada principal para la API REST
 */

// Headers CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Responder a OPTIONS request (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Cargar configuración y autoloader
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../models/conexion.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/middleware/auth.php';
require_once __DIR__ . '/helpers/response.php';

// Iniciar sesión para validación
session_start();

// Cargar controladores
require_once __DIR__ . '/../../controllers/dashboard.controllers.php';
require_once __DIR__ . '/../../controllers/consumption.controllers.php';
require_once __DIR__ . '/../../controllers/reportcdr.controllers.php';
require_once __DIR__ . '/../../controllers/reportconsumo.controllers.php';
require_once __DIR__ . '/../../controllers/payhistory.controllers.php';
require_once __DIR__ . '/../../controllers/miaccount.controllers.php';
require_once __DIR__ . '/../../controllers/usuarios.controllers.php';

// Cargar modelos
require_once __DIR__ . '/../../models/dashboard.models.php';
require_once __DIR__ . '/../../models/consumption.models.php';
require_once __DIR__ . '/../../models/reportcdr.models.php';
require_once __DIR__ . '/../../models/reportconsumo.models.php';
require_once __DIR__ . '/../../models/payhistory.models.php';
require_once __DIR__ . '/../../models/miaccount.models.php';
require_once __DIR__ . '/../../models/usuarios.models.php';

// Obtener método y ruta
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Parsear la URI
$uri = parse_url($uri, PHP_URL_PATH);

// Obtener el path relativo a este script
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
if (strpos($uri, $scriptPath) === 0) {
    $uri = substr($uri, strlen($scriptPath));
}

$uri = trim($uri, '/');
$GLOBALS['uriParts'] = explode('/', $uri);
$uriParts = $GLOBALS['uriParts'];

// Router principal
$endpoint = $uriParts[0] ?? 'index';

try {
    // Validar autenticación (excepto para login y documentación)
    if (!in_array($endpoint, ['auth', 'docs', 'index', ''])) {
        AuthMiddleware::validateToken();
    }

    // Routing
    switch ($endpoint) {
        case '':
        case 'index':
            Response::success([
                'name' => 'Reportería API',
                'version' => '1.0',
                'endpoints' => [
                    'auth' => '/api/v1/auth',
                    'dashboard' => '/api/v1/dashboard',
                    'consumption' => '/api/v1/consumption',
                    'reportcdr' => '/api/v1/reportcdr',
                    'reportconsumo' => '/api/v1/reportconsumo',
                    'payhistory' => '/api/v1/payhistory',
                    'account' => '/api/v1/account',
                    'docs' => '/api/v1/docs'
                ]
            ]);
            break;

        case 'auth':
            require_once __DIR__ . '/endpoints/auth.php';
            break;

        case 'dashboard':
            require_once __DIR__ . '/endpoints/dashboard.php';
            break;

        case 'consumption':
            require_once __DIR__ . '/endpoints/consumption.php';
            break;

        case 'reportcdr':
            require_once __DIR__ . '/endpoints/reportcdr.php';
            break;

        case 'reportconsumo':
            require_once __DIR__ . '/endpoints/reportconsumo.php';
            break;

        case 'payhistory':
            require_once __DIR__ . '/endpoints/payhistory.php';
            break;

        case 'account':
            require_once __DIR__ . '/endpoints/account.php';
            break;

        case 'docs':
            require_once __DIR__ . '/docs/index.php';
            break;

        default:
            Response::error('Endpoint no encontrado', 404);
            break;
    }

} catch (Exception $e) {
    Response::error($e->getMessage(), $e->getCode() ?: 500);
}
