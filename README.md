# Málaga IAM Open Forum · Web

Web del **Málaga IAM Open Forum**, foro abierto de movilidad aérea innovadora vinculado al proyecto europeo VERTI-GO (Horizonte Europa · SESAR 3).

## Estructura

- `index.html`: página completa (HTML, CSS y JS en un solo archivo).
- `img/`: logos e imágenes.

## Enlaces pendientes

Al final de `index.html`, rellena:

```js
const INSCRIPCION_URL = "";   // formulario de inscripción
const LINKEDIN_URL = "";      // página de LinkedIn del foro
```

## Publicar en https://malaga-iam.website

Cada push a `main` que cambie `index.html` o `img/` copia la web al servidor Plesk con GitHub Actions (`.github/workflows/deploy.yml`). También se puede lanzar a mano desde la pestaña **Actions → Deploy a Plesk → Run workflow**.

Secretos necesarios en **Settings → Secrets and variables → Actions**:

| Secreto | Valor |
|---|---|
| `PLESK_HOST` | servidor Plesk (IP o nombre) |
| `PLESK_USER` | usuario SSH del dominio |
| `PLESK_SSH_KEY` | clave privada SSH (la pública debe estar en `~/.ssh/authorized_keys` del servidor) |
| `PLESK_PORT` | puerto SSH (opcional, 22 por defecto) |
| `PLESK_TARGET_DIR` | carpeta raíz del sitio, p. ej. `/var/www/vhosts/malaga-iam.website/httpdocs` |

---

Este proyecto ha recibido financiación de la Empresa Común SESAR 3 en virtud del Acuerdo de Subvención n.º 101287356, en el marco del programa de investigación e innovación Horizonte Europa de la Unión Europea.
