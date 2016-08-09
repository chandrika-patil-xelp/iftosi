var uid = customStorage.readFromStorage('userid');
var is_vendor = customStorage.readFromStorage('is_vendor');
var userName = customStorage.readFromStorage('name');
$('#userName').html(userName.split(' ')[0]);
$(document).ready( function()
{
    loadcustomer(1);
});

var loadCustomers = true;
var currentPage = 1;
function loadcustomer(pgno)
{

    var tmstmp = new Date().getTime();
    $.ajax({url: APIDOMAIN + "index.php?action=customerList&pgno=" + pgno + "&limit=50"+"&timestamp="+tmstmp, success: function (result)
            {
                loadCustomerCallback(result,pgno);
            }
    });
}
function loadCustomerCallback(res,pgno)
{
    var obj = jQuery.parseJSON(res);
    if (obj['results'] != '')
    {
        $('#pgno').val(pgno);
        var total = obj['total_customers'];
        $('#customerList').text('Customer ('+total+')');
        var total_pages = obj['total_customers'];
        var str = '';
        if(total != 0)
        {
            if(total_pages==currentPage)
            {
                loadCustomers = false;
            }
            var len = obj['results'].length;
            var i = 0;
            while (i < len)
            {
                console.log(obj['results']);
                str += customerList(obj['results'][i]);
                i++;
            }
            var html = pagination(obj,pgno);
        }
        else
        {
            str = '<div class="noRecords"><span>Sorry! No Customer Found!</span></div>';
            loadcustomers = false;
        }
        $('.cntctCard').html(str);
        $('.scntctCard').html(str);
        $('.pgComm').click(function ()
        {
            $('.pgComm').removeClass('pgActive');
            $(this).addClass('pgActive');
            loadcustomer($(this).text());
            $('html,body').animate({scrollTop: $('.listContainer').offset().top - 100}, 300);
        });
        paginationPrevNext();
    }
    else
    {
        loadcustomers = false;
    }
}
    function customerList(obj)
    {
        var str = '';
        $(obj).each(function(i,vl)
        {
            str+='<div class="cDtls fLeft" id="customer_'+vl.uid+'">';
                str+='<div class="cList fLeft">';
                    str+='<div class="div15 fLeft txtCenter">'+vl.joinDate+'</div>';
                    str+='<div class="div22 fLeft txtCenter">'+vl.name+'</div>';
                    str+='<div class="div25 fLeft"><div class="emIcon fLeft">'+vl.email+'</div></div>';
                    str+='<div class="div18 fLeft txtCenter"><div class="mbIcon fLeft">'+vl.mobile+'</div></div>';
                    str+='<div class="div20 fLeft txtCenter bRnone">'+vl.city+" ( "+vl.state+' )</div>';
                str+='</div>';
            str+='</div>';
            str += '</div>';
        });
        return str;
    }
    function pagination(data, pgno)
    {
        pgno = pgno * 1;
        var html = '';
        var tc = data.total_customers;
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
    $('.pPrev').bind('click', function()
    {
        var curpgno = parseInt($('#pgno').val());
        var pgval = curpgno - 1;
        if(curpgno > 1)
        {
            $('#pgno').val(pgval);
            if(pageName.indexOf("customerList") !== -1)
            {
                loadcustomer(pgval);
            }
            else
            {
                loadcustomer(1);
            }
        }
    });
    $('.pNext').bind('click', function()
    {
        var curpgno = parseInt($('#pgno').val());
        var pgval = curpgno + 1;
        if(curpgno < parseInt($('#total_pageno').val()))
        {
            $('#pgno').val(pgval);
            if(pageName.indexOf("customerList") !== -1)
            {
                loadcustomer(pgval);
            }
            else
            {
                loadcustomer(1);
            }
        }
    });
}
loadcustomer(1);
$('.pPrev').bind('click', function()
{
    var curpgno = parseInt($('#pgno').val());
    var pgval = curpgno - 1;
    if(curpgno > 1)
    {
        $('#pgno').val(pgval);
        if(pageName.indexOf("customerList") !== -1)
        {
            loadcustomer(pgval);
        }
        else
        {
            loadcustomer(1);
        }
    }
});

$('.pNext').bind('click', function()
{
    var curpgno = parseInt($('#pgno').val());
    var pgval = curpgno + 1;
    if(curpgno < parseInt($('#total_pageno').val()))
    {
        $('#pgno').val(pgval);
        if(pageName.indexOf("customerList") !== -1)
        {
            loadcustomer(pgval);
        }
        else
        {
            loadcustomer(1);
        }
    }
});

function searchVendor(val,pgno)
{
    if(!pgno)
        pgno = 1;
    if( val != '')
    {
        var tmstmp = new Date().getTime();
        $.ajax({url: DOMAIN + "apis/index.php?action=customerList&srchTxt="+ encodeURIComponent(val) +"&page="+pgno+"&limit=50"+"&timestamp="+tmstmp, success: function (result)
                {
                    searchVendorCallback(result,pgno);
                }
        });
        searchScrollValue = val;
    }
    else
    {
        $('.cntctCard').removeClass('dn');
        $('.scntctCard').html('').addClass('dn');
    }
}
function searchVendorCallback(res,pgno)
{
    var obj = eval('('+res+')');
    if (obj.results !== '')
    {
        var total = obj.total_customers;
        if(total != 0)
        {
            $('.cntctCard').addClass('dn');
            $('.scntctCard').removeClass('dn');
            var len = obj.results.length;
            var i = 0;
                var str = '';
                while (i < len)
                {
                    str += customerList(obj.results[i]);
                    i++;
                }
                var html = pagination(obj,pgno);
                if(str !== undefined && str !== null && str !== '')
                {
                    $('.scntctCard').html(str);

                    $('.pgComm').click( function()
                    {
                            $('.pgComm').removeClass('pgActive');
                            $(this).addClass('pgActive');
                            var searchCustomer = $('#searchVendor').val();
                            searchVendor(searchCustomer,$(this).text());
                            $('#pgno').val($(this).text());
                            $('html,body').animate({scrollTop: $('.scntctCard').offset().top-100}, 300);
                    });
                }
        }
        else if(pgno==1)
        {
            var str = '<div class="noResult fLeft fmopenR"><span>Sorry! No Vendors Found!</span></div>';
            $('.scntctCard').html(str);
        }
    }
}
