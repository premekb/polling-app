function addInput() {
    // Adds a new input field for answers. Rewires the event listener.
    if (answerCount != 20){
    answerCount += 1

    let newLabel = document.createElement("label");
    newLabel.setAttribute("for", "answer" + answerCount);
    newLabel.innerHTML = "Answer " + answerCount;

    let newInput = document.createElement("input");
    newInput.setAttribute("type", "text");
    newInput.setAttribute("name", "answers[]");
    newInput.setAttribute("id", "answer" + answerCount);

    let form = document.querySelector("form");
    form.insertBefore(newLabel, plusSign);
    form.insertBefore(document.createElement("br"), plusSign);
    form.insertBefore(newInput, plusSign);
    form.insertBefore(document.createElement("br"), plusSign)


    plusSign = document.querySelector("#addAnswer");
    plusSign.addEventListener("click", addInput);
    plusSign.scrollIntoView();
    }
}

function removeInput() {
    // Remove an input field. Rewires the event listener.
    if (answerCount != 0){
        form = document.querySelector("form");
        form.querySelector("label[for='answer" + answerCount + "']").nextSibling.remove();
        form.querySelector("label[for='answer" + answerCount + "']").remove();
        form.querySelector("#answer" + answerCount).nextSibling.remove();
        form.querySelector("#answer" + answerCount).remove();
        answerCount -= 1;
    }
}

function validate(event){
    // Validate the user input in the "create a new poll" form.
    var questionField = document.querySelector("#question");
    var fail = false;
    var tooLongAnswer = false;

    var errorParagraph = document.querySelector("p");
    var article = document.querySelector("article");

    // Remove the red borders from the last control.
    questionField.classList.remove("fail");

    // Remove all the previous error messages.
    while (errorParagraph){
        errorParagraph.remove();
        errorParagraph = document.querySelector("p");
    }

    if (questionField.value.split(" ").join("").length == 0){
        questionField.classList.add("fail");
        fail = true;
    }

    else if (questionField.value.length > 200){
        questionField.classList.add("fail");
        fail = true;
        errorParagraph = document.createElement("p");
        errorParagraph.innerHTML = "The question cannot be longer than 200 letters.";
        article.appendChild(errorParagraph);
    }

    for (i = 0; i < answerCount; i++){
        var answerId = "#answer" + (i + 1);
        answerField = document.querySelector(answerId);
        // Remove the red borders from the last control.
        answerField.classList.remove("fail");

        if (answerField.value.split(" ").join("").length == 0){
            answerField.classList.add("fail");
            fail = true;
        }

        else if (answerField.value.length > 100){
            answerField.classList.add("fail");
            fail = true;
            tooLongAnswer = true;
        }
    }

    if (fail){
        event.preventDefault();
    }

    if (tooLongAnswer){
        errorParagraph = document.createElement("p");
        errorParagraph.innerHTML = "The answers cannot be longer than 100 letters.";
        article.appendChild(errorParagraph);
    }
}

document.querySelector("#minusphp").outerHTML = document.querySelector("#minusphp").innerHTML;
document.querySelector("#plusphp").outerHTML = document.querySelector("#plusphp").innerHTML;
var plusSign = document.querySelector("#addAnswer");
var minusSign = document.querySelector("#removeAnswer");
var answerCount = 3
plusSign.addEventListener("click", addInput);
minusSign.addEventListener("click", removeInput);

document.querySelector("form").addEventListener("submit", validate);