# Solución al Error de Git - API Key Detectada

## ⚠️ Problema

GitHub detectó tu API key de OpenAI en los commits y bloqueó el push por seguridad.

## ✅ Solución Aplicada

He eliminado todas las API keys de los archivos y creado versiones seguras:

1. ✅ **USAR_API_KEY.md** - Actualizado con ejemplos (sin API key real)
2. ✅ **ejecutar_ia.sh** - Eliminado (contenía tu API key)
3. ✅ **ejecutar_ia.example.sh** - Creado (versión de ejemplo segura)
4. ✅ **.gitignore** - Creado (protege archivos con secretos)

## 🔧 Pasos para Resolver

### 1. Eliminar la API key del historial de Git

Tienes dos opciones:

#### Opción A: Eliminar los commits problemáticos (si son recientes)

```bash
# Ver los últimos commits
git log --oneline -5

# Si los commits problemáticos son los últimos, puedes hacer reset
# CUIDADO: Esto eliminará los commits
git reset --soft HEAD~2  # Ajusta el número según tus commits
```

#### Opción B: Usar git filter-branch o BFG (recomendado)

```bash
# Instalar git-filter-repo (más moderno)
# O usar git filter-branch

# Eliminar el archivo del historial
git filter-branch --force --index-filter \
  "git rm --cached --ignore-unmatch ModuStackBases/USAR_API_KEY.md ModuStackBases/ejecutar_ia.sh" \
  --prune-empty --tag-name-filter cat -- --all
```

#### Opción C: Permitir el secreto en GitHub (NO recomendado)

Si realmente necesitas mantener el secreto en el historial:
1. Ve al enlace que GitHub te dio en el error
2. Sigue las instrucciones para permitir el secreto
3. ⚠️ **NO recomendado** por seguridad

### 2. Agregar archivos al staging

```bash
# Agregar los archivos corregidos
git add .gitignore
git add ejecutar_ia.example.sh
git add USAR_API_KEY.md
git add README.md
```

### 3. Hacer commit de los cambios

```bash
git commit -m "fix: Eliminar API keys de archivos públicos y agregar .gitignore"
```

### 4. Hacer push

```bash
git push origin main
```

## 📝 Archivos Modificados

- ✅ `USAR_API_KEY.md` - API keys reemplazadas con ejemplos
- ✅ `ejecutar_ia.sh` - Eliminado (estaba en .gitignore)
- ✅ `ejecutar_ia.example.sh` - Creado (versión segura)
- ✅ `.gitignore` - Creado (protege secretos)

## 🔒 Prevención Futura

1. **Nunca subas API keys a Git**
2. **Usa `.gitignore`** para archivos con secretos
3. **Usa variables de entorno** para API keys
4. **Revisa antes de hacer commit**: `git diff` antes de `git commit`

## 🚀 Cómo Usar Ahora

### Crear tu archivo ejecutar_ia.sh local (no se subirá a Git):

```bash
# 1. Copiar el ejemplo
cp ejecutar_ia.example.sh ejecutar_ia.sh

# 2. Editar y agregar tu API key
nano ejecutar_ia.sh  # o usa tu editor favorito
# Reemplaza 'TU-API-KEY-AQUI' con tu API key real

# 3. Ejecutar
bash ejecutar_ia.sh
```

**Nota:** `ejecutar_ia.sh` está en `.gitignore`, así que no se subirá a Git.

---

## ⚠️ IMPORTANTE: Revocar API Key

Si tu API key ya fue expuesta públicamente en GitHub:

1. Ve a: https://platform.openai.com/api-keys
2. Revoca la API key actual
3. Genera una nueva API key
4. Actualiza `ejecutar_ia.sh` con la nueva key

---

## ✅ Verificación

Después de hacer los cambios, verifica que no haya más secretos:

```bash
# Buscar posibles API keys en los archivos
grep -r "sk-proj" . --exclude-dir=.git
```

Si no encuentra nada, está listo para hacer push.

