# API Reportería - Documentación

## 🚀 Inicio Rápido

### Instalación

1. La API ya está lista para usar en tu instalación actual de Laragon
2. Accede a la documentación completa en: `http://localhost/reporteria/api/v1/docs`

### Primer Uso

1. **Obtener un token de autenticación:**

```bash
POST http://localhost/reporteria/api/v1/auth/login
Content-Type: application/json

{
  "username": "tu_usuario",
  "password": "tu_contraseña"
}
```

2. **Usar el token en las peticiones:**

```bash
GET http://localhost/reporteria/api/v1/dashboard/today
Authorization: Bearer {tu_token}
```

## 📚 Endpoints Disponibles

### Documentación Interactiva
- **Swagger UI**: `http://localhost/reporteria/api/v1/docs/swagger.html` ⭐ **Recomendado**
- **Documentación HTML**: `http://localhost/reporteria/api/v1/docs/index.html`
- **Selector**: `http://localhost/reporteria/api/v1/docs`

### Autenticación
- `POST /api/v1/auth/login` - Iniciar sesión
- `POST /api/v1/auth/refresh` - Refrescar token

### Dashboard
- `GET /api/v1/dashboard/today` - Datos de hoy
- `GET /api/v1/dashboard/yesterday` - Datos de ayer
- `GET /api/v1/dashboard/month` - Datos del mes
- `GET /api/v1/dashboard/year` - Datos del año
- `GET /api/v1/dashboard/summary` - Resumen completo

### Consumo
- `GET /api/v1/consumption/day?date=YYYY-MM-DD` - Consumo diario
- `GET /api/v1/consumption/month?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD` - Consumo por rango

### Reportes CDR
- `GET /api/v1/reportcdr/month` - Reporte CDR del mes

### Reportes de Consumo por Línea
- `GET /api/v1/reportconsumo/month` - Consumo agrupado por línea

### Histórico de Pagos
- `GET /api/v1/payhistory?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD` - Histórico de pagos

### Mi Cuenta
- `GET /api/v1/account` - Información de cuenta
- `PUT /api/v1/account/password` - Cambiar contraseña

## 🔧 Configuración

### Variables de Entorno

Edita `api/v1/config/config.php` para configurar:

- `JWT_SECRET_KEY`: Clave secreta para JWT (IMPORTANTE: cambiar en producción)
- `JWT_EXPIRATION`: Tiempo de expiración del token (default: 24 horas)
- `API_TIMEZONE`: Zona horaria (default: America/Bogota)

## 🎯 Filtros

La API implementa los mismos filtros que las vistas web:

### Filtros de Fecha
- **date**: Para consultas de un día específico (formato: YYYY-MM-DD)
- **start_date / end_date**: Para rangos de fechas

### Ejemplos:

```bash
# Consumo de un día específico
GET /api/v1/consumption/day?date=2025-12-10

# Consumo de un rango de fechas
GET /api/v1/consumption/month?start_date=2025-12-01&end_date=2025-12-10

# Histórico de pagos filtrado
GET /api/v1/payhistory?start_date=2025-11-01&end_date=2025-12-31
```

## 📊 Formato de Respuestas

### Respuesta Exitosa
```json
{
  "success": true,
  "message": "OK",
  "data": { ... },
  "timestamp": "2025-12-12 10:30:45"
}
```

### Respuesta de Error
```json
{
  "success": false,
  "message": "Descripción del error",
  "timestamp": "2025-12-12 10:30:45",
  "errors": { ... }
}
```

## 🔐 Seguridad

1. **Autenticación JWT**: Todos los endpoints (excepto login) requieren token
2. **HTTPS**: Usar HTTPS en producción
3. **Cambiar JWT_SECRET_KEY**: Usar una clave segura en producción
4. **CORS**: Configurado para permitir todas las origenes (ajustar en producción)

## 📝 Notas

- Todos los valores monetarios están en formato sin decimales
- Los tiempos están en formato HH:MM:SS
- Las fechas están en formato YYYY-MM-DD
- Zona horaria: America/Bogota
- Versión actual: 1.0

## 🛠️ Estructura de Archivos

```
api/
└── v1/
    ├── index.php                # Punto de entrada principal
    ├── config/
    │   └── config.php          # Configuración
    ├── middleware/
    │   └── auth.php            # Middleware de autenticación
    ├── helpers/
    │   └── response.php        # Helper de respuestas
    ├── endpoints/
    │   ├── auth.php            # Endpoint de autenticación
    │   ├── dashboard.php       # Endpoint de dashboard
    │   ├── consumption.php     # Endpoint de consumo
    │   ├── reportcdr.php       # Endpoint de reportes CDR
    │   ├── reportconsumo.php   # Endpoint de reportes por línea
    │   ├── payhistory.php      # Endpoint de histórico de pagos
    │   └── account.php         # Endpoint de cuenta
    └── docs/
        ├── index.html          # Documentación HTML
        └── index.php           # Redirección
```

## 🌐 Testing con Postman

Importa esta colección para probar la API:

1. Crea una nueva colección en Postman
2. Agrega la variable de entorno `base_url` = `http://localhost/reporteria/api/v1`
3. Agrega la variable de entorno `token` (se llenará después del login)
4. Importa los endpoints desde la documentación

## 📞 Soporte

Para más información, consulta la documentación completa en:
`http://localhost/reporteria/api/v1/docs`
