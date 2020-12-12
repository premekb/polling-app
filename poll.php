<?php
    include_once "header.php";
    require "php/polls_include.php";
    include "php/errors_include.php";
?>
    <article>
        <?php 
            generatePoll($_GET["id"]);
        ?>
    </article>
    <script src="scripts/poll.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    // Parts of the script taken from w3schools.
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    // Set the background and font color based on the stylesheet used.
    stylesheetName = document.styleSheets[0].href.split("/");
    stylesheetName = stylesheetName[stylesheetName.length - 1];
    if (stylesheetName == "styles_dark.css"){
        var bgColor = "#253342";
        var fontColor = "#FFFFFF";
    }

    else{
        var bgColor = "#FFFFFF";
        var fontColor = "#000000";
    }
    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php getGoogleChartData($_GET["id"]);?>);
        // Optional; add a title and set the width and height of the chart
        var options = {backgroundColor: bgColor, height: window.screen.height / 2, legend: {alignment: 'center', position: 'top', textStyle:{color: fontColor}}};

        // Display the chart inside the <div> element with id="piechart"
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
    // Redraws the chart on window resize to fit the screen resolution
    window.addEventListener("resize", drawChart);
    </script>
</body>
</html>

