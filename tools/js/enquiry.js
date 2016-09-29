var uid = customStorage.readFromStorage('userid');
var loadEnq = true;
//var pgno = 1;
var curpgno = 1;

loadEnqs(1);
function loadEnqs(pgno) {
    	if(!pgno)
		pgno = 1;
            var tmstmp = new Date().getTime();
    $.ajax({url: common.APIWebPath() + "index.php?action=viewLog&vid=" + uid + "&page=" + pgno + "&limit=50"+"&timestamp="+tmstmp, success: function (result) {
            loadEnqCallback(result,pgno);
    }});
}
function loadEnqCallback(res,pgno)
{
    var obj = eval('('+res+')');
    if (obj.results !== undefined && obj.results !== 'undefined' && obj.results !== null && obj.results !== 'null' && obj.results !== '')
    {
        $('#pgno').val(pgno);
                var total = (obj.total_enqs == 1 ? obj.total_enqs + ' Enquiry Listed' : obj.total_enqs + ' Enquiries Listed');
                $('#totalEnqs').text(total);
                
                var total_pages = obj.total_pages;
                
                var str = '';
                var total = obj.total_enqs;
                
                if(total !== 0 && total !== '0')
                {
                    if(total_pages == curpgno)
                    {
                        loadEnq = false;
                    }
                    var len = obj.total_enqs;
                    var i = 0;
                    while (i < len)
                    {
                        str += generateEnqList(obj.results[i]);
                        i++;
                    }
                    var html = pagination(obj,pgno);
                }
                else
                {
                    str += '<p class="noRecords"><span>Sorry! No Enquiries Found!</span></p>';
                    loadEnq = false;
                }
                    $('#enqList').html(str);
                    $('#enqList').append(html);
                    $('.pgComm').click( function()
                    {
                        $('.pgComm').removeClass('pgActive');
                        $(this).addClass('pgActive');
                        loadEnqs($(this).text());
                        $('html,body').animate({scrollTop: $('.prdResults').offset().top-100}, 300);
                    });
                    paginationPrevNext();
    }
    else
    {
        loadEnq = false;
    }
}

function generateEnqList(obj,pgno)
{
    if(obj !== undefined && obj !== 'undefined' && obj !== null && obj !== 'null')
    {
        var date = obj.enquiry.update_time.split(' ');
        var search = obj.search;
        var enquiry= obj.enquiry;  
        var barcode = search.barcode;
        var uname = enquiry.user_name;
        var umail = enquiry.user_email;
        var umob = enquiry.user_mobile;
        var certified = search.certified;
        var clarity = search.clarity;
        var shape = search.shape;
        var subCatName = obj.category.cat_name;
        var product_id = search.product_id;
        var type = search.type;
        var weight = search.gold_weight;
        var metal = search.metal;
        var color = search.color;
        var purity = search.purity;
        var price = search.price;
        var categoryid = obj.category.mcatid;
        
        if(barcode == null || barcode == '' || barcode == 'null') {
            barcode = 'N-A';
        }
        if(uname == undefined || uname == 'undefined' || uname == null || uname == '' || uname == 'null' || uname === '0') {
            uname = 'N/A';
        }
        if(uname == undefined || uname == 'undefined' || umail == null || umail == '' || umail === '0' ) {
            umail = 'N/A';
        }
        if(umob == undefined || umob == 'undefined' || umob == null || umob == '' || umob === '0' || umob === 0 ) {
            umob = 'N/A';
        }
        if(certified == undefined || certified == 'undefined' || certified == null || certified == '' || certified === '0' || certified === 0 ) {
            certified = 'N/A';
        }
        if(shape == undefined || shape == 'undefined' || shape == null || shape == '' || shape === '0' || shape === 0 ) {
            shape = 'N/A';
        }
        if(clarity == undefined || clarity == 'undefined' || clarity == null || clarity == '' || clarity === '0' || clarity === 0 ) {
            clarity = 'N/A';
        }
        
        var str='';
        str += '<li>';
        str += '<div class="date fLeft"> ';
        str += '<span class="upSpan">' + date[0] + '</span>';
        str += '<span class="lwSpan">' + date[1] + '</span>';
        str += '</div>';
        str += '<div class="name fLeft txtOver">' + uname + '</div>';
        str += '<div class="email fLeft txtOver" title='+umail+'>'+ umail + '</div>';
        
        if(categoryid == '10000' || categoryid == 10000)
        {
                    str += '<div class="type fLeft">'+ subCatName;
                    str += '</div>';
        }
        else
        {
            if(categoryid == '10002' || categoryid == 10002)
            {
                str += '<div class="type fLeft">';
                    str += '<span class="upSpan">' + subCatName + '</span>';
                    str += '<span class="lwSpan">' + type + '</span>';
                str += '</div>';
            }
            else if(categoryid == '10001' || categoryid == 10001)
            {
                str += '<div class="type fLeft">';
                    str += '<span class="upSpan">' + subCatName + '</span>';
                    str += '<span class="lwSpan">' + shape + '</span>';
                str += '</div>';
            }
        }
 
        str += '<div class="barcode fLeft">';
        str += '<span class="upSpan txtOver">' + barcode + '</span>';
            if(categoryid == 10000)
                {
                 
                    var tempUrl = '';
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
                    if(certified !== null && certified !== undefined){
                        if(tempUrl !== ''){
                        tempUrl += '-certified-'+certified; 
                        }
                        else{
                        tempUrl += 'certified-'+certified;
                        }
                    }
                    if(tempUrl !== '')
                    {
                        str += '<span class="lwSpan"><a href="'+DOMAIN+ tempUrl+'/did-'+product_id+'" target="_blank">View Details</a></span>';
                    }
                    else
                    {
                        str += '<span class="lwSpan"><a href="'+ DOMAIN + search.shape +'/did-'+search.product_id+'" target="_blank">View Details</a></span>';
                    }
                }
                else if(categoryid == 10002)
                {
                    
                    var tempUrl = '';
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
                    if(purity !== '' && purity !== 'null' && purity !== null && purity !== undefined && purity !== 'undefined'){
                        if(tempUrl !== ''){
                            var goldpty = purity.split('.');
                            tempUrl += '-'+goldpty[0]; 
                        }
                        else{
                            var goldpty = purity.split('.');
                            tempUrl += goldpty[0];
                        }
                    }
                    if(weight !== null && weight !== undefined && weight !== ''){
                        if(tempUrl !== ''){
                            var goldwt = weight.split('.');
                            goldwt = goldwt[0].split(',');
                            tempUrl += '-'+ goldwt+'-Grams'; 
                        }
                        else{
                            var goldwt = weight.split('.');
                            goldwt = goldwt[0].split(',');
                            tempUrl += goldwt+'-Grams';
                        }
                    }
                    if(tempUrl !== '')
                    {
                        str += '<span class="lwSpan"><a href="'+DOMAIN+ tempUrl+'/bid-'+product_id+'" target="_blank">View Details</a></span>';
                    }
                    else
                    {
                        str += '<span class="lwSpan"><a href="'+ DOMAIN + type +'/bid-'+product_id+'" target="_blank">View Details</a></span>';
                    }
                }
                else if(categoryid == 10001)
                {
                    
                    var tempUrl = '';
                    if(metal !== null && metal !== undefined){
                        if(tempUrl !== ''){
                            tempUrl += '-'+metal; 
                        }
                        else{
                            tempUrl += metal;
                        }
                    }
                    if(subCatName !== null && subCatName !== undefined && subCatName !== '' && subCatName !== 'undefined')
                    {
                        if(tempUrl !== ''){
                            tempUrl += '-'+encodeURIComponent(subCatName); 
                        }
                        else{
                            tempUrl += '-Jewellery';
                        }
                    }
                    if(search.gold_purity !== '' && search.gold_purity !== 'null' && search.gold_purity !== null && search.gold_purity !== undefined && search.gold_purity !== 'undefined'){
                        if(tempUrl !== ''){
                            tempUrl += '-'+purity+'-Carat'; 
                        }
                        else{
                            tempUrl += purity+'-Carat';
                        }
                    }
                    if(search.certified !== null && search.certified !== undefined && search.certified !== ''){
                        if(tempUrl !== ''){
                            tempUrl += '-certificate-'+certified; 
                        }
                        else{
                            tempUrl += 'certificate-'+certified;
                        }
                    }
                    if(tempUrl !== '')
                    {
                        str += '<span class="lwSpan"><a href="'+DOMAIN+ tempUrl+'/jid-'+product_id+'" target="_blank">View Details</a></span>';
                    }
                    else
                    {
                        str += '<span class="lwSpan"><a href="'+ DOMAIN + search.shape +'/jid-'+product_id+'" target="_blank">View Details</a></span>';
                    }
                }
        str += '</div>';
                str += '<div class="price fLeft bNone fmOpenB"><span class="fLeft rupeeImg"></span>' + price + '</div>';
        str += '</li>';
        return str;
    }
    else
    {
     str = '';
     return str;
    }
        
}

function pagination(data,pgno)
{
	/* For Pagintion */
	pgno = pgno*1;
	var html = '';
	var tc = data.total_enqs;
        
	var lastpg = Math.ceil(tc/50);
	var adjacents = 2;
	
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
	return html;
}

function paginationPrevNext()
{
    $('.pPrev').bind('click', function() {
        
            var curpgno = parseInt($('#pgno').val());
                var pgval = curpgno - 1;
                
		if(curpgno > 1)
		{
                    
                        $('#pgno').val(pgval);
                        
			if(pageName.indexOf("Enquiries") !== -1)
			{
                                loadEnqs(pgval);
			}
			else
			{   
				loadEnqs(1);
			}
		}
            });
            	$('.pNext').bind('click', function() {
		var curpgno = parseInt($('#pgno').val());
		var pgval = curpgno + 1;

		if(curpgno < parseInt($('#total_pageno').val()))
		{
			$('#pgno').val(pgval);
			if(pageName.indexOf("Enquiries") !== -1)
			{
				/*if(typeof $('.wishTabComm') !== 'undefined')
				{
					$(".productstab").each(function(){
						if($(this).hasClass('sel'))
						{
							loadProducts($(this).attr('id'),pgval);
						}
					});
				}*/
                                loadEnqs(pgval);
			}
			else
			{
				loadEnqs(1);
			}
		}
	});
}
$(document).ready(function() {	
    loadEnqs(1);
        $('.pPrev').bind('click', function() {

            var curpgno = parseInt($('#pgno').val());
            
            	var pgval = curpgno - 1;
		if(curpgno > 1)
		{
			$('#pgno').val(pgval);
                        
			if(pageName.indexOf("Enquiries") !== -1)
			{
				/*if(typeof $('.productstab') !== 'undefined')
				{
					$(".productstab").each(function(){
						if($(this).hasClass('sel'))
						{
							loadProducts($(this).attr('id'),pgval);
						}
					});
				}*/
                                loadEnqs(pgval);
			}
			else
			{   
				loadEnqs(1);
			}
		}
	});

	$('.pNext').bind('click', function() {
		var curpgno = parseInt($('#pgno').val());
		var pgval = curpgno + 1;
		if(curpgno < parseInt($('#total_pageno').val()))
		{
			$('#pgno').val(pgval);
			
			if(pageName.indexOf("Enquiries") !== -1)
			{
				/*if(typeof $('.wishTabComm') !== 'undefined')
				{
					$(".productstab").each(function(){
						if($(this).hasClass('sel'))
						{
							loadProducts($(this).attr('id'),pgval);
						}
					});
				}*/
                    
                                loadEnqs(pgval);
			}
			else
			{
				loadEnqs(1);
			}
		}
	});
    });