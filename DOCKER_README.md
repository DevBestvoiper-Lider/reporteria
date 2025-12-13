# 🐳 API Reportería - Docker con PHP 8.2

## 📋 Requisitos

- Docker Desktop instalado
- Docker Compose v2+
- Git

## 🚀 Inicio Rápido

### 1. Clonar el repositorio

```bash
git clone https://github.com/DevBestvoiper-Lider/reporteria.git
cd reporteria
```

### 2. Construir y levantar los contenedores

```bash
docker-compose up -d --build
```

### 3. Verificar que los contenedores están corriendo

```bash
docker-compose ps
```

Deberías ver 3 contenedores activos:
- `reporteria-api` (PHP 8.2 + Apache)
- `reporteria-db` (MySQL 8.0)
- `reporteria-phpmyadmin` (phpMyAdmin)

### 4. Acceder a la aplicación

- **API**: http://localhost:8080/api/v1/
- **Documentación Swagger**: http://localhost:8080/api/v1/docs/swagger.html
- **phpMyAdmin**: http://localhost:8081/

## 🔧 Configuración

### Variables de entorno de Base de Datos

Edita `docker-compose.yml` para cambiar las credenciales:

```yaml
environment:
  MYSQL_ROOT_PASSWORD: tu_password_root
  MYSQL_DATABASE: nombre_db
  MYSQL_USER: tu_usuario
  MYSQL_PASSWORD: tu_password
```

### Actualizar conexión en la aplicación

Edita `models/conexion.php`:

```php
$host = "db";  // Nombre del servicio en docker-compose
$database = "reporteria_db";
$username = "reporteria_user";
$password = "reporteria_pass";
```

## 📦 Servicios Incluidos

### 1. Web (PHP 8.2 + Apache)
- **Puerto**: 8080
- **PHP Version**: 8.2
- **Extensiones**: PDO, PDO_MySQL, MySQLi, GD, ZIP, OPcache
- **Módulos Apache**: mod_rewrite, mod_headers

### 2. Base de Datos (MySQL 8.0)
- **Puerto**: 3307 (para evitar conflictos con MySQL local)
- **Credenciales por defecto**:
  - Root: `rootpassword`
  - Usuario: `reporteria_user`
  - Password: `reporteria_pass`
  - Base de datos: `reporteria_db`

### 3. phpMyAdmin
- **Puerto**: 8081
- **Acceso**: root / rootpassword

## 🛠️ Comandos Útiles

### Ver logs en tiempo real

```bash
# Todos los servicios
docker-compose logs -f

# Solo el servicio web
docker-compose logs -f web

# Solo la base de datos
docker-compose logs -f db
```

### Reiniciar servicios

```bash
# Reiniciar todo
docker-compose restart

# Reiniciar solo web
docker-compose restart web
```

### Detener servicios

```bash
# Detener sin eliminar
docker-compose stop

# Detener y eliminar contenedores
docker-compose down

# Detener y eliminar TODO (incluye volúmenes)
docker-compose down -v
```

### Acceder al contenedor

```bash
# Shell interactivo en el contenedor web
docker-compose exec web bash

# Ejecutar comandos PHP
docker-compose exec web php -v
docker-compose exec web php /var/www/html/reporteria/api/v1/test.php
```

### Importar base de datos

```bash
# Desde un archivo SQL
docker-compose exec -T db mysql -uroot -prootpassword reporteria_db < backup.sql

# O copiar el archivo al contenedor
docker cp backup.sql reporteria-db:/tmp/
docker-compose exec db mysql -uroot -prootpassword reporteria_db -e "source /tmp/backup.sql"
```

### Ver información de contenedores

```bash
# Estado de contenedores
docker-compose ps

# Uso de recursos
docker stats reporteria-api reporteria-db

# Inspeccionar red
docker network inspect reporteria_reporteria-network
```

## 🔍 Troubleshooting

### El puerto 8080 ya está en uso

Cambia el puerto en `docker-compose.yml`:

```yaml
ports:
  - "8090:80"  # Cambiar 8080 por otro puerto disponible
```

### Error de conexión a la base de datos

1. Verifica que el contenedor db esté corriendo: `docker-compose ps`
2. Verifica las credenciales en `models/conexion.php`
3. El host debe ser `db` (nombre del servicio), no `localhost`

### Permisos de archivos

```bash
# Dar permisos al usuario www-data dentro del contenedor
docker-compose exec web chown -R www-data:www-data /var/www/html/reporteria
docker-compose exec web chmod -R 755 /var/www/html/reporteria
```

### Limpiar caché de Docker

```bash
# Eliminar contenedores parados
docker container prune

# Eliminar imágenes sin usar
docker image prune

# Limpieza completa (cuidado!)
docker system prune -a
```

## 🔄 Actualizar la aplicación

```bash
# 1. Detén los contenedores
docker-compose down

# 2. Actualiza el código
git pull origin main

# 3. Reconstruye la imagen
docker-compose build --no-cache

# 4. Inicia de nuevo
docker-compose up -d
```

## 📊 Desarrollo vs Producción

### Desarrollo (local)

El `docker-compose.yml` actual está configurado para desarrollo con:
- Volúmenes montados para hot-reload
- Puertos expuestos localmente
- phpMyAdmin incluido

### Producción

Para producción, crea `docker-compose.prod.yml`:

```yaml
version: '3.8'

services:
  web:
    build: .
    container_name: reporteria-api
    ports:
      - "80:80"
    environment:
      - APACHE_DOCUMENT_ROOT=/var/www/html/reporteria
    networks:
      - reporteria-network
    depends_on:
      - db
    restart: always

  db:
    image: mysql:8.0
    container_name: reporteria-db
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_USER: ${DB_USER}
      MYSQL_PASSWORD: ${DB_PASSWORD}
    volumes:
      - mysql-data:/var/lib/mysql
    networks:
      - reporteria-network
    restart: always

networks:
  reporteria-network:
    driver: bridge

volumes:
  mysql-data:
```

Ejecutar con: `docker-compose -f docker-compose.prod.yml up -d`

## 🌐 Deploy en Servidor

### Opción 1: Docker directo en servidor

```bash
# 1. Copiar archivos al servidor
scp -r reporteria/ user@server:/path/to/

# 2. SSH al servidor
ssh user@server

# 3. Navegar al directorio
cd /path/to/reporteria

# 4. Levantar contenedores
docker-compose up -d
```

### Opción 2: Docker Hub

```bash
# 1. Construir imagen
docker build -t tu-usuario/reporteria-api:latest .

# 2. Subir a Docker Hub
docker push tu-usuario/reporteria-api:latest

# 3. En el servidor
docker pull tu-usuario/reporteria-api:latest
docker run -d -p 80:80 tu-usuario/reporteria-api:latest
```

### Opción 3: Registry privado

```bash
# 1. Tag para registry privado
docker tag reporteria-api:latest registry.tu-dominio.com/reporteria-api:latest

# 2. Push al registry
docker push registry.tu-dominio.com/reporteria-api:latest
```

## 🔐 Seguridad

### Variables de entorno

Crea un archivo `.env`:

```env
DB_ROOT_PASSWORD=password_seguro_root
DB_DATABASE=reporteria_db
DB_USER=reporteria_user
DB_PASSWORD=password_seguro_user
JWT_SECRET_KEY=clave_secreta_muy_larga_y_segura
```

Úsalo en `docker-compose.yml`:

```yaml
environment:
  MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
  MYSQL_DATABASE: ${DB_DATABASE}
  MYSQL_USER: ${DB_USER}
  MYSQL_PASSWORD: ${DB_PASSWORD}
```

### HTTPS con reverse proxy (Nginx/Traefik)

Para producción, usa un reverse proxy con SSL:

```yaml
services:
  nginx:
    image: nginx:alpine
    ports:
      - "443:443"
      - "80:80"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf
      - ./ssl:/etc/nginx/ssl
```

## 📈 Monitoreo

### Health checks

Agregar en `docker-compose.yml`:

```yaml
services:
  web:
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost/api/v1/"]
      interval: 30s
      timeout: 10s
      retries: 3
```

### Logs centralizados

```bash
# Ver logs de todos los servicios
docker-compose logs -f --tail=100
```

## ✅ Checklist de Deploy

- [ ] Variables de entorno configuradas
- [ ] Credenciales de BD actualizadas
- [ ] JWT_SECRET_KEY cambiado
- [ ] Base de datos importada
- [ ] Puertos correctos configurados
- [ ] Health checks funcionando
- [ ] SSL configurado (producción)
- [ ] Backups programados
- [ ] Logs configurados
- [ ] Documentación actualizada

## 🆘 Soporte

Si tienes problemas:

1. Revisa los logs: `docker-compose logs -f`
2. Verifica el estado: `docker-compose ps`
3. Prueba el test: http://localhost:8080/api/v1/test.php
4. Accede al contenedor: `docker-compose exec web bash`

