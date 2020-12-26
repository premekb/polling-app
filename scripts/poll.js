/**
 * Checks that if the user is logged in an has chosen an answer.
 * If not, then voting is prevented.
 * 
 * @param {submitEvent} event 
 * 
 * @return {boolean}
 */
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
    return false;
}

/**
 * Removes the HTML list poll results, to be replaced by a google chart.
 * 
 * @return {void}
 */
function removeHTMLresults(){
    document.querySelector("#jsoff").remove();
}

/**
 * Parses the HTML list results. Appends a paragraph saying, that no one has voted yet,
 * if all of the answer results are 0.
 * 
 * @return {void}
 */
function noVotes(){
    var items = document.querySelectorAll("li");
    var voted = false;
    for (i = 0; i < items.length; i++){
        if(items[i].innerHTML[items[i].innerHTML.length - 1] != "0"){
            voted = true;
            break;
        }
    }

    if (!voted){
        var paragraph = document.createElement("p");
        var piechart = document.querySelector("#piechart");
        paragraph.innerHTML = "No one has voted yet. Be the first one.";
        piechart.after(paragraph);
    }

}

document.querySelector("form#voting_form").addEventListener("submit", validate);
noVotes();
removeHTMLresults();
