# Guía para Migrar a Template Personalizado en Producción

## Estado Actual
✅ El sistema está configurado con el template **hello_world** para testing
- Template: `hello_world`
- Idioma: `en_US`
- Sin parámetros personalizados

## Pasos para Migrar a Template Personalizado

### 1. Crear Template en Meta Business Suite

Ve a [Meta Business Suite](https://business.facebook.com/) y crea un template personalizado con la siguiente estructura:

#### Ejemplo de Template Recomendado:

**Nombre del template:** `confirmacion_turno_medico`

**Categoría:** UTILITY (para confirmaciones de citas)

**Idioma:** Español (es)

**Cuerpo del mensaje:**
```
Hola {{1}} 👋

Te confirmamos tu turno médico:

📅 Fecha y hora: {{2}}
👨‍⚕️ Profesional: {{3}}

Si no puedes asistir, por favor avísanos con anticipación.

Gracias por confiar en nosotros.
```

**Parámetros:**
- {{1}} = Nombre del paciente
- {{2}} = Fecha y hora del turno
- {{3}} = Nombre del doctor

### 2. Esperar Aprobación de Meta

Meta debe aprobar tu template. Esto puede tomar de 1 a 24 horas.

### 3. Actualizar el archivo .env

Una vez aprobado el template, actualiza estas líneas en tu archivo `.env`:

```env
# Cambiar de hello_world a tu template
WHATSAPP_TEMPLATE_NAME=confirmacion_turno_medico

# Cambiar el idioma a español
WHATSAPP_TEMPLATE_LANGUAGE=es
```

### 4. El Código Ya Está Preparado

El archivo `app/Services/WhatsAppService.php` ya tiene el código listo para usar templates personalizados:

- ✅ Si usas `hello_world`: envía sin parámetros
- ✅ Si usas otro template: envía con 3 parámetros (nombre, fecha/hora, doctor)

**No necesitas modificar el código.** Solo cambia el `.env` y el sistema automáticamente:
1. Detectará que NO es `hello_world`
2. Enviará los parámetros correctos

### 5. Limpiar Caché

Después de cambiar el `.env`, ejecuta:

```bash
php artisan config:clear
```

### 6. Verificar Configuración

Ejecuta el comando de diagnóstico:

```bash
php artisan whatsapp:diagnose
```

Deberías ver:
```
✅ Configuración completa
✅ Template configurado: confirmacion_turno_medico
```

### 7. Probar

Crea una cita de prueba y verifica que:
1. El WhatsApp se envíe correctamente
2. Los datos aparezcan en el mensaje
3. Revisa los logs: `storage/logs/laravel.log`

## Personalización Adicional del Template

Si necesitas más parámetros en tu template, modifica el método `sendTemplateMessage` en `WhatsAppService.php`:

```php
// Línea 261-277
$templatePayload['template']['components'] = [
    [
        'type' => 'body',
        'parameters' => [
            ['type' => 'text', 'text' => $patientName],
            ['type' => 'text', 'text' => $dateTime],
            ['type' => 'text', 'text' => $doctorName],
            // Agrega más parámetros aquí si tu template los necesita
            // ['type' => 'text', 'text' => $speciality],
            // ['type' => 'text', 'text' => $cabinet],
        ],
    ],
];
```

## Solución de Problemas

### Error: "Template name does not exist"
- Verifica que el nombre del template en `.env` sea exactamente igual al de Meta
- Verifica que el template esté APROBADO en Meta

### Error: "Template name does not exist in [idioma]"
- Verifica que `WHATSAPP_TEMPLATE_LANGUAGE` coincida con el idioma del template en Meta
- Ejemplo: si el template está en español, usa `es` no `es_AR` o `es_ES`

### Error: "There's an issue with the parameters"
- Verifica que la cantidad de parámetros en el código coincida con los {{X}} en Meta
- Verifica que no haya saltos de línea o espacios excesivos en los textos

## Mantener hello_world para Testing

Si quieres mantener `hello_world` para pruebas y usar el template personalizado solo en producción:

1. En `.env.local` (desarrollo):
```env
WHATSAPP_TEMPLATE_NAME=hello_world
WHATSAPP_TEMPLATE_LANGUAGE=en_US
```

2. En `.env.production` (producción):
```env
WHATSAPP_TEMPLATE_NAME=confirmacion_turno_medico
WHATSAPP_TEMPLATE_LANGUAGE=es
```

## Resumen

✅ **Actualmente:** Usando `hello_world` (funciona de inmediato, sin aprobación)
🔄 **Para producción:** Cambiar a tu template personalizado (requiere aprobación de Meta)
📝 **El código:** Ya está preparado para ambos casos

¿Necesitas ayuda? Revisa los logs en `storage/logs/laravel.log` buscando "WhatsApp"
