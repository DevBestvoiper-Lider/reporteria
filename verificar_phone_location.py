#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Verificar estructura de tablas phone y customerclearinglocation
"""

import pymysql
from datetime import datetime

def conectar_db():
    try:
        conexion = pymysql.connect(
            host='sw3.bestvoiper.com',
            user='developers',
            password='Luisda0806*++',
            database='vos3000',
            charset='utf8',
            connect_timeout=30
        )
        print("✓ Conexión exitosa\n")
        return conexion
    except Exception as e:
        print(f"✗ Error: {e}")
        return None

def verificar_estructura():
    conexion = conectar_db()
    if not conexion:
        return
    
    cursor = conexion.cursor()
    
    print("="*80)
    print("COMPARANDO ESTRUCTURAS: REPORTES vs CDR")
    print("="*80)
    
    # e_reportphonefee
    print("\n📋 TIPO: PHONE")
    print("-"*80)
    
    cursor.execute("DESCRIBE e_reportphonefee_20260512")
    cols_phone = cursor.fetchall()
    
    print("\nEstructura e_reportphonefee_20260512:")
    for col in cols_phone:
        print(f"  {col[0]:<30} {col[1]}")
    
    # Datos de ejemplo
    cursor.execute("SELECT e164, customeraccount, feetime, fee FROM e_reportphonefee_20260512 WHERE customeraccount = 'SOLVENCIA' LIMIT 5")
    datos = cursor.fetchall()
    
    print("\nDatos de ejemplo (SOLVENCIA):")
    print(f"  {'e164':<20} {'Customer':<15} {'Feetime':<12} {'Fee'}")
    print("  " + "-"*60)
    for row in datos:
        print(f"  {row[0]:<20} {row[1]:<15} {row[2]:<12} ${row[3]:.4f}")
    
    # e_reportcustomerclearinglocationfee
    print("\n\n📋 TIPO: CUSTOMERCLEARINGLOCATION")
    print("-"*80)
    
    cursor.execute("DESCRIBE e_reportcustomerclearinglocationfee_20260512")
    cols_location = cursor.fetchall()
    
    print("\nEstructura e_reportcustomerclearinglocationfee_20260512:")
    for col in cols_location:
        print(f"  {col[0]:<30} {col[1]}")
    
    # Datos de ejemplo
    cursor.execute("SELECT areacode, customeraccount, feetime, fee FROM e_reportcustomerclearinglocationfee_20260512 WHERE customeraccount = 'SOLVENCIA' LIMIT 5")
    datos = cursor.fetchall()
    
    print("\nDatos de ejemplo (SOLVENCIA):")
    print(f"  {'Areacode':<20} {'Customer':<15} {'Feetime':<12} {'Fee'}")
    print("  " + "-"*60)
    for row in datos:
        print(f"  {row[0]:<20} {row[1]:<15} {row[2]:<12} ${row[3]:.4f}")
    
    # Verificar campos en e_cdr
    print("\n\n📋 CAMPOS EN e_cdr PARA MAPEO")
    print("-"*80)
    
    hoy = datetime.now().strftime('%Y%m%d')
    tabla_cdr = f'e_cdr_{hoy}'
    
    cursor.execute(f"DESCRIBE {tabla_cdr}")
    cols_cdr = cursor.fetchall()
    
    campos_relevantes = ['calleee164', 'calleraccesse164', 'calleeareacode', 'callerareacode']
    
    print(f"\nCampos relevantes en {tabla_cdr}:")
    for col in cols_cdr:
        if col[0] in campos_relevantes:
            print(f"  {col[0]:<30} {col[1]}")
    
    # Probar query equivalente para PHONE
    print("\n\n📋 QUERY EQUIVALENTE PARA PHONE (desde CDR)")
    print("-"*80)
    
    query_phone = f"""
    SELECT 
        calleee164 AS e164,
        SUM(feetime) AS feetime,
        SUM(fee) AS fee,
        COUNT(*) AS llamadas
    FROM {tabla_cdr}
    WHERE customeraccount = 'SOLVENCIA'
    GROUP BY calleee164
    ORDER BY fee DESC
    LIMIT 10
    """
    
    cursor.execute(query_phone)
    resultados = cursor.fetchall()
    
    print("\nTop 10 e164 por costo (SOLVENCIA):")
    print(f"  {'e164':<20} {'Llamadas':<12} {'Feetime':<12} {'Fee'}")
    print("  " + "-"*60)
    for row in resultados:
        print(f"  {row[0]:<20} {row[3]:<12} {row[1]:<12} ${row[2]:.4f}")
    
    # Probar query equivalente para CUSTOMERCLEARINGLOCATION
    print("\n\n📋 QUERY EQUIVALENTE PARA CUSTOMERCLEARINGLOCATION (desde CDR)")
    print("-"*80)
    
    query_location = f"""
    SELECT 
        calleeareacode AS areacode,
        SUM(feetime) AS feetime,
        SUM(fee) AS fee,
        COUNT(*) AS llamadas
    FROM {tabla_cdr}
    WHERE customeraccount = 'SOLVENCIA'
    GROUP BY calleeareacode
    ORDER BY fee DESC
    LIMIT 10
    """
    
    cursor.execute(query_location)
    resultados = cursor.fetchall()
    
    print("\nTop 10 areacodes por costo (SOLVENCIA):")
    print(f"  {'Areacode':<20} {'Llamadas':<12} {'Feetime':<12} {'Fee'}")
    print("  " + "-"*60)
    for row in resultados:
        print(f"  {row[0]:<20} {row[3]:<12} {row[1]:<12} ${row[2]:.4f}")
    
    cursor.close()
    conexion.close()
    
    print("\n" + "="*80)
    print("✓ VERIFICACIÓN COMPLETADA")
    print("="*80)

if __name__ == "__main__":
    verificar_estructura()
