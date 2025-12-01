# 📱 Crear Plantilla de WhatsApp para Confirmación de Citas

## Paso 1: Acceder a Meta Business Manager

1. Ve a [Meta Business Manager](https://business.facebook.com/)
2. Inicia sesión con tu cuenta
3. Selecciona tu cuenta de negocio
4. En el menú lateral, busca **"Herramientas de cuenta" > "Plantillas de mensajes"**
5. O accede directamente a: https://business.facebook.com/wa/manage/message-templates/

## Paso 2: Crear Nueva Plantilla

Haz clic en **"Crear plantilla"** (botón azul)

### Configuración Básica

**Nombre de la plantilla:**
```
cita_confirmada
```
- Solo minúsculas, números y guiones bajos
- Sin espacios ni caracteres especiales

**Categoría:**
- Selecciona: **UTILITY**
- Esto es para mensajes transaccionales como confirmaciones

**Idiomas:**
- Selecciona: **Spanish (ARG)** o **Spanish (es_AR)**

## Paso 3: Diseñar el Contenido

### 🔵 Header (Encabezado) - OPCIONAL

**Tipo:** Texto

**Contenido:**
```
✅ Cita Confirmada
```

---

### 🟢 Body (Cuerpo) - REQUERIDO

**Contenido (copia exactamente):**
```
Hola {{1}},

Tu cita médica ha sido confirmada:

📅 *Fecha:* {{2}}
🕐 *Hora:* {{3}}
👨‍⚕️ *Doctor/a:* {{4}}
🏥 *Especialidad:* {{5}}

📍 *Ubicación:*
{{6}}

Por favor, llega 10 minutos antes de tu cita.

Si necesitas cancelar o reprogramar, comunícate con nosotros lo antes posible.

¡Te esperamos!
```

**Variables en el body:**
- `{{1}}` = Nombre del paciente
- `{{2}}` = Fecha de la cita (formato: "lunes 2 de diciembre de 2024")
- `{{3}}` = Hora de la cita (formato: "14:30")
- `{{4}}` = Nombre del doctor/a
- `{{5}}` = Especialidad médica
- `{{6}}` = Dirección y detalles del consultorio

---

### 🔵 Footer (Pie de página) - OPCIONAL

**Contenido:**
```
Sistema de Gestión de Citas Médicas
```

---

### 🟣 Buttons (Botones) - OPCIONAL

#### Opción 1: Botón de Llamada

1. Haz clic en **"Agregar botón"**
2. Tipo: **Llamar número de teléfono**
3. **Texto del botón:** `Llamar`
4. **Número de teléfono:** Tu número (ej: `+5491123456789`)

#### Opción 2: Botón de Ubicación (Google Maps)

1. Haz clic en **"Agregar botón"** 
2. Tipo: **Visitar sitio web**
3. **Texto del botón:** `Ver Ubicación`
4. **URL:** 
   - Opción A: URL fija de Google Maps
     ```
     https://maps.google.com/?q=TU_DIRECCION_AQUI
     ```
   - Opción B: URL dinámica (si usas {{7}} para la URL)
     ```
     {{1}}
     ```

#### Opción 3: Botón de Confirmación

1. Tipo: **Respuesta rápida**
2. **Texto:** `Confirmar asistencia`

**Nota:** Puedes agregar hasta 3 botones en total.

---

## Paso 4: Ejemplo Visual

Así se verá tu plantilla:

```
┌─────────────────────────────────────┐
│ ✅ Cita Confirmada                  │
├─────────────────────────────────────┤
│ Hola Juan Pérez,                    │
│                                     │
│ Tu cita médica ha sido confirmada:  │
│                                     │
│ 📅 Fecha: lunes 2 de diciembre     │
│ 🕐 Hora: 14:30                     │
│ 👨‍⚕️ Doctor/a: Dra. María López      │
│ 🏥 Especialidad: Cardiología       │
│                                     │
│ 📍 Ubicación:                       │
│ Av. Corrientes 1234                 │
│ Piso 3, Consultorio B               │
│                                     │
│ Por favor, llega 10 minutos antes.  │
│                                     │
│ Si necesitas cancelar o             │
│ reprogramar, comunícate con         │
│ nosotros lo antes posible.          │
│                                     │
│ ¡Te esperamos!                      │
├─────────────────────────────────────┤
│ Sistema de Gestión de Citas Médicas │
├─────────────────────────────────────┤
│ [Llamar] [Ver Ubicación]           │
└─────────────────────────────────────┘
```

## Paso 5: Enviar para Aprobación

1. Revisa toda la plantilla
2. Haz clic en **"Enviar"**
3. Meta revisará tu plantilla (usualmente toma entre **15 minutos y 24 horas**)
4. Recibirás un email cuando sea aprobada

### ⚠️ Importante para la aprobación:

- **NO uses texto promocional** ("Descuento", "Oferta", etc.)
- **SÍ usa texto informativo** (Confirmaciones, recordatorios)
- La categoría **UTILITY** es para mensajes transaccionales
- Las variables {{1}}, {{2}}, etc. deben estar donde corresponda

## Paso 6: Verificar Estado

Para ver el estado de tu plantilla:

1. Ve a la lista de plantillas
2. Busca `cita_confirmada`
3. Estados posibles:
   - ⏳ **PENDING** = En revisión
   - ✅ **APPROVED** = Aprobada (lista para usar)
   - ❌ **REJECTED** = Rechazada (revisa el motivo)

## Paso 7: Configurar en el Código

Una vez **APROBADA**, la plantilla ya está configurada en:
- `app/Notifications/AppointmentCreatedNotification.php`

**Archivo ya actualizado** ✅

### Parámetros que se enviarán automáticamente:

1. `{{1}}` → Nombre del paciente
2. `{{2}}` → Fecha formateada (ej: "lunes 2 de diciembre de 2024")
3. `{{3}}` → Hora (ej: "14:30")
4. `{{4}}` → Nombre del doctor
5. `{{5}}` → Especialidad
6. `{{6}}` → Ubicación del gabinete o consultorio

## Paso 8: Probar el Envío

Una vez aprobada la plantilla, prueba:

```bash
# Renovar el token si expiró
# Edita .env con el nuevo WHATSAPP_ACCESS_TOKEN

# Probar envío
php test_whatsapp_created.php

# Procesar cola
php artisan queue:work --stop-when-empty
```

---

## 🔧 Solución de Problemas

### Error: "Template not found"
- Espera a que Meta apruebe la plantilla
- Verifica que el nombre sea exactamente `cita_confirmada`

### Error: "Invalid parameter count"
- Asegúrate de que la plantilla tenga 6 parámetros {{1}} a {{6}}
- Verifica que `getTemplateParameters()` retorne 6 valores

### Error: "Access token expired"
- Renueva el token en Meta for Developers
- Actualiza `WHATSAPP_ACCESS_TOKEN` en tu `.env`

---

## 📞 Recursos Adicionales

- [Meta Business Manager](https://business.facebook.com/)
- [Documentación de Plantillas WhatsApp](https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates)
- [WhatsApp Business Platform](https://business.whatsapp.com/)

---

## ✅ Checklist Final

- [ ] Plantilla creada con nombre `cita_confirmada`
- [ ] Categoría: UTILITY
- [ ] Idioma: Spanish (es)
- [ ] Body con 6 variables {{1}} a {{6}}
- [ ] Plantilla enviada para aprobación
- [ ] Estado: APPROVED
- [ ] Token de WhatsApp renovado en `.env`
- [ ] Probado con `test_whatsapp_created.php`
- [ ] Queue worker corriendo
