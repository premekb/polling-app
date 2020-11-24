function validate(event){
    // Check if user is logged in based on the nav menu.
    var login = document.querySelector("a[href='login.php']");
    if (login){
        event.preventDefault();
        alert("You need to login to be able to vote.")
        return false;
    }


    // Check if user has chosen an answer.
    var answer = document.querySelector("form").value
    var answerIndex = 1;
    var radioButton = document.querySelector("#vote" + String(answerIndex));

    while (radioButton){
        if (radioButton.checked){
            return true;
        }
        radioButton = document.querySelector("#vote" + String(answerIndex));
        answerIndex += 1;
    }

    event.preventDefault();
    alert("Choose an answer please.");
}


document.querySelector("form").addEventListener("submit", validate);