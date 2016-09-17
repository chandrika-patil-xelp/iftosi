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
var submiter = true;
var funcObj = '';
$(document).ready(function(){
     setTimeout(function() {
        var samt = 100;
        if (isMobile) {
            samt = 450;
        }
      //  $('body').animate({scrollTop: samt}, 300);
    }, 100);

    /* $("#gallery1").bind("click", function(){
        var img=$(this).css("background-image");
        $("#prdImage").css({'background':"url("+img+") 50% 50% / cover no-repeat scroll padding-box border-box rgb(255, 255, 255);"});
    });

    $('.imgThumbnil').click(function(){
        var img=$(this).css('background-image');
        $('.galleryImg').css({'background':"url("+img+") 50% 50% / cover no-repeat scroll padding-box border-box rgb(255, 255, 255);"});
    });

    $('.imgPreview').click(function(){
        var img=$(this).css('background-image');
        $('.galleryImg').css({'background':"url("+img+") 50% 50% / cover no-repeat scroll padding-box border-box rgb(255, 255, 255);"});
    }); */

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

		if(isLoggedIn == undefined || isLoggedIn == null || isLoggedIn == '' || isLoggedIn == false || isLoggedIn == 'false')
		{
			mobile = customStorage.readFromStorage('mobile');
			name = customStorage.readFromStorage('name');
			email = customStorage.readFromStorage('email');
			uid = customStorage.readFromStorage('userid');
			islog = customStorage.readFromStorage('isLoggedIn');

			if(uid == '' || uid == null || uid == undefined || islog == false || islog == '' || islog == null || islog == undefined)
			{
                                if($(this).hasClass('iconCall'))
                                {
                                    $('.lgTitle').html('Submit details to get Vendor Details');
                                }
                                else if($(this).hasClass('iconMessage'))
                                {
                                    $('.lgTitle').html('Submit details to get SMS & EMAIL');
                                }
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
//			if(isVendor == 2 || isVendor == '2')
//			{
//				customStorage.toast(0, 'This feature is not available for Admin');
//			}
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

	$('#userSubmit').bind('click',function()
        {
            var ur_name = $('#ur_name').val();
            var ur_mobile = $('#ur_mobile').val();
            var ur_city = $('#ur_city').val();
            var ur_cityid = $('#ur_cityid').val();
            var ur_email = $('#ur_email').val();

            if(ur_mobile=='' || ur_mobile.length!=10 || isNaN(ur_mobile))
            {
                customStorage.toast(0,'Invalid Format for Mobile');
                $('#ur_mobile').focus();
                return false;
            }
            else if(ur_name.length==0 || isNaN(ur_name)!==true)
            {
                customStorage.toast(0,'Invalid Format for Name');
                $('#ur_name').focus();
                return false;
            }
            else if(ur_city=='')
            {
                customStorage.toast(0,'City is required');
                $('#ur_city').focus();
                return false;
            }
            else if(ur_cityid=='')
            {
                customStorage.toast(0,'Please select the city from list');
                $('#ur_city').focus();
                return false;
            }
            else if(ur_email=='')
            {
                customStorage.toast(0,'Email is Required!');
                $('#ur_email').focus();
                return false;
            }
            else if(!common.validateEmail('ur_email'))
            {
                customStorage.toast(0,'Email is Not Valid!');
                $('#ur_email').focus();
                return false;
            }
            else
            {
                if(submiter == true)
                {
                    submiter = false;
                                if(isMail)
                                {
                                        var isLoggedIn = customStorage.readFromStorage('isLoggedIn');
                                        var mobile = customStorage.readFromStorage('mobile');
                                        if(isLoggedIn == '' || isLoggedIn == null || isLoggedIn == undefined || isLoggedIn == false || isLoggedIn == 'false')
                                        {
                                                //customStorage.addToStorage('mobile',ur_mobile);
                                                //var pr_mobile = customStorage.readFromStorage('mobile');
                                                otpGo();

                                        }
                                        else if(mobile !== ur_mobile)
                                        {
                                            otpGo();
                                        }
                                        else
                                        {
                                            var tmstmp = new Date().getTime();
                                            $.ajax({url: DOMAIN + "apis/index.php?action=getOwnerCheck&uid="+uid+"&pid="+pid+"timestamp="+tmstmp, success: function(result)
                                                {
                                                    var obj = eval('('+result+')');
                                                    var eCode = obj.error.code;
                                                    if(eCode == 1 || eCode == '1')
                                                    {
                                                        customStorage.toast(0,'You can not have information of your own product');
                                                    }
                                                    else
                                                    {
                                                        sendDetailsToUser();
                                                        addToEnquiry();
                                                    }
                                                }
                                            });

                                        }
                                }
                                else
                                {
                                        showVendorDetails();
                                }

                    $('#userForm').velocity({scale:0},{delay:0,ease:'swing'});
                    $('#overlay').velocity({opacity:0},{delay:100,ease:'swing'});
                    setTimeout(function(){
                            $('#overlay, #userForm').addClass('dn');
                            submiter = true;
                    },1010);
                    return true;
                }
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

	getImagesData(prdList);

        if(similarPrdList !== undefined && similarPrdList !== 'undefined' && similarPrdList !== null && similarPrdList !== '')
        {
            getImagesData(similarPrdList,true);
        }
});


function getImagesData(prdList,simtrue)
{
	if(prdList !== undefined && prdList !== null && prdList !== '' && typeof prdList !== 'undefined' && prdList !== 'undefined' && prdList !== 'null')
	{
		var params 	= 'action=ajx&case=getImages&prdIds=' + encodeURIComponent(prdList) + '&td=' + new Date();
		var URL 	= DOMAIN + "index.php";
		$.getJSON(URL, params, function(data)
		{

			if(data !== undefined && data !== null && data !== '' && data !== 'undefined' && data !== 'null'&& typeof data !== 'undefined')
			{
                                if(simtrue == true)
                                {
                                    showSimillarImages(data);
                                }
                                else
                                {
                                    showImages(data);
                                }
			}
		});
	}
}

function showImages(data)
{
	var vid = customStorage.readFromStorage('userid');
	var newVid = '';
	var imgArr = new Array();
	var k = 0;
	if(data.error.code == 0)
	{
		$.each(data.results, function(i, val) {
			$.each(val.images, function(j, vl){
				if(vl.active_flag === '0' || vl.active_flag === 0)
				{
					if(val.vid == vid)
					{
						imgArr[k] = vl.image;
					}
				}
				else
				{
					imgArr[k] = vl.image;
				}
				k++;
			});

			var imgHtml = getDtlsImageData(imgArr);
			$('#detailsImgs').html(imgHtml);
			imgArr = new Array();
			k = 0;
		});

		$('#gallery1 .thumbnil').click(function(){
                        $(this).addClass("borderColor");
                        $(this).prevAll().removeClass("borderColor");
                         $(this).nextAll().removeClass("borderColor");
			var imgvl=$(this).css('background-image');
			$('#prdImage').css({'background-image': imgvl});
		});

		$('.imgThumbnil').click(function(){
			var imgvl=$(this).css('background-image');
			$('.galleryImg').css({'background-image': imgvl});
		});

		$('.imgPreview').click(function(){
			var imgvl=$(this).css('background-image');
			$('.galleryImg').css({'background-image': imgvl});
		});

		$('#galleryClose').click(function(){
		   $('#imgGallery').addClass('dn');
		});

		$('#prdImage').click(function(){
		   $('#imgGallery').removeClass('dn');
		});
	}
}


function showSimillarImages(data)
{
    	var vid = customStorage.readFromStorage('userid');
	var newVid = '';
	var imgArr = new Array();
	var k = 0;
	if(data.error.code == 0)
	{
		$.each(data.results, function(i, val) {
			$.each(val.images, function(j, vl){
        if(vl.active_flag === '0' || vl.active_flag === 0)
				{
					if(val.vid == vid)
					{
						imgArr[k] = vl.image;
					}
				}
				else
				{
					imgArr[k] = vl.image;
				}
				k++;
      });
                        var a = i;
			var imgHtml = getSimilarDtlsImageData(imgArr);
			$('#'+a).html(imgHtml);
			imgArr = new Array();
			k = 0;
		});
	}
}

function getSimilarDtlsImageData(imgData)
{
		var imgLen = 0;
		var tmpHtml = "<div class='for-1 noImage'></div>";
		if(imgData !== undefined && imgData !== null && imgData !== '')
		{
			imgLen = imgData.length;
			if(imgLen > 12)
			{
				imgLen = 12;
			}

			switch(imgLen)
			{
				case 0:
					tmpHtml = "<div class='for-1 noImage'></div>";
				break;
				case 1:
				case 2:
				case 3:
				case 4:
				case 6:
				case 8:
				case 9:
				case 10:
				case 12:
					tmpHtml = '';
					for(imgi = 0; imgi < imgLen; imgi++)
					{
						tmpHtml += "<div class='prox for-"+imgLen+"' onmouseover='showMouseOver(this);' style='background: #ccc url(" + IMGDOMAIN + imgData[imgi] + ") no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div>";
					}
					tmpHtml += "<div class='proxImg fLeft' style='background: #fff url(" + IMGDOMAIN + imgData[0] + ")no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div>";
				break;
				case 5:
					tmpHtml = "<div class='prox for-"+imgLen+"Left'>";
					for(imgi = 0; imgi < imgLen; imgi++)
					{
						if(imgi == imgLen - 1)
						{
							tmpHtml += "</div>";
							tmpHtml += "<div class='prox for-"+imgLen+"Right'><div onmouseover='showMouseOver(this);' class='prox for-"+imgLen+"' style='background: #ccc url(" + IMGDOMAIN + imgData[imgi] + ")no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div></div>";
						}
						else
						{
							tmpHtml += "<div class='prox for-"+imgLen+"' onmouseover='showMouseOver(this);' style='background: #ccc url(" + IMGDOMAIN + imgData[imgi] + ")no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div>";
						}
					}
					tmpHtml += "<div class='proxImg fLeft' style='background: #fff url(" + IMGDOMAIN + imgData[0] + ")no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div>";
				break;
				case 7:
					tmpHtml = "";
					var curi = 0;
					for(imgi = 0; imgi < imgLen; imgi++)
					{
						if(imgi == 0)
						{
							tmpHtml += "<div class='for-" + imgLen + "Upper'><div class='prox for-" + imgLen + "' onmouseover='showMouseOver(this);' style='background: #ccc url(" + IMGDOMAIN + imgData[imgi] + ")no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div></div>";
						}
						else
						{
							if(curi == 0)
							{
								tmpHtml += "<div class='for-" + imgLen + "Lower'>";
							}
							tmpHtml += "<div class='prox for-" + imgLen + "' onmouseover='showMouseOver(this);' style='background: #fff url(" + IMGDOMAIN + imgData[imgi] + ")no-repeat;background-size: contain;background-position:center;background-color:#FFF;'></div>";

							if(curi == imgLen - 2)
							{
								tmpHtml += "</div>";
							}

							curi++;
						}
					}
					tmpHtml += "<div class='proxImg fLeft' style='background: #fff url(" + IMGDOMAIN + imgData[0] + ")no-repeat;background-size:contain;background-position:center;background-color:#FFF;'></div>";
				break;
				case 11:
					var curi = 0;
					tmpHtml = "<div class='prox for-" + imgLen + "Left'>";

					for(imgi = 0; imgi < imgLen; imgi++)
					{
						if(curi == 0)
						{
							if(imgi < 3)
							{
								tmpHtml += "<div class='prox for-"+imgLen+"' onmouseover='showMouseOver(this);' style='background: #fff url(" + IMGDOMAIN + imgData[imgi] + ")no-repeat;background-size:contain;background-position:center;background-color:#FFF;'></div>";

								if(imgi == 2)
								{
									curi++;
								}
							}
						}
						else
						{
							if(imgi == 3)
							{
								tmpHtml += "</div>";
								tmpHtml += "<div class='prox for-"+imgLen+"Right'>";
							}

							tmpHtml += "<div class='prox for-"+imgLen+"' onmouseover='showMouseOver(this);' style='background: #fff url(" + IMGDOMAIN + imgData[imgi] + ")no-repeat;background-size:contain;background-position:center;background-color:#FFF;'></div>";

							if(imgi == imgLen - 1)
							{
								tmpHtml += "</div>";
							}
						}
					}
					tmpHtml += "<div class='proxImg fLeft' style='background: #fff url(" + IMGDOMAIN + imgData[0] + ")no-repeat;background-size:contain;background-position:center;background-color:#FFF;'></div>";
				break;
			}
		}
	return tmpHtml;
}

function showMouseOver(obj)
{
	var tmpClass = $(obj).attr('class');
	tmpClass = tmpClass.split(' ');
	var actClassNm = 'for-1';
	for(var i = 0 ; i < tmpClass.length; i++)
	{
		if(tmpClass[i].indexOf('for-') !== -1)
		{
			actClassNm = tmpClass[i];
			break;
		}
	}

	var classInd = actClassNm.split('-');
	classInd = classInd[1];

	var img=$(obj).css('background');
	if(classInd == 1 || classInd == 2 || classInd == 3 || classInd == 4 || classInd == 6 || classInd == 8 || classInd == 9 || classInd == 10 || classInd == 12)
	{
		$(obj).siblings('.proxImg').css({'background':img});
	}
	else
	{
		$(obj).parent().siblings('.proxImg').css({'background':img});
	}
}






function getDtlsImageData(imgData)
{
	var imgLen = 0;
	var tmpHtml = "<div class='for-1 noImage'></div>";
	if(imgData !== undefined && imgData !== null && imgData !== '')
	{
		if(imgData.length > 0)
		{
			tmpHtml = '<div class="leftArrow transition300" onclick="movePrImg(true);"></div>';
			tmpHtml += '<div class="rightArrow  transition300" onclick="movePrImg(false);"></div>';
			tmpHtml += '<div id="prdImage" class="forImg fLeft imgPreview" style="background: url(\''+IMGDOMAIN + imgData[0]+'\') 50% 50% / cover no-repeat scroll padding-box border-box rgb(255, 255, 255);"></div>';
			tmpHtml += '<div id="gallery1" class="gallery fLeft transition300">';
            tmpHtml += '<div class="carousel transition300">';
			for(var imgi = 0; imgi < imgData.length; imgi++)
			{
				tmpHtml += '<div class="thumbnil transition300" style="background:#fff url(\''+IMGDOMAIN+imgData[imgi]+'\')no-repeat;background-size: contain;background-position: center;">';
				tmpHtml += '</div>';
			}
			tmpHtml += '</div>';
            tmpHtml += '</div>';
		}
	}
	return tmpHtml;
}

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
			//common.checkMobile('ur_mobile');
			//customStorage.addToStorage('mobile',mobile);
			//pr_mobile = customStorage.readFromStorage('mobile');
			otpGo();
		}

	}
	else
	{
		if(isWishList==true)
		{
                    var tmstmp = new Date().getTime();
                    $.ajax({url: DOMAIN + "apis/index.php?action=getOwnerCheck&uid="+uid+"&pid="+pid+"&timestamp="+tmstmp, success: function(result)
                        {
                            var obj = eval('('+result+')');
                            var eCode = obj.error.code;
                            if(eCode == 1 || eCode == '1')
                            {
                                customStorage.toast(0,'You can not have information of your own product');
                            }
                            else
                            {
                                addToWishList();
                            }
                        }
                    });

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
                    var tmstmp = new Date().getTime();
                        $.ajax({url: DOMAIN + "apis/index.php?action=getOwnerCheck&uid="+uid+"&pid="+pid+"&tmstmp="+tmstmp, success: function(result)
                        {
                            var obj = eval('('+result+')');
                            var eCode = obj.error.code;
                            if(eCode == 1 || eCode == '1')
                            {
                                customStorage.toast(0,'You can not have information of your own product');
                            }
                            else
                            {
                                var bottomPos = $('.prdMainDetails').position().top+$('.prdMainDetails').outerHeight(true);
                                var pos=bottomPos - 65;

                                setTimeout(function(){
                                        $('#vDetails').removeClass('vTransit');
                                        $('#vDetails').removeClass('dn');
                                        $('html,body').animate({scrollTop: pos}, 150);
                                        if($("head").html().indexOf('http://maps.google.com/maps/api/js') === -1)
                                        {
                                                //setTimeout(function () {
                                                        var mpscrpt = document.createElement("script");
                                                        mpscrpt.type = "text/javascript";
                                                        mpscrpt.src = "http://maps.google.com/maps/api/js";
                                                        $("head").append(mpscrpt);

                                                        setTimeout(function () {
                                                                initMap(vndrLat*1,vndrLng*1,vndrFullAddr);
                                                        }, 220);
                                                //}, 220);
                                        }
                                }, 20);

                                addToEnquiry();
                            }
                        }
                    });
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
            var tmstmp = new Date().getTime();
            var params = 'action=ajx&case=addToEnquiry&uid='+userid+'&vid='+vendor_id+'&pid='+pid+'&timestamp='+tmstmp;
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
                    var tmstmp = new Date().getTime();
			var params = 'action=ajx&case=addToWishList&userid='+userid+'&vid='+vendor_id+'&prdid='+pid+'&timestamp='+tmstmp;
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
//$('#lgSubmit, #lgCancel').bind('click', function () {
//    if(this.id=='lgCancel') {
//        closeAllForms();
//    }
//});

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
            var tmstmp = new Date().getTime();
		var params = 'action=ajx&case=checkWish&userid='+userid+'&vid='+vendor_id+'&prdid='+pid+'&timestamp='+tmstmp;
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
            var tmstmp = new Date().getTime();
		var params = 'action=ajx&case=getUserDet&uid='+userid+'&timestamp='+tmstmp;
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
        var isVendor = customStorage.readFromStorage('is_vendor');
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

    if(isVendor !== 2 && isVendor !== '2')
    {
	if(isValidName && isValidMobile && isValidEmail)
	{
            var tmstmp = new Date().getTime();
		var params = 'action=ajx&case=sendDetailsToUser&usrName='+encodeURIComponent(usrName)+'&usrMobile='+encodeURIComponent(usrMobile)+'&usrEmail='+encodeURIComponent(usrEmail)+'&prdid='+encodeURIComponent(pid)+'&timestamp='+tmstmp;
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
    else
    {
        customStorage.toast(1, 'Details can not be sent to Admin');
    }
}


function requestOTP()
{
    setTimeout(function () {
        $('#overlay1').removeClass('dn');
        $('#otpDiv').removeClass('dn');
        $('#overlay1').velocity({opacity: 1}, {delay: 0, duration: 50, ease: 'swing'});
        $('#optDiv').velocity({scale: 1}, {delay: 80, duration: 100, ease: 'swing'});
    }, 500);
}
function otpCheck()
{
   var otpProvided =  $('#pr_otp').val();
   var obj = funcObj;
   if(otpProvided !== undefined && otpProvided !== 'undefined' && otpProvided !== null && otpProvided !== 'null' && otpProvided !== '' && isNaN(otpProvided) == false )
   {
            var mobile = $('#ur_mobile').val();
            var isValid = true;

            //var loggedin = customStorage.readfromStorage(isLoggedIn);
            var tmstmp = new Date().getTime();
           $.ajax({url: DOMAIN + "apis/index.php?action=validOTP&mobile="+mobile+"&vc="+otpProvided+"&timestamp="+tmstmp, success: function(result)
               {
                        var obj = jQuery.parseJSON(result);
                        var errCode = obj['error']['Msg'];

                        if(errCode == 'Data matched')
                        {
                                isValid = true;
                                var mobile = $('#ur_mobile').val().trim();
                                var name = $('#ur_name').val().trim();
                                var email = $('#ur_email').val().trim();
                                var city = $('#ur_city').val().trim();
                                var tmstmp = new Date().getTime();
                                var params = 'action=ajx&case=userCheck&ur_city='+encodeURIComponent(city)+'&mobile='+mobile+'&name='+encodeURIComponent(name)+'&email='+encodeURIComponent(email)+'&pid='+pid+"&timestamp="+tmstmp;
                                var URL = DOMAIN + "index.php";

                                $.getJSON(URL, params, function(data) {
                                        if(data !== null && data !== undefined && data !== '')
                                        {
                                                if((data.error !== '' && data.error !== null && data.error !== undefined && data.error.Code == 0) || (data.error.Code == 1 && data.results.userid != ''))
                                                {
                                                                if(data.error.Msg === 'Data matched')
                                                                {
                                                                        var userDt = null;
                                                                        var custDt = null;
                                                                        for(var i = 0; i < data.results.userDet.length; i++)
                                                                        {
                                                                            if(data.results.userDet[i].logmobile == mobile)
                                                                            {
                                                                                if(data.results.userDet[i].is_vendor.toString() == '1')
                                                                                {
                                                                                    userDt = data.results.userDet[i];
                                                                                    break;
                                                                                }
                                                                                if(data.results.userDet[i].is_vendor.toString() == '0')
                                                                                {
                                                                                    custDt = data.results.userDet[i];
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }
                                                                        if(userDt !== null && userDt !== 'null' && userDt !== undefined)
                                                                        {
                                                                                customStorage.addToStorage('userid', userDt.user_id);
                                                                                customStorage.addToStorage('l', userDt.logmobile);
                                                                                customStorage.addToStorage('mobile', userDt.logmobile);
                                                                                customStorage.addToStorage('username', userDt.user_name);
                                                                                customStorage.addToStorage('name', userDt.user_name);
                                                                                customStorage.addToStorage('email', userDt.email);
                                                                                customStorage.addToStorage('city', userDt.city);
                                                                                customStorage.addToStorage('isLoggedIn',true);
                                                                                customStorage.addToStorage('busiType', userDt.busiType);
                                                                                var isVndr = userDt.is_vendor;
                                                                                var isComp = userDt.isComp;
                                                                                customStorage.addToStorage('isComp', isComp);
                                                                                customStorage.addToStorage('is_vendor',isVndr);
                                                                        }
                                                                        else
                                                                        {
                                                                            if(custDt == null || custDt == 'null')
                                                                            {
                                                                                customStorage.toast(0,data.error.type.flagMsg);
                                                                                setTimeout(function()
                                                                                    {
                                                                                        closeOtpForm();
                                                                                    },2000);
                                                                                return false;
                                                                            }
                                                                            else if(custDt.logmobile == mobile)
                                                                            {
                                                                                customStorage.addToStorage('userid', custDt.user_id);
                                                                                customStorage.addToStorage('l', custDt.logmobile);
                                                                                customStorage.addToStorage('mobile', custDt.logmobile);
                                                                                customStorage.addToStorage('username',custDt.user_name);
                                                                                customStorage.addToStorage('name',custDt.user_name);
                                                                                customStorage.addToStorage('email',custDt.user_name);
                                                                                customStorage.addToStorage('city',custDt.user_name);
                                                                                customStorage.addToStorage('isLoggedIn',true);
                                                                                var isVndr = custDt.is_vendor;
                                                                                if(isVndr == 0 || isVndr == '0')
                                                                                {
                                                                                    isVndr = -1;
                                                                                    customStorage.addToStorage('is_vendor',isVndr);
                                                                                }
                                                                            }

                                                                        }
                                                                }
                                                                common.checkLogin();
                                                                closeOtpForm();

                                                                if((obj !== undefined && $(obj).hasClass('iconWishlist')) || isWishList)
                                                                {
                                                                        addToWishList();
                                                                }

                                                                if((obj !== undefined && $(obj).hasClass('iconMessage')) || isMail)
                                                                {
                                                                        if(data.results.userDet.vtype == 'same')
                                                                        {
                                                                            customStorage.toast(0,'You can not have information of your own product');
                                                                            $('.prdBtns').addClass('dn');
                                                                        }
                                                                        else
                                                                        {
                                                                            sendDetailsToUser();
                                                                            addToEnquiry();
                                                                        }
                                                                }
                                                                else
                                                                {
                                                                        var bottomPos = $('.prdMainDetails').position().top+$('.prdMainDetails').outerHeight(true)
                                                                        var pos=bottomPos - 65;

                                                                        setTimeout(function(){
                                                                                $('#vDetails').removeClass('vTransit');
                                                                                $('#vDetails').removeClass('dn');
                                                                                $('body').animate({scrollTop: pos}, 150);
                                                                                if($("head").html().indexOf('http://maps.google.com/maps/api/js') === -1)
                                                                                {
                                                                                        //setTimeout(function () {
                                                                                                        var mpscrpt = document.createElement("script");
                                                                                                        mpscrpt.type = "text/javascript";
                                                                                                        mpscrpt.src = "http://maps.google.com/maps/api/js";
                                                                                                        $("head").append(mpscrpt);
                                                                                                        setTimeout(function () {
                                                                                                                initMap(vndrLat*1,vndrLng*1,vndrFullAddr);
                                                                                                        }, 220);
                                                                                        //}, 220);
                                                                                }
                                                                        }, 20);




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
                            requestOTP();
                        }
                }

            });
    }
    else
    {
        isValid = false;
        customStorage.toast(0,'OTP verification Unsuccessful');
        requestOTP();
    }
        return isValid;
}
function otpGo()
{
    var pr_mobile = $('#ur_mobile').val();
    var tmstmp = new Date().getTime();
    $.ajax({url: DOMAIN + "apis/index.php?action=sendOTP&mb="+pr_mobile+"&timestamp="+tmstmp, success: function(result)
            {
                var obj = jQuery.parseJSON(result);

                var errCode = obj.code;

                if(errCode == 1)
                {
                    customStorage.toast(1,'OTP is sent to your mobile number');
                    requestOTP();
                }
                else
                {
                    customStorage.toast(0,'OTP sending failed');
                }
            }
        });
}

function closeOtpForm()
{

        if(pageName == 'signup' || pageName == 'forgot')
        {
            window.history.back();
        }
        else
        {

//            $('#overlay1').velocity({opacity: 0}, {delay: 100, ease: 'swing'});
//            $('#otpDiv').velocity({scale: 0}, {delay: 0, ease: 'swing'});
            setTimeout(function () {
                $('#overlay1').velocity({opacity: 0}, {delay: 100, duration: 300, ease: 'swing'});
                $('#optDiv').velocity({scale: 0}, {delay: 0, duration: 100, ease: 'swing'});
                $('#overlay1,#otpDiv').addClass('dn');
                //$("#otpDiv,#overlay1").remove();
            }, 10);

            isValid = true;
        }
}

$(document).ready(function(){
    var userid =  customStorage.readFromStorage('userid');
    var tmstmp = new Date().getTime();
    var params 	= '?action=getOwnerCheck&pid=' + pid + '&uid=' +userid+'&timestamp='+tmstmp;
    var URL 	= DOMAIN + "apis/index.php";
    $.ajax({url: URL+params, success: function(result)
    {
        var obj = jQuery.parseJSON(result);

        if(result !== undefined && result !== null && result !== '' && result !== 'undefined' && result !== 'null'&& typeof result !== 'undefined')
        {
                if(obj.error.code == 0 || obj.error.code === '0')
                {
                    $('.prdBtns').removeClass('dn');
                }
                else
                {
                    $('.prdBtns').addClass('dn');
                }
        }
    }});
});
