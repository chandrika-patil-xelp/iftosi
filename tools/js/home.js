var scene;
var parallax;
var lastSc = 0;
var pw = $(window).width();
var isMobile = false;
if (pw < 768) {
    isMobile = true;
}


$(document).ready(function() {
    $('input').bind('click',function(){$(this).attr('readonly',false);});
    
    //$('.categoryCircle').velocity({scale: "0"}, {duration: 0, delay: 0});
    $('body').animate({scrollTop: 0});
    scene = document.getElementById('scene');
    parallax = new Parallax(scene);

    if (!isMobile) {
        setTimeout(function() {
            //showCategory();
        }, 10);
        showCategory();
    }else{
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
		
		var slistarr = new Array();
		var i = 0;
        $('.shapeComm').each(function() {
			if($(this).hasClass('shapeSelected')) {
				slistarr[i] = $(this).attr('id');
				i++;
			}
		});
		var slist = slistarr.join('|@|');
		var dlink = $('#dialink').attr("href").split('?');
		if(slist)
		{
			var dialink = dlink[0]+'?slist='+slist;
			$('#dialink').attr("href",dialink);
		}
		else{
			$('#dialink').attr("href",dlink[0]);
		}
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
	
	$('.categoryCircle').each(function() {
		$(this).velocity({scale: "1"}, {duration: 800, delay: 0, easing: 'spring'});
	});
	
    //$('#catDiamond').velocity({scale: "1"}, {duration: 800, delay: 0, easing: 'spring'});
    //$('#catJewellery').velocity({scale: "1"}, {duration: 800, delay: 150, easing: 'spring'});
    //$('#catBullion').velocity({scale: "1"}, {duration: 800, delay: 250, easing: 'spring'});
}

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