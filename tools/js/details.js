var isOpen = false;
var lastSc=0;
var pw=$(window).width();
var ph=$(window).height();
var isMobile=false;
var input_selector = 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea, input[type=radio]';
if(pw<768){
    isMobile=true; 
}
var mobile = customStorage.readFromStorage('mobile');
var name = customStorage.readFromStorage('name');
var email = customStorage.readFromStorage('email');
var uid = customStorage.readFromStorage('userid');
var isWishList = false;
var isMail = false;
var isContact = false;
var isPidInWishlist = false;
var funcObj = '';
$(document).ready(function(){
     setTimeout(function() {
        var samt = 100;
        if (isMobile) {
            samt = 450;
        }
      //  $('body').animate({scrollTop: samt}, 300);
    }, 100);

    
    $('#gallery1 .thumbnil').click(function(){
        var img=$(this).css('background');
        $('#prdImage').css({'background':img});
    });
    
    $('.imgThumbnil').click(function(){
        var img=$(this).css('background');
        $('.galleryImg').css({'background':img});
    });
    
    $('.imgPreview').click(function(){
        var img=$(this).css('background');
        $('.galleryImg').css({'background':img});
    });
   
    $('#galleryClose').click(function(){
       $('#imgGallery').addClass('dn');
    });
    
    $('#prdImage').click(function(){
       $('#imgGallery').removeClass('dn');
    });
    
        
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
    
    $('#dragTarget').click(function() {
        showLeftMenu(false);
    });
    
    $('.iconWishlist').click(function(){
		var isVendor = customStorage.readFromStorage('is_vendor');
		var isLoggedIn = customStorage.readFromStorage('isLoggedIn');

		if(isVendor != "" && (isVendor == '-1' || isVendor == -1))
		{
			isVendor = 0;
		}

		if((isVendor !== '2' && isVendor !== 2 ) || isLoggedIn == undefined || isLoggedIn == null || isLoggedIn == '' || isLoggedIn == false || isLoggedIn == 'false')
		{
			mobile = customStorage.readFromStorage('mobile');
			name = customStorage.readFromStorage('name');
			email = customStorage.readFromStorage('email');
			uid = customStorage.readFromStorage('userid');

			if(uid == '' || uid == null || uid == undefined)
			{
				isWishList = true;
				common.showLoginForm(1);
			}
			else
			{
				isWishList = true;
				showVendorDetails(this);
			}
		}
                
		else
		{   
			if(isVendor == 2 || isVendor == '2')
			{
				customStorage.toast(0, 'This feature is not available for Admin');
			}
			/*else
			{
				customStorage.toast(0, 'This feature is not available for vendors');
			}*/
			
		}
    });

    $('#overlay').velocity({opacity:0},{delay:0,duration:0});
    $('#userForm').velocity({scale:0},{delay:0,duration:0});

    $('.iconCall,.iconMessage').click(function(){
		var isVendor = customStorage.readFromStorage('is_vendor');
		var isLoggedIn = customStorage.readFromStorage('isLoggedIn');

		if(isVendor != '' && (isVendor == -1 || isVendor == "-1"))
		{
			isVendor = 0;
		}

		if((isVendor !== '2' && isVendor !== 2) || isLoggedIn == undefined || isLoggedIn == null || isLoggedIn == '' || isLoggedIn == false || isLoggedIn == 'false')
		{
			mobile = customStorage.readFromStorage('mobile');
			name = customStorage.readFromStorage('name');
			email = customStorage.readFromStorage('email');
			uid = customStorage.readFromStorage('userid');
			islog = customStorage.readFromStorage('isLoggedIn');

			if(uid == '' || uid == null || uid == undefined || islog == false || islog == '' || islog == null || islog == undefined)
			{
				$('#overlay,#userForm').removeClass('dn');
				setTimeout(function(){
					$('#overlay').velocity({opacity:1},{delay:0,duration:300,ease:'swing'});
					$('#userForm').velocity({scale:1},{delay:80,duration:100,ease:'swing'});
				},10); 
				if($(this).hasClass('iconMessage'))
				{
					isMail = true;
					isWishList = false;
				}
				if($(this).hasClass('iconCall'))
				{
					isWishList = false;
					isMail = false;
				}
			}
			else
			{
				if($(this).hasClass('iconMessage'))
				{
					isMail = true;
					isWishList = false;
				}
				if($(this).hasClass('iconCall'))
				{
					isWishList = false;
					isMail = false;
				}
				showVendorDetails(this);
			}
		}
		else
		{
			if(isVendor == 2 || isVendor == '2')
			{
				customStorage.toast(0, 'This feature is not available for Admin');
			}
			/*else
			{
				customStorage.toast(0, 'This feature is not available for vendors');
			}*/
		}
    });
    
    
    $('#userCancel').bind('click',function(){
        $('#userForm').velocity({scale:0},{delay:0,ease:'swing'});
        $('#overlay').velocity({opacity:0},{delay:100,ease:'swing'});
        setTimeout(function(){
            $('#overlay,#userForm').addClass('dn');
        },1010);
    }); 
    
        //console.log(isNaN(ur_name));return false;
	
	$('#userSubmit').bind('click',function() {
        var ur_name = $('#ur_name').val();
        var ur_mobile = $('#ur_mobile').val();
        var ur_email = $('#ur_email').val();
        if(ur_mobile=='' || ur_mobile.length!=10 || isNaN(ur_mobile)) {
            customStorage.toast(0,'Invalid Format for Mobile'); 
            $('#ur_mobile').focus();
            return false;
        }
        else if(ur_name.length==0 || isNaN(ur_name)!==true) {
            customStorage.toast(0,'Invalid Format for Name'); 
            $('#ur_name').focus();
            return false;
        }
        else if(ur_email=='') {
            customStorage.toast(0,'Email is Required!'); 
            $('#ur_email').focus();
            return false;
        } 
        else if(!common.validateEmail('ur_email')) {
            customStorage.toast(0,'Email is Not Valid!'); 
            $('#ur_email').focus();
            return false;
        }
        else 
        {
			if(isMail)
			{
				var isLoggedIn = customStorage.readFromStorage('isLoggedIn');
				if(isLoggedIn == '' || isLoggedIn == null || isLoggedIn == undefined || isLoggedIn == false || isLoggedIn == 'false')
				{
					customStorage.addToStorage('mobile',ur_mobile);
					pr_mobile = customStorage.readFromStorage('mobile');
					otpGo(pr_mobile);
					requestOTP();
				}
				else
				{
					sendDetailsToUser();            
					addToEnquiry();
				}
			}
			else
			{
				showVendorDetails();
			}

            $('#userForm').velocity({scale:0},{delay:0,ease:'swing'});
            $('#overlay').velocity({opacity:0},{delay:100,ease:'swing'});
            setTimeout(function(){
                    $('#overlay,#userForm').addClass('dn');
            },1010);
            return true;
        }
	});

	if(mobile !== '' && mobile !== null && mobile !== undefined && mobile !== 'null' && mobile !== 'undefined' && typeof mobile !== 'undefined')
	{
		$('#ur_mobile').val(mobile);
		common.changeStyle('mobile');
	}
	if(name !== '' && name !== null && name !== undefined && name !== 'null' && name !== 'undefined' && typeof name !== 'undefined')
	{
		$('#ur_name').val(name);
		common.changeStyle('name');
	}
	if(email !== '' && email !== null && email !== undefined && email !== 'null' && email !== 'undefined' && typeof email !== 'undefined')
	{
		$('#ur_email').val(email);
		common.changeStyle('email');
	}

	if(pageName == 'diamond_details' || pageName == 'bullion_details' || pageName == 'jewellery_details')
	{
		getWishList();
	}

	if(popup !== null && popup !== '' && popup !== undefined)
	{
		if(popup == '3')
		{
			$('.iconWishlist').click();
		}
		else if(popup == '1')
		{
			$('.iconCall').click();
		}
		else if(popup == '2')
		{
			$('.iconMessage').click();
		}
	}
});

function eSubmit(evt, btnId)
{
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode === 13) {
		$('#' + btnId).click();
	}
}

function showLeftMenu(flag) {
    if (flag) {
        $('#leftMenu').removeClass('leftTransit');
        $('body').addClass('pFixed');
        $('#dragTarget').css({width: '50%', left: '250px'});

    } else {
        $('#leftMenu').addClass('leftTransit');
        $('body').removeClass('pFixed');
        $('#dragTarget').css({width: '20px', left: '0px'});
    }
}


var ele = document.getElementById('dragTarget');
var mc = new Hammer(ele);
mc.on("panleft panright tap press", function(ev) {
    //ele.textContent = ev.type +" gesture detected.";
    if (ev.type == 'panright') {
        showLeftMenu(true);
    }
    else if (ev.type == 'panleft') {
        showLeftMenu(false);
    }
});


var len=$('#gallery1 .thumbnil').length;
var val = 0;
var moveCount = 0;
function movePrImg(flag) {
    var totalThumbs = $('#gallery1 .thumbnil').length;
    var liWidth = ($('#gallery1 .thumbnil').outerWidth());
    if (!flag) {
        if (moveCount < (totalThumbs - 4)) {
            val = val - liWidth-4;
            $("#gallery1 .carousel").css({transform: 'translateX(' + val + 'px)'});
            moveCount++;
        }
    }
    else if (flag) {
        if (moveCount > 0) {
            val = val + liWidth+4;
            $("#gallery1 .carousel").css({transform: 'translateX(' + val + 'px)'});
            moveCount--;
        }
    }
}

function replaceAll(find, replace, str) {
	return str.replace(new RegExp(find, 'g'), replace);
}

function showVendorDetails(obj)
{
	var mobile = customStorage.readFromStorage('mobile');
	var name = customStorage.readFromStorage('name');
	var email = customStorage.readFromStorage('email');
	var uid = customStorage.readFromStorage('userid');
	var isLoggedIn = customStorage.readFromStorage('isLoggedIn');
        
	if(isLoggedIn == '' || isLoggedIn == null || isLoggedIn == undefined || isLoggedIn == false || isLoggedIn == 'false')
	{
		var mobile = $('#ur_mobile').val().trim();
		var name = $('#ur_name').val().trim();
		var email = $('#ur_email').val().trim();
		var mobCond = (mobile !== '' && mobile !== null && mobile !== undefined) ? true : false;
		var nmCond = (name !== '' && name !== null && name !== undefined) ? true : false;
		var emCond = (email !== '' && email !== null && email !== undefined) ? true : false;

		if(mobCond && nmCond && emCond)
		{
                    
			funcObj = obj;
			common.checkMobile('ur_mobile');
			customStorage.addToStorage('mobile',mobile);
			pr_mobile = customStorage.readFromStorage('mobile');
			otpGo(pr_mobile);
			requestOTP();
		}
			
	}
	else
	{
		if(isWishList==true)
		{
			addToWishList();
		}
		else if(isMail==true)
		{
			$('#overlay,#userForm').removeClass('dn');
			setTimeout(function(){
				$('#overlay').velocity({opacity:1},{delay:0,duration:300,ease:'swing'});
				$('#userForm').velocity({scale:1},{delay:80,duration:100,ease:'swing'});
			},10);
			/*sendDetailsToUser();
			addToEnquiry();*/
		}
		else if((isMail==false)&&(isWishList==false))
		{
                    var bottomPos = $('.prdMainDetails').position().top+$('.prdMainDetails').outerHeight(true)
			var pos=bottomPos - 65;

			setTimeout(function(){
				$('#vDetails').removeClass('vTransit');
				$('#vDetails').removeClass('dn');
				$('body').animate({scrollTop: pos}, 300);
			},10);

			setTimeout(function () {
				var mpscrpt = document.createElement("script");
				mpscrpt.type = "text/javascript";
				mpscrpt.src = "http://maps.google.com/maps/api/js";
				$("head").append(mpscrpt);
			}, 100);

			setTimeout(function () {
				initMap(vndrLat*1,vndrLng*1,vndrFullAddr);
			},250);
			addToEnquiry();
		}
	}
}
function initMap(lat,lng,contentString) {
	if(typeof google !== 'undefined' && google !== undefined && google !== '' && google !== null)
	{
		if(google.maps !== null && google.maps !== undefined && google.maps !== '')
		{
			var myLatLng = {lat: lat, lng: lng};
			//var infowindow = new google.maps.InfoWindow();
			var map = new google.maps.Map(document.getElementById('googleMap'), {
				zoom: 16,
				center: new google.maps.LatLng(lat, lng)
				//center: myLatLng
			});
		}
	}

	/*var marker = new google.maps.Marker({
		position: myLatLng,
		map: map,
		title: 'Hello World!'
	});
	
	google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(contentString);
        infowindow.open(map, marker);
    });*/
}

//imgvendorGallery


$('#galleryClose2').click(function(){
    $('#imgvendorGallery').addClass('dn');
 });

 $('#moreimg').click(function(){
    $('#imgvendorGallery').removeClass('dn');
 });

function addToEnquiry()
{
	var userid = customStorage.readFromStorage('userid');
        if(userid !== null && vendor_id !== null && pid !== null)
        {
            var params = 'action=ajx&case=addToEnquiry&uid='+userid+'&vid='+vendor_id+'&pid='+pid;
            var URL = DOMAIN + "index.php";
           
            $.getJSON(URL, params, function(data) {
				if(data !== null && data !== undefined && data !== '' && data !== 'null' && data !== 'undefined' && typeof data !== 'undefined')
				{
					if(data.error !== '' && data.error !== null && data.error !== undefined && data.error !== 'undefined' && typeof data.error !== 'undefined' && data.error.Code == 0)
					{
						 //customStorage.toast(0, 'Product is already present in wishlist');
					}
				}
            });
        }
        /*else
        {
            showVendorDetails();
        }*/
}

function addToWishList()
{
	if(isPidInWishlist == false)
	{
		var userid = customStorage.readFromStorage('userid');
		if(userid !== null && vendor_id !== null && pid !== null)
		{
			var params = 'action=ajx&case=addToWishList&userid='+userid+'&vid='+vendor_id+'&prdid='+pid;
			var URL = DOMAIN + "index.php";

			$.getJSON(URL, params, function(data) {
				if(data !== null && data !== undefined && data !== '' && data !== 'null' && data !== 'undefined' && typeof data !== 'undefined')
				{
					if(data.error !== '' && data.error !== null && data.error !== undefined && data.error !== 'undefined' && typeof data.error !== 'undefined' && data.error.Code == 0)
					{
						isPidInWishlist = true;
						$('#addtowishlist').html('Added To Wishlist');
						common.getWishListCount();
						if(data.results.indexOf('updated') !== -1)
						{
							customStorage.toast(1, 'Product already present in wishlist');
						}
						else
						{
							customStorage.toast(1,'Added to wishlist');
						}
					}
				}
			});
		}
	}
	else
	{
		customStorage.toast(0, 'Product is already present in wishlist');
	}
}

$('#loginDiv').velocity({scale: 0}, {delay: 0, duration: 0});
$('#signInUpTab').click(function () {
    mobile = customStorage.readFromStorage('mobile');
    name = customStorage.readFromStorage('name');
    email = customStorage.readFromStorage('email');

    if (mobile == '' || mobile == null || mobile == undefined)
    {
        $('#overlay,#loginDiv').removeClass('dn');
        setTimeout(function () {
            $('#overlay').velocity({opacity: 1}, {delay: 0, duration: 300, ease: 'swing'});
            $('#loginDiv').velocity({scale: 1}, {delay: 80, duration: 100, ease: 'swing'});
        }, 10);
    }
    else
    {

    }
});
    
$('#overlay').bind('click', function () {
    closeAllForms();
});
$('#lgSubmit, #lgCancel').bind('click', function () {
    if(this.id=='lgCancel') {
        closeAllForms();
    }
    
});

function closeAllForms() {
    $('#loginDiv,#userForm').velocity({scale: 0}, {delay: 0, ease: 'swing'});
    $('#overlay').velocity({opacity: 0}, {delay: 100, ease: 'swing'});
    setTimeout(function () {
        $('#overlay,#loginDiv,#userForm').addClass('dn');
    }, 1010);
}

function getWishList()
{
	var userid = customStorage.readFromStorage('userid');
	if(vendor_id !== '' && pid !== '' && userid !== '' && userid !== undefined && userid !== null)
	{
		var params = 'action=ajx&case=checkWish&userid='+userid+'&vid='+vendor_id+'&prdid='+pid;
		var URL = DOMAIN + "index.php";

		$.getJSON(URL, params, function(data) {
			if(data !== null && data !== undefined && data !== '' && data !== 'null' && data !== 'undefined' && typeof data !== 'undefined')
			{
				isPidInWishlist = false;
				if(data.error !== '' && data.error !== null && data.error !== undefined && data.error !== 'undefined' && typeof data.error !== 'undefined' && data.error.Code == 0)
				{
					if(data.results !== '' && data.results.length > 0)
					{
						for(i = 0 ; i < data.results.length; i++)
						{
							if(data.results[i].pid == pid)
							{
								isPidInWishlist = true;
								break;
							}
						}
					}

					if(isPidInWishlist == true)
					{
						$('#addtowishlist').html('Added To Wishlist');
					}
				}
			}
		});
	}
}

function getUserDetails()
{
	var userid = customStorage.readFromStorage('userid');
	if(userid !== '' && userid !== undefined && userid !== null)
	{
		var params = 'action=ajx&case=getUserDet&uid='+userid;
		var URL = DOMAIN + "index.php";
		$.getJSON(URL, params, function(data) {
			if(data !== null && data !== undefined && data !== '' && data !== 'null' && data !== 'undefined' && typeof data !== undefined)
			{
                           
				if(data.error !== '' && data.error !== null && data.error !== undefined || data.error !== 'undefined')
				{
                                     
					if(data.results !== '' && data.results.length > 0)
					{
						$('#ur_mobile').val(data.results[0].logmobile);
						$('#ur_name').val(data.results[0].user_name);
						$('#ur_email').val(data.results[0].email);
					}
					else
					{
						customStorage.toast(0,'error');
					}

				}
			}
			else
			{
				customStorage.toast(0,'error');
			}
		});
	}
}

function sendDetailsToUser()
{
	var usrName = $('#ur_name').val();
	var usrMobile = $('#ur_mobile').val();
	var usrEmail = $('#ur_email').val();

	var isValidName = (usrName !== '' && usrName !== null && usrName !== undefined) ? true : false;
	var isValidMobile = (usrMobile !== '' && usrMobile !== null && usrMobile !== undefined) ? true : false;
	var isValidEmail = (usrEmail !== '' && usrEmail !== null && usrEmail !== undefined) ? true : false;

	if(isValidMobile)
	{
		isValidMobile = common.validate_mobile(usrMobile);
	}

	if(isValidEmail)
	{
		isValidEmail = common.validate_email(usrEmail);
	}

	if(isValidName && isValidMobile && isValidEmail)
	{
		var params = 'action=ajx&case=sendDetailsToUser&usrName='+encodeURIComponent(usrName)+'&usrMobile='+encodeURIComponent(usrMobile)+'&usrEmail='+encodeURIComponent(usrEmail)+'&prdid='+encodeURIComponent(pid);
		var URL = DOMAIN + "index.php";
		$.getJSON(URL, params, function(data) {
			if(data !== undefined && data !== null && data !== '')
			{
				if(data.error !== undefined && data.error !== null && data.error !== '')
				{
					if(data.error.Code !== undefined && data.error.Code !== null && data.error.Code !== '' && data.error.Code == 0)
					{
						customStorage.toast(1, 'SMS / Email sent to the details');
					}
					else
					{
						customStorage.toast(0, 'Error sending SMS / Email');
					}
				}
				else
				{
					customStorage.toast(0, 'Error sending SMS / Email');
				}
			}
			else
			{
				customStorage.toast(0, 'Error sending SMS / Email');
			}
		});
	}
}


function requestOTP()
{
    $('#overlay1').removeClass('dn');
     $('#otpDiv').removeClass('dn');
    setTimeout(function () {
        $('#overlay1').velocity({opacity: 1}, {delay: 0, duration: 300, ease: 'swing'});
        $('#optDiv').velocity({scale: 1}, {delay: 80, duration: 100, ease: 'swing'});
    }, 10);
}
function otpCheck()
{
   var otpProvided =  $('#pr_otp').val();
   var mobile = customStorage.readFromStorage('mobile');
   var isValid = true;
   $.ajax({url: DOMAIN + "apis/index.php?action=validOTP&mobile="+mobile+"&vc="+otpProvided, success: function(result)
       {
                var obj = jQuery.parseJSON(result);
                var errCode = obj['error']['Msg'];
                
                if(errCode == 'Data matched')
                {
                    isValid = true;
                    var mobile = $('#ur_mobile').val().trim();
                        var name = $('#ur_name').val().trim();
                        var email = $('#ur_email').val().trim();
                        
			var params = 'action=ajx&case=userCheck&mobile='+mobile+'&name='+encodeURIComponent(name)+'&email='+encodeURIComponent(email);
			var URL = DOMAIN + "index.php";

			$.getJSON(URL, params, function(data) {
				if(data !== null && data !== undefined && data !== '')
				{
					if((data.error !== '' && data.error !== null && data.error !== undefined && data.error.Code == 0) || (data.error.Code == 1 && data.results.userid != ''))
					{
						/*if(data.results.userDet[0].is_vendor == 1)
						{
							customStorage.toast(0, 'This feature is not available for vendors');
						}
						else
						{*/
							if(data.error.Msg === 'Data matched')
							{
								customStorage.addToStorage('l', data.results.userDet.logmobile);
								customStorage.addToStorage('mobile', data.results.userDet.logmobile);
								customStorage.addToStorage('username', data.results.userDet.user_name);
								customStorage.addToStorage('name', name);
								customStorage.addToStorage('email', email);
								customStorage.addToStorage('city', data.results.userDet.city);
								customStorage.addToStorage('isLoggedIn',true);
								var isVndr = data.results.userDet.is_vendor;
								var isComp = data.results.userDet.is_complete;
								if(isVndr == 0 || isVndr == '0')
								{
									isVndr = -1;
								}
                                else if(isVndr == 1 || isVndr == '1')
                                {
                                    customStorage.addToStorage('isComp', data.results.userDet.isComp);
                                    customStorage.addToStorage('busiType', data.results.userDet.busiType);
                                }
								customStorage.addToStorage('is_vendor',isVndr);
					
								var uid = customStorage.addToStorage('userid', data.results.userid);
							}
							else
							{
								customStorage.addToStorage('l', mobile);
								customStorage.addToStorage('mobile', mobile);
								customStorage.addToStorage('username', name);
								customStorage.addToStorage('name', name);
								customStorage.addToStorage('email', email);
								customStorage.addToStorage('isLoggedIn',true);
								var isVndr = data.results.userDet.is_vendor;
								if(isVndr == 0 || isVndr == '0')
								{
									isVndr = -1;
								}
								
								customStorage.addToStorage('is_vendor',isVndr);
					
								var uid = customStorage.addToStorage('userid', data.results.userid);
							}
							common.checkLogin();
							var obj = funcObj;
							
							$('#otpDiv').velocity({scale: 0}, {delay: 0, ease: 'swing'});
							$('#overlay1').velocity({opacity: 0}, {delay: 100, ease: 'swing'});
							setTimeout(function () {
							$('#overlay1,#otpDiv').addClass('dn');
							$("#otpDiv,#overlay1").remove();
							}, 1010);
                                                        
							if((obj !== undefined && $(obj).hasClass('iconWishlist')) || isWishList)
							{
								addToWishList();
							}

							if((obj !== undefined && $(obj).hasClass('iconMessage')) || isMail)
							{
								sendDetailsToUser();
								addToEnquiry();
							}
							else
							{
								var bottomPos = $('.prdMainDetails').position().top+$('.prdMainDetails').outerHeight(true)
								var pos=bottomPos - 65;

								setTimeout(function(){
									$('#vDetails').removeClass('vTransit');
									$('#vDetails').removeClass('dn');
									$('body').animate({scrollTop: pos}, 300);
								},10);

								setTimeout(function () {
									var mpscrpt = document.createElement("script");
									mpscrpt.type = "text/javascript";
									mpscrpt.src = "http://maps.google.com/maps/api/js";
									$("head").append(mpscrpt);
								}, 100);

								setTimeout(function () {
									initMap(vndrLat*1,vndrLng*1,vndrFullAddr);
								},250);
								addToEnquiry();
							}
						//}
					}
				}
			});
                }
                else if(errCode === 'Otp validation failed')
                {
                    isValid = false;
                    customStorage.toast(0,'OTP verification Unsuccessful');
                    requestOTP(pr_mobile);
                }
        }
        });
        return isValid;
}
function otpGo(pr_mobile)
{
        var pr_mobile = $('#ur_mobile').val().trim();
        var otpValue = $("#pr_otp").val().trim();

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
        $('#overlay1').velocity({opacity: 0}, {delay: 100, ease: 'swing'});
        setTimeout(function () {
            $('#overlay1,#otpDiv').addClass('dn');
            $("#otpDiv,#overlay1").remove();
        }, 1010);
}


