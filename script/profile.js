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

function sanitize(str) {
        return str.trim()
                  .replace(/</g, "&lt;")
                  .replace(/>/g, "&gt;")
                  .replace(/"/g, "&quot;")
                  .replace(/'/g, "&#039;");
    }