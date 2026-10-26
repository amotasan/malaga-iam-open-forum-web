# Málaga IAM Open Forum · Web

Web del **Málaga IAM Open Forum**, foro abierto de movilidad aérea innovadora. Es una iniciativa de AEROSOLUTIONS, paralela al proyecto europeo VERTI-GO (Horizonte Europa · SESAR 3), para promover la aceptación pública del proyecto. El foro no forma parte de VERTI-GO.

## Estructura

- `index.html`: página de inicio (landing).
- `blog/index.html`: blog del foro, en página aparte (`https://malaga-iam.website/blog/`). Cada entrada es un `<article class="entry">` con su propio `id`, que sirve como enlace directo (`/blog/#id`).
- `actividades/index.html`: página de actividades (`https://malaga-iam.website/actividades/`), con el programa completo y la ficha de cada encuentro. Cada encuentro es un `<article class="act-detail">` con su `id` (`/actividades/#evento-1`). En la portada solo queda un resumen de cada uno (`.act-card`) que enlaza aquí.
- `verti-go/index.html`: página del proyecto VERTI-GO (`https://malaga-iam.website/verti-go/`), con el contenido del folleto adaptado a web: ficha, resumen, objetivos, soluciones, hoja de ruta, impacto, consorcio y glosario. Se enlaza desde «VERTI-GO» en el menú y desde el botón «Conoce el proyecto VERTI-GO» del bloque VERTI-GO de la portada. Lleva también el bloque de financiación UE.
- `css/site.css`: estilos compartidos por todas las páginas.
- `docs/`: documentos descargables (folleto de VERTI-GO en PDF, ES y EN).
- `img/`: logos e imágenes.
- `js/main.js`: script común (menú móvil, enlaces a LinkedIn, animación del logo). Sin scripts en línea, para poder usar una CSP estricta.
- `.htaccess`: cabeceras de seguridad (HSTS, CSP, X-Frame-Options…) y bloqueo de archivos ocultos y de documentación.

## Enlace a LinkedIn

La URL de la página de LinkedIn está en `js/main.js` (`LINKEDIN_URL`), compartida por todas las páginas.

## Seguridad

La política de contenidos (CSP) del `.htaccess` solo permite scripts propios (`js/`) y Google Fonts. Si se añade un script en línea o un recurso de otro dominio (vídeo, mapa, analítica…), hay que añadirlo también a la CSP o no se cargará.

## Publicar en https://malaga-iam.website

La web se despliega con la extensión **Git de Plesk**: el servidor descarga este repositorio público en `httpdocs`. Un webhook de GitHub avisa a Plesk en cada push a `main`.


## Financiación de la UE

La financiación de la UE solo cubre el proyecto VERTI-GO, no el foro. Por eso los logos de la UE y de SESAR y el texto de financiación van únicamente en el bloque VERTI-GO de la portada (`index.html`, debajo de la hoja de ruta) y al final de la página `verti-go/`, y no en el pie de las páginas. El texto es este:

> El proyecto VERTI-GO ha recibido financiación de la Empresa Común SESAR 3 en virtud del Acuerdo de Subvención n.º 101287356, en el marco del programa de investigación e innovación Horizonte Europa de la Unión Europea.
