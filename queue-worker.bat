@echo off
echo === Queue Worker - Sistema de Citas ===
echo.
echo Procesando trabajos en cola...
echo Presiona Ctrl+C para detener
echo.

:loop
php artisan queue:work database --sleep=3 --tries=3 --max-time=3600
echo.
echo Worker detenido. Reiniciando en 5 segundos...
timeout /t 5
goto loop
