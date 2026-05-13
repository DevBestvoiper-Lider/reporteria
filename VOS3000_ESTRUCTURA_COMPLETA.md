# MAPEO COMPLETO DE BASE DE DATOS VOS3000
**Fecha:** 13 de Mayo 2026
**Servidor:** sw3.bestvoiper.com
**Base de datos:** vos3000

---

## RESUMEN GENERAL

**Total de tablas:** ~5,000+  
**Categorías principales:**
- Reportes Fee: ~4,988 tablas (tablas diarias de reportes)
- Configuración: Múltiples tablas
- Clientes: 9 tablas
- Usuarios: 5 tablas
- Firewall: 3 tablas

---

## TABLAS DE REPORTES FEE (Principales)

### 1. e_reportgatewaymappingfee_YYYYMMDD
- **Total de tablas:** 1,061 (una por día)
- **Tabla más reciente:** 20260512 (12 de Mayo 2026) - 153 registros
- **Uso:** Reportes de llamadas por gateway mapping
- **Tiene campo gatewayid:** ✓ SÍ

**Estructura:**
```
- id (PK)
- date (INDEX)
- count (número de llamadas)
- calllevel
- fee (costo)
- tax (impuesto)
- feetime (duración en segundos)
- suitefee
- suitefeetime
- gatewayid (INDEX) ← IMPORTANTE
- ip
- customeraccount (INDEX)
- customername
- customertimezoneid
- agentaccount (INDEX)
```

### 2. e_reportgatewayroutingfee_YYYYMMDD
- **Total de tablas:** 1
- **Tabla más reciente:** 20250731
- **Uso:** Reportes de llamadas por gateway routing
- **Tiene campo gatewayid:** ✓ SÍ

**Estructura:**
```
- Misma estructura que gatewaymappingfee
- También incluye gatewayid e ip
```

### 3. e_reportphonefee_YYYYMMDD
- **Total de tablas:** 1,061
- **Tabla más reciente:** 20260512 (12 de Mayo 2026) - 70 registros
- **Uso:** Reportes de llamadas por teléfono/extensión
- **Tiene campo gatewayid:** ✗ NO

**Estructura:**
```
- id (PK)
- date (INDEX)
- count
- calllevel
- fee
- tax
- feetime
- suitefee
- suitefeetime
- calleebilling
- e164 (INDEX) ← Número de teléfono
- customeraccount (INDEX)
- customername
- customertimezoneid
- agentaccount (INDEX)
```

### 4. e_reportcustomerclearinglocationfee_YYYYMMDD
- **Total de tablas:** 1,061
- **Tabla más reciente:** 20260512 (12 de Mayo 2026) - 19 registros
- **Uso:** Reportes por ubicación de clearing del cliente
- **Tiene campo gatewayid:** ✗ NO

**Estructura:**
```
- id (PK)
- date (INDEX)
- count
- fee
- tax
- feetime
- suitefee
- suitefeetime
- areacode ← Código de área
- customeraccount (INDEX)
- customername
- customertimezoneid
```

### 5. e_reportcustomerfee_YYYYMMDD
- **Total de tablas:** 1,061
- **Tabla más reciente:** 20260512 - 112 registros
- **Uso:** Reportes generales por cliente

**Estructura:**
```
- Similar a otras tablas fee
- Agrupa por cuenta de cliente
```

### 6. e_reportcustomerlocationfee_YYYYMMDD
- **Total de tablas:** 1,061
- **Tabla más reciente:** 20260512 - 479 registros
- **Uso:** Reportes por ubicación del cliente

---

## TABLAS DE CONFIGURACIÓN PRINCIPALES

### e_customer
**Registros:** 213 clientes  
**Descripción:** Tabla principal de clientes VOS3000

**Campos importantes:**
```
- id (PK)
- account (nombre de cuenta)
- name (nombre del cliente)
- password
- type (tipo de cliente)
- money (saldo actual)
- validtime (fecha de vencimiento)
- status (estado: activo/inactivo)
- todayconsumption (consumo del día)
- timezoneid
- feerategroup_id (grupo de tarifas)
```

### e_usuariosclientes
**Registros:** 214 usuarios  
**Descripción:** Usuarios del sistema de reportería (tabla personalizada)

**Campos:**
```
- id (PK)
- nombre
- usuario
- password (hash bcrypt)
- perfil (Administrador/Cliente)
- ultimo_login
- customeraccountname (vincula con e_customer.account)
- estado (0=activo, 1=inactivo)
```

### e_gateway
**Descripción:** Tabla de gateways/servidores VoIP

### e_firewall
**Registros:** 229  
**Descripción:** IPs bloqueadas por firewall

---

## CAMPOS CLAVE PARA CONSULTAS

### Para identificar cliente:
- `customeraccount` - Nombre de cuenta del cliente
- `customername` - Nombre descriptivo
- `agentaccount` - Cuenta del agente/revendedor

### Para medir consumo:
- `fee` - Costo en dólares
- `feetime` - Duración en segundos
- `count` - Número de llamadas

### Para filtrar por gateway:
- `gatewayid` - ID del gateway (solo en gatewaymapping y gatewayrouting)
- `ip` - IP del gateway

### Para filtrar por fecha:
- `date` - Timestamp de la fecha
- Sufijo de tabla: `_YYYYMMDD`

---

## LÓGICA DE NEGOCIO IDENTIFICADA

### 1. Tipos de tabla según tu código actual:
```php
'gatewaymapping' => 'e_reportgatewaymappingfee_'     // Tiene gatewayid
'gatewayrouting' => 'e_reportgatewayroutingfee_'     // Tiene gatewayid  
'phone' => 'e_reportphonefee_'                       // NO tiene gatewayid
'customerclearinglocation' => 'e_reportcustomerclearinglocationfee_' // NO tiene gatewayid
```

### 2. Tablas con gatewayid:
- ✓ e_reportgatewaymappingfee_*
- ✓ e_reportgatewayroutingfee_*
- ✗ e_reportphonefee_*
- ✗ e_reportcustomerclearinglocationfee_*

### 3. Nomenclatura de tablas diarias:
- Formato: `{prefijo}_{YYYYMMDD}`
- Ejemplo: `e_reportgatewaymappingfee_20260512`
- Se crea una tabla nueva cada día
- Rango actual: Desde 2023 hasta Mayo 2026

---

## CONSIDERACIONES IMPORTANTES

1. **Tablas diarias:** VOS3000 crea una tabla nueva cada día para cada tipo de reporte
2. **Volumen de datos:** ~1,061 tablas por tipo (casi 3 años de datos)
3. **Índices:** Las tablas tienen índices en date, customeraccount, gatewayid
4. **Charset:** utf8 (MySQL 5.0.96 - versión antigua)
5. **Particionamiento natural:** Por fecha en el nombre de la tabla

---

## CONSULTAS TÍPICAS

### Para consumo del día:
```sql
SELECT gatewayid, ip, SUM(feetime), SUM(fee)
FROM e_reportgatewaymappingfee_20260512
WHERE customeraccount = 'NombreCliente'
GROUP BY gatewayid, ip
```

### Para consumo del mes:
```sql
-- Iterar sobre tablas del rango de fechas
-- e_reportgatewaymappingfee_20260501 hasta e_reportgatewaymappingfee_20260512
```

### Consolidar todos los tipos:
Consultar en paralelo:
1. e_reportgatewaymappingfee_*
2. e_reportgatewayroutingfee_*
3. e_reportphonefee_*
4. e_reportcustomerclearinglocationfee_*

---

## PRÓXIMOS PASOS RECOMENDADOS

1. ✓ Mapeo completado
2. Revisar queries existentes en models/consumption.models.php
3. Optimizar consultas mensuales (1,061 tablas puede ser lento)
4. Considerar vistas materializadas para reportes frecuentes
5. Implementar caché para datos históricos
6. Documentar relaciones entre tablas

---

**Estado:** MAPEO COMPLETO ✓  
**Archivos generados:** Este resumen  
**Próximo:** Analizar y optimizar queries existentes
