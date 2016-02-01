var isOpen = false;
var isStop = false;
var pw = $(window).width();
var ph = $(window).height();
var isMobile = false;
var suggestObj = null;
if (pw < 768) {
    isMobile = true;
}

$(document).ready(function() {
	
	$(".wishTabComm").bind('click',function(){
		
		$(".wishTabComm").removeClass('sel');
		$(this).addClass('sel');
		var catid 	= $(this).attr("id");
		var pgno 	= 1;
		var uid 	= $('#uid').val();
		$('#catid').val(catid);
		showWish(catid,pgno,uid,1);
	});
	
	$(".wisgDel").bind('click',function(e){
		e.stopImmediatePropagation();
		var catid 	= $('#catid').val();
		var pgno 	= $('#pgno').val()*1;
		var uid 	= $('#uid').val();
		showWish(catid,pgno,uid,1,$(this).attr('id'));
	});
	
	
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
        }, 50);
        toggleDropDown(true);
    });

    $('#dropList li').click(function() {
        var val = $(this).val();
        var text = $(this).text();
        $('#drpinp').text(text);
		
		var sortby = $(this).attr('id');
		var catid = $("#catid").val();
		$('#sortbyvl').val(sortby);
                $('#pgno').val(1);
		FR(sortby);
		
        isOpen = false;
        toggleDropDown(false);
    });

//    $(document).click(function() {
//        if (isOpen) {
//           // toggleDropDown(false);
//        }
//    });
    
    $('#dropList').mouseleave(function(){
        
        if (isOpen) {
           toggleDropDown(false);
        }
        
    });
    
    
	
	if($('#slist').val())
	{
		var sla = $('#slist').val().split('|@|');
		$.each(sla,function(i,v){
			$('.'+v).addClass('shapeSelected');
		});
		$('#slist').val('');
	}
	
    $('.shapeComm').bind('click', function() {
        $(this).toggleClass('shapeSelected');
		var cnt = getRandomInt(-500,500);
        totalCnt = (totalCnt*1)+cnt;
        $('#pgno').val(1);
		FR();
    });

    $('.jshapeComm').bind('click', function() {
        $(this).toggleClass('shapeSelected');
		var cnt = getRandomInt(-500,500);
        totalCnt = (totalCnt*1)+cnt;
		var idsp = $(this).attr('id').split('_');
		$('#'+idsp[0]+' a').click();
                $('#pgno').val(1);
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
    
    /*$('.prox').mouseover(function()
	{
		var tmpClass = $(this).attr('class');
		tmpClass = tmpClass.split(' ');
		var actClassNm = 'for-1';
		for(var i = 0 ; i < tmpClass.length; i++)
		{
			if(tmpClass[i].indexOf('for-') !== -1)
			{
				actClassNm = tmpClass[i];
				break;
			}
		}

		var classInd = actClassNm.split('-');
		classInd = classInd[1];

		var img=$(this).css('background');
		if(classInd == 1 || classInd == 2 || classInd == 3 || classInd == 4 || classInd == 6 || classInd == 8 || classInd == 9 || classInd == 10 || classInd == 12)
		{
			$(this).siblings('.proxImg').css({'background':img});
		}
		else
		{
			$(this).parent().siblings('.proxImg').css({'background':img});
		}
    });*/
	
	var inputField = 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea, input[type=radio]';
	
	$(inputField).bind('keyup focus', inputField, function(event) {
		/* Autocomplete code */
		if ($(this).attr('id') == 'txtjArea')
		{
                    if(event.type.toLowerCase() != 'focus')
                    {
                        if($(this).val() == '')
                        {
                                $('#ctid').val('');
                                FR();
                        }
                        else
                        {
                                var params = 'action=ajx&case=auto&str=' + escape($(this).val());
                                new Autosuggest($(this).val(), '#txtjArea', '#jasug', DOMAIN + "index.php", params, false, '', '#ctid', event);
                        }
                    }
		}
                $(document).bind('click',function()
                {
                    $('#jasug').addClass('dn');
                });
	});
	
	$('.pgComm').bind('click', function() {
		if(!$(this).hasClass('pgActive'))
		{
			var pgval = $(this).text();
			$('#pgno').val(pgval);
			$('.pgComm').removeClass('pgActive');
			$(this).addClass('pgActive');
			var uid = $('#uid').val();
			if(pageName.indexOf("wishlist") !== -1)
			{
				if(typeof $('.wishTabComm') !== 'undefined')
				{
					$(".wishTabComm").each(function(){
						if($(this).hasClass('sel'))
						{
							showWish($(this).attr('id'),pgval,uid);
						}
					});
				}
			}
			else
                        {
                            if($('#sortbyvl').val())
                                FR($('#sortbyvl').val());
                            else
                                FR(1);
                        }
		}
	});

	$('.pPrev').bind('click', function() {
		var curpgno = parseInt($('#pgno').val());
		var pgval = curpgno - 1;
		if(curpgno > 1)
		{
			$('#pgno').val(pgval);
			var uid = $('#uid').val();
			if(pageName.indexOf("wishlist") !== -1)
			{
				if(typeof $('.wishTabComm') !== 'undefined')
				{
					$(".wishTabComm").each(function(){
						if($(this).hasClass('sel'))
						{
							showWish($(this).attr('id'),pgval,uid);
						}
					});
				}
			}
			else
			{
				FR(1);
			}
		}
	});

	$('.pNext').bind('click', function() {
		var curpgno = parseInt($('#pgno').val());
		var pgval = curpgno + 1;
		if(curpgno < parseInt($('#total_pageno').val()))
		{
			$('#pgno').val(pgval);
			var uid = $('#uid').val();
			if(pageName.indexOf("wishlist") !== -1)
			{
				if(typeof $('.wishTabComm') !== 'undefined')
				{
					$(".wishTabComm").each(function(){
						if($(this).hasClass('sel'))
						{
							showWish($(this).attr('id'),pgval,uid);
						}
					});
				}
			}
			else
			{
				FR(1);
			}
		}
	});

	if(pageName == 'jewellery')
	{
		getImagesData(prdList);
	}
});

function getImagesData(prdList)
{
	if(prdList !== undefined && prdList !== null && prdList !== '' && typeof prdList !== 'undefined' && prdList !== 'undefined' && prdList !== 'null')
	{
		var params 	= 'action=ajx&case=getImages&prdIds=' + encodeURIComponent(prdList);
		var URL 	= DOMAIN + "index.php";
		$.getJSON(URL, params, function(data)
		{
			if(data !== undefined && data !== null && data !== '' && data !== 'undefined' && data !== 'null'&& typeof data !== 'undefined')
			{
				showImages(data);
			}
		});
	}
}

function showImages(data)
{
	var vid = customStorage.readFromStorage('userid');
	var newVid = '';
	var imgArr = new Array();
	var k = 0;
	if(data.error.code == 0)
	{
		$.each(data.results, function(i, val) {
			$.each(val.images, function(j, vl){
				if(vl.active_flag === '0' || vl.active_flag === 0)
				{
					if(val.vid == vid)
					{
						imgArr[k] = vl.image;
					}
				}
				else
				{
					imgArr[k] = vl.image;
				}
				k++;
			});

			var imgHtml = getImageData(imgArr, 1);
			$('#' + i + '_imgs').html(imgHtml);
			imgArr = new Array();
			k = 0;
		});
	}
}

function showWish(catid,pgno,uid,nojump,pid)
{
	if(typeof pid === 'undefined')
		pid = '';
	if(!nojump)
		$('html,body').animate({scrollTop: $('.wishTabsCont').offset().top-60}, 300);
	
	var params 	= 'action=ajx&case=filter&catid='+catid+'&pgno='+pgno+"&uid="+uid+"&pid="+pid;
	var URL 	= DOMAIN + "index.php";
	$.getJSON(URL, params, function(data) {
		
		
		if(data.results.length == 0)
			data.results.total = 0;
		if(data.results.total === null)
			data.results.total = 0;
		$('#count_'+catid).text('('+data.results.total+')');
		
		if(catid == 10000)
			pageName = 'wishlist-diamonds';
		else if(catid == 10001)
			pageName = 'wishlist-jewellery';
		else
			pageName = 'wishlist-bullion';
		getResultsData(data);
                
                var params = 'action=ajx&case=getWishListCount&userid='+encodeURIComponent(uid);
                var URL = DOMAIN + "index.php";
                $.get(URL, params, function(data)
                {
                    if(data !== undefined && data !== null && data !== '')
                    {
                        $('#wishListCnt').html(' (' + data + ')');
                    }
                    else
                    {
                        $('#wishListCnt').html(' (' + 0 + ')');
                    }
                });
                //$('#wishListCnt').html(data.results.total);
		$(".wisgDel").bind('click',function(e){
			e.stopImmediatePropagation();
			var catid 	= $('#catid').val();
			var pgno 	= $('#pgno').val()*1;
			var uid 	= $('#uid').val();
                        //$('#wishListCnt').html(data.results.total);
			showWish(catid,pgno,uid,1,$(this).attr('id'));
		});
	});
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function arrangeData(adata, id, divHolder, nextxt)
{
    if (adata.results)
    {
		var html = '<ul>';
        $.each(adata.results, function(i, vl) {
            if (id == '#txtjArea')
			{
				var dtval = (vl.city) ? vl.name+' ('+vl.city+')' : vl.name;
				var dtid = (vl.city) ? vl.id+'_area' : vl.id;
				html += '<li class="autoSuggstions transition300 txtCap" onmousedown="setAutoData(\'' + dtid + '\',\'' + vl.name + '\',\'' + id + '\',\'' + divHolder + '\',\'\',\'#ctid\');" id="' + dtid + '">'+dtval+'</li>';
			}
        });
        html += '</ul>';
        return html;
    }
    else
        return '';
}

function makeCall(id, cid) {
	$('#pgno').val(1);
	FR();
	$('html,body').animate({scrollTop: $('.allShapes').offset().top-60}, 300);
}

function getResultsData(data,sortby,showtree)
{
	var html = '';
	var fhtml = '';
	var tfhtml = '';
	var prdIds = '';
	if(data.results.products) {
        if(dummyPage == 'b2bproducts') {
			$.each(data.results.products, function(i, vl) {
				html += '<div class="prdComm fLeft" style="opacity: 0; transform: translateX(1500px);">';
					html += '<div class="prdCommDiv fLeft">';
                        html += '<a href="'+DOMAIN+'index.php?case=b2bdetails&productid='+vl.pid+'">';
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
                
//						html += '<div class="prdPrice fLeft">';
//							html += '<div class="detComm">';
//								html += '<div class="detLabel fmOpenB fLeft">BEST PRICE</div>';
//                              if(vl.dollar_rate !== '0.00' && vl.dollar_rate !== undefined && vl.dollar_rate !== null && vl.dollarValue !== '') {
//                                  dollarValue=vl.dollar_rate; 
//                              }
//                              var price = Math.ceil(((vl.pprice * dollarValue) * vl.attributes.carat));
//								html += '<div class="detValue fmOpenB fLeft"><span>&#8377;</span>'+ common.IND_money_format(price) +'</div>';
//							html += '</div>';
//						html += '</div>';
                
                        html += '<div class="b2bPricesCont fLeft">';   
                            html += '<div class="prdPrice fLeft">';
                                html += '<div class="detComm">';
                                    html += '<div class="detLabel fmOpenB fLeft">CUST. PRICE</div>';
                                    if(vl.dollar_rate !== '0.00' && vl.dollar_rate !== undefined && vl.dollar_rate !== null && vl.dollarValue !== '') {
                                        dollarValue=vl.dollar_rate; 
                                    }
                                    var price = Math.ceil(((vl.pprice * dollarValue) * vl.attributes.carat));
                                    html += '<div class="detValue fmOpenB fLeft"><span>₹</span>'+ common.IND_money_format(price) +'</div>';
                                html += '</div>';
                            html += '</div>';
                            html += '<div class="prdPrice fLeft">';
                                html += '<div class="detComm">';
                                    html += '<div class="detLabel fmOpenB fLeft">B2B PRICE</div>';
                                        if(vl.dollar_rate !== '0.00' && vl.dollar_rate !== undefined && vl.dollar_rate !== null && vl.dollarValue !== '') {
                                        dollarValue=vl.dollar_rate; 
                                    }
                                    var price = Math.ceil(((vl.b2bprice * dollarValue) * vl.attributes.carat));
                                    price = common.IND_money_format(price);
                                    if(price == '0' || price == 0 || price == 0.00 || price == '0.00')
                                    { price = 'N/A'; }
                                    html += '<div class="detValue fmOpenB fLeft"><span>₹</span>'+ price +'</div>';
                                html += '</div>';
                            html += '</div>';
                        html += '</div>';
                
						html += '</a>';
                
                        html += '<div class="prdActions fLeft wAuto">';
                            html += '<a href="'+DOMAIN+'index.php?case=b2bdetails&productid='+vl.pid+'&popup=1">';
                                html += '<div class="wConBtn fLeft">Contact Dealer</div>';
                            html += '</a>';
                        html += '</div>';
                
//						html += '<div class="prdActions fLeft">';
//                                                if(tempUrl !== '')
//                                                {
//                                                	html += '<a href="'+DOMAIN+tempUrl+'/did-'+vl.pid+'/1" class="actionComm fLeft transition100 poR ripplelink"></a>';
//							html += '<a href="'+DOMAIN+tempUrl+'/did-'+vl.pid+'/2" class="actionComm fLeft transition100 poR ripplelink"></a>';
//							html += '<a href="'+DOMAIN+tempUrl+'/did-'+vl.pid+'/3" class="actionComm fLeft transition100 poR ripplelink"></a>';
//							html += '<a href="'+DOMAIN+tempUrl+'/did-'+vl.pid+'" class="actionComm fLeft transition100 poR ripplelink"></a>';
//                                                }
//                                                else
//                                                {
//                                                    html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'">';
//                                                    	html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'/1" class="actionComm fLeft transition100 poR ripplelink"></a>';
//							html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'/2" class="actionComm fLeft transition100 poR ripplelink"></a>';
//							html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'/3" class="actionComm fLeft transition100 poR ripplelink"></a>';
//							html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'" class="actionComm fLeft transition100 poR ripplelink"></a>';
//                                                }
//						
//						html += '</div>';
                
					html += '</div>';
				html += '</div>';
			});
		}
        else if(pageName == 'diamonds') {
            $.each(data.results.products, function(i, vl) {
				html += '<div class="prdComm fLeft" style="opacity: 0; transform: translateX(1500px);">';
                html += '<div class="prdCommDiv fLeft">';                 
                var tempUrl = '';
                var shape = vl.attributes.shape;
                var color = vl.attributes.color;
                var clarity = vl.attributes.clarity;
                var cert = vl.attributes.certified;

                if(shape !== null && shape !== undefined) {
                    if(tempUrl !== ''){
                        tempUrl += '-'+shape; 
                    }
                    else{
                        tempUrl += shape;
                    }
                }
                if(color !== null && color !== undefined) {
                    if(tempUrl !== ''){
                        tempUrl += '-colour-'+color; 
                    }
                    else{
                        tempUrl += 'colour-'+color;
                    }
                }
                if(clarity !== null && clarity !== undefined) {
                    if(tempUrl !== ''){
                        tempUrl += '-clarity-'+clarity; 
                    }
                    else{
                        tempUrl += 'clarity-'+clarity;
                    }
                }
                if(cert !== null && cert !== undefined) {
                    if(tempUrl !== ''){
                        tempUrl += '-certified-'+cert; 
                    }
                    else{
                        tempUrl += 'certified-'+cert;
                    }
                }
                if(tempUrl !== '') {
                    html += '<a href="'+DOMAIN+tempUrl+'/did-'+vl.pid+'">';
                }
                else {
                    html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'">';
                }                                        

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
                
                if(vl.dollar_rate !== '0.00' && vl.dollar_rate !== undefined && vl.dollar_rate !== null && vl.dollarValue !== '') { 
                    dollarValue=vl.dollar_rate; 
                }
                
                var price = Math.ceil(((vl.pprice * dollarValue) * vl.attributes.carat));
                html += '<div class="detValue fmOpenB fLeft"><span>&#8377;</span>'+ common.IND_money_format(price) +'</div>';
                html += '</div>';
                html += '</div>';
                html += '</a>';
                html += '<div class="prdActions fLeft">';
                if(tempUrl !== '') {
                    html += '<a href="'+DOMAIN+tempUrl+'/did-'+vl.pid+'/1" class="actionComm fLeft transition100 poR ripplelink"></a>';
                    html += '<a href="'+DOMAIN+tempUrl+'/did-'+vl.pid+'/2" class="actionComm fLeft transition100 poR ripplelink"></a>';
                    html += '<a href="'+DOMAIN+tempUrl+'/did-'+vl.pid+'/3" class="actionComm fLeft transition100 poR ripplelink"></a>';
                    html += '<a href="'+DOMAIN+tempUrl+'/did-'+vl.pid+'" class="actionComm fLeft transition100 poR ripplelink"></a>';
                }
                else {
                    html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'">';
                    html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'/1" class="actionComm fLeft transition100 poR ripplelink"></a>';
                    html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'/2" class="actionComm fLeft transition100 poR ripplelink"></a>';
                    html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'/3" class="actionComm fLeft transition100 poR ripplelink"></a>';
                    html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'" class="actionComm fLeft transition100 poR ripplelink"></a>';
                }

                html += '</div>';
                html += '</div>';
                html += '</div>';
            });
        }
		else if(pageName == 'jewellery') {
			
			$.each(data.results.products, function(i, vl) {
				
				var price = Math.ceil(vl.attributes.price);
				if(prdIds !== '')
				{
					prdIds += "," + vl.pid;
				}
				else
				{
					prdIds = vl.pid;
				}
                                
					html += '<div class="prdComm fLeft jwRes transition100" style="opacity: 1; transform: translateX(0px);">';
						html += '<div class="prdCommDiv fLeft transition100">';
                                                
                                                var tempUrl = '';
                                                var metal = vl.attributes.metal;
                                                var shape = vl.attributes.shape;
                                                var purity = parseInt(vl.attributes.gold_purity);
                                                var weight = parseInt(vl.attributes.gold_weight);
                                                var cert = vl.attributes.certified;
                                                
                                                if(metal !== null && metal !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+metal; 
                                                    }
                                                    else{
                                                        tempUrl += metal;
                                                    }
                                                }
                                                if(shape !== null && shape !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+shape; 
                                                    }
                                                    else{
                                                        tempUrl += shape;
                                                    }
                                                }
                                                if(purity !== null && purity !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+purity+'-Carat'; 
                                                    }
                                                    else{
                                                        tempUrl += purity+'-Carat';
                                                    }
                                                }
                                                if(weight !== null && weight !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+weight+'-Grams'; 
                                                    }
                                                    else{
                                                        tempUrl += weight+'-Grams';
                                                    }
                                                }
                                                if(cert !== null && cert !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+cert; 
                                                    }
                                                    else{
                                                        tempUrl += cert;
                                                    }
                                                }
                                                if(tempUrl !== '')
                                                {
                                                    html += '<a href="'+DOMAIN+tempUrl+'/jid-'+vl.pid+'">';
                                                }
                                                else
                                                {
                                                    html += '<a href="'+DOMAIN+cert+'/jid-'+vl.pid+'">';
                                                }
								html += '<div id="'+vl.pid+'_imgs" class="prdCommImg fLeft">';
									html += getImageData(vl.images);
								html += '</div>';
								html += '<div class="prdDetails fLeft">';
									html += '<div class="detComm">';
										html += '<div class="detLabel fmOpenB fLeft">DESIGN NO.</div>';
										html += '<div class="detValue fmOpenR fLeft">'+vl.pcode+'</div>';
									html += '</div>';
								html += '</div>';
								html += '<div class="prdPrice fLeft">';
									html += '<div class="detComm">';
										html += '<div class="detLabel fmOpenB fLeft">PRICE</div>';
										html += '<div class="detValue fmOpenB fLeft"><span>₹</span>'+ common.IND_money_format(price) +'</div>';
									html += '</div>';
								html += '</div>';
							html += '</a>';
							html += '<div class="prdActions fLeft">';
                                                        if(tempUrl !== '' )
                                                        {
                                                            html += '<a href="'+DOMAIN+tempUrl+'/jid-'+vl.pid+'/1" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                            html += '<a href="'+DOMAIN+tempUrl+'/jid-'+vl.pid+'/2" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                            html += '<a href="'+DOMAIN+tempUrl+'/jid-'+vl.pid+'/3" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                            html += '<a href="'+DOMAIN+tempUrl+'/jid-'+vl.pid+'" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                        }
                                                        else
                                                        {
                                                            html += '<a href="'+DOMAIN+cert+'/jid-'+vl.pid+'/1" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                            html += '<a href="'+DOMAIN+cert+'/jid-'+vl.pid+'/2" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                            html += '<a href="'+DOMAIN+cert+'/jid-'+vl.pid+'/3" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                            html += '<a href="'+DOMAIN+cert+'/jid-'+vl.pid+'" class="actionComm fLeft transition100 poR ripplelink"></a>';                                                           
                                                        }
                                                        html += '</div>';
						html += '</div>';
					html += '</div>';
				
			});
			getImagesData(prdIds);
		}
		else if(pageName == 'wishlist-diamonds') {
			$.each(data.results.products, function(i, vl) {
				html += '<div class="prdComm fLeft transition100">';
					html += '<div class="wisgDel" id="'+vl.pid+'"></div>';
						html += '<div class="prdCommDiv fLeft transition100">';
                                                
                                                
                                                
                                                var tempUrl = '';
                                                var shape = vl.attributes.shape;
                                                var color = vl.attributes.color;
                                                var clarity = vl.attributes.clarity;
                                                var cert = vl.attributes.certified;
                                                
                                                if(shape !== null && shape !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+shape; 
                                                    }
                                                    else{
                                                        tempUrl += shape;
                                                    }
                                                }
                                                if(color !== null && color !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-colour-'+color; 
                                                    }
                                                    else{
                                                        tempUrl += 'colour-'+color;
                                                    }
                                                }
                                                if(clarity !== null && clarity !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-clarity-'+clarity; 
                                                    }
                                                    else{
                                                        tempUrl += 'clarity-'+clarity;
                                                    }
                                                }
                                                if(cert !== null && cert !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-certified-'+cert; 
                                                    }
                                                    else{
                                                        tempUrl += 'certified-'+cert;
                                                    }
                                                }
                                                if(tempUrl !== '')
                                                {
                                                    html += '<a href="'+DOMAIN+tempUrl+'/did-'+vl.pid+'">';
                                                }
                                                else
                                                {
                                                    html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'">';
                                                }
                                                
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
                                                                    if(vl.vdetail !== null && vl.vdetail.dollar_rate !== '0.00' && vl.vdetail.dollar_rate !== undefined && vl.vdetail.dollar_rate !== null && vl.vdetail.dollar_rate !== '') { dollarValue=vl.vdetail.dollar_rate; }
                                                                var price = Math.ceil(((vl.pprice * dollarValue) * vl.attributes.carat));
                                                                html += '<div class="detValue fmOpenB fLeft"><span>&#8377;</span>'+common.IND_money_format(price)+'</div>';
                                                        html += '</div>';
                                                html += '</div>';
                                                html += '</a>';
                                                html += '<div class="prdActions fLeft">';
                                                if(tempUrl !== '')
                                                {
                                                    html += '<a href="'+DOMAIN+tempUrl+'/did-'+vl.pid+'/1">';
                                                }
                                                else
                                                {
                                                    html += '<a href="'+DOMAIN+shape+'/did-'+vl.pid+'">';
                                                }
                                                html += '<div class="wConBtn fLeft">Contact Dealer</div>';
                                            html += '</a>';
                                        html += '</div>';
                                    html += '</div>';
                                html += '</div>';
                        });
		}
		else if(pageName == 'wishlist-bullion') {
			$.each(data.results.products, function(i, vl) {
				html += '<div class="prdComm fLeft transition100" style="opacity: 1; transform: translateX(0px);">';
					html += '<div class="wisgDel" id="'+vl.pid+'"></div>';
						html += '<div class="prdCommDiv fLeft transition100">';
                                                
                                                var tempUrl = '';
                                                var metal = vl.attributes.metal;
                                                var type = vl.attributes.type;
                                                var purity = parseInt(vl.attributes.gold_purity);
                                                var weight = parseInt(vl.attributes.gold_weight);
                                                
                                                if(metal !== null && metal !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+metal; 
                                                    }
                                                    else{
                                                        tempUrl += metal;
                                                    }
                                                }
                                                if(type !== null && type !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+type; 
                                                    }
                                                    else{
                                                        tempUrl += type;
                                                    }
                                                }
                                                if(purity !== null && purity !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+purity; 
                                                    }
                                                    else{
                                                        tempUrl += purity;
                                                    }
                                                }
                                                if(weight !== null && weight !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+weight+'-Grams'; 
                                                    }
                                                    else{
                                                        tempUrl += weight+'-Grams';
                                                    }
                                                }
                                                
                                                if(tempUrl !== '')
                                                {
                                                    html += '<a href="'+DOMAIN+tempUrl+'/bid-'+vl.pid+'">';
                                                }
                                                else
                                                {
                                                    html += '<a href="'+DOMAIN+type+'/bid-'+vl.pid+'">';
                                                }
                                            		html += '<div class="prdShape fLeft">';
								html += '<div class="prdShTitle fLeft fmOpenB">TYPE</div>';
								html += '<div class="prdShType fLeft fmOpenR">'+vl.attributes.type+'</div>';
								html += '<div class="'+vl.attributes.type.toLowerCase()+'Ic fRight"></div>';
							html += '</div>';
							html += '<div class="prdDetails fLeft">';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">METAL</div>';
									html += '<div class="detValue fmOpenR fLeft">'+vl.attributes.metal+'</div>';
								html += '</div>';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">WEIGHT</div>';
                                                                        if(vl.attributes.gold_weight > 1000)
                                                                        {
                                                                            vl.attributes.gold_weight = parseFloat(vl.attributes.gold_weight);
                                                                            var gweights = vl.attributes.gold_weight / 1000;
                                                                            gweights = number_format(gweights,2)+' Kgs';
                                                                        }
                                                                        else if(vl.attributes.gold_weight == 1000)
                                                                        {
                                                                            var gweights = '1 Kgs';
                                                                        }
                                                                        else
                                                                        {
                                                                            vl.attributes.gold_weight = parseFloat(vl.attributes.gold_weight);
                                                                            var gweights = number_format(vl.attributes.gold_weight,2)+' Gms';
                                                                        }
									html += '<div class="detValue fmOpenR fLeft">'+gweights+'</div>';
								html += '</div>';
							html += '</div>';
							html += '<div class="prdPrice fLeft">';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">PRICE</div>';
                                                                        var metal_price = vl.attributes.price;
                                                                        
                                                                            if(vl.vdetail !== null && vl.vdetail.gold_rate !== 'undefined'  && vl.vdetail.gold_rate !== undefined && vl.vdetail.gold_rate !== null && vl.vdetail.gold_rate !== 'null' && vl.vdetail.gold_rate !== '0.00'  && vl.vdetail.gold_rate !== '' ) { goldRate=vl.vdetail.gold_rate; }
                                                                            if(vl.vdetail !== null && vl.vdetail.silver_rate !== '0.00' && vl.vdetail.silver_rate !== undefined && vl.vdetail.silver_rate !== null && vl.vdetail.silver_rate !== '') { silverRate=vl.vdetail.silver_rate; }
                                                                            if((vl.attributes.metal.toLowerCase()) === 'gold'){ metal_rate=goldRate; 
                                                                            metal_rate=(metal_rate/10)*(vl.attributes.gold_purity/995);
                                                                            metal_price = vl.attributes.gold_weight * metal_rate;
                                                                            }
                                                                            else if((vl.attributes.metal.toLowerCase()) === 'silver'){ metal_rate=silverRate; 
                                                                            metal_rate=(metal_rate/1000)*(vl.attributes.gold_purity/999);
                                                                            metal_price = vl.attributes.gold_weight * metal_rate;
                                                                            }
                                                                            metal_price = Math.ceil(metal_price);
                                                                        html += '<div class="detValue fmOpenB fLeft"><span>₹</span>'+ common.IND_money_format(metal_price)+'</div>';
								html += '</div>';
							html += '</div>';
                                                        html += '</a>';
							html += '<div class="prdActions fLeft">';
                                                        if(tempUrl !== '')
                                                {
                                                    html += '<a href="'+DOMAIN+tempUrl+'/bid-'+vl.pid+'/1">';
                                                }
                                                else
                                                {
                                                    html += '<a href="'+DOMAIN+type+'/bid-'+vl.pid+'">';
                                                }
                                                        	html += '<div class="wConBtn fLeft">Contact Dealer</div>';
                                                            html += '</a>';
							html += '</div>';
						html += '</div>';
				html += '</div>';
			});
		}
		else if(pageName == 'wishlist-jewellery') {
			$.each(data.results.products, function(i, vl) {
				html += '<div class="prdComm fLeft jwRes transition100" style="opacity: 1; transform: translateX(0px);">';
					html += '<div class="wisgDel" id="'+vl.pid+'"></div>';
						html += '<div class="prdCommDiv fLeft transition100">';
                                                
                                                var tempUrl = '';
                                                var metal = vl.attributes.metal;
                                                var shape = vl.attributes.shape;
                                                var purity = parseInt(vl.attributes.gold_purity);
                                                var weight = parseInt(vl.attributes.gold_weight);
                                                var cert = vl.attributes.certified;
                                                
                                                if(metal !== null && metal !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+metal; 
                                                    }
                                                    else{
                                                        tempUrl += metal;
                                                    }
                                                }
                                                if(shape !== null && shape !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+shape; 
                                                    }
                                                    else{
                                                        tempUrl += shape;
                                                    }
                                                }
                                                if(purity !== null && purity !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+purity+'-Carat'; 
                                                    }
                                                    else{
                                                        tempUrl += purity+'-Carat';
                                                    }
                                                }
                                                if(weight !== null && weight !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+weight+'-Grams'; 
                                                    }
                                                    else{
                                                        tempUrl += weight+'-Grams';
                                                    }
                                                }
                                                if(cert !== null && cert !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+cert; 
                                                    }
                                                    else{
                                                        tempUrl += cert;
                                                    }
                                                }
                                                if(tempUrl !== '')
                                                {
                                                    html += '<a href="'+DOMAIN+tempUrl+'/jid-'+vl.pid+'">';
                                                }
                                                else
                                                {
                                                    html += '<a href="'+DOMAIN+cert+'/jid-'+vl.pid+'">';
                                                }
                                                
							html += '<div id="'+vl.pid+'_imgs" class="prdCommImg fLeft">';
								html += getImageData(vl.images);
							html += '</div>';
							html += '<div class="prdDetails fLeft">';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">DESIGN NO.</div>';
									html += '<div class="detValue fmOpenR fLeft">'+vl.pcode+'</div>';
								html += '</div>';
							html += '</div>';
							html += '<div class="prdPrice fLeft">';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">PRICE</div>';
                                                                        var price = Math.ceil(vl.attributes.price);
									html += '<div class="detValue fmOpenB fLeft"><span>₹</span>'+common.IND_money_format(price)+'</div>';
								html += '</div>';
							html += '</div>';
                                                html += '</a>';
							html += '<div class="prdActions fLeft">';
                                                        if(tempUrl !== '')
                                                            {
                                                                html += '<a href="'+DOMAIN+tempUrl+'/did-'+vl.pid+'/1">';
                                                            }
                                                            else
                                                            {
                                                                html += '<a href="'+DOMAIN+cert+'/did-'+vl.pid+'">';
                                                            }
                                                        html += '<div class="wConBtn fLeft">Contact Dealer</div>';
                                                        html += '</a>'; 
							html += '</div>';
						html += '</div>';
					
				html += '</div>';
			});
		}
		else if(pageName == 'wishlist') {
			$.each(data.results.products, function(i, vl) {
				html += '<div class="prdComm fLeft transition100">';
					html += '<div class="prdCommDiv fLeft transition100">';
						html += '<div class="prdShape fLeft">';
							html += '<div class="prdShTitle fLeft fmOpenB">SHAPE</div>';
							html += '<div class="prdShType fLeft fmOpenR">Round</div>';
							html += '<div class="Round fRight"></div>';
						html += '</div>';
						html += '<div class="prdDetails fLeft">';
							html += '<div class="detComm">';
								html += '<div class="detLabel fmOpenB fLeft">COLOR</div>';
								html += '<div class="detValue fmOpenR fLeft">F</div>';
							html += '</div>';
							html += '<div class="detComm">';
								html += '<div class="detLabel fmOpenB fLeft">CARATS</div>';
								html += '<div class="detValue fmOpenR fLeft">0.99</div>';
							html += '</div>';
							html += '<div class="detComm">';
								html += '<div class="detLabel fmOpenB fLeft">CLARITY</div>';
								html += '<div class="detValue fmOpenR fLeft">Excellent</div>';
							html += '</div>';
							html += '<div class="detComm">';
								html += '<div class="detLabel fmOpenB fLeft">CERTIFICATE</div>';
								html += '<div class="detValue fmOpenR fLeft">1199046357</div>';
							html += '</div>';
						html += '</div>';
						html += '<div class="prdPrice fLeft">';
							html += '<div class="detComm">';
								html += '<div class="detLabel fmOpenB fLeft">BEST PRICE</div>';
								html += '<div class="detValue fmOpenB fLeft"><span>&#8377;</span>8,28,5888</div>';
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
		else {
			$.each(data.results.products, function(i, vl) {
				html += '<div class="prdComm fLeft jwRes" style="opacity: 0; transform: translateX(1000px);">';
					html += '<div class="prdCommDiv fLeft transition100">';
                                                
                                                var tempUrl = '';
                                                var metal = vl.attributes.metal;
                                                var type = vl.attributes.type;
                                                var purity = parseInt(vl.attributes.gold_purity);
                                                var weight = parseInt(vl.attributes.gold_weight);
                                                
                                                if(metal !== null && metal !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+metal; 
                                                    }
                                                    else{
                                                        tempUrl += metal;
                                                    }
                                                }
                                                if(type !== null && type !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+type; 
                                                    }
                                                    else{
                                                        tempUrl += type;
                                                    }
                                                }
                                                if(purity !== null && purity !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+purity; 
                                                    }
                                                    else{
                                                        tempUrl += purity;
                                                    }
                                                }
                                                if(weight !== null && weight !== undefined){
                                                    if(tempUrl !== ''){
                                                        tempUrl += '-'+weight+'-Grams'; 
                                                    }
                                                    else{
                                                        tempUrl += weight+'-Grams';
                                                    }
                                                }
                                                
                                                if(tempUrl !== '')
                                                {
                                                    html += '<a href="'+DOMAIN+tempUrl+'/bid-'+vl.pid+'">';
                                                }
                                                else
                                                {
                                                    html += '<a href="'+DOMAIN+type+'/bid-'+vl.pid+'">';
                                                }
							html += '<div class="prdShape fLeft">';
								html += '<div class="prdShTitle fLeft fmOpenB">TYPE</div>';
								html += '<div class="prdShType fLeft fmOpenR">'+vl.attributes.type+'</div>';
								html += '<div class="'+vl.attributes.type.toLowerCase()+'Ic fRight"></div>';
							html += '</div>';
							html += '<div class="prdDetails fLeft">';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">METAL</div>';
									html += '<div class="detValue fmOpenR fLeft">'+vl.attributes.metal+'</div>';
								html += '</div>';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">WEIGHT</div>';
                                                                        if(vl.attributes.gold_weight > 1000)
                                                                        {
                                                                            vl.attributes.gold_weight = parseFloat(vl.attributes.gold_weight);
                                                                            var gweights = vl.attributes.gold_weight / 1000;
                                                                            gweights = number_format(gweights,2)+' Kgs';
                                                                        }
                                                                        else if(vl.attributes.gold_weight == 1000)
                                                                        {
                                                                            var gweights ='1 Kgs';
                                                                        }
                                                                        else
                                                                        {
                                                                            vl.attributes.gold_weight = parseFloat(vl.attributes.gold_weight);
                                                                            var gweights = number_format(vl.attributes.gold_weight,2)+' Gms';
                                                                        }
									html += '<div class="detValue fmOpenR fLeft">'+gweights+'</div>';
								html += '</div>';
							html += '</div>';
							html += '<div class="prdPrice fLeft">';
								html += '<div class="detComm">';
									html += '<div class="detLabel fmOpenB fLeft">PRICE</div>';
                                                                var metal_price = '';
                                                                if(vl.gold_rate !== undefined && vl.gold_rate !== 'undefined' && vl.gold_rate !== '0.00' && vl.gold_rate !== null && vl.gold_rate !== 'null' && vl.gold_rate !== '' )
                                                                {
                                                                    goldRate=vl.gold_rate;
                                                                }
                                                                if(vl.silver_rate !== undefined && vl.silver_rate !== 'undefined' && vl.silver_rate !== '0.00' && vl.silver_rate !== null && vl.silver_rate !== 'null' && vl.silver_rate !== '')
                                                                {
                                                                    silverRate=vl.silver_rate;
                                                                }
                                                                var metal_rate = silverRate;
                                                                
                                                                if((vl.attributes.metal.toLowerCase()) === 'gold')
                                                                {
                                                                    metal_rate=goldRate; 
                                                                    metal_rate=(metal_rate/10)*(vl.attributes.gold_purity/995);
                                                                    metal_price = vl.attributes.gold_weight * metal_rate;
                                                                }
                                                                if((vl.attributes.metal.toLowerCase()) === 'silver')
                                                                {
                                                                    metal_rate=(metal_rate/1000)*(vl.attributes.gold_purity/999);
                                                                    metal_price =vl.attributes.gold_weight * metal_rate;
                                                                }
                                                                metal_price = Math.ceil(metal_price);
                                                                var tmp_metalprce = common.IND_money_format(metal_price)
                                                                    html += '<div class="detValue fmOpenB fLeft"><span>&#8377;</span>'+ tmp_metalprce +'</div>';
								html += '</div>';
							html += '</div>';
						html += '</a>';
						html += '<div class="prdActions fLeft">';
                                                    if(tempUrl !== '' )
                                                        {
                                                            html += '<a href="'+DOMAIN+tempUrl+'/bid-'+vl.pid+'/1" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                            html += '<a href="'+DOMAIN+tempUrl+'/bid-'+vl.pid+'/2" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                            html += '<a href="'+DOMAIN+tempUrl+'/bid-'+vl.pid+'/3" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                            html += '<a href="'+DOMAIN+tempUrl+'/bid-'+vl.pid+'" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                        }
                                                        else
                                                        {
                                                            html += '<a href="'+DOMAIN+type+'/bid-'+vl.pid+'/1" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                            html += '<a href="'+DOMAIN+type+'/bid-'+vl.pid+'/2" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                            html += '<a href="'+DOMAIN+type+'/bid-'+vl.pid+'/3" class="actionComm fLeft transition100 poR ripplelink"></a>';
                                                            html += '<a href="'+DOMAIN+type+'/bid-'+vl.pid+'" class="actionComm fLeft transition100 poR ripplelink"></a>';                                                           
                                                        }
                                                html += '</div>';
					html += '</div>';
				html += '</div>';
			});
		}
	}
	else
	{
		if(pageName.indexOf("wishlist") !== -1)
		{
			html += '<div class="noresDiv">';
				html += '<div class="noresults font25 fLeft">There are no products to display.</div>';
				html += '<div class="noresults font18 fLeft">Please add products to your wishlist.</div>';
			html += '</div>';
		}
		else
		{
			html += '<div class="noresDiv">';
				html += '<div class="noresults font25 fLeft">There are no results available for your search.</div>';
				html += '<div class="noresults font18 fLeft">Please try again.</div>';
			html += '</div>';
		}
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
	if(data.results.filters)
	{
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
                                                        $('#pgno').val(1);
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
	}
	
	/* For Pagintion */
	totalCount = data.results.total;
	var pgno = $('#pgno').val()*1;
	if(pageName.indexOf("wishlist") !== -1)
		var lastpg = Math.ceil(totalCount/16);
	else
		var lastpg = Math.ceil(totalCount/15);
	//lastpg = 10;
	var adjacents = 2;
	$('#total_pageno').val(lastpg);
	
	if(lastpg > 1)
	{
		html += '<div class="fLeft pagination fmOpenR">';
			html += '<center>';
				html += '<div class="pPrev poR ripplelink">Previous</div>';
				if(lastpg < 7 + (adjacents * 2))
				{
					for(var i = 1; i <= lastpg; i++)
					{
						if(i == pgno)
						{
							html += '<div class="pgComm poR ripplelink pgActive">'+i+'</div>';
						}
						else
						{
							html += '<div class="pgComm poR ripplelink">'+i+'</div>';
						}
					}
				}
				else if(lastpg > 5 + (adjacents * 2))
				{
					if(pgno < 1 + (adjacents * 2))
					{
						for (var i = 1; i < 4 + (adjacents * 2); i++)
						{
							if(i == pgno)
							{
								html += '<div class="pgComm poR ripplelink pgActive">'+i+'</div>';
							}
							else
							{
								html += '<div class="pgComm poR ripplelink">'+i+'</div>';
							}
						}
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgComm poR ripplelink">'+lastpg+'</div>';
					}
					else if(lastpg - (adjacents * 2) > pgno && pgno > (adjacents * 2))
					{
						html += '<div class="pgComm poR ripplelink">1</div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						for (var i = pgno - adjacents; i <= pgno + adjacents; i++)
						{
							if(i == pgno)
							{
								html += '<div class="pgComm poR ripplelink pgActive">'+i+'</div>';
							}
							else
							{
								html += '<div class="pgComm poR ripplelink">'+i+'</div>';
							}
						}
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgComm poR ripplelink">'+lastpg+'</div>';
					}
					else
					{
						html += '<div class="pgComm poR ripplelink">1</div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						html += '<div class="pgEmpty"></div>';
						for (var i = lastpg - (2 + (adjacents * 2)); i <= lastpg; i++)
						{
							if(i == pgno)
							{
								html += '<div class="pgComm poR ripplelink pgActive">'+i+'</div>';
							}
							else
							{
								html += '<div class="pgComm poR ripplelink">'+i+'</div>';
							}
						}
					}
				}
				html += '<div class="pNext poR ripplelink">Next</div>';
			html += '</center>';
		html += '</div>';
	}
	/* ************* */
	
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
	{
		if(typeof $('.allResults').offset() !== 'undefined')
		{
			$('html,body').animate({scrollTop: $('.allResults').offset().top-60}, 300);
		}
		else
			$('html,body').animate({scrollTop: $('.listCont').offset().top-120}, 300);
	}
		
	
	setTimeout(function(){
		showPrd();
	},10);
	
	$('.pgComm').bind('click', function() {
		if(!$(this).hasClass('pgActive'))
		{
			var pgval = $(this).text();
			$('#pgno').val(pgval);
			$('.pgComm').removeClass('pgActive');
			$(this).addClass('pgActive');
			var uid = $('#uid').val();
			if(pageName.indexOf("wishlist") !== -1)
			{
				if(typeof $('.wishTabComm') !== 'undefined')
				{
					$(".wishTabComm").each(function(){
						if($(this).hasClass('sel'))
						{
							showWish($(this).attr('id'),pgval,uid);
						}
					});
				}
			}
			else
			{
                            if($('#sortbyvl').val())
                                FR($('#sortbyvl').val());
                            else
                                FR(1);
                        }
		}
	});

	$('.pPrev').bind('click', function() {
		var curpgno = parseInt($('#pgno').val());
		var pgval = curpgno - 1;
		if(curpgno > 1)
		{
			$('#pgno').val(pgval);
			var uid = $('#uid').val();
			if(pageName.indexOf("wishlist") !== -1)
			{
				if(typeof $('.wishTabComm') !== 'undefined')
				{
					$(".wishTabComm").each(function(){
						if($(this).hasClass('sel'))
						{
							showWish($(this).attr('id'),pgval,uid);
						}
					});
				}
			}
			else
			{
				FR(1);
			}
		}
	});

	$('.pNext').bind('click', function() {
		var curpgno = parseInt($('#pgno').val());
		var pgval = curpgno + 1;
		if(curpgno < parseInt($('#total_pageno').val()))
		{
			$('#pgno').val(pgval);
			var uid = $('#uid').val();
			if(pageName.indexOf("wishlist") !== -1)
			{
				if(typeof $('.wishTabComm') !== 'undefined')
				{
					$(".wishTabComm").each(function(){
						if($(this).hasClass('sel'))
						{
							showWish($(this).attr('id'),pgval,uid);
						}
					});
				}
			}
			else
			{
				FR(1);
			}
		}
	});

	/*$('.prox').mouseover(function()
	{
		var tmpClass = $(this).attr('class');
		tmpClass = tmpClass.split(' ');
		var actClassNm = 'for-1';
		for(var i = 0 ; i < tmpClass.length; i++)
		{
			if(tmpClass[i].indexOf('for-') !== -1)
			{
				actClassNm = tmpClass[i];
				break;
			}
		}

		var classInd = actClassNm.split('-');
		classInd = classInd[1];

		var img=$(this).css('background');
		if(classInd == 1 || classInd == 2 || classInd == 3 || classInd == 4 || classInd == 6 || classInd == 8 || classInd == 9 || classInd == 10 || classInd == 12)
		{
			$(this).siblings('.proxImg').css({'background':img});
		}
		else
		{
			$(this).parent().siblings('.proxImg').css({'background':img});
		}
    });*/
	
	if(data.results.total === null)
		data.results.total = 0;
	
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
	if(suggestObj)
	{
		suggestObj.abort();
	}
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
	
	var ctid = $('#ctid').val();
	
	var pgno = $('#pgno').val();
	var uid = $('#uid').val();
    
    var dummyCall = '';
    if(dummyPage == 'b2bproducts')
        dummyCall = '&b2bsort=1';

	if(sortby)
	{
		var params = 'action=ajx&case=filter&catid='+catid+'&sortby='+sortby+'&slist='+slist+'&clist='+clist+'&tlist='+tlist+'&ilist='+ilist+'&jlist='+jlist+'&ctid='+ctid+'&pgno='+pgno+"&uid="+uid+dummyCall;
		var URL = DOMAIN + "index.php";
		suggestObj = $.getJSON(URL, params, function(data) {
			getResultsData(data,sortby);   
		});
	}
	else
	{
		$('#drpinp').text('Best Match');
		var params = 'action=ajx&case=filter&catid='+catid+'&slist='+slist+'&clist='+clist+'&tlist='+tlist+'&ilist='+ilist+'&jlist='+jlist+'&ctid='+ctid+'&pgno='+pgno+"&uid="+uid+dummyCall;
		var URL = DOMAIN + "index.php";
		suggestObj = $.getJSON(URL, params, function(data) {
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
if(ele !== null)
{
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
}

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


function getImageData(imgData, isImgs)
{
	if(isImgs === undefined || isImgs === null || isImgs === '' || isImgs === 'undefined' || isImgs === 'null' || typeof isImgs === 'undefined')
	{
		var tmpHtml = "<div class='for-1 noImage'></div>";
	}
	else
	{
		var imgLen = 0;
		var tmpHtml = "<div class='for-1 noImage'></div>";
		if(imgData !== undefined && imgData !== null && imgData !== '')
		{
			imgLen = imgData.length;
			if(imgLen > 12)
			{
				imgLen = 12;
			}

			switch(imgLen)
			{
				case 0:
					tmpHtml = "<div class='for-1 noImage'></div>";
				break;
				case 1:
				case 2:
				case 3:
				case 4:
				case 6:
				case 8:
				case 9:
				case 10:
				case 12:
					tmpHtml = '';
					for(imgi = 0; imgi < imgLen; imgi++)
					{
						tmpHtml += "<div class='prox for-"+imgLen+"' onmouseover='showMouseOver(this);' style='background: #ccc url(" + IMGDOMAIN + imgData[imgi] + ") no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div>";
					}
					tmpHtml += "<div class='proxImg fLeft' style='background: #fff url(" + IMGDOMAIN + imgData[0] + ")no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div>";
				break;
				case 5:
					tmpHtml = "<div class='prox for-"+imgLen+"Left'>";
					for(imgi = 0; imgi < imgLen; imgi++)
					{
						if(imgi == imgLen - 1)
						{
							tmpHtml += "</div>";
							tmpHtml += "<div class='prox for-"+imgLen+"Right'><div onmouseover='showMouseOver(this);' class='prox for-"+imgLen+"' style='background: #ccc url(" + IMGDOMAIN + imgData[imgi] + ")no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div></div>";
						}
						else
						{
							tmpHtml += "<div class='prox for-"+imgLen+"' onmouseover='showMouseOver(this);' style='background: #ccc url(" + IMGDOMAIN + imgData[imgi] + ")no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div>";
						}
					}
					tmpHtml += "<div class='proxImg fLeft' style='background: #fff url(" + IMGDOMAIN + imgData[0] + ")no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div>";
				break;
				case 7:
					tmpHtml = "";
					var curi = 0;
					for(imgi = 0; imgi < imgLen; imgi++)
					{
						if(imgi == 0)
						{
							tmpHtml += "<div class='for-" + imgLen + "Upper'><div class='prox for-" + imgLen + "' onmouseover='showMouseOver(this);' style='background: #ccc url(" + IMGDOMAIN + imgData[imgi] + ")no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div></div>";
						}
						else
						{
							if(curi == 0)
							{
								tmpHtml += "<div class='for-" + imgLen + "Lower'>";
							}
							tmpHtml += "<div class='prox for-" + imgLen + "' onmouseover='showMouseOver(this);' style='background: #fff url(" + IMGDOMAIN + imgData[imgi] + ")no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div>";

							if(curi == imgLen - 2)
							{
								tmpHtml += "</div>";
							}

							curi++;
						}
					}
					tmpHtml += "<div class='proxImg fLeft' style='background: #fff url(" + IMGDOMAIN + imgData[0] + ")no-repeat;background-size:contain;background-position:center;background-color:#FFF;'></div>";
				break;
				case 11:
					var curi = 0;
					tmpHtml = "<div class='prox for-" + imgLen + "Left'>";

					for(imgi = 0; imgi < imgLen; imgi++)
					{
						if(curi == 0)
						{
							if(imgi < 3)
							{
								tmpHtml += "<div class='prox for-"+imgLen+"' onmouseover='showMouseOver(this);' style='background: #fff url(" + IMGDOMAIN + imgData[imgi] + ")no-repeat;background-size:contain;background-position:center;background-color:#FFF;'></div>";

								if(imgi == 2)
								{
									curi++;
								}
							}
						}
						else
						{
							if(imgi == 3)
							{
								tmpHtml += "</div>";
								tmpHtml += "<div class='prox for-"+imgLen+"Right'>";
							}

							tmpHtml += "<div class='prox for-"+imgLen+"' onmouseover='showMouseOver(this);' style='background: #fff url(" + IMGDOMAIN + imgData[imgi] + ")no-repeat;background-size:contain;background-position:center;background-color:#FFF;'></div>";

							if(imgi == imgLen - 1)
							{
								tmpHtml += "</div>";
							}
						}
					}
					tmpHtml += "<div class='proxImg fLeft' style='background: #fff url(" + IMGDOMAIN + imgData[0] + ")no-repeat;background-size:contain;background-position:center;background-color:#FFF;'></div>";
				break;
			}
		}
	}

	return tmpHtml;
}

function showMouseOver(obj)
{
	var tmpClass = $(obj).attr('class');
	tmpClass = tmpClass.split(' ');
	var actClassNm = 'for-1';
	for(var i = 0 ; i < tmpClass.length; i++)
	{
		if(tmpClass[i].indexOf('for-') !== -1)
		{
			actClassNm = tmpClass[i];
			break;
		}
	}

	var classInd = actClassNm.split('-');
	classInd = classInd[1];

	var img=$(obj).css('background');
	if(classInd == 1 || classInd == 2 || classInd == 3 || classInd == 4 || classInd == 6 || classInd == 8 || classInd == 9 || classInd == 10 || classInd == 12)
	{
		$(obj).siblings('.proxImg').css({'background':img});
	}
	else
	{
		$(obj).parent().siblings('.proxImg').css({'background':img});
	}
}