<?php
/** @var array $booking */
/** @var bool $cancellable */
/** @var int $limit */
$cancelled = $booking['status'] === 'cancelled';
?>
<h1>Votre billet</h1>

<article class="ticket <?= $cancelled ? 'ticket-cancelled' : '' ?>">
  <header>
    <span>Ticket<strong>Go</strong></span>
    <span class="badge <?= $cancelled ? 'badge-off' : 'badge-ok' ?>"><?= $cancelled ? 'ANNULÉ' : 'VALIDE' ?></span>
  </header>

  <div class="ticket-route">
    <div><small>Départ</small><strong><?= e($booking['origin']) ?></strong><span><?= e(format_datetime($booking['departure_at'])) ?></span></div>
    <div class="arrow">→</div>
    <div><small>Arrivée</small><strong><?= e($booking['destination']) ?></strong><span><?= e(format_datetime($booking['arrival_at'])) ?></span></div>
  </div>

  <p class="muted"><?= e($booking['company']) ?> · <?= e($booking['bus_type']) ?></p>

  <table class="table">
    <thead><tr><th>Passager</th><th>Siège</th></tr></thead>
    <tbody>
      <?php foreach ($booking['passengers'] as $p) : ?>
        <tr><td><?= e($p['full_name']) ?></td><td><?= (int) $p['seat_number'] ?></td></tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="ticket-code">
    <small>Code à présenter au chauffeur</small>
    <strong><?= e($booking['reference']) ?></strong>
  </div>
  <p class="muted">Payé : <?= e(money((int) $booking['total'])) ?> via <?= e(payment_methods()[$booking['payment_method']] ?? $booking['payment_method']) ?></p>
</article>

<div class="actions no-print">
  <button class="btn" type="button" onclick="window.print()">Imprimer / enregistrer en PDF</button>
  <a class="btn btn-outline" href="<?= u('tickets') ?>">Mes billets</a>
  <?php if ($cancellable) : ?>
    <form method="post" action="<?= u('cancel') ?>" class="inline" onsubmit="return confirm('Annuler ce billet ?');">
      <?= csrf_field() ?>
      <input type="hidden" name="ref" value="<?= e($booking['reference']) ?>">
      <button class="btn btn-danger" type="submit">Annuler le billet</button>
    </form>
  <?php elseif (!$cancelled) : ?>
    <span class="muted small">Annulation impossible moins de <?= (int) $limit ?> h avant le départ.</span>
  <?php endif; ?>
</div>
