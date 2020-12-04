function validatePID(event){
    // Check if user didn't leave a form field empty.
    let forms = document.querySelector("form[name=delete_poll]")
    var fail = false;
    forms.elements.pid.classList.remove("fail");
    
    // Check if username field is empty.
    if (forms.elements.pid.value.length == 0){
        fail = true;
        forms.elements.pid.classList.add("fail");
    }

    // Check if pid is a number
    for (i = 0; i < forms.elements.pid.value.length; i++){
        if (!isDigit(forms.elements.pid.value[i])){
            fail = true;
            forms.elements.pid.classList.add("fail");
            break;
        }
    }

    // Prevent submitting the form in case of an empty field.
    if (fail){
        event.preventDefault();
    }
}

function validateUsername(event){
    // Check if user didn't leave a form field empty.
    let forms = document.querySelector("form[name=delete_user]")
    var fail = false;
    forms.elements.username.classList.remove("fail");
    
    // Check if username field is empty.
    if (forms.elements.username.value.length == 0){
        fail = true;
        forms.elements.username.classList.add("fail");
    }

    // Prevent submitting the form in case of an empty field.
    if (fail){
        event.preventDefault();
    }
}

function isDigit(x){
    // Returns true if input is a digit.

    return /[0-9]/.test(x);
}

document.querySelector("form[name=delete_poll]").addEventListener("submit", validatePID);
document.querySelector("form[name=delete_user]").addEventListener("submit", validateUsername);