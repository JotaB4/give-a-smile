// ==UserScript==
// @name         AutoUpdater
// @namespace    updater
// @version      0.4
// @description  updates the queue
// @author       lukoyanov
// @include      https://****.ru/helpdesk*
// @grant        GM_addStyle
// ==/UserScript==

(function() {
    'use strict';
$(".helpdesk-loading").hide();
var updateBtn = $("body > div.navbar.navbar-inverse.navbar-fixed-top > div > div > ul > li:nth-child(1) > a");
updateBtn.removeAttr("href");
var brandStr = "Авито";
function UpdOn(){
	localStorage.setItem("doUpdate",true);
	
}
function UpdOff(){
	localStorage.setItem("doUpdate",false);
	$(".navbar-brand:eq(1)").html(brandStr);
}

var doUpdate = localStorage.getItem("doUpdate");
if(doUpdate === null)
	UpdOff();
else{
	if(doUpdate==='true')
		UpdOn();
	else
		UpdOff();
}
updateBtn.click(function(){
	if(doUpdate==='true')
		UpdOff();
	else 
		UpdOn();
});
	

var i=0;
var brnLen = brandStr.length;
setInterval(function(){
	//i know it's lame.
	//setting width for popup:
	$(".popover-visible_large").css({"min-width": "800px"});
	//
	var pos = $(window).scrollTop();
	doUpdate = localStorage.getItem("doUpdate");
	
	if(doUpdate === 'true'){
		i =i%brnLen; 
		$(".navbar-brand:eq(1)").html(brandStr.slice(0,i)+"<font color='skyblue'>"+brandStr.slice(i,i+1)+"</font>"+brandStr.slice(i+1,brnLen));
		i++;
		var findButtonSl = "body > div.container-fluid > section > article > div > div.row > div:nth-child(2) > div.container-fluid > div > div.helpdesk-main-section.col-xs-12 > form > div > div.pull-right > button.btn.btn-primary";
		if($(findButtonSl).length > 0)
        	$(findButtonSl).click();
		$(window).scrollTop(pos);
	}
                                 
},1000);
})();