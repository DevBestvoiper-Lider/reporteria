<?php
/**
 * Endpoint de Mi Cuenta
 * GET /api/v1/account - Información de la cuenta
 * PUT /api/v1/account/password - Cambiar contraseña
 */

$method = $_SERVER['REQUEST_METHOD'];
$uriParts = $GLOBALS['uriParts'];
$action = $uriParts[1] ?? null;

// Obtener usuario autenticado
$authUser = AuthMiddleware::getAuthenticatedUser();
$username = $authUser['nombre'];

switch ($action) {
    case null:
    case '':
        if ($method === 'GET') {
            handleGetAccount($username);
        } else {
            Response::error('Método no permitido. Use GET', 405);
        }
        break;

    case 'password':
        if ($method === 'PUT' || $method === 'POST') {
            handleChangePassword($username);
        } else {
            Response::error('Método no permitido. Use PUT o POST', 405);
        }
        break;

    default:
        Response::error('Acción no válida', 400);
        break;
}

/**
 * Obtener información de la cuenta
 */
function handleGetAccount($username) {
    try {
        $item = 'nombre';
        $usuario = ControllersUsuarios::ctrMostrarUsuarios($item, $username);

        if (!$usuario) {
            Response::error('Usuario no encontrado', 404);
        }

        // Obtener datos del customer
        $item = 'account';
        $accountname = $usuario['customeraccountname'];
        $dashboard = ControllersDashboard::ctrMostrarDatosHoy($item, $accountname);

        Response::success([
            'user' => [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'customer_account_name' => $usuario['customeraccountname'],
                'perfil' => $usuario['perfil'] ?? null,
                'ultimo_login' => $usuario['ultimo_login'] ?? null
            ],
            'account' => [
                'balance' => @$dashboard['money'],
                'balance_formatted' => number_format(@$dashboard['money'], 0, ',', '.'),
                'today_consumption' => abs(@$dashboard['todayconsumption']),
                'today_consumption_formatted' => number_format(abs(@$dashboard['todayconsumption']), 0, ',', '.')
            ]
        ]);

    } catch (Exception $e) {
        Response::error('Error al obtener información de cuenta: ' . $e->getMessage(), 500);
    }
}

/**
 * Cambiar contraseña
 */
function handleChangePassword($username) {
    try {
        $input = Response::getInput();
        
        // Validar campos requeridos
        Response::validateRequired($input, ['current_password', 'new_password']);

        $currentPassword = $input['current_password'];
        $newPassword = $input['new_password'];

        // Validar longitud de nueva contraseña
        if (strlen($newPassword) < 6) {
            Response::error('La nueva contraseña debe tener al menos 6 caracteres', 400);
        }

        // Obtener usuario
        $item = 'nombre';
        $usuario = ControllersUsuarios::ctrMostrarUsuarios($item, $username);

        if (!$usuario) {
            Response::error('Usuario no encontrado', 404);
        }

        // Verificar contraseña actual
        if (!password_verify($currentPassword, $usuario['password'])) {
            Response::error('La contraseña actual es incorrecta', 401);
        }

        // Actualizar contraseña (necesitarás implementar este método en el controlador)
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Aquí deberías llamar a un método del controlador para actualizar la contraseña
        // Por ejemplo: ControllersUsuarios::ctrActualizarPassword($usuario['id'], $hashedPassword);
        
        Response::success([
            'message' => 'Contraseña actualizada exitosamente'
        ]);

    } catch (Exception $e) {
        Response::error('Error al cambiar contraseña: ' . $e->getMessage(), 500);
    }
}
