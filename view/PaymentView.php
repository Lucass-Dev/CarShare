<h1>Paiement du trajet</h1>

  <div class="card">

    <?php if (isset($error)): ?>
      <div class="error-message">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <div class="payment-info">
      <div class="section-title">Récapitulatif du trajet</div>

      <table>
        <tr><td><strong>Trajet :</strong></td><td><?= htmlspecialchars($carpooling['start_location']) ?> → <?= htmlspecialchars($carpooling['end_location']) ?></td></tr>
        <tr><td><strong>Date :</strong></td><td><?= date('d/m/Y', strtotime($carpooling['start_date'])) ?></td></tr>
        <tr><td><strong>Heure :</strong></td><td><?= date('H:i', strtotime($carpooling['start_date'])) ?></td></tr>
        <tr><td><strong>Conducteur :</strong></td><td><?= htmlspecialchars($carpooling['first_name']) ?> (⭐ <?= $carpooling['global_rating'] ? round($carpooling['global_rating'], 1) : 'N/A' ?>)</td></tr>
      </table>

      <div class="total-price">
        Total : <?= number_format($carpooling['price'], 2) ?> € TTC
      </div>
    </div>

    <form class="payment-form" method="POST" action="/CarShare/index.php?action=payment&carpooling_id=<?= $carpooling['id'] ?>">
      <div class="section-title">Informations de paiement</div>

      <div>
        <label>Nom sur la carte</label>
        <input type="text" name="card_name" placeholder="Ex : Marie Dupont" required>
      </div>

      <div>
        <label>Numéro de carte</label>
        <input type="text" name="card_number" placeholder="1234 5678 9012 3456" required>
      </div>

      <div style="display:flex; gap:20px;">
        <div style="flex:1;">
          <label>Expiration</label>
          <input type="text" name="card_expiry" placeholder="MM/AA" required>
        </div>
        <div style="flex:1;">
          <label>CVV</label>
          <input type="text" name="card_cvv" placeholder="123" required>
        </div>
      </div>

      <p class="legal-consent" style="margin:12px 0;">
        <label>
          <input type="checkbox" name="accept_terms" value="1" required>
          <span>
            J'accepte les conditions CarShare pour cette réservation (
            <a href="/CarShare/index.php?action=cgv" target="_blank">CGV</a>,
            <a href="/CarShare/index.php?action=cgu" target="_blank">CGU</a>,
            <a href="/CarShare/index.php?action=legal" target="_blank">Mentions légales</a>
            )
          </span>
        </label>
      </p>

      <button type="submit" class="btn-payer">Payer maintenant</button>

      <div class="details-resume">
        En cliquant sur "Payer maintenant", vous confirmez votre réservation.
      </div>
    </form>

  </div>
