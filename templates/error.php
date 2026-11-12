<?php
/** @var string $message */
?>
<div class="empty">
  <h1>Oups, une erreur est survenue</h1>
  <p><?= e($message) ?></p>
  <a class="btn" href="<?= u('home') ?>">Retour à l'accueil</a>
</div>
