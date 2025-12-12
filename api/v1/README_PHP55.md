# API Reportería - Compatibilidad PHP 5.5

## ⚠️ IMPORTANTE

Esta API ha sido actualizada para ser compatible con **PHP 5.5.38**.

## ✅ Cambios Realizados

Se han eliminado las siguientes características de PHP 7+:

1. **Operador Null Coalescing (`??`)** - Convertido a operador ternario con `isset()`
2. **Error class** - Removida del catch (solo Exception)
3. **Sintaxis de arrays cortos en funciones** - Ajustados para PHP 5.5

## 📋 Archivos Modificados

- `index.php` - Router principal
- `middleware/auth.php` - Autenticación JWT
- `endpoints/auth.php` - Login y refresh token
- `endpoints/dashboard.php` - Dashboard metrics
- `endpoints/consumption.php` - Consumo de llamadas
- `endpoints/reportcdr.php` - Reportes CDR
- `endpoints/reportconsumo.php` - Consumo agrupado
- `endpoints/payhistory.php` - Historial de pagos
- `endpoints/account.php` - Información de cuenta

## 🚀 Despliegue en Servidor

### 1. Verificar Requisitos

```bash
php -v  # Debe ser >= 5.5
```

### 2. Verificar Módulos Apache

```bash
httpd -M | grep rewrite
httpd -M | grep headers
```

Deben estar habilitados:
- `mod_rewrite`
- `mod_headers`

### 3. Activar módulos (si no están activos)

```bash
# En el archivo httpd.conf, descomentar:
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule headers_module modules/mod_headers.so
```

### 4. Verificar AllowOverride

En la configuración del VirtualHost o Directory:

```apache
<Directory "/ruta/a/reporteria/api/v1">
    AllowOverride All
    Require all granted
</Directory>
```

### 5. Reiniciar Apache

```bash
systemctl restart httpd
# o
service httpd restart
```

### 6. Probar la API

```bash
# Test básico
curl http://tu-servidor.com/reporteria/api/v1/

# Debe retornar JSON con información de la API
```

## 🔍 Diagnóstico de Errores

Si persiste el error 500:

### 1. Verificar logs de Apache

```bash
tail -f /var/log/httpd/error_log
```

### 2. Acceder al script de diagnóstico

```
http://tu-servidor.com/reporteria/api/v1/test.php
```

Este script mostrará:
- Versión de PHP
- Módulos Apache activos
- Archivos requeridos
- Permisos
- Extensiones PHP
- Constantes definidas

### 3. Verificar permisos

```bash
chmod 755 /ruta/a/reporteria/api/v1
chmod 644 /ruta/a/reporteria/api/v1/*.php
chmod 644 /ruta/a/reporteria/api/v1/.htaccess
```

### 4. SELinux (si está activo)

```bash
# Verificar si SELinux está bloqueando
getenforce

# Si está en "Enforcing", temporalmente:
setenforce 0

# O configurar contexto correcto:
chcon -R -t httpd_sys_content_t /ruta/a/reporteria/api/v1/
```

## 📝 Extensiones PHP Requeridas

- `pdo`
- `pdo_mysql`
- `json`
- `mbstring`
- `openssl`

Verificar con:

```bash
php -m | grep -E 'pdo|mysql|json|mbstring|openssl'
```

## ✅ Checklist de Verificación

- [ ] PHP >= 5.5 instalado
- [ ] mod_rewrite habilitado
- [ ] mod_headers habilitado
- [ ] AllowOverride All configurado
- [ ] Permisos correctos (755 para directorios, 644 para archivos)
- [ ] Extensiones PHP requeridas instaladas
- [ ] Apache reiniciado después de cambios
- [ ] test.php muestra todos ✅
- [ ] API responde en `/reporteria/api/v1/`

## 🆘 Soporte

Si después de verificar todo lo anterior persiste el error:

1. Revisa los logs: `tail -f /var/log/httpd/error_log`
2. Activa display_errors en `index.php` (línea 11): `ini_set('display_errors', 1);`
3. Accede a la API y copia el mensaje de error completo
4. Verifica que la ruta en `.htaccess` coincida con tu estructura: `RewriteBase /reporteria/api/v1/`
