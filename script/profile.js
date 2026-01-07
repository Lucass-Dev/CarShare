async function check_update_profile_values() {
    
    let form = document.getElementById("update_user_form");

    const pwd = document.getElementById("pass");
    const confirm_pass = document.getElementById("confirm_pass");

    const state_message = document.getElementById("change_profile_msg");
    state_message.innerText = "";
    

    if (pwd.value !== "") {
        if (pwd.value.length < 12) {
            state_message.innerText = "Votre mot de passe doit contenir au minimum 12 caractères."
            state_message.style.display = "block";
            return;
        }else if (pwd.value != confirm_pass.value) {
            state_message.innerText = "Les mots de passes ne correspondent pas."
            state_message.style.display = "block";
            return;
        }else if (pwd.value !== "" && confirm_pass.value === "") {
            state_message.innerText = "Veuillez confirmer votre mot de passe."
            state_message.style.display = "block";
            return;
        }
    }
    form.submit();
    
}

async function check_update_car_values() {
    let form = document.getElementById("update-car-form");

    let carBrandInput = document.getElementById('car_brand');
    let carModelInput = document.getElementById('car_model');
    let carYearInput = document.getElementById('car_year');
    let carCritAirInput = document.getElementById('car_crit_air');
    let carPlateInput = document.getElementById('car_plate');

    carBrandInput.value = sanitize(carBrandInput.value)
    carModelInput.value = sanitize(carModelInput.value)
    carYearInput.value = sanitize(carYearInput.value)
    carCritAirInput.value = sanitize(carCritAirInput.value)
    carPlateInput.value = sanitize(carPlateInput.value)

    form.submit()
    
}

function sanitize(str) {
        return str.trim()
                  .replace(/</g, "&lt;")
                  .replace(/>/g, "&gt;")
                  .replace(/"/g, "&quot;")
                  .replace(/'/g, "&#039;");
    }