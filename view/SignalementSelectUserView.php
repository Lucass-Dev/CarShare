<section class="report-main">
    <section class="report-container">
        <h1 class="report-title">Sélectionnez un utilisateur à signaler</h1>

        <?php if ($error === 'user_not_found'): ?>
            <p style="margin:12px 0; padding:10px; border-radius:8px; background:#ffe8e8; color:#c00;">
                ❌ Utilisateur ou trajet non trouvé
            </p>
        <?php elseif ($error === 'missing_data'): ?>
            <p style="margin:12px 0; padding:10px; border-radius:8px; background:#ffe8e8; color:#c00;">
                ❌ Données manquantes
            </p>
        <?php elseif ($error === 'save_failed'): ?>
            <p style="margin:12px 0; padding:10px; border-radius:8px; background:#ffe8e8; color:#c00;">
                ❌ Erreur lors de l'enregistrement
            </p>
        <?php endif; ?>

        <form id="select-user-form" class="report-form" style="max-width: 600px; margin: 0 auto;">
            <div class="form-row">
                <label for="user-search" class="form-label">Rechercher un utilisateur</label>
                <input 
                    type="text" 
                    id="user-search" 
                    class="form-select" 
                    placeholder="Tapez un nom, prénom ou email..."
                    autocomplete="off"
                >
                <input type="hidden" id="selected-user-id" name="user_id" value="">
                
                <!-- Results dropdown -->
                <div id="search-results" style="display:none; position:absolute; background:white; border:1px solid #ccc; max-height:300px; overflow-y:auto; width:100%; z-index:1000; border-radius:4px; box-shadow:0 2px 8px rgba(0,0,0,0.1);"></div>
            </div>

            <div id="selected-user-info" style="display:none; margin:15px 0; padding:15px; background:#f0f0f0; border-radius:8px;">
                <p><strong>Utilisateur sélectionné:</strong> <span id="selected-user-name"></span></p>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit" disabled id="continue-btn">
                    Continuer
                </button>
            </div>
        </form>
    </section>
</section>

<script>
// Dynamic user search for signalement
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('user-search');
    const searchResults = document.getElementById('search-results');
    const selectedUserIdInput = document.getElementById('selected-user-id');
    const continueBtn = document.getElementById('continue-btn');
    const form = document.getElementById('select-user-form');
    const selectedUserInfo = document.getElementById('selected-user-info');
    const selectedUserName = document.getElementById('selected-user-name');
    
    // All users data from PHP
    const users = <?= json_encode(array_map(function($u) {
        return [
            'id' => $u['id'],
            'first_name' => $u['first_name'],
            'last_name' => $u['last_name'],
            'email' => $u['email'],
            'fullName' => $u['first_name'] . ' ' . $u['last_name']
        ];
    }, $users), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    
    let selectedUserId = null;
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }
        
        // Filter users
        const filtered = users.filter(u => 
            u.first_name.toLowerCase().includes(query) ||
            u.last_name.toLowerCase().includes(query) ||
            u.fullName.toLowerCase().includes(query) ||
            u.email.toLowerCase().includes(query)
        );
        
        if (filtered.length === 0) {
            searchResults.innerHTML = '';
            const noResults = document.createElement('div');
            noResults.style.cssText = 'padding:10px; color:#999;';
            noResults.textContent = 'Aucun utilisateur trouvé';
            searchResults.appendChild(noResults);
            searchResults.style.display = 'block';
            return;
        }
        
        // Display results
        searchResults.innerHTML = '';
        filtered.forEach(user => {
            const div = document.createElement('div');
            div.style.cssText = 'padding:10px; cursor:pointer; border-bottom:1px solid #eee;';
            
            const strong = document.createElement('strong');
            strong.textContent = user.fullName;
            div.appendChild(strong);
            
            div.appendChild(document.createElement('br'));
            
            const small = document.createElement('small');
            small.style.color = '#666';
            small.textContent = user.email;
            div.appendChild(small);
            
            div.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f5f5f5';
            });
            div.addEventListener('mouseleave', function() {
                this.style.backgroundColor = 'white';
            });
            
            div.addEventListener('click', function() {
                selectUser(user);
            });
            
            searchResults.appendChild(div);
        });
        
        searchResults.style.display = 'block';
    });
    
    function selectUser(user) {
        selectedUserId = user.id;
        selectedUserIdInput.value = user.id;
        searchInput.value = user.fullName;
        searchResults.style.display = 'none';
        
        selectedUserName.textContent = user.fullName + ' (' + user.email + ')';
        selectedUserInfo.style.display = 'block';
        continueBtn.disabled = false;
    }
    
    // Click outside to close results
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (selectedUserId) {
            // No carpooling_id required, just redirect with user_id
            window.location.href = url('index.php?action=signalement&user_id=' + selectedUserId);
        }
    });
});
</script>
