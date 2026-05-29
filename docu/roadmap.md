# Roadmap

## Fase 1 — Base técnica del importador (COMPLETADA)
- **CPT Partners**: crear `partners` + taxonomía `partner_tipo`.
- **Campos**:
  - `clientes`: añadir campo `cliente_order` (ACF) para “Orden”.
  - `partners`: añadir campos `partner_url`, `partner_order`, `partner_priority` (ACF).
- **Importador**:
  - Implementar importador que lea el dump SQL y haga upsert (idempotente) de:
    - `posts` -> `post`
    - `clients` -> `clientes`
    - `partners` -> `partners`
    - `articles` -> `centro_de_ayuda` (con `categories` -> `category` y `article_translations` como secciones)
  - **Ejecución sin WP-CLI**: página en WP Admin (`Herramientas -> Importador SQL`) para lanzar el importador desde el navegador.
  - **Rollback**: marcar contenido importado y añadir botón para **borrar en bloque** lo importado desde la misma pantalla.

## Fase 2 — Ejecución en entorno y validación (PENDIENTE)
- Subir el dump por FTP a la ruta del tema (o ajustar la ruta en el formulario).
- Ejecutar primero en **modo dry-run** y revisar conteos.
- Ejecutar importación real por secciones (posts/clients/partners/articles) en staging.
- Si algo falla, usar **Borrar contenido importado** para resetear y reintentar.
- Validar:
  - Slugs y fechas de `post` y `centro_de_ayuda`.
  - Asignación de categorías en `centro_de_ayuda`.
  - Tags en `post`.
  - Revisión manual de ~78 artículos (contenido y estructura).

## Fase 3 — Medios (COMPLETADA)
- **Origen de imágenes**: soportar:
  - **Media base DIR** (ruta en disco para ficheros subidos por FTP).
  - **Media base URL** (descarga por HTTP si no hay DIR o el fichero no está en DIR).
- **Adjuntos**:
  - Importar imágenes a la librería de medios como **adjuntos**.
  - Asignar la primera imagen como **imagen destacada**.
  - Guardar el resto en el custom field `bilky_import_additional_images` (IDs de adjunto).
- **Rollback**:
  - Opción para borrar también **adjuntos (imágenes)** creados por el importador.

## Fase 4 — Endurecimiento y SEO (PENDIENTE)
- Añadir logs más detallados y modos de “solo actualizar” / “solo crear”.
- Revisar redirecciones si hubiera cambios de rutas históricas.
- Checklist SEO final (indexación, duplicados, canónicos, etc.).

