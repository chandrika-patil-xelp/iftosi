var isOpen = false;
var lastSc=0;
var pw=$(window).width();
var ph=$(window).height();
var isMobile=false;
if(pw<768){
    isMobile=true; 
}

$(document).ready(function(){
    $('.thumbnil').click(function(){
        var img=$(this).css('background');
        $('#prdImage').css({'background':img});
    });
});