<?php
/** @var array $popular */
?>
<section class="hero">
  <h1>Votre billet de bus, depuis votre canapé.</h1>
  <p>Réservez, payez par Mobile Money et recevez votre billet sans vous déplacer à la gare.</p>

  <form class="search-form" method="get" action="index.php">
    <input type="hidden" name="page" value="search">
    <label>Départ
      <select name="from" required>
        <option value="">Choisir…</option>
        <?php foreach (cities() as $c) : ?>
          <option value="<?= e($c) ?>" <?= $c === 'Abidjan' ? 'selected' : '' ?>><?= e($c) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Arrivée
      <select name="to" required>
        <option value="">Choisir…</option>
        <?php foreach (cities() as $c) : ?>
          <option value="<?= e($c) ?>"><?= e($c) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Date
      <input type="date" name="date" value="<?= e(date('Y-m-d')) ?>" min="<?= e(date('Y-m-d')) ?>" required>
    </label>
    <button class="btn" type="submit">Rechercher</button>
  </form>
</section>

<section class="steps">
  <h2>Comment ça marche ?</h2>
  <ol class="step-list">
    <li><strong>1. Cherchez</strong><span>Choisissez vos villes et votre date.</span></li>
    <li><strong>2. Réservez</strong><span>Indiquez les passagers, les places sont attribuées automatiquement.</span></li>
    <li><strong>3. Payez</strong><span>Orange Money, MTN MoMo, Moov Money ou Wave.</span></li>
    <li><strong>4. Voyagez</strong><span>Présentez le code de votre billet au chauffeur.</span></li>
  </ol>
</section>

<?php if ($popular) : ?>
<section>
  <h2>Destinations populaires depuis Abidjan</h2>
  <div class="cards">
    <?php foreach ($popular as $p) : ?>
      <a class="card" href="<?= u('search', ['from' => $p['origin'], 'to' => $p['destination'], 'date' => date('Y-m-d')]) ?>">
        <strong><?= e($p['origin']) ?> → <?= e($p['destination']) ?></strong>
        <span>à partir de <?= e(money((int) $p['price'])) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
