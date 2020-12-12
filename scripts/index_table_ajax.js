function generateTable(event){
    // Ajax request for page of polls.
    if (event != undefined){
    event.preventDefault();
    }
    var rows = document.querySelector("select").value;
    // If the submit button was clicked or rows were changed.
    if (event == undefined || event.type == "submit"){
    var page = document.querySelector("#page").value;
    }
    // If an arrow was clicked. Get the page from url and set number input appropriately.
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

function generateArrows(page){
    // Generate navigation arrows under the table.
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

function generateRightArrow(page){
    // Generates the right navigation arrow under the table.
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


function generateLeftArrow(page){
    // Generates the left navigation arrow under the table.
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

function validate(page){
    // Check, that the page to be generated is in proper range.
    var stringPage = String(page);
    
    if (stringPage.length == 0){
        return false;
    }
    for (i = 0; i < stringPage.length; i++){
        if (!isDigit(stringPage[i])){
            return false;
        }
    }
    var maxPage = Number(document.querySelector("#page").max);

    if (page < 0 || page > maxPage){
        return false;
    }

    return true;
}

function isDigit(x){
    // Returns true if input is a digit.

    return /[0-9]/.test(x);
}

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