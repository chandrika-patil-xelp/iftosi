var uid = customStorage.readFromStorage('userid');
if (vid != uid || uid == '') {
    window.location.assign(DOMAIN + 'index.php');
}
$(document).ready(function () {
    if ($('#cperson').val() == '') {
        $('#cperson').val(customStorage.readFromStorage('username'));
    }
    if ($('#conMobile').val() == '') {
        $('#conMobile').val(customStorage.readFromStorage('mobile'));
    }
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
function fnValidatePAN() {
    var Obj = $conv('#pan').val();
    var str = '';

}
function validateForm() {
    var orgname = $('#orgname').val();
    var fulladd = $('#fulladd').val();
    var pincode = $('#pincode').val();
    var area = $('#area').val();
    var city = $('#city').val();
    var state = $('#state').val();
    var wbst = $('#wbst').val();
    var pancard = $('#pan').val();
    //var vatno = $('#vat').val();

    var str = "V02554544";
    var patt1 = /^(C|V){1}([0-3]){2}([0-9]){2}/g;
    var result = str.match(patt1);
    var panPat = /^([A-Z]){5}([0-9]){4}([A-Z]){1}?$/;
    var code = /([C,P,H,F,A,T,B,L,J,G,K])/;
    var code_chk = pancard.substring(3, 4);

    var str = '';
    if (orgname == '')
    {
        str = 'Organization Name is Required\n';
        $('#orgname').focus();
    }

    if (str == '' && fulladd == '')
    {
        str = 'Address is Required';
        $('#fulladd').focus();
    }

    if (str == '' && (pincode == '' || pincode.length < 6 || isNaN(pincode)))
    {
        str = 'Pincode is Required';
        $('#pincode').focus();
    }

    if (str == '' && area == '')
    {
        str = 'Area is Required';
        $('#area').focus();
    }

    if (str == '' && city == '')
    {
        str = 'City is Required';
        $('#city').focus();
    }

    if (str == '' && state == '')
    {
        str = 'State is Required';
        $('#state').focus();
    }

    if (str == '' && pancard.match(panPat) == null)
    {
        str = 'Pancard is Invalid';
        $('#pan').focus();
    }

    if (str == '' && pancard === ' ')
    {
        str = 'Pancard is Invalid';
        $('#pan').focus();
    }

    if (str == '' && pancard.search(panPat) == -1) {
        str = 'Invalid Pan No';
        $('#pan').focus();
    }

    if (str == '' && code.test(code_chk) == false) {
        str = 'Invaild PAN Card No.';
        $('#pan').focus();
    }
//    if (vatno != '') {
//        if (vatno.length == 11) {
//            var vat_pattr = /^(C|V){1}([0-3]){1}([0-9]){2}/g;
//            var vat_pattr1 = /([0-9]*$)/;
//            var vatLtNo = vatno.substring(3, 11);
//
//            if (vatLtNo.search(vat_pattr1) != 0 || vatno.search(vat_pattr) != 0) {
//                str = 'Invaild VAT No.';
//                $('#vat').focus();
//            }
//        } else {
//            str = 'Invaild VAT Nunber';
//            $('#vat').focus();
//        }
//    } 
//    else  {
//        str = 'VAT Nunber Required';
//        $('#vat').focus();
//    }
    if (str == '' && !$("#forDiamond").hasClass("comSelected") && !$("#forJewellery").hasClass("comSelected") && !$("#forBullion").hasClass("comSelected")) {
        str = 'Select business type';
    }
    else if (str == '')
    {
        if (wbst !== '')
        {
            if (!common.validateUrl('wbst'))
            {
                return false;
            }
            else
            {
                return  true;
            }
        }

        return  true;
    }

    if (str !== '')
    {
        common.toast(0, str);
        return false;
    }
    else
    {
        return true;
    }
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
            common.toast(0, 'Enter GJEPC Membership Certificate');
            return false;
        }
        else if (bdbc == '') {
            common.toast(0, 'Enter Bharat Diamond Bourse Certificate');
            $('#bdbc').focus();
            return false;
        }
        else if (othdbaw == '') {
            common.toast(0, 'Enter Details Of Membership Of Other Diamond Bourse Around The World');
            $('#othdbaw').focus();
            return false;
        }
        else if (ofcity == '') {
            common.toast(0, 'Enter Offices In other Cities');
            $('#ofcity').focus();
            return false;
        }
        else if (ofcountry == '') {
            common.toast(0, 'Enter Offices In other Countries');
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
            common.toast(0, 'Show Room Name is Required');
            return false;
        }
        else if (showroomno == '' || isNaN(showroomno) || showroomno == 0) {
            common.toast(0, 'Enter Number of Showrooms');
            $('#showroomno').focus();
            return false;
        }
        else if (mdbw == '') {
            common.toast(0, 'Membership Of Council / Jewellers Association is Required');
            $('#mdbw').focus();
            return false;
        }
    }
    if (/3/.test(busiType)) {
        var bul_mdbw = $('#bul_mdbw').val();
        if (bul_mdbw == '') {
            common.toast(0, 'Membership Of Council / Jewellers Association is Required');
            $('#bul_mdbw').focus();
            return false;
        }
    }
    if (!(/1/.test(busiType)) && !(/2/.test(busiType)) && !(/3/.test(busiType))) {
        common.toast(0, 'Select business type');
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
        common.toast(0, 'Contact Person Name is Required');
        $('#cperson').focus();
        return false;
    }
    else if (position == '') {
        common.toast(0, 'Position is Required');
        $('#position').focus();
        return false;
    }
    if (!common.checkMobile('conMobile')) {
        $('#conMobile').focus();
        return false;
    }
    if (!common.validateEmail('email')) {
        common.toast(0, 'Contact Email is Required');
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
		$('html,body').animate({ scrollTop: 0 }, 'slow');
    }
    else if (id == 'step3' && validateStep2Form()) {
        submitStep2Form(obj)
		$('html,body').animate({ scrollTop: 0 }, 'slow');
    }
    else if (id == 'step4' && validateStep3Form()) {
        submitStep3Form(obj)
    }
}
function submitForm() {
    customStorage.removeFromStorage('isComp');
    var val = new Array("orgname", "fulladd", "add1", "pincode", "area", "city", "state", "vat", "pan", "tovr", "wbst", "banker");
    var data = new Object;
    var res = formatData(val);
    var busiTypeVal = new Array;
    if ($("#forDiamond").hasClass("comSelected")) {
        busiTypeVal.push(1);
    }
    if ($("#forJewellery").hasClass("comSelected")) {
        busiTypeVal.push(2);
    }
    if ($("#forBullion").hasClass("comSelected")) {
        busiTypeVal.push(3);
    }
    busiType = busiTypeVal.join(',');
    res['busiType'] = busiType;
    res['country'] = country;
    res['lng'] = lng;
    res['lat'] = lat;
    res['uid'] = uid;
    data['result'] = res;
    data = JSON.stringify(data);
    $.ajax({url: common.APIWebPath() + "index.php?action=udtProfile&dt=" + encodeURIComponent(data), success: function (result) {
            var obj = jQuery.parseJSON(result);
            customStorage.addToStorage('busiType', busiType);
            var errCode = obj['error']['code'];
            var errMsg = obj['error']['msg'];
            if (errCode == 0) {
                $('#showroomname').val(res['orgname']);
                common.toast(1, errMsg);
                changeTab('step2');
            } else {
                common.toast(0, errMsg);
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
    customStorage.addToStorage('isComp', 1);
    $.ajax({url: common.APIWebPath() + "index.php?action=udtProfile&isC=1&dt=" + encodeURIComponent(data), success: function (result) {
            var obj = jQuery.parseJSON(result);
            var errCode = obj['error']['code'];
            var errMsg = obj['error']['msg'];
            if (errCode == 0) {
                common.toast(1, errMsg);
                changeTab('step3');
            } else {
                common.toast(0, errMsg);
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
    $.ajax({url: common.APIWebPath() + "index.php?action=udtProfile&isC=2&dt=" + encodeURIComponent(data), success: function (result) {
            var obj = jQuery.parseJSON(result);
            var errCode = obj['error']['code'];
            var errMsg = obj['error']['msg'];
            if (errCode == 0) {
                common.toast(1, errMsg);
                var isComp = 2;
                customStorage.addToStorage('isComp', isComp);
                var bsType = parseInt(busiType.charAt(0));
                bsType = bsType - 1;
                window.location.href = 'index.php?case=vendor_landing&catid=1000' + bsType;
            } else {
                common.toast(0, errMsg);
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

var country = '';
var lng = '';
var lat = '';
function loadAreaList() {
    var pincode = $('#pincode').val();
    if (pincode.length == 6) {
        $.ajax({url: common.APIWebPath() + "index.php?action=viewbyPincode&code=" + pincode, success: function (result) {
                var obj = jQuery.parseJSON(result);
                //var results = new Object;
                var results = obj['results'];
                if (results != '') {
                    areaList = results;
                    $('#area').val(results[0]['area']);
                    $('#city').val(results[0]['city']);
                    $('#state').val(results[0]['state']);
                    country = results[0]['country'];
                    lat = results[0]['latitude'];
                    lng = results[0]['longitude'];
                }
            }});
    }
}
loadAreaList();
$('#pincode').keyup(function () {
    loadAreaList();
});

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

var searchArea = new Search();
searchArea.setMinimumSearchChar(1);
searchArea.setId("areaSuggestDiv", "area", searchAreaCallBack, searchAreaDivOutput);
searchArea.setAppId("searchArea");
searchArea.arrayResult = true;
var areaList = '';
var tmpAreaList = new Array();

function searchAreaCallBack(res) {
    searchArea.setData = true;
    $("#area").val(res["area"]);
    $('#city').val(res['city']);
    $('#state').val(res['state']);
    country = res['country'];
    lat = res['latitude'];
    lng = res['longitude'];
    document.getElementById("areaSuggestDiv").innerHTML = "";
}

var lastHightlighted = "";
var listName;
var autoSuggDiv;
var obiLen;
function searchAreaDivOutput(res) {
    setTimeout(function () {
        listName = 'areaList';
        autoSuggDiv = 'areaSuggestDiv';
        autoSuggParent = 'area';
        searchArea.suggestionList = areaList;
        var obj = areaList;
        var len = obj.length;
        obiLen = len;
        var i = 0;
        if (len > 0)
        {
            searchArea.suggestLen = len;
            var i = 0;
            var suggest = "<div class='smallField fmRoboto font14 pointer border1 transition300' style='overflow-y:auto'>";
            if (res == "") {
                while (i < len) {
                    suggest += "<div id='suggest" + i + "' class='autoSuggestRow transition300 txtCaCase' onmousedown='searchArea.setSearchResult(\"" + i + "\")' onclick='searchArea.setSearchResult(\"" + i + "\")'>&nbsp;&nbsp;" + obj[i].area + "</div>";
                    i++;
                }
            } else {
                var s = res.toUpperCase();
                var slen = s.length;
                var count = 0;
                var tmpSearch = new Array();
                while (i < len) {
                    var t = (obj[i].area).split(" ");
                    var len1 = t.length;
                    var j = 0;
                    var loadFlag = false;
                    while (j < len1) {
                        var st = (t[j].substring(0, slen)).toUpperCase();
                        if (s == st) {
                            loadFlag = true;
                            break;
                        }
                        j++;
                    }
                    if (loadFlag) {
                        tmpSearch.push(obj[i]);
                        suggest += "<div id='suggest" + count + "' class='autoSuggestRow transition300 txtCaCase' onmousedown='searchArea.setSearchResult(\"" + count + "\")' onclick='searchArea.setSearchResult(\"" + count + "\")'>&nbsp;&nbsp;" + obj[i].area + "</div>";
                        count++;
                    }
                    i++;
                }
                searchArea.suggestionList = tmpSearch;
                searchArea.suggestLen = (count - 1);
            }
            suggest += "</div>";
            searchArea.suggestLen = searchArea.suggestLen - 1;
            document.getElementById("areaSuggestDiv").innerHTML = suggest;
        }
    }, 150);

}

function matchValue(val, list, type) {
    var len = list.length;
    for (var i = 0; i < len; i++) {
        var text = (list[i].area).toLowerCase();
        var ind = text.indexOf(val);
        if ((ind != -1)) {
            $('#area').val(list[i].area);
            break;
        }
    }
}

function onlyAlphabets(evt, t) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode < 47) {
        return true
    } else
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return true;
    }
    return false;
}
function clearVal(id) {
    $('#' + id).val('');
}
function closeSuggest(id) {
    setTimeout(function () {
        $('#' + id).html('');
    }, 500);
}

var searchCity = new Search();
searchCity.setMinimumSearchChar(1);
searchCity.setId("citySuggestDiv", "prName", searchCityCallBack, searchCityDivOutput);
searchCity.setAppId("searchCity");
searchCity.setPath(APIDOMAIN + "apis/index.php?action=citySuggest&name=ban");

function searchCityCallBack(res) {
    searchCity.setData = true;
    document.getElementById("citySuggestDiv").innerHTML = "";
}


function searchCityDivOutput(res) {
    if (res != null) {
        searchCity.suggestionList = res;
        var obj = res;
        var len = obj.length;
        var i = 0;
        if (len > 0)
        {
            searchCity.suggestLen = len;
            var i = 0;
            var suggest = "<div class='smallField w100 fmRoboto transition300 font14 pointer border1' >";
            while (i < len) {
                suggest += "<div id='suggest" + i + "' class='autoSuggestRow w100 transition300 txtCaCase' onclick='setSuggestValue(\"" + obj[i].n + "\",\"city\");setSuggestValue(\"" + obj[i].s + "\",\"state\");'>&nbsp;&nbsp;" + obj[i].n + "</div>";
                i++;
            }
            suggest += "</div>";
            searchCity.suggestLen = searchCity.suggestLen - 1;
            document.getElementById("citySuggestDiv").innerHTML = suggest;
        }

    } else {
        document.getElementById("citySuggestDiv").innerHTML = "";
    }
}

var searchState = new Search();
searchState.setMinimumSearchChar(1);
searchState.setId("stateSuggestDiv", "prName", searchStateCallBack, searchStateDivOutput);
searchState.setAppId("searchState");

function searchStateCallBack(res) {
    searchCity.setData = true;
    document.getElementById("stateSuggestDiv").innerHTML = "";
}

function searchStateDivOutput(res) {
    if (res != null) {
        searchState.suggestionList = res;
        var obj = res;
        var len = obj.length;
        var i = 0;
        if (len > 0)
        {
            searchState.suggestLen = len;
            var i = 0;
            var suggest = "<div class='smallField w100 fmRoboto transition300 font14 pointer border1' >";
            while (i < len) {
                suggest += "<div id='suggest" + i + "' class='autoSuggestRow w100 transition300 txtCaCase' onclick='setSuggestValue(\"" + obj[i].n + "\",\"state\")'>&nbsp;&nbsp;" + obj[i].n + "</div>";
                i++;
            }
            suggest += "</div>";
            searchState.suggestLen = searchState.suggestLen - 1;
            document.getElementById("stateSuggestDiv").innerHTML = suggest;
        }

    } else {
        document.getElementById("stateSuggestDiv").innerHTML = "";
    }
}
function setSuggestValue(val, id) {
    $('#' + id).val(val);
    closeSuggest(id + 'SuggestDiv');
}

$('#pincode').keyup(function () {
    loadAreaList();
    clearVal('area')
});

$('#city').keyup(function () {
    var city = $(this).val();
    if (city != '') {
        $.ajax({url: common.APIWebPath() + "index.php?action=citySuggest&name=" + city, success: function (result) {
                var obj = jQuery.parseJSON(result);
                //var results = new Object;
                var results = obj['results'];
                if (results != '') {
                    searchCityDivOutput(results);
                }
            }});
    } else {
        closeSuggest('citySuggestDiv')
    }
});

$('#state').keyup(function () {
    var state = $(this).val();
    if (state != '') {
        $.ajax({url: common.APIWebPath() + "index.php?action=stateSuggest&name=" + state, success: function (result) {
                var obj = jQuery.parseJSON(result);
                //var results = new Object;
                var results = obj['results'];
                if (results != '') {
                    searchStateDivOutput(results);
                }
            }});
    } else {
        closeSuggest('stateSuggestDiv')
    }
});
