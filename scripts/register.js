function isDigit(x){
    // Returns true if input is a digit.

    return /[0-9]/.test(x);
}

function isChar(x){
    // Returns true if input is a letter.

    return /[a-zA-Z]/.test(x);
}
function validate(event){
    // Check if the the data from user is valid.
    // If not, show him an error message and prevent the form from submitting.

    var forms = document.forms["register"];
    var fail = false;
    var main = document.querySelector("main");

    // Remove the red borders from the last control.
    forms.elements.username.classList.remove("fail");
    forms.elements.email.classList.remove("fail");
    forms.elements.password.classList.remove("fail");
    forms.elements.r_password.classList.remove("fail");

    errorParagraph = document.querySelector("p");

    // Remove all the previous error messages.
    while (errorParagraph){
        errorParagraph.remove();
        errorParagraph = document.querySelector("p");
    }

    // If input is invalid, error message gets displayed in form of a paragraph.
    // Check if username has the reuqired length.
    if (forms.elements.username.value.length < 3 || forms.elements.username.value.length > 30){
        forms.elements.username.classList.add("fail");
        errorParagraph = document.createElement("p");
        errorParagraph.innerHTML = "Your username needs to be 3 - 30 characters long.";
        main.appendChild(errorParagraph);
        fail = true;
    }

    // Check if only allowed characters are contained in the username.
    if (forms.elements.username.value.length != 0){
        for (i = 0; i < forms.elements.username.value.length; i++){
            if (!isDigit(forms.elements.username.value[i]) && !isChar(forms.elements.username.value[i])){
                fail = true;
                errorParagraph = document.createElement("p");
                forms.elements.username.classList.add("fail");
                errorParagraph.innerHTML = "You username can only contain digits and letters.";
                main.appendChild(errorParagraph);
                break;
            }
        }
    }
    // Check if email address is valid.
    if (!(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(forms.elements.email.value))){
        forms.elements.email.classList.add("fail");
        errorParagraph = document.createElement("p");
        errorParagraph.innerHTML = "The e-mail address is invalid.";
        main.appendChild(errorParagraph);
        fail = true;
    }

    // Check if password has more than 6 characters.
    if (forms.elements.password.value.length < 6){
        forms.elements.password.classList.add("fail");
        forms.elements.r_password.classList.add("fail");
        errorParagraph = document.createElement("p");
        errorParagraph.innerHTML = "Your password needs to be atleast 6 characters long.";
        main.appendChild(errorParagraph);
        fail = true;
    }

    // Check if passwords match.
    if (forms.elements.password.value != forms.elements.r_password.value){
        forms.elements.r_password.classList.add("fail");
        errorParagraph = document.createElement("p");
        errorParagraph.innerHTML = "Passwords don't match.";
        main.appendChild(errorParagraph);
        fail = true;
    }

    // If one of the tests did not pass, then prevent submitting the form.
    if (fail){
        event.preventDefault();
    }
}

document.querySelector("form").addEventListener("submit", validate);