#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script para buscar tablas CDR en tiempo real de VOS3000
"""

import pymysql
from datetime import datetime

def conectar_db():
    """Conectar a la base de datos VOS3000"""
    try:
        conexion = pymysql.connect(
            host='sw3.bestvoiper.com',
            user='developers',
            password='Luisda0806*++',
            database='vos3000',
            charset='utf8',
            connect_timeout=30
        )
        print("Conexion exitosa al servidor VOS3000")
        return conexion
    except Exception as e:
        print(f"Error al conectar: {e}")
        return None

def buscar_tablas_cdr():
    """Buscar todas las tablas CDR"""
    conexion = conectar_db()
    if not conexion:
        return
    
    cursor = conexion.cursor()
    
    print("\n" + "="*80)
    print("BUSCANDO TABLAS CDR (Call Detail Records)")
    print("="*80)
    
    # Buscar tablas que contienen 'cdr'
    cursor.execute("SHOW TABLES LIKE '%cdr%'")
    tablas_cdr = [tabla[0] for tabla in cursor.fetchall()]
    
    print(f"\nTotal de tablas CDR encontradas: {len(tablas_cdr)}\n")
    
    # Clasificar tablas CDR
    tablas_principales = []
    tablas_diarias = []
    tablas_mensuales = []
    otras = []
    
    for tabla in tablas_cdr:
        if tabla.endswith(tuple(str(i) for i in range(10))) and len(tabla) > 10:
            # Probablemente tabla con fecha
            if '_202' in tabla or '_201' in tabla:
                if len(tabla.split('_')[-1]) == 8:
                    tablas_diarias.append(tabla)
                elif len(tabla.split('_')[-1]) == 6:
                    tablas_mensuales.append(tabla)
                else:
                    otras.append(tabla)
            else:
                otras.append(tabla)
        else:
            tablas_principales.append(tabla)
    
    print("TABLAS CDR PRINCIPALES (sin fecha):")
    print("-" * 80)
    for tabla in sorted(tablas_principales):
        cursor.execute(f"SELECT COUNT(*) FROM `{tabla}`")
        count = cursor.fetchone()[0]
        print(f"  {tabla:<50} {count:>10,} registros")
        
        # Mostrar estructura de las principales
        if count > 0 or 'e_cdr' == tabla:
            print(f"\n  Estructura COMPLETA de {tabla}:")
            cursor.execute(f"DESCRIBE `{tabla}`")
            columnas = cursor.fetchall()
            print(f"  Total columnas: {len(columnas)}\n")
            for col in columnas:  # TODAS las columnas
                print(f"    - {col[0]:<30} {col[1]:<20}")
            print()
    
    if tablas_mensuales:
        print("\nTABLAS CDR MENSUALES (más recientes):")
        print("-" * 80)
        for tabla in sorted(tablas_mensuales, reverse=True)[:5]:
            cursor.execute(f"SELECT COUNT(*) FROM `{tabla}`")
            count = cursor.fetchone()[0]
            print(f"  {tabla:<50} {count:>10,} registros")
    
    if tablas_diarias:
        print("\nTABLAS CDR DIARIAS (más recientes):")
        print("-" * 80)
        for tabla in sorted(tablas_diarias, reverse=True)[:10]:
            cursor.execute(f"SELECT COUNT(*) FROM `{tabla}`")
            count = cursor.fetchone()[0]
            fecha = tabla.split('_')[-1]
            fecha_format = f"{fecha[0:4]}-{fecha[4:6]}-{fecha[6:8]}"
            print(f"  {tabla:<50} {count:>10,} registros  ({fecha_format})")
    
    if otras:
        print(f"\nOTRAS TABLAS CDR: {len(otras)}")
    
    # Buscar tabla CDR del día actual
    print("\n" + "="*80)
    print("BUSCANDO DATOS DEL DIA ACTUAL")
    print("="*80)
    
    hoy = datetime.now().strftime('%Y%m%d')
    mes_actual = datetime.now().strftime('%Y%m')
    
    tablas_hoy = [
        f'e_cdr_{hoy}',
        f'e_cdr_{mes_actual}',
        'e_cdr'
    ]
    
    for tabla in tablas_hoy:
        try:
            cursor.execute(f"SELECT COUNT(*) FROM `{tabla}`")
            count = cursor.fetchone()[0]
            print(f"\n{tabla}:")
            print(f"  Total registros: {count:,}")
            
            if count > 0:
                # Mostrar últimos registros
                cursor.execute(f"SELECT * FROM `{tabla}` ORDER BY id DESC LIMIT 3")
                registros = cursor.fetchall()
                
                # Obtener nombres de columnas
                cursor.execute(f"DESCRIBE `{tabla}`")
                columnas = [col[0] for col in cursor.fetchall()]
                
                print(f"\n  Ultimos 3 registros:")
                for i, reg in enumerate(registros, 1):
                    print(f"\n  Registro {i}:")
                    for j, valor in enumerate(reg[:10]):  # Solo primeros 10 campos
                        print(f"    {columnas[j]:<25} = {valor}")
                
                break
        except Exception as e:
            print(f"\n{tabla}: No existe o no accesible")
    
    # Verificar e_customer.todayconsumption
    print("\n" + "="*80)
    print("VERIFICANDO e_customer (consumo del dia actual)")
    print("="*80)
    
    cursor.execute("SELECT account, todayconsumption, money FROM e_customer WHERE todayconsumption > 0 ORDER BY todayconsumption DESC LIMIT 10")
    clientes = cursor.fetchall()
    
    print("\nClientes con consumo hoy (Top 10):")
    print("-" * 80)
    for cliente in clientes:
        print(f"  {cliente[0]:<30} Consumo hoy: ${cliente[1]:>10.4f}  Saldo: ${cliente[2]:>10.2f}")
    
    cursor.close()
    conexion.close()
    
    print("\n" + "="*80)
    print("BUSQUEDA COMPLETADA")
    print("="*80)

if __name__ == "__main__":
    buscar_tablas_cdr()
