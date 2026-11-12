<?php
/** @var string $from */
/** @var string $to */
/** @var string $date */
/** @var string[] $errors */
/** @var array $trips */
?>
<h1>Trajets <?= e($from) ?> → <?= e($to) ?></h1>
<p class="muted"><?= e(date('d/m/Y', strtotime($date))) ?> · <a href="<?= u('home') ?>">Modifier la recherche</a></p>

<?php foreach ($errors as $err) : ?>
  <div class="alert alert-error"><?= e($err) ?></div>
<?php endforeach; ?>

<?php if (!$errors && !$trips) : ?>
  <div class="empty">
    <p>Aucun départ disponible ce jour-là pour ce trajet.</p>
    <a class="btn" href="<?= u('search', ['from' => $from, 'to' => $to, 'date' => date('Y-m-d', strtotime($date . ' +1 day'))]) ?>">Voir le lendemain</a>
  </div>
<?php endif; ?>

<?php foreach ($trips as $t) : ?>
  <article class="trip">
    <div class="trip-times">
      <strong><?= e(format_time($t['departure_at'])) ?></strong>
      <span>→</span>
      <strong><?= e(format_time($t['arrival_at'])) ?></strong>
    </div>
    <div class="trip-info">
      <div><?= e($t['company']) ?> <span class="badge"><?= e($t['bus_type']) ?></span></div>
      <div class="muted">
        <?= (int) $t['seats_left'] > 0 ? (int) $t['seats_left'] . ' place(s) restante(s)' : 'Complet' ?>
      </div>
    </div>
    <div class="trip-price">
      <strong><?= e(money((int) $t['price'])) ?></strong>
      <?php if ((int) $t['seats_left'] > 0) : ?>
        <a class="btn btn-small" href="<?= u('book', ['trip' => $t['id']]) ?>">Réserver</a>
      <?php else : ?>
        <span class="badge badge-off">Complet</span>
      <?php endif; ?>
    </div>
  </article>
<?php endforeach; ?>
