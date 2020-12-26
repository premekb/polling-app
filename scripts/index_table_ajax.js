/**
 * Sends an ajax request to the server to generate a table on the index page
 * upon successful client side validation.
 * 
 * The function also calls functions which change the UI appropriately (navigation arrows, number input value)
 * 
 * @param {clickEvent, submitEvent} event Click on the navigation arrow, 
 * submitting page number via submit button, changing the row select (no event).
 * 
 * @return {void}
 */
function generateTable(event){
    if (event != undefined){
    event.preventDefault();
    }
    var rows = document.querySelector("select").value;
    // If the submit button was clicked or rows were changed.
    if (event == undefined || event.type == "submit"){
    var page = document.querySelector("#page").value;
    }
    // If an arrow was clicked. Get the page from the arrow link url and set the number input appropriately.
    else{
        var url = event.target.parentElement.href;
        var url = new URL(url);
        var page = url.searchParams.get("page");
        document.querySelector("input[type=number]").value = Number(page);
    }
    var numberInput = document.querySelector("#page");
    if (validate(page)){
        var table = document.querySelector("table");
        var xhttp = new XMLHttpRequest();
        numberInput.classList.remove("fail");

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                table.innerHTML = this.responseText;
                document.querySelector("select").scrollIntoView();
            }
        }
        
        xhttp.open("GET", "php/ajax_try.php?page=" + page + "&rows=" + rows, true);
        xhttp.send();
        generateArrows(page);
        // rewire the event listeners for arrows
        var left_arrow = document.querySelector("#left_nav");
        var right_arrow = document.querySelector("#right_nav");

        if (left_arrow != undefined){
            left_arrow.addEventListener("click", generateTable);
        }

        if (right_arrow != undefined){
            right_arrow.addEventListener("click", generateTable);
        }
    }
    else{
        numberInput.classList.add("fail")
    }
}

/**
 * Deletes current arrows under the table on the index page.
 * Decides which arrows to generate.
 * 
 * @param {*} page
 * 
 * @return {void}
 */
function generateArrows(page){
    // Delete current arrows
    var leftArrow = document.querySelector("#left_nav");
    var rightArrow = document.querySelector("#right_nav");
    if (leftArrow != null){
        leftArrow.parentElement.remove();
        leftArrow.remove();
    }

    if (rightArrow != null){
        rightArrow.parentElement.remove();
        rightArrow.remove();
    }

    // Decide which arrows should be generated
    maxPages = document.querySelector("#page").max;
    if (maxPages != 1){
        if (page == 1){
            generateRightArrow(page);
        }

        else if (page == maxPages){
            generateLeftArrow(page);
        }

        else{
            generateRightArrow(page);
            generateLeftArrow(page);
        }
    }
}
/**
 * Generate the right navigation arrow under the table on the index page.
 * 
 * @param {*} page 
 * 
 * @return {void}
 */
function generateRightArrow(page){
    rightLink = document.createElement("a");
    rightLink.href = "index.php?page=" + (Number(page) + 1);
    rightArrow = document.createElement("img");
    rightArrow.src = "Icons/arrow_right_index.png"
    rightArrow.id = "right_nav";
    rightArrow.alt = "next page arrow";
    rightLink.appendChild(rightArrow);
    form = document.getElementsByName("pages")[0];
    bottomNav = document.querySelector("#bottom_nav");
    bottomNav.insertBefore(rightLink, form);
}

/**
 * Generate the left navigation arrow under the table on the index page.
 * 
 * @param {*} page 
 * 
 * @return {void}
 */
function generateLeftArrow(page){
    leftLink = document.createElement("a");
    leftLink.href = "index.php?page=" + (Number(page) + -1);
    leftArrow = document.createElement("img");
    leftArrow.src = "Icons/arrow_left_index.png"
    leftArrow.id = "left_nav";
    leftArrow.alt = "previous page arrow";
    leftLink.appendChild(leftArrow);
    form = document.getElementsByName("pages")[0];
    bottomNav = document.querySelector("#bottom_nav");
    bottomNav.insertBefore(leftLink, form);
}

/**
 * Returns true, if the page argument is a number of page,
 * that can be generated.
 * 
 * @param {*} page 
 * 
 * @return {boolean}
 */
function validate(page){
    var stringPage = String(page);
    
    if (stringPage.length == 0){
        return false;
    }
    for (i = 0; i < stringPage.length; i++){
        if (!isDigit(stringPage[i])){
            return false;
        }
    }

    // Extract the maximum page number from the number input form.
    var maxPage = Number(document.querySelector("#page").max);

    if (page < 0 || page > maxPage){
        return false;
    }

    return true;
}

/**
 * Returns true if x is a digit.
 * 
 * @param {string} x one character
 * 
 * @return {boolean} 
 */
function isDigit(x){
    return /[0-9]/.test(x);
}

/**
 * It is called when the rows select is changed. It sets the number input value to 1,
 * based on which the table is generated, changes the maximum amount of pages value
 * and generates the table.
 * 
 * @return {void}
 */
function changeRows(){
    let rows = document.querySelector("select").value;
    document.querySelector("#page").max = Math.ceil((Number(originalMaxPages )* 25) / rows);
    document.querySelector("#of_pages").innerHTML = "of " + document.querySelector("#page").max;
    document.querySelector("#page").value = "1";
    generateTable();
}

var originalMaxPages = document.querySelector("#page").max;

document.querySelector("form").addEventListener("submit", generateTable);
document.querySelector("select").addEventListener("change", changeRows);

var left_arrow = document.querySelector("#left_nav");
var right_arrow = document.querySelector("#right_nav");

if (left_arrow != undefined){
    left_arrow.addEventListener("click", generateTable);
}

if (right_arrow != undefined){
    right_arrow.addEventListener("click", generateTable);
}