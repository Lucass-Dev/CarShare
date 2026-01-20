document.addEventListener('DOMContentLoaded', function() {
    const afterInput = document.getElementById('start_time_range_after');
    const beforeInput = document.getElementById('start_time_range_before');
    const afterValue = document.getElementById('after-value');
    const beforeValue = document.getElementById('before-value');

    function updateTimeDisplay(input, display) {
        const hours = parseInt(input.value);
        display.textContent = hours + 'h';
    }

    if (afterInput && afterValue) {
        updateTimeDisplay(afterInput, afterValue);
        afterInput.addEventListener('input', () => updateTimeDisplay(afterInput, afterValue));
    }

    if (beforeInput && beforeValue) {
        updateTimeDisplay(beforeInput, beforeValue);
        beforeInput.addEventListener('input', () => updateTimeDisplay(beforeInput, beforeValue));
    }
});
const params = new URLSearchParams(window.location.search);
let link = ""
let start_input = document.getElementById('start_place');
let start_suggestion_box = document.getElementById('start-suggestion-box');

let end_input = document.getElementById('end_place');
let end_suggestion_box = document.getElementById('end-suggestion-box');

let form_start_input = document .getElementById('form_start_input');
let form_end_input = document .getElementById('form_end_input');
let date = document.getElementById("date")

let baselink = "https://www.google.com/maps/embed/v1/directions?key=AIzaSyCST_1-YvBtvMCvCgX3qFb2KCsBoacIRa0&origin="
let midlink = "+France&destination="
let endlink = "&avoid=tolls|highways"

var today = new Date().toISOString().split('T')[0];
if (date) {
    date.setAttribute('min', today);
}

if (params.get("action") == "display_search") {
    let remove_sort_filters = document.getElementById("remove-sort-filters");
    let remove_date_filters = document.getElementById("remove-date-filters");
    let remove_constraints_filters = document.getElementById("remove-constraints-filters");

    remove_sort_filters.addEventListener('click', function(){
        const sorts_filters = document.getElementsByClassName("sort-input");
        for (let index = 0; index < sorts_filters.length; index++) {
            const element = sorts_filters[index];
            element.checked = 0
        }
    })

    remove_date_filters.addEventListener('click', function(){
        const sorts_filters = document.getElementsByClassName("date-input");
        for (let index = 0; index < sorts_filters.length; index++) {
            const element = sorts_filters[index];
            element.value = 0
        }
    })

    remove_constraints_filters.addEventListener('click', function(){
        const sorts_filters = document.getElementsByClassName("constraints-input");
        for (let index = 0; index < sorts_filters.length; index++) {
            const element = sorts_filters[index];
            element.checked = 0
        }
    })
}

if (start_suggestion_box) {
    start_suggestion_box.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('suggestion-item')) {
        console.log(e.target.id);
        
        start_input.value = e.target.innerText;
        start_suggestion_box.innerHTML = '';
        form_start_input.value = e.target.id;
        if (end_input.value != "") {
            link = baselink+e.target.innerHTML.split('(')[0].trim()+midlink+end_input.value.split('(')[0].trim()+endlink
            changeIframe(link)
            
        }
    }
});

start_input.addEventListener('input', async function() {
    await fetchCities(start_input, start_suggestion_box);
});

start_input.onkeydown = function(event) {
    let key = event.keyCode || event.charCode;
    
    if ((key == 8 || key == 46) && start_input.value.length <= 5) {
        form_start_input.value = ""
        start_input.value = ""
        start_suggestion_box.innerHTML = ""
    }
}    
}

if (end_suggestion_box) {
    end_suggestion_box.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('suggestion-item')) {
        end_input.value = e.target.innerText;
        end_suggestion_box.innerHTML = '';
        form_end_input.value = e.target.id;
        if (start_input.value != "") {
            link = baselink+start_input.value.split('(')[0].trim()+midlink+e.target.innerHTML.split('(')[0].trim()+endlink
            changeIframe()
            
        }
        
    }
});

end_input.addEventListener('input', async function(event) {
    await fetchCities(end_input, end_suggestion_box);
});

end_input.onkeydown = function(event) {
    let key = event.keyCode || event.charCode;
    
    if ((key == 8 || key == 46) && end_input.value.length <= 5) {
        end_input.value = ""
        form_end_input.value = ""
        end_suggestion_box.innerHTML = ""
    }
}
}





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

if (params.get("action") == "details") {
    
    
    let start = document.getElementById("start_place");
    let end = document.getElementById("end_place");
    link = baselink+start.value+midlink+end.value+endlink
    console.log(link);
    changeIframe(link)
}

function changeIframe(){
    let iframe = document.getElementById("map-preview-link");
    iframe.src=link 
}