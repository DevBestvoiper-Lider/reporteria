@echo off
echo.
echo ================================
echo  API Reporteria - Docker Setup
echo ================================
echo.

REM Verificar Docker
where docker >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Docker no esta instalado.
    echo Por favor instala Docker Desktop desde:
    echo https://www.docker.com/products/docker-desktop
    pause
    exit /b 1
)

echo [OK] Docker detectado
echo.

REM Verificar Docker Compose
where docker-compose >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Docker Compose no esta instalado.
    pause
    exit /b 1
)

echo [OK] Docker Compose detectado
echo.

REM Detener contenedores existentes
echo Deteniendo contenedores existentes...
docker-compose down
echo.

REM Construir imagenes
echo Construyendo imagenes Docker...
docker-compose build --no-cache
echo.

REM Levantar contenedores
echo Iniciando contenedores...
docker-compose up -d
echo.

REM Esperar
echo Esperando a que los servicios esten listos...
timeout /t 10 /nobreak > nul
echo.

REM Verificar estado
echo Estado de los contenedores:
docker-compose ps
echo.

echo.
echo ================================
echo  Despliegue completado!
echo ================================
echo.
echo Servicios disponibles:
echo   - API:              http://localhost:8080/api/v1/
echo   - Documentacion:    http://localhost:8080/api/v1/docs/swagger.html
echo   - phpMyAdmin:       http://localhost:8081/
echo.
echo Credenciales de Base de Datos:
echo   - Host:     db (dentro de Docker) o localhost:3307 (desde host)
echo   - Database: reporteria_db
echo   - User:     reporteria_user
echo   - Password: reporteria_pass
echo.
echo Comandos utiles:
echo   - Ver logs:         docker-compose logs -f
echo   - Detener:          docker-compose down
echo   - Reiniciar:        docker-compose restart
echo   - Shell del web:    docker-compose exec web bash
echo.
pause
