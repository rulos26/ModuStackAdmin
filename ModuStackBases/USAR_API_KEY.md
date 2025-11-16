# Cómo usar tu API Key de OpenAI

## 🔑 Tu API Key está lista

Ya tienes tu API key de OpenAI. Aquí tienes **3 formas** de usarla:

---

## Opción 1: Pasar la API key directamente al ejecutar (Más fácil) ⭐

```bash
OPENAI_API_KEY='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' bash seplyer_1.3.sh
```

**Ventaja:** No necesitas configurar nada, solo ejecutas el comando.

---

## Opción 2: Variable de entorno (Recomendado para uso frecuente)

### En Linux/Mac:

```bash
export OPENAI_API_KEY='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'
bash seplyer_1.3.sh
```

### Para que persista (agregar a ~/.bashrc o ~/.zshrc):

```bash
echo 'export OPENAI_API_KEY="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"' >> ~/.bashrc
source ~/.bashrc
```

**Ventaja:** Solo lo configuras una vez y funciona siempre.

---

## Opción 3: El script te pedirá la API key

Si ejecutas el script sin la API key, te preguntará si la tienes y podrás ingresarla:

```bash
bash seplyer_1.3.sh
```

Luego cuando te pregunte, ingresa tu API key.

---

## ✅ Verificación

Para verificar que todo funciona, ejecuta:

```bash
OPENAI_API_KEY='tu-api-key' bash seplyer_1.3.sh
```

Deberías ver:

```
Hola, ¿cómo estás?
shh

=== Conectado a IA (OpenAI) ===

Puedes hacer una pregunta a la IA (escribe 'salir' para terminar):
Tu pregunta: 
```

---

## 🔒 Seguridad

⚠️ **IMPORTANTE:** 
- **NO compartas** tu API key públicamente
- **NO la subas** a repositorios Git públicos
- Si alguien la ve, revócala inmediatamente en: https://platform.openai.com/api-keys

---

## 🚀 Listo para usar

Con tu API key, el script está **100% listo** para funcionar. Solo necesitas:

1. ✅ API key (ya la tienes)
2. ✅ Script `seplyer_1.3.sh` (ya está creado)
3. ✅ `curl` instalado (normalmente viene preinstalado)
4. ✅ Conexión a internet

¡Ya puedes empezar a hacer preguntas a la IA!

