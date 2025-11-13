#!/bin/bash

# Instalar dependencias de PHP si es necesario
echo "Instalando dependencias..."

# Crear estructura de carpetas necesarias
mkdir -p assets/images
mkdir -p controllers
mkdir -p config

# Dar permisos necesarios
chmod -R 755 assets/
chmod 644 config/conexion.php

echo "Build completado exitosamente"s