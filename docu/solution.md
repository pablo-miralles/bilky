# Solution (desarrollo realizado)

## Fase 1 — Base técnica del importador (COMPLETADA)

### Cambios realizados
- **Nuevo CPT `partners`** y **taxonomía `partner_tipo`**:
  - Archivo: `includes/partners-cpt.php`
  - Características: gestión en admin, sin single público (redirige a home), taxonomía jerárquica para “Tipo”.

- **Campos ACF**
  - `clientes`: se añadió `cliente_order` para almacenar el “Orden”.
    - Archivo: `includes/clientes-fields.php`
  - `partners`: se añadieron `partner_url`, `partner_order`, `partner_priority`.
    - Archivo: `includes/partners-fields.php`

- **Importador desde dump SQL**
  - Archivo: `includes/sql-importer.php`
  - Se integra en el tema vía: `includes/functions.php`
  - Implementa un **upsert idempotente** usando meta:
    - `_bilky_sql_table`
    - `_bilky_sql_id`
  - Además marca el contenido importado para poder **borrarlo en bloque**:
    - `_bilky_sql_imported`
    - `_bilky_sql_import_created` (solo si el importador lo creó)
    - `_bilky_sql_imported_at`
  - **Medios (imágenes)**
    - Importa imágenes como **adjuntos** en la librería de medios desde:
      - **Media base DIR** (ficheros subidos por FTP), o
      - **Media base URL** (descarga por HTTP).
    - Asigna la primera imagen como **imagen destacada**.
    - Si hay más de una, guarda las restantes en el custom field `bilky_import_additional_images` (array de IDs de adjunto).
  - Importa y mapea:
    - `posts` -> `post` (slug/título/contenido/extracto + tags + meta SEO propia)
    - `clients` -> `clientes` (título + `cliente_url` + `cliente_order` + tipo en `cliente_categoria`)
    - `partners` -> `partners` (título + `partner_url` + `partner_order` + `partner_tipo`)
    - `articles` + `article_translations` + `categories` -> `centro_de_ayuda` (slug/título/contenido + categoría nativa `category`)

### Cómo ejecutar
El importador se puede ejecutar **sin WP‑CLI** desde el panel de WordPress (útil cuando solo hay acceso por FTP).

#### Opción A — Sin WP‑CLI (recomendada si solo tienes FTP)
- En WP Admin ve a: **Herramientas → Importador SQL**
- Selecciona:
  - Fichero SQL (por defecto apunta a la ruta del tema).
  - Idioma (`es`/`en`).
  - Qué importar (posts/clients/partners/articles).
- (Opcional) configura medios:
  - **Media base DIR**: carpeta (en disco) donde has subido las imágenes por FTP.
  - **Media base URL**: URL base desde la que se pueden descargar las imágenes.
  - Marca **Importar imágenes** para que se creen adjuntos + destacada + `bilky_import_additional_images`.
- Ejecuta primero con **Dry run** para validar conteos, y luego sin dry-run para importar.

#### Borrar/rollback desde el admin
En la misma pantalla (**Herramientas → Importador SQL**) tienes el bloque **Borrar contenido importado**:

- Borra por secciones (posts/clients/partners/articles).
- Por defecto borra **solo lo creado por el importador** (modo seguro).
- Opcionalmente puede borrar también términos creados por el importador (categorías/tags/tipos).
- Opcionalmente puede borrar también **adjuntos (imágenes)** creados por el importador.
- El borrado es **permanente** (sin papelera) para permitir reimportar sin conflictos de slug.

#### Actualizar vídeos (sin tocar contenido)
Si necesitas actualizar únicamente el **título** de artículos de `centro_de_ayuda` y guardar una **URL de vídeo**, sin modificar nunca el `post_content`:

- En WP Admin ve a: **Herramientas → Importador SQL**
- Baja a: **Actualizar URL de vídeo (centro de ayuda)**
- Configura:
  - **Fichero SQL**
  - **Idioma**
  - **URL de vídeo a localizar**
  - (Opcional) **Dry run**
- Ejecuta **Actualizar vídeos (solo título + meta)**.

La URL se guarda en el meta: `bilky_video_url`.

#### Opción B — Con WP‑CLI (si existe en el servidor)
- **Dry run (no escribe cambios)**:

```bash
wp bilky import-sql --dry-run
```

- **Importar solo artículos (centro de ayuda)**:

```bash
wp bilky import-sql --only=articles
```

- **Importar artículos con media base URL/DIR**:

```bash
wp bilky import-sql --only=articles --media-base-url="https://TU_DOMINIO/RUTA/IMAGENES" --media-base-dir="/ruta/en/disco"
```

> Nota: si no se configura media, el importador puede dejar marcadores HTML `<!-- bilky-import-step-image: ... -->` como trazabilidad.

