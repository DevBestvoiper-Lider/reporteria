#!/bin/bash

echo "🐳 Iniciando API Reportería con Docker..."
echo ""

# Verificar si Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker no está instalado. Por favor, instala Docker Desktop."
    echo "   https://www.docker.com/products/docker-desktop"
    exit 1
fi

# Verificar si Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose no está instalado."
    exit 1
fi

echo "✅ Docker y Docker Compose detectados"
echo ""

# Detener contenedores existentes
echo "🛑 Deteniendo contenedores existentes..."
docker-compose down

# Construir imágenes
echo ""
echo "🔨 Construyendo imágenes Docker..."
docker-compose build --no-cache

# Levantar contenedores
echo ""
echo "🚀 Iniciando contenedores..."
docker-compose up -d

# Esperar a que los servicios estén listos
echo ""
echo "⏳ Esperando a que los servicios estén listos..."
sleep 10

# Verificar estado
echo ""
echo "📊 Estado de los contenedores:"
docker-compose ps

echo ""
echo "✅ ¡Despliegue completado!"
echo ""
echo "🌐 Servicios disponibles:"
echo "   - API:              http://localhost:8080/api/v1/"
echo "   - Documentación:    http://localhost:8080/api/v1/docs/swagger.html"
echo "   - phpMyAdmin:       http://localhost:8081/"
echo ""
echo "📝 Credenciales de Base de Datos:"
echo "   - Host:     db (dentro de Docker) o localhost:3307 (desde host)"
echo "   - Database: reporteria_db"
echo "   - User:     reporteria_user"
echo "   - Password: reporteria_pass"
echo ""
echo "🔍 Comandos útiles:"
echo "   - Ver logs:         docker-compose logs -f"
echo "   - Detener:          docker-compose down"
echo "   - Reiniciar:        docker-compose restart"
echo "   - Shell del web:    docker-compose exec web bash"
echo ""
