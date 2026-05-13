#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script para mapear completamente la base de datos VOS3000
"""

import pymysql
import json
from datetime import datetime
from collections import defaultdict

def conectar_db():
    """Conectar a la base de datos VOS3000"""
    try:
        # Credenciales del servidor de desarrollo
        conexion = pymysql.connect(
            host='sw3.bestvoiper.com',
            user='developers',
            password='Luisda0806*++',
            database='vos3000',
            charset='utf8',  # Usar utf8 para compatibilidad con MySQL antiguo
            connect_timeout=30
        )
        print("✓ Conexión exitosa al servidor VOS3000 (sw3.bestvoiper.com)")
        return conexion
    except Exception as e:
        print(f"✗ Error al conectar: {e}")
        print("\nIntentando conexión alternativa...")
        try:
            # Intentar sin especificar base de datos
            conexion = pymysql.connect(
                host='sw3.bestvoiper.com',
                user='developers',
                password='Luisda0806*++',
                charset='utf8',
                connect_timeout=30
            )
            print("✓ Conexión exitosa al servidor MySQL")
            
            # Listar bases de datos disponibles
            cursor = conexion.cursor()
            cursor.execute("SHOW DATABASES")
            databases = [db[0] for db in cursor.fetchall()]
            
            print("\n📁 Bases de datos disponibles:")
            for db in databases:
                print(f"  • {db}")
            
            if 'vos3000' in databases:
                conexion.select_db('vos3000')
                print(f"\n✓ Usando base de datos: vos3000")
                return conexion
            else:
                print("\n⚠ Base de datos 'vos3000' no encontrada")
                cursor.close()
                conexion.close()
                return None
                
        except Exception as e2:
            print(f"✗ Error en conexión alternativa: {e2}")
            return None

def listar_tablas(cursor):
    """Obtener lista de todas las tablas"""
    cursor.execute("SHOW TABLES")
    tablas = [tabla[0] for tabla in cursor.fetchall()]
    return tablas

def obtener_estructura_tabla(cursor, tabla):
    """Obtener la estructura completa de una tabla"""
    try:
        cursor.execute(f"DESCRIBE `{tabla}`")
        columnas = cursor.fetchall()
        
        estructura = []
        for col in columnas:
            estructura.append({
                'campo': col[0],
                'tipo': col[1],
                'nulo': col[2],
                'key': col[3],
                'default': col[4],
                'extra': col[5]
            })
        
        return estructura
    except Exception as e:
        print(f"  ✗ Error al obtener estructura de {tabla}: {e}")
        return []

def contar_registros(cursor, tabla):
    """Contar registros en una tabla"""
    try:
        cursor.execute(f"SELECT COUNT(*) FROM `{tabla}`")
        return cursor.fetchone()[0]
    except Exception as e:
        return 0

def obtener_muestra_datos(cursor, tabla, limite=3):
    """Obtener muestra de datos de una tabla"""
    try:
        cursor.execute(f"SELECT * FROM `{tabla}` LIMIT {limite}")
        return cursor.fetchall()
    except Exception as e:
        return []

def clasificar_tablas(tablas):
    """Clasificar tablas por tipo"""
    clasificacion = {
        'reportes_fee': [],
        'reportes_cdr': [],
        'configuracion': [],
        'clientes': [],
        'usuarios': [],
        'firewall': [],
        'otras': []
    }
    
    for tabla in tablas:
        if 'report' in tabla.lower() and 'fee' in tabla.lower():
            clasificacion['reportes_fee'].append(tabla)
        elif 'report' in tabla.lower() and 'cdr' in tabla.lower():
            clasificacion['reportes_cdr'].append(tabla)
        elif 'customer' in tabla.lower() or 'cliente' in tabla.lower():
            clasificacion['clientes'].append(tabla)
        elif 'usuario' in tabla.lower() or 'user' in tabla.lower():
            clasificacion['usuarios'].append(tabla)
        elif 'firewall' in tabla.lower() or 'ip' in tabla.lower():
            clasificacion['firewall'].append(tabla)
        elif tabla.startswith('e_') and not 'report' in tabla.lower():
            clasificacion['configuracion'].append(tabla)
        else:
            clasificacion['otras'].append(tabla)
    
    return clasificacion

def analizar_tablas_reportes_fee(cursor, tablas_fee):
    """Analizar específicamente las tablas de reportes de fee"""
    print("\n" + "="*80)
    print("ANÁLISIS DETALLADO DE TABLAS DE REPORTES FEE")
    print("="*80)
    
    # Agrupar por tipo
    tipos_reportes = defaultdict(list)
    for tabla in tablas_fee:
        # Extraer el tipo (parte antes de la fecha)
        partes = tabla.rsplit('_', 1)
        if len(partes) == 2:
            tipo = partes[0]
            fecha = partes[1]
            if len(fecha) == 8 and fecha.isdigit():
                tipos_reportes[tipo].append((tabla, fecha))
    
    for tipo, tablas_fechas in sorted(tipos_reportes.items()):
        print(f"\n📊 Tipo: {tipo}")
        print(f"   Total de tablas: {len(tablas_fechas)}")
        
        # Ordenar por fecha
        tablas_fechas.sort(key=lambda x: x[1], reverse=True)
        
        # Mostrar las 5 más recientes
        print(f"   Tablas más recientes:")
        for tabla, fecha in tablas_fechas[:5]:
            count = contar_registros(cursor, tabla)
            fecha_formateada = f"{fecha[0:4]}-{fecha[4:6]}-{fecha[6:8]}"
            print(f"     - {tabla} ({fecha_formateada}): {count:,} registros")
        
        # Obtener estructura de la tabla más reciente
        if tablas_fechas:
            tabla_reciente = tablas_fechas[0][0]
            print(f"\n   Estructura de {tabla_reciente}:")
            estructura = obtener_estructura_tabla(cursor, tabla_reciente)
            for col in estructura:
                key_info = f" [{col['key']}]" if col['key'] else ""
                print(f"     • {col['campo']:<30} {col['tipo']:<20} {key_info}")

def mapear_base_datos():
    """Función principal para mapear toda la base de datos"""
    conexion = conectar_db()
    if not conexion:
        return
    
    cursor = conexion.cursor()
    
    print("\n" + "="*80)
    print("MAPEO COMPLETO DE BASE DE DATOS VOS3000")
    print("="*80)
    print(f"Fecha: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    
    # Obtener todas las tablas
    tablas = listar_tablas(cursor)
    print(f"\n📁 Total de tablas encontradas: {len(tablas)}")
    
    # Clasificar tablas
    clasificacion = clasificar_tablas(tablas)
    
    print("\n" + "="*80)
    print("CLASIFICACIÓN DE TABLAS")
    print("="*80)
    for categoria, lista_tablas in clasificacion.items():
        if lista_tablas:
            print(f"\n{categoria.upper().replace('_', ' ')}: {len(lista_tablas)} tablas")
            if categoria == 'reportes_fee':
                # Para reportes fee, mostrar solo un resumen
                tipos = set()
                for t in lista_tablas:
                    partes = t.rsplit('_', 1)
                    if len(partes) == 2:
                        tipos.add(partes[0])
                for tipo in sorted(tipos):
                    count = len([t for t in lista_tablas if t.startswith(tipo)])
                    print(f"  • {tipo}: {count} tablas diarias")
            else:
                for tabla in sorted(lista_tablas)[:10]:
                    count = contar_registros(cursor, tabla)
                    print(f"  • {tabla}: {count:,} registros")
                if len(lista_tablas) > 10:
                    print(f"  ... y {len(lista_tablas) - 10} tablas más")
    
    # Analizar tablas de reportes fee en detalle
    if clasificacion['reportes_fee']:
        analizar_tablas_reportes_fee(cursor, clasificacion['reportes_fee'])
    
    # Analizar tablas principales de configuración
    print("\n" + "="*80)
    print("TABLAS PRINCIPALES DE CONFIGURACIÓN")
    print("="*80)
    
    tablas_importantes = [
        'e_customer',
        'e_gateway', 
        'e_clienttype',
        'e_usuariosclientes',
        'e_reseller'
    ]
    
    for tabla in tablas_importantes:
        if tabla in tablas:
            print(f"\n📋 {tabla.upper()}")
            count = contar_registros(cursor, tabla)
            print(f"   Registros: {count:,}")
            print(f"   Estructura:")
            estructura = obtener_estructura_tabla(cursor, tabla)
            for col in estructura:
                key_info = f" [{col['key']}]" if col['key'] else ""
                null_info = "NULL" if col['nulo'] == 'YES' else "NOT NULL"
                print(f"     • {col['campo']:<30} {col['tipo']:<20} {null_info:<10} {key_info}")
    
    # Guardar mapeo completo en archivo JSON
    print("\n" + "="*80)
    print("GUARDANDO MAPEO COMPLETO...")
    print("="*80)
    
    mapeo_completo = {
        'fecha_mapeo': datetime.now().isoformat(),
        'total_tablas': len(tablas),
        'clasificacion': {k: len(v) for k, v in clasificacion.items()},
        'tablas': {}
    }
    
    # Guardar estructura de tablas importantes
    for categoria, lista_tablas in clasificacion.items():
        if categoria != 'reportes_fee':  # Evitar guardar miles de tablas fee
            for tabla in lista_tablas:
                mapeo_completo['tablas'][tabla] = {
                    'categoria': categoria,
                    'registros': contar_registros(cursor, tabla),
                    'estructura': obtener_estructura_tabla(cursor, tabla)
                }
    
    # Para reportes fee, guardar solo las tablas más recientes de cada tipo
    tipos_reportes = defaultdict(list)
    for tabla in clasificacion['reportes_fee']:
        partes = tabla.rsplit('_', 1)
        if len(partes) == 2:
            tipo = partes[0]
            fecha = partes[1]
            if len(fecha) == 8 and fecha.isdigit():
                tipos_reportes[tipo].append((tabla, fecha))
    
    mapeo_completo['tipos_reportes_fee'] = {}
    for tipo, tablas_fechas in tipos_reportes.items():
        tablas_fechas.sort(key=lambda x: x[1], reverse=True)
        tabla_reciente = tablas_fechas[0][0]
        mapeo_completo['tipos_reportes_fee'][tipo] = {
            'total_tablas': len(tablas_fechas),
            'tabla_mas_reciente': tabla_reciente,
            'fecha_mas_reciente': tablas_fechas[0][1],
            'estructura': obtener_estructura_tabla(cursor, tabla_reciente),
            'registros_reciente': contar_registros(cursor, tabla_reciente)
        }
    
    # Guardar JSON
    with open('vos3000_mapeo.json', 'w', encoding='utf-8') as f:
        json.dump(mapeo_completo, f, indent=2, ensure_ascii=False, default=str)
    
    print("✓ Mapeo guardado en: vos3000_mapeo.json")
    
    # Crear resumen en texto
    with open('vos3000_mapeo_resumen.txt', 'w', encoding='utf-8') as f:
        f.write("="*80 + "\n")
        f.write("RESUMEN DEL MAPEO DE VOS3000\n")
        f.write("="*80 + "\n")
        f.write(f"Fecha: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
        f.write(f"Total de tablas: {len(tablas)}\n\n")
        
        for categoria, lista_tablas in clasificacion.items():
            if lista_tablas:
                f.write(f"\n{categoria.upper().replace('_', ' ')}: {len(lista_tablas)}\n")
                f.write("-" * 80 + "\n")
                for tabla in sorted(lista_tablas)[:20]:
                    count = contar_registros(cursor, tabla)
                    f.write(f"  {tabla:<50} {count:>10,} registros\n")
                if len(lista_tablas) > 20:
                    f.write(f"  ... y {len(lista_tablas) - 20} tablas más\n")
    
    print("✓ Resumen guardado en: vos3000_mapeo_resumen.txt")
    
    cursor.close()
    conexion.close()
    
    print("\n" + "="*80)
    print("✓ MAPEO COMPLETADO EXITOSAMENTE")
    print("="*80)

if __name__ == "__main__":
    mapear_base_datos()
