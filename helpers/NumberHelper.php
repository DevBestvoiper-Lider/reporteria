<?php
/**
 * Helper para manejar números, especialmente fees en notación científica
 */
class NumberHelper {
    
    /**
     * Convierte un número de notación científica a decimal
     * Ejemplo: 1.0E-7 -> 0.0000001
     * 
     * @param mixed $number El número a convertear
     * @param int $decimals Número de decimales (default: 10)
     * @return string El número formateado como string decimal
     */
    public static function fromScientific($number, $decimals = 10) {
        if ($number === null || $number === '') {
            return '0';
        }
        
        // Si es notación científica, convertir primero
        if (stripos($number, 'e') !== false || stripos($number, 'E') !== false) {
            $number = sprintf("%.{$decimals}f", (float)$number);
        }
        
        // Eliminar ceros a la derecha innecesarios pero mantener al menos un decimal
        $number = rtrim(rtrim(number_format((float)$number, $decimals, '.', ''), '0'), '.');
        
        return $number;
    }
    
    /**
     * Formatea un fee para mostrar, manejando notación científica
     * 
     * @param mixed $fee El valor del fee
     * @param int $decimals Decimales a mostrar (default: 8)
     * @param string $decimalSep Separador decimal
     * @param string $thousandSep Separador de miles
     * @return string El fee formateado
     */
    public static function formatFee($fee, $decimals = 8, $decimalSep = ',', $thousandSep = '.') {
        if ($fee === null || $fee === '') {
            return '0';
        }
        
        // Convertir de notación científica si es necesario
        $feeDecimal = self::fromScientific($fee, $decimals + 2);
        
        // Formatear con number_format
        return number_format((float)$feeDecimal, $decimals, $decimalSep, $thousandSep);
    }
    
    /**
     * Formatea un fee eliminando ceros innecesarios a la derecha
     * 
     * @param mixed $fee El valor del fee
     * @param int $maxDecimals Máximo de decimales (default: 10)
     * @return string El fee formateado sin ceros extras
     */
    public static function formatFeeClean($fee, $maxDecimals = 10) {
        if ($fee === null || $fee === '') {
            return '0';
        }
        
        // Convertir de notación científica
        $feeDecimal = sprintf("%.{$maxDecimals}f", (float)$fee);
        
        // Eliminar ceros a la derecha
        $feeDecimal = rtrim($feeDecimal, '0');
        $feeDecimal = rtrim($feeDecimal, '.');
        
        return $feeDecimal ?: '0';
    }
    
    /**
     * Suma un array de fees manejando notación científica
     * 
     * @param array $fees Array de fees
     * @return string La suma formateada
     */
    public static function sumFees(array $fees) {
        $total = 0;
        foreach ($fees as $fee) {
            // Convertir de notación científica antes de sumar
            $total += (float) self::fromScientific($fee);
        }
        return self::formatFeeClean($total);
    }
}
