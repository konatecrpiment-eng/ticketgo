<?php
/** @var ?string $error */
/** @var string $email */
?>
<h1>Connexion</h1>
<?php if ($error) : ?>
  <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>
<form method="post" class="form narrow">
  <?= csrf_field() ?>
  <label>Adresse e-mail
    <input type="email" name="email" value="<?= e($email) ?>" autocomplete="email" required>
  </label>
  <label>Mot de passe
    <input type="password" name="password" autocomplete="current-password" required>
  </label>
  <button class="btn" type="submit">Se connecter</button>
  <p class="muted">Pas encore de compte ? <a href="<?= u('register') ?>">Créer un compte</a></p>
  <p class="muted small">Démo : demo@ticketgo.test / demo1234</p>
</form>
