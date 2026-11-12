<?php
/** @var array $bookings */
/** @var string $now */
?>
<h1>Mes billets</h1>

<?php if (!$bookings) : ?>
  <div class="empty">
    <p>Vous n'avez pas encore de billet.</p>
    <a class="btn" href="<?= u('home') ?>">Rechercher un trajet</a>
  </div>
<?php endif; ?>

<?php foreach ($bookings as $b) :
    $past = $b['departure_at'] < $now;
    ?>
  <a class="trip trip-link" href="<?= u('ticket', ['ref' => $b['reference']]) ?>">
    <div class="trip-info">
      <strong><?= e($b['origin']) ?> → <?= e($b['destination']) ?></strong>
      <div class="muted"><?= e(format_datetime($b['departure_at'])) ?> · <?= (int) $b['passengers_count'] ?> passager(s)</div>
    </div>
    <div class="trip-price">
      <span class="muted"><?= e($b['reference']) ?></span>
      <?php if ($b['status'] === 'cancelled') : ?>
        <span class="badge badge-off">Annulé</span>
      <?php elseif ($past) : ?>
        <span class="badge badge-off">Voyage effectué</span>
      <?php else : ?>
        <span class="badge badge-ok">Valide</span>
      <?php endif; ?>
    </div>
  </a>
<?php endforeach; ?>
