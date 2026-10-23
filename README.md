# Málaga IAM Open Forum · Web

Web del **Málaga IAM Open Forum**, foro abierto de movilidad aérea innovadora vinculado al proyecto europeo VERTI-GO (Horizonte Europa · SESAR 3).

## Estructura

- `index.html`: página de inicio (landing).
- `blog/index.html`: blog del foro, en página aparte (`https://malaga-iam.website/blog/`). Cada entrada es un `<article class="entry">` con su propio `id`, que sirve como enlace directo (`/blog/#id`).
- `css/site.css`: estilos compartidos por las dos páginas.
- `docs/`: documentos descargables (folleto de VERTI-GO en PDF, ES y EN).
- `img/`: logos e imágenes.
- `js/main.js`: script común (menú móvil, enlaces a LinkedIn, animación del logo). Sin scripts en línea, para poder usar una CSP estricta.
- `.htaccess`: cabeceras de seguridad (HSTS, CSP, X-Frame-Options…) y bloqueo de archivos ocultos y de documentación.

## Enlace a LinkedIn

La URL de la página de LinkedIn está en `js/main.js` (`LINKEDIN_URL`), compartida por la portada y el blog.

## Seguridad

La política de contenidos (CSP) del `.htaccess` solo permite scripts propios (`js/`) y Google Fonts. Si se añade un script en línea o un recurso de otro dominio (vídeo, mapa, analítica…), hay que añadirlo también a la CSP o no se cargará.

## Publicar en https://malaga-iam.website

La web se despliega con la extensión **Git de Plesk**: el servidor descarga este repositorio público en `httpdocs`. Un webhook de GitHub avisa a Plesk en cada push a `main`.


---

Este proyecto ha recibido financiación de la Empresa Común SESAR 3 en virtud del Acuerdo de Subvención n.º 101287356, en el marco del programa de investigación e innovación Horizonte Europa de la Unión Europea.
