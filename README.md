# 📊 API Reportería - Sistema de Reportes Telefónicos

API REST moderna construida con PHP 8.2 para consultar reportes de consumo, CDR y pagos de sistemas de telefonía.

## 🌟 Características

- ✅ **PHP 8.2** con sintaxis moderna
- ✅ **Docker** para despliegue fácil
- ✅ **JWT Authentication** para seguridad
- ✅ **Swagger/OpenAPI** documentación interactiva
- ✅ **REST API** con endpoints bien definidos
- ✅ **MySQL 8.0** base de datos
- ✅ **Apache** con mod_rewrite
- ✅ **CORS** habilitado

## 🚀 Inicio Rápido con Docker (Recomendado)

### Windows

```cmd
start-docker.bat
```

### Linux/Mac

```bash
chmod +x start-docker.sh
./start-docker.sh
```

### Manual

```bash
docker-compose up -d --build
```

**Accede a:**
- API: http://localhost:8080/api/v1/
- Documentación: http://localhost:8080/api/v1/docs/swagger.html
- phpMyAdmin: http://localhost:8081/

## 📦 Instalación Sin Docker

### Requisitos

- PHP >= 8.0
- MySQL >= 5.7
- Apache con mod_rewrite
- Composer

### Pasos

1. Clonar repositorio:
```bash
git clone https://github.com/DevBestvoiper-Lider/reporteria.git
cd reporteria
```

2. Instalar dependencias:
```bash
composer install
```

3. Configurar base de datos en `models/conexion.php`

4. Configurar Apache VirtualHost apuntando a la carpeta `reporteria`

5. Importar base de datos desde `db/`

## 📚 Documentación

- **[Docker Setup](DOCKER_README.md)** - Guía completa de Docker
- **[API Documentation](api/README.md)** - Guía de la API
- **[Swagger UI](http://localhost:8080/api/v1/docs/swagger.html)** - Documentación interactiva

## 🔌 Endpoints Principales

### Autenticación

```bash
POST /api/v1/auth/login
POST /api/v1/auth/refresh
```

### Reportes

```bash
GET  /api/v1/consumption/day?date=2024-01-15
GET  /api/v1/consumption/month?start_date=2024-01-01&end_date=2024-01-31
GET  /api/v1/reportcdr/month?start_date=2024-01-01&end_date=2024-01-31
GET  /api/v1/reportconsumo/month
GET  /api/v1/payhistory?start_date=2024-01-01&end_date=2024-01-31
```

### Cuenta

```bash
GET  /api/v1/account
```

## 🔐 Autenticación

La API usa JWT (JSON Web Tokens). Para autenticarte:

1. **Obtén un token:**

```bash
curl -X POST http://localhost:8080/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "tu_usuario",
    "password": "tu_contraseña"
  }'
```

2. **Usa el token en las peticiones:**

```bash
curl -X GET http://localhost:8080/api/v1/consumption/day?date=2024-01-15 \
  -H "Authorization: Bearer TU_TOKEN_JWT"
```

## 🏗️ Estructura del Proyecto

```
reporteria/
├── api/
│   └── v1/
│       ├── config/           # Configuración (JWT, etc)
│       ├── middleware/       # Autenticación JWT
│       ├── helpers/          # Funciones helper
│       ├── endpoints/        # Endpoints de la API
│       ├── docs/            # Documentación Swagger
│       ├── index.php        # Router principal
│       └── .htaccess        # Reglas Apache
├── controllers/             # Lógica de negocio
├── models/                  # Acceso a datos
├── views/                   # Vistas web (legacy)
├── vendor/                  # Dependencias Composer
├── Dockerfile              # Imagen Docker
├── docker-compose.yml      # Orquestación Docker
└── README.md              # Este archivo
```

## 🛠️ Tecnologías

- **Backend**: PHP 8.2
- **Base de Datos**: MySQL 8.0
- **Servidor Web**: Apache 2.4
- **Autenticación**: JWT (JSON Web Tokens)
- **Documentación**: Swagger/OpenAPI 3.0
- **Containerización**: Docker & Docker Compose
- **Dependencias**: Composer (Dompdf, etc)

## 🔧 Configuración

### Variables de Entorno

Copia `.env.example` a `.env` y ajusta:

```env
APP_ENV=development
DB_HOST=db
DB_NAME=reporteria_db
DB_USER=reporteria_user
DB_PASS=reporteria_pass
JWT_SECRET_KEY=tu_clave_secreta
```

### Base de Datos

**Docker**: Automáticamente configurado

**Sin Docker**: Edita `models/conexion.php`

## 📊 Base de Datos

La API se conecta a una base de datos MySQL con las siguientes tablas principales:

- `e_cdr_*` - Call Detail Records por fecha
- `e_reportcustomerfee_*` - Reportes de consumo
- `usuarios` - Usuarios del sistema

## 🐳 Docker

### Servicios Incluidos

- **web**: PHP 8.2 + Apache (puerto 8080)
- **db**: MySQL 8.0 (puerto 3307)
- **phpmyadmin**: Administración BD (puerto 8081)

### Comandos Docker Útiles

```bash
# Iniciar
docker-compose up -d

# Ver logs
docker-compose logs -f

# Detener
docker-compose down

# Reiniciar
docker-compose restart

# Shell del contenedor
docker-compose exec web bash

# Ejecutar PHP
docker-compose exec web php -v
```

## 🧪 Testing

### Verificar API

```bash
# Test básico
curl http://localhost:8080/api/v1/

# Test con diagnóstico
curl http://localhost:8080/api/v1/test.php
```

## 📈 Desarrollo

### Agregar un nuevo endpoint

1. Crea el archivo en `api/v1/endpoints/mi_endpoint.php`
2. Agrega el case en `api/v1/index.php`
3. Implementa la lógica en el controller
4. Actualiza la documentación Swagger

### Hot Reload

Con Docker, los cambios en el código se reflejan automáticamente gracias a los volúmenes montados.

## 🚀 Despliegue en Producción

### Con Docker

```bash
# 1. En el servidor
git clone https://github.com/DevBestvoiper-Lider/reporteria.git
cd reporteria

# 2. Configurar variables de entorno
cp .env.example .env
nano .env

# 3. Levantar en modo producción
docker-compose -f docker-compose.prod.yml up -d
```

### Sin Docker (Apache tradicional)

1. Subir archivos al servidor
2. Configurar VirtualHost de Apache
3. Habilitar mod_rewrite y mod_headers
4. Configurar conexión a BD
5. Dar permisos apropiados

Ver [DOCKER_README.md](DOCKER_README.md) para más detalles.

## 🔒 Seguridad

- ✅ Autenticación JWT
- ✅ Validación de tokens
- ✅ Prepared statements (PDO)
- ✅ CORS configurado
- ✅ Error handling robusto
- ⚠️ Cambiar JWT_SECRET_KEY en producción
- ⚠️ Usar HTTPS en producción
- ⚠️ Restringir CORS en producción

## 📝 Logs

### Docker

```bash
# Logs de la API
docker-compose logs -f web

# Logs de MySQL
docker-compose logs -f db
```

### Apache tradicional

```bash
# Error log
tail -f /var/log/apache2/error.log

# Access log
tail -f /var/log/apache2/access.log
```

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama (`git checkout -b feature/AmazingFeature`)
3. Commit cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto es privado y pertenece a Best Voiper.

## 👥 Autores

- **DevBestvoiper-Lider** - Desarrollo inicial

## 🆘 Soporte

Para problemas o preguntas:

1. Revisa la [documentación](DOCKER_README.md)
2. Verifica los [logs](#-logs)
3. Ejecuta el [diagnóstico](#-testing)
4. Abre un issue en GitHub

## 📞 Contacto

- Email: soporte@bestvoiper.com
- GitHub: [@DevBestvoiper-Lider](https://github.com/DevBestvoiper-Lider)

---

**Última actualización**: Diciembre 2025
**Versión API**: 1.0.0
**PHP Version**: 8.2
