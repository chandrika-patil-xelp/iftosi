var scene;
var parallax;
var lastSc=0;
var pw=$(window).width();
var isMobile=false;
if(pw<768){
	isMobile=true;
}


$(document).ready(function() {
	//$('.categoryCircle').velocity({scale: "0"}, {duration: 0, delay: 0});
	$('body').animate({scrollTop:0});
	scene = document.getElementById('scene');
	parallax = new Parallax(scene);
	
	if(!isMobile){
		setTimeout(function(){
			//showCategory();
		},10);
		showCategory();
	}
	
	
	if(isMobile){
		$('.hPrdComm').height(pw+'px');
	}
	

	$(window).scroll(function(){
		var sc=$(window).scrollTop();
		$('#header,#logoTxt,#mMenuBtn').velocity("stop");
		$('.logo').velocity("stop");
		
		if(!isMobile){
			if(sc>10){
				$('#header').velocity({height: "60px",paddingTop:12+"px",paddingBottom:12+"px"},{duration:0, delay:0,easing:'swing'});
				$('#logoTxt').velocity({opacity:0},{duration:0, delay:0});
				$('.logo').velocity({top:20+"px",height:60+"px",width:60+"px"},{duration:0, delay:100});
			}else if(lastSc>sc){
				$('.logo').velocity({top:30+"px",height:100+"px",width:100+"px"},{duration:0, delay:0});
				$('#logoTxt').velocity({opacity:1},{duration:300, delay:400});
				$('#header').velocity({height: "100px",paddingTop:32+"px",paddingBottom:32+"px"},{duration:0, delay:0,easing:'swing'});
			} 
		}else{
			if(sc>10){
				$('.logo').velocity({top:20+"px",height:50+"px",width:50+"px"},{duration:0, delay:000});
				$('#logoTxt').velocity({opacity:0},{duration:0, delay:0});
				$('#header').velocity({height: "50px"},{duration:0, delay:000,easing:'easeOut'});
				$('#mMenuBtn').velocity({marginTop:0+"px"},{duration:0, delay:0});
			}else if(lastSc>sc){
				$('.logo').velocity({top:20+"px",height:80+"px",width:80+"px"},{duration:0, delay:0});
				$('#logoTxt').velocity({opacity:1},{duration:00, delay:000});
				$('#header').velocity({height: "60px"},{duration:0, delay:0,easing:'swing'});
				$('#mMenuBtn').velocity({marginTop:5+"px"},{duration:0, delay:0});
			} 
			
		}
		lastSc=sc;
	});
	
	if(!isMobile){
		$('.categoryCircle').mouseover(function(){
			$(this).velocity({boxShadowSpread:"10px", scale:"1.1"},{duration:150, delay:0});
		});
		$('.categoryCircle').mouseout(function(){
			$(this).velocity({boxShadowSpread:"0px", scale:"1.0"},{duration:150, delay:0});
		});
	}
	
	$('.shapeComm').bind('click',function(){
		$(this).toggleClass('shapeSelected');
	});
	
	$('.searchBtn ').bind('click',function(){
		$(this).addClass('sBtnActive');
	});
	
});


$(window).load(function(){
	if(!isMobile){
	   // $('.categoryCircle').velocity({scale: "0"}, {duration: 0, delay: 0});
	}
});


function showCategory(){
	$('#catDiamond').velocity({scale: "1"}, {duration: 800, delay: 0,easing:'spring'});
	$('#catJewellery').velocity({scale: "1"}, {duration: 800, delay: 150,easing:'spring'});
	$('#catBullion').velocity({scale: "1"}, {duration: 800, delay: 250,easing:'spring'});
}
