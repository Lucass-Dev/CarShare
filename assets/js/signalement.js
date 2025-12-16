// User and carpooling selection for signalement page
document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('user-select');
    const carpoolingSelect = document.getElementById('carpooling-select');
    const continueBtn = document.getElementById('continue-btn');
    const form = document.getElementById('select-user-form');

    if (!userSelect || !carpoolingSelect || !continueBtn || !form) return;

    // When user is selected, fetch their carpoolings
    userSelect.addEventListener('change', function() {
        const userId = this.value;
        
        // Reset carpooling select
        carpoolingSelect.innerHTML = '<option value="">-- Chargement... --</option>';
        carpoolingSelect.disabled = true;
        continueBtn.disabled = true;

        if (!userId) {
            carpoolingSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un utilisateur --</option>';
            return;
        }

        // Fetch carpoolings for this user
        fetch('/CarShare/index.php?action=signalement_get_carpoolings&user_id=' + userId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                carpoolingSelect.innerHTML = '';
                
                if (data.length === 0) {
                    carpoolingSelect.innerHTML = '<option value="">Aucun trajet trouvé</option>';
                } else {
                    carpoolingSelect.innerHTML = '<option value="">-- Sélectionnez un trajet --</option>';
                    data.forEach(function(carpooling) {
                        const option = document.createElement('option');
                        option.value = carpooling.id;
                        
                        const date = new Date(carpooling.start_date);
                        const dateStr = date.toLocaleDateString('fr-FR');
                        
                        option.textContent = carpooling.start_name + ' → ' + carpooling.end_name + ' — ' + dateStr;
                        carpoolingSelect.appendChild(option);
                    });
                    carpoolingSelect.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error fetching carpoolings:', error);
                carpoolingSelect.innerHTML = '<option value="">Erreur lors du chargement</option>';
            });
    });

    // When carpooling is selected, enable continue button
    carpoolingSelect.addEventListener('change', function() {
        continueBtn.disabled = !this.value;
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const userId = userSelect.value;
        const carpoolingId = carpoolingSelect.value;
        
        if (userId && carpoolingId) {
            window.location.href = '/CarShare/index.php?action=signalement&user_id=' + userId + '&carpooling_id=' + carpoolingId;
        }
    });
});
