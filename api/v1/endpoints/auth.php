<?php
/**
 * Endpoint de Autenticación
 * POST /api/v1/auth/login - Iniciar sesión
 * POST /api/v1/auth/refresh - Refrescar token
 */

$method = $_SERVER['REQUEST_METHOD'];
$uriParts = $GLOBALS['uriParts'];
$action = isset($uriParts[1]) ? $uriParts[1] : null;

switch ($action) {
    case 'login':
        if ($method !== 'POST') {
            Response::error('Método no permitido. Use POST', 405);
        }
        handleLogin();
        break;

    case 'refresh':
        if ($method !== 'POST') {
            Response::error('Método no permitido. Use POST', 405);
        }
        handleRefresh();
        break;

    default:
        Response::error('Acción no válida. Usar /auth/login o /auth/refresh', 400);
        break;
}

/**
 * Manejar login
 */
function handleLogin() {
    $input = Response::getInput();
    
    // Validar campos requeridos
    Response::validateRequired($input, ['username', 'password']);

    $username = $input['username'];
    $password = $input['password'];

    try {
        // Buscar usuario
        $item = 'nombre';
        $valor = $username;
        $usuario = ControllersUsuarios::ctrMostrarUsuarios($item, $valor);

        if (!$usuario) {
            Response::error('Usuario no encontrado', 404);
        }

        // Verificar contraseña
        if (!password_verify($password, $usuario['password'])) {
            Response::error('Credenciales inválidas', 401);
        }

        // Generar token
        $token = AuthMiddleware::generateToken([
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'customeraccountname' => $usuario['customeraccountname']
        ]);

        Response::success([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWT_EXPIRATION,
            'user' => [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'customeraccountname' => $usuario['customeraccountname'],
                'perfil' => isset($usuario['perfil']) ? $usuario['perfil'] : null
            ]
        ], 'Login exitoso');

    } catch (Exception $e) {
        Response::error('Error en el servidor: ' . $e->getMessage(), 500);
    }
}

/**
 * Manejar refresh de token
 */
function handleRefresh() {
    try {
        $user = AuthMiddleware::validateToken();
        
        // Generar nuevo token
        $newToken = AuthMiddleware::generateToken([
            'id' => $user->user_id,
            'nombre' => $user->nombre,
            'customeraccountname' => $user->customeraccountname
        ]);

        Response::success([
            'token' => $newToken,
            'token_type' => 'Bearer',
            'expires_in' => JWT_EXPIRATION
        ], 'Token refrescado exitosamente');

    } catch (Exception $e) {
        Response::error('Error al refrescar token: ' . $e->getMessage(), 500);
    }
}
