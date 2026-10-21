<?php
require __DIR__ . '/config.php';
lunch_headers();

$resetMsg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    lunch_reset();
    header('Location: totales.php?vaciado=1', true, 303);
    exit;
}
if (isset($_GET['vaciado'])) { $resetMsg = 'Respuestas borradas: los totales vuelven a 0.'; }
if ($_SERVER['REQUEST_METHOD'] === 'GET') { header('Refresh: 30; url=totales.php'); }

$t = lunch_totals();

$text = "Pedido comida " . LUNCH_DATE_ES . " — " . $t['people'] . " personas\n\nPRIMEROS\n";
foreach (STARTERS as $id => [$es]) { $text .= "  " . $t['starters'][$id] . " × " . $es . "\n"; }
$text .= "\nSEGUNDOS\n";
foreach (MAINS as $id => [$es]) { $text .= "  " . $t['mains'][$id] . " × " . $es . "\n"; }

lunch_head('Totales · comida IAB VERTI-GO');
?>
<main class="lunch">
  <section class="lunch-hero">
    <div class="wrap">
      <span class="tag">VERTI-GO · IAB · Pedido para el restaurante</span>
      <h1>Totales <span><?= h(LUNCH_DATE_ES) ?></span></h1>
      <p class="lunch-meta"><?= h(RESTAURANT) ?> · se actualiza cada 30 s</p>
    </div>
  </section>

  <div class="wrap lunch-body">
    <?php if ($resetMsg): ?><p class="lunch-alert" role="status"><?= h($resetMsg) ?></p><?php endif; ?>
    <div class="lunch-card">
      <p class="lunch-people"><b class="num"><?= (int) $t['people'] ?></b> personas han elegido</p>

      <h2 class="lunch-h">Primeros</h2>
      <table class="lunch-totals">
        <?php foreach (STARTERS as $id => [$es, $en]): ?>
        <tr><td><?= h($es) ?></td><td class="num"><?= (int) $t['starters'][$id] ?></td></tr>
        <?php endforeach; ?>
      </table>

      <h2 class="lunch-h">Segundos</h2>
      <table class="lunch-totals">
        <?php foreach (MAINS as $id => [$es, $en]): ?>
        <tr><td><?= h($es) ?></td><td class="num"><?= (int) $t['mains'][$id] ?></td></tr>
        <?php endforeach; ?>
      </table>

      <h2 class="lunch-h">Texto para el restaurante</h2>
      <textarea class="lunch-copy" id="copy" rows="13" readonly><?= h($text) ?></textarea>
      <button class="btn btn-line" type="button" onclick="navigator.clipboard.writeText(document.getElementById('copy').value).then(()=>{this.textContent='Copiado ✓'})">Copiar</button>

      <details class="lunch-reset">
        <summary>Vaciar respuestas</summary>
        <form method="post" action="totales.php" onsubmit="return confirm('¿Borrar todas las respuestas? Los totales volverán a 0.')">
          <button class="btn btn-line" type="submit" name="reset" value="1">Borrar todo</button>
        </form>
      </details>
    </div>
  </div>
</main>
<?php lunch_foot();
