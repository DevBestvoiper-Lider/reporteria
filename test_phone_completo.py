#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Prueba completa de queries para PHONE y CUSTOMERCLEARINGLOCATION
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

def probar_queries():
    conexion = conectar_db()
    if not conexion:
        return
    
    cursor = conexion.cursor()
    
    hoy = datetime.now().strftime('%Y%m%d')
    ayer = '20260512'
    
    cliente = 'SOLVENCIA'
    
    print("="*80)
    print("PRUEBA COMPLETA: PHONE y CUSTOMERCLEARINGLOCATION")
    print("="*80)
    
    # ==================== TIPO: PHONE ====================
    print("\n📱 TIPO: PHONE")
    print("-"*80)
    
    # AYER - tabla de reportes (debe tener datos)
    print(f"\n1️⃣  AYER ({ayer}) - desde e_reportphonefee_{ayer}:")
    
    query_phone_ayer = f"""
    SELECT e164, feetime, fee 
    FROM e_reportphonefee_{ayer} 
    WHERE customeraccount = %s 
    ORDER BY fee DESC 
    LIMIT 5
    """
    
    cursor.execute(query_phone_ayer, (cliente,))
    resultados = cursor.fetchall()
    
    if resultados:
        print(f"   {'e164':<20} {'Feetime':<12} {'Fee'}")
        print("   " + "-"*50)
        for row in resultados:
            print(f"   {row[0]:<20} {row[1]:<12} ${row[2]:.4f}")
    else:
        print("   Sin datos")
    
    # HOY - tabla e_cdr (debe usar query con agrupación)
    print(f"\n2️⃣  HOY ({hoy}) - desde e_cdr_{hoy}:")
    
    query_phone_hoy = f"""
    SELECT 
        calleee164 AS e164,
        SUM(feetime) AS feetime,
        SUM(fee) AS fee
    FROM e_cdr_{hoy}
    WHERE customeraccount = %s
    GROUP BY calleee164
    ORDER BY fee DESC
    LIMIT 5
    """
    
    cursor.execute(query_phone_hoy, (cliente,))
    resultados = cursor.fetchall()
    
    if resultados:
        print(f"   {'e164':<20} {'Feetime':<12} {'Fee'}")
        print("   " + "-"*50)
        total_feetime = 0
        total_fee = 0
        for row in resultados:
            print(f"   {row[0]:<20} {row[1]:<12} ${row[2]:.4f}")
            total_feetime += float(row[1] or 0)
            total_fee += float(row[2] or 0)
        print("   " + "-"*50)
        print(f"   {'TOTAL (Top 5)':<20} {total_feetime:<12.0f} ${total_fee:.4f}")
    else:
        print("   Sin datos")
    
    # ==================== TIPO: CUSTOMERCLEARINGLOCATION ====================
    print("\n\n🌍 TIPO: CUSTOMERCLEARINGLOCATION")
    print("-"*80)
    
    # AYER - tabla de reportes
    print(f"\n1️⃣  AYER ({ayer}) - desde e_reportcustomerclearinglocationfee_{ayer}:")
    
    query_location_ayer = f"""
    SELECT areacode, feetime, fee 
    FROM e_reportcustomerclearinglocationfee_{ayer} 
    WHERE customeraccount = %s 
    ORDER BY fee DESC 
    LIMIT 5
    """
    
    cursor.execute(query_location_ayer, (cliente,))
    resultados = cursor.fetchall()
    
    if resultados:
        print(f"   {'Areacode':<20} {'Feetime':<12} {'Fee'}")
        print("   " + "-"*50)
        for row in resultados:
            areacode = row[0] if row[0] else '(vacío)'
            print(f"   {areacode:<20} {row[1]:<12} ${row[2]:.4f}")
    else:
        print("   Sin datos")
    
    # HOY - tabla e_cdr
    print(f"\n2️⃣  HOY ({hoy}) - desde e_cdr_{hoy}:")
    
    query_location_hoy = f"""
    SELECT 
        calleeareacode AS areacode,
        SUM(feetime) AS feetime,
        SUM(fee) AS fee
    FROM e_cdr_{hoy}
    WHERE customeraccount = %s
    GROUP BY calleeareacode
    ORDER BY fee DESC
    LIMIT 5
    """
    
    cursor.execute(query_location_hoy, (cliente,))
    resultados = cursor.fetchall()
    
    if resultados:
        print(f"   {'Areacode':<20} {'Feetime':<12} {'Fee'}")
        print("   " + "-"*50)
        total_feetime = 0
        total_fee = 0
        for row in resultados:
            areacode = row[0] if row[0] else '(vacío)'
            print(f"   {areacode:<20} {row[1]:<12} ${row[2]:.4f}")
            total_feetime += float(row[1] or 0)
            total_fee += float(row[2] or 0)
        print("   " + "-"*50)
        print(f"   {'TOTAL (Top 5)':<20} {total_feetime:<12.0f} ${total_fee:.4f}")
    else:
        print("   Sin datos")
    
    # ==================== RESUMEN ====================
    print("\n\n" + "="*80)
    print("📊 RESUMEN COMPARATIVO")
    print("="*80)
    
    tipos = [
        ('GATEWAYMAPPING', 'e_reportgatewaymappingfee', 'callergatewayid, callerip'),
        ('PHONE', 'e_reportphonefee', 'calleee164'),
        ('CUSTOMERCLEARINGLOCATION', 'e_reportcustomerclearinglocationfee', 'calleeareacode')
    ]
    
    for nombre, tabla_base, campo_agrupacion in tipos:
        print(f"\n{nombre}:")
        
        # Contar registros de ayer (tabla reporte)
        try:
            cursor.execute(f"SELECT COUNT(*) FROM {tabla_base}_{ayer} WHERE customeraccount = %s", (cliente,))
            count_ayer = cursor.fetchone()[0]
        except:
            count_ayer = 0
        
        # Contar registros agrupados de hoy (e_cdr)
        if 'gateway' in nombre.lower():
            group_field = 'callergatewayid, callerip'
        elif nombre == 'PHONE':
            group_field = 'calleee164'
        else:
            group_field = 'calleeareacode'
        
        cursor.execute(f"""
            SELECT COUNT(DISTINCT {group_field.replace(', ', ', ')})
            FROM e_cdr_{hoy}
            WHERE customeraccount = %s
        """, (cliente,))
        count_hoy = cursor.fetchone()[0]
        
        print(f"   Ayer ({ayer}):  {count_ayer:>5} registros en {tabla_base}_{ayer}")
        print(f"   Hoy  ({hoy}):  {count_hoy:>5} registros agrupados desde e_cdr_{hoy}")
    
    cursor.close()
    conexion.close()
    
    print("\n" + "="*80)
    print("✓ PRUEBA COMPLETADA")
    print("="*80)

if __name__ == "__main__":
    probar_queries()
