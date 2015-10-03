var isOpen = false;
var lastSc=0;
var isStop = false;
var pw=$(window).width();
var ph=$(window).height();
var isMobile=false;
if(pw<768){
	isMobile=true; 
}


$(document).ready(function() {
	setTimeout(function(){
		var samt=100;
		if(isMobile){
			samt=450;
		}$('body').animate({scrollTop:samt},300);
	},100);
	
	
	$('#drpinp').click(function() {
		setTimeout(function(){
			isOpen = true;
		},150);
		toggleDropDown(true);
	});

	$('#dropList li').click(function() {
		var val = $(this).val();
		var text = $(this).text();
		$('#drpinp').text(text);
		isOpen = false;
		toggleDropDown(false);
	});

	$(document).click(function() {
		if (isOpen) {
			toggleDropDown(false);
		}
	});
	var count= 255;
	$('.shapeComm').bind('click',function(){
		$(this).toggleClass('shapeSelected');
		count+=135;
		
		$('#resultCount').numerator({
			toValue: count, 
			delimiter: ',', 
			onStart: function() {
					isStop = true;
			}
		});
		
	});
   
   
   $('#resultCount').numerator({
		toValue: 2835, 
		delimiter: ',', 
		onStart: function() {
				isStop = true;
		}
	});
	$('#mfFooter').bind('click',function(){
		if(!isMobile){
			alert('reset filter');
		}else{
			alert('apply filter');
		}
	});
   
   
	$('.headCat').bind('click',function(){
		$(this).addClass('headSelected');
		//headCA1
		var id=$(this).attr('id');
		if(id=='hbgDiam'){
			$('.headCatCont').addClass('headCA1').removeClass('headCA2 headCA3');
			$('#hbgJewel,#hbgBull').removeClass('headSelected');
		}
		else if(id=='hbgJewel'){
			$('.headCatCont').addClass('headCA2').removeClass('headCA1 headCA3');
			$('#hbgDiam,#hbgBull').removeClass('headSelected');
		}
		else if(id=='hbgBull'){
			$('.headCatCont').addClass('headCA3').removeClass('headCA1 headCA2');
			$('#hbgDiam,#hbgJewel').removeClass('headSelected');
		}
		
	});
	
	$(window).scroll(function(){
		var sc=$(window).scrollTop();
		$('#header,#hbgDiam,#hbgJewel,#hbgBull,#logoTxt').velocity("stop");
		$('.headCatCont,.logo').velocity("stop");
		if(!isMobile){
			if(sc>10){
				$('#header').velocity({height: "60px",paddingTop:12+"px",paddingBottom:12+"px"},{duration:0, delay:0,easing:'swing'});
				$('#hbgDiam,#hbgJewel,#hbgBull').velocity({backgroundPositionY:-60+"px"},{duration:0, delay:0,easing:'swing'});
				$('.headCatCont').velocity({top:-50+"px"},{duration:0, delay:0,easing:'swing'});
				$('.headCatCont').addClass('headCatActive');
				$('#logoTxt').velocity({opacity:0},{duration:0, delay:0});
				$('.logo').velocity({top:20+"px",height:60+"px",width:60+"px"},{duration:0, delay:100});
			}else if(lastSc>sc){
				$('.logo').velocity({top:30+"px",height:100+"px",width:100+"px"},{duration:0, delay:0});
				$('#logoTxt').velocity({opacity:1},{duration:300, delay:400});
				$('#header').velocity({height: "100px",paddingTop:32+"px",paddingBottom:32+"px"},{duration:0, delay:0,easing:'swing'});
				$('#hbgDiam,#hbgJewel,#hbgBull').velocity({backgroundPositionY:8+"px"},{duration:0, delay:0,easing:'swing'});
				$('.headCatCont').velocity({top:-30+"px"},{duration:0, delay:0,easing:'swing'});
				$('.headCatCont').removeClass('headCatActive');
			}
		}else{
			if(sc>10){
				$('.logo').velocity({top:20+"px",height:50+"px",width:50+"px"},{duration:0, delay:000});
				$('#logoTxt').velocity({opacity:0},{duration:0, delay:0});
				$('#header').velocity({height: "50px"},{duration:0, delay:000,easing:'easeOut'});
				$('#mMenuBtn,#mFilterBtn').velocity({marginTop:0+"px"},{duration:0, delay:0});
			}else if(lastSc>sc){
				$('.logo').velocity({top:20+"px",height:80+"px",width:80+"px"},{duration:0, delay:0});
				$('#logoTxt').velocity({opacity:1},{duration:00, delay:000});
				$('#header').velocity({height: "60px"},{duration:0, delay:0,easing:'swing'});
				$('#mMenuBtn,#mFilterBtn').velocity({marginTop:5+"px"},{duration:0, delay:0});
			}
		}
		lastSc=sc;
	});        
	var min_carats = "0.30";
	var max_carats = "20.11";
	var min_price = "700.00";
	var max_price = "9999.99";

	
	
	$("#priceRange").ionRangeSlider({
		type: "double",
		grid: true,
		min: min_price,
		max: max_price,
		from: min_price,
		to: max_price,
		prefix: "INR: ",
		postfix: " ",
		decorate_both: false,
		prettify_separator: ",",
		force_edges: true,
		drag_interval: true,
		onFinish: function (data) {
			
		}
	});
	
	
	$("#caratsRange").ionRangeSlider({
		type: "single",
		grid: true,
		min: 0.3,
		max: 20.11,
		from: 05,
		drag_interval: true,
		prefix: "carat: ",
		postfix: " ",
		decorate_both: false,
		force_edges: true,
		step: 0.01,
		onFinish: function (data) {
			
		}
	});
	
	$('#dragTarget').click(function(){
		showLeftMenu(false);
	});


}); 

var areas=new Array("Jakkur","Judicial Layout","M.G Road","Indiranagar");

function searchArea(val){
	$('#asug').removeClass('dn');
}

function setData(obj){
	var text=$(obj).text();
	$('#txtArea').val(text);
	$('#asug').addClass('dn');

}

function toggleDropDown(flag) {
	if (flag) {
		$("#dropList").velocity({opacity: 1, borderRadius: 0}, {duration: 200, display: "block"});
	}
	else {
		$("#dropList").velocity({opacity: 0, borderRadius: '100%'}, {duration: 50, display: "none"});
	}

}
var switchFlag=true;
function showSideNav(flag) {
	if (flag) {
		$('#filters').removeClass('transit-100X');
		switchFlag=false;   
	} else {
		$('#filters').addClass('transit-100X');
		switchFlag=true;
	}
}


function swichFilter(){
	showSideNav(switchFlag);
}

var styled = 0;
function removeStyle() {
	if (styled < 5) {
		$('#caratsDiv .irs-bar').css({background: 'none', border: 'none'});
		$('#caratsDiv .irs-bar-edge').css({background: 'none', border: 'none'});
		styled++;
	}

}
setTimeout(function() {
	removeStyle();
},500);

function showLeftMenu(flag){
	if(flag){
		$('#leftMenu').removeClass('leftTransit');
		$('body').addClass('pFixed');
		$('#dragTarget').css({width: '50%', left: '250px'});
		
	}else{
		$('#leftMenu').addClass('leftTransit');
		$('body').removeClass('pFixed');
		 $('#dragTarget').css({width: '20px', left: '0px'});
	}
}


var ele = document.getElementById('dragTarget');
var mc = new Hammer(ele);
mc.on("panleft panright tap press", function(ev) {
	//ele.textContent = ev.type +" gesture detected.";
	if (ev.type == 'panright') {
		showLeftMenu(true);
		console.log("right");
	}
	else if (ev.type == 'panleft') {
		showLeftMenu(false);
		console.log("left");
	}
});