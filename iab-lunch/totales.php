<?php
require __DIR__ . '/config.php';
lunch_headers();
header('Refresh: 30');

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
    </div>
  </div>
</main>
<?php lunch_foot();
