var scene;
var parallax;
var lastSc = 0;
var pw = $(window).width();
var isMobile = false;
if (pw < 768) {
    isMobile = true;
}

$(document).ready(function() {
    //$('.categoryCircle').velocity({scale: "0"}, {duration: 0, delay: 0});
    $('body').animate({scrollTop: 0});
    scene = document.getElementById('scene');
    parallax = new Parallax(scene);

    if (!isMobile) {
        setTimeout(function() {
            //showCategory();
        }, 10);
        showCategory();
    }


    if (isMobile) {
        $('.hPrdComm').height(pw + 'px');
    }

    if (!isMobile) {
        $('.categoryCircle').mouseover(function() {
            $(this).velocity({boxShadowSpread: "10px", scale: "1.1"}, {duration: 150, delay: 0});
        });
        $('.categoryCircle').mouseout(function() {
            $(this).velocity({boxShadowSpread: "0px", scale: "1.0"}, {duration: 150, delay: 0});
        });
    }

    $('.shapeComm').bind('click', function() {
        $(this).toggleClass('shapeSelected');
    });

    $('.searchBtn ').bind('click', function() {
        $(this).addClass('sBtnActive');
    });

});


$(window).load(function() {
    if (!isMobile) {
        // $('.categoryCircle').velocity({scale: "0"}, {duration: 0, delay: 0});
    }
});


function showCategory() {
    $('#catDiamond').velocity({scale: "1"}, {duration: 800, delay: 0, easing: 'spring'});
    $('#catJewellery').velocity({scale: "1"}, {duration: 800, delay: 150, easing: 'spring'});
    $('#catBullion').velocity({scale: "1"}, {duration: 800, delay: 250, easing: 'spring'});
}