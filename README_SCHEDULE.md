# Configuración de Horarios (Schedule)

Este documento explica cómo funciona la configuración de horarios para la gestión de citas médicas.

## Archivo de Configuración

La configuración principal se encuentra en: **`config/schedule.php`**

Este archivo controla todos los aspectos del sistema de horarios de la aplicación.

## Parámetros de Configuración

### 1. `days` (Días de la semana)

Define qué días de la semana estarán disponibles para agendar citas.

```php
'days' => [
    1 => 'lunes',
    2 => 'martes',
    3 => 'miércoles',
    4 => 'jueves',
    5 => 'viernes',
    // 6 => 'sábado',
    // 0 => 'domingo',
],
```

**Valores:**
- `0` = Domingo
- `1` = Lunes
- `2` = Martes
- `3` = Miércoles
- `4` = Jueves
- `5` = Viernes
- `6` = Sábado

**Nota:** Comenta o descomenta las líneas para habilitar/deshabilitar días específicos.

### 2. `appointments_duration` (Duración de las citas)

Define la duración de cada intervalo de cita en minutos.

```php
'appointments_duration' => 15,
```

**Valores comunes:**
- `15` = Intervalos de 15 minutos (08:00, 08:15, 08:30, 08:45...)
- `30` = Intervalos de 30 minutos (08:00, 08:30, 09:00...)
- `60` = Intervalos de 1 hora (08:00, 09:00, 10:00...)

### 3. `start_time` (Hora de inicio)

Define la hora de inicio del horario laboral.

```php
'start_time' => '10:00:00',
```

**Formato:** `HH:MM:SS` (24 horas)

### 4. `end_time` (Hora de fin)

Define la hora de fin del horario laboral.

```php
'end_time' => '16:00:00',
```

**Formato:** `HH:MM:SS` (24 horas)

**Importante:** El último intervalo disponible será hasta esta hora. Por ejemplo:
- Si `end_time = '16:00:00'` y `appointments_duration = 15`
- El último intervalo será: **15:45 - 16:00**

## Ejemplo de Configuración

### Ejemplo 1: Clínica de lunes a viernes, 8am a 6pm, citas de 15 minutos

```php
return [
    'days' => [
        1 => 'lunes',
        2 => 'martes',
        3 => 'miércoles',
        4 => 'jueves',
        5 => 'viernes',
    ],
    'appointments_duration' => 15,
    'start_time' => '08:00:00',
    'end_time' => '18:00:00',
];
```

### Ejemplo 2: Clínica todos los días, 9am a 5pm, citas de 30 minutos

```php
return [
    'days' => [
        0 => 'domingo',
        1 => 'lunes',
        2 => 'martes',
        3 => 'miércoles',
        4 => 'jueves',
        5 => 'viernes',
        6 => 'sábado',
    ],
    'appointments_duration' => 30,
    'start_time' => '09:00:00',
    'end_time' => '17:00:00',
];
```

## Cómo Aplicar Cambios

1. Edita el archivo `config/schedule.php`
2. Guarda los cambios
3. Limpia el caché de configuración:
   ```bash
   php artisan config:clear
   ```
4. Los cambios se aplicarán inmediatamente en el gestor de horarios

## Componentes que Usan Esta Configuración

- **ScheduleManager** (`app/Livewire/Admin/ScheduleManager.php`): Gestor de horarios de doctores
- **Vista de horarios** (`resources/views/livewire/admin/schedule-manager.blade.php`): Interfaz visual

## Notas Importantes

⚠️ **Advertencia:** Cambiar la configuración después de haber creado horarios puede causar inconsistencias. Se recomienda:
1. Eliminar los horarios existentes
2. Cambiar la configuración
3. Volver a crear los horarios

🔧 **Tip:** Si cambias `appointments_duration`, asegúrate de que 60 sea divisible por ese número (15, 20, 30) para evitar problemas de cálculo.
