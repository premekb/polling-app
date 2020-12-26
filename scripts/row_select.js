/**
 * Running this script adds an option for the user to select how many rows should be displayed
 * one the index page. This feature is available only with enabled JS.
 */

var span = document.querySelector("span#span_bottom_nav");
span.innerHTML += "<label for='rows'>Rows</label><select name='rows' id='rows'><option value='25'>25</option><option value='50'>50</option><option value='100'>100</option><option value='250'>250</option><option value='1000'>1000</option></select>";




