var start_input = document.getElementById('start_place');
var start_suggestion_box = document.getElementById('start-suggestion-box');

var end_input = document.getElementById('end_place');
var end_suggestion_box = document.getElementById('end-suggestion-box');


start_suggestion_box.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('suggestion-item')) {
        start_input.value = e.target.innerText;
        start_suggestion_box.innerHTML = '';
    }
});

start_input.addEventListener('input', async function() {
     await fetchCities(start_input, start_suggestion_box);
});

end_suggestion_box.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('suggestion-item')) {
        end_input.value = e.target.innerText;
        end_suggestion_box.innerHTML = '';
    }
});

end_input.addEventListener('input', async function() {
     await fetchCities(end_input, end_suggestion_box);
});



async function fetchCities(input, box){
    var suggestions_html = '';
    if (input.value.length > 2) {
        try {
            // Utiliser le chemin absolu depuis la racine du site
            const basePath = window.location.pathname.split('/').slice(0, -1).join('/');
            const apiUrl = basePath.includes('CarShare') 
                ? basePath.substring(0, basePath.indexOf('CarShare') + 8) + '/model/Utils.php'
                : '/CarShare/model/Utils.php';
            
            const response = await fetch(`${apiUrl}?query=${encodeURIComponent(input.value)}&need=fetchCities`)
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP ${res.status}`);
                    }
                    return res.json();
                })
                .catch(error => {
                    console.error('[SearchPage] Erreur fetch cities:', error);
                    return [];
                });
            
            if (response && response.length > 0) {
                for (let city of response) {
                    suggestions_html += `<div class="suggestion-item">${city.name} (${city.postal_code})</div>`;
                }
                box.innerHTML = suggestions_html;
            } else {
                box.innerHTML = '<div class="suggestion-item no-results">Aucune ville trouvée</div>';
            }
        } catch (error) {
            console.error('[SearchPage] Erreur fetchCities:', error);
            box.innerHTML = '<div class="suggestion-item error">Erreur de recherche</div>';
        }
    } else if (input.value.length === 0) {
        box.innerHTML = '';
    }
}


    