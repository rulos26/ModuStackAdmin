# Seplyer 1.3 - Script de Saludo con IA

**Versión:** 1.3  
**Fecha:** 2025-11-15

## 📋 Descripción

Script bash simple que muestra un saludo y permite hacer preguntas a una IA (OpenAI ChatGPT).

## 🚀 Uso

### Ejecutar el script:

**Opción más fácil (recomendada):**
```bash
# 1. Copia el archivo de ejemplo
cp ejecutar_ia.example.sh ejecutar_ia.sh

# 2. Edita ejecutar_ia.sh y reemplaza 'TU-API-KEY-AQUI' con tu API key real

# 3. Ejecuta
bash ejecutar_ia.sh
```

**Nota:** `ejecutar_ia.sh` está en `.gitignore` para proteger tu API key.

**Otras opciones:**

```bash
# Opción 1: Con API key en variable de entorno
export OPENAI_API_KEY='tu-api-key-aqui'
bash seplyer_1.3.sh

# Opción 2: Pasar API key directamente
OPENAI_API_KEY='tu-api-key-aqui' bash seplyer_1.3.sh

# Opción 3: El script te pedirá la API key si no está configurada
bash seplyer_1.3.sh
```

O darle permisos de ejecución:

```bash
chmod +x seplyer_1.3.sh
./seplyer_1.3.sh
```

## 📤 Salida

El script mostrará:

```
Hola, ¿cómo estás?
shh

=== Conectado a IA (OpenAI) ===

Puedes hacer una pregunta a la IA (escribe 'salir' para terminar):
Tu pregunta: [tu pregunta aquí]

🤔 Pensando...

🤖 Respuesta de la IA:
[Respuesta de la IA]
```

## 📝 Requisitos

- Bash (normalmente incluido en Linux/Mac)
- `curl` (para conectarse a la API de OpenAI)
- `jq` (recomendado, para parsear JSON mejor)
- API key de OpenAI (obtener en: https://platform.openai.com/api-keys)
- Conexión a internet
- Permisos de ejecución (opcional, si usas `./seplyer_1.3.sh`)

## 🔧 Instalación

1. Descarga el archivo `seplyer_1.3.sh`
2. Asegúrate de tener `curl` instalado (normalmente viene preinstalado)
3. (Recomendado) Instala `jq` para mejor formato de respuestas
4. Obtén una API key de OpenAI: https://platform.openai.com/api-keys
5. (Opcional) Dale permisos de ejecución: `chmod +x seplyer_1.3.sh`
6. Ejecuta: `bash seplyer_1.3.sh` o `./seplyer_1.3.sh`

### Instalar dependencias

**Linux:**
```bash
sudo apt-get install curl jq  # Debian/Ubuntu
sudo yum install curl jq       # CentOS/RHEL
```

**Mac:**
```bash
brew install curl jq
```

### Configurar API Key de OpenAI

**Opción 1: Variable de entorno (recomendado)**
```bash
export OPENAI_API_KEY='tu-api-key-aqui'
```

**Opción 2: Agregar a tu archivo de configuración**
```bash
# En ~/.bashrc o ~/.zshrc
echo 'export OPENAI_API_KEY="tu-api-key-aqui"' >> ~/.bashrc
source ~/.bashrc
```

**Opción 3: El script te pedirá la API key al ejecutarlo**

## 📄 Licencia

Script de ejemplo para uso educativo.

## 📝 Historial de Versiones

### Versión 1.3 (2025-11-15)
- Simplificado a solo el saludo inicial
- Agregada integración con IA (OpenAI ChatGPT)
- Permite hacer preguntas interactivas a la IA
- La IA responde en tiempo real
- Soporte para API key de OpenAI
- Bucle interactivo para múltiples preguntas
- Comando 'salir' para terminar la sesión
- Actualización de documentación

### Versión 1.2 (2025-11-15)
- Agregada funcionalidad para buscar noticias de hoy en internet
- Muestra un párrafo simple de una noticia actual
- Muestra la fuente de donde se obtiene la noticia (Google News)
- Requiere conexión a internet y curl
- Actualización de documentación

### Versión 1.1 (2025-11-15)
- Renombrado de hola.sh a seplyer_1.1.sh
- Agregada funcionalidad para mostrar la ruta donde se ejecuta el script
- Muestra directorio actual y ruta completa del script
- Actualización de documentación

### Versión 1.0 (2025-11-15)
- Versión inicial
- Funcionalidad básica de saludo

---

## 💡 Uso Interactivo

Una vez ejecutado el script, puedes:

1. **Hacer preguntas**: Escribe cualquier pregunta y presiona Enter
2. **Múltiples preguntas**: El script permanece activo para hacer varias preguntas
3. **Salir**: Escribe `salir`, `exit` o `quit` para terminar

### Ejemplos de preguntas:

- "¿Qué es Python?"
- "Explícame la teoría de la relatividad"
- "Dame un chiste"
- "¿Cuál es la capital de Francia?"

---

**Versión:** 1.3

