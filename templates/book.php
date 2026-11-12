<?php
/** @var array $trip */
/** @var string[] $names */
/** @var string $method */
/** @var string $phone */
/** @var ?string $error */
?>
<h1>Réserver votre voyage</h1>

<div class="summary">
  <strong><?= e($trip['origin']) ?> → <?= e($trip['destination']) ?></strong>
  <span><?= e(format_datetime($trip['departure_at'])) ?> · <?= e($trip['company']) ?> (<?= e($trip['bus_type']) ?>)</span>
  <span><?= e(money((int) $trip['price'])) ?> par passager · <?= (int) $trip['seats_left'] ?> place(s) restante(s)</span>
</div>

<?php if ($error) : ?>
  <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<form method="post" class="form" id="booking-form"
      data-price="<?= (int) $trip['price'] ?>" data-max="<?= min(5, (int) $trip['seats_left']) ?>">
  <?= csrf_field() ?>

  <fieldset>
    <legend>1. Passagers</legend>
    <div id="passengers">
      <?php foreach ($names as $i => $n) : ?>
        <label>Passager <?= $i + 1 ?>
          <input type="text" name="passengers[]" value="<?= e($n) ?>" minlength="2" maxlength="80" required>
        </label>
      <?php endforeach; ?>
    </div>
    <button type="button" class="btn btn-outline btn-small" id="add-passenger">+ Ajouter un passager</button>
  </fieldset>

  <fieldset>
    <legend>2. Paiement Mobile Money</legend>
    <div class="radio-row">
      <?php foreach (payment_methods() as $key => $label) : ?>
        <label class="radio">
          <input type="radio" name="method" value="<?= e($key) ?>" <?= $method === $key ? 'checked' : '' ?> required>
          <?= e($label) ?>
        </label>
      <?php endforeach; ?>
    </div>
    <label>Numéro à débiter
      <input type="tel" name="phone" value="<?= e($phone) ?>" placeholder="0700000000" inputmode="tel" required>
    </label>
  </fieldset>

  <div class="total">Total : <strong id="total"><?= e(money((int) $trip['price'] * count($names))) ?></strong></div>
  <button class="btn" type="submit">Payer et obtenir mon billet</button>
  <p class="muted small">Paiement simulé : aucun argent réel n'est débité.</p>
</form>
