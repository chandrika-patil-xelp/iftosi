var uid = customStorage.readFromStorage('userid');
var busiType = customStorage.readFromStorage('busiType');
var username = customStorage.readFromStorage('username');
document.getElementById('userName').innerHTML = username;
if (/1/.test(busiType)) {
    $('#dmdTab').removeClass('dn');
}
if (/2/.test(busiType)) {
    $('#jewTab').removeClass('dn');
}
if (/3/.test(busiType)) {
    $('#bullTab').removeClass('dn');
}

var activeVTab='';
switch(activePage) {
    case 'vendor_landing':
        activeVTab ='prdTab';
    break;
    case 'vendor_enquiries':
        activeVTab ='enqTab';
    break;
    default :
        activeVTab ='';
    break;
}
$('#'+activeVTab).addClass('vSelected');

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
        if ($(this).hasClass('shapeSelected'))
        {
            $('#allcontent').removeClass('dn');
        }
        else
        {
            $('#allcontent').addClass('dn');
        }
        
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
            $('#allcontent').removeClass('dn');
        }
        else
        {
            var tmpId = $(this).attr('id');
            tmpId = tmpId.toLowerCase();
            $('#' + tmpId + 'Type').addClass('dn');
            $('#allcontent').addClass('dn');
        }

        var bthis = $(this);



        if ($(this).hasClass('shapeSelected'))
        {
            var id = $(this).attr('id');
            var tmpId = id.toLowerCase();

            if ((tmpId == 'gbars') || (tmpId == 'gcoins'))
            {
                $('.allprop').addClass('dn');
                $('.goldprop').removeClass('dn');
            }
            else if ((tmpId == 'sbars') || (tmpId == 'scoins'))
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
$('.dropInput').on('mouseover',function () {
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


//$('.vTabs').click(function () {
//    var id = $(this).attr('id');
//    $('.vTabs').removeClass('vSelected');
//    $(this).addClass('vSelected');
//
//    switch (id) {
//        case 'dashTab':
//            active = '';
//            $('#product,#enquiry,#settings').addClass('dn');
//            $('#dashboard').removeClass('dn');
//            break;
//        case 'prdTab':
//            $('#dashboard,#enquiry,#settings').addClass('dn');
//            $('#product').removeClass('dn');
//            break;
//        case 'enqTab':
//            active = 'enqFheader';
//            $('#dashboard,#product,#settings').addClass('dn');
//            $('#enquiry').removeClass('dn');
//            break;
//        case 'setTab':
//            active = '';
//            $('#dashboard,#product,#enquiry').addClass('dn');
//            $('#settings').removeClass('dn');
//            break;
//    }
//});

function toggleDropDown(flag, id) {
    if (flag) {
        $("#" + id + "List").velocity({opacity: 1, borderRadius: 0}, {duration: 200, display: "block"});
    }
    else {
        $("#" + id + "List").velocity({opacity: 0, borderRadius: '0%'}, {duration: 10, display: "none"});
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
     alert('submit ' +values);*/
}