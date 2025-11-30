<?php
class RegisterModel {
    
    public function check_form_values($values) {
        if (!isset($values["first_name"]) || $values["first_name"] != "") {
            return false;
        }
        if (!isset($values["last_name"]) || $values["last_name"] != "") {
            return false;
        }
        if (!isset($values["mail"]) || $values["mail"] != "") {
            return false;
        }
        if (!isset($values["birthdate"]) || $values["birthdate"] != "") {
            return false;
        }
        if (!isset($values["pass"]) || $values["pass"] != "") {
            return false;
        }
        if (!isset($values["confirm_pass"]) || $values["confirm_pass"] != "") {
            return false;
        }
    }
}