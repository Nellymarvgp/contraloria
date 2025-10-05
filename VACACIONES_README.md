# Módulo de Vacaciones - Sistema de Nómina

## 📋 Descripción
Sistema completo de solicitud y aprobación de vacaciones para empleados con notificaciones por correo electrónico.

## ✨ Características Implementadas

### Para Empleados:
- ✅ Solicitar vacaciones con selección de fechas mediante calendario
- ✅ Ver todas sus solicitudes de vacaciones
- ✅ Editar solicitudes pendientes
- ✅ Eliminar solicitudes pendientes
- ✅ Recibir notificaciones por correo al aprobar/rechazar
- ✅ Cálculo automático de días solicitados

### Para Administradores:
- ✅ Ver todas las solicitudes de vacaciones de todos los empleados
- ✅ Aprobar solicitudes con comentarios opcionales
- ✅ Rechazar solicitudes con motivo obligatorio
- ✅ Envío automático de correos de notificación
- ✅ Filtrado por estado (pendiente, aprobada, rechazada)

## 🗂️ Archivos Creados

### Migración:
- `database/migrations/2025_10_04_220621_create_vacaciones_table.php`

### Modelo:
- `app/Models/Vacacion.php`

### Controlador:
- `app/Http/Controllers/VacacionController.php`

### Vistas:
- `resources/views/vacaciones/index.blade.php` - Lista de solicitudes
- `resources/views/vacaciones/create.blade.php` - Formulario de nueva solicitud
- `resources/views/vacaciones/edit.blade.php` - Editar solicitud
- `resources/views/vacaciones/show.blade.php` - Detalle de solicitud

### Emails:
- `app/Mail/VacacionAprobada.php`
- `app/Mail/VacacionRechazada.php`
- `resources/views/emails/vacacion-aprobada.blade.php`
- `resources/views/emails/vacacion-rechazada.blade.php`

## 🔧 Configuración de Correo

Para que funcionen las notificaciones por correo, configure las siguientes variables en su archivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-correo@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicación
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-correo@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Para Gmail:
1. Habilitar "Verificación en 2 pasos" en tu cuenta de Google
2. Generar una "Contraseña de aplicación" en: https://myaccount.google.com/apppasswords
3. Usar esa contraseña en `MAIL_PASSWORD`

### Para otros proveedores:
- **Mailtrap (desarrollo)**: https://mailtrap.io
- **SendGrid**: https://sendgrid.com
- **Mailgun**: https://www.mailgun.com

## 🚀 Rutas Disponibles

```php
GET    /vacaciones              - Lista de solicitudes
GET    /vacaciones/create       - Formulario nueva solicitud
POST   /vacaciones              - Guardar nueva solicitud
GET    /vacaciones/{id}         - Ver detalle
GET    /vacaciones/{id}/edit    - Editar solicitud
PUT    /vacaciones/{id}         - Actualizar solicitud
DELETE /vacaciones/{id}         - Eliminar solicitud
POST   /vacaciones/{id}/aprobar - Aprobar solicitud (admin)
POST   /vacaciones/{id}/rechazar - Rechazar solicitud (admin)
```

## 📊 Estructura de la Base de Datos

### Tabla: vacaciones
- `id` - ID único
- `empleado_id` - Referencia al empleado
- `fecha_inicio` - Fecha de inicio de vacaciones
- `fecha_fin` - Fecha de fin de vacaciones
- `dias_solicitados` - Cantidad de días
- `motivo` - Motivo de la solicitud (opcional)
- `estado` - Estado: pendiente/aprobada/rechazada
- `comentario_admin` - Comentario del administrador
- `aprobado_por` - Usuario que aprobó/rechazó
- `fecha_aprobacion` - Fecha de aprobación/rechazo
- `created_at` - Fecha de creación
- `updated_at` - Fecha de actualización

## 🎨 Interfaz de Usuario

### Características visuales:
- ✅ Diseño moderno con TailwindCSS
- ✅ Iconos Font Awesome
- ✅ Tabla responsive con DataTables
- ✅ Modales para aprobar/rechazar
- ✅ Badges de estado con colores
- ✅ Calendario HTML5 para selección de fechas
- ✅ Contador automático de días
- ✅ Validación de formularios

### Estados visuales:
- 🟡 **Pendiente**: Badge amarillo
- 🟢 **Aprobada**: Badge verde
- 🔴 **Rechazada**: Badge rojo

## 📧 Plantillas de Email

### Email de Aprobación:
- Diseño profesional con gradiente verde
- Detalles completos de las vacaciones
- Información del aprobador
- Botón para ver detalles

### Email de Rechazo:
- Diseño profesional con gradiente rojo
- Motivo del rechazo destacado
- Información del rechazador
- Sugerencia para nueva solicitud

## 🔐 Permisos

### Empleados pueden:
- Ver sus propias solicitudes
- Crear nuevas solicitudes
- Editar solicitudes pendientes
- Eliminar solicitudes pendientes

### Administradores pueden:
- Ver todas las solicitudes
- Aprobar solicitudes
- Rechazar solicitudes
- Ver historial completo

## 📝 Uso del Sistema

### Solicitar Vacaciones (Empleado):
1. Ir a "Vacaciones" en el menú lateral
2. Click en "Nueva Solicitud"
3. Seleccionar fecha de inicio y fin
4. Agregar motivo (opcional)
5. Enviar solicitud

### Aprobar/Rechazar (Administrador):
1. Ir a "Vacaciones" en el menú lateral
2. Ver lista de todas las solicitudes
3. Click en el ícono de check (✓) para aprobar
4. Click en el ícono de X para rechazar
5. Agregar comentario
6. Confirmar acción
7. El empleado recibe un correo automáticamente

## 🧪 Pruebas

Para probar el sistema:

1. **Como empleado**:
   - Crear una solicitud de vacaciones
   - Verificar que aparece en la lista
   - Editar la solicitud
   - Ver los detalles

2. **Como administrador**:
   - Ver todas las solicitudes
   - Aprobar una solicitud
   - Verificar que se envía el correo
   - Rechazar una solicitud con motivo

## 🔄 Flujo de Trabajo

```
Empleado solicita vacaciones
         ↓
Estado: PENDIENTE
         ↓
Administrador revisa
         ↓
    ┌────┴────┐
    ↓         ↓
APROBADA  RECHAZADA
    ↓         ↓
Email     Email
enviado   enviado
```

## 📌 Notas Importantes

1. **Validaciones**:
   - La fecha de inicio debe ser igual o posterior a hoy
   - La fecha de fin debe ser posterior a la fecha de inicio
   - Solo se pueden editar/eliminar solicitudes pendientes

2. **Correos**:
   - Se envían automáticamente al aprobar/rechazar
   - Requiere configuración correcta del servidor SMTP
   - El empleado debe tener email registrado

3. **Navegación**:
   - El menú "Vacaciones" está disponible para todos los usuarios autenticados
   - Los empleados solo ven sus solicitudes
   - Los administradores ven todas las solicitudes

## 🎯 Próximas Mejoras Sugeridas

- [ ] Dashboard con estadísticas de vacaciones
- [ ] Calendario visual de vacaciones del equipo
- [ ] Límite de días de vacaciones por empleado
- [ ] Aprobación por supervisor directo
- [ ] Exportar reporte de vacaciones a PDF/Excel
- [ ] Notificaciones en la aplicación (además de email)
- [ ] Historial de vacaciones por año
- [ ] Conflictos de fechas (múltiples empleados)

## 🆘 Soporte

Si encuentra algún problema:
1. Verificar configuración de correo en `.env`
2. Revisar logs en `storage/logs/laravel.log`
3. Verificar que la migración se ejecutó correctamente
4. Confirmar que el empleado tiene email registrado

---

**Desarrollado para**: Sistema de Nómina - Contraloría del Municipio Independencia
**Fecha**: Octubre 2025
