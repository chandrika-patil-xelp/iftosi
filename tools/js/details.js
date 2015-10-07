var isOpen = false;
var lastSc=0;
var pw=$(window).width();
var ph=$(window).height();
var isMobile=false;
var input_selector = 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea, input[type=radio]';
if(pw<768){
    isMobile=true; 
}

$(document).ready(function(){
     setTimeout(function() {
        var samt = 100;
        if (isMobile) {
            samt = 450;
        }
        $('body').animate({scrollTop: samt}, 300);
    }, 100);

    
    $('.thumbnil').click(function(){
        var img=$(this).css('background');
        $('#prdImage').css({'background':img});
    });
    
    $('.imgThumbnil').click(function(){
        var img=$(this).css('background');
        $('.galleryImg').css({'background':img});
    });
   
    $('#galleryClose').click(function(){
       $('#imgGallery').addClass('dn');
    });
    
    $('#prdImage').click(function(){
       $('#imgGallery').removeClass('dn');
    });
    
        
    $(input_selector).bind('focus',function() {
        $(this).siblings('label, i').addClass('labelActive');
        $(this).addClass('brOrange');//.removeClass('brRed');
        $('.bsText').addClass('op0');
    });

    $(input_selector).bind('blur',function() {
        if ($(this).val().length === 0 && $(this).attr('placeholder') === undefined) {
            $(this).siblings('label, i').removeClass('labelActive');
            $(this).removeClass('brOrange brGreen');//.addClass('brRed');
        } else {
            $(this).removeClass(' ').addClass('brGreen');
        }
    });
    
    $('#dragTarget').click(function() {
        showLeftMenu(false);
    });
    $('#userForm,#loginDiv,#overlay').velocity({scale:0},{delay:0,duration:0});
    
    $('.iconCall,.iconMessage').click(function(){
        $('#overlay,#userForm').removeClass('dn');
        setTimeout(function(){
            $('#overlay').velocity({scale:1},{delay:0,duration:300,ease:'swing'});
            $('#userForm').velocity({scale:1},{delay:80,duration:300,ease:'swing'});
        },10);
    });
    
    $('#userCancel').bind('click',function(){
        $('#userForm').velocity({scale:0},{delay:0,ease:'swing'});
        $('#overlay').velocity({scale:0},{delay:100,ease:'swing'});
        setTimeout(function(){
            $('#overlay,#userForm').addClass('dn');
        },1010);
    });  
    
    
    
    
});


function showLeftMenu(flag) {
    if (flag) {
        $('#leftMenu').removeClass('leftTransit');
        $('body').addClass('pFixed');
        $('#dragTarget').css({width: '50%', left: '250px'});

    } else {
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