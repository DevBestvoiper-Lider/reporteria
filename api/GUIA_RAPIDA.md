# 🚀 API Reportería - Guía de Uso Rápido

## ✅ Instalación Completada

Tu API REST está lista para usar. Aquí tienes todo lo que necesitas saber:

## 📍 URLs Importantes

- **Documentación Swagger (Interactiva)**: `http://localhost/reporteria/api/v1/docs/swagger.html` ⭐
- **Documentación HTML**: `http://localhost/reporteria/api/v1/docs/index.html`
- **Selector de Docs**: `http://localhost/reporteria/api/v1/docs`
- **Endpoint base**: `http://localhost/reporteria/api/v1`

## 🔑 Primer Paso: Autenticación

### 1. Obtener Token

```bash
curl -X POST http://localhost/reporteria/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "tu_usuario",
    "password": "tu_contraseña"
  }'
```

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "token_type": "Bearer",
    "expires_in": 86400
  }
}
```

### 2. Usar el Token

Incluye el token en todas las peticiones:

```bash
curl -X GET http://localhost/reporteria/api/v1/dashboard/today \
  -H "Authorization: Bearer tu_token_aqui"
```

## 📊 Ejemplos de Uso

### Dashboard de Hoy
```bash
GET /api/v1/dashboard/today
```

### Consumo de un Día Específico
```bash
GET /api/v1/consumption/day?date=2025-12-10
```

### Consumo Mensual con Rango de Fechas
```bash
GET /api/v1/consumption/month?start_date=2025-12-01&end_date=2025-12-10
```

### Histórico de Pagos Filtrado
```bash
GET /api/v1/payhistory?start_date=2025-11-01&end_date=2025-12-31
```

### Reporte CDR del Mes
```bash
GET /api/v1/reportcdr/month
```

### Reporte de Consumo por Línea
```bash
GET /api/v1/reportconsumo/month
```

## 🎯 Filtros Disponibles

La API implementa los **mismos filtros** que las vistas web:

### Filtros de Fecha
- `date` - Para consultas de un día (formato: YYYY-MM-DD)
- `start_date` y `end_date` - Para rangos de fechas

### Ejemplos:
- `/consumption/day` - Hoy (por defecto)
- `/consumption/day?date=2025-12-10` - Día específico
- `/consumption/month?start_date=2025-12-01&end_date=2025-12-10` - Rango
- `/payhistory?start_date=2025-11-01` - Desde una fecha

## 🛠️ Testing con Postman

1. Importa la colección: `api/Postman_Collection.json`
2. Configura las variables:
   - `base_url`: `http://localhost/reporteria/api/v1`
   - `token`: (se llenará automáticamente al hacer login)
3. Ejecuta el request "Login" primero
4. Prueba los demás endpoints

## 📦 Estructura Creada

```
api/
└── v1/
    ├── index.php              # Punto de entrada
    ├── .htaccess              # URL rewriting
    ├── config/
    │   └── config.php         # Configuración JWT
    ├── middleware/
    │   └── auth.php           # Autenticación JWT
    ├── helpers/
    │   └── response.php       # Respuestas estandarizadas
    ├── endpoints/
    │   ├── auth.php           # Login y refresh
    │   ├── dashboard.php      # Métricas dashboard
    │   ├── consumption.php    # Consumo diario/mensual
    │   ├── reportcdr.php      # Reportes CDR
    │   ├── reportconsumo.php  # Consumo por línea
    │   ├── payhistory.php     # Histórico de pagos
    │   └── account.php        # Información de cuenta
    └── docs/
        ├── index.html         # Documentación interactiva
        └── index.php          # Redirección
```

## 🔐 Seguridad

### ⚠️ IMPORTANTE - Antes de Producción:

1. **Cambiar la clave JWT**:
   Edita `api/v1/config/config.php` y cambia:
   ```php
   define('JWT_SECRET_KEY', 'TU_CLAVE_SUPER_SECRETA_AQUI');
   ```

2. **Habilitar HTTPS**: La API debe usarse únicamente sobre HTTPS en producción

3. **Configurar CORS**: Edita `api/v1/.htaccess` para restringir orígenes permitidos

4. **Variables de Entorno**: Mover JWT_SECRET_KEY a variables de entorno

## 📖 Documentación Completa

Visita: `http://localhost/reporteria/api/v1/docs`

La documentación incluye:
- ✅ Todos los endpoints disponibles
- ✅ Parámetros requeridos y opcionales
- ✅ Ejemplos de request/response
- ✅ Códigos de error
- ✅ Guías de uso

## 🎨 Características Implementadas

✅ Autenticación JWT con tokens seguros  
✅ Todos los endpoints de las vistas web  
✅ Mismos filtros que las vistas (fechas, rangos)  
✅ Respuestas estandarizadas en JSON  
✅ Documentación completa interactiva  
✅ Colección de Postman lista para usar  
✅ Manejo de errores completo  
✅ CORS habilitado  
✅ Datos formateados (números, fechas, tiempos)  

## 💡 Tips

- El token expira en 24 horas (configurable)
- Usa `/auth/refresh` para obtener un nuevo token sin hacer login
- Todos los valores monetarios vienen en formato numérico y formateado
- Los tiempos están en formato HH:MM:SS
- Las fechas usan formato YYYY-MM-DD

## 🆘 Soporte

Si tienes problemas:
1. Verifica que Apache esté corriendo
2. Verifica que mod_rewrite esté habilitado
3. Revisa los logs de PHP/Apache
4. Consulta la documentación en `/docs`

---

**¡Tu API está lista! 🎉**

Comienza probándola con Postman o desde la documentación interactiva.
