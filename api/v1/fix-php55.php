<?php
/**
 * Script para convertir sintaxis PHP 7+ a PHP 5.5
 * Ejecutar: php fix-php55.php
 */

function convertNullCoalescing($content) {
    // Convertir ?? a operador ternario con isset
    // Patrón: $var ?? $default
    $pattern = '/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*\?\?\s*([^;,\)]+)/';
    $content = preg_replace($pattern, '(isset($$1) ? $$1 : $2)', $content);
    
    // Patrón: $array['key'] ?? $default
    $pattern = '/\$([a-zA-Z_][a-zA-Z0-9_]*)\[([\'"][^\'"]+[\'"])\]\s*\?\?\s*([^;,\)]+)/';
    $content = preg_replace($pattern, '(isset($$1[$2]) ? $$1[$2] : $3)', $content);
    
    // Patrón encadenado: $a ?? $b ?? $c
    while (preg_match('/\?\?/', $content)) {
        $content = preg_replace('/\(isset\(([^\)]+)\)\s*\?\s*([^\)]+)\s*:\s*([^\)]+)\)\s*\?\?\s*([^;,\)]+)/', 
                               '(isset($1) ? $2 : (isset($3) ? $3 : $4))', $content);
    }
    
    return $content;
}

function convertScalarTypeHints($content) {
    // Remover type hints de scalar types
    $content = preg_replace('/function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(\s*string\s+/', 'function $1(', $content);
    $content = preg_replace('/function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(\s*int\s+/', 'function $1(', $content);
    $content = preg_replace('/function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(\s*bool\s+/', 'function $1(', $content);
    $content = preg_replace('/function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(\s*float\s+/', 'function $1(', $content);
    
    return $content;
}

function convertTernaryOperator($content) {
    // Convertir ?: a ? : con expresión completa
    $pattern = '/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*\?\:\s*([^;,\)]+)/';
    $content = preg_replace($pattern, '($1 ? $1 : $2)', $content);
    
    return $content;
}

$files = [
    __DIR__ . '/middleware/auth.php',
    __DIR__ . '/endpoints/auth.php',
    __DIR__ . '/endpoints/dashboard.php',
    __DIR__ . '/endpoints/consumption.php',
    __DIR__ . '/endpoints/reportcdr.php',
    __DIR__ . '/endpoints/reportconsumo.php',
    __DIR__ . '/endpoints/payhistory.php',
    __DIR__ . '/endpoints/account.php',
];

echo "Convirtiendo archivos a sintaxis PHP 5.5...\n\n";

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "❌ No encontrado: $file\n";
        continue;
    }
    
    $content = file_get_contents($file);
    $original = $content;
    
    // Aplicar conversiones
    $content = convertNullCoalescing($content);
    $content = convertScalarTypeHints($content);
    $content = convertTernaryOperator($content);
    
    if ($content !== $original) {
        // Backup
        $backup = $file . '.php7backup';
        file_put_contents($backup, $original);
        
        // Guardar convertido
        file_put_contents($file, $content);
        echo "✅ Convertido: " . basename($file) . " (backup en " . basename($backup) . ")\n";
    } else {
        echo "ℹ️  Sin cambios: " . basename($file) . "\n";
    }
}

echo "\n✅ Conversión completada!\n";
