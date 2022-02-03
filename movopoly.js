"use strict";

function reset() {
   
}

function newName() {

}

function selectGame() {

}


$(document).ready(function() {
   // init UI components
   $("button").button();

   let username = getCookie("username");
   if (username != "") {
      $("#welcome-back, #yes-btn, #no-btn").addClass("revealed");
      $("#yes-btn").click(selectGame);
      $("#no-btn").click(newName);
   } else {
      newName();
   }
});
