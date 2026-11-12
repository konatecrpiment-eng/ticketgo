<?php
/** @var string[] $errors */
/** @var array $old */
?>
<h1>Créer un compte</h1>
<?php foreach ($errors as $err) : ?>
  <div class="alert alert-error"><?= e($err) ?></div>
<?php endforeach; ?>
<form method="post" class="form narrow">
  <?= csrf_field() ?>
  <label>Nom complet
    <input type="text" name="name" value="<?= e($old['name']) ?>" autocomplete="name" required>
  </label>
  <label>Adresse e-mail
    <input type="email" name="email" value="<?= e($old['email']) ?>" autocomplete="email" required>
  </label>
  <label>Téléphone (Mobile Money)
    <input type="tel" name="phone" value="<?= e($old['phone']) ?>" autocomplete="tel" placeholder="0700000000">
  </label>
  <label>Mot de passe (8 caractères minimum)
    <input type="password" name="password" minlength="8" autocomplete="new-password" required>
  </label>
  <button class="btn" type="submit">Créer mon compte</button>
  <p class="muted">Déjà inscrit ? <a href="<?= u('login') ?>">Se connecter</a></p>
</form>
