var uid = customStorage.readFromStorage('userid');
var busiType = customStorage.readFromStorage('busiType');
var username = customStorage.readFromStorage('username');
if(busiType==null || busiType=='' || busiType==undefined) {
    window.location.assign(DOMAIN+'index.php?case=vendor_Form&uid='+uid);
}
var active = '';
document.getElementById('userName').innerHTML = username.split(' ')[0];
if ( catid == '10000') {
    //$('#dmdTab').removeClass('dn');
   active = 'diamondFheader';
}
if ( catid == '10001') {
    //$('#jewTab').removeClass('dn');
    active = 'jewelleryFheader';
}
if ( catid == '10002') {
   // $('#bullTab').removeClass('dn');
    active = 'bullionFheader';
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



var mxSc = 170;//$('.prdResults').offset().top;

var lastSc = 0;
$(document).ready(function () {
	
    $('.vTabs').eq(1).click();

    
var busiTypeSplt = busiType.split(',');
for(var i = 0; i< busiTypeSplt.length; i++)
{
    if(busiTypeSplt[i] == 1)
    {
        $('#dmdTab').removeClass('dn');
    }
    if(busiTypeSplt[i] == 2)
    {
        $('#jewTab').removeClass('dn');
    }
    if(busiTypeSplt[i] == 3)
    {
        $('#bullTab').removeClass('dn');
    }
}




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

    $(window).scroll(function () {
        var sc = $(window).scrollTop();
        if (sc > mxSc) {
            $('#' + active).removeClass('pLHtransit dn');
        } else if (lastSc > sc) {
            $('#' + active).addClass('pLHtransit');
        }
        lastSc = sc;
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
                $('#silverpurity').val('');
                $('#silverweight').val('');
                if (tmpId == 'gbars') {
                    $('#goldweight').attr('placeholder','eg. Kgs Or Gms');
                } else {
                    $('#goldweight').attr('placeholder','eg. Gms');
                }
            }
            else if ((tmpId == 'sbars') || (tmpId == 'scoins'))
            {
                $('.allprop').addClass('dn');
                $('.silverprop').removeClass('dn');
                $('#goldpurity').val('');
                $('#goldweight').val('');
                if (tmpId == 'sbars') {
                    $('#silverweight').attr('placeholder','eg. Kgs Or Gms');
                } else {
                    $('#silverweight').attr('placeholder','eg. Gms');
                }
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

function onEnterFormSubmit(evt,type) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if(charCode==13) {
        if(type==1) {
            updateDollarRate();
        } else if(type==2) {
            updateSilverRate();
        } else if(type==3) {
            updateGoldRate();
        }
    }
}

$('#overlay').velocity({opacity:0},{delay:0,duration:0});
$('#uploadDiv,#dollarRateDiv,#goldRateDiv,#silverRateDiv').velocity({scale: 0}, {delay: 0, duration: 0});
$('#upProds').click(function () {
    $('#overlay,#uploadDiv').removeClass('dn');
    setTimeout(function () {
        $('#overlay').velocity({opacity: 1}, {delay: 0, duration: 300, ease: 'swing'});
        $('#uploadDiv').velocity({scale: 1}, {delay: 80, duration: 100, ease: 'swing'});
    }, 10);
    loadDiamont = false;
});
$('#upDolRt').click(function () {
    showDollarRateForm();
});

function showDollarRateForm() {
    $('#overlay,#dollarRateDiv').removeClass('dn');
    setTimeout(function () {
        $('#overlay').velocity({opacity: 1}, {delay: 0, duration: 300, ease: 'swing'});
        $('#dollarRateDiv').velocity({scale: 1}, {delay: 80, duration: 100, ease: 'swing'});
    }, 10);
    $.ajax({url: common.APIWebPath() + "index.php?action=getDollerRate&vid=" + uid, success: function (result) {
        var obj = jQuery.parseJSON(result);
        var errCode = obj['error']['Code'];
        if (errCode == 0) {
            $('#dollar_rate').val(obj['results']['dollar_rate']);
        }
    }});
}

$('#overlay,#upCancel').bind('click', function () {
    closeAllForms();
});

function closeAllForms() {
    $('#uploadDiv,#dollarRateDiv,#goldRateDiv,#silverRateDiv').velocity({scale: 0}, {delay: 0, ease: 'swing'});
    $('#overlay').velocity({opacity: 0}, {delay: 100, ease: 'swing'});
    setTimeout(function () {
        $('#overlay,#uploadDiv,#dollarRateDiv,#goldRateDiv,#silverRateDiv').addClass('dn');
    }, 1010);
    loadDiamont = true;
}

function updateDollarRate() {
    var dollar_rate = $("#dollar_rate").val();
    dollar_rate=parseFloat(dollar_rate);
    if(dollar_rate=='' || dollar_rate <= 0 || dollar_rate == undefined) {
        common.toast(0,'Invaild Rate');
    } else {
        $.ajax({url: DOMAIN + "/apis/index.php?action=updateDollerRate&vid="+uid+"&dolRate="+dollar_rate, success: function(result) {
                var obj = jQuery.parseJSON(result);
                var errCode = obj['error']['Code'];
                if(errCode==0) {
                    common.toast(1,obj['error']['Msg']);
                    closeAllForms();
                    setTimeout(function () { window.location.assign(DOMAIN+"index.php?case=diamond_Form&catid=10000&vid="+uid); }, 1800);
                } else if(errCode==1) {
                    common.toast(0,obj['error']['Msg']);
                }
            }
        });
    }
}

/* For bullion silver rate of vendor */
$('#upSilRt').click(function () {
    showSilverRateForm();
});

function showSilverRateForm() {
    $('#overlay,#silverRateDiv').removeClass('dn');
    setTimeout(function () {
        $('#overlay').velocity({opacity: 1}, {delay: 0, duration: 300, ease: 'swing'});
        $('#silverRateDiv').velocity({scale: 1}, {delay: 80, duration: 100, ease: 'swing'});
    }, 10);
    $.ajax({url: common.APIWebPath() + "index.php?action=getSilverRate&vid="+ uid, success: function (result) {
        var obj = jQuery.parseJSON(result);
        var errCode = obj['error']['Code'];
        if(errCode==0) {
            $('#silver_rate').val(obj['results']['silver_rate']);
        }
    }});
}

function updateSilverRate() {
    var silver_rate = $("#silver_rate").val();
    silver_rate=parseFloat(silver_rate);
    if(silver_rate=='' || silver_rate <= 0 || silver_rate == undefined) {
        common.toast(0,'Invaild Rate');
    } else {
        $.ajax({url: DOMAIN + "/apis/index.php?action=updateSilverRate&vid="+uid+"&silRate="+silver_rate, success: function(result) {
                var obj = jQuery.parseJSON(result);
                var errCode = obj['error']['code'];
                if(errCode==0) {
                    common.toast(1,obj['error']['Msg']);
                    closeAllForms();
                } else if(errCode==1) {
                    common.toast(0,obj['error']['Msg']);
                }
            }
        });
    }
}


/* for bullion gold  rate of vendor */
$('#upGoldRt').click(function () {
    showGoldRateForm();
});

function showGoldRateForm() {
    $('#overlay,#goldRateDiv').removeClass('dn');
    setTimeout(function () {
        $('#overlay').velocity({opacity: 1}, {delay: 0, duration: 300, ease: 'swing'});
        $('#goldRateDiv').velocity({scale: 1}, {delay: 80, duration: 100, ease: 'swing'});
    }, 10);
    $.ajax({url: common.APIWebPath() + "index.php?action=getGoldRate&vid="+ uid, success: function (result) {
        var obj = jQuery.parseJSON(result);
        var errCode = obj['error']['Code'];
        if(errCode==0) {
            $('#gold_rate').val(obj['results']['gold_rate']);
            $('#silver_rate').val(obj['results']['silver_rate']);
        }
    }});
}

function updateGoldRate() {
    var gold_rate = $("#gold_rate").val();
    var silver_rate = $("#silver_rate").val();
    console.log(silver_rate);
    gold_rate=parseFloat(gold_rate);
    silver_rate=parseFloat(silver_rate);
   if(gold_rate=='' || gold_rate <= 0 || gold_rate == undefined || isNaN(gold_rate) == true){
        common.toast(0,'Gold Rate is Must to fill');
        return false;
    }
    else if(silver_rate=='' || silver_rate <= 0 || silver_rate == undefined || isNaN(silver_rate) == true) {
        common.toast(0,'Silver Rate is Must to fill');
        return false;
    }    
    else {
    $.ajax({url: DOMAIN + "/apis/index.php?action=updateGoldRate&vid="+uid+"&goldRate="+gold_rate+"&silverRate="+silver_rate, success: function(result) {
                var obj = jQuery.parseJSON(result);
                var errCode = obj['error']['code'];
                if(errCode==0) {
                    common.toast(1,obj['error']['Msg']);
                    //window.location.reload(1);
                    customStorage.readFromStorage('rateErr');
                    closeAllForms();
                    setTimeout(function () { window.location.assign(DOMAIN+"index.php?case=bullion_Form&catid=10002&vid="+uid); }, 1800);
                } else if(errCode==1) {
                    
                    common.toast(0,obj['error']['Msg']);
                }
            }
        });
    }
}



function isValidFloatKey(obj, e, allowDecimal)
{
    var key;
    var isCtrl = false;
    var keychar;
    var reg;

    if (window.event) {
        key = e.keyCode;
        isCtrl = window.event.ctrlKey
    }
    else if (e.which) {
        key = e.which;
        isCtrl = e.ctrlKey;
    }

    if (isNaN(key))
        return true;

    keychar = String.fromCharCode(key);

    // check for backspace or delete, or if Ctrl was pressed
    if (key == 8 || isCtrl)
    {
        return true;
    }
    if(key == 13) {
       // updateDollarRate();
    }
    reg = /\d/;
    var isFirstD = allowDecimal ? keychar == '.' && obj.value.indexOf('.') == -1 : false;

    return isFirstD || reg.test(keychar);
}


$("#upSubmit").on('click',(function(e) {
    if($("#up_file").val()=='' || ValidateFile()==false) {
        common.toast(0,'Please Select Valid CSV File');
    } else {
        $.ajax({url: DOMAIN + "/apis/index.php?action=bulkInsertProducts&vid="+uid,
            type: "POST",             
            data: new FormData($('form')[0]), 
            contentType: false,
            cache: false,
            processData:false,
            success: function(result) {
                var obj = jQuery.parseJSON(result);
                $("#up_file").val('');
                var errCode = obj['error']['Code'];
                if(errCode==0) {
                    $("#DiamondsList").html('');
                    diamondPage = 1;
                    loadDiamonds();
                    loadDiamond = false;
                    errCode=1;
                    closeAllForms();
                } else if(errCode==1) {
                    errCode=0;
                }
                common.toast(errCode,obj['error']['Msg']);
//                if(obj['error']['Code']==0) {
//                    common.toast(1,'Products are updated Successfully');
//                }
//                else {
//                    common.toast(0,'Products are Failed to Update');
//                }
            }
        });
    }
}));


function ValidateFile() {
    var allowedFiles = ["csv","xls","xlsx"];
    var fileUpload = document.getElementById("up_file").value;
    var fileExt = fileUpload.split('.').pop();
    if (allowedFiles.indexOf(fileExt)!=-1) {
        return true;
    }
    return false;
}

function getcatid() {
    var btype = customStorage.readFromStorage('busiType');
    
    if (btype !=='' || btype !== undefined || btype !== null ) {
        var catid = parseInt(btype.charAt(0))-1;
    }
    return 1000+catid.toString();
}
function addDiamond() {
    $.ajax({url: common.APIWebPath() + "index.php?action=getDollerRate&vid="+ uid, success: function (result) {
        var obj = jQuery.parseJSON(result);
            var dollarRate=obj['results']['dollar_rate'];
            if(dollarRate!=0.00) {
                window.location.assign(DOMAIN+"index.php?case=diamond_Form&catid=10000&vid="+uid);
            } else {
                showDollarRateForm();
                $('#dollarErr').removeClass('dn');
            }
    }}); 
}
function addBulion() {

    $.ajax({url: DOMAIN + "apis/index.php?action=getAllRatesByVID&vid="+uid, success: function(result) {
            var obj = jQuery.parseJSON(result);
            var goldRate = obj['results']['gold_rate'];
            var silverRate = obj['results']['silver_rate'];
            var dollarRate = obj['results']['dollar_rate'];
            if(goldRate==0.00) {
                showGoldRateForm();
                $('#goldErr').removeClass('dn');
            }
            else if(silverRate==0.00) {
                showSilverRateForm();
                $('#silverErr').removeClass('dn');
            } else {
                window.location.assign(DOMAIN+"index.php?case=bullion_Form&catid="+catid+"&vid="+uid);
            }
        
    }}); 
}
