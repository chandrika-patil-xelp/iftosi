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

var validMob = true;
$('#ur_mobile').keyup(function () {
    var mobile = $(this).val();
    if(mobile.length==10) {
        $.ajax({url: DOMAIN + "apis/index.php?action=checkUser&mobile=" + mobile, success: function (result) {
            var obj = jQuery.parseJSON(result);
            var errCode = obj['error']['Code'];
            if(errCode == 1) {
                validMob = false;
                customStorage.toast(0,'This Mobile Number Already Registered!'); 
            } else {
                validMob = true;
            }
        }});
    }
});

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
		if(isVendor !== '1' && isVendor !== 1)
		{
			mobile = customStorage.readFromStorage('mobile');
			name = customStorage.readFromStorage('name');
			email = customStorage.readFromStorage('email');
			uid = customStorage.readFromStorage('userid');
			if(uid == '' || uid == null || uid == undefined)
			{
                            if($(this).hasClass('iconWishlist'))
                            {
                                isWishList = true;
                            }
                            else
                            {
                                isWishList = false;
                            }
                            common.showLoginForm(1);
                        }
			else
			{
				if($(this).hasClass('iconWishlist'))
				{
					isWishList = true;
				}
				else
				{
					isWishList = false;
				}
				showVendorDetails(this);
			}
		}
		else
		{
			customStorage.toast(0, 'This feature is not available for vendors');
		}
    });
    $('#overlay').velocity({opacity:0},{delay:0,duration:0});
    $('#userForm').velocity({scale:0},{delay:0,duration:0});
    $('.iconCall,.iconMessage').click(function(){
		var isVendor = customStorage.readFromStorage('is_vendor');
		if(isVendor !== '1' && isVendor !== 1)
		{
			mobile = customStorage.readFromStorage('mobile');
			name = customStorage.readFromStorage('name');
			email = customStorage.readFromStorage('email');
			uid = customStorage.readFromStorage('userid');
			islog = customStorage.readFromStorage('isLoggedIn');
			if(uid == '' || uid == null || uid == undefined || islog == false)
			{
				$('#overlay,#userForm').removeClass('dn');
				setTimeout(function(){
					$('#overlay').velocity({opacity:1},{delay:0,duration:300,ease:'swing'});
					$('#userForm').velocity({scale:1},{delay:80,duration:100,ease:'swing'});
				},10); 
				isWishList = false;
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
			customStorage.toast(0, 'This feature is not available for vendors');
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
        if(!validMob) {
            customStorage.toast(0,'This Mobile Number Already Registered!'); 
            $('#ur_mobile').focus();
            return false;
        }
        else if(ur_mobile=='' || ur_mobile.length!=10 || isNaN(ur_mobile)) {
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
            showVendorDetails();
            $('#userForm').velocity({scale:0},{delay:0,ease:'swing'});
            $('#overlay').velocity({opacity:0},{delay:100,ease:'swing'});
            setTimeout(function(){
                    $('#overlay,#userForm').addClass('dn');
            },1010);
            return true;
        }
	});
        
        function eSubmit(evt, btnId) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode === 13) {
            $('#' + btnId).click();
        }
    };

	if(mobile !== '' && mobile !== null && mobile !== undefined && mobile !== 'null' && mobile !== 'undefined' && typeof mobile !== 'undefined')
	{
		$('#ur_mobile').val(mobile);
		changeStyle('mobile');
	}
	if(name !== '' && name !== null && name !== undefined && name !== 'null' && name !== 'undefined' && typeof name !== 'undefined')
	{
		$('#ur_name').val(name);
		changeStyle('name');
	}
	if(email !== '' && email !== null && email !== undefined && email !== 'null' && email !== 'undefined' && typeof email !== 'undefined')
	{
		$('#ur_email').val(email);
		changeStyle('email');
	}

	if(pageName == 'diamond_details' || pageName == 'bullion_details' || pageName == 'jewellery_details')
	{
		getWishList();
	}
});


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
        
	if(uid == '' || uid == null || uid == undefined)
	{
            
		var mobile = $('#ur_mobile').val();
		var name = $('#ur_name').val();
		var email = $('#ur_email').val();
		var mobCond = (mobile !== '' && mobile !== null && mobile !== undefined) ? true : false;
		var nmCond = (name !== '' && name !== null && name !== undefined) ? true : false;
		var emCond = (email !== '' && email !== null && email !== undefined) ? true : false;
                

		if(mobCond && nmCond && emCond)
		{
                    
			customStorage.addToStorage('l', mobile);
			customStorage.addToStorage('username', name);
			customStorage.addToStorage('email', email);
			customStorage.addToStorage('isLoggedIn',true);
			customStorage.addToStorage('is_vendor','0');
			common.checkLogin();

			var params = 'action=ajx&case=userCheck&mobile='+mobile+'&name='+encodeURIComponent(name)+'&email='+encodeURIComponent(email);
			var URL = DOMAIN + "index.php";
                        
			$.getJSON(URL, params, function(data) {
                          if(data !== null && data !== undefined && data !== '') {
                             
					if(data.error !== '' && data.error !== null && data.error !== undefined && data.error.code == 0)
					{       
						var uid = customStorage.addToStorage('userid', data.userid);
						if((obj !== undefined && $(obj).hasClass('iconWishlist')) || isWishList)
						{
							addToWishList();
						}

						if((obj !== undefined && $(obj).hasClass('iconMessage')) || isMail)
						{
							sendDetailsToUser();
						}
						else
						{
							setTimeout(function () {
								initMap(vndrLat*1,vndrLng*1,vndrFullAddr);
							},100);
							var pos=$('.wrapper').height()-100;
							setTimeout(function(){
								$('#vDetails').removeClass('vTransit');
								$('#vDetails').removeClass('dn');
								$('body').animate({scrollTop: pos}, 300);
							},200);
							addToEnquiry();
						}
					}
				}
			});
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
			//getUserDetails();
		}
		else if((isMail==false)&&(isWishList==false))
		{
			setTimeout(function () {
				initMap(vndrLat*1,vndrLng*1,vndrFullAddr);
			},100);
			//var pos=$('.wrapper').offset().height+50;
                        var pos=$('.wrapper').height()-50;
                        //console.log(pos);
                        
			setTimeout(function(){
				$('#vDetails').removeClass('vTransit');
				$('#vDetails').removeClass('dn');
				$('body').animate({scrollTop: pos}, 300);
			},200);
			addToEnquiry();                        
		}
	}

	/*var params = 'action=ajx&case=vendor&productid='+pid;
	var URL = DOMAIN + "index.php";
	$.getJSON(URL, params, function(data) {
		if(data.results.vendor_details) {
			var vhtml = '';
			var j=0;
			$.each(data.results.vendor_details, function(i, dt) {
				if(j==0) {
					var add = dt.fulladdress.split(',');
					vhtml += '<div class="wrapperMax ">';
						vhtml += '<div class="vdLeftCard fLeft">';
							vhtml += '<div class="orgName fLeft fmOpenR">'+dt.OrganisationName+'</div>';
							vhtml += '<div class="orgAdd fLeft fmOpenR">';
								for(var i=0;i<add.length;i++)
								{
									if(i == (add.length - 1))
										vhtml += add[i];
									else
										vhtml += add[i]+',<br>';
								}
							vhtml += '</div>';
							vhtml += '<div class="orgNo fLeft">';
								vhtml += '<div class="infoLabel fmOpenB font12 fmOpenB fLeft">Telephone No.</div>';
								vhtml += '<div class="commtxt tel fmOpenR fLeft">'+replaceAll('~',', ',dt.telephones)+'</div>';
							vhtml += '</div>';
							vhtml += '<div class="orgNo fLeft">';
								vhtml += '<div class="infoLabel fmOpenB font12 fmOpenB fLeft">Email Address</div>';
								vhtml += '<div class="commtxt email fmOpenR fLeft">'+replaceAll('~',', ',dt.alt_email)+'</div>';
							vhtml += '</div>';
							vhtml += '<div class="orgNo fLeft">';
								vhtml += '<div class="infoLabel fmOpenB font12 fmOpenB fLeft">Website</div>';
								vhtml += '<div class="commtxt email fmOpenR fLeft"><a href="#">'+dt.website+'</a></div>';
							vhtml += '</div>';
							vhtml += '<div class="orgNo fLeft">';
								vhtml += '<div class="commLeft fLeft">';
									vhtml += '<div class="infoLabel fmOpenB font12 fmOpenB fLeft">VAT Number</div>';
									vhtml += '<div class="commtxt email fmOpenR fLeft">'+dt.Vat_Number+'</div>';
								vhtml += '</div>';
								vhtml += '<div class="commRight fLeft">';
									vhtml += '<div class="infoLabel fmOpenB font12 fmOpenB fLeft">Turnover (cr)</div>';
									vhtml += '<div class="commtxt email fmOpenR fLeft">'+dt.turnover+'</div>';
								vhtml += '</div>';
							vhtml += '</div>';

							vhtml += '<div class="orgNo fLeft">';
								vhtml += '<div class="infoLabel fmOpenB font12 fmOpenB fLeft">Bankers</div>';
								vhtml += '<div class="commtxt bankers fmOpenR fLeft">'+replaceAll('~',', ',dt.bankers)+'</div>';
							vhtml += '</div>';
						vhtml += '</div>';
						vhtml += '<div class="vdCenterCard fLeft">';
							vhtml += '<div class="cntCard fLeft">';
								vhtml += '<div class="vTitle fLeft fmOpenR" style="background-color: #5e0037;">Contact Person</div>';
								vhtml += '<div class="cardCommCont"><div class="cardComm cntName orgName fLeft fmOpenR">'+dt.contact_person+'</div>';
									vhtml += '<div class="fmOpenB desig">('+dt.position+')</div>';
									vhtml += '<div class="cardComm cntMb fmOpenB fLeft">'+dt.contact_mobile+'</div>';
									vhtml += '<div class="cardComm cntEmail fmOpenB fLeft">'+dt.email+'</div>';
								vhtml += '</div>';
							vhtml += '</div>';
							vhtml += '<div class="cntCard fLeft">';
								vhtml += '<div class="vTitle fLeft fmOpenR" style="background-color: #380B34;">Bharat Diamond Bourse Certificate</div>';
								vhtml += '<div class="cardCommCont">';
									vhtml += '<div class="cardComm fLeft fmOpenR">'+dt.bharat_Diamond_Bource_Certificate+'</div>';
								vhtml += '</div>';
							vhtml += '</div>';

							vhtml += '<div class="cntCard fLeft">';
								vhtml += '<div class="vTitle fLeft fmOpenR" style="background-color: #2A0E34;">GJEPC Membership Certificate</div>';
								vhtml += '<div class="cardCommCont">';
									vhtml += '<div class="cardComm fLeft fmOpenR">'+dt.Membership_Certificate+'</div>';
								vhtml += '</div>';
							vhtml += '</div>';
							vhtml += '<div class="cntCard fLeft">';
								vhtml += '<div class="vTitle fLeft fmOpenR" style="background-color: #171334;">Membership Of Other Diamond Bourses</div>';
								vhtml += '<div class="cardCommCont">';
									vhtml += '<div class="cardComm fLeft fmOpenR">'+replaceAll('~',', ',dt.membership_of_other_diamond_bourses_around_world)+'</div>';
								vhtml += '</div>';
							vhtml += '</div>';
						vhtml += '</div>';
						vhtml += '<div  class="vdRightCard fLeft">';
							vhtml += '<div class="cntCard fLeft shadoNone">';
								vhtml += '<div class="vTitle fLeft fmOpenR" style="background-color: #ebebde;color:#666;">Office Locations</div>';
								vhtml += '<div class="cardCommCont" style="padding: 10px;"><div id="googleMap" class="mapCont fLeft"></div></div>';
							vhtml += '</div>';
							
							vhtml += '<div class="cntCard fLeft shadoNone">';
								vhtml += '<div class="vTitle fLeft fmOpenR" style="background-color: #ebebde;color:#666;">Gallery</div>';
								vhtml += '<div class="cardCommCont" style="padding: 10px;">';
									vhtml += '<div class="gallery fLeft">';
										vhtml += '<div class="thumbnil fLeft" style="background:#fff url(http://localhost/iftosi/tools/img/common/tumb5.jpg)no-repeat;background-size: contain;background-position: center;"></div><div class="thumbnil fLeft" style="background:#fff url(http://localhost/iftosi/tools/img/common/tumb2.png)no-repeat;background-size: contain;background-position: center;"></div>';
										vhtml += '<div class="thumbnil fLeft" style="background:#fff url(http://localhost/iftosi/tools/img/common/tumb3.jpg)no-repeat;background-size: contain;background-position: center;"></div>';
										vhtml += '<div id="moreimg" class="thumbnil fLeft poR" style="background:#8A0044"><span>+5 More</span></div>';
									vhtml += '</div>';
								vhtml += '</div>';
							vhtml += '</div>';
						vhtml += '</div>';
					vhtml += '</div>';
					
					$('#vDetails').html(vhtml);
					
				}
				j++;
			});
		}
	});*/
}
function initMap(lat,lng,contentString) {
	var myLatLng = {lat: lat, lng: lng};
	var infowindow = new google.maps.InfoWindow();

	var map = new google.maps.Map(document.getElementById('googleMap'), {
		zoom: 16,
		center: myLatLng
	});

	var marker = new google.maps.Marker({
		position: myLatLng,
		map: map,
		title: 'Hello World!'
	});
	
	google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(contentString);
        infowindow.open(map, marker);
    });
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
        else
        {
            showVendorDetails($obj);
        }
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
						customStorage.toast(1,'Added to wishlist');
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
function changeStyle(fr)
{
	if(fr == 'all')
	{
		$('#ur_mobile').addClass('mobileIcon');
		$('#ur_name').addClass('nameIcon');
		$('#ur_email').addClass('emailIcon');

		$('#ur_mobile').addClass('brOrange');
		$('#ur_mobile').addClass('brGreen');
		$('#ur_mobile').siblings('label').addClass('labelActive');

		$('#ur_name').addClass('brOrange');
		$('#ur_name').addClass('brGreen');
		$('#ur_name').siblings('label').addClass('labelActive');

		$('#ur_email').addClass('brOrange');
		$('#ur_email').addClass('brGreen');
		$('#ur_email').siblings('label').addClass('labelActive');
	}
	else if (fr == 'mobile')
	{
		$('#ur_mobile').addClass('mobileIcon');
		$('#ur_mobile').addClass('brOrange');
		$('#ur_mobile').addClass('brGreen');
		$('#ur_mobile').siblings('label').addClass('labelActive');
	}
	else if(fr == 'name')
	{
		$('#ur_name').addClass('nameIcon');
		$('#ur_name').addClass('brOrange');
		$('#ur_name').addClass('brGreen');
		$('#ur_name').siblings('label').addClass('labelActive');
	}
	else if(fr == 'email')
	{
		$('#ur_email').addClass('emailIcon');
		$('#ur_email').addClass('brOrange');
		$('#ur_email').addClass('brGreen');
		$('#ur_email').siblings('label').addClass('labelActive');
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
			
		});
	}
}

$('.wishProduct').bind('click',function(){
    console.log('wishProduct');
});

$('.msgProduct').bind('click',function(){
    console.log('msgProduct');
});

$('.callProduct').bind('click',function(){
    console.log('callProduct');
});

$('.viewProduct').bind('click',function(){
    console.log('viewProduct');
});