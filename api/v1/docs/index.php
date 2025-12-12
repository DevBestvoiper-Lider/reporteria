<?php
/**
 * Selector de documentación
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Reportería - Documentación</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            width: 100%;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        
        .header h1 {
            font-size: 3em;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .version {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            margin-top: 10px;
            font-size: 0.9em;
        }
        
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.2);
        }
        
        .card-icon {
            font-size: 4em;
            margin-bottom: 15px;
        }
        
        .card h2 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 1.5em;
        }
        
        .card p {
            color: #666;
            line-height: 1.6;
        }
        
        .badge {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85em;
            margin-top: 10px;
        }
        
        .recommended {
            position: relative;
            border: 2px solid #667eea;
        }
        
        .recommended::before {
            content: "✨ Recomendado";
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 API Reportería</h1>
            <p>Documentación Completa</p>
            <div class="version">Versión 1.0</div>
        </div>
        
        <div class="cards">
            <a href="swagger.html" class="card recommended">
                <div class="card-icon">🚀</div>
                <h2>Swagger UI</h2>
                <p>Documentación interactiva OpenAPI 3.0 con prueba de endpoints en tiempo real</p>
                <span class="badge">Probar API</span>
            </a>
            
            <a href="index.html" class="card">
                <div class="card-icon">📖</div>
                <h2>Documentación HTML</h2>
                <p>Guía completa con ejemplos de código y casos de uso detallados</p>
                <span class="badge">Leer Docs</span>
            </a>
        </div>
    </div>
</body>
</html>
