# Template de WhatsApp: confirmacion_de_turno

## Configuración en Meta Business

Para que el sistema envíe WhatsApp automáticamente al crear turnos, necesitás crear y aprobar este template en Meta Business Manager.

### Paso 1: Acceder a WhatsApp Manager
1. Ve a https://business.facebook.com/
2. Seleccioná tu cuenta de WhatsApp Business
3. Click en **WhatsApp Manager**
4. En el menú izquierdo, click en **Message Templates**

### Paso 2: Crear el Template

Click en **Create Template** y completá:

**Nombre del template:** `confirmacion_de_turno`

**Categoría:** `UTILITY` (para confirmaciones de citas)

**Idioma:** `Spanish (es)`

### Paso 3: Contenido del Template

#### Header (opcional)
Podés dejar vacío o agregar:
- Texto: `Confirmación de Turno`

#### Body (obligatorio)
```
Hola {{1}}, 

Te confirmamos tu turno para {{2}} a las {{3}}.

👨‍⚕️ Profesional: {{4}}
🩺 Especialidad: {{5}}

📍 Dirección: {{6}}

Por favor, llegá 10 minutos antes de tu hora programada.

Si necesitás cancelar o reprogramar, comunicate con nosotros con anticipación.
```

**Variables:**
1. `{{1}}` - Nombre del paciente
2. `{{2}}` - Fecha (ej: "lunes 2 de diciembre de 2025")
3. `{{3}}` - Hora (ej: "14:30")
4. `{{4}}` - Nombre del doctor
5. `{{5}}` - Especialidad
6. `{{6}}` - Ubicación de la clínica

#### Footer (opcional)
```
Gracias por confiar en nosotros 🏥
```

#### Buttons (opcional)
Podés agregar botones como:
- **Call to Action**: Llamar al consultorio
- **Quick Reply**: "Confirmar asistencia"

### Paso 4: Enviar para Aprobación

1. Click en **Submit**
2. La aprobación suele tardar **algunas horas hasta 24 horas**
3. Recibirás una notificación cuando sea aprobado

### Paso 5: Una vez aprobado

El sistema **ya está configurado** para usar este template automáticamente cuando:
- Se crea un turno nuevo desde el sistema
- El paciente tiene un número de teléfono registrado

## Verificación

Para verificar que todo funciona, ejecutá:

```bash
php test_whatsapp_text.php
```

O creá un turno desde la interfaz web y revisá los logs:

```bash
tail -20 storage/logs/laravel.log | grep -i whatsapp
```

## Troubleshooting

### El mensaje no se envía
- Verificá que el template esté **aprobado** en Meta
- Verificá que el token no haya expirado
- Revisá los logs: `tail -50 storage/logs/laravel.log`

### Error: Template not found
- Verificá que el nombre sea exactamente `confirmacion_de_turno`
- Verificá que el idioma sea `es` (no `es_AR` ni `es_ES`)

### Error: Token expired
- Generá un nuevo token en Meta for Developers
- Actualizá `WHATSAPP_ACCESS_TOKEN` en el `.env`
- Ejecutá: `php artisan config:clear`

## Configuración Actual

✅ Servicio configurado en: `app/Services/WhatsAppService.php`
✅ Template configurado: `confirmacion_de_turno`
✅ Idioma: `es`
✅ Se envía automáticamente al crear turnos en:
   - `app/Livewire/Admin/AppointmentManager.php` (línea 217)
   - `app/Http/Controllers/Admin/AppointmentController.php` (línea 47)

## Parámetros del Template

Los siguientes datos se envían automáticamente:
1. Nombre del paciente (desde `users.name`)
2. Fecha formateada (ej: "lunes 2 de diciembre de 2025")
3. Hora (formato 24h, ej: "14:30")
4. Nombre del doctor
5. Especialidad del doctor
6. Ubicación: "Jose C Paz 5723, San Martín, Buenos Aires, Argentina"
