#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Verificar query de CDR con agrupación por gateway (simulando la consulta PHP)
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
        print("✓ Conexión exitosa")
        return conexion
    except Exception as e:
        print(f"✗ Error al conectar: {e}")
        return None

def probar_query_cdr():
    """Probar query que usará el sistema"""
    conexion = conectar_db()
    if not conexion:
        return
    
    cursor = conexion.cursor()
    
    hoy = datetime.now().strftime('%Y%m%d')
    tabla_cdr = f'e_cdr_{hoy}'
    
    print("\n" + "="*80)
    print(f"PROBANDO QUERY EQUIVALENTE A LA CONSULTA PHP")
    print(f"Tabla: {tabla_cdr}")
    print("="*80)
    
    # Query con gateway (tipo gatewaymapping/gatewayrouting)
    print("\n--- CON GATEWAY (gatewaymapping/gatewayrouting) ---\n")
    
    clientes = ['SOLVENCIA', 'PUNTUALMENTE', 'CORFIN']
    
    for cliente in clientes:
        query_gateway = f"""
        SELECT 
            callergatewayid AS gatewayid,
            callerip AS ip,
            SUM(feetime) AS feetime,
            SUM(fee) AS fee
        FROM `{tabla_cdr}`
        WHERE customeraccount = %s
        GROUP BY callergatewayid, callerip
        ORDER BY fee DESC
        LIMIT 10
        """
        
        cursor.execute(query_gateway, (cliente,))
        resultados = cursor.fetchall()
        
        print(f"\n🔹 Cliente: {cliente}")
        print(f"   Gateways únicos: {len(resultados)}")
        
        if resultados:
            print("\n   Top gateways por costo:")
            print(f"   {'Gateway ID':<30} {'IP':<20} {'Tiempo (seg)':<15} {'Costo ($)'}")
            print("   " + "-"*85)
            
            total_feetime = 0
            total_fee = 0
            
            for row in resultados[:5]:  # Solo mostrar top 5
                gatewayid, ip, feetime, fee = row
                print(f"   {str(gatewayid):<30} {str(ip):<20} {feetime:>13,.2f}  ${fee:>12.4f}")
                total_feetime += float(feetime or 0)
                total_fee += float(fee or 0)
            
            print("   " + "-"*85)
            print(f"   {'TOTAL (Top 5)':<50} {total_feetime:>13,.2f}  ${total_fee:>12.4f}")
        else:
            print("   Sin datos")
    
    # Query sin gateway (tipo phone/customerclearinglocation)
    print("\n\n--- SIN GATEWAY (phone/customerclearinglocation) ---\n")
    
    for cliente in clientes:
        query_sin_gateway = f"""
        SELECT 
            SUM(feetime) AS feetime,
            SUM(fee) AS fee,
            COUNT(*) AS llamadas
        FROM `{tabla_cdr}`
        WHERE customeraccount = %s
        """
        
        cursor.execute(query_sin_gateway, (cliente,))
        resultado = cursor.fetchone()
        
        if resultado and resultado[0]:
            feetime, fee, llamadas = resultado
            print(f"   {cliente:<30} Llamadas: {llamadas:>8,}  Tiempo: {float(feetime):>12,.2f}s  Costo: ${float(fee):>12.4f}")
        else:
            print(f"   {cliente:<30} Sin datos")
    
    # Comparar con e_customer.todayconsumption
    print("\n\n" + "="*80)
    print("COMPARACIÓN CON e_customer.todayconsumption")
    print("="*80 + "\n")
    
    for cliente in clientes:
        # Total desde CDR
        query_cdr_total = f"""
        SELECT SUM(fee) AS total_cdr
        FROM `{tabla_cdr}`
        WHERE customeraccount = %s
        """
        cursor.execute(query_cdr_total, (cliente,))
        total_cdr = cursor.fetchone()[0] or 0
        
        # Total desde e_customer
        query_customer = """
        SELECT todayconsumption
        FROM e_customer
        WHERE account = %s
        """
        cursor.execute(query_customer, (cliente,))
        resultado_customer = cursor.fetchone()
        total_customer = resultado_customer[0] if resultado_customer else 0
        
        diferencia = float(total_cdr) - float(total_customer)
        
        print(f"   {cliente:<30}")
        print(f"      e_cdr_{hoy}:         ${float(total_cdr):>15.4f}")
        print(f"      e_customer.todaycons:  ${float(total_customer):>15.4f}")
        print(f"      Diferencia:            ${diferencia:>15.4f}")
        print()
    
    cursor.close()
    conexion.close()
    
    print("="*80)
    print("✓ PRUEBA COMPLETADA")
    print("="*80)

if __name__ == "__main__":
    probar_query_cdr()
