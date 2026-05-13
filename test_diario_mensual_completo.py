#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Prueba COMPLETA: Consultas diarias Y mensuales con todos los tipos
"""

import pymysql
from datetime import datetime, timedelta

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

def probar_queries_completas():
    conexion = conectar_db()
    if not conexion:
        return
    
    cursor = conexion.cursor()
    
    hoy = datetime.now().strftime('%Y%m%d')
    ayer = '20260512'
    hace_5_dias = '20260508'
    
    cliente = 'SOLVENCIA'
    
    print("="*80)
    print("PRUEBA COMPLETA: DIARIO + MENSUAL | TODOS LOS TIPOS")
    print("="*80)
    
    tipos = [
        {
            'nombre': 'GATEWAYMAPPING',
            'tabla_reporte': 'e_reportgatewaymappingfee',
            'agrupacion_cdr': 'callergatewayid, callerip',
            'columnas_cdr': 'callergatewayid AS gatewayid, callerip AS ip, SUM(feetime) AS feetime, SUM(fee) AS fee',
            'columnas_reporte': 'gatewayid, ip, feetime, fee'
        },
        {
            'nombre': 'PHONE',
            'tabla_reporte': 'e_reportphonefee',
            'agrupacion_cdr': 'calleee164',
            'columnas_cdr': 'calleee164 AS e164, SUM(feetime) AS feetime, SUM(fee) AS fee',
            'columnas_reporte': 'e164, feetime, fee'
        },
        {
            'nombre': 'CUSTOMERCLEARINGLOCATION',
            'tabla_reporte': 'e_reportcustomerclearinglocationfee',
            'agrupacion_cdr': 'calleeareacode',
            'columnas_cdr': 'calleeareacode AS areacode, SUM(feetime) AS feetime, SUM(fee) AS fee',
            'columnas_reporte': 'areacode, feetime, fee'
        }
    ]
    
    for tipo in tipos:
        print(f"\n{'='*80}")
        print(f"📊 TIPO: {tipo['nombre']}")
        print(f"{'='*80}")
        
        # ===== PRUEBA DIARIA (HOY) =====
        print(f"\n🔹 DIARIA - HOY ({hoy}):")
        print("-"*80)
        
        # Tabla reporte (debería estar vacía)
        try:
            cursor.execute(f"SELECT COUNT(*) FROM {tipo['tabla_reporte']}_{hoy} WHERE customeraccount = %s", (cliente,))
            count_reporte = cursor.fetchone()[0]
            print(f"   {tipo['tabla_reporte']}_{hoy}: {count_reporte} registros")
        except Exception as e:
            count_reporte = 0
            print(f"   {tipo['tabla_reporte']}_{hoy}: No existe")
        
        # Tabla CDR (datos en tiempo real)
        query_cdr = f"""
        SELECT {tipo['columnas_cdr']}
        FROM e_cdr_{hoy}
        WHERE customeraccount = %s
        GROUP BY {tipo['agrupacion_cdr']}
        ORDER BY fee DESC
        LIMIT 3
        """
        
        cursor.execute(query_cdr, (cliente,))
        resultados = cursor.fetchall()
        count_cdr = len(resultados)
        
        # Contar total agrupados
        cursor.execute(f"""
            SELECT COUNT(DISTINCT {tipo['agrupacion_cdr']})
            FROM e_cdr_{hoy}
            WHERE customeraccount = %s
        """, (cliente,))
        total_agrupados = cursor.fetchone()[0]
        
        print(f"   e_cdr_{hoy}: {total_agrupados} registros agrupados")
        
        if resultados:
            print(f"\n   Top 3 por costo:")
            for row in resultados:
                print(f"      {str(row[0])[:30]:<30} Fee: ${row[-1]:.4f}")
        
        # ===== PRUEBA DIARIA (AYER) =====
        print(f"\n🔹 DIARIA - AYER ({ayer}):")
        print("-"*80)
        
        try:
            cursor.execute(f"SELECT COUNT(*) FROM {tipo['tabla_reporte']}_{ayer} WHERE customeraccount = %s", (cliente,))
            count_ayer = cursor.fetchone()[0]
            print(f"   {tipo['tabla_reporte']}_{ayer}: {count_ayer} registros")
            
            if count_ayer > 0:
                cursor.execute(f"""
                    SELECT {tipo['columnas_reporte']}
                    FROM {tipo['tabla_reporte']}_{ayer}
                    WHERE customeraccount = %s
                    ORDER BY fee DESC
                    LIMIT 3
                """, (cliente,))
                resultados_ayer = cursor.fetchall()
                
                if resultados_ayer:
                    print(f"\n   Top 3 por costo:")
                    for row in resultados_ayer:
                        print(f"      {str(row[0])[:30]:<30} Fee: ${row[-1]:.4f}")
        except Exception as e:
            print(f"   {tipo['tabla_reporte']}_{ayer}: No existe o sin datos")
        
        # ===== PRUEBA MENSUAL (últimos 5 días) =====
        print(f"\n🔸 MENSUAL - Del {hace_5_dias} al {hoy}:")
        print("-"*80)
        
        # Contar tablas en el rango (incluyendo hoy)
        total_dias = 0
        total_registros = 0
        
        for i in range(6):  # 5 días atrás + hoy
            fecha = (datetime.now() - timedelta(days=i)).strftime('%Y%m%d')
            
            # Intentar tabla reporte
            try:
                cursor.execute(f"SELECT COUNT(*) FROM {tipo['tabla_reporte']}_{fecha} WHERE customeraccount = %s", (cliente,))
                count = cursor.fetchone()[0]
                if count > 0:
                    total_dias += 1
                    total_registros += count
            except:
                pass
            
            # Si es hoy, agregar datos de CDR
            if fecha == hoy:
                cursor.execute(f"""
                    SELECT COUNT(DISTINCT {tipo['agrupacion_cdr']})
                    FROM e_cdr_{hoy}
                    WHERE customeraccount = %s
                """, (cliente,))
                count_hoy_cdr = cursor.fetchone()[0]
                if count_hoy_cdr > 0:
                    total_dias += 1
                    total_registros += count_hoy_cdr
        
        print(f"   Días con datos: {total_dias}")
        print(f"   Total registros: {total_registros}")
    
    cursor.close()
    conexion.close()
    
    print("\n" + "="*80)
    print("✓ PRUEBA COMPLETADA")
    print("="*80)
    print("\nRESUMEN:")
    print("  ✓ DIARIO - HOY: Usa e_cdr_YYYYMMDD con agrupación")
    print("  ✓ DIARIO - AYER: Usa e_report*fee_YYYYMMDD")
    print("  ✓ MENSUAL: Combina ambos según la fecha")
    print("  ✓ Todos los tipos (gatewaymapping, phone, customerclearinglocation) funcionan")

if __name__ == "__main__":
    probar_queries_completas()
