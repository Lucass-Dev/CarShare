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
    if (input.value.length > 3) {
        const response = await fetch(`../model/Utils.php?query=${input.value}&need=fetchCities`)
            .then(res => {return res.json()});
        for (let city of response) {

            suggestions_html += `<div class="suggestion-item">${city.name} (${city.postal_code})</div>`;
        };
        
        box.innerHTML = suggestions_html;
            
    }   
}


    