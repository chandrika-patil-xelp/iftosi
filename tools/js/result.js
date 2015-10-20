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
        $(this).toggleClass('shapeSelected');
		var cnt = getRandomInt(-500,500);
        totalCnt = (totalCnt*1)+cnt;
		var idsp = $(this).attr('id').split('_');
		$('#'+idsp[0]+' a').click();
		FR();
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
        $(this).parent().siblings('.proxImg').css({'background':img});
    });
	
	var inputField = 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea, input[type=radio]';
	
	$(inputField).bind('keyup focus', inputField, function(event) {
		/* Autocomplete code */
		if ($(this).attr('id') == 'txtjArea')
		{
			var params = 'action=ajx&type=auto&cases=cAuto&str=' + escape($(this).val());
			new Autosuggest($(this).val(), '#txtjArea', '#jasug', WEBROOT + "index.php", params, true, '', '', event);
		}
	});

});

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function getResultsData(data,sortby,showtree)
{
	var html = '';
	var fhtml = '';
	var tfhtml = '';
	if(data.results.products) {
		
		if(pageName == 'diamonds') {
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
									html += '<div class="detValue fmOpenB fLeft"><span>₹</span>'+number_format(vl.pprice,2)+'</div>';
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
		}
		else if(pageName == 'jewellery') {
			$.each(data.results.products, function(i, vl) {
				html += '<div class="prdComm fLeft jwRes transition100" style="opacity: 1; transform: translateX(0px);">';
					html += '<div class="prdCommDiv fLeft transition100">';
						html += '<div class="prdCommImg fLeft">';
							html += '<div class="for-7Upper">';
								html += '<div class="prox for-7" style="background: #fff url(../tools/img/product1/1.jpg)no-repeat;background-size: contain;background-position:center"></div>';
							html += '</div>';
							html += '<div class="for-7Lower">';
								html += '<div class="prox for-7" style="background: #fff url(../tools/img/product1/2.jpg)no-repeat;background-size: contain;background-position:center"></div>';
								html += '<div class="prox for-7" style="background: #fff url(../tools/img/product1/3.jpeg)no-repeat;background-size: contain;background-position:center"></div>';
								html += '<div class="prox for-7" style="background: #fff url(../tools/img/product1/4.jpg)no-repeat;background-size: contain;background-position:center"></div>';
								html += '<div class="prox for-7" style="background: #fff url(../tools/img/product1/5.jpg)no-repeat;background-size: contain;background-position:center"></div>';
								html += '<div class="prox for-7" style="background: #fff url(../tools/img/product1/6.jpg)no-repeat;background-size: contain;background-position:center"></div>';
								html += '<div class="prox for-7" style="background: #fff url(../tools/img/product1/7.jpg)no-repeat;background-size: contain;background-position:center"></div>';
							html += '</div>';
							html += '<div class="proxImg fLeft" style="background: url(http://localhost/iftosi/tools/img/product1/2.jpg) 50% 50% / contain no-repeat scroll padding-box border-box rgb(255, 255, 255);"></div>';
						html += '</div>';
						html += '<div class="prdDetails fLeft">';
							html += '<div class="detComm">';
								html += '<div class="detLabel fmOpenB fLeft">DESIGN NO.</div>';
								html += '<div class="detValue fmOpenR fLeft">18101</div>';
							html += '</div>';
						html += '</div>';
						html += '<div class="prdPrice fLeft">';
							html += '<div class="detComm">';
								html += '<div class="detLabel fmOpenB fLeft">PRICE</div>';
								html += '<div class="detValue fmOpenB fLeft"><span>₹</span>50,800</div>';
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
			});
		}
	}
	else
	{
		html += '<div class="noresDiv">';
			html += '<div class="noresults font25 fLeft">There are no results available for your search.</div>';
			html += '<div class="noresults font18 fLeft">Please try again.</div>';
		html += '</div>';
	}
	
	var treedata = data.results.treedata;
	treedata = '';
	if(treedata)
	{
		var thtml = '';
		var getdata = data.results.getdata.jlist;
		var gdarr = getdata.split('|@|');
		
		if($('#idlist').html().trim()) {
			
			$.each(treedata.subcat, function(i,v) {					
				for(var k=0;k<gdarr.length;k++)
				{
					var igdarr = gdarr[k].split('_');
					if(igdarr[0] == v.catid && $("#" + v.catid).length == 0)
					{
						thtml += '<li id="'+v.catid+'"><a>'+v.cat_name+'</a>';
							thtml += '<ul>';
								$.each(v.subcat, function(j,vl) {
									thtml += '<li>';
										thtml += '<a>';
											thtml += '<div class="checkDiv fLeft">';
												thtml += '<input type="checkbox" class="filled-in" id="'+vl.catid+'"/>';
												thtml += '<label for="'+vl.catid+'">'+vl.cat_name+'</label>';
											thtml += '</div>';
										thtml += '</a>';
									thtml += '</li>';
								});
							thtml += '</ul>';
						thtml += '</li>';
					}
				}
			});
			
			$('#mainCat').append(thtml);
		}
		else {
			
			thtml += '<div class="fLeft optionTitle fmOpenR">Category</div>';
			thtml += '<div id="wrapper" class="fLeft">';
				thtml += '<div class="tree transition300">';
					thtml += '<ul>';
						thtml += '<li><a>'+treedata.cat_name+'</a>';
							thtml += '<ul id="mainCat">';
								$.each(treedata.subcat, function(i,v) {
									
									for(var k=0;k<gdarr.length;k++)
									{
										var igdarr = gdarr[k].split('_');
										if(igdarr[0] == v.catid)
										{
											thtml += '<li id="'+v.catid+'"><a>'+v.cat_name+'</a>';
												thtml += '<ul>';
													$.each(v.subcat, function(j,vl) {
														thtml += '<li>';
															thtml += '<a>';
																thtml += '<div class="checkDiv fLeft">';
																	thtml += '<input type="checkbox" class="filled-in" id="'+vl.catid+'"/>';
																	thtml += '<label for="'+vl.catid+'">'+vl.cat_name+'</label>';
																thtml += '</div>';
															thtml += '</a>';
														thtml += '</li>';
													});
												thtml += '</ul>';
											thtml += '</li>';
										}
									}
								});
							thtml += '</ul>';
						thtml += '</li>';
					thtml += '</ul>';
				thtml += '</div>';
			thtml += '</div>';
			
			$('#idlist').html(thtml);
		}
		
		$('.tree li').each(function() {
            if ($(this).children('ul').length > 0) {
                $(this).addClass('parent');
            }
        });
        $('.tree li.parent > a').bind('click',function() {
            var id = $(this).parent().attr('id');
            $(this).parent().addClass('active');
            $(this).parent().children('ul').slideToggle('fast');
            lOpen = id;
        });
        $('.tree ul:first > li').eq(0).each(function() {
            $(this).addClass('active');
            $(this).children('ul').slideToggle('fast');
        });
		$('.jshapeComm').each(function() {
			var idsp = $(this).attr('id').split('_');
			$('#'+idsp[0]+' a').click();
		});
		$('.filterCont :input[type=checkbox]').each(function() {
			$(this).bind('click', function(event) {
				FR('',1);
				if (event && $.isFunction(event.stopImmediatePropagation))
					event.stopImmediatePropagation();
				else 
					window.event.cancelBubble=true;
			});
		});
	}
	//else
		//$('#idlist').html('');
	
	$.each(data.results.filters, function(i, v) {
		$.each(v, function(k, vl) {
			
			
			
			/* if(k == 'range')
			{
				var dvl = vl.value.split(';');
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
			} */
			if(k == 'checkbox')
			{
				var cidarr = new Array();
				var dvl = vl.ovalue.split(',');
				var dvl1 = vl.value.split(',');
				tfhtml = '';
				var jid = "#" + vl.name+'_'+vl.id;
				var k=0;
				$(jid).find('input:checked').each(function() {
					cidarr[k] = $(this).attr('id');
					k++;
				});
				
				for(var i=0;i<dvl.length;i++)
				{
					var a = dvl1.indexOf(dvl[i]);
					if(a == -1)
					{
						tfhtml += '<div class="checkDiv fLeft">';
							tfhtml += '<input type="checkbox" class="filled-in" disabled id="'+vl.name+'_'+dvl[i]+'" />';
							tfhtml += '<label for="'+vl.name+'_'+dvl[i]+'">'+dvl[i]+'</label>';
						tfhtml += '</div>';
					}
					else
					{
						tfhtml += '<div class="checkDiv fLeft">';
							tfhtml += '<input type="checkbox" class="filled-in" id="'+vl.name+'_'+dvl[i]+'" />';
							tfhtml += '<label for="'+vl.name+'_'+dvl[i]+'">'+dvl[i]+'</label>';
						tfhtml += '</div>';
					}
				}
				//$(jid).html(tfhtml);
				for(var i=0;i<cidarr.length;i++)
				{
					$('#'+cidarr[i]).attr('checked',true);
				}
				
				$(jid+' :input[type=checkbox]').each(function() {
					$(this).bind('click', function(event) {
						FR();
						if (event && $.isFunction(event.stopImmediatePropagation))
							event.stopImmediatePropagation();
						else 
							window.event.cancelBubble=true;
					});
				});
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
	if(sortby)
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

function number_format (number, decimals, dec_point, thousands_sep) {
  
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function (n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

function FR(sortby,showtree) {
	
	var slistarr = new Array();
	var jlistarr = new Array();
	var clistarr = new Array();
	var tlistarr = new Array();
	var ilistarr = new Array();
	
	var catid = $("#catid").val();
	
	var i = 0;
	$('.shapeComm').each(function() {
		if($(this).hasClass('shapeSelected')) {
			slistarr[i] = $(this).attr('id');
			i++;
		}
	});
	var slist = slistarr.join('|@|');
	
	var i = 0;
	$('.jshapeComm').each(function() {
		if($(this).hasClass('shapeSelected')) {
			jlistarr[i] = $(this).attr('id');
			i++;
		}
	});
	var jlist = jlistarr.join('|@|');
	
	var idlist = '';
	
	var j=0;
	var k = 0;
	$('.filterCont').each(function() {
		var tempclistarr = new Array();
		var tempilistarr = new Array();
		var i = 0;
		
		var id = $(this).find('input:checked').parent().parent().attr('id');
		if(typeof id === 'undefined')
		{
			$(this).find('input:checked').each(function() {
				tempilistarr[k] = $(this).attr('id');
				k++;
			});
		}
		else
		{
			$(this).find('input:checked').each(function() {
				tempclistarr[i] = $(this).attr('id');
				i++;
			});
		}
		if(tempclistarr.length)
		{
			clistarr[j] = id+'|~|'+tempclistarr.join('|@|');
			j++;
		}
		if(tempilistarr.length)
		{
			ilistarr[j] = tempilistarr.join('|@|');
			j++;
		}
		
	});
	var clist = clistarr.join('|$|');
	var ilist = ilistarr.join('|$|');
	
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
		var params = 'action=ajx&case=filter&catid='+catid+'&sortby='+sortby+'&slist='+slist+'&clist='+clist+'&tlist='+tlist+'&ilist='+ilist+'&jlist='+jlist;
		var URL = DOMAIN + "index.php";
		$.getJSON(URL, params, function(data) {
			getResultsData(data,sortby);   
		});
	}
	else
	{
		$('#drpinp').text('Best Match');
		var params = 'action=ajx&case=filter&catid='+catid+'&slist='+slist+'&clist='+clist+'&tlist='+tlist+'&ilist='+ilist+'&jlist='+jlist;
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

