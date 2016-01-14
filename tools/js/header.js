var lastSc = 0;
var pw = $(window).width();
var ph = $(window).height();
var isMobile = false;
var hisOpen = false;
if (pw < 481) {
    isMobile = true;
}

/*
 $(document).ready(function(){
 $(window).scroll(function() {
 return;
 var sc = $(window).scrollTop();
 if(pageName==='index' || pageName==='wishlist' ){
 var sc=$(window).scrollTop();
 $('#header,#logoTxt,#mMenuBtn').velocity("stop");
 $('.logo').velocity("stop");
 
 if(!isMobile){
 if(sc>10){
 $('#header').velocity({height: "60px",paddingTop:12+"px",paddingBottom:12+"px"},{duration:0, delay:0,easing:'swing'});
 $('#logoTxt').velocity({left:20+"px",bottom:20+"px"},{duration:0, delay:200});
 $('.logo').velocity({top:20+"px",height:60+"px",width:150+"px",backgroundPositionX:15 + "px"},{duration:0, delay:100});
 }else if(lastSc>sc){
 //                            $('.logo').velocity({top:30+"px",height:100+"px",width:100+"px"},{duration:0, delay:0});
 //                            $('#logoTxt').velocity({opacity:1},{duration:300, delay:400});
 $('.logo').velocity({top: 30 + "px", height: 100 + "px", width: 100 + "px", backgroundPositionX: '50%'}, {duration: 0, delay: 0});
 $('#logoTxt').velocity({left:0+"px",bottom:-30+"px"},{duration:300, delay:0});
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
 $('.logo').removeClass('lactive');
 $('#logoTxt').velocity({opacity:1},{duration:00, delay:000});
 $('#header').velocity({height: "60px"},{duration:0, delay:0,easing:'swing'});
 $('#mMenuBtn').velocity({marginTop:5+"px"},{duration:0, delay:0});
 } 
 
 }
 lastSc=sc;
 
 }
 if(pageName==='diamonds' || pageName==='diamond_details'  || pageName==='jewellery_details' || pageName==='bullion_details' || pageName==='jewellery' || pageName==='bullion'){
 $('#header,#hbgDiam,#hbgJewel,#hbgBull,#logoTxt').velocity("stop");
 $('.headCatCont,.logo').velocity("stop");
 if (!isMobile) {
 if (sc > 10) {
 $('#header').velocity({height: "60px",paddingTop:12+"px",paddingBottom:12+"px"},{duration:0, delay:0,easing:'swing'});
 $('#logoTxt').velocity({left:20+"px",bottom:20+"px"},{duration:0, delay:200});
 $('.logo').velocity({top:20+"px",height:60+"px",width:150+"px",backgroundPositionX:15 + "px"},{duration:0, delay:100});
 $('#logoTxt').css({color:'#fff'});
 // $('#header').velocity({height: "60px", paddingTop: 12 + "px", paddingBottom: 12 + "px"}, {duration: 0, delay: 0, easing: 'swing'});
 $('#hbgDiam,#hbgJewel,#hbgBull').velocity({backgroundPositionY: -60 + "px"}, {duration: 0, delay: 0, easing: 'swing'});
 $('.headCatCont').velocity({top: -50 + "px"}, {duration: 0, delay: 0, easing: 'swing'});
 $('.headCatCont').addClass('headCatActive');
 //  $('#logoTxt').velocity({opacity: 0}, {duration: 0, delay: 0});
 //  $('.logo').velocity({top: 20 + "px", height: 60 + "px", width: 60 + "px"}, {duration: 0, delay: 100});
 } else if (lastSc > sc) {
 //                    $('.logo').removeClass('lactive');
 $('.logo').velocity({top: 30 + "px", height: 100 + "px", width: 100 + "px", backgroundPositionX: '50%'}, {duration: 0, delay: 0});
 //$('#logoTxt').velocity({opacity: 1}, {duration: 300, delay: 400});
 if(pageName==='diamond_details'  || pageName==='jewellery_details' || pageName==='bullion_details'){
 $('#logoTxt').css({color:'#4d4d4d'});
 }
 $('#logoTxt').velocity({left:0+"px",bottom:-30+"px"},{duration:300, delay:0});
 $('#header').velocity({height: "100px", paddingTop: 32 + "px", paddingBottom: 32 + "px"}, {duration: 0, delay: 0, easing: 'swing'});
 $('#hbgDiam,#hbgJewel,#hbgBull').velocity({backgroundPositionY: 8 + "px"}, {duration: 0, delay: 0, easing: 'swing'});
 $('.headCatCont').velocity({top: -30 + "px"}, {duration: 0, delay: 0, easing: 'swing'});
 $('.headCatCont').removeClass('headCatActive');
 }
 } else {
 if (sc > 10) {
 $('.logo').velocity({top: 20 + "px", height: 50 + "px", width: 50 + "px"}, {duration: 0, delay: 000});
 $('#logoTxt').velocity({opacity: 0}, {duration: 0, delay: 0});
 $('#header').velocity({height: "50px"}, {duration: 0, delay: 000, easing: 'easeOut'});
 $('#mMenuBtn,#mFilterBtn').velocity({marginTop: 0 + "px"}, {duration: 0, delay: 0});
 } else if (lastSc > sc) {
 $('.logo').velocity({top: 20 + "px", height: 80 + "px", width: 80 + "px"}, {duration: 0, delay: 0});
 $('#logoTxt').velocity({opacity: 1}, {duration: 00, delay: 000});
 $('#header').velocity({height: "60px"}, {duration: 0, delay: 0, easing: 'swing'});
 $('#mMenuBtn,#mFilterBtn').velocity({marginTop: 5 + "px"}, {duration: 0, delay: 0});
 }
 }
 lastSc = sc;
 }
 });
 $('.headCat').bind('click', function() {
 $(this).addClass('headSelected');
 //headCA1
 var id = $(this).attr('id');
 if (id == 'hbgDiam') {
 $('.headCatCont').addClass('headCA1').removeClass('headCA2 headCA3');
 $('#hbgJewel,#hbgBull').removeClass('headSelected');
 }
 else if (id == 'hbgJewel') {
 $('.headCatCont').addClass('headCA2').removeClass('headCA1 headCA3');
 $('#hbgDiam,#hbgBull').removeClass('headSelected');
 }
 else if (id == 'hbgBull') {
 $('.headCatCont').addClass('headCA3').removeClass('headCA1 headCA2');
 $('#hbgDiam,#hbgJewel').removeClass('headSelected');
 }
 
 });
 $('#overlay').velocity({opacity:0},{delay:0,duration:0});
 $('#loginDiv').velocity({scale:0},{delay:0,duration:0});
 $('.signInUpTab,.iLogin').bind('click',function(){
 $('#overlay,#loginDiv').removeClass('dn');
 setTimeout(function(){
 $('#overlay').velocity({opacity:1},{delay:0,duration:300,ease:'swing'});
 $('#loginDiv').velocity({scale:1},{delay:80,duration:300,ease:'swing'});
 },10);
 
 });
 
 
 $('#lgCancel').bind('click',function(){
 $('#loginDiv').velocity({scale:0},{delay:0,ease:'swing'});
 $('#overlay').velocity({opacity:0},{delay:100,ease:'swing'});
 setTimeout(function(){
 $('#overlay,#loginDiv').addClass('dn');
 },1010);
 });
 
 /*$('.wishCount,.wishlist').click(function(){
 var URL=DOMAIN+"?case=wishlist";
 if(pageName!=='wishlist'){
 window.open(URL);
 }else{
 location.reload(true);
 }
 });*/

$(document).ready(function() {
        if(pageName == 'wishlist')
	{
		var isLoggedIn = customStorage.readFromStorage('isLoggedIn');
		if(isLoggedIn == 'false' || isLoggedIn == false || isLoggedIn == '' || isLoggedIn == null || isLoggedIn == undefined)
		{
			window.location.href = DOMAIN;
		}
	}

    $(window).scroll(function() {
        $('#header,#logoTxt,#mMenuBtn,#hbgDiam,#hbgJewel,#hbgBull').velocity("stop");
        $('.logo,.headCatCont,.headRight,.upperMenu').velocity("stop");
        var sc = $(window).scrollTop();
        if (sc > 10) {
            $('#header').velocity({height: "60px"}, {duration: 0, delay: 0, easing: 'swing'});
            $('.headCatCont').velocity({top: "-40px"}, {duration: 0, delay: 0, easing: 'swing'});
            $('.headRight').velocity({paddingTop: 12 + "px", paddingBottom: 12 + "px"}, {duration: 0, delay: 0, easing: 'swing'});
            $('#hbgDiam,#hbgJewel,#hbgBull').velocity({backgroundPositionX: 8 + "px", backgroundPositionY: -100 + "px"}, {duration: 0, delay: 0});
            $('#logoTxt').css({color: '#fff'});
            $('#logoTxt').velocity({left: 20 + "px", bottom: 20 + "px"}, {duration: 0, delay: 200});
            $('.logo').velocity({top: 20 + "px", height: 60 + "px", width: 150 + "px", backgroundPositionX: 15 + "px"}, {duration: 0, delay: 100});
            $('.upperMenu').removeClass('transition300');
            $('.upperMenu').velocity({top: 60 + "px"}, {duration: 100, delay: 0});
            setTimeout(function() {
               // $('.upperMenu').addClass('transition300');
            }, 110);


        } else if (lastSc > sc) {
            $('.logo').velocity({top: 30 + "px", height: 100 + "px", width: 100 + "px", backgroundPositionX: '50%'}, {duration: 0, delay: 0});
            $('#logoTxt').velocity({left: 0 + "px", bottom: -30 + "px"}, {duration: 300, delay: 0});

            if (pageName === 'diamond_details' || pageName === 'jewellery_details' || pageName === 'bullion_details' || pageName === 'diamond_shapes' || pageName === 'education_round'||pageName === 'education_pear'||pageName === 'education_oval'||pageName === 'education_emerald'||pageName === 'education_clarity'||pageName === 'education_asscher'||pageName === 'education_heart'||pageName === 'education_marq'||pageName === 'education_radiant'||pageName === 'e_certification'||pageName === 'e_certification1'||pageName === 'e_certification2'||pageName === 'e_carat_weight'||pageName === 'education_cushion'||pageName === 'education_princess'||pageName === 'e_color'||pageName === 'e_color1'||pageName === 'e_cut'||pageName === 'e_cut1'||pageName === 'e_cut2'||pageName === 'e_cut3'||pageName === 'e_cut4'||pageName === 'e_cut5'||pageName === 'e_shapes') {
                $('#logoTxt').css({color: '#4d4d4d'});
            }
            $('#header').velocity({height: "100px"}, {duration: 0, delay: 0, easing: 'swing'});
            $('.upperMenu').velocity({top: 100 + "px"}, {duration: 100, delay: 0, easing: 'swing'});
            //$('.upperMenu').addClass('transition300');

            $('.headCatCont').velocity({top: "0px"}, {duration: 0, delay: 0, easing: 'swing'});
            $('.headRight').velocity({paddingTop: 32 + "px", paddingBottom: 32 + "px"}, {duration: 0, delay: 0, easing: 'swing'});
            $('#hbgDiam,#hbgJewel,#hbgBull').velocity({backgroundPositionX: 8 + "px", backgroundPositionY: 0 + "px"}, {duration: 0, delay: 0});

        }
        lastSc = sc;


    });


    $('#hdrpinp,div.loggedIn').click(function() {
		if(hisOpen)
		{
			hisOpen = false;
			htoggleDropDown(false);
		}
		else
		{
			setTimeout(function() {
				hisOpen = true;
			}, 150);
			htoggleDropDown(true);
		}
    });

    $('#hdropList li').click(function() {
        var val = $(this).val();
        var text = $(this).text();
        //$('#drpinp').text(text);
        hisOpen = false;
        htoggleDropDown(false);
    });

//    $(document).click(function() {
//        if (hisOpen) {
//            hisOpen = false;
//            htoggleDropDown(false);
//        }
//    });

    /*$('#hdropList').mouseleave(function(){
        if (hisOpen) {
            htoggleDropDown(false);
        }
        
    });*/


//transform: translateY(50px);opacity: 0;

    $('#jwMenu').velocity({translateY:"-20px", borderRadius:"35%",opacity:"0"});
    var mInt;
    $('#hbgJewel').bind('mouseenter', function() {
        clearTimeout(mInt);
        mInt=setTimeout(function() {
        $('#hbgJewel').addClass('jwActive');
        $('#jwMenu').removeClass('dn');
            setTimeout(function() {
                $('#jwMenu').velocity('stop');
                //$('#jwMenu').removeClass('upTransit');
                $('#jwMenu').velocity({opacity:"1"},{delay:50,duration:150,easing:"swing"});
                $('#jwMenu').velocity({translateY:"0px",borderRadius:"0px"},{queue: false,delay:00,duration:400,easing:"swing"});

                }, 0);
        }, 100);
    });
    
    $('#hbgJewel').bind('mouseleave', function() {
        $(this).removeClass('jwActive');
        //$('#jwMenu').addClass('upTransit');
        $('#jwMenu').velocity({opacity:"0"},{delay:0,duration:0,easing:"swing"});
        $('#jwMenu').velocity({borderRadius:"35%",translateY:"-20px",},{queue: false,delay:0,duration:0,easing:"swing"});
        setTimeout(function() {
            $('#jwMenu').addClass('dn');
        }, 00);
    });

    var isVendor = customStorage.readFromStorage('is_vendor');
    var isLoggedIn = customStorage.readFromStorage('isLoggedIn');

	if(isVendor != '' && (isVendor == -1 || isVendor == "-1"))
	{
		isVendor = 0;
	}

    if (isLoggedIn == 'true' || isLoggedIn == true)
    {
        if (isVendor == 1 || isVendor == '1')
        {
            $('#prdHeader').removeClass('dn');
            $('#enqHeader').removeClass('dn');
        }
        else
        {
            $('#wishHeader').removeClass('dn');
        }
    }

});

function htoggleDropDown(flag) {
    if (flag) {
        $("#hdropList").velocity({opacity: 1, borderRadius: 0, top: '100%'}, {duration: 200, display: "block"});
    }
    else {
        $("#hdropList").velocity({opacity: 0, borderRadius: '100%', top: 0}, {duration: 50, display: "none"});
    }

}

function redirectToWishlist()
{
	var uid = customStorage.readFromStorage('userid');
	var isLoggedIn = customStorage.readFromStorage('isLoggedIn');

	if((isLoggedIn == true || isLoggedIn == 'true') && uid !== undefined && uid !== null && uid !== '')
	{
		window.location.href = DOMAIN + 'wishlist/' + uid;
	}
	else
	{
		common.showLoginForm();
	}
}