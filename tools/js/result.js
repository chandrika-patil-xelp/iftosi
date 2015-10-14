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
		
		FR(sortby);
		
		
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
    });

    $('.jshapeComm').bind('click', function() {
        $('body').animate({scrollTop: 280}, 300);
            var id=$(this).attr('id');
            $(this).toggleClass('shapeSelected');
                    id=id.toLowerCase()+"Li";
                    $('#'+id+' a').click();

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
    
    $('.prox').mouseover(function(){
        var img=$(this).css('background');
        $('.proxImg').css({'background':img});
        //console.log("rrr");
    });

});

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function getResultsData(data)
{
	var html = '';
	var fhtml = '';
	$.each(data.results.products, function(i, vl) {
		html += '<a href="'+DOMAIN+vl.attributes.certified.toLowerCase()+'-'+vl.attributes.shape.toLowerCase()+'-clarity-'+vl.attributes.clarity+'/pid-'+vl.pid+'">';
			html += '<div class="prdComm fLeft" style="opacity: 0; transform: translateX(1500px);">';
				html += '<div class="prdCommDiv fLeft">';
					html += '<div class="prdShape fLeft">';
						html += '<div class="prdShTitle fLeft fmOpenB">SHAPE</div>';
						html += '<div class="prdShType fLeft fmOpenR">'+vl.attributes.shape+'</div>';
						html += '<div class="'+vl.attributes.shape+' fRight"></div>';
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
							html += '<div class="detValue fmOpenR fLeft">'+vl.attributes.certified+'</div>';
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
	
	$.each(data.results.filters, function(i, v) {
		$.each(v, function(k, vl) {
			
			var dvl = vl.value.split(';');
			
			if(k == 'range')
			{
				fhtml += '<input type="hidden" id="'+vl.name+'RangeMin" value="'+dvl[0]+'">';
				fhtml += '<input type="hidden" id="'+vl.name+'RangeMax" value="'+dvl[1]+'">';
				fhtml += '<div id="'+vl.name+'Div" class="filterCont fLeft">';
					fhtml += '<div class="fLeft optionTitle fmOpenR">'+vl.dname+'</div>';
					fhtml += '<div class="fLeft rangeCont">';
						fhtml += '<div class="fLeft rangeDiv">';
							fhtml += '<input type="text" id="'+vl.name+'Range" class="fLeft rngInp" style="visibility: hidden;">';
						fhtml += '</div>';
					fhtml += '</div>';
				fhtml += '</div>';
			}
		});
	});
	
	//$('#filters').html(fhtml);
	
	/*$(".rngInp").each(function () {
		
		var id = $(this).attr('id');
		var min_price = $('#'+id+'Min').val()*1;
		var max_price = $('#'+id+'Max').val()*1;
		
		if((max_price - min_price) > 100)
			var step = '';
		else
			var step = 0.01;
		
		$(this).ionRangeSlider({
			type: "double",
			grid: true,
			min: min_price,
			max: max_price,
			from: min_price,
			to: max_price,
			decorate_both: false,
			prettify_separator: ",",
			force_edges: true,
			drag_interval: true,
			step: step,
			onFinish: function(data) {
				FR();
			}
		});
		
		$('.filterCont :input[type=checkbox]').each(function() {
			$(this).bind('click', function(event) {
				FR();
				if (event && $.isFunction(event.stopImmediatePropagation))
					event.stopImmediatePropagation();
				else 
					window.event.cancelBubble=true;
			});
		});
	}); */
	
	$('.prdResults').html(html);
	$('.prdResults').offset();
	$('body').animate({scrollTop: $('.shapesCont').offset().top+50}, 300);
	setTimeout(function(){
		showPrd();
	},10);
	
	$('#resultCount').numerator({
		toValue: data.results.total,
		delimiter: ',',
		onStart: function() {
			isStop = true;
		}
	});
}

function FR(sortby) {
	
	var slistarr = new Array();
	var clistarr = new Array();
	var tlistarr = new Array();
	
	var catid = $("#catid").val();
	
	var i = 0;
	$('.shapeComm').each(function() {
		if($(this).hasClass('shapeSelected')) {
			slistarr[i] = $(this).attr('id');
			i++;
		}
	});
	var slist = slistarr.join('|@|');
	
	var j=0;
	$('.filterCont').each(function() {
		var tempclistarr = new Array();
		var i = 0;
		var id = $(this).find('input:checked').parent().parent().attr('id');
		$(this).find('input:checked').each(function() {
			tempclistarr[i] = $(this).attr('id');
			i++;
		});
		if(tempclistarr.length)
		{
			clistarr[j] = id+'|~|'+tempclistarr.join('|@|');
			j++;
		}
	});
	var clist = clistarr.join('|$|');
	
	var i = 0;
	$('.filterCont .rangeCont :input[type=text]').each(function() {
		if($(this).val()) {
			//tlistarr[$(this).attr('id')] = $(this).val();
			tlistarr[i] = $(this).attr('id')+'|~|'+$(this).val();
			i++;
		}
	});
	var tlist = tlistarr.join('|$|');
	
	if(sortby)
	{
		var params = 'action=ajx&case=filter&catid='+catid+'&sortby='+sortby+'&slist='+slist+'&clist='+clist+'&tlist='+tlist;
		var URL = DOMAIN + "index.php";
		$.getJSON(URL, params, function(data) {
			getResultsData(data);   
		});
	}
	else
	{
		$('#drpinp').text('Best Match');
		var params = 'action=ajx&case=filter&catid='+catid+'&slist='+slist+'&clist='+clist+'&tlist='+tlist;
		var URL = DOMAIN + "index.php";
		$.getJSON(URL, params, function(data) {
			getResultsData(data);
		});
	}
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


//prdComm 

showPrd();
function showPrd() {
        $('.prdComm').each(function() {$(this).velocity({opacity: "0",translateX:50+"px"}, {duration: 0, delay: 0});});
        var time = 0;
        $('.prdComm').each(function() {
            $(this).velocity({opacity: "1", translateX: "0"}, {duration: 500, delay: time, easing: "ease-in-out"});
            time += 120;
        });
        setTimeout(function(){
            $('.prdComm').addClass('transition100');
        },time);
    
}

