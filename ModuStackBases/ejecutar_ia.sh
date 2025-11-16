#!/usr/bin/env bash
# ejecutar_ia.sh
# Script helper para ejecutar seplyer_1.3.sh con la API key configurada
# Fecha: 2025-11-15

# Tu API key de OpenAI
API_KEY="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"

# Verificar que seplyer_1.3.sh existe
if [ ! -f "seplyer_1.3.sh" ]; then
    echo "Error: No se encontró seplyer_1.3.sh"
    echo "Asegúrate de estar en el directorio correcto."
    exit 1
fi

# Ejecutar el script con la API key
echo "🚀 Ejecutando seplyer_1.3.sh con API key configurada..."
echo ""
OPENAI_API_KEY="$API_KEY" bash seplyer_1.3.sh

