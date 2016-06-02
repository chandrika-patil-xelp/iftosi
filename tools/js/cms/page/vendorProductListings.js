var loadDiamont = false;
var loadJewel = false;
var loadBullion = false;

var diamondPage = 1;
var jewellPage = 1;
var bullionPage = 1;
//$.ajax({url: common.APIWebPath() + "index.php?action=viewAll&uid="+uid, success: function (result) {
//    var obj = jQuery.parseJSON(result);
//    var busiType = obj['results'][1]['business_type'];
    if (/1/.test(busiType)) {
        $('#dmdTab').removeClass('dn');
        loadDiamont = true;
    }
    if (/2/.test(busiType)) {
        $('#jewTab').removeClass('dn');
        loadJewel = true;
    }
    if (/3/.test(busiType)) {
        $('#bullTab').removeClass('dn');
        loadBullion = true;
    }
    if (/1/.test(busiType) && catid == 10000) {
        $('#diamondPrds').removeClass('dn');
        loadDiamonds(1);
    }
    else if (/2/.test(busiType) && catid == 10001) {
        loadJewels(1);
        $('#jewelleryPrds').removeClass('dn');
    }
    else if (/3/.test(busiType) && catid == 10002) {
        $('#bullionPrds').removeClass('dn');
        loadBullions();
    }
//}});

/*
$(window).scroll(function () {
    if ($(window).scrollTop() == ($(document).height() - $(window).height())) {
        if(!searchScroll) {
            if (catid == 10000 && loadDiamont) {
                loadDiamonds();
            }
            if (catid == 10001 && loadJewel) {
                loadJewels();
            }
            if (catid == 10002 && loadBullion) {
                loadBullions();
            }
        } else {
            searchPage++;
            searchBarcode(searchScrollValue);
        }
    }
});
*/

function loadDiamonds(pgno) {
	if(!pgno)
		pgno = 1;
	$.ajax({url: common.APIWebPath() + "index.php?action=getVproducts&vid=" + uid + "&page=" + pgno + "&limit=50&catid="+catid, success: function (result) {
		
		loadDiamondCallback(result,pgno);
	}});
}
function loadDiamondCallback(res,pgno) {
    var obj = jQuery.parseJSON(res);
    if (obj['results'] != '') {
        var total = obj['results']['total_products'];
        $('#totalDiamonds').text(total);
        var total_pages = obj['results']['total_pages'];
        
        var str = '';
        if(total != 0) {
			if(pgno <= total_pages)
			{
				if(total_pages==diamondPage) {
					loadDiamont = false;
				}
				var len = obj['results']['products'].length;
				var i = 0;
				while (i < len) {
					str += generateDiamondList(obj['results']['products'][i]);
					i++;
				}
				var html = pagination(obj,pgno);
				diamondPage++;
				$('#DiamondsList').html(str);
				$('#DiamondsList').append(html);
			}
        } else {
            str = '<p class="noRecords"><span>Sorry! No Products Found!</span></p>';
            loadDiamont = false;
			$('#DiamondsList').html(str);
			$('#DiamondsList').append(html);
        }
		
		$('.pgComm').click( function(){
			
			$('.pgComm').removeClass('pgActive');
			$(this).addClass('pgActive');
			loadDiamonds($(this).text());
			$('#pgno').val($(this).text());
			if($(this).text() >= 1 && $(this).text() <= total_pages)
			{
				$('html,body').animate({scrollTop: $('.prdResults').offset().top-100}, 300);
			}
		});
		$('.pPrev').bind('click', function() {
			var curpgno = parseInt($('#pgno').val());
			var pgval = curpgno - 1;
			if(curpgno > 1)
			{
				$('#pgno').val(pgval);
				loadDiamonds(pgval);
				if(pgval >= 1 && pgval <= total_pages)
				{
					$('html,body').animate({scrollTop: $('.prdResults').offset().top-100}, 300);
				}
			}
			else
			{
				pgval = 1;
			}
		});

		$('.pNext').bind('click', function() {
			var curpgno = parseInt($('#pgno').val());
			var pgval = curpgno + 1;
			if(curpgno < parseInt($('#total_pageno').val()))
			{
				$('#pgno').val(pgval);
				loadDiamonds(pgval);
				if(pgval >= 1 && pgval <= total_pages)
				{
					$('html,body').animate({scrollTop: $('.prdResults').offset().top-100}, 300);
				}
			}
			else
			{
				pgval = parseInt($('#total_pageno').val());
			}
		});
		
    } else {
        loadDiamont = false;
    }
}

function pagination(data,pgno){
	/* For Pagintion */
	pgno = pgno*1;
	var html = '';
	var tc = data.results.total_products;
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

function generateDiamondList(obj) {
    var cl='';
    var pro_name = obj['product_name'];
    if(pro_name == null || pro_name == '' || pro_name == 'null') {
        pro_name = obj['barcode'];
    }
    var barcode = obj['barcode'];
    if(barcode == 'null' || barcode == undefined || barcode == null || barcode == '' || barcode == '' ) {
        barcode = 'N-A';
    }
    
    var b2b_price = obj.b2b_price;
    if(b2b_price == 'null' || b2b_price == undefined || b2b_price == null || b2b_price == '' || b2b_price == '' || b2b_price == '0.00')
    {
        b2b_price = 'N-A';
    }
    else
    {
        b2b_price = "&#36;"+b2b_price;
    }
    
    var date = obj['update_time'].split(' ');
    var str = '<li>';
    str += '<div class="date fLeft"> ';
    str += '<span class="upSpan">' + date[0] + '</span>';
    str += '<span class="lwSpan">' + date[1] + '</span>';
    str += '</div>';
    str += '<div class="barcode fLeft">';
    str += '<span class="upSpan fmOpenB">' + barcode + '</span>';
    var shape = '';
    if(obj['shape'] != null || obj['shape'] !== undefined) {
        shape = obj.shape;
    }
    var tempUrl = '';
    if(shape !== null && shape !== undefined){
        if(tempUrl !== ''){
            tempUrl += '-'+shape; 
        }
        else{
            tempUrl += shape;
        }
    }
    if(obj.color !== null && obj.color !== undefined){
        if(tempUrl !== ''){
            tempUrl += '-colour-'+obj.color; 
        }
        else{
            tempUrl += 'colour-'+obj.color;
        }
    }
    if(obj.clarity !== null && obj.clarity !== undefined){
        if(tempUrl !== ''){
            tempUrl += '-clarity-'+obj.clarity; 
        }
        else{
            tempUrl += 'clarity-'+obj.clarity;
        }
    }
    if(obj.cert !== null && obj.cert !== undefined){
        if(tempUrl !== ''){
            tempUrl += '-certified-'+obj.cert; 
        }
        else{
            tempUrl += 'certified-'+obj.cert;
        }
    }
    if(tempUrl !== '')
    {
        str += '<span class="lwSpan"><a href="'+ DOMAIN+tempUrl+'/did-'+obj.id+'" target="_blank">View Details</a></span>';
    }
    else
    {
        str += '<span class="lwSpan"><a href="'+ DOMAIN +shape+'/did-'+obj.id+'" target="_blank">View Details</a></span>';
    }
    str += '</div>';
    str += '<div class="shape fLeft">' + obj['shape'] + '</div>';
    str += '<div class="carats fLeft fmOpenB">' + obj['carat'] + '</div>';
    str += '<div class="color fLeft">' + obj['color'] + '</div>';
    str += '<div class="clarity fLeft">' + obj['clarity'] + '</div>';
    str += '<div class="cert fLeft">' + obj['cert'] + '</div>';
    str += '<div class="price fLeft fmOpenB">&#36;' + obj['price']+ '</div>';
    str += '<div class="price fLeft fmOpenB">' + b2b_price + '</div>';
    str += '<div class="acct fLeft poR">';
    if(obj['active_flag'] == 1 || obj['active_flag'] == 0){
        cl='inStockPrd';
    }
    if(obj['active_flag'] == 3){
        cl='outStockPrd';
    }
    if(obj['active_flag'] == 4){
        cl='soldStockPrd';
    }
     str += '<div class="dmyCnt fRight">';
    str += '<select id="isStock'+ obj['id'] +'" onchange="inStock(' + obj['id'] + ',this.value);" value='+obj['active_flag']+' class="txtSelect fmOpenB '+cl+' fLeft">';
    str += '<option '+((obj['active_flag']) == 1 ? "selected" : "")+' class="arrow txtCenter" value="1">In Stock</option>';
    str += '<option '+((obj['active_flag']) == 3 ? "selected" : "")+' class="arrow txtCenter" value="3">Out Of Stock</option>';
    str += '<option '+((obj['active_flag']) == 4 ? "selected" : "")+' class="arrow txtCenter" value="4">Sold</option>';
    str += '</select>'
    str += '<div class="deltBtn poR fRight ripplelink" onclick="showConfirmDelete(' + obj['id'] + ',this)"></div>';
    str += '<a href="'+ DOMAIN +'index.php?case=diamond_Form&catid=10000&prdid='+ obj['id'] +'" target="_blank"><div class="editBtn fRight poR ripplelink"></div></a>';
    str += '<a href="'+ DOMAIN + 'upload-image/pid-'+ obj['id'] +'&c='+catid+'" target="_blank"><div class="uploadBtn fRight poR ripplelink"></div></a>';
    str += '</div>';
     str += '<div class="tltpBtn">';
     str += '<div class="tltpbox transition300">';
      str += '<div class="deltBtn poR fRight ripplelink" onclick="showConfirmDelete(' + obj['id'] + ',this)"></div>';
    str += '<a href="'+ DOMAIN +'index.php?case=diamond_Form&catid=10000&prdid='+ obj['id'] +'" target="_blank"><div class="editBtn fRight poR ripplelink"></div></a>';
    str += '<a href="'+ DOMAIN + 'upload-image/pid-'+ obj['id'] +'&c='+catid+'" target="_blank"><div class="uploadBtn fRight poR ripplelink"></div></a>';
         str += '<select id="isStock'+ obj['id'] +'" onchange="inStock(' + obj['id'] + ',this.value);" value='+obj['active_flag']+' class="txtSelect fmOpenB '+cl+' fLeft">';
    str += '<option '+((obj['active_flag']) == 1 ? "selected" : "")+' class="arrow txtCenter" value="1">In Stock</option>';
    str += '<option '+((obj['active_flag']) == 3 ? "selected" : "")+' class="arrow txtCenter" value="3">Out Of Stock</option>';
    str += '<option '+((obj['active_flag']) == 4 ? "selected" : "")+' class="arrow txtCenter" value="4">Sold</option>';
    str += '</select>'    
      str += '</div>';
     str += '</div>';
    
    
    
//    str += '<div class="soldBtn poR ripplelink fmOpenR '+cl+'" id="isStock'+ obj['id'] +'" onclick="inStock(' + obj['id'] + ',this)">'+((obj['active_flag']==3 ) ? "Out Of Stock" : "Out Of Stock");+'</div>';
    str += '</div>';
    str += '</li>';
    str += '';
    return str;
}



function loadJewels(pgno) {
	if(!pgno)
		pgno = 1;
    var tmstmp = new Date().getTime();
    $.ajax({url: common.APIWebPath() + "index.php?action=getVproducts&vid=" + uid + "&page=" + pgno + "&limit=50&catid="+catid+"&timestamp="+tmstmp, success: function (result) {
            loadJewellCallback(result,pgno);
    }});
}
function loadJewellCallback(res,pgno) {
    var obj = jQuery.parseJSON(res);
    if (obj['results'] != '') {
        var total = obj['results']['total_products'];
        $('#totalJewells').text(total);
        var total_pages = obj['results']['total_pages'];
            
        var str = '';
        if(total!=0) {
			if(pgno <= total_pages)
			{
            if(total_pages==jewellPage) {
                loadJewel = false;
            }
            var len = obj['results']['products'].length;
            var i = 0;
            while (i < len) {
                str += generateJewellList(obj['results']['products'][i]);
                i++;
            }
			var html = pagination(obj,pgno);
            jewellPage++;
			$('#JewellsList').html(str);
			$('#JewellsList').append(html);
			}
        } else {
            str = '<p class="noRecords"><span>Sorry! No Products Found!</span></p>';
            loadJewel = false;
			$('#JewellsList').html(str);
			$('#JewellsList').append(html);
        }
		
		$('.pgComm').click( function(){
			
			$('.pgComm').removeClass('pgActive');
			$(this).addClass('pgActive');
			loadJewels($(this).text());
			$('#pgno').val($(this).text());

			if($(this).text() >= 1 && $(this).text() <= total_pages)
			{
				$('html,body').animate({scrollTop: $('.prdResults').offset().top-100}, 300);
			}
		});

		$('.pPrev').bind('click', function() {
			var curpgno = parseInt($('#pgno').val());
			var pgval = curpgno - 1;
			if(curpgno > 1)
			{
				$('#pgno').val(pgval);
				loadJewels(pgval);
				if(pgval >= 1 && pgval <= total_pages)
				{
					$('html,body').animate({scrollTop: $('.prdResults').offset().top-100}, 300);
				}
			}
			else
			{
				pgval = 1;
			}
		});

		$('.pNext').bind('click', function() {
			var curpgno = parseInt($('#pgno').val());
			var pgval = curpgno + 1;
			if(curpgno < parseInt($('#total_pageno').val()))
			{
				$('#pgno').val(pgval);
				loadJewels(pgval);
				if(pgval >= 1 && pgval <= total_pages)
				{
					$('html,body').animate({scrollTop: $('.prdResults').offset().top-100}, 300);
				}
			}
			else
			{
				pgval = parseInt($('#total_pageno').val());
			}
		});
		
    } else {
        loadJewel = false;
    }
}
function generateJewellList(obj) {
    if(obj !== undefined && obj !== null && obj !== '')
    {   
        var cl='';
        var pro_name = obj['product_name'];
        var shape = obj['shape'];
		if(obj['category'] !== undefined && obj['category'] !== null && obj['category'] !== '' && typeof obj['category'] !== 'undefined')
		{
			var category = obj['category'][0]['cat_name'];
                        var cats = category;
		}
		else
		{
			var category = '';
		}
        var metal = obj['metal'];
        if(category == 'Bangles/Bracelets')
        {
            var cats1 = cats.split('/');
            cats = cats1.join('-');
            category = '<span class="upSpan">Bangles / Bracelets</span>';
        }
        if(category == "Men's")
        {
            var cats2 = cats.split("'");
            cats = cats2.join('-');
        }
        var barcode = obj['barcode'];
        if(barcode == undefined || barcode == null || barcode == '' || barcode == 'null') {
            barcode = 'N-A';
        }
        if(pro_name == undefined || pro_name == null || pro_name == '' || pro_name == 'null') {
            pro_name = barcode;
        }
        if(shape == undefined || shape == null || shape == '' || shape == 'null') {
            shape = 'N/A';
        }
        
        if(metal == undefined || metal == null || metal == '' || metal == 'null') {
            metal = 'N/A';
        }
        
        var tempUrl = '';
        if(obj.metal !== null && obj.metal !== undefined){
            if(tempUrl !== ''){
                tempUrl += '-'+obj.metal; 
            }
            else{
                tempUrl += obj.metal;
            }
        }
        if(cats !== null && cats !== undefined){
            if(tempUrl !== ''){
                tempUrl += '-'+encodeURIComponent(cats); 
            }
            else{
                tempUrl += encodeURIComponent(cats);
            }
        }
        if(obj.gold_purity !== '' && obj.gold_purity !== 'null' && obj.gold_purity !== null && obj.gold_purity !== undefined && obj.gold_purity !== 'undefined'){
            if(tempUrl !== ''){
                tempUrl += '-'+obj.gold_purity+'-Carat'; 
            }
            else{
                tempUrl += obj.gold_purity+'-Carat';
            }
        }
        if(obj.gold_weight !== '' && obj.gold_weight !== 'null' && obj.gold_weight !== null && obj.gold_weight !== undefined && obj.gold_weight !== 'undefined'){
            if(tempUrl !== ''){
                tempUrl += '-'+common.number_format(obj.gold_weight,0)+'-Grams'; 
            }
            else{
                tempUrl += common.number_format(obj.gold_weight,0)+'-Grams';
            }
        }
        if(obj.cert !== null && obj.cert !== undefined && obj.cert !== ''){
            if(tempUrl !== ''){
                tempUrl += '-'+obj.cert; 
            }
            else{
                tempUrl += obj.cert;
            }
        }
        
        
        var date = obj['update_time'].split(' ');
        var str = '<li>';
        str += '<div class="date fLeft"> ';
        str += '<span class="upSpan">' + date[0] + '</span>';
        str += '<span class="lwSpan">'+ date[1] +'</span>';
        str += '</div>';
        str += '<div class="barcode fLeft">';
        str += '<span class="upSpan fmOpenB">' + barcode + '</span>';
        
        if(tempUrl !== '')
        {
            str += '<span class="lwSpan"><a href="'+ DOMAIN + tempUrl+'/jid-'+obj['id']+'" target="_blank">View Details</a></span>';
        }
        else
        {
            str += '<span class="lwSpan"><a href="'+ DOMAIN +obj.cert+'/jid-'+obj['id']+'" target="_blank">View Details</a></span>';
        }
        str += '</div>';
        str += '<div class="metal fLeft">' + metal.split('~')[0] + '</div>';
        str += '<div class="catg fLeft">' + shape +'</div>';
        str += '<div class="degno fLeft">' + obj['dwt'] + '</div>';
        if(obj.gold_weight == 1000)
        {
            var gweights = '1 Kgs';
        }
        else if(obj.gold_weight > 1000)
        {
            obj.gold_weight = parseFloat(obj.gold_weight);
            var gweights = (obj.gold_weight/1000);
            gweights = common.number_format(gweights,2)+' Kgs';

        }
        else
        {
            obj.gold_weight = parseFloat(obj.gold_weight);
            var gweights = common.number_format(obj.gold_weight,2)+' Gms';
        }
//        str += '<div class="subType fLeft">' + gweights + '</div>';
        str += '<div class="price fLeft fmOpenB">&#8377;' + obj['price']+ '</div>';
        str += '<div class="acct fLeft poR">';
        
        if(obj['active_flag'] == 1 || obj['active_flag'] == 0){
            cl='inStockPrd';
        }
        if(obj['active_flag'] == 3){
            cl='outStockPrd';
        }
         str += '<div class="dmyCnt fRight">';
        str += '<select id="isStock'+ obj['id'] +'" onchange="inStock(' + obj['id'] + ',this.value);" value='+obj['active_flag']+' class="txtSelect fmOpenR fLeft '+cl+'">';
        str += '<option value="1" '+((obj['active_flag']) == 1 ? "selected" : "")+'>In Stock</option>';
        str += '<option value="3" '+((obj['active_flag']) == 3 ? "selected" : "")+'>Out Of Stock</option>';
        str += '</select>';
        str += '<div class="deltBtn poR ripplelink fRight" onclick="showConfirmDelete(' + obj['id'] + ',this)"></div>';
        str += '<a href="'+ DOMAIN +'index.php?case=jewellery_Form&catid=10001&prdid='+ obj['id'] +'" target="_blank"><div class="editBtn poR ripplelink fRight"></div></a>';
        str += '<a href="'+ DOMAIN + 'upload-image/pid-'+ obj['id'] +'&c='+catid+'" target="_blank"><div class="uploadBtn poR ripplelink fRight"></div></a>';
        str += '</div>';
         str += '<div class="tltpBtn">';
     str += '<div class="tltpbox transition300">';
      str += '<div class="deltBtn poR fRight ripplelink" onclick="showConfirmDelete(' + obj['id'] + ',this)"></div>';
    str += '<a href="'+ DOMAIN +'index.php?case=diamond_Form&catid=10000&prdid='+ obj['id'] +'" target="_blank"><div class="editBtn fRight poR ripplelink"></div></a>';
    str += '<a href="'+ DOMAIN + 'upload-image/pid-'+ obj['id'] +'&c='+catid+'" target="_blank"><div class="uploadBtn fRight poR ripplelink"></div></a>';
         str += '<select id="isStock'+ obj['id'] +'" onchange="inStock(' + obj['id'] + ',this.value);" value='+obj['active_flag']+' class="txtSelect fmOpenR fLeft '+cl+'">';
        str += '<option value="1" '+((obj['active_flag']) == 1 ? "selected" : "")+'>In Stock</option>';
        str += '<option value="3" '+((obj['active_flag']) == 3 ? "selected" : "")+'>Out Of Stock</option>';
        str += '</select>';   
      str += '</div>';
     str += '</div>';
        
        //str += '<div class="soldBtn poR ripplelink fmOpenR '+cl+'" id="isStock'+ obj['id'] +'" onclick="inStock(' + obj['id'] + ',this)">'+((obj['active_flag']==3 ) ? "Out Of Stock" : "Out Of Stock");+'</div>';
       
        str += '</div>';
        str += '</li>';
        str += '';
        return str;
        }
 }

function loadBullions(pgno) {
	if(!pgno)
		pgno = 1;
            var tmstmp = new Date().getTime();
    $.ajax({url: common.APIWebPath() + "index.php?action=getVproducts&vid=" + uid + "&page=" + pgno + "&limit=50&catid="+catid+"&timestamp="+tmstmp, success: function (result) {
		loadBullionsCallback(result,pgno);
	}});
}
function loadBullionsCallback(res,pgno) {
    var obj = jQuery.parseJSON(res);
    if (obj['results'] != '') {
        var total = obj['results']['total_products'];
        $('#totalBullions').text(total);
        var total_pages = obj['results']['total_pages'];
        
        var str = '';
        if(total!=0) {
			if(pgno <= total_pages)
			{
				if(total_pages == bullionPage) {
					loadBullion = false;
				}
				var len = obj['results']['products'].length;
				var i = 0;
				while (i < len) {
					str += generatBullionsList(obj['results']['products'][i]);
					i++;
				}
				var html = pagination(obj,pgno);
				bullionPage++;
				$('#BullionsList').html(str);
				$('#BullionsList').append(html);
			}
        } else {
            str = '<p class="noRecords"><span>Sorry! No Products Found!</span></p>';
            loadBullion = false;
			$('#BullionsList').html(str);
			$('#BullionsList').append(html);
        }

		$('.pgComm').click( function() {
			$('.pgComm').removeClass('pgActive');
			$(this).addClass('pgActive');
			loadBullions($(this).text());
			$('#pgno').val($(this).text());
			if($(this).text() >= 1 && $(this).text() <= total_pages)
			{
				$('html,body').animate({scrollTop: $('.prdResults').offset().top-100}, 300);
			}
		});

		$('.pPrev').bind('click', function() {
			var curpgno = parseInt($('#pgno').val());
			var pgval = curpgno - 1;
			if(curpgno > 1)
			{
				$('#pgno').val(pgval);
				loadBullions(pgval);
				if(pgval >= 1 && pgval <= total_pages)
				{
					$('html,body').animate({scrollTop: $('.prdResults').offset().top-100}, 300);
				}
			}
			else
			{
				pgval = 1;
			}
		});

		$('.pNext').bind('click', function() {
			var curpgno = parseInt($('#pgno').val());
			var pgval = curpgno + 1;
			if(curpgno < parseInt($('#total_pageno').val()))
			{
				$('#pgno').val(pgval);
				loadBullions(pgval);
				if(pgval >= 1 && pgval <= total_pages)
				{
					$('html,body').animate({scrollTop: $('.prdResults').offset().top-100}, 300);
				}
			}
			else
			{
				pgval = parseInt($('#total_pageno').val());
			}
		});
		
    } else {
        loadBullion = false;
    }
}
function generatBullionsList(obj) {
    var barcode = obj['barcode'];
    var cl='';
    if(barcode == null || barcode == '' || barcode == 'null') {
        barcode = 'N-A';
    }
    var pro_name = obj['product_name'];
    if(pro_name == null || pro_name == '' || pro_name == 'null') {
        pro_name = '';
    }
    var design = obj['bullion_design'];
    if(design == null || design == '' || design == 'null') {
        design = '';
    }
        var metal = obj['metal'];
    if(metal == null || metal == '' || metal == 'null' || metal == 'undefined') {
        metal = '';
    }
        var type = obj['type'];
    if(type == null || type == '' || type == 'null' || type == 'unfined' || type == undefined) {
        type = '';
    }
    
    
        var tempUrl = '';
        if(obj.metal !== null && obj.metal !== undefined){
            if(tempUrl !== ''){
                tempUrl += '-'+obj.metal; 
            }
            else{
                tempUrl += obj.metal;
            }
        }
        if(obj.type !== null && obj.type !== undefined){
            if(tempUrl !== ''){
                tempUrl += '-'+obj.type; 
            }
            else{
                tempUrl += obj.type;
            }
        }
        if(obj.gold_purity !== '' && obj.gold_purity !== 'null' && obj.gold_purity !== null && obj.gold_purity !== undefined && obj.gold_purity !== 'undefined'){
            if(tempUrl !== ''){
                var goldpty = obj.gold_purity.split('.');
                tempUrl += '-'+goldpty[0]; 
            }
            else{
                var goldpty = obj.gold_purity.split('.');
                tempUrl += goldpty[0];
            }
        }
        if(obj.gold_weight !== null && obj.gold_weight !== undefined && obj.gold_weight !== ''){
            if(tempUrl !== ''){
                var goldwt = obj.gold_weight.split('.');
                goldwt = goldwt[0].split(',');
                tempUrl += '-'+ goldwt+'-Grams'; 
            }
            else{
                var goldwt = obj.gold_weight.split('.');
                goldwt = goldwt[0].split(',');
                tempUrl += goldwt+'-Grams';
            }
        }
    
    
    var date = obj['update_time'].split(' ');
    var str = '<li>';
    str += '<div class="date fLeft"> ';
    str += '<span class="upSpan">' + date[0] + '</span>';
    str += '<span class="lwSpan">'+ date[1] +'</span>';
    str += '</div>';
    str += '<div class="barcode fLeft">';
    str += '<span class="upSpan fmOpenB">' + barcode + '</span>';
    
    if(tempUrl !== '')
    {
        str += '<span class="lwSpan"><a href="'+ DOMAIN + tempUrl + '/bid-'+obj['id']+'" target="_blank">View Details</a></span>';
    }
    else
    {
        str += '<span class="lwSpan"><a href="'+ DOMAIN + obj.metal +'/bid-'+obj['id']+'" target="_blank">View Details</a></span>';
    }
    str += '</div>';
    str += '<div class="weight fLeft">'
    str += '<span class="upSpan">' + obj['type'] + '</span>';
    str += '<span class="lwSpan textOverflow" title="'+ design +'" >'+ design +'</span>';
    str += '</div>';
    str += '<div class="metal fLeft">' + obj['metal'].split('~')[0] + '</div>';
    str += '<div class="purity fLeft">' + obj['gold_purity'] + '</div>';
    
    if(obj.gold_weight == 1000)
    {
        
        var gweights = '1 Kgs';
    }
    else if(obj.gold_weight > 1000)
    {
        obj.gold_weight = parseFloat(obj.gold_weight);
        var gweights = (obj.gold_weight/1000);
        gweights = common.number_format(gweights,2)+' Kgs';
        
    }
    else
    {
        obj.gold_weight = parseFloat(obj.gold_weight);
        obj.gold_weight = common.number_format(obj.gold_weight,2);
        var gweights = obj.gold_weight + ' Gms';
    }
    str += '<div class="btype fLeft">' + gweights + '</div>';
    str += '<div class="price fLeft fmOpenB">&#8377;' + obj['price']+ '</div>';
    str += '<div class="acct fLeft poR">';
   
    
    
    if(obj['active_flag'] == 1 || obj['active_flag'] == 0){
            cl='inStockPrd';
    }
    if(obj['active_flag'] == 3){
        cl='outStockPrd';
    }
     str += '<div class="dmyCnt fRight">';
    str += '<select id="isStock'+ obj['id'] +'" onchange="inStock(' + obj['id'] + ',this.value);" value='+obj['active_flag']+' class="txtSelect fmOpenR fLeft '+cl+'">';
    str += '<option value="1" '+((obj['active_flag']) == 1 ? "selected" : "")+'>In Stock</option>';
    str += '<option value="3" '+((obj['active_flag']) == 3 ? "selected" : "")+'>Out Of Stock</option>';
    str += '</select>';
    str += '<div class="deltBtn poR ripplelink fRight" onclick="showConfirmDelete(' + obj['id'] + ',this)"></div>';
    str += '<a href="'+ DOMAIN +'index.php?case=bullion_Form&catid=10002&prdid='+ obj['id'] +'" target="_blank"><div class="editBtn poR ripplelink fRight"></div></a>';
    str += '<a href="'+ DOMAIN + 'upload-image/pid-'+ obj['id'] +'&c='+catid+'" target="_blank"><div class="uploadBtn poR ripplelink fRight"></div></a>';
     str += '</div>';
      str += '<div class="tltpBtn">';
     str += '<div class="tltpbox transition300">';
      str += '<div class="deltBtn poR fRight ripplelink" onclick="showConfirmDelete(' + obj['id'] + ',this)"></div>';
    str += '<a href="'+ DOMAIN +'index.php?case=diamond_Form&catid=10000&prdid='+ obj['id'] +'" target="_blank"><div class="editBtn fRight poR ripplelink"></div></a>';
    str += '<a href="'+ DOMAIN + 'upload-image/pid-'+ obj['id'] +'&c='+catid+'" target="_blank"><div class="uploadBtn fRight poR ripplelink"></div></a>';
         str += '<select id="isStock'+ obj['id'] +'" onchange="inStock(' + obj['id'] + ',this.value);" value='+obj['active_flag']+' class="txtSelect fmOpenR fLeft '+cl+'">';
    str += '<option value="1" '+((obj['active_flag']) == 1 ? "selected" : "")+'>In Stock</option>';
    str += '<option value="3" '+((obj['active_flag']) == 3 ? "selected" : "")+'>Out Of Stock</option>';
    str += '</select>';   
      str += '</div>';
     str += '</div>';
     
     
    str += '</div>';
    str += '</li>';
    str += '';
    return str;
}
var catName='';

var productDelId = '', productDelEle = '';

function closeConfirmDelete()
{
	productDelId = '', productDelEle = '';
	$('#confirmDelete').velocity({scale: 0}, {delay: 0, ease: 'swing'});
	$('#overlay').velocity({opacity: 0}, {delay: 100, ease: 'swing'});
	setTimeout(function () {
		$('#overlay,#confirmDelete').addClass('dn');
                window.location.reload();
	}, 1010);
        
}

function showConfirmDelete(proId, ele)
{
	productDelId = proId;
	productDelEle = ele;
	$('#overlay,#confirmDelete').removeClass('dn');
	setTimeout(function () {
		$('#overlay').velocity({opacity: 1}, {delay: 0, duration: 300, ease: 'swing'});
		$('#confirmDelete').velocity({scale: 1}, {delay: 80, duration: 100, ease: 'swing'});
	}, 10);
}


function deleteProduct() {
	var proId = productDelId;
	var ele = productDelEle;
    $.ajax({url: common.APIWebPath() + "index.php?action=vDeletePrd&vid=" + uid + "&prdid=" + proId, success: function (result) {
        var obj = jQuery.parseJSON(result);
        if(obj['error']['Code']==0) {
            $(ele).parent().parent().parent().remove();
            if(catid==10000) {
                catName='Diamonds';
            } else if(catid==10001) {
                catName='Jewells';
            } else if(catid==10002) {
                catName='Bullions';
            }
            var total=$('#total'+catName).text();
            total--;
            $('#total'+catName).text(total);
            if(total==0) {
                loadBullion = false;
            }
            var count = $("#"+catName+"List li").length;
            if(count<15) {
                $("#"+catName+"List").html('');
                if(catid==10000) {
                    diamondPage = 1;
                    loadDiamonds();
                } else if(catid==10001) {
                    jewellPage = 1;
                    loadJewels();
                } else if(catid==10002) {
                    bullionPage = 1;
                    loadBullions();
                }
            }
            common.toast(1,obj['error']['Msg']);
        } else {
            common.toast(0,obj['error']['Msg']);
        }
    }});
	closeConfirmDelete();
}

function inStock(proId,ele)
{
    var tmstmp = new Date().getTime();
    $.ajax({url: common.APIWebPath() + "index.php?action=togglePrdstatus&vid=" + uid + "&prdid=" + proId+"&flag="+ele+"&timestamp="+tmstmp, success: function (result)
        {
            var obj = jQuery.parseJSON(result);
            if(obj['error']['Code']==0)
            {
                var stockid="#isStock"+proId;
                //$(ele).parent().parent().parent().remove();
                
                if(catid==10000) 
                {
                    catName='Diamonds';
                }
                else if(catid==10001)
                {
                    catName='Jewells';
                }
                else if(catid==10002)
                {
                    catName='Bullions';
                }
                var total=$('#total'+catName).text();
                $('#total'+catName).text(total);
                if(ele == 1)
                {
                    common.toast(4,"Product is in stock");
                    $(stockid).removeClass('soldStockPrd outStockPrd');
                    $(stockid).addClass('inStockPrd');
                }
                if(ele == 3)
                {
                    common.toast(2,"Product is out of stock");
                    $(stockid).removeClass('inStockPrd soldStockPrd');
                    $(stockid).addClass('outStockPrd');
                }
                if(ele == 4)
                {
                    common.toast(3,"Product is sold");
                    $(stockid).removeClass('inStockPrd outStockPrd');
                    $(stockid).addClass('soldStockPrd');
                }
//            $('#'+stockid).toggleClass('outofstock');
            }
            else
            {
                common.toast(0,obj['error']['Msg']);
            }
        }
    });
}

var searchScrollValue= '';
var searchScroll= false;
var searchIDName = '';
var searchPage = 1;
var searchVal = '';
$('.prdSeachTxt').val('');

function searchBarcode(val,pgno) {
	if(!pgno)
		pgno = 1;
	searchVal = val;
    if(catid==10000) {
        searchIDName='Diamonds';
    }else if(catid==10001) {
        searchIDName='Jewells';
    } else if(catid==10002) {
        searchIDName='Bullions';
    }
    if(val!='')
    {
        var tmstmp = new Date().getTime();
        $.ajax({url: common.APIWebPath() + "index.php?action=getVProductsByBcode&bcode="+ val +"&vid="+ uid +"&catid="+catid+"&page="+pgno+"&limit=50"+"&timestamp="+tmstmp, success: function (result) {
            searchBarcodeCallback(result,pgno);
        }});
        searchScrollValue = val;
    }
    else
    {
        $('#'+searchIDName+'List').removeClass('dn');
        $('#s'+searchIDName+'List').html('').addClass('dn');
    }
}
function searchBarcodeCallback(res,pgno) {
    var obj = jQuery.parseJSON(res);
    if (obj['results'] !== '') {
        var total = obj['results']['total_products'];
        //$('#total'+searchIDName).text(total);
        
        $('#'+searchIDName+'List').addClass('dn');
        $('#s'+searchIDName+'List').removeClass('dn');
        if(total!=0) {
            searchScroll = true;
            var len = obj['results']['products'].length;
            var i = 0;
            var catName;
            if(catid==10000) {
                var str = '';
                while (i < len) {
                    str += generateDiamondList(obj['results']['products'][i]);
                    i++;
                }
            } else if(catid==10001) {
                var str = '';
                while (i < len) {
                    str += generateJewellList(obj['results']['products'][i]);
                    i++;
                }
            } else if(catid==10002) {
                var str = '';
                while (i < len) {
                    str += generatBullionsList(obj['results']['products'][i]);
                    i++;
                }
            }
			var html = pagination(obj,pgno);
            if(str !== undefined && str !== null && str !== '')
            {
                //$('#s'+searchIDName+'List').append(str);
				$('#s'+searchIDName+'List').html(str);
				$('#s'+searchIDName+'List').append(html);
				
				$('.pgComm').click( function(){
					
					$('.pgComm').removeClass('pgActive');
					$(this).addClass('pgActive');
					searchBarcode(searchVal,$(this).text());
					$('#pgno').val($(this).text());
					$('html,body').animate({scrollTop: $('.prdResults').offset().top-100}, 300);
				});
            }
        }
        else if(searchPage==1) {
            searchScroll = false;
            var str = '<p class="noRecords"><span>Sorry! No Products Found!</span></p>';
            $('#s'+searchIDName+'List').html(str);
        }
    } else {
        searchScroll = false;
        var str = '<p class="noRecords"><span>Sorry! No Products Found!</span></p>';
        $('#s' + searchIDName + 'List').html(str);
    }
}
