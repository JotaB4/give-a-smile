// ==UserScript==
// @name         Reopen Pacman
// @namespace    reopenpacman
// @version      0.8
// @updateURL http://****.ru/pacman/js/repacman.js
// @downloadURL  http://****.ru/pacman/js/repacman.js
// @description  Pacman who checks for reopens
// @author       Lukoyanov, skype: avito_lukoyanov
// @include      https://****.ru/*
// @grant        GM_addStyle
// ==/UserScript==
//
//1027 - LF_Comp, 1002 - Vas_Comp
(function() {
    'use strict';
var niceIndicator = $("<div id='niceindicator'></div>").css({
 display:'table',
});
$(niceIndicator).on('click',function(){
  	window.open('****.ru/helpdesk?assigneeId='+window.localStorage.agentID+'&p=1&sortField=reactionTxtime&sortType=desc&statusId=6', '_blank');
});
var pacman = $("<div></div>").css({
 'display':'inline-block',
   'width': '0px',
   'height': '0px',
   'border-right': '20px solid transparent',
   'border-top': '20px solid yellow',
   'border-left': '20px solid yellow',
   'border-bottom': '20px solid yellow',
   'border-top-left-radius': '20px',
   'border-top-right-radius': '20px',
   'border-bottom-left-radius': '20px',
   'border-bottom-right-radius': '20px',
});


var circleContainer = $("<div></div>").css({
'vertical-align':'middle',
display:'table-cell',
});
var circle = $("<div></div>").css({
 background:'white',
 width:10,
 height:10,
 'border-radius':'50%',
 'margin':'2px',
});
$(circleContainer).append(circle);

function checkReopens(){
 $.get("https://****.ru/helpdesk/ticket/search?p=1&assigneeId="+window.localStorage.agentID+"&statusId%5B%5D=6",
  function(data){
   $("#niceindicator").empty();
   $(niceIndicator).append("<span><font color='grey'>["+data.count+"]</font></span>");
   $(niceIndicator).append(pacman);
   var oldTime = new Date();
   oldTime.setHours(oldTime.getHours()-1);
   var newCount = 0;
   $.each(data.tickets,function(){
       var updateTime = new Date(this.lastComment.createdTxtime);
  //it's a little verbose, but it will be easier to debug/read later
  var LF_Comp = false;
  var Vas_Comp = false;
  var isNew = false;
  if(this.tags.indexOf(1027)!=-1)

LF_Comp = true;
  if(this.tags.indexOf(1002)!=-1)

Vas_Comp = true;
if(updateTime>oldTime)
isNew = true;
       if(isNew && !LF_Comp && !Vas_Comp)
           newCount++;
   });
   for(var i = 0; i<newCount && i<10;i++){
    var anotherCircle = $(circleContainer).clone();
    $(niceIndicator).append(anotherCircle);
   }

   $("body > div.navbar.navbar-inverse.navbar-fixed-top > div > div").append(niceIndicator);
  });

}
checkReopens();
setInterval(checkReopens,15000);
//end-of-check-for-reopens

})();