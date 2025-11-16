#!/usr/bin/env bash
# seplyer_1.3.sh
# Versión: 1.3
# Script de saludo con integración de IA
# Autor: Generado para práctica
# Fecha: 2025-11-15

echo "Hola, ¿cómo estás?"
echo "shh"
echo ""

# Verificar si curl está disponible
if ! command -v curl >/dev/null 2>&1; then
    echo "Error: curl no está instalado. No se puede conectar a la IA."
    exit 1
fi

# Verificar si jq está disponible (para parsear JSON)
if ! command -v jq >/dev/null 2>&1; then
    echo "Advertencia: jq no está instalado. La respuesta puede no formatearse correctamente."
    echo "Instala jq con: sudo apt-get install jq (Linux) o brew install jq (Mac)"
    USE_JQ=false
else
    USE_JQ=true
fi

# Configuración de API (puedes cambiar esto)
# Para usar OpenAI, necesitas una API key: https://platform.openai.com/api-keys
API_KEY="${OPENAI_API_KEY:-}"
API_URL="https://api.openai.com/v1/chat/completions"
MODEL="gpt-3.5-turbo"

# Si no hay API key, usar un servicio alternativo o mostrar error
if [ -z "$API_KEY" ]; then
    echo "⚠️  No se encontró OPENAI_API_KEY en las variables de entorno."
    echo ""
    echo "Para usar este script necesitas:"
    echo "1. Obtener una API key de OpenAI: https://platform.openai.com/api-keys"
    echo "2. Exportar la variable: export OPENAI_API_KEY='tu-api-key'"
    echo "3. O ejecutar: OPENAI_API_KEY='tu-api-key' bash seplyer_1.3.sh"
    echo ""
    read -p "¿Tienes una API key de OpenAI? (s/n): " tiene_key
    
    if [ "$tiene_key" = "s" ] || [ "$tiene_key" = "S" ]; then
        read -p "Ingresa tu API key de OpenAI: " API_KEY
    else
        echo "Sin API key no se puede conectar a la IA. Saliendo..."
        exit 1
    fi
fi

echo "=== Conectado a IA (OpenAI) ==="
echo ""

# Bucle para hacer preguntas
while true; do
    echo "Puedes hacer una pregunta a la IA (escribe 'salir' para terminar):"
    read -p "Tu pregunta: " pregunta
    
    # Verificar si quiere salir
    if [ "$pregunta" = "salir" ] || [ "$pregunta" = "exit" ] || [ "$pregunta" = "quit" ]; then
        echo "¡Hasta luego!"
        break
    fi
    
    # Verificar que la pregunta no esté vacía
    if [ -z "$pregunta" ]; then
        echo "Por favor, ingresa una pregunta."
        echo ""
        continue
    fi
    
    echo ""
    echo "🤔 Pensando..."
    echo ""
    
    # Escapar caracteres especiales en la pregunta para JSON
    pregunta_escaped=$(echo "$pregunta" | sed 's/\\/\\\\/g' | sed 's/"/\\"/g' | sed "s/'/\\'/g" | sed 's/$/\\n/' | tr -d '\n')
    
    # Preparar el JSON para la API de OpenAI
    JSON_DATA=$(cat <<EOF
{
  "model": "$MODEL",
  "messages": [
    {
      "role": "user",
      "content": "$pregunta_escaped"
    }
  ],
  "max_tokens": 500,
  "temperature": 0.7
}
EOF
)
    
    # Hacer la petición a la API
    RESPONSE=$(curl -s -X POST "$API_URL" \
        -H "Content-Type: application/json" \
        -H "Authorization: Bearer $API_KEY" \
        -d "$JSON_DATA" \
        --max-time 30)
    
    # Verificar si hubo error
    if [ $? -ne 0 ]; then
        echo "❌ Error al conectar con la IA. Verifica tu conexión a internet."
        echo ""
        continue
    fi
    
    # Extraer la respuesta
    if [ "$USE_JQ" = true ]; then
        RESPUESTA=$(echo "$RESPONSE" | jq -r '.choices[0].message.content' 2>/dev/null)
        ERROR=$(echo "$RESPONSE" | jq -r '.error.message' 2>/dev/null)
    else
        # Fallback sin jq (más básico)
        RESPUESTA=$(echo "$RESPONSE" | grep -o '"content":"[^"]*"' | head -1 | sed 's/"content":"//g' | sed 's/"$//g')
        ERROR=$(echo "$RESPONSE" | grep -o '"message":"[^"]*"' | head -1 | sed 's/"message":"//g' | sed 's/"$//g')
    fi
    
    # Mostrar respuesta o error
    if [ -n "$ERROR" ] && [ "$ERROR" != "null" ]; then
        echo "❌ Error de la API: $ERROR"
        echo ""
    elif [ -n "$RESPUESTA" ] && [ "$RESPUESTA" != "null" ]; then
        echo "🤖 Respuesta de la IA:"
        echo "$RESPUESTA"
        echo ""
    else
        echo "❌ No se pudo obtener una respuesta válida."
        echo "Respuesta cruda: $RESPONSE"
        echo ""
    fi
done

