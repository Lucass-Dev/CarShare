(function(){
  // Enhance UX for rating/report actions.
  const cards = document.querySelectorAll('.trajet-card');
  cards.forEach(card => {
    const buttons = card.querySelectorAll('.small-btn');
    buttons.forEach(btn => {
      btn.addEventListener('click', (e) => {
        // Optional: simple confirmation before navigating
        const isRating = btn.href.includes('action=rating');
        const isSignalement = btn.href.includes('action=signalement');
        const label = isRating ? 'Donner un avis' : (isSignalement ? 'Signaler' : 'Continuer');
        if (!confirm(label + ' pour ce voyage ?')) {
          e.preventDefault();
        }
      });
    });
  });
})();
