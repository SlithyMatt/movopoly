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
         if (username.includes("<") || (username.includes(">"))) {
            nameErrorDialog("Names cannot include &lt; or &gt; characters");
         } else {
            refreshCookie(username);
            selectGame();
         }
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

function refreshCookie(username) {
   username = (typeof username === "string")? username : getCookie("username");
   const d = new Date();
   d.setTime(d.getTime() + 366*24*60*60*1000);
   let expires = d.toUTCString()
   let hash = getCookie("hash");
   if (hash == "") {
      hash = $.md5(username + expires);
   }
   document.cookie = "username=" + username + ";hash=" + hash + ";expires=" + expires;
}

function newGame() {
   reset();
   $("#new-game, #cancel-btn, #start-btn").show();
   refreshCookie();

   $("#cancel-btn").click(function() {
      selectGame();
   });
   $("#start-btn").click(function() {
      let gameName = $("#game-name-txt").val();
      if (gameName != "") {
         $.post("newgame.php", {
            originatorName: getCookie("username"),
            originatorHash: getCookie("hash"),
            gameName: gameName
         }, function() {
            waitGame(gameName);
         }).fail(function(xhr) {
            if (xhr.status == 400) {
               nameErrorDialog("Name is invalid");
            } else if (xhr.status == 409) {
               nameErrorDialog("Name is already being used");
            } else {
               errorDialog(xhr);
            }
         });
      } else {
         $("#no-name-dlg").dialog("open");
      }
   })
}

function waitGame(gameName) {
   reset();

}

function errorDialog (xhr) {
   $("#error-code").html(xhr.status);
   $("#error-text").html(xhr.responseText)
   $("#error-dlg").dialog("open");
}

$(document).ready(function() {
   // init UI components
   reset();
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
      $(".username").html(username);
      $("#welcome-back, #yes-btn, #no-btn").show();
      $("#yes-btn").click(selectGame);
      $("#no-btn").click(newName);
   } else {
      newName();
   }
});
