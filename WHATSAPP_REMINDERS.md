# Sistema de Recordatorios por WhatsApp

Este sistema permite enviar recordatorios automáticos a los pacientes a través de WhatsApp usando la API oficial de WhatsApp Business.

## 📋 Requisitos Previos

1. **Cuenta de WhatsApp Business**
   - Crear una cuenta en [Meta for Developers](https://developers.facebook.com/)
   - Configurar WhatsApp Business API
   - Obtener un número de teléfono verificado

2. **Configuración en Meta**
   - Crear una aplicación en Meta for Developers
   - Agregar el producto "WhatsApp"
   - Obtener las credenciales necesarias:
     - Access Token (Token de acceso)
     - Phone Number ID (ID del número de teléfono)

## 🔧 Configuración

### 1. Variables de Entorno

Agrega las siguientes variables a tu archivo `.env`:

```env
WHATSAPP_API_URL=https://graph.facebook.com/v21.0
WHATSAPP_ACCESS_TOKEN=tu_token_de_acceso_aqui
WHATSAPP_PHONE_NUMBER_ID=tu_phone_number_id_aqui
```

### 2. Obtener Credenciales de WhatsApp

#### Access Token:
1. Ve a [Meta for Developers](https://developers.facebook.com/)
2. Selecciona tu aplicación
3. En el panel izquierdo, ve a WhatsApp > Comenzar
4. Copia el "Token de acceso temporal" o genera uno permanente desde Configuración del sistema

#### Phone Number ID:
1. En la misma sección de WhatsApp
2. Busca "Phone Number ID" debajo del número de teléfono
3. Copia el ID

### 3. Configurar Cola de Trabajos

Las notificaciones se envían usando colas. Asegúrate de tener configurada la cola:

```bash
# En .env
QUEUE_CONNECTION=database
```

Ejecuta las migraciones si aún no lo has hecho:

```bash
php artisan migrate
```

Inicia el worker de cola:

```bash
php artisan queue:work
```

### 4. Configurar el Scheduler (Tareas Programadas)

Para que los recordatorios se envíen automáticamente, debes configurar el cron job del sistema.

#### En Linux/macOS:

Edita el crontab:
```bash
crontab -e
```

Agrega esta línea:
```
* * * * * cd /ruta/a/tu/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

#### En Windows (usando Task Scheduler):

1. Abre el Programador de Tareas
2. Crea una nueva tarea
3. Configura el trigger para ejecutarse cada minuto
4. Acción: Iniciar programa
   - Programa: `php`
   - Argumentos: `artisan schedule:run`
   - Directorio: `C:\xampp\htdocs\citas`

#### Para desarrollo (ejecutar manualmente):

```bash
php artisan schedule:work
```

## 📅 Funcionamiento

### Recordatorios Automáticos

El sistema envía dos tipos de recordatorios:

1. **24 horas antes**: Se envía diariamente a las 9:00 AM
2. **2 horas antes**: Se revisa cada 15 minutos

### Comandos Disponibles

#### Enviar recordatorios 24 horas antes (manualmente):
```bash
php artisan appointments:send-day-before-reminders
```

#### Enviar recordatorios 2 horas antes (manualmente):
```bash
php artisan appointments:send-two-hours-before-reminders
```

## 📝 Formato de Números de Teléfono

El sistema formatea automáticamente los números de teléfono al formato internacional:

- **Entrada aceptada**: 
  - `11 1234-5678`
  - `+54 11 1234-5678`
  - `011 1234-5678`
  - `1112345678`

- **Formato de salida**: `5411XXXXXXXX` (formato WhatsApp sin el +)

**Nota**: El sistema asume números argentinos (+54) si no se especifica código de país.

## 🎯 Contenido de los Mensajes

### Recordatorio 24 horas antes:
```
🏥 *Recordatorio de Cita*

Hola [Nombre del Paciente],

Te recordamos que tienes una cita programada para mañana:

📅 Fecha: [día de la semana] [día] de [mes]
🕐 Hora: [hora]
👨‍⚕️ Doctor/a: [nombre del doctor]

Por favor, confirma tu asistencia o comunícate con nosotros si necesitas cancelar o reprogramar.
```

### Recordatorio 2 horas antes:
```
🏥 *Recordatorio de Cita*

Hola [Nombre del Paciente],

Te recordamos que tienes una cita en *2 horas*:

📅 Fecha: [día de la semana] [día] de [mes]
🕐 Hora: [hora]
👨‍⚕️ Doctor/a: [nombre del doctor]

Por favor, llega 10 minutos antes.

Si necesitas cancelar o reprogramar, comunícate con nosotros lo antes posible.
```

## 🔍 Verificación y Debugging

### Verificar que el servicio esté configurado:

```php
use App\Services\WhatsAppService;

$whatsapp = app(WhatsAppService::class);
if ($whatsapp->isConfigured()) {
    echo "WhatsApp está configurado correctamente";
} else {
    echo "Falta configuración de WhatsApp";
}
```

### Ver logs:

Los logs de WhatsApp se guardan en `storage/logs/laravel.log`:

```bash
tail -f storage/logs/laravel.log | grep -i whatsapp
```

## ⚠️ Consideraciones Importantes

1. **Límites de API**: La API de WhatsApp tiene límites de mensajes. Consulta tu plan en Meta.

2. **Números verificados**: En modo de desarrollo, solo puedes enviar mensajes a números verificados en tu cuenta de Meta.

3. **Templates**: Para uso en producción, WhatsApp requiere que uses templates pre-aprobados. El servicio incluye el método `sendTemplate()` para esto.

4. **Zona horaria**: Los comandos están configurados para la zona horaria de Argentina. Modifica en `bootstrap/app.php` si es necesario.

## 🚀 Puesta en Producción

1. Solicita un número de teléfono oficial de WhatsApp Business
2. Crea y aprueba templates de mensajes en Meta Business Manager
3. Genera un token de acceso permanente
4. Configura el cron job en el servidor
5. Mantén el worker de cola corriendo (usa supervisor o similar)

### Ejemplo de configuración con Supervisor:

```ini
[program:citas-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /ruta/a/tu/proyecto/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/ruta/a/tu/proyecto/storage/logs/worker.log
```

## 📞 Soporte

Para más información sobre la API de WhatsApp Business:
- [Documentación oficial](https://developers.facebook.com/docs/whatsapp)
- [WhatsApp Business Platform](https://business.whatsapp.com/products/business-platform)
