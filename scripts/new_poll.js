/**
 * Adds a new input text field for answers, if there is less than 20 of them.
 * 
 * @return {void}
 */
function addInput() {
    // Adds a new input field for answers. Rewires the event listener.
    if (answerCount != 20){
    answerCount += 1

    // Create a label for the input
    let newLabel = document.createElement("label");
    newLabel.setAttribute("for", "answer" + answerCount);
    newLabel.innerHTML = "Answer " + answerCount;

    // Create a new text input element. Assign a cookie value.
    let newInput = document.createElement("input");
    newInput.setAttribute("type", "text");
    newInput.setAttribute("name", "answers[]");
    newInput.setAttribute("id", "answer" + answerCount);
    newInput.setAttribute("value", getCookie("answer" + answerCount));

    // Insert the label and input field.
    let form = document.querySelector("form");
    form.insertBefore(newLabel, plusSign);
    form.insertBefore(document.createElement("br"), plusSign);
    form.insertBefore(newInput, plusSign);
    form.insertBefore(document.createElement("br"), plusSign)

    // Rewire the event listener on the plus sign. 
    plusSign = document.querySelector("#addAnswer");
    plusSign.addEventListener("click", addInput);
    plusSign.scrollIntoView();
    }
}

/**
 * Removes the last input text field, if there is more than 2 of them left.
 * Saves value of this text field in cookie, in case the user would decide to add it back.
 * This cookie is removed upon unloading the page.
 * 
 * @return {void}
 */
function removeInput() {
    if (answerCount > 2){
        saveInput();
        form = document.querySelector("form");
        form.querySelector("label[for='answer" + answerCount + "']").nextSibling.remove();
        form.querySelector("label[for='answer" + answerCount + "']").remove();
        form.querySelector("#answer" + answerCount).nextSibling.remove();
        form.querySelector("#answer" + answerCount).remove();
        answerCount -= 1;
        document.cookie = "answerCount=" + answerCount;
    }
}

/**
 * It saves the value of the last input text field into a cookie.
 * This function is only called upon removing the last input text field.
 * 
 * @return {void}
 */
function saveInput() {
    // Save the input value in a cookie.
    textFieldValue = document.querySelector("#answer" + answerCount).value;
    if (textFieldValue.length != 0){
        document.cookie = "answer" + answerCount + "=" + textFieldValue;
    }
}

/**
 * It gets a cookie value based on its name.
 * This script was taken from the W3CSchools website.
 * 
 * @return {string}
 */
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

/**
 * Delete stored text field inputs if the validation was successful.
 * 
 * @return {void}
 */
function deleteCookies(){
      for (i = 1; i <= 20; i++){
          document.cookie = "answer" + i + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
      }
  }

/**
 * Validates data submited in the "create a new poll" form.
 * Prevents the form from submitting if the data is in an incorrect format.
 * 
 * @param {submitEvent} event 
 * 
 * @return {void}
 */
function validate(event){
    var questionField = document.querySelector("#question");
    // If the fail variable is set to true at any point. Then the validation is unsuccessful.
    var fail = false;
    var tooLongAnswer = false;

    var errorParagraph = document.querySelector("p");
    var main = document.querySelector("main");

    // Remove the red borders from the last control.
    questionField.classList.remove("fail");

    // Remove all the previous error messages.
    while (errorParagraph){
        errorParagraph.remove();
        errorParagraph = document.querySelector("p");
    }

    // Prevent submission if the question field is empty or contains only spaces
    if (questionField.value.split(" ").join("").length == 0){
        questionField.classList.add("fail");
        fail = true;
    }

    // Prevent submission if the question is longer than 200 characters.
    else if (questionField.value.length > 200){
        questionField.classList.add("fail");
        fail = true;
        errorParagraph = document.createElement("p");
        errorParagraph.innerHTML = "The question cannot be longer than 200 letters.";
        main.appendChild(errorParagraph);
    }

    for (i = 0; i < answerCount; i++){
        var answerId = "#answer" + (i + 1);
        answerField = document.querySelector(answerId);
        // Remove the red borders from the last control.
        answerField.classList.remove("fail");

        // Prevent submission if the answer field is empty or contains only spaces
        if (answerField.value.split(" ").join("").length == 0){
            answerField.classList.add("fail");
            fail = true;
        }

        // Prevent submission if the answer is longer than 100 characters.
        else if (answerField.value.length > 100){
            answerField.classList.add("fail");
            fail = true;
            tooLongAnswer = true;
        }
    }

    if (fail){
        event.preventDefault();
    }

    // Delete the stored text field input if the validation was successful.
    else if (!fail){
        deleteCookies();
    }

    if (tooLongAnswer){
        errorParagraph = document.createElement("p");
        errorParagraph.innerHTML = "The answers cannot be longer than 100 letters.";
        main.appendChild(errorParagraph);
    }
}

/**
 * Tries to extract the number of answers from the URL.
 * If the GET answers parameter is not set, then return 2.
 * 
 * @return {Number}
 */
function getAnswerCount(){
    var url = window.location.href;
    var url = new URL(url);
    let answerCount = url.searchParams.get("answers");
    if (!answerCount){
        return 2;
    }
    else{
        return Number(answerCount);
    }
}

// Remove the links on plus and minus sign generated by the PHP.
// This way of adding and removing answers should only be used with JS off.
document.querySelector("#minusphp").outerHTML = document.querySelector("#minusphp").innerHTML;
document.querySelector("#plusphp").outerHTML = document.querySelector("#plusphp").innerHTML;

var plusSign = document.querySelector("#addAnswer");
var minusSign = document.querySelector("#removeAnswer");
var answerCount = getAnswerCount();
plusSign.addEventListener("click", addInput);
minusSign.addEventListener("click", removeInput);

document.querySelector("form").addEventListener("submit", validate);
// Delete the saved form values when user leaves the page.
window.addEventListener("unload", deleteCookies);