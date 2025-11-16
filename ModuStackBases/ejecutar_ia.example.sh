#!/usr/bin/env bash
# ejecutar_ia.example.sh
# Script helper de ejemplo para ejecutar seplyer_1.3.sh con la API key configurada
# Fecha: 2025-11-15
# 
# INSTRUCCIONES:
# 1. Copia este archivo: cp ejecutar_ia.example.sh ejecutar_ia.sh
# 2. Edita ejecutar_ia.sh y reemplaza 'TU-API-KEY-AQUI' con tu API key real
# 3. Ejecuta: bash ejecutar_ia.sh

# Tu API key de OpenAI (REEMPLAZA 'TU-API-KEY-AQUI' con tu API key real)
API_KEY="TU-API-KEY-AQUI"

# Verificar que seplyer_1.3.sh existe
if [ ! -f "seplyer_1.3.sh" ]; then
    echo "Error: No se encontró seplyer_1.3.sh"
    echo "Asegúrate de estar en el directorio correcto."
    exit 1
fi

# Verificar que la API key no sea el placeholder
if [ "$API_KEY" = "TU-API-KEY-AQUI" ]; then
    echo "⚠️  Error: Debes configurar tu API key en este archivo."
    echo "Edita ejecutar_ia.sh y reemplaza 'TU-API-KEY-AQUI' con tu API key real."
    exit 1
fi

# Ejecutar el script con la API key
echo "🚀 Ejecutando seplyer_1.3.sh con API key configurada..."
echo ""
OPENAI_API_KEY="$API_KEY" bash seplyer_1.3.sh

