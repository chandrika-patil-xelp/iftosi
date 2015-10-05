var lastSc=0;
$(document).ready(function(){
    $(window).scroll(function() {
        var sc = $(window).scrollTop();
        if(pageName==='index'){
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

        }
        if(pageName==='results' || pageName==='product_details'){
            $('#header,#hbgDiam,#hbgJewel,#hbgBull,#logoTxt').velocity("stop");
            $('.headCatCont,.logo').velocity("stop");
            if (!isMobile) {
                if (sc > 10) {
                    $('#header').velocity({height: "60px", paddingTop: 12 + "px", paddingBottom: 12 + "px"}, {duration: 0, delay: 0, easing: 'swing'});
                    $('#hbgDiam,#hbgJewel,#hbgBull').velocity({backgroundPositionY: -60 + "px"}, {duration: 0, delay: 0, easing: 'swing'});
                    $('.headCatCont').velocity({top: -50 + "px"}, {duration: 0, delay: 0, easing: 'swing'});
                    $('.headCatCont').addClass('headCatActive');
                    $('#logoTxt').velocity({opacity: 0}, {duration: 0, delay: 0});
                    $('.logo').velocity({top: 20 + "px", height: 60 + "px", width: 60 + "px"}, {duration: 0, delay: 100});
                } else if (lastSc > sc) {
                    $('.logo').velocity({top: 30 + "px", height: 100 + "px", width: 100 + "px"}, {duration: 0, delay: 0});
                    $('#logoTxt').velocity({opacity: 1}, {duration: 300, delay: 400});
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
    
});