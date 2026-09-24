<?php
/**
 * Sekcia recenzií z Google - skutočné recenzie z data/reviews.php
 * a výzva "Ohodnoťte nás na Google". Kým nie sú vložené žiadne recenzie,
 * zobrazí sa iba výzva s odkazmi na Google profil.
 */

$reviews = require __DIR__ . '/../data/reviews.php';
$reviewUrl = GOOGLE_REVIEW_URL !== '' ? GOOGLE_REVIEW_URL : GOOGLE_PROFILE_URL;

$googleLogo = '<svg class="google-logo" viewBox="0 0 48 48" aria-hidden="true"><path fill="#FFC107" d="M43.6 20.1H42V20H24v8h11.3C33.7 32.7 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.6-.4-3.9z"/><path fill="#FF3D00" d="m6.3 14.7 6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.1 26.7 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.1H42V20H24v8h11.3c-.8 2.2-2.2 4.2-4.1 5.6l6.2 5.2C37 39.2 44 34 44 24c0-1.3-.1-2.6-.4-3.9z"/></svg>';

$stars = function (float $rating): string {
    $out = '<span class="stars" aria-label="' . e(sprintf(t('%s z 5 hviezdičiek'), str_replace('.', ',', (string) $rating))) . '">';
    for ($i = 1; $i <= 5; $i++) {
        $out .= '<span class="stars__star' . ($i <= round($rating) ? ' stars__star--on' : '') . '" aria-hidden="true">★</span>';
    }
    return $out . '</span>';
};

// Farba krúžku s iniciálou - stabilná podľa mena, z palety značky.
$avatarColors = ['#c1272d', '#e3a018', '#2f9e44', '#1c7ed6', '#7048e8', '#d6336c', '#0c8599'];
$avatarColor = function (string $name) use ($avatarColors): string {
    return $avatarColors[abs(crc32($name)) % count($avatarColors)];
};
?>

<section class="section section--alt reviews">
  <div class="container">
    <div class="section__head">
      <p class="eyebrow"><?= h('Recenzie') ?></p>
      <h2><?= h('Čo o nás píšu zákazníci') ?></h2>
      <?php if (GOOGLE_RATING !== ''): ?>
      <div class="reviews__summary">
        <?= $googleLogo ?>
        <strong><?= e(GOOGLE_RATING) ?></strong>
        <?= $stars((float) str_replace(',', '.', GOOGLE_RATING)) ?>
        <?php if (GOOGLE_REVIEW_COUNT > 0): ?>
        <span><?= h('%s recenzií na Google', e((string) GOOGLE_REVIEW_COUNT)) ?></span>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>

    <?php if ($reviews): ?>
    <div class="reviews__grid">
      <?php foreach ($reviews as $review): ?>
      <article class="review-card">
        <header class="review-card__head">
          <span class="review-card__avatar" style="background:<?= e($avatarColor($review['name'])) ?>" aria-hidden="true"><?= e(function_exists('mb_substr') ? mb_substr($review['name'], 0, 1, 'UTF-8') : substr($review['name'], 0, 1)) ?></span>
          <div>
            <strong class="review-card__name"><?= e($review['name']) ?></strong>
            <?php if (!empty($review['date'])): ?><span class="review-card__date"><?= e($review['date']) ?></span><?php endif; ?>
          </div>
          <?= $googleLogo ?>
        </header>
        <?= $stars((float) $review['rating']) ?>
        <p><?= e($review['text']) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="reviews__cta<?= $reviews ? '' : ' reviews__cta--solo' ?>">
      <?php if (!$reviews): ?>
      <div class="reviews__cta-text">
        <?= $googleLogo ?>
        <p><?= h('Boli ste u nás spokojní? Budeme radi, keď sa o svoj zážitok podelíte na Google - pomôžete tým aj ostatným pri výbere.') ?></p>
      </div>
      <?php endif; ?>
      <div class="reviews__cta-actions">
        <a href="<?= e($reviewUrl) ?>" class="btn btn--primary" target="_blank" rel="noopener"><?= icon('star', 'icon icon--sm') ?> <?= h('Ohodnoťte nás na Google') ?></a>
        <a href="<?= e(GOOGLE_PROFILE_URL) ?>" class="btn btn--outline" target="_blank" rel="noopener"><?= h('Všetky recenzie na Google') ?></a>
      </div>
    </div>
  </div>
</section>
