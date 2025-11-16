# Seplyer 1.2 - Script de Saludo con Noticias

**Versión:** 1.2  
**Fecha:** 2025-11-15

## 📋 Descripción

Script bash simple que muestra un saludo y un mensaje.

## 🚀 Uso

### Ejecutar el script:

```bash
bash seplyer_1.2.sh
```

O darle permisos de ejecución:

```bash
chmod +x seplyer_1.2.sh
./seplyer_1.2.sh
```

## 📤 Salida

El script mostrará:

```
Hola, ¿cómo estás?
shh

Ruta donde se está ejecutando el script:
Directorio actual: /ruta/donde/ejecutaste/el/script
Ruta completa del script: /ruta/completa/del/archivo/seplyer_1.2.sh

=== Buscando noticia de hoy ===
Buscando noticias en: https://news.google.com

📰 Noticia de hoy:
Título: [Título de la noticia]
Resumen: [Párrafo de la noticia]...
Fuente: https://news.google.com
```

## 📝 Requisitos

- Bash (normalmente incluido en Linux/Mac)
- `curl` (para buscar noticias en internet)
- Conexión a internet
- Permisos de ejecución (opcional, si usas `./seplyer_1.2.sh`)

## 🔧 Instalación

1. Descarga el archivo `seplyer_1.2.sh`
2. Asegúrate de tener `curl` instalado (normalmente viene preinstalado)
3. (Opcional) Dale permisos de ejecución: `chmod +x seplyer_1.2.sh`
4. Ejecuta: `bash seplyer_1.2.sh` o `./seplyer_1.2.sh`

### Instalar curl (si no lo tienes)

**Linux:**
```bash
sudo apt-get install curl  # Debian/Ubuntu
sudo yum install curl       # CentOS/RHEL
```

**Mac:**
```bash
brew install curl
```

## 📄 Licencia

Script de ejemplo para uso educativo.

## 📝 Historial de Versiones

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

**Versión:** 1.2

