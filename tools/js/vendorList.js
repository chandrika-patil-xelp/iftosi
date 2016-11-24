    var uid = customStorage.readFromStorage('userid');
    var is_vendor = customStorage.readFromStorage('is_vendor');
    var userName = customStorage.readFromStorage('name');
    $('#userName').html(userName.split(' ')[0]);
    $(document).ready( function(){
        /*$('.toggle-button').on('click', function() {function
            $(this).toggleClass('toggle-button-selected');
        });*/
    });



    function changeStatus(uid,obj) {
        var st = 0;
        setTimeout(function() {
            var pLeft = $(obj).find(".button").position().left;
            if(pLeft == 0)
            {
                st = 1;
            }
            else
            {
                st = 0;
            }
            //st = pLeft;
            var tmstmp = new Date().getTime();
            var URL = DOMAIN+ "index.php?action=ajx&case=updateStatus&userid=" + uid +"&af="+st+"&timestamp="+tmstmp;
            $.ajax({
                url: URL,
                type: 'POST',
                success: function(res) {
                    var expObj = eval('('+res+')');
                    var expiry = expObj.results.expiry;
                    
                    if(expiry !== null && expiry !== undefined && expiry !== 'undefined' && expiry !== 'null' && expiry !== '')
                    {
                        $('#vndExpDate'+uid).text(expiry);
                    }
                    if(uid !== null)
                    {
                        var params = 'action=Vpactive&vid='+uid+'&af='+st+"&timestamp="+tmstmp;
                        var URL = APIDOMAIN + "index.php";

                        $.getJSON(URL, params, function(data) {
                            if(data !== null && data !== undefined && data !== '' && data !== 'null' && data !== 'undefined' && typeof data !== 'undefined')
                            {
                                if(data.error !== '' && data.error !== null && data.error !== undefined && data.error !== 'undefined' && typeof data.error !== 'undefined' && data.error.code == 0)
                                {
                                    $(obj).toggleClass('toggle-button-selected');
                                    if(st == 1)
                                    {
                                        customStorage.toast(1, 'Vendor status is changed');
                                    }
                                    else if(st == 0)
                                    {
                                        customStorage.toast(0, 'Vendor status is changed');
                                    }
                                }
                            }
                        });
                    }
             }
            });
        }, 350);
    }

    var loadProduct = true;
    var currentPage = 1;
function loadProducts(pgno) {
    var tmstmp = new Date().getTime();
    $.ajax({url: APIDOMAIN + "index.php?action=vendorlist&pgno=" + pgno + "&limit=50"+"&timestamp="+tmstmp, success: function (result) {
        loadProductsCallback(result,pgno);
    }});
}
function loadProductsCallback(res,pgno) {
    var obj = jQuery.parseJSON(res);
    if (obj['results'] != '') {
        $('#pgno').val(pgno);
        var total = obj['results']['total_vendors'];
        $('#totalProducts').text(total);
        var total_pages = obj['results']['total_vendors'];
        
        var str = '';
        if(total != 0) {
            if(total_pages==currentPage) {
                loadProduct = false;
            }
            var len = obj['results']['vendors'].length;
            var i = 0;
            while (i < len) {
                str += generateVendorsList(obj['results']['vendors'][i]);
                i++;
            }
            var html = pagination(obj,pgno);
        } else {
            str = '<div class="noRecords"><span>Sorry! No Vendors Found!</span></div>';
            loadProduct = false;
        }
        $('#vendorLists').html(str);
        $('#vendorLists').append(html)
        $('.pgComm').click(function () {
            $('.pgComm').removeClass('pgActive');
            $(this).addClass('pgActive');
            loadProducts($(this).text());
            $('html,body').animate({scrollTop: $('.listContainer').offset().top - 100}, 300);
        });
        paginationPrevNext();
    } else {
        loadProduct = false;
    }
}
        function generateVendorsList(obj) {
            
            var str = '<div class="listComm fLeft fmOpenR">';
            str += '<div class="fLeft vndName poR">';
            str +='<div class="infrIcn" onclick="openlogin()"></div>';
            str += '   <div class="nameLocatCont fLeft txtOver hedofc" id="cn_14267019859310">'+obj['orgName']+' -'+obj['city']+'</div>';
            str += '    <div class="mobileEmailCont fLeft cBlue txtOver">';
            str += '        <div class="fLeft font12">';
            str += '            <div class="mbIcon fLeft" id="mo_14267019859310">'+obj['contact_mobile']+'</div>';
            str += '            <div class="emIcon fLeft txtOver" id="em_14267019859310" txtOver>'+obj['email']+'</div>';
                                
            str += '        </div>';
            str += '        <div class="fLeft font12">';
            str += '            <div class="pnIcon fLeft" id="mo_14267019859310">'+obj['pancard']+'</div>';
            str += '            <div class="vatIcon fLeft txtOver" id="em_14267019859310">'+obj['vatno']+'</div>';
                  
            str += '        </div>';
            str += '    </div>';
            str += '</div>';
            var cost=obj['pay_amount'];
            var vid = obj['vendor_id'];
            var package=new Array();
            var business_type = obj['business_type'].split(',');
            var expiry = obj.expiry_show;
            if(expiry !='Not Available')
            {
                var sbstr= expiry.substring(expiry.indexOf(" "),expiry.indexOf(" ")+4);
                var expiry = expiry.replace(expiry.substring(expiry.indexOf(" "),expiry.indexOf(",")),sbstr);
            }
            
            for(var j = 0; j < business_type.length; j++) {
                if(business_type[j] == 1)
                {
                    package.push('Diamonds');
                }
                if(business_type[j] == 2)
                {
                    package.push('Jewellery');
                }
                if(business_type[j] == 3)
                {
                    package.push('Bullion');
                }
            }
            
            str += '<div class="fLeft vndPackage">'+package.join()+'</div>';
            str += '<div class="fLeft vndAmount">'+cost+'</div>';
            str += '<div class="fLeft vndExpDate" id="vndExpDate'+vid+'">'+expiry+'</div>';
            str += '<div class="fLeft vndEdit">';
            var className='';
            if(obj['active_flag']=='1'){
                className='toggle-button-selected';
            }
            str += '    <div class="toggle-button '+className+'" onclick="changeStatus('+obj['vendor_id']+',this)">';
            str += '        <span class="fActive">Yes</span>';
            str += '        <button class="button"></button>';
            str += '        <span class="fDactive">No</span>';
            str += '    </div>';
            str += '</div>';
            str += '</div>';
            return str;
        }

        function pagination(data, pgno) {
            /* For Pagintion */
            pgno = pgno * 1;
            var html = '';
            var tc = data.results.total_vendors;
            var lastpg = Math.ceil(tc / 50);
            var adjacents = 2;
            $('#total_pageno').val(lastpg);
            if (lastpg > 1)
            {
                html += '<div class="fLeft pagination fmOpenR">';
                html += '<center>';
                html += '<div class="pPrev poR ripplelink">Previous</div>';
                if (lastpg < 7 + (adjacents * 2))
                {
                    for (var i = 1; i <= lastpg; i++)
                    {
                        if (i == pgno)
                        {
                            html += '<div class="pgComm poR ripplelink pgActive">' + i + '</div>';
                        }
                        else
                        {
                            html += '<div class="pgComm poR ripplelink">' + i + '</div>';
                        }
                    }
                }
                else if (lastpg > 5 + (adjacents * 2))
                {
                    if (pgno < 1 + (adjacents * 2))
                    {
                        for (var i = 1; i < 4 + (adjacents * 2); i++)
                        {
                            if (i == pgno)
                            {
                                html += '<div class="pgComm poR ripplelink pgActive">' + i + '</div>';
                            }
                            else
                            {
                                html += '<div class="pgComm poR ripplelink">' + i + '</div>';
                            }
                        }
                        html += '<div class="pgEmpty"></div>';
                        html += '<div class="pgEmpty"></div>';
                        html += '<div class="pgEmpty"></div>';
                        html += '<div class="pgComm poR ripplelink">' + lastpg + '</div>';
                    }
                    else if (lastpg - (adjacents * 2) > pgno && pgno > (adjacents * 2))
                    {
                        html += '<div class="pgComm poR ripplelink">1</div>';
                        html += '<div class="pgEmpty"></div>';
                        html += '<div class="pgEmpty"></div>';
                        html += '<div class="pgEmpty"></div>';
                        for (var i = pgno - adjacents; i <= pgno + adjacents; i++)
                        {
                            if (i == pgno)
                            {
                                html += '<div class="pgComm poR ripplelink pgActive">' + i + '</div>';
                            }
                            else
                            {
                                html += '<div class="pgComm poR ripplelink">' + i + '</div>';
                            }
                        }
                        html += '<div class="pgEmpty"></div>';
                        html += '<div class="pgEmpty"></div>';
                        html += '<div class="pgEmpty"></div>';
                        html += '<div class="pgComm poR ripplelink">' + lastpg + '</div>';
                    }
                    else
                    {
                        html += '<div class="pgComm poR ripplelink">1</div>';
                        html += '<div class="pgEmpty"></div>';
                        html += '<div class="pgEmpty"></div>';
                        html += '<div class="pgEmpty"></div>';
                        for (var i = lastpg - (2 + (adjacents * 2)); i <= lastpg; i++)
                        {
                            if (i == pgno)
                            {
                                html += '<div class="pgComm poR ripplelink pgActive">' + i + '</div>';
                            }
                            else
                            {
                                html += '<div class="pgComm poR ripplelink">' + i + '</div>';
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
			if(pageName.indexOf("vendorList") !== -1)
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
                                loadProducts(pgval);
			}
			else
			{   
				loadProducts(1);
			}
		}
            });
            	$('.pNext').bind('click', function() {
		var curpgno = parseInt($('#pgno').val());
		var pgval = curpgno + 1;
		if(curpgno < parseInt($('#total_pageno').val()))
		{
			$('#pgno').val(pgval);
			if(pageName.indexOf("vendorList") !== -1)
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
                                loadProducts(pgval);
			}
			else
			{
				loadProducts(1);
			}
		}
	});
}
    loadProducts(1);
        $('.pPrev').bind('click', function() {

            var curpgno = parseInt($('#pgno').val());
            	var pgval = curpgno - 1;
		if(curpgno > 1)
		{
			$('#pgno').val(pgval);
			if(pageName.indexOf("vendorList") !== -1)
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
                                loadProducts(pgval);
			}
			else
			{   
				loadProducts(1);
			}
		}
	});

	$('.pNext').bind('click', function() {
		var curpgno = parseInt($('#pgno').val());
		var pgval = curpgno + 1;
		if(curpgno < parseInt($('#total_pageno').val()))
		{
			$('#pgno').val(pgval);
			
			if(pageName.indexOf("vendorList") !== -1)
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
                                loadProducts(pgval);
			}
			else
			{
				loadProducts(1);
			}
		}
	});
        
function searchVendor(val,pgno) {
	if(!pgno)
            pgno = 1;
    if( val != '')
    {
        var tmstmp = new Date().getTime();
        $.ajax({url: DOMAIN + "apis/index.php?action=getVendorBySearch&srchTxt="+ encodeURIComponent(val) +"&page="+pgno+"&limit=50"+"&timestamp="+tmstmp, success: function (result) {
            searchVendorCallback(result,pgno);
        }});
        searchScrollValue = val;
    }
    else
    {
        $('#vendorLists').removeClass('dn');
        $('#svendorLists').html('').addClass('dn');
    }
}
function searchVendorCallback(res,pgno)
{
    
    var obj = eval('('+res+')');
    if (obj.results !== '')
    {
            var total = obj.results.total_vendors;
            
            if(total != 0)
            {
                $('#vendorLists').addClass('dn');
                $('#svendorLists').removeClass('dn');
                var len = obj.results.vendors.length;
                var i = 0;
                    var str = '';
                    while (i < len)
                    {
                        str += generateVendorsList(obj.results.vendors[i]);
                        i++;
                    }
                var html = pagination(obj,pgno);
                if(str !== undefined && str !== null && str !== '')
                {
                    $('#svendorLists').append(str);
                    $('#svendorLists').html(str);
                    $('#svendorLists').append(html);

                    $('.pgComm').click( function()
                    {

                            $('.pgComm').removeClass('pgActive');
                            $(this).addClass('pgActive');
                            var searchVendor = $('#searchVendor').val();
                            searchVendor(searchVendor,$(this).text());
                            $('#pgno').val($(this).text());
                            $('html,body').animate({scrollTop: $('#svendorLists').offset().top-100}, 300);
                    });
                }
            }
            else if(pgno==1)
            {
                var str = '<div class="noResult fLeft fmopenR"><span>Sorry! No Vendors Found!</span></div>';
                $('#svendorLists').html(str);
            }
}
}
        