function validate(event){
    // Check if user didn't leave a form field empty.
    let forms = document.forms["login"];
    var fail = false;
    forms.elements.username.classList.remove("fail");
    forms.elements.password.classList.remove("fail");
    
    // Check if username field is empty.
    if (forms.elements.username.value.length == 0){
        fail = true;
        forms.elements.username.classList.add("fail");
    }

    // Check if password field is empty.
    if (forms.elements.password.value.length == 0){
        fail = true;
        forms.elements.password.classList.add("fail");
    }

    // Prevent submitting the form in case of an empty field.
    if (fail){
        event.preventDefault();
    }
}

document.querySelector("form").addEventListener("submit", validate);