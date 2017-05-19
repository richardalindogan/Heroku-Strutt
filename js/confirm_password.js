var password = document.getElementById("password");
var password_verify = document.getElementById("password_verify");

function validatePassword() {
    if (password.value != password_verify.value) {
        password_verify.setCustomValidity("Passwords Don't Match");
    } 
    else {
        password_verify.setCustomValidity('');
    }
}

password.onchange = validatePassword;
password_verify.onkeyup = validatePassword;