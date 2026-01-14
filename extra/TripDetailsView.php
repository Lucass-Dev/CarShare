<h1>Détails du trajet</h1>

  <div class="trajet-info">
    <div>
      <h2><?= htmlspecialchars($carpooling['start_location']) ?> → <?= htmlspecialchars($carpooling['end_location']) ?></h2>
      <span><?= date('d/m/Y', strtotime($carpooling['start_date'])) ?></span>
    </div>
  </div>

  <div class="card">
    <div class="left">
      <img src="/CarShare/assets/img/avatar.jpg" alt="Photo du conducteur">
      <p><strong><?= htmlspecialchars($carpooling['first_name']) ?></strong><br>
      <?= $carpooling['global_rating'] ? round($carpooling['global_rating'], 1) : 'N/A' ?>⭐</p>
    </div>

    <div class="right">
      <table>
        <tr>
          <td><strong>Départ</strong></td>
          <td>Lieu : <?= htmlspecialchars($carpooling['start_location']) ?></td>
          <td>Date : <?= date('d/m/Y', strtotime($carpooling['start_date'])) ?></td>
          <td>Heure : <?= date('H:i', strtotime($carpooling['start_date'])) ?></td>
        </tr>
        <tr>
          <td><strong>Arrivée</strong></td>
          <td>Lieu : <?= htmlspecialchars($carpooling['end_location']) ?></td>
          <td colspan="2"></td>
        </tr>
      </table>

      <div class="details-bottom">
        <div>
          <p><?= $carpooling['available_places'] ?> place(s) disponible(s)</p>
        </div>
        <div>
          <span class="price"><?= number_format($carpooling['price'], 2) ?> € TTC</span><br>
          <br>
          <a class="btn-reserver" href="/CarShare/index.php?action=payment&carpooling_id=<?= $carpooling['id'] ?>">Réserver</a>
        </div>
      </div>
    </div>
  </div>