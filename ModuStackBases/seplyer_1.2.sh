#!/usr/bin/env bash
# seplyer_1.2.sh
# Versión: 1.2
# Script simple de saludo con búsqueda de noticias
# Autor: Generado para práctica
# Fecha: 2025-11-15

echo "Hola, ¿cómo estás?"
echo "shh"
echo ""
echo "Ruta donde se está ejecutando el script:"
echo "Directorio actual: $(pwd)"
echo "Ruta completa del script: $(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/$(basename "${BASH_SOURCE[0]}")"
echo ""
echo "=== Buscando noticia de hoy ==="

# Verificar si curl está disponible
if ! command -v curl >/dev/null 2>&1; then
    echo "Error: curl no está instalado. No se puede buscar noticias."
    exit 1
fi

# Intentar obtener una noticia de Google News RSS (en español)
NEWS_URL="https://news.google.com/rss?hl=es-419&gl=MX&ceid=MX:es-419"
SOURCE_URL="https://news.google.com"

echo "Buscando noticias en: $SOURCE_URL"
echo ""

# Obtener el RSS y extraer el primer título y descripción
NEWS_CONTENT=$(curl -s -L --max-time 10 "$NEWS_URL" 2>/dev/null)

if [ -z "$NEWS_CONTENT" ]; then
    echo "No se pudo obtener noticias. Verifica tu conexión a internet."
    exit 1
fi

# Extraer el primer título y descripción del RSS (compatible con diferentes sistemas)
TITLE=$(echo "$NEWS_CONTENT" | grep -o '<title>[^<]*</title>' | head -2 | tail -1 | sed 's/<title>//g' | sed 's/<\/title>//g' | sed 's/&quot;/"/g' | sed 's/&amp;/\&/g' | sed 's/&lt;/</g' | sed 's/&gt;/>/g' | sed 's/&apos;/'"'"'/g')
DESCRIPTION=$(echo "$NEWS_CONTENT" | grep -o '<description>[^<]*</description>' | head -2 | tail -1 | sed 's/<description>//g' | sed 's/<\/description>//g' | sed 's/&quot;/"/g' | sed 's/&amp;/\&/g' | sed 's/&lt;/</g' | sed 's/&gt;/>/g' | sed 's/&apos;/'"'"'/g' | sed 's/<[^>]*>//g')

if [ -n "$TITLE" ]; then
    echo "📰 Noticia de hoy:"
    echo "Título: $TITLE"
    echo ""
    if [ -n "$DESCRIPTION" ] && [ "$DESCRIPTION" != "$TITLE" ]; then
        echo "Resumen:"
        echo "$DESCRIPTION" | head -c 300
        echo "..."
    fi
    echo ""
    echo "Fuente: $SOURCE_URL"
else
    echo "No se pudo extraer la noticia del feed RSS."
    echo "Fuente intentada: $SOURCE_URL"
fi

