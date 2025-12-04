#!/bin/bash

# Verificar si el worker está corriendo
if ! pgrep -f "queue:work" > /dev/null; then
    echo "Worker no está corriendo, reiniciando..."
    php artisan queue:restart  # Sin argumentos
    php artisan queue:work --daemon &
fi
