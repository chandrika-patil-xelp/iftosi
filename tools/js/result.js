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
       // $('body').animate({scrollTop: samt}, 300);
    }, 100);
    
    
    if(pw<1024 && pw>480){
        $('.fTitle').click(function(){
            $('#filters').toggleClass('transit-100X');
        });
    }

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
		
		var sortby = $(this).attr('id');
		var catid = $("#catid").val();
		
		var params = 'action=ajx&case=sortby&catid='+catid+'&sortby='+sortby;
		var URL = DOMAIN + "index.php";
		$.getJSON(URL, params, function(data) {
			var html = '';
			$.each(data.results.products, function(i, vl) {
				
				html += '<a href="'+DOMAIN+vl.attributes.certified.toLowerCase()+'-'+vl.attributes.shape.toLowerCase()+'-clarity-'+vl.attributes.clarity+'-carat-'+vl.attributes.carat+'/pid-'+vl.pid+'">';
					html += '<div class="prdComm fLeft transition100">';
						html += '<div class="prdCommDiv fLeft transition100">';
							html += '<div class="prdShape fLeft">';
								html += '<div class="prdShTitle fLeft fmOpenB">SHAPE</div>';
								html += '<div class="prdShType fLeft fmOpenR">'+vl.attributes.shape+'</div>';
								html += '<div class="Round fRight"></div>';
							html += '</div>';
							html += '<div class="prdDetails fLeft">';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">COLOR</div>';
									html += '<div class="detValue fmOpenR fLeft">'+vl.attributes.color+'</div>';
								html += '</div>';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">CARATS</div>';
									html += '<div class="detValue fmOpenR fLeft">'+vl.attributes.carat+'</div>';
								html += '</div>';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">CLARITY</div>';
									html += '<div class="detValue fmOpenR fLeft">'+vl.attributes.clarity+'</div>';
								html += '</div>';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">CERTIFICATE</div>';
									html += '<div class="detValue fmOpenR fLeft">'+vl.attributes.cno+'</div>';
								html += '</div>';
							html += '</div>';
							html += '<div class="prdPrice fLeft">';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">BEST PRICE</div>';
									html += '<div class="detValue fmOpenB fLeft"><span>₹</span>'+vl.pprice+'</div>';
								html += '</div>';
							html += '</div>';
							html += '<div class="prdActions fLeft">';
								html += '<div class="actionComm fLeft transition100 poR ripplelink"></div>';
								html += '<div class="actionComm fLeft transition100 poR ripplelink"></div>';
								html += '<div class="actionComm fLeft transition100 poR ripplelink"></div>';
								html += '<div class="actionComm fLeft transition100 poR ripplelink"></div>';
							html += '</div>';
						html += '</div>';
					html += '</div>';
				html += '</a>';
			});
			
			$('.prdResults').html(html);
			$('.prdResults').offset();
			$('body').animate({scrollTop: $('.shapesCont').offset().top+50}, 300);
		});

		
        isOpen = false;
        toggleDropDown(false);
    });

    $(document).click(function() {
        if (isOpen) {
            toggleDropDown(false);
        }
    });
    $('.shapeComm').bind('click', function() {
        $(this).toggleClass('shapeSelected');
		var cnt = getRandomInt(-500,500);
        totalCnt = (totalCnt*1)+cnt;
		
		FR();

        $('#resultCount').numerator({
            toValue: totalCnt,
            delimiter: ',',
            onStart: function() {
                isStop = true;
            }
        });
    });

    $('.jshapeComm').bind('click', function() {
        $('body').animate({scrollTop: 280}, 300);
            var id=$(this).attr('id');
            $(this).toggleClass('shapeSelected');
                    id=id.toLowerCase()+"Li";
                    $('#'+id+' a').click();
            console.log(id);
            if(addedFilters.indexOf(id)==-1){
                //addFiltters(id);
            }else{
                //removeFilters(id+"_Filters");
                addedFilters.pop(id);
            }
    });

    $('#resultCount').numerator({
        toValue: totalCnt,
        delimiter: ',',
        onStart: function() {
            isStop = true;
        }
    });
   
    $('#dragTarget').click(function() {
        showLeftMenu(false);
    });

});

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

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
	console.log(slistarr);
	console.log(clistarr);
	console.log(tlistarr);
	
	var params = 'action=ajx&case=filter&slist='+slist;
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
