# Troubleshooting: WhatsApp no envía en Producción

## Checklist de Verificación

### 1. Verificar Configuración del .env

Ejecutar diagnóstico:
```bash
php artisan whatsapp:diagnose
```

**Debe mostrar**:
- ✅ API URL: configurado
- ✅ Access Token: configurado  
- ✅ Phone Number ID: configurado
- ✅ Template: configurado

**Si falta algo**, agregar al `.env`:
```env
WHATSAPP_API_URL=https://graph.facebook.com/v21.0
WHATSAPP_ACCESS_TOKEN=tu_token_aqui
WHATSAPP_PHONE_NUMBER_ID=tu_phone_number_id_aqui
```

Después de editar `.env`, ejecutar:
```bash
php artisan config:clear
```

### 2. Verificar que el Queue Worker está corriendo

**El problema más común**: Los mensajes de WhatsApp se envían mediante colas. Si el worker no está activo, los mensajes quedan pendientes.

#### Verificar si está corriendo:
```bash
# En Linux/Mac
ps aux | grep queue:work

# En Windows (PowerShell)
Get-Process | Where-Object {$_.ProcessName -like "*php*"}
```

#### Iniciar el worker:

**Para desarrollo (temporal)**:
```bash
php artisan queue:work
```

**Para producción (con supervisor en Linux)**:

Crear archivo `/etc/supervisor/conf.d/citas-worker.conf`:
```ini
[program:citas-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /ruta/completa/al/proyecto/artisan queue:work --sleep=3 --tries=3 --timeout=90
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/ruta/completa/al/proyecto/storage/logs/worker.log
```

Luego:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start citas-queue-worker
```

**En Windows con Task Scheduler**:
1. Crear un script `queue-worker.bat`:
```batch
@echo off
cd C:\xampp\htdocs\citas
php artisan queue:work --sleep=3 --tries=3
```

2. Crear tarea programada que ejecute este script al iniciar el sistema

### 3. Verificar trabajos fallidos en la cola

```bash
php artisan queue:failed
```

Si hay trabajos fallidos:
```bash
# Ver detalles del trabajo fallido
php artisan queue:failed

# Reintentar todos los trabajos fallidos
php artisan queue:retry all

# Limpiar trabajos fallidos
php artisan queue:flush
```

### 4. Verificar el Template en Meta Business

El template debe estar **APROBADO** en Meta Business Manager.

#### Verificar en Meta:
1. Ve a https://business.facebook.com/
2. Selecciona tu cuenta de WhatsApp Business
3. Click en **WhatsApp Manager** → **Message Templates**
4. Busca el template `confirmacion_de_turno`
5. **Estado debe ser**: ✅ APPROVED

#### Si está REJECTED o PENDING:
- Revisa el template según `WHATSAPP_TEMPLATE.md`
- Vuelve a enviarlo para aprobación
- La aprobación puede tardar hasta 24 horas

#### Verificar código de idioma:
El template debe estar en idioma: **`es`** (no `es_AR` ni `es_ES`)

Si creaste el template con otro código, actualizar en `.env`:
```env
WHATSAPP_TEMPLATE_LANGUAGE=es
```

### 5. Verificar Token de Acceso

El token puede **expirar**. 

#### Verificar validez del token:
```bash
# En Linux/Mac
curl -X GET "https://graph.facebook.com/v21.0/me?access_token=TU_TOKEN_AQUI"

# En Windows (PowerShell)
Invoke-RestMethod -Uri "https://graph.facebook.com/v21.0/me?access_token=TU_TOKEN_AQUI"
```

Si responde con error de token inválido:
1. Ve a Meta for Developers
2. Genera un nuevo token permanente
3. Actualiza en `.env`:
```env
WHATSAPP_ACCESS_TOKEN=nuevo_token_aqui
```
4. Ejecuta: `php artisan config:clear`

### 6. Revisar Logs

```bash
# Ver últimos mensajes de WhatsApp
tail -50 storage/logs/laravel.log | grep -i whatsapp

# En Windows (PowerShell)
Get-Content storage\logs\laravel.log -Tail 50 | Select-String -Pattern "WhatsApp"

# Seguir logs en tiempo real
tail -f storage/logs/laravel.log | grep -i whatsapp
```

**Buscar**:
- ❌ `WhatsApp template failed`: Problema con el template
- ❌ `WhatsApp message failed`: Error en el envío
- ✅ `WhatsApp template sent successfully`: Mensaje enviado correctamente

### 7. Verificar números de teléfono

Los pacientes **deben tener teléfono** en la base de datos.

```sql
-- Verificar si los pacientes tienen teléfono
SELECT u.name, u.email, u.phone 
FROM users u 
INNER JOIN patients p ON p.user_id = u.id 
WHERE u.phone IS NULL OR u.phone = '';
```

**Formato aceptado**: Números argentinos
- `11 1234-5678`
- `1112345678`
- `+54 11 1234-5678`
- `15 6997 5132`

El sistema formatea automáticamente al formato WhatsApp: `5411XXXXXXXX`

### 8. Verificar límites de la API de WhatsApp

WhatsApp tiene **límites de envío** según tu plan:
- **Tier 1**: 1,000 conversaciones/24h
- **Tier 2**: 10,000 conversaciones/24h
- **Tier 3**: 100,000 conversaciones/24h

Ver límites en: https://business.facebook.com/ → WhatsApp Manager → Insights

### 9. Test Manual

Crear un turno de prueba desde la interfaz web y verificar:

```bash
# 1. Ver si se creó el job en la cola
SELECT * FROM jobs ORDER BY id DESC LIMIT 10;

# 2. Procesar manualmente un job
php artisan queue:work --once

# 3. Ver logs inmediatamente
tail -20 storage/logs/laravel.log
```

### 10. Verificar permisos de WhatsApp Business Account

En Meta for Developers:
1. Ve a tu aplicación
2. WhatsApp → Configuration
3. Verifica que el número tenga permisos:
   - ✅ Send messages
   - ✅ Manage message templates

## Resumen de Comandos Útiles

```bash
# Diagnóstico completo
php artisan whatsapp:diagnose

# Ver trabajos fallidos
php artisan queue:failed

# Limpiar caché de configuración
php artisan config:clear

# Iniciar worker (desarrollo)
php artisan queue:work

# Ver logs en tiempo real
tail -f storage/logs/laravel.log | grep -i whatsapp

# Test manual de envío
php artisan whatsapp:test
```

## Problemas Comunes y Soluciones

### ❌ "No se envía ningún WhatsApp"
**Causa**: Queue worker no está corriendo
**Solución**: Iniciar `php artisan queue:work`

### ❌ "Template not found"
**Causa**: Template no aprobado en Meta o nombre incorrecto
**Solución**: Verificar en Meta Business Manager que el template `confirmacion_de_turno` esté aprobado

### ❌ "Invalid access token"
**Causa**: Token expirado
**Solución**: Generar nuevo token en Meta for Developers

### ❌ "Phone number not registered"
**Causa**: En modo desarrollo, solo puedes enviar a números verificados
**Solución**: 
- Agregar el número en Meta for Developers → Phone Numbers → Add phone
- O solicitar aprobación del número para producción

### ❌ "Message sent pero el usuario no lo recibe"
**Causa**: Número de teléfono incorrecto en BD
**Solución**: Verificar formato del número en la tabla `users`

## Contacto de Soporte

- Documentación WhatsApp Business: https://developers.facebook.com/docs/whatsapp
- Soporte Meta Business: https://business.facebook.com/support
