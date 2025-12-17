// Star display synchronization for rating form
document.addEventListener('DOMContentLoaded', function() {
    const starsSelect = document.getElementById('stars');
    const starDisplay = document.getElementById('star-display');

    if (!starsSelect || !starDisplay) return;

    // Make stars interactive: click to set rating
    starDisplay.classList.add('stars--interactive');
    const stars = Array.from(starDisplay.querySelectorAll('.star'));
    stars.forEach((starEl, idx) => {
        starEl.addEventListener('click', function() {
            const newRating = idx + 1; // stars are 1-indexed visually
            starsSelect.value = String(newRating);
            updateStarDisplay(newRating);
        });
    });

    // Update star display when selection changes
    starsSelect.addEventListener('change', function() {
        const rating = parseInt(this.value);
        updateStarDisplay(rating);
    });

    function updateStarDisplay(rating) {
        const nodes = starDisplay.querySelectorAll('.star');
        nodes.forEach(function(star, index) {
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
