<?php
/**
 * Middleware de Autenticación
 * Valida el token JWT en las peticiones
 */

class AuthMiddleware {
    
    /**
     * Validar token JWT
     */
    public static function validateToken() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (!$authHeader) {
            Response::error('Token de autenticación no proporcionado', 401);
        }

        // Extraer token del header "Bearer {token}"
        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            Response::error('Formato de token inválido', 401);
        }

        $token = $matches[1];

        try {
            $decoded = self::decodeJWT($token);
            
            // Verificar que el token tenga los campos necesarios
            if (!isset($decoded->exp) || !isset($decoded->nombre) || !isset($decoded->customeraccountname) || !isset($decoded->user_id)) {
                Response::error('Token inválido: estructura incorrecta', 401);
            }
            
            // Verificar expiración
            if ($decoded->exp < time()) {
                Response::error('Token expirado', 401);
            }

            // Guardar datos del usuario en sesión para uso posterior
            $_SESSION['api_user'] = [
                'nombre' => $decoded->nombre,
                'customeraccountname' => $decoded->customeraccountname,
                'user_id' => $decoded->user_id
            ];

            return $decoded;

        } catch (Exception $e) {
            Response::error('Token inválido: ' . $e->getMessage(), 401);
        }
    }

    /**
     * Generar token JWT
     */
    public static function generateToken($userData) {
        $header = json_encode(['typ' => 'JWT', 'alg' => JWT_ALGORITHM]);
        
        $payload = json_encode([
            'user_id' => $userData['id'],
            'nombre' => $userData['nombre'],
            'customeraccountname' => $userData['customeraccountname'],
            'iat' => time(),
            'exp' => time() + JWT_EXPIRATION
        ]);

        // Encode Header
        $base64UrlHeader = self::base64UrlEncode($header);
        
        // Encode Payload
        $base64UrlPayload = self::base64UrlEncode($payload);

        // Create Signature
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, JWT_SECRET_KEY, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    }

    /**
     * Decodificar token JWT
     */
    private static function decodeJWT($jwt) {
        $tokenParts = explode('.', $jwt);
        
        if (count($tokenParts) !== 3) {
            throw new Exception('Token malformado');
        }

        $header = json_decode(self::base64UrlDecode($tokenParts[0]));
        $payload = json_decode(self::base64UrlDecode($tokenParts[1]));
        $signatureProvided = $tokenParts[2];

        // Verificar firma
        $base64UrlHeader = $tokenParts[0];
        $base64UrlPayload = $tokenParts[1];
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, JWT_SECRET_KEY, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        if ($base64UrlSignature !== $signatureProvided) {
            throw new Exception('Firma de token inválida');
        }

        return $payload;
    }

    /**
     * Encode Base64 URL
     */
    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Decode Base64 URL
     */
    private static function base64UrlDecode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    /**
     * Obtener datos del usuario autenticado
     */
    public static function getAuthenticatedUser() {
        return $_SESSION['api_user'] ?? null;
    }
}
