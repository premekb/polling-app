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

document.querySelector("#minusphp").outerHTML = document.querySelector("#minusphp").innerHTML;
document.querySelector("#plusphp").outerHTML = document.querySelector("#plusphp").innerHTML;
var plusSign = document.querySelector("#addAnswer");
var minusSign = document.querySelector("#removeAnswer");
var answerCount = 3
plusSign.addEventListener("click", addInput);
minusSign.addEventListener("click", removeInput);