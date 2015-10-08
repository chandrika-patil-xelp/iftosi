var isOpen = false;
var isStop = false;
var pw = $(window).width();
var ph = $(window).height();
var isMobile = false;
if (pw < 768) {
    isMobile = true;
}


$(document).ready(function() {
    setTimeout(function() {
        var samt = 100;
        if (isMobile) {
            samt = 450;
        }
        $('body').animate({scrollTop: samt}, 300);
    }, 100);


    $('#drpinp').click(function() {
        setTimeout(function() {
            isOpen = true;
        }, 150);
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
    var count = 255;
    $('.shapeComm').bind('click', function() {
        $(this).toggleClass('shapeSelected');
        count += 135;
		
		FR();

        $('#resultCount').numerator({
            toValue: count,
            delimiter: ',',
            onStart: function() {
                isStop = true;
            }
        });
    });

    $('.jshapeComm').bind('click', function() {
        var len=$('.jshapeComm.shapeSelected').size();
        $('body').animate({scrollTop: 280}, 300);
       // if(len<3){
            var id=$(this).attr('id');
            $(this).toggleClass('shapeSelected');
            if(addedFilters.indexOf(id)==-1){
                addFiltters(id);
            }else{
                removeFilters(id+"_Filters");
                addedFilters.pop(id);
            }
        //}
        
    });

    $('#resultCount').numerator({
        toValue: 2835,
        delimiter: ',',
        onStart: function() {
            isStop = true;
        }
    });
   
    $('#dragTarget').click(function() {
        showLeftMenu(false);
    });

});

function FR() {
	var slistarr = new Array();
	var clistarr = new Array();
	var tlistarr = new Array();
	var i = 0;
	$('.shapeComm').each(function() {
		if($(this).hasClass('shapeSelected')) {
			slistarr[i] = $(this).attr('id');
			i++;
		}
	});
	var slist = slistarr.join('|@|');
	
	$('.filterCont').each(function() {
		var tempclistarr = new Array();
		var i = 0;
		var id = $(this).find('input:checked').parent().parent().attr('id');
		$(this).find('input:checked').each(function() {
			tempclistarr[i] = $(this).attr('id');
			i++;
		});
		if(tempclistarr.length)
			clistarr[id] = tempclistarr;
	});
	
	var i = 0;
	$('.filterCont .rangeCont :input[type=text]').each(function() {
		if($(this).val()) {
			tlistarr[$(this).attr('id')] = $(this).val();
		}
	});
	console.log(tlistarr);
	
	var params = 'action=ajx&type=filter&shape='+slist;
	var URL = DOMAIN + "index.php";
	$.getJSON(URL, params, function(data) {
		
	});
}

var areas = new Array("Jakkur", "Judicial Layout", "M.G Road", "Indiranagar");



function toggleDropDown(flag) {
    if (flag) {
        $("#dropList").velocity({opacity: 1, borderRadius: 0}, {duration: 200, display: "block"});
    }
    else {
        $("#dropList").velocity({opacity: 0, borderRadius: '100%'}, {duration: 50, display: "none"});
    }

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


var ele = document.getElementById('dragTarget');
var mc = new Hammer(ele);
mc.on("panleft panright tap press", function(ev) {
    //ele.textContent = ev.type +" gesture detected.";
    if (ev.type == 'panright') {
        showLeftMenu(true);
    }
    else if (ev.type == 'panleft') {
        showLeftMenu(false);
    }
});