function generateTable(){
    // Ajax request for page of polls.
    var page = document.querySelector("#page").value;
    var numberInput = document.querySelector("#page");
    if (validate(page)){
        var table = document.querySelector("table");
        var xhttp = new XMLHttpRequest();
        numberInput.classList.remove("fail");

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                table.innerHTML = this.responseText;
            }
        }
        
        xhttp.open("GET", "php/ajax_try.php?page=" + page, true);
        xhttp.send();
        generateArrows(page);
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

document.querySelector("#gotopage").addEventListener("click", generateTable);