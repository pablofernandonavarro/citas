# Implementación de Gabinetes para Kinesiólogos

## Resumen
Se ha implementado un sistema de gabinetes que permite a los kinesiólogos atender múltiples pacientes simultáneamente (uno por gabinete en el mismo horario).

## Cambios Realizados

### 1. Base de Datos

#### Nuevas Tablas Creadas:
- **`cabinets`**: Almacena los gabinetes disponibles
  - `id`: ID del gabinete
  - `name`: Nombre del gabinete
  - `description`: Descripción (opcional)
  - `is_active`: Si está activo o no
  - `created_at`, `updated_at`

- **`doctor_cabinet`**: Tabla pivot para la relación muchos a muchos
  - `id`
  - `doctor_id`: FK a doctors
  - `cabinet_id`: FK a cabinets
  - `created_at`, `updated_at`
  - Índice único en (`doctor_id`, `cabinet_id`)

#### Tabla Modificada:
- **`appointments`**: Se agregó el campo:
  - `cabinet_id`: FK a cabinets (nullable) - El doctor lo asigna después

### 2. Modelos Actualizados

#### `Cabinet` (nuevo)
- Relación `doctors()`: belongsToMany con Doctor
- Relación `appointments()`: hasMany con Appointment

#### `Doctor`
- Nueva relación `cabinets()`: belongsToMany con Cabinet

#### `Appointment`
- Nuevo campo fillable: `cabinet_id`
- Nueva relación `cabinet()`: belongsTo Cabinet

### 3. Lógica de Negocio

#### AppointmentService
**Método `searchAvailability()`:**
- Ahora carga la relación `cabinets` de cada doctor
- Pasa al método `processResults()`

**Método `getAvailableSchedules()`:**
- Cuenta cuántas citas existen en cada horario
- Obtiene la cantidad de gabinetes del doctor
- **Lógica de disponibilidad:**
  - Si el doctor tiene gabinetes: horario disponible si `citas < gabinetes`
  - Si NO tiene gabinetes: horario disponible si `citas == 0`

#### AppointmentManager (Livewire)
**Método `save()`:**
- Validación actualizada para verificar disponibilidad según gabinetes
- Cuenta citas existentes en el horario
- Obtiene cantidad de gabinetes del doctor
- **Validación:**
  - Con gabinetes: rechaza si `citas >= gabinetes`
  - Sin gabinetes: rechaza si `citas > 0`
- Mensaje de error adaptado según tenga o no gabinetes

### 4. Controlador Creado

#### `CabinetController`
CRUD completo para gestionar gabinetes:
- `index()`: Listar gabinetes
- `create()`: Formulario de creación
- `store()`: Guardar nuevo gabinete
- `show()`: Ver detalles de un gabinete
- `edit()`: Formulario de edición
- `update()`: Actualizar gabinete
- `destroy()`: Eliminar gabinete
- `assignToDoctor()`: Asignar gabinetes a un doctor

### 5. Seeder Creado

#### `CabinetSeeder`
Crea 3 gabinetes de ejemplo:
- Gabinete 1
- Gabinete 2
- Gabinete 3

## Flujo de Trabajo

### Para el Paciente
1. El paciente busca turno disponible (como antes)
2. Selecciona un horario
3. El sistema valida:
   - Si el doctor NO tiene gabinetes: solo 1 turno por horario
   - Si el doctor tiene gabinetes: permite tantos turnos como gabinetes tenga
4. Se crea la cita con `cabinet_id = null`

### Para el Doctor
1. El doctor ve sus citas del día
2. **Cuando atiende al paciente**, asigna el gabinete a la cita
3. Esto permite saber qué paciente está en qué gabinete

## Implementación Completada ✅

### 1. Rutas ✅
Rutas agregadas en `routes/admin.php`:
- `Route::resource('cabinets', CabinetController::class)` - CRUD de gabinetes
- `Route::post('doctors/{doctor}/cabinets', ...)` - Asignar gabinetes a doctor
- `Route::post('appointments/{appointment}/assign-cabinet', ...)` - Asignar gabinete a cita

### 2. Permisos ✅
Permisos creados en `PermissionSeeder.php`:
- `read_cabinet` ✅
- `create_cabinet` ✅
- `update_cabinet` ✅
- `delete_cabinet` ✅

### 3. Interfaz para Asignar Gabinetes a Doctores ✅
Implementado en `resources/views/admin/doctors/edit.blade.php`:
- Lista de gabinetes disponibles con checkboxes
- Indicador visual de gabinetes asignados
- Formulario que envía a `assignToDoctor()`
- Mensaje de ayuda y enlace para crear gabinetes si no existen

### 4. Interfaz para Asignar Gabinete a Cita ✅
Implementado en `resources/views/admin/appointments/edit.blade.php`:
- Card que muestra el gabinete actual (si existe)
- Dropdown para seleccionar gabinete
- Botón para asignar
- Solo visible si el doctor tiene gabinetes asignados
- Validación en backend que el gabinete pertenezca al doctor

### 5. Datos de Prueba ✅
Seeder ejecutado:
```bash
php artisan db:seed --class=CabinetSeeder
```
Se crearon 3 gabinetes de ejemplo.

### 6. Vistas para Administración de Gabinetes ✅
Se crearon todas las vistas necesarias:
- `resources/views/admin/cabinets/index.blade.php` ✅
  - Listado de gabinetes con tabla completa
  - Muestra nombre, descripción, doctores asignados, estado
  - Acciones: ver, editar, eliminar
  - Estado vacío con mensaje y botón para crear

- `resources/views/admin/cabinets/create.blade.php` ✅
  - Formulario para crear nuevo gabinete
  - Campos: nombre, descripción, estado (activo/inactivo)

- `resources/views/admin/cabinets/edit.blade.php` ✅
  - Formulario para editar gabinete existente
  - Muestra doctores asignados al gabinete
  - Botón para eliminar gabinete

- `resources/views/admin/cabinets/show.blade.php` ✅
  - Vista detallada del gabinete
  - Información general (nombre, descripción, estado, fechas)
  - Lista de doctores asignados con sus especialidades
  - Estadísticas de uso (total citas, citas hoy, próximas citas)

## Ejemplo de Uso

### Caso 1: Doctor sin Gabinetes (Médico General)
- Horario 10:00 - 11:00
- 0 citas existentes ✅ Puede agendar
- 1 cita existente ❌ No puede agendar

### Caso 2: Kinesiólogo con 3 Gabinetes
- Horario 10:00 - 11:00
- 0 citas existentes ✅ Puede agendar
- 1 cita existente ✅ Puede agendar (gabinete disponible)
- 2 citas existentes ✅ Puede agendar (gabinete disponible)
- 3 citas existentes ❌ No puede agendar (todos los gabinetes ocupados)

## Testing

### Probar Validación de Disponibilidad
1. Crear un doctor sin gabinetes
2. Intentar agendar 2 citas en el mismo horario → Debe fallar la segunda

3. Asignar 2 gabinetes al doctor
4. Intentar agendar 3 citas en el mismo horario → Deben pasar las 2 primeras, fallar la tercera

### Verificar Búsqueda de Disponibilidad
1. Kinesiólogo con 2 gabinetes
2. Ya tiene 1 cita a las 10:00
3. Buscar disponibilidad a las 10:00 → Debe aparecer como disponible
4. Agendar segunda cita a las 10:00
5. Buscar disponibilidad a las 10:00 → Debe aparecer como NO disponible

## Notas Importantes

- El campo `cabinet_id` en `appointments` es nullable porque se asigna DESPUÉS de crear la cita
- La validación de disponibilidad se hace al CREAR la cita, no al asignar el gabinete
- Un doctor puede tener 0 o más gabinetes
- Los turnos son de 60 minutos (configurado en el sistema)
- Solo los doctores con gabinetes pueden tener múltiples citas simultáneas

## Migraciones Ejecutadas
✅ `2025_11_16_092749_create_cabinets_table.php`
✅ `2025_11_16_093150_create_doctor_cabinet_table.php`
✅ `2025_11_16_093212_add_cabinet_id_to_appointments_table.php`
