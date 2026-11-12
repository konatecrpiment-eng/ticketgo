<?php
/** @var string $title */
/** @var string $content */
$user = current_user();
$flash = flash();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
  <div class="container nav">
    <a class="brand" href="<?= u('home') ?>">Ticket<span>Go</span></a>
    <nav>
      <a href="<?= u('home') ?>">Rechercher</a>
      <?php if ($user) : ?>
        <a href="<?= u('tickets') ?>">Mes billets</a>
        <form method="post" action="<?= u('logout') ?>" class="inline">
          <?= csrf_field() ?>
          <button type="submit" class="link-btn">Déconnexion (<?= e(explode(' ', $user['full_name'])[0]) ?>)</button>
        </form>
      <?php else : ?>
        <a href="<?= u('login') ?>">Connexion</a>
        <a class="btn btn-small" href="<?= u('register') ?>">Créer un compte</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="container">
  <?php if ($flash) : ?>
    <div class="alert alert-<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div>
  <?php endif; ?>
  <?= $content ?>
</main>

<footer class="site-footer">
  <div class="container">TicketGo — projet pédagogique MIAGE 2. Les paiements Mobile Money sont simulés.</div>
</footer>
<script src="assets/js/app.js"></script>
</body>
</html>
