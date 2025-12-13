<?php
/**
 * Helper de Respuestas
 * Estandariza las respuestas de la API
 */

class Response {
    
    /**
     * Respuesta exitosa
     */
    public static function success($data, $message = 'OK', $code = 200) {
        http_response_code($code);
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }

    /**
     * Respuesta de error
     */
    public static function error($message, $code = 400, $errors = null) {
        http_response_code($code);
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }

    /**
     * Validar datos requeridos
     */
    public static function validateRequired($data, $requiredFields) {
        $missing = [];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $missing[] = $field;
            }
        }
        
        if (!empty($missing)) {
            self::error(
                'Campos requeridos faltantes',
                400,
                ['missing_fields' => $missing]
            );
        }
    }

    /**
     * Obtener datos de entrada
     */
    public static function getInput() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        if ($method === 'GET') {
            return $_GET;
        }
        
        // Para POST, PUT, DELETE
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        // Si no es JSON, intentar con form data
        if ($data === null) {
            $data = $_POST;
        }
        
        return $data ? $data : array();
    }
}
