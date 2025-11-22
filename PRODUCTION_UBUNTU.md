# 🚀 Guía de Despliegue a Producción - Ubuntu/Linux

## 📋 Checklist Pre-Despliegue

### 1. Configurar .env en el Servidor

```bash
# Editar el archivo .env en producción
nano /var/www/citas/.env
```

```env
# Aplicación
APP_NAME="Cetrip-kinesio"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña_segura

# Colas
QUEUE_CONNECTION=database

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tu-dominio.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Script de Deploy Automático

Creá el archivo `deploy.sh`:

```bash
nano /var/www/citas/deploy.sh
```

Contenido:

```bash
#!/bin/bash

echo "========================================"
echo "  Desplegando actualizaciones..."
echo "========================================"
echo ""

# Ir al directorio del proyecto
cd /var/www/citas

echo "[1/8] Activando modo mantenimiento..."
php artisan down

echo ""
echo "[2/8] Actualizando código..."
git pull origin main

echo ""
echo "[3/8] Instalando dependencias..."
composer install --optimize-autoloader --no-dev

echo ""
echo "[4/8] Ejecutando migraciones..."
php artisan migrate --force

echo ""
echo "[5/8] Limpiando cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo ""
echo "[6/8] Optimizando..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "[7/8] Reiniciando queue worker..."
php artisan queue:restart

echo ""
echo "[8/8] Desactivando modo mantenimiento..."
php artisan up

echo ""
echo "========================================"
echo "  ✅ Despliegue completado!"
echo "========================================"
echo ""
echo "Verificando estado..."
php artisan queue:failed
```

Dar permisos de ejecución:

```bash
chmod +x /var/www/citas/deploy.sh
```

### 3. Configurar Supervisor (Queue Worker)

Supervisor es el estándar para mantener procesos corriendo en Linux.

#### Instalar Supervisor:

```bash
sudo apt update
sudo apt install supervisor
```

#### Crear configuración para el worker:

```bash
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

Contenido:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/citas/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/citas/storage/logs/worker.log
stopwaitsecs=3600
```

#### Activar el worker:

```bash
# Recargar configuración de Supervisor
sudo supervisorctl reread
sudo supervisorctl update

# Iniciar el worker
sudo supervisorctl start laravel-worker:*
```

### 4. Comandos de Supervisor

```bash
# Ver estado
sudo supervisorctl status

# Detener
sudo supervisorctl stop laravel-worker:*

# Iniciar
sudo supervisorctl start laravel-worker:*

# Reiniciar
sudo supervisorctl restart laravel-worker:*

# Ver logs en tiempo real
tail -f /var/www/citas/storage/logs/worker.log
```

### 5. Configurar Permisos

```bash
# Dar permisos correctos al usuario del servidor web
sudo chown -R www-data:www-data /var/www/citas
sudo chmod -R 755 /var/www/citas
sudo chmod -R 775 /var/www/citas/storage
sudo chmod -R 775 /var/www/citas/bootstrap/cache
```

### 6. Proceso de Deploy

Cada vez que actualices el código:

```bash
# Opción 1: Usar el script (Recomendado)
cd /var/www/citas
./deploy.sh

# Opción 2: Manual
cd /var/www/citas
php artisan down
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
php artisan up
```

### 7. Configurar Cron para Tareas Programadas (Opcional)

Si usás el scheduler de Laravel:

```bash
# Editar crontab
crontab -e

# Agregar esta línea
* * * * * cd /var/www/citas && php artisan schedule:run >> /dev/null 2>&1
```

### 8. Monitoreo en Producción

#### Ver logs:

```bash
# Logs de Laravel
tail -f /var/www/citas/storage/logs/laravel.log

# Logs del worker
tail -f /var/www/citas/storage/logs/worker.log

# Logs de Nginx (si usas Nginx)
sudo tail -f /var/log/nginx/error.log

# Logs de Apache (si usas Apache)
sudo tail -f /var/log/apache2/error.log
```

#### Verificar cola:

```bash
cd /var/www/citas

# Ver trabajos pendientes
php artisan tinker --execute="echo 'Trabajos pendientes: ' . DB::table('jobs')->count();"

# Ver trabajos fallidos
php artisan queue:failed

# Reintentar fallidos
php artisan queue:retry all
```

### 9. Backup Automático

Crear script de backup:

```bash
sudo nano /var/www/citas/backup.sh
```

Contenido:

```bash
#!/bin/bash

# Variables
FECHA=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/var/backups/citas"
DB_NAME="tu_base_datos"
DB_USER="tu_usuario"
DB_PASS="tu_contraseña"

# Crear directorio si no existe
mkdir -p $BACKUP_DIR

echo "Creando backup $FECHA..."

# Backup de base de datos
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$FECHA.sql.gz

# Backup de archivos subidos
tar -czf $BACKUP_DIR/files_$FECHA.tar.gz /var/www/citas/storage/app/public

# Limpiar backups antiguos (mantener solo los últimos 7 días)
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completado: $FECHA"
```

Dar permisos:

```bash
chmod +x /var/www/citas/backup.sh
```

Automatizar con cron (backup diario a las 2 AM):

```bash
crontab -e

# Agregar:
0 2 * * * /var/www/citas/backup.sh
```

### 10. Troubleshooting

#### Worker no inicia:

```bash
# Ver logs de supervisor
sudo tail -f /var/log/supervisor/supervisord.log

# Verificar configuración
sudo supervisorctl status

# Reiniciar supervisor
sudo systemctl restart supervisor
```

#### Permisos incorrectos:

```bash
# Corregir permisos
sudo chown -R www-data:www-data /var/www/citas/storage
sudo chmod -R 775 /var/www/citas/storage
```

#### Emails no se envían:

```bash
# 1. Verificar worker
sudo supervisorctl status laravel-worker:*

# 2. Ver logs del worker
tail -f /var/www/citas/storage/logs/worker.log

# 3. Ver trabajos en cola
cd /var/www/citas
php artisan queue:failed

# 4. Reiniciar worker
sudo supervisorctl restart laravel-worker:*
```

### 11. Configuración de Firewall (UFW)

```bash
# Permitir SSH
sudo ufw allow 22/tcp

# Permitir HTTP y HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Habilitar firewall
sudo ufw enable

# Ver estado
sudo ufw status
```

### 12. SSL con Let's Encrypt (Certbot)

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx

# Obtener certificado (Nginx)
sudo certbot --nginx -d tu-dominio.com -d www.tu-dominio.com

# Renovación automática (ya viene configurado, pero verificá)
sudo certbot renew --dry-run
```

### 13. Optimización para Producción

```bash
# Instalar OPcache (si no está)
sudo apt install php8.1-opcache

# Editar php.ini
sudo nano /etc/php/8.1/fpm/php.ini

# Configurar:
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0

# Reiniciar PHP-FPM
sudo systemctl restart php8.1-fpm
```

## 🎯 Resumen Rápido

### Primera vez:
1. Configurar `.env`
2. Configurar Supervisor con el archivo `laravel-worker.conf`
3. Iniciar worker: `sudo supervisorctl start laravel-worker:*`
4. Configurar permisos

### Cada deploy:
```bash
cd /var/www/citas
./deploy.sh
```

### Verificar que todo funciona:
```bash
# Worker corriendo
sudo supervisorctl status

# Sin trabajos fallidos
php artisan queue:failed

# Sin errores en logs
tail /var/www/citas/storage/logs/laravel.log
```

---

**¡Listo para producción!** 🚀
