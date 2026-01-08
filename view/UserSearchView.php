<link rel="stylesheet" href="/CarShare/assets/styles/user-search.css">

<div class="search-container">
    <div class="search-header">
        <h1>🔍 Recherche d'utilisateurs</h1>
        
        <form method="GET" action="/CarShare/index.php" class="search-form">
            <input type="hidden" name="action" value="user_search">
            <input type="hidden" name="sort" value="<?= htmlspecialchars($sortBy ?? 'name') ?>">
            <div class="search-input-wrapper">
                <input 
                    type="text" 
                    name="q" 
                    value="<?= htmlspecialchars($query) ?>" 
                    placeholder="Rechercher un utilisateur par nom ou prénom..."
                    class="search-input"
                    autofocus
                >
                <button type="submit" class="search-btn">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <?php if (!empty($query)): ?>
        <div class="search-meta">
            <div class="results-count">
                <?= count($users) ?> utilisateur<?= count($users) > 1 ? 's' : '' ?> trouvé<?= count($users) > 1 ? 's' : '' ?>
            </div>
            
            <div class="sort-controls">
                <label>Trier par:</label>
                <a href="?action=user_search&q=<?= urlencode($query) ?>&sort=name" 
                   class="sort-btn <?= ($sortBy ?? 'name') === 'name' ? 'active' : '' ?>">
                    Nom
                </a>
                <a href="?action=user_search&q=<?= urlencode($query) ?>&sort=rating" 
                   class="sort-btn <?= $sortBy === 'rating' ? 'active' : '' ?>">
                    ⭐ Note
                </a>
                <a href="?action=user_search&q=<?= urlencode($query) ?>&sort=trips" 
                   class="sort-btn <?= $sortBy === 'trips' ? 'active' : '' ?>">
                    🚗 Trajets
                </a>
            </div>
        </div>

        <?php if (empty($users)): ?>
            <div class="no-results">
                <div class="no-results-icon">🔍</div>
                <h2>Aucun utilisateur trouvé</h2>
                <p>Essayez avec d'autres mots-clés</p>
            </div>
        <?php else: ?>
            <div class="users-list">
                <?php foreach ($users as $user): ?>
                    <a href="/CarShare/index.php?action=user_profile&id=<?= $user['id'] ?>" class="user-card">
                        <div class="user-avatar">
                            <div class="user-avatar-default">
                                <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                            </div>
                        </div>
                        
                        <div class="user-info">
                            <h3 class="user-name">
                                <?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['last_name']) ?>
                            </h3>
                            
                            <div class="user-meta">
                                <?php if ($user['car_brand']): ?>
                                    <span class="user-badge driver">
                                        🚗 Conducteur
                                    </span>
                                    <span class="user-vehicle">
                                        <?= htmlspecialchars($user['car_brand']) ?> <?= htmlspecialchars($user['car_model']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="user-badge passenger">
                                        👥 Passager
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="user-stats">
                                <?php if ($user['global_rating']): ?>
                                    <span class="stat-item">
                                        ⭐ <?= number_format($user['global_rating'], 1) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="stat-item muted">
                                        ⭐ Nouveau
                                    </span>
                                <?php endif; ?>
                                
                                <span class="stat-item">
                                    🚗 <?= $user['trip_count'] ?> trajet<?= $user['trip_count'] > 1 ? 's' : '' ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="user-arrow">
                            →
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="search-prompt">
            <div class="search-prompt-icon">👥</div>
            <h2>Rechercher un utilisateur</h2>
            <p>Entrez un nom ou un prénom pour trouver des utilisateurs</p>
        </div>
    <?php endif; ?>
</div>
