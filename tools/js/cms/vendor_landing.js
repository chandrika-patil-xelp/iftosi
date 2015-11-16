var forDate;
$('#datepicker').datepicker({
    onSelect: function (dateText, inst) {
        var dateText = $.datepicker.formatDate("yy-mm-dd", new Date(dateText));
        $(this).val(dateText);
        $('#' + forDate).val(dateText);
        if (forDate === 'dateTo') {

        }

        $('#datepicker').addClass('dn');
    },
    maxDate: "+0D"
});

function showCalander(inpDiv) {
    forDate = inpDiv;
    var pos = $('#' + inpDiv).position();
    var pTop = pos.top + 35;
    $('#datepicker').css({top: pTop + "px", left: 45 + "px"});
    $('#datepicker').removeClass('dn');
}

function checkToHide(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode === 8) {
        $('#datepicker').addClass('dn');
    }
}

var active = 'diamondFheader';

var mxSc = 170;//$('.prdResults').offset().top;

var lastSc = 0;
$(document).ready(function () {
    $('.vTabs').eq(1).click();

    $(window).scroll(function () {
        var sc = $(window).scrollTop();
        if (sc > mxSc) {
            $('#' + active).removeClass('pLHtransit dn');
        } else if (lastSc > sc) {
            $('#' + active).addClass('pLHtransit');
        }
        lastSc = sc;
    });





    $('#dmdTab,#jewTab,#bullTab').bind('click', function () {
        var id = $(this).attr('id');

        switch (id) {
            case'dmdTab':
                active = 'diamondFheader';
                $('#diamondPrds').removeClass('dn');
                $('#jewelleryPrds,#bullionPrds').addClass('dn');
                break;
            case'jewTab':
                active = 'jewelleryFheader';
                $('#jewelleryPrds').removeClass('dn');
                $('#diamondPrds,#bullionPrds').addClass('dn');
                break;
            case'bullTab':
                active = 'bullionFheader';
                $('#bullionPrds').removeClass('dn');
                $('#diamondPrds,#jewelleryPrds').addClass('dn');
                break;
        }

    });

    $('.vFilBtn').click(function () {
        showEnqFilter();
    });


    $('.shapeComm').bind('click', function ()
    {
        var uthis = $(this);
        $('.shapeComm').each(function () {
            if ($(this).hasClass('shapeSelected') && $(this).attr('id') != uthis.attr('id'))
                $(this).removeClass('shapeSelected');
        });
        $(this).toggleClass('shapeSelected');
    });

    $('.jshapeComm').bind('click', function ()
    {
        var uthis = $(this);
        $('.jshapeComm').each(function () {
            if ($(this).hasClass('shapeSelected') && $(this).attr('id') != uthis.attr('id'))
                $(this).removeClass('shapeSelected');
                $("input[type=checkbox]").prop("checked", false);
        });
        $(this).toggleClass('shapeSelected');
        if ($(this).hasClass('shapeSelected'))
        {
            var tmpId = $(this).attr('id');
            tmpId = tmpId.toLowerCase();
            $('.subCatType').addClass('dn');
            $('#' + tmpId + 'Type').removeClass('dn');
        }
        else
        {
            var tmpId = $(this).attr('id');
            tmpId = tmpId.toLowerCase();
            $('#' + tmpId + 'Type').addClass('dn');
        }

        var bthis = $(this);

        if ($(this).hasClass('shapeSelected'))
        {
            var id = $(this).attr('id');
            var tmpId = id.toLowerCase();

            if ((tmpId == 'gbar') || (tmpId == 'gcoin'))
            {
                $('.allprop').addClass('dn');
                $('.goldprop').removeClass('dn');
            }
            else if ((tmpId == 'sbar') || (tmpId == 'scoin'))
            {
                $('.allprop').addClass('dn');
                $('.silverprop').removeClass('dn');
            }
        }
        else
        {
            $('.goldprop').addClass('dn');
            $('.silverprop').addClass('dn');
            $('.allprop').removeClass('dn');
        }
    });

    //$(this).toggleClass('shapeSelected');

});





var isOpen = false;
var id = "";
$('.dropInput').mouseover(function () {
    id = $(this).attr('id');
    setTimeout(function () {
        isOpen = true;
    }, 50);
    toggleDropDown(true, id);
});


$('.dropList').mouseleave(function () {
    toggleDropDown(false, id);
});

$(document).click(function () {
    if (isOpen) {
        toggleDropDown(false, id);
    }
});

$('.vTabs').mouseover(function () {
    var tid = $(this).attr('id');
    if (tid !== 'prdTab') {
        if (isOpen) {
            toggleDropDown(false, id);
        }
    }
});


$('.vTabs').click(function () {
    var id = $(this).attr('id');
    $('.vTabs').removeClass('vSelected');
    $(this).addClass('vSelected');

    switch (id) {
        case 'dashTab':
            active = '';
            $('#product,#enquiry,#settings').addClass('dn');
            $('#dashboard').removeClass('dn');
            break;
        case 'prdTab':
            $('#dashboard,#enquiry,#settings').addClass('dn');
            $('#product').removeClass('dn');
            break;
        case 'enqTab':
            active = 'enqFheader';
            $('#dashboard,#product,#settings').addClass('dn');
            $('#enquiry').removeClass('dn');
            break;
        case 'setTab':
            active = '';
            $('#dashboard,#product,#enquiry').addClass('dn');
            $('#settings').removeClass('dn');
            break;
    }
});

function toggleDropDown(flag, id) {
    if (flag) {
        $("#" + id + "List").velocity({opacity: 1, borderRadius: 0}, {duration: 200, display: "block"});
    }
    else {
        $("#" + id + "List").velocity({opacity: 0, borderRadius: '100%'}, {duration: 50, display: "none"});
    }

}

$('#priceRange').ionRangeSlider({
    type: "double",
    grid: true,
    min: 1000,
    max: 1000000,
    from: 0,
    to: 500000,
    decorate_both: false,
    prettify_separator: ",",
    force_edges: true,
    drag_interval: true,
    step: 1,
    onFinish: function (data) {
        //FR();
    }
});


function showEnqFilter() {
    var flag = $('.v_Filters').hasClass('transit100X');
    if (flag) {
        $('.v_Filters').removeClass('transit100X');
    } else {
        $('.v_Filters').addClass('transit100X');
    }

}


function submitDForm() {
    window.location.href = 'http://localhost/iftosi/?case=vendor_landing';

    /*
     values={};
     values['shape']=$('.shapeSelected').attr('id');
     
     y=$('#dAddForm').serializeArray();
     $(y).each(function(i){
     values[y[i].name]=y[i].value;
     });
     console.log(values);
     alert('submit ' +values);*/
}

var uid = common.readFromStorage('uid');
var loadDiamont = true;
var diamondPage = 1;
$(window).scroll(function () {
    if ($(window).scrollTop() == ($(document).height() - $(window).height())) {
        if (active == 'diamondFheader' && loadDiamont) {
            loadDiamonts();
        }
        if (active == 'jewelleryFheader' && loadJewel) {
            loadJewels();
        }
        if (active == 'bullionFheader' && loadBullion) {
            loadBullions();
        }
    }
});
loadDiamonts();
function loadDiamonts() {
    $.ajax({url: common.APIWebPath() + "index.php?action=getVproducts&vid=" + uid + "&page=" + diamondPage + "&limit=15&catid=10000", success: function (result) {
            loadDiamondCallback(result);
        }});
}
function loadDiamondCallback(res) {
    var obj = jQuery.parseJSON(res);
    if (obj['results'] != '') {
        var total = obj['results']['total_products'];
        $('#totalDiamonds').text(total);
        var total_pages = obj['results']['total_pages'];
        var len = obj['results']['products'].length;
        var i = 0;
        var str = '';
        while (i < len) {
            str += generateDiamondList(obj['results']['products'][i]);
            i++;
        }
        $('#diamondList').append(str);
        diamondPage++;
    } else {
        loadDiamont = false;
    }
}
function generateDiamondList(obj) {
    var date = obj['update_time'].split(' ');
    var str = '<li>';
    str += '<div class="date fLeft"> ';
    str += '<span class="upSpan">' + date[0] + '</span>';
    str += '<span class="lwSpan">' + date[1] + '</span>';
    str += '</div>';
    str += '<div class="barcode fLeft">';
    str += '<span class="upSpan">' + obj['barcode'] + '</span>';
    str += '<span class="lwSpan"><a href="http://localhost/iftosi//gia-round-clarity-IF/did-'+ obj['id'] +'">View Details</a></span>';
    str += '</div>';
    str += '<div class="shape fLeft">' + obj['shape'] + '</div>';
    str += '<div class="color fLeft">' + obj['color'] + '</div>';
    str += '<div class="carats fLeft">' + obj['carat'] + '</div>';
    str += '<div class="clarity fLeft">' + obj['clarity'] + '</div>';
    str += '<div class="cert fLeft">' + obj['cert'] + '</div>';
    str += '<div class="price fLeft fmOpenB">&#8377;' + obj['price'] + '</div>';
    str += '<div class="acct fLeft">';
    str += '<center>';
    str += '<div class="deltBtn poR ripplelink"></div>';
    str += '<a href="http://localhost/iftosi/index.php?case=diamond_Form&catid=10000&prdid='+ obj['id'] +'"><div class="editBtn poR ripplelink"></div></a>';
    str += '<div class="soldBtn poR ripplelink fmOpenR">SOLD</div>';
    str += '</center>';
    str += '</div>';
    str += '</li>';
    str += '';
    return str;
}

var loadJewel = true;
var jewellPage = 1;

loadJewels();
function loadJewels() {
    $.ajax({url: common.APIWebPath() + "index.php?action=getVproducts&vid=" + uid + "&page=" + jewellPage + "&limit=15&catid=10001", success: function (result) {
            loadJewellCallback(result);
        }});
}
function loadJewellCallback(res) {
    var obj = jQuery.parseJSON(res);
    if (obj['results'] != '') {
        var total = obj['results']['total_products'];
        $('#totalJewells').text(total);
        var total_pages = obj['results']['total_pages'];
        var len = obj['results']['products'].length;
        var i = 0;
        var str = '';
        while (i < len) {
            str += generateJewellList(obj['results']['products'][i]);
            i++;
        }
        $('#JewellList').append(str);
        jewellPage++;
    } else {
        loadJewel = false;
    }
}
function generateJewellList(obj) {
    var date = obj['update_time'].split(' ');
    var str = '<li>';
    str += '<div class="date fLeft"> ';
    str += '<span class="upSpan">' + date[0] + '</span>';
    str += '<span class="lwSpan">'+ date[1] +'</span>';
    str += '</div>';
    str += '<div class="barcode fLeft">';
    str += '<span class="upSpan">' + obj['barcode'] + '</span>';
    str += '<span class="lwSpan"><a href="http://localhost/iftosi//gia-round-clarity-IF/jid-'+ obj['id'] +'">View Details</a></span>';
    str += '</div>';
    str += '<div class="degno fLeft">' + obj['lotref'] + '</div>';
    str += '<div class="metal fLeft">' + obj['metal'].split('~')[0] + '</div>';
    str += '<div class="catg fLeft">' + obj['category'][1]['cat_name'] + '</div>';
    str += '<div class="subType fLeft">' + obj['category'][0]['cat_name'] + '</div>';
    str += '<div class="price fLeft fmOpenB">&#8377;' + obj['price'] + '</div>';
    str += '<div class="acct fLeft">';
    str += '<center>';
    str += '<div class="deltBtn poR ripplelink"></div>';
    str += '<a href="http://localhost/iftosi/index.php?case=jewellery_Form&catid=10001&prdid='+ obj['id'] +'"><div class="editBtn poR ripplelink"></div></a>';
    str += '<div class="soldBtn poR ripplelink fmOpenR">SOLD</div>';
    str += '</center>';
    str += '</div>';
    str += '</li>';
    str += '';
    return str;
}

var loadBullion = true;
var bullionPage = 1;

loadBullions();
function loadBullions() {
    $.ajax({url: common.APIWebPath() + "index.php?action=getVproducts&vid=" + uid + "&page=" + bullionPage + "&limit=15&catid=10002", success: function (result) {
            loadBullionsCallback(result);
        }});
}
function loadBullionsCallback(res) {
    var obj = jQuery.parseJSON(res);
    if (obj['results'] != '') {
        var total = obj['results']['total_products'];
        $('#totalBullions').text(total);
        var total_pages = obj['results']['total_pages'];
        var len = obj['results']['products'].length;
        var i = 0;
        var str = '';
        while (i < len) {
            str += generatBullionsList(obj['results']['products'][i]);
            i++;
        }
        $('#bullionsList').append(str);
        bullionPage++;
    } else {
        loadBullion = false;
    }
}
function generatBullionsList(obj) {
    var date = obj['update_time'].split(' ');
    var str = '<li>';
    str += '<div class="date fLeft"> ';
    str += '<span class="upSpan">' + date[0] + '</span>';
    str += '<span class="lwSpan">'+ date[1] +'</span>';
    str += '</div>';
    str += '<div class="barcode fLeft">';
    str += '<span class="upSpan">' + obj['barcode'] + '</span>';
    str += '<span class="lwSpan"><a href="http://localhost/iftosi//gia-round-clarity-IF/bid-'+ obj['id'] +'">View Details</a></span>';
    str += '</div>';
    str += '<div class="btype fLeft">' + obj['type'] + '</div>';
    str += '<div class="metal fLeft">' + obj['metal'].split('~')[0] + '</div>';
    str += '<div class="purity fLeft">' + obj['gold_purity'] + '</div>';
    str += '<div class="weight fLeft">' + obj['gold_weight'] + '</div>';
    str += '<div class="price fLeft fmOpenB">&#8377;' + obj['price'] + '</div>';
    str += '<div class="acct fLeft">';
    str += '<center>';
    str += '<div class="deltBtn poR ripplelink"></div>';
    str += '<a href="http://localhost/iftosi/index.php?case=bullion_Form&catid=10002&prdid='+ obj['id'] +'"><div class="editBtn poR ripplelink"></div></a>';
    str += '<div class="soldBtn poR ripplelink fmOpenR">SOLD</div>';
    str += '</center>';
    str += '</div>';
    str += '</li>';
    str += '';
    return str;
}
