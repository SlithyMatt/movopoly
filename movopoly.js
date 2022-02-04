"use strict";

function reset() {
   $(".latent").hide();
}

function newName() {
   reset();
   $("#new-user, #text-form").show();
   $("#submit-btn").click(function() {
      let username = $("#text-form-input").val();
      if (username != "") {
         const d = new Date();
         d.setTime(d.getTime() + 366*24*60*60*1000);
         document.cookie = "username=" + username + ";expires=" + d.toUTCString();
         selectGame();
      } else {
         $("#no-name-dlg").dialog("open");
      }
   });
}

function selectGame() {
   reset();
   $("#select-game").show();
   $("#go-new-btn").click(function() {
      newGame();
   });
}

function newGame() {
   reset();
   $("#new-game, #cancel-btn, #start-btn").show();

   $("#cancel-btn").click(function() {
      selectGame();
   });
   $("#start-btn").click(function() {
      // TODO attempt to start game
   })
}

$(document).ready(function() {
   // init UI components
   $("button").button();
   $(".msg-dlg").dialog({
      autoOpen: false,
      draggable: false,
      dialogClass: "no-close",
      buttons: [
         {
            text: "OK",
            click: function() {
               $(this).dialog("close");
            }
         }
      ]
   });
   $("#clear-btn").click(function() {
      $("#text-form-input").val("");
   });
   $("#text-form-input").keypress(function(event) {
      let keycode = (event.keyCode ? event.keyCode : event.which);
      if(keycode == '13') {
         $("#submit-btn").click();
      }
   });

   let username = getCookie("username");
   if (username != "") {
      $("#welcome-back, #yes-btn, #no-btn").show();
      $("#yes-btn").click(selectGame);
      $("#no-btn").click(newName);
   } else {
      newName();
   }
});
