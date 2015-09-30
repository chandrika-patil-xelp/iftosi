var lHt = $('#logoCont').height();
var tLFlag = true;
var doScroll = true;
var pw = $(window).width();
var ph = $(window).height();


$(document).ready(function() {
     $(".button-collapse").sideNav();
    $(".side-nav").removeClass("dn");
    scroll0();
    var position = $(window).scrollTop();
    var upcount = 0;
    $(window).scroll(function() {
        var isTouch = isTouchDevice();
        if (!isTouch) {
            if (doScroll) {
                $('#logoCont,#scene').removeClass('transition300');
                var scroll = $(window).scrollTop();
                if (scroll > position) {
                    upcount -= 3;
                    if ((upcount >= -480) && (upcount <= 0)) {
                        $('#logoCont').velocity({translateY: "" + upcount + "px"}, {duration: 100, easing: "linear"});
                    }
                }
                else if (scroll < position) {
                    upcount += 3;
                    if (upcount <= 0) {
                        //console.log("up "+upcount);
                        $("#logoCont,#scene").velocity("stop", true);
                        $('#logoCont').velocity({translateY: "" + upcount + "px"}, {duration: 100, easing: "linear"});
                    }
                }
                if (scroll == 0) {
                    upcount = 0;
                    $("#logoCont,#scene").velocity("stop", true);
                    $('#logoCont').velocity({translateY: "" + upcount + "px"}, {duration: 100, easing: "linear"});
                }
                position = scroll;
            }
        }
        if (tLFlag) {
            var scroll = $(window).scrollTop();
            if (scroll >= (lHt - 100)) {
                var lFlag = $('.logoTop').hasClass('logoTransit1');
                if (lFlag) {
                    $('.logoTop').removeClass('logoTransit1');
                    $('#logoCont').addClass('transition300');
                    $('#logoCont').addClass('op0');
                }
            } else if (scroll <= (lHt - 100)) {
                var lFlag = $('.logoTop').hasClass('logoTransit1');
                if (!lFlag) {
                    $('.logoTop').addClass('logoTransit1');
                    setTimeout(function() {
                        $('#logoCont').removeClass('op0');
                        setTimeout(function() {
                            $('#logoCont').removeClass('transition300');
                        }, 200);
                    }, 100);
                }
            }
        }
    });
 checkReleated();
});

function isTouchDevice() {
    return typeof window.ontouchstart !== 'undefined';
}

function scrollOPe(flag) {
    if (flag) {//open
        $('body').css({overflow: 'hidden'});
    } else {//close
        $('body').css({overflow: ''});
    }
}

String.prototype.ucwords = function() {
    str = this.toLowerCase();
    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
            function($1) {
                return $1.toUpperCase();
            });
};

function scroll0() {
    $('body').animate({scrollTop: 0}, 300, 'linear');
}


function checkReleated(){
//    var pw=$(window).width();
//    if(pw<960){
//        $('#sugg3,#sugg4').addClass('dn');
//    }else if(pw>980){
//        $('#sugg3,#sugg4').removeClass('dn');
//    }
    
}


$(window).resize(function(){
    //checkReleated(); 
});