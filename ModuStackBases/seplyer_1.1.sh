#!/usr/bin/env bash
# seplyer_1.1.sh
# Versión: 1.1
# Script simple de saludo
# Autor: Generado para práctica
# Fecha: 2025-11-15

echo "Hola, ¿cómo estás?"
echo "shh"
echo ""
echo "Ruta donde se está ejecutando el script:"
echo "Directorio actual: $(pwd)"
echo "Ruta completa del script: $(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/$(basename "${BASH_SOURCE[0]}")"

