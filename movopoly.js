"use strict";

var currentLoop;

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
            $(".username").html(username);
            refreshCookies(username);
            selectGame();
         }
      } else {
         $("#no-name-dlg").dialog("open");
      }
   });
}

function selectGame() {
   reset();
   $("#title-bar").css("height",77);
   $("#banner-img").attr("src","movopoly_banner.png");
   $(".banner-text").css("display","inline");
   $("#select-game").show();
   let getPending = function() {
      $.getJSON("newgame.php", function(games) {
         $("#pending-games").empty();
         if (games.length > 0) {
            $("#pending-games").append("<h3>Games waiting to start:</h3>");
            for (let i=0; i < games.length; i++) {
               $("#pending-games").append(
                  "<button class=\"ui-button-inline pending-btn\" id=\"pending" + i +
                  "\">Join</button> " + games[i] + "<br>");
            }
            $(".pending-btn").button().click(function() {
               clearInterval(currentLoop);
               let index = $(this).attr("id").substr(7);
               waitGame(games[index]);
            });
         }
      });
   };
   getPending();
   currentLoop = setInterval(getPending, 1000);
   $("#go-new-btn").click(function() {
      clearInterval(currentLoop);
      newGame();
   });
}

function refreshCookies(username) {
   username = (typeof username === "string")? username : getCookie("username");
   const d = new Date();
   d.setTime(d.getTime() + 366*24*60*60*1000);
   let expires = d.toUTCString()
   document.cookie = "username=" + username + ";expires=" + expires;
   let hash = getCookie("hash");
   if (hash == "") {
      hash = $.md5(username + expires);
   }
   document.cookie = "hash=" + hash + ";expires=" + expires;
}

function newGame() {
   reset();
   $("#new-game, #cancel-btn, #start-btn").show();
   refreshCookies();

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
   $(".gamename").html(gameName);
   
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
