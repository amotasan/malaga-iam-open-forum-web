<?php
/*
 * Elección de menú · comida del Institutional Advisory Board (IAB) VERTI-GO
 * Página oculta: no está enlazada desde la web y lleva noindex.
 * Se llega con el QR del PDF del menú: https://malaga-iam.website/iab-lunch/
 * Totales para el restaurante:          https://malaga-iam.website/iab-lunch/totales.php
 *
 * Solo interesa el total de cada plato. El nombre se pide únicamente para que una
 * misma persona no cuente dos veces: se guarda como huella (hash), nunca en claro.
 */

if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === 'config.php') { http_response_code(404); exit; }

const LUNCH_DATE_ES = 'Jueves, 22 de octubre de 2026';
const LUNCH_DATE_EN = 'Thursday, 22 October 2026';
const RESTAURANT    = 'Zinkinn · casual food';

// Hora de cierre del formulario (hora de Madrid). null = siempre abierto.
const CLOSES_AT = null; // p. ej. '2026-10-22 12:00'

const STARTERS = [
    'ensalada' => ['Ensalada variada', 'Mixed salad'],
    'callos'   => ['Callos marineros', 'Seafood stew in spicy paprika sauce (callos-style)'],
    'pesto'    => ['Pasta al pesto', 'Pasta with pesto'],
];
const MAINS = [
    'pezespada' => ['Pez espada a la riojana', 'Rioja-style swordfish (peppers, tomato & onion)'],
    'secreto'   => ['Secreto al wok', 'Wok-fried Iberian pork (secreto)'],
    'tricolor'  => ['Tricolor de verduras', 'Three-colour vegetable medley'],
];
const INCLUDED = ['Postre o café · agua, cerveza o refresco', 'Dessert or coffee · water, beer or soft drink'];

const MAX_ENTRIES = 300;
const SALT = 'verti-go-iab-2026-lunch';

date_default_timezone_set('Europe/Madrid');

function lunch_is_closed(): bool {
    return CLOSES_AT !== null && time() >= strtotime(CLOSES_AT);
}

/*
 * Las respuestas se guardan fuera de httpdocs (/var/www/vhosts/<dominio>/iab-lunch-data/),
 * fuera del alcance de la web y del despliegue de Plesk Git. Si no se puede, se usa
 * iab-lunch/data/ con un fichero .php que corta la ejecución si alguien lo pide por web.
 */
function lunch_data_file(): string {
    $candidates = [];
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $candidates[] = dirname(rtrim($_SERVER['DOCUMENT_ROOT'], '/')) . '/iab-lunch-data';
    }
    $candidates[] = __DIR__ . '/data';
    foreach ($candidates as $dir) {
        if (!is_dir($dir)) { @mkdir($dir, 0750, true); }
        if (is_dir($dir) && is_writable($dir)) { return $dir . '/responses.php'; }
    }
    throw new RuntimeException('No hay carpeta escribible para guardar las respuestas.');
}

const GUARD = "<?php http_response_code(404); exit; ?>\n";

/** Devuelve [huella => ['s' => primero, 'm' => segundo]] */
function lunch_read_all($fh = null): array {
    if ($fh === null) {
        $file = lunch_data_file();
        if (!is_file($file)) { return []; }
        $raw = (string) file_get_contents($file);
    } else {
        rewind($fh);
        $raw = (string) stream_get_contents($fh);
    }
    $data = json_decode(substr($raw, strlen(GUARD)) ?: '{}', true);
    return is_array($data) ? $data : [];
}

/** Guarda o actualiza la elección. Devuelve 'new' | 'updated' | 'full'. */
function lunch_save(string $first, string $last, string $starter, string $main): string {
    $fh = fopen(lunch_data_file(), 'c+');
    if (!$fh || !flock($fh, LOCK_EX)) { throw new RuntimeException('No se pudo abrir el fichero de respuestas.'); }
    try {
        $all = lunch_read_all($fh);
        $key = lunch_key($first, $last);
        $existed = isset($all[$key]);
        if (!$existed && count($all) >= MAX_ENTRIES) { return 'full'; }
        $all[$key] = ['s' => $starter, 'm' => $main];
        ftruncate($fh, 0);
        rewind($fh);
        fwrite($fh, GUARD . json_encode($all));
        fflush($fh);
        return $existed ? 'updated' : 'new';
    } finally {
        flock($fh, LOCK_UN);
        fclose($fh);
    }
}

/** Huella del nombre: sirve para detectar repetidos sin guardar el nombre. */
function lunch_key(string $first, string $last): string {
    $s = mb_strtolower(trim($first) . ' ' . trim($last), 'UTF-8');
    $s = strtr($s, ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n','à'=>'a','è'=>'e','ò'=>'o','ç'=>'c']);
    $s = preg_replace('/\s+/', ' ', $s);
    return substr(hash('sha256', SALT . '|' . $s), 0, 24);
}

/** Totales por plato. */
function lunch_totals(): array {
    $all = lunch_read_all();
    $s = array_fill_keys(array_keys(STARTERS), 0);
    $m = array_fill_keys(array_keys(MAINS), 0);
    foreach ($all as $e) {
        if (isset($s[$e['s'] ?? ''])) { $s[$e['s']]++; }
        if (isset($m[$e['m'] ?? ''])) { $m[$e['m']]++; }
    }
    return ['people' => count($all), 'starters' => $s, 'mains' => $m];
}

function h(?string $s): string { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }

function lunch_headers(): void {
    header('X-Robots-Tag: noindex, nofollow');
    header('Cache-Control: no-store');
    header('Referrer-Policy: same-origin');
    header('X-Content-Type-Options: nosniff');
}

function lunch_head(string $title, string $root = '../'): void { ?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="robots" content="noindex, nofollow">
<link rel="icon" href="<?= $root ?>img/logo.svg" type="image/svg+xml">
<title><?= h($title) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,400&display=swap">
<link rel="stylesheet" href="<?= $root ?>css/site.css">
<link rel="stylesheet" href="lunch.css">
</head>
<body>
<header class="top">
  <div class="wrap">
    <a class="brand" href="<?= $root ?>" aria-label="Málaga IAM Open Forum"><img src="<?= $root ?>img/logo-h.svg" alt="Málaga IAM Forum"></a>
    <a class="lunch-site" href="<?= $root ?>">Visita la web del foro · Visit the forum site →</a>
  </div>
</header>
<?php }

function lunch_foot(string $root = '../'): void { ?>
<footer>
  <div class="wrap">
    <div class="funding">
      <div class="logos"><img src="<?= $root ?>img/eu.jpg" alt="Cofinanciado por la Unión Europea"><img src="<?= $root ?>img/sesar.png" alt="SESAR Joint Undertaking"></div>
      <p>Este proyecto ha recibido financiación de la Empresa Común SESAR 3 en virtud del Acuerdo de Subvención n.º 101287356, en el marco del programa de investigación e innovación Horizonte Europa de la Unión Europea.</p>
    </div>
    <p class="copy">© 2026 Málaga IAM Open Forum · Proyecto VERTI-GO</p>
  </div>
</footer>
</body>
</html>
<?php }
