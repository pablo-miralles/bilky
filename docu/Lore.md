# Lore (contexto del proyecto)

## Proyecto
Tema de WordPress para **Bilky** (sitio web corporativo). El repositorio contiene:

- Un **tema** (no plugin) con componentes/bloques propios.
- CPT existentes:
  - `clientes` (gestión de clientes, sin single público).
  - `centro_de_ayuda` (artículos de ayuda, público y con categorías nativas `category`).

## Origen de datos
Existe un dump SQL en `docu/frontend_bilky-20250119.sql/frontend_bilky-20250119.sql` (procedente de una app previa) con tablas relevantes:

- `posts` (blog)
- `clients` (clientes)
- `partners` (partners)
- `articles` + `article_translations` + `categories` (centro de ayuda)
- `tags` (etiquetas asociadas a posts)

## Objetivo actual
Migrar contenido desde el dump SQL a WordPress mediante un **importador idempotente** (re-ejecutable sin duplicar) respetando especialmente:

- **Slugs/URLs** en `post` y `centro_de_ayuda` (SEO).
- Estructura de categorías del centro de ayuda (`categories` SQL -> `category` WP).
- Datos clave de `clientes` y `partners` (título, tipo, orden, URL).

