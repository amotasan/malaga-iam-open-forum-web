<?php
require __DIR__ . '/config.php';
lunch_headers();

$errors = [];
$result = null;
$val = ['first' => '', 'last' => '', 'starter' => '', 'main' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['website'])) {               // trampa anti-bots
        $result = 'new';
    } elseif (lunch_is_closed()) {
        $result = 'closed';
    } else {
        foreach ($val as $k => $_) { $val[$k] = trim((string) ($_POST[$k] ?? '')); }
        if ($val['first'] === '' || mb_strlen($val['first']) > 60)  { $errors['first'] = 'Indica tu nombre · Please enter your first name'; }
        if ($val['last'] === ''  || mb_strlen($val['last']) > 80)   { $errors['last']  = 'Indica tu apellido · Please enter your last name'; }
        if (!isset(STARTERS[$val['starter']])) { $errors['starter'] = 'Elige un primer plato · Choose a starter'; }
        if (!isset(MAINS[$val['main']]))       { $errors['main']    = 'Elige un segundo plato · Choose a main course'; }
        if (!$errors) {
            try {
                $result = lunch_save($val['first'], $val['last'], $val['starter'], $val['main']);
            } catch (Throwable $e) {
                error_log('iab-lunch: ' . $e->getMessage());
                $result = 'error';
            }
        }
    }
}

lunch_head('Elige tu menú · Choose your meal — VERTI-GO IAB');

function dish_options(string $name, array $dishes, string $selected): void {
    foreach ($dishes as $id => [$es, $en]) { ?>
      <label class="dish">
        <input type="radio" name="<?= $name ?>" value="<?= h($id) ?>" <?= $selected === $id ? 'checked' : '' ?> required>
        <span class="dish-card"><strong><?= h($es) ?></strong><em><?= h($en) ?></em></span>
      </label>
<?php }
}
?>
<main class="lunch">
  <section class="lunch-hero">
    <div class="wrap">
      <span class="tag">VERTI-GO · Institutional Advisory Board</span>
      <h1>Elige tu menú <span>Choose your meal</span></h1>
      <p class="lunch-meta"><?= h(LUNCH_DATE_ES) ?> · <?= h(LUNCH_DATE_EN) ?><br><?= h(RESTAURANT) ?></p>
    </div>
  </section>

  <div class="wrap lunch-body">
<?php if ($result === 'new' || $result === 'updated'): ?>
    <div class="lunch-card lunch-done" role="status">
      <h2><?= $result === 'updated' ? '¡Elección actualizada!' : '¡Gracias!' ?> <span><?= $result === 'updated' ? 'Choice updated!' : 'Thank you!' ?></span></h2>
      <?php if (isset(STARTERS[$val['starter']], MAINS[$val['main']])): ?>
      <dl class="lunch-summary">
        <dt>Primero · Starter</dt><dd><?= h(STARTERS[$val['starter']][0]) ?> <em><?= h(STARTERS[$val['starter']][1]) ?></em></dd>
        <dt>Segundo · Main</dt><dd><?= h(MAINS[$val['main']][0]) ?> <em><?= h(MAINS[$val['main']][1]) ?></em></dd>
      </dl>
      <?php endif; ?>
      <p>Si cambias de opinión, vuelve a enviar el formulario con el mismo nombre y apellido.<br><em>If you change your mind, just submit again with the same name.</em></p>
      <div class="btns">
        <a class="btn btn-line" href="./">Cambiar · Change</a>
        <a class="btn btn-sun" href="../">Conoce el Málaga IAM Open Forum →</a>
      </div>
    </div>
<?php elseif ($result === 'closed' || ($result === null && lunch_is_closed())): ?>
    <div class="lunch-card" role="status">
      <h2>Elección cerrada <span>Choices are closed</span></h2>
      <p>Ya hemos pasado el pedido al restaurante. Si necesitas algo, habla con la organización.<br><em>The order has been sent to the restaurant. Please speak to the organisers if you need anything.</em></p>
    </div>
<?php else: ?>
    <?php if ($result === 'full' || $result === 'error'): ?>
    <p class="lunch-alert" role="alert">No hemos podido guardar tu elección. Inténtalo de nuevo o díselo a la organización.<br><em>We couldn't save your choice. Please try again or tell the organisers.</em></p>
    <?php endif; ?>
    <form class="lunch-card" method="post" action="./" novalidate>
      <fieldset class="lunch-name">
        <legend>Tus datos · Your details</legend>
        <label>Nombre · First name
          <input type="text" name="first" maxlength="60" autocomplete="given-name" value="<?= h($val['first']) ?>" required <?= isset($errors['first']) ? 'aria-invalid="true"' : '' ?>>
          <?php if (isset($errors['first'])): ?><small class="err"><?= h($errors['first']) ?></small><?php endif; ?>
        </label>
        <label>Apellido · Last name
          <input type="text" name="last" maxlength="80" autocomplete="family-name" value="<?= h($val['last']) ?>" required <?= isset($errors['last']) ? 'aria-invalid="true"' : '' ?>>
          <?php if (isset($errors['last'])): ?><small class="err"><?= h($errors['last']) ?></small><?php endif; ?>
        </label>
      </fieldset>

      <fieldset class="lunch-dishes">
        <legend><span class="eyebrow">Primero</span> Starter</legend>
        <?php if (isset($errors['starter'])): ?><small class="err"><?= h($errors['starter']) ?></small><?php endif; ?>
        <?php dish_options('starter', STARTERS, $val['starter']); ?>
      </fieldset>

      <fieldset class="lunch-dishes">
        <legend><span class="eyebrow">Segundo</span> Main course</legend>
        <?php if (isset($errors['main'])): ?><small class="err"><?= h($errors['main']) ?></small><?php endif; ?>
        <?php dish_options('main', MAINS, $val['main']); ?>
      </fieldset>

      <p class="lunch-incl">Incluye · Included: <?= h(INCLUDED[0]) ?> <em>(<?= h(INCLUDED[1]) ?>)</em></p>

      <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

      <button class="btn btn-sun lunch-submit" type="submit">Enviar · Submit</button>
      <p class="lunch-privacy">Tu nombre solo se usa para que nadie cuente dos veces; no se guarda en claro y al restaurante solo le llega el total de cada plato.<br><em>Your name is only used to avoid counting anyone twice; it isn't stored in readable form and the restaurant only receives the total for each dish.</em></p>
    </form>
<?php endif; ?>
  </div>
</main>
<?php lunch_foot();
