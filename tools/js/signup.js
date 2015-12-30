var input_selector = 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea, input[type=radio]';
var pw = $(window).width();
var ph = $(window).height();
var isMobile = false;
if (pw < 768) {
    isMobile = true;
}

var validMob = true;


    if (isMobile) {
        $(input_selector).focus(function () {
            var npos = $('body').scrollTop() + 40;
            $('body').animate({scrollTop: npos}, 100);
        });
    }
    
    $(input_selector).bind('focus',function() {
        $(this).siblings('label, i').addClass('labelActive');
        $(this).addClass('brOrange');//.removeClass('brRed');
        $('.bsText').addClass('op0');
    });

    $(input_selector).bind('blur',function() {
        if ($(this).val().length === 0 && $(this).attr('placeholder') === undefined) {
            $(this).siblings('label, i').removeClass('labelActive');
            $(this).removeClass('brOrange brGreen');//.addClass('brRed');
        } else {
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
        


    $('#signupCancel').click(function () {
        $("input").val('');
        $("#isVendor").removeAttr('checked');
        setTimeout(function () {window.location.assign(DOMAIN + 'index.php'); },20);
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
        var cityid = $('#pr_cid').val();
        var isVendor = $('#isVendor').is(':checked');
        var amIVendor = $("input[type=checkbox]:checked").length;
        var isValid = false;
        var userType =1;
        if(isVendor)
            isVendor=1;
        else 
            isVendor=-1;

		if(pr_mobile.length==10)
                {
                        $.ajax({url: DOMAIN + "apis/index.php?action=checkUser&mobile=" + pr_mobile, success: function (result) {
				var obj = jQuery.parseJSON(result);
				var errCode = obj['error']['Code'];
				if(errCode == 1) {
					validMob = false;
				} else {
					validMob = true;
				}
			}});
		}

		setTimeout(function () {
			if(pr_name.length==0 || isNaN(pr_name)!==true) {
				customStorage.toast(0,'Invalide format for Name'); 
				$('#pr_name').focus();
				return false;
			}
                        else if(city == '') {
				customStorage.toast(0,'City is mandatory!'); 
				$('#pr_city').focus();
				return false;
			}
                         else if(cityid == '') {
				customStorage.toast(0,'Please choose city from the list!'); 
				$('#pr_city').focus();
				return false;
			}
                        else if(pr_mobile=='' || pr_mobile.length!=10 || isNaN(pr_mobile)) {
				customStorage.toast(0,'Invalid format for Mobile'); 
				$('#pr_mobile').focus();
				return false;
			} else if(pr_email=='') {
				customStorage.toast(0,'Email is Required!'); 
				$('#pr_email').focus();
				return false;
			} else if(!common.validateEmail('pr_email')) {
				customStorage.toast(0,'Email is Not Valid!'); 
				$('#pr_email').focus();
				return false;
			} else if(pr_pass=='') {
				customStorage.toast(0,'Password is Required!'); 
				$('#pr_pass').focus();
				return false;
			}
                        else if(!validMob) {
				customStorage.toast(0,'This mobile number is already registered!'); 
				$('#pr_mobile').focus();
				return false;
			}
                        
			else if(amIVendor == undefined || amIVendor == 'undefined' || amIVendor == '' || amIVendor == null || amIVendor == 'null' || amIVendor == 0){
				customStorage.toast(0,'You have not Selected the type of user!'); 
				return false;
			} else {
					if(isVendor == -1)
					{
						userType = 0;
					}
                                        pr_mobile = customStorage.addToStorage('mobile',pr_mobile);
                                        otpGo(pr_mobile);
                                        requestOTP();
                                        
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
                                window.location.assign(DOMAIN + "index.php?case=login");
                            }
                        }});
                    }
                    else
                    {

                        var pr_name = $('#pr_name').val();
                        var pr_mobile = $('#pr_mobile').val();
                        var pr_email = $('#pr_email').val();
                        var pr_pass = $('#pr_pass').val();
                        var pr_city = $('#pr_city').val();
                        var isVendor = $('#isVendor').is(':checked');
                        var amIVendor = $("input[type=checkbox]:checked").length;
                        isValid = false;
                        var userType =1;
                        if(isVendor)
                            isVendor=1;
                        else 
                            isVendor=-1;
                        $.ajax({url: DOMAIN + "apis/index.php?action=userReg&username=" + pr_name +"&password=" + pr_pass +"&mobile=" + pr_mobile + "&cityname="+pr_city+"&email="+pr_email+'&isvendor='+userType, success: function (result) {
                                    var obj = eval('('+result+')');
                                    var errCode = obj.error.code;
                                    if(errCode == 0) {
                                            var userid = obj.userid;
                                            customStorage.addToStorage('isLoggedIn',true);
                                            customStorage.addToStorage('userid',userid);
                                            customStorage.addToStorage('mobile',pr_mobile);
                                            customStorage.addToStorage('username',pr_name);
                                            customStorage.addToStorage('is_vendor',isVendor);
                                            customStorage.addToStorage('email',pr_email);
                                            customStorage.addToStorage('name',pr_name);
                                            customStorage.addToStorage('city',pr_city);
                                            if(isVendor===1) {
                                                    customStorage.removeFromStorage('busiType');
                                                    window.location.assign(DOMAIN + 'index.php?case=vendor_Form&uid='+userid);
                                            } else {
                                                    customStorage.toast(1,'Registration Successfull Done');
                                                    setTimeout(function () {window.location.assign(DOMAIN + 'index.php'); },1500);
                                            }
                                    } else {
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


function otpGo(pr_mobile)
{
        var pr_mobile = $('#pr_mobile').val().trim();
        var otpValue = $("#pr_otp").val().trim();
		var isValid = false;

    otpValue = parseFloat(otpValue);
    if(otpValue =='' || otpValue <= 0 || otpValue == undefined || otpValue == 'undefined')
    {
        common.toast(0,'OTP value provied is not proper');
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
                    return isValid;
                }
                if(errCode == 0)
                {
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
                console.log(obj);
                var errCode = obj.error.Code;
                console.log(errCode);
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
                suggest += "<li id='suggest" + i + "' class='autoSuggestRow w100 transition300 txtCaCase txtOverFlow txtOver' title="+vl.n+" style='text-transform:capitalize;' onClick='setSuggestValue(\""+vl.n+"\",\"#pr_city\",\""+vl.id+"\");'>"+vl.n+"</li>";
        });
        suggest += "</ul>";  
        return suggest;
    }
    else
        return '';
    
}

function setSuggestValue(val, id, cid) {
    $(id).val(val);
    $('#pr_cid').val(cid);
    $(id).next( "label" ).addClass("labelActive");
    $(id).addClass('brGreen');
    setTimeout(function () {
        $('#pr_citySuggestDiv').addClass('dn');
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
});