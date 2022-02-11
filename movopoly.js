"use strict";

function reset() {
   $(".latent").hide().off("click");
   okCallback.empty();
   yesCallback.empty();
   noCallback.empty();
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
            $(".username").text(username);
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
   $(".gamename").empty();
   $("#title-bar").css("height",77);
   $("#banner-img").attr("src","images/movopoly_banner.png");
   $(".banner-text").css("display","inline");
   $("#select-game").show();
   var gameLoop;
   let getGames = function() {
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
               clearInterval(gameLoop);
               let index = $(this).attr("id").substr(7);
               let game = games[index];
               $.post("joingame.php", {
                  game: game,
                  name: getCookie("username"),
                  hash: getCookie("hash")
               }, function() {
                  waitGame(game);
               }).fail(function(xhr) {
                  errorDialog(xhr);
               });

            });
         }
      });
      $.getJSON("activegames.php", {
         hash: getCookie("hash")
      }, function(games) {
         $("#active-games").empty();
         if (games.length > 0) {
            $("#active-games").append("<h3>Active Games:</h3>");
            for (let i=0; i < games.length; i++) {
               $("#active-games").append(
                  "<button class=\"ui-button-inline active-btn\" id=\"active" + i +
                  "\">Play</button> " + games[i] + "<br>");
            }
            $(".active-btn").button().click(function() {
               clearInterval(gameLoop);
               let index = $(this).attr("id").substr(6);
               let gameName = games[index];
               $(".gamename").text(gameName);
               playGame(gameName);
            });
         }
      });
   };
   getGames();
   gameLoop = setInterval(getGames, 1000);
   // TODO in-progress list
   $("#go-new-btn").click(function() {
      clearInterval(gameLoop);
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
   $(".gamename").text(gameName);
   $("#waiting-room").show();
   let hash = getCookie("hash");
   var waitingLoop;
   $("#start-btn").click(function() {
      $.post("waitingroom.php", {
         action: "start",
         game: gameName,
         hash: hash
      }, function() {
         clearInterval(waitingLoop);
         playGame(gameName);
      }).fail(function(xhr) {
         errorDialog(xhr);
      });
   });
   let originator = "";
   $("#cancel-btn").click(function() {
      clearInterval(waitingLoop);
      if (originator == hash) {
         yesCallback.add(function () {
            $.post("waitingroom.php", {
               action: "cancel",
               game: gameName,
               hash: hash
            }, selectGame);
         });
         $("#cancel-game-dlg").dialog("open");
      } else {
         yesCallback.add(function () {
            $.post("waitingroom.php", {
               action: "leave",
               game: gameName,
               hash: hash
            }, selectGame);
         });
         $("#leave-game-dlg").dialog("open");
      }
   });
   let getWaitingRoom = function() {
      $.getJSON("waitingroom.php", {game: gameName}, function(status) {
         originator = status.originatorHash;
         if (status.started) {
            clearInterval(waitingLoop);
            playGame(gameName);
         } else {
            $("#waiting-players").empty();
            if (status.players.length == 0) {
               clearInterval(waitingLoop);
               okCallback.add(selectGame);
               $("#game-canceled-dlg").dialog("open");
               $("#cancel-btn, #start-btn").hide();
            } else {
               let txtColor = "FireBrick";
               for (let i in status.players) {
                  let player = status.players[i];
                  $("#waiting-players").append("<span style=\"color:" + txtColor + ";\">" + player + "</span><br>");
                  txtColor = "Black";
               }
               if ((status.originatorHash == hash) && (status.players.length >= 2)) {
                  $("#start-btn").show();
               } else {
                  $("#start-btn").hide();
               }
               $("#cancel-btn").show();
            }
         }
      });
   };
   getWaitingRoom();
   waitingLoop = setInterval(getWaitingRoom, 1000);

}

function playGame(gameName) {
   reset();
   let hash = getCookie("hash");
   var statusLoop;
   $("#props-btn").off("click").click(function() {
      clearInterval(statusLoop);
      viewProperties(gameName);
   });
   $("#game-space").show();
   $("#yes-btn").click(function() {
      $.post("endturn.php", {
         game: gameName,
         hash: hash
      }, function() {
         $("#yes-btn").hide();
      });
   });
   statusLoop = function() {
      $.getJSON("status.php", {
         game: gameName,
         hash: hash
      }, function(status) {
         $("#status-money").text(status.money);
         $("#money-effect").html(status.moneyEffect);
         $("#current-player").text(status.currentPlayer);
         $("#next-player").text(status.nextPlayer);
         $("#space-indicator").html("<h2>" + status.spaceName + "</h2><img src=\"images/"
            + status.spaceImage + "\">");
         switch (status.turnState) {
            case "roll":
               clearInterval(statusLoop);
               rollDialog(gameName, status.roll);
               okCallback.add(function() {
                  setInterval(statusLoop,1000);
                  okCallback.empty();
               });
               break;
            case "forSale":
               forSaleDialog(gameName);
               break;
            case "rent":
               rentDialog(gameName);
               break;
            case "paid":
               $("#game-info").html("You are renting this property.<br>Are you done with your turn?");
               $("#yes-btn").show();
            case "own":
               $("#game-info").html("You own this property.<br>Are you done with your turn?");
               $("#yes-btn").show();
               break;
            case "chance":
               changeDialog(gameName);
               $("#game-info").html("Are you done with your turn?");
               $("#yes-btn").show();
               break;
            case "go":
               $("#game-info").html("You collected your salary.<br>Are you done with your turn?");
               $("#yes-btn").show();
               break;
            default:
               $("#game-info").text("Waiting for your turn...");
         }
      });
   };
   statusLoop();
   setInterval(statusLoop,1000);

}

function rollDialog(gameName, roll) {
   if (!$("#roll-dlg").dialog("isOpen")) {
      $("#roll-msg").text("Tap the die to roll.");
      $("div.msg-dlg button").hide();
      $("#die-div").css("background-image","url(\"images/die" + roll + ".png\")").click(function() {
         $("#roll-msg").empty();
         let frames = 10;
         var dieFrameLoop;
         let finalRoll = 0;
         let space;
         let nextFrame = function() {
            if ((--frames <= 0) && (finalRoll != 0)) {
               clearInterval(dieFrameLoop);
               roll = finalRoll;
               $("#roll-msg").text("Move to " + space + "!");
               $("div.msg-dlg button").show();
            } else {
               roll = (Math.random() * 6) | 0 + 1
            }
            $("#die-div").css("background-image","url(\"images/die" + roll + ".png\")");
         };
         setInterval(nextFrame,200);
         $.getJSON("roll.php", {
            game: gameName,
            hash: getCookie("hash")
         }, function(response) {
            finalRoll = response.roll;
            space = response.space;
         });
      });
      $("#roll-dlg").dialog("open");
   }
}

function viewProperties(gameName) {
   reset();

}

function errorDialog (xhr) {
   $("#error-code").text(xhr.status);
   $("#error-text").html(xhr.responseText)
   $("#error-dlg").dialog("open");
}

function nameErrorDialog(reason) {
   $("#name-error-reason").text(reason);
   $("#name-error-dlg").dialog("open");
}

var okCallback = $.Callbacks();
var yesCallback = $.Callbacks();
var noCallback = $.Callbacks();

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
               okCallback.fire();
            }
         }
      ]
   });
   $(".yes-no-dlg").dialog({
      autoOpen: false,
      draggable: false,
      dialogClass: "no-close",
      buttons: [
         {
            text: "Yes",
            click: function() {
               $(this).dialog("close");
               yesCallback.fire();
            }
         },
         {
            text: "No",
            click: function() {
               $(this).dialog("close");
               noCallback.fire();
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
      $(".username").text(username);
      $("#welcome-back, #yes-btn, #no-btn").show();
      $("#yes-btn").click(selectGame);
      $("#no-btn").click(newName);
   } else {
      newName();
   }
});
