let link = ""
let start_input = document.getElementById('start_place');
let start_suggestion_box = document.getElementById('start-suggestion-box');

let end_input = document.getElementById('end_place');
let end_suggestion_box = document.getElementById('end-suggestion-box');

let form_start_input = document .getElementById('form_start_input');
let form_end_input = document .getElementById('form_end_input');

let baselink = "https://www.google.com/maps/embed/v1/directions?key=AIzaSyCST_1-YvBtvMCvCgX3qFb2KCsBoacIRa0&origin="
let midlink = "+France&destination="
let endlink = "&avoid=tolls|highways"

var today = new Date().toISOString().split('T')[0];
document.getElementById("date").setAttribute('min', today);

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

start_suggestion_box.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('suggestion-item')) {
        console.log(e.target.id);
        
        start_input.value = e.target.innerText;
        start_suggestion_box.innerHTML = '';
        form_start_input.value = e.target.id;
        if (end_input.value != "") {
            link = baselink+e.target.innerHTML.split('(')[0].trim()+midlink+end_input.value.split('(')[0].trim()+endlink
            changeIframe()
            
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



async function fetchCities(input, box){
    let suggestions_html = '';
    if (input.value.length > 2) {
        
        const response = await fetch(`../model/Utils.php?query=${input.value}&need=fetchCities`)
            .then(res => {return res.json()});
        for (let city of response) {

            suggestions_html += `<div class="suggestion-item" id="${city.id}">${city.name} (${city.postal_code})</div>`;
        };
        
        box.innerHTML = suggestions_html;
    }else{
        box.innerHTML = '';
    }
}

function changeIframe(){
    let iframe = document.getElementById("map-preview-link");
    iframe.src=link 
}