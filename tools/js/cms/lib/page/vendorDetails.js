var uid = common.readFromStorage('uid');

$(document).ready(function () {
    $('.compComm').bind('click', function (e) {

        var x = (document.all) ? event.x : e.pageX;
        var y = (document.all) ? event.y : e.pageY;

        setTimeout(function () {
            x = e.pageX = e.pageX + 100;
            y = e.pageY = e.pageY + 100;
        }, 1000);

//                $('#forDiamond,#forJewellery,#forBullion').removeClass('comSelected');
//                $('#bullionDet,#jewelleryDet,#diamondDet').addClass('dn');
        $(this).toggleClass('comSelected');
        var id = $(this).attr('id');
        if (id == 'forDiamond') {
            $('#diamondDet').toggleClass('dn');
        }
        else if (id == 'forJewellery') {
            $('#jewelleryDet').toggleClass('dn');
        }
        else if (id == 'forBullion') {
            $('#bullionDet').toggleClass('dn');
        }
    });
});

function validateForm() {
    var orgname = $('#orgname').val();
    var fulladd = $('#fulladd').val();
    var pincode = $('#pincode').val();
    var area = $('#area').val();
    var city = $('#city').val();
    var state = $('#state').val();
    var wbst = $('#wbst').val();
    var str = '';
    if (orgname == '') {
        str = 'Organization Name is Required\n';
        $('#orgname').focus();
    }
    else if (fulladd == '') {
        str = 'Address is Required';
        $('#fulladd').focus();
    }
    else if (pincode == '' || pincode.length < 6 || isNaN(pincode)) {
        str = 'Pincode is Required';
        $('#pincode').focus();
    }
    else if (area == '') {
        str = 'Area is Required';
        $('#area').focus();
    }
    else if (city == '') {
        str = 'City is Required';
        $('#city').focus();
    }
    else if (state == '') {
        str = 'State is Required';
        $('#state').focus();
    }
    else if (!$("#forDiamond").hasClass("comSelected") && !$("#forJewellery").hasClass("comSelected") && !$("#forBullion").hasClass("comSelected")) {
        str = 'Select business type';
    }
    else {
        if (wbst != '') {
            if (!common.validateUrl('wbst')) {
                return false;
            } else { return  true;}
        }
        return  true;
    }
    common.toast(0,str);
    return false;
}

function validateStep2Form() {
    var str = 'This Field is Required';
    if (/1/.test(busiType)) {
        var memcert = $('#memcert').val();
        var bdbc = $('#bdbc').val();
        var othdbaw = $('#othdbaw').val();
        var ofcity = $('#ofcity').val();
        var ofcountry = $('#ofcountry').val();
        if (memcert == '') {
            $('#memcert').focus();
            common.toast(0,'Enter GJEPC Membership Certificate');
            return false;
        }
        else if (bdbc == '') {
            common.toast(0,'Enter Bharat Diamond Bourse Certificate');
            $('#bdbc').focus();
            return false;
        }
        else if (othdbaw == '') {
            common.toast(0,'Enter Details Of Membership Of Other Diamond Bourse Around The World');
            $('#othdbaw').focus();
            return false;
        }
        else if (ofcity == '') {
            common.toast(0,'Enter Offices In other Cities');
            $('#ofcity').focus();
            return false;
        }
        else if (ofcountry == '') {
            common.toast(0,'Enter Offices In other Countries');
            $('#ofcountry').focus();
            return false;
        }
    }  
    if (/2/.test(busiType)) {
        var showroomname = $('#showroomname').val();
        var showroomno = $('#showroomno').val();
        var mdbw = $('#mdbw').val();
        if (showroomname == '') {
            $('#showroomname').focus();
            common.toast(0,'Show Room Name is Required');
            return false;
        }
        else if (showroomno == '') {
            common.toast(0,'Enter Number of Showrooms');
            $('#showroomno').focus();
            return false;
        }
        else if (mdbw == '') {
            common.toast(0,'Membership Of Council / Jewellers Association is Required');
            $('#mdbw').focus();
            return false;
        }
    } 
    if (/3/.test(busiType)) {
        var bul_mdbw = $('#bul_mdbw').val();
        if (bul_mdbw == '') {
            common.toast(0,'Membership Of Council / Jewellers Association is Required');
            $('#bul_mdbw').focus();
            return false;
        } 
    } 
    if (!(/1/.test(busiType)) && !(/2/.test(busiType)) && !(/3/.test(busiType))) {
        common.toast(0,'Select business type');
        return false;
    }
    return true;
}

function validateStep3Form() {
    var cperson = $('#cperson').val();
    var position = $('#position').val();
    var conMobile = $('#conMobile').val();
    var email = $('#email').val();
    var landline = $('#landline').val();
    var str = '';
    if (cperson == '') {
        common.toast(0,'Contact Person Name is Required');
        $('#cperson').focus();
        return false;
    }
    else if (position == '') {
        common.toast(0,'Position is Required');
        $('#position').focus();
        return false;
    }
    if (!common.checkMobile('conMobile')) {
        $('#conMobile').focus();
        return false;
    }
    if (!common.validateEmail('email')) {
        common.toast(0,'Contact Email is Required');
        $('#email').focus();
        return false;
    }
    if (landline != '') {
        if (!common.checkLandline('lnCode', 'landline')) {
            return false;
        }
    }
    return  true;
}

function clickThis(id) {
    var obj = document.getElementById(id);
    if (id == 'step2' && validateForm()) {
        submitForm(obj)
    }
    else if (id == 'step3' && validateStep2Form()) {
        submitStep2Form(obj)
    }
    else if (id == 'step4' && validateStep3Form()) {
        submitStep3Form(obj)
    }
}
function submitForm() {
    var val = new Array("orgname", "fulladd", "add1", "pincode", "area", "city", "state", "vat", "pan", "tovr", "wbst", "banker");
    var data = new Object;
    var res = formatData(val);
    var busiTypeVal = new Array;
    if ($("#forDiamond").hasClass("comSelected")) {
        busiTypeVal.push(1);
    }
    if ($("#forJewellery").hasClass("comSelected")) {
        busiTypeVal.push('2');
    }
    if ($("#forBullion").hasClass("comSelected")) {
        busiTypeVal.push('3');
    }
    busiType = busiTypeVal.join(',');
    res['busiType'] = busiType;
    res['uid'] = uid;
    data['result'] = res;
    data = JSON.stringify(data);
    $.ajax({url: common.APIWebPath() + "index.php?action=udtProfile&dt=" + encodeURIComponent(data), success: function (result) {
            var obj = jQuery.parseJSON(result);
            var errCode = obj['error']['code'];
            var errMsg = obj['error']['msg'];
            if (errCode == 0) {
                common.toast(1,errMsg);
                changeTab('step2');
            } else {
                common.toast(0,errMsg);
            }
        }});
}

function submitStep2Form() {
    var res = new Object;
    var data = new Object;
    var mdbw = '';
    if (/1/.test(busiType)) {
        var val = new Array('memcert', 'bdbc', 'othdbaw', 'ofcity', 'ofcountry');
        res = formatData(val);
    }
    if (/2/.test(busiType)) {
        res['showroomname'] = $('#showroomname').val();
        res['showroomno'] = $('#showroomno').val();
        mdbw = $('#mdbw').val();
    }
    if (/3/.test(busiType)) {
        mdbw += '~' + $('#bul_mdbw').val();
    }
    if (mdbw != '') {
        mdbw = mdbw.replace(/~*$/, "");
        res['mdbw'] = mdbw;
    }
    res['uid'] = uid;
    data['result'] = res;

    data = JSON.stringify(data);
    $.ajax({url: common.APIWebPath() + "index.php?action=udtProfile&dt=" + encodeURIComponent(data), success: function (result) {
            var obj = jQuery.parseJSON(result);
            var errCode = obj['error']['code'];
            var errMsg = obj['error']['msg'];
            if (errCode == 0) {
                common.toast(1,errMsg);
                changeTab('step3');
            } else {
                common.toast(0,errMsg);
            }
        }});

}

function submitStep3Form() {
    var val = new Array('cperson', 'position', 'email');
    var data = new Object;
    var res = formatData(val);
    var conMobile = $('#conMobile').val();
    if (altmbNo1_Mobile != '') {
        res['cmobile'] = conMobile;
    }
    var altmbNo1_Mobile = $('#altmbNo1_Mobile').val();
    if (altmbNo1_Mobile != '') {
        res['alt_cmobile'] = altmbNo1_Mobile;
    }
    var altmbNo2_Mobile = $('#altmbNo2_Mobile').val();
    if (altmbNo2_Mobile != '') {
        res['alt_cmobile'] += '|~|' + altmbNo2_Mobile;
    }
    /*
     var landline = $('#landline').val();
     var lnCode = $('#lnCode').val();
     if(landline!='' && lnCode!='') {
     res['landline'] = lnCode + '-' + landline;
     }
     var landline1 = $('#landline1').val();
     var lnCode1 = $('#lnCode1').val();
     if(landline1!='' && lnCode1!='') {
     res['landline'] += '~'+lnCode1 + '-' + landline1;
     }
     var landline2 = $('#landline2').val();
     var lnCode2 = $('#lnCode2').val();
     if(landline2!='' && lnCode2!='') {
     res['landline'] += '~'+lnCode2 + '-' + landline2;
     }
     var landline3 = $('#landline3').val();
     var lnCode3 = $('#lnCode3').val();
     if(landline3!='' && lnCode3!='') {
     res['landline'] += '~'+lnCode3 + '-' + landline3;
     }
     */
    res['landline'] = common.getLandlineNo();
    ;
    res['uid'] = uid;
    data['result'] = res;
    data = JSON.stringify(data);

    $.ajax({url: common.APIWebPath() + "index.php?action=udtProfile&dt=" + encodeURIComponent(data), success: function (result) {
            var obj = jQuery.parseJSON(result);
            var errCode = obj['error']['code'];
            var errMsg = obj['error']['msg'];
            if (errCode == 0) {
                common.toast(1,errMsg);
                window.location.assign('index.php?case=vendor_landing');
            } else {
                common.toast(0,errMsg);
            }
        }});
}

function formatData(val) {
    var data = new Object;
    for (var i = 0; i < val.length; i++) {
        data[val[i]] = $('#' + val[i]).val();
    }
    return data;
}
function changeTab(id) {
    var obj = document.getElementById(id);
    common.changeTab(obj);
}

/*    
 function ValidateNumber(val,minLen,maxLen) {
 var length = val.length;
 if(val=='') {
 return 'is required';
 } else if (length<minLen || length>maxLen) {
 return ' length should be between ' + minLen + ' to' + maxLen;
 } else if (isNaN(val)) {
 return ' must be a Number';
 }
 }
 */
