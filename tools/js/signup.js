
var input_selector = 'input[type=text],  input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea, input[type=radio]';
var pw = $(window).width();
var ph = $(window).height();
var isMobile = false;
if (pw < 768) {
    isMobile = true;
}
var submiter = true;
var validMob = true;

    if (isMobile) {
        $(input_selector).focus(function () {
            var npos = $('html,body').scrollTop() + 40;
            $('html,body').animate({scrollTop: npos}, 100);
        });
    }

    $(input_selector).bind('focus',function() {
        $(this).siblings('label, i').addClass('labelActive');
        $(this).addClass('brOrange');//.removeClass('brRed');
        $('.bsText').addClass('op0');
    });

    $(input_selector).bind('blur',function() {
        if ($(this).val().length === 0 && $(this).attr('placeholder') === undefined)
        {
            $(this).siblings('label, i').removeClass('labelActive');
            $(this).removeClass('brOrange brGreen');//.addClass('brRed');
        } else 
        {
            $(this).removeClass(' ').addClass('brGreen');
        }
    });

     $(input_selector).bind('keyup',function() {
        var val = $(this).val();
        var len = val.length;
        var id = $(this).attr('id');
        var max = 5;
        if (len >= max) {
            $('#' + id + '_inpText').addClass('op0');
        }
        else if (len < max) {
            $('#' + id + '_inpText').removeClass('op0');
        }
    });

function setCityId(e)
{
    if(e.keyCode == 13) {
        $('#pr_cityid').val('1');
    }
}


$('#pr_mobile').focus(function (){
    $('#pr_citySuggestDiv').addClass('dn');
});


    $('#signupCancel').click(function () {
        $("input").val('');
        var a = document.referrer;
        $("#isVendor").removeAttr('checked');
        //alert(a);
        window.location=a;
        //setTimeout(function () {window.location.assign(DOMAIN); },20);
    });
    $('#signupSubmit').bind('click', function () {
        
        
            if(!$('#pr_citySuggestDiv').hasClass('dn'))
            {
                $('#pr_citySuggestDiv').addClass('dn');
            }
            
            
            var pr_name = $('#pr_name').val();
            var pr_mobile = $('#pr_mobile').val();
            var pr_email = $('#pr_email').val();
            var pr_pass = $('#pr_pass').val();
            var city = $('#pr_city').val();
            var cityid = $('#pr_cityid').val();
            var isVendor = $('#isVendor').is(':checked');
                isVendor = $('#isVendor').val();
            var amIVendor = $("input[type=checkbox]:checked").length;
            var isValid = false;
            var userType = isVendor;
            var n = /^[A-Za-z\+*?\s]+$/;
            if(isVendor == 1 || isVendor == '1')
            {
                isVendor=1;
            }
            else
            {
                isVendor=-1;
            }
            var uType = $('#isVendor').val();

                    setTimeout(function () {
                            if(pr_name.length==0 || isNaN(pr_name)!==true || (n.test(pr_name)== false)) {
                                    customStorage.toast(0,'Invalid format for Name');
                                    $('#pr_name').focus();
                                    return false;
                            }
                            else if(city == '' || !(pr_name.match(n))) {
                                    customStorage.toast(0,'City is mandatory!');
                                    $('#pr_city').focus();
                                    return false;
                            }
                            else if(cityid == '') {
                                    customStorage.toast(0,'Choose the city from the list');
                                    $('#pr_cityid').focus();
                                    return false;
                            }
                            else if(pr_mobile=='' || pr_mobile.length!=10 || isNaN(pr_mobile)) {
                                    customStorage.toast(0,'Invalid format for Mobile');
                                    $('#pr_mobile').focus();
                                    return false;
                            }
                            else if(pr_email=='') {
                                    customStorage.toast(0,'Email is Required!');
                                    $('#pr_email').focus();
                                    return false;
                            }
                            else if(!common.validateEmail('pr_email')) {
                                    customStorage.toast(0,'Email is Not Valid!');
                                    $('#pr_email').focus();
                                    return false;
                            }
                            else if(pr_pass=='') {
                                    customStorage.toast(0,'Password is Required!');
                                    $('#pr_pass').focus();
                                    return false;
                            }
                            else if(pr_pass.length <= 5 || pr_pass == 0) {
                                    customStorage.toast(0,'Password must have minimum 6 characters!');
                                    $('#pr_pass').focus();
                                    return false;
                            }
                            else if(pr_mobile.length==10 && common.validateEmail('pr_email'))
                            {
                                    var tmstmp = new Date().getTime();
                                        $.ajax({url: DOMAIN + "apis/index.php?action=checkUser&mobile=" + pr_mobile + "&email="+pr_email+"&isVendor="+uType+"&timestamp="+tmstmp, success: function (result) {
                                                var obj = jQuery.parseJSON(result);

                                                var errCode = obj.error.type.code;

                                                if(errCode == 3)
                                                {
                                                    customStorage.toast(0,'Mobile number is already registered!');
                                                    $('#pr_mobile').focus();
                                                    return false;
                                                }
                                                else if(errCode == 2)
                                                {
                                                    customStorage.toast(0,'Email id is already registered!');
                                                    $('#pr_email').focus();
                                                    return false;
                                                }
                                                else if(errCode == 4)
                                                {
                                                    customStorage.toast(0,obj.error.type.flagMsg);
                                                }
                                                else if(errCode == 5)
                                                {
                                                    if(isVendor == -1)
                                                    {
                                                            userType = 0;
                                                    }
                                                    pr_mobile = customStorage.addToStorage('mobile',pr_mobile);
                                                    otpGo(pr_mobile);
                                                    requestOTP();
                                                }
                                                else if(obj.error.type == 0)
                                                {
                                                    if(isVendor == -1)
                                                    {
                                                            userType = 0;
                                                    }
                                                    pr_mobile = customStorage.addToStorage('mobile',pr_mobile);
                                                    otpGo(pr_mobile);
                                                    requestOTP();
                                                }
                                            }});

                            }
                    }, 250);
    });

function isValidMKey(evt, id) {
    var val = $('#' + id).val();
    var len = val.length;
    var match = /^[7-9]/g;
    var val = val.match(match);
    if (val == null) {
        $('#' + id).val('');
    }
}

$(".amIVendor").change(function () {
    $(".amIVendor").not(this).prop('checked', false);
});

function requestOTP()
{
    $('#overlay').removeClass('dn');
     $('#otpDiv').removeClass('dn');
    setTimeout(function () {
        $('#overlay').velocity({opacity: 1}, {delay: 0, duration: 300, ease: 'swing'});
        $('#optDiv').velocity({scale: 1}, {delay: 80, duration: 100, ease: 'swing'});
    }, 10);
}
function otpCheck()
{
   //var mobile = customStorage.readFromStorage('mobile');
   //checkOtp();
   //return false;

    var otpProvided =  $('#pr_otp').val();
//    if(otpProvided == undefined || otpProvided == 'undefined' || otpProvided == null || otpProvided == 'null' || otpProvided == '' || isNaN(otpProvided))
//    {
//        customStorage.toast(0,'Please enter the correct OTP or click on resend button');
//    }

    var mobile = customStorage.readFromStorage('mobile');
	var isValid= '';

    $.ajax({url: DOMAIN + "apis/index.php?action=validOTP&mobile="+mobile+"&vc="+otpProvided, success: function(result)
       {
                var obj = jQuery.parseJSON(result);
                var errCode = obj.error.Code;
                if(errCode == 0 || errCode == '0')
                {
                    isValid = true;
                    if(pageName == 'forgot')
                    {
                        $.ajax({url: DOMAIN + "apis/index.php?action=forgotPwd&email=" + mobile, success: function (result) {
                            var obj = jQuery.parseJSON(result);
                            var errCode = obj['error']['Code'];
                            var errMsg = obj['error']['Msg'];
                            if(errCode == 1)
                            {
                                customStorage.toast(0,errMsg);
                            }
                            else
                            {
                                customStorage.toast(1,errMsg);
                                setTimeout(function () {
                                    $('#overlay,#otpDiv').addClass('dn');
                                    $("#otpDiv,#overlay").remove();
                                }, 10);
                                setTimeout(function ()
                                {
                                    window.location.assign(DOMAIN + "Login")
                                },4000);
                            }
                        }});
                    }
                    else
                    {

                        var pr_name = encodeURIComponent($('#pr_name').val());
                        var pr_mobile = encodeURIComponent($('#pr_mobile').val());
                        var pr_email = encodeURIComponent($('#pr_email').val());
                        var pr_pass = encodeURIComponent($('#pr_pass').val());
                        var pr_city = encodeURIComponent($('#pr_city').val());
                        //var isVendor = $('#isVendor').is(':checked');
                        var isVendor = $('#isVendor').val();
                        var amIVendor = $("input[type=checkbox]:checked").length;
                        isValid = false;
                        var userType = isVendor;
                        if(isVendor == 1)
                        {
                            isVendor=1;
                        }
                        else
                        {
                            isVendor=-1;
                        }
                        $.ajax({url: DOMAIN + "apis/index.php?action=userReg&username=" + pr_name +"&password=" + pr_pass +"&mobile=" + pr_mobile + "&cityname="+pr_city+"&email="+pr_email+'&isvendor='+userType, success: function (result) {
                                    var obj = eval('('+result+')');
                                    var errCode = obj.error.code;
                                    if(errCode == 0)
                                    {
                                            var userid = obj.userid;
                                            customStorage.addToStorage('isLoggedIn',true);
                                            customStorage.addToStorage('userid',userid);
                                            customStorage.addToStorage('mobile',pr_mobile);
                                            customStorage.addToStorage('username',decodeURIComponent(pr_name));
                                            customStorage.addToStorage('is_vendor',decodeURIComponent(isVendor));
                                            customStorage.addToStorage('email',decodeURIComponent(pr_email));
                                            customStorage.addToStorage('name',decodeURIComponent(pr_name));
                                            customStorage.addToStorage('city',decodeURIComponent(pr_city));
                                            if(isVendor === 1)
                                            {
                                                    customStorage.removeFromStorage('busiType');
                                                    window.location.assign(DOMAIN + 'index.php?case=vendor_Form&uid='+userid);
                                            }
                                            else
                                            {
                                               $.ajax({url: DOMAIN + "apis/index.php?action=sendWelcomeMailSMS&username="+decodeURIComponent(pr_name) +"&mobile="+decodeURIComponent(pr_mobile) +"&email="+decodeURIComponent(pr_email)+"&isVendor="+decodeURIComponent(userType), success: function (result) {
                                                    var obj = eval('('+result+')');
                                                    var errCode = obj.error.code;
                                                    if(errCode == 0)
                                                    {
                                                        customStorage.toast(1,'Registration Successfully Done');
                                                        setTimeout(function () {
                                                            window.location.assign(DOMAIN);
                                                            submiter = true;
                                                        },2500);
                                                    }
                                                }});
                                            }
                                    }
                                    else
                                    {
                                            customStorage.toast(0,'Registration Unsuccessfull');
                                    }
                            }
                        });

                    }
                }
                else if(errCode == 1 || errCode == '1')
                {
                    customStorage.toast(0,'OTP verification Unsuccessful');
                    isValid = false;
                }
        }
    });

}

function signUpProceed()
{
    var pr_name = $('#pr_name').val();
    var pr_mobile = $('#pr_mobile').val();
    var pr_email = $('#pr_email').val();
    var pr_pass = $('#pr_pass').val();
    var pr_city = $('#pr_city').val();
    //var isVendor = $('#isVendor').is(':checked');
    var isVendor = $('#isVendor').val();
    var amIVendor = $("input[type=checkbox]:checked").length;
    isValid = false;
    var userType = isVendor;
    if(isVendor == 1)
    {
        isVendor=1;
    }
    else
    {
        isVendor=-1;
    }
    $.ajax({url: DOMAIN + "apis/index.php?action=userReg&username=" + pr_name +"&password=" + pr_pass +"&mobile=" + pr_mobile + "&cityname="+pr_city+"&email="+pr_email+'&isvendor='+userType, success: function (result) {
        var obj = eval('('+result+')');
        var errCode = obj.error.code;
        if(errCode == 0)
        {
                var userid = obj.userid;
                customStorage.addToStorage('isLoggedIn',true);
                customStorage.addToStorage('userid',userid);
                customStorage.addToStorage('mobile',pr_mobile);
                customStorage.addToStorage('username',pr_name);
                customStorage.addToStorage('is_vendor',isVendor);
                customStorage.addToStorage('email',pr_email);
                customStorage.addToStorage('name',pr_name);
                customStorage.addToStorage('city',pr_city);
                if(isVendor === 1)
                {
                        customStorage.removeFromStorage('busiType');
                        window.location.assign(DOMAIN + 'index.php?case=vendor_Form&uid='+userid);
                }
                else
                {
                   $.ajax({url: DOMAIN + "apis/index.php?action=sendWelcomeMailSMS&username="+pr_name +"&mobile="+pr_mobile +"&email="+pr_email +"&isVendor="+userType, success: function (result) {
                        var obj = eval('('+result+')');
                        var errCode = obj.error.code;
                        if(errCode == 0)
                        {
                            customStorage.toast(1,'Registration Successfully Done');
                            setTimeout(function () {window.location.assign(DOMAIN); },2500);
                        }
                    }});
                }
        }
        else
        {
                customStorage.toast(0,'Registration Unsuccessfull');
        }
    }
    });

}



function otpGo(pr_mobile)
{
        var pr_mobile = $('#pr_mobile').val().trim();
        var otpValue = $("#pr_otp").val().trim();
		var isValid = false;

    otpValue = parseFloat(otpValue);
    if(otpValue =='' || otpValue <= 0 || otpValue == undefined || otpValue == 'undefined')
    {
        customStorage.toast(0,'OTP value provided is not proper');
    }
    else
    {
        $.ajax({url: DOMAIN + "apis/index.php?action=sendOTP&mb="+pr_mobile, success: function(result)
            {
                var obj = jQuery.parseJSON(result);

                var errCode = obj.code;
                if(errCode == 1)
                {

                    isValid = true;
                    customStorage.toast(1,'OTP is sent to your mobile number');
                    return isValid;
                }
                if(errCode == 0)
                {
                    customStorage.toast(0,'OTP sending failed');
                    return isValid;
                }
            }
        });
    }
}

function closeOtpForm()
{
        $('#otpDiv').velocity({scale: 0}, {delay: 0, ease: 'swing'});
        window.history.back();
        $('#overlay').velocity({opacity: 0}, {delay: 100, ease: 'swing'});
        setTimeout(function () {
            $('#overlay,#otpDiv').addClass('dn');
            $("#otpDiv,#overlay").remove();
        }, 1010);
}

function onEnterFormSubmit(evt,type)
{
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if(charCode==13)
    {
        if(type==1)
        {
            otpCheck();
        }
    }
}


function checkOtp()
{
    var otpProvided =  $('#pr_otp').val();
    var mobile = customStorage.readFromStorage('mobile');

    $.ajax({url: DOMAIN + "apis/index.php?action=validOTP&mobile="+mobile+"&vc="+otpProvided, success: function(result)
       {
                var obj = jQuery.parseJSON(result);
                var errCode = obj.error.Code;
                if(errCode == 0 || errCode == '0')
                {
                    isValid = true;
                }
                else if(errCode == 1 || errCode == '1')
                {
                    isValid = false;
                }
        }
    });
}

/* For suggestions of City */
$('#pr_city').bind('keyup focus', input_selector, function(event)
{
    var params = 'action=cityName&name=' + escape($(this).val());
    new Autosuggest($(this).val(), '#pr_city', '#pr_citySuggestDiv', DOMAIN + "apis/index.php", params, true, '', '', event);
});


function arrangeData(data, id, divHolder, nextxt)
{
    if (data.results)
    {

        var suggest = "<ul class='smallField w100 fmRoboto transition300 font14 pointer border1'>";
        $.each(data.results, function(i, vl) {
            if(id == '#pr_city')
                suggest += "<li id='suggest" + i + "' class='autoSuggestRow w100 transition300 txtCaCase txtOverFlow txtOver' title="+vl.n+" style='text-transform:capitalize;' onClick='setSuggestValue(\""+vl.n+"\",\"#pr_city\",\""+vl.id+"\");'>"+vl.n+"</li>";
            if(id == '#ur_city')
                suggest += "<li id='suggest" + i + "' class='autoSuggestRow w100 transition300 txtCaCase txtOverFlow txtOver' title="+vl.n+" style='text-transform:capitalize;' onClick='setCityValue(\""+vl.n+"\",\"#ur_city\",\""+vl.id+"\");'>"+vl.n+"</li>";
        });
        suggest += "</ul>";
        return suggest;
    }
    else
        return '';
}

function setSuggestValue(val, id, cid) {
    $(id).val(val);
    $('#pr_cityid').val(id);
    $(id).next( "label" ).addClass("labelActive");
    $(id).addClass('brGreen');
    setTimeout(function () {
        $('#pr_citySuggestDiv').addClass('dn');
    }, 50);
}

function setCityValue(val, id, cid) {
    $(id).val(val);
    $('#ur_cityid').val(val);
    $(id).next( "label" ).addClass("labelActive");
    $(id).addClass('brGreen');
    setTimeout(function () {
        $('#ur_citySuggestDiv').addClass('dn');
    }, 50);
}

function closeSuggest(id) {
    setTimeout(function () {
        $('#' + id).html('');
    }, 10);
}
$('body').click(function()
{
    $('#pr_citySuggestDiv').addClass('dn');
    $('#ur_citySuggestDiv').addClass('dn');
});

$(document).ready(function ()
{
    var uid = customStorage.readFromStorage('userid');
        if(uid == null || uid == undefined || uid == 'undefined' || uid == 'null' || uid == '')
        {
            $('#vsignup').removeClass('dn');
        }

    $('#ur_city').bind('keyup focus', input_selector, function(event)
    {
        var params = 'action=cityName&name=' + escape($(this).val());
        new Autosuggest($(this).val(), '#ur_city', '#ur_citySuggestDiv', DOMAIN + "apis/index.php", params, true, '', '', event);
    });
});

function changeHIdVal()
{
    if($('#ur_city').val() == '')
    {
        $('#ur_cityid').val('');
    }
}
