// Star display synchronization for rating form
document.addEventListener('DOMContentLoaded', function() {
    const starsSelect = document.getElementById('stars');
    const starDisplay = document.getElementById('star-display');

    if (!starsSelect || !starDisplay) return;

    // Update star display when selection changes
    starsSelect.addEventListener('change', function() {
        const rating = parseInt(this.value);
        updateStarDisplay(rating);
    });

    function updateStarDisplay(rating) {
        const stars = starDisplay.querySelectorAll('.star');
        
        stars.forEach(function(star, index) {
            if (index < rating) {
                star.classList.add('star--on');
                star.textContent = '★';
            } else {
                star.classList.remove('star--on');
                star.textContent = '☆';
            }
        });
    }

    // Initialize with current selection
    const initialRating = parseInt(starsSelect.value);
    updateStarDisplay(initialRating);
});
