var isOpen = false;
var lastSc=0;
var pw=$(window).width();
var ph=$(window).height();
var isMobile=false;
var input_selector = 'input[type=text], input[type=password], input[type=email], input[type=url], input[type=tel], input[type=number], input[type=search], textarea, input[type=radio]';
if(pw<768){
    isMobile=true; 
}

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
    
    $('#overlay').velocity({opacity:0},{delay:0,duration:0});
    $('#userForm').velocity({scale:0},{delay:0,duration:0});
    $('.iconCall,.iconMessage').click(function(){
        $('#overlay,#userForm').removeClass('dn');
        setTimeout(function(){
            $('#overlay').velocity({opacity:1},{delay:0,duration:300,ease:'swing'});
            $('#userForm').velocity({scale:1},{delay:80,duration:100,ease:'swing'});
        },10);
    });
    
    $('#userCancel').bind('click',function(){
        $('#userForm').velocity({scale:0},{delay:0,ease:'swing'});
        $('#overlay').velocity({opacity:0},{delay:100,ease:'swing'});
        setTimeout(function(){
            $('#overlay,#userForm').addClass('dn');
        },1010);
    }); 
	
	$('#userSubmit').bind('click',function() {
		showVendorDetails();
		$('#userForm').velocity({scale:0},{delay:0,ease:'swing'});
		$('#overlay').velocity({opacity:0},{delay:100,ease:'swing'});
		setTimeout(function(){
			$('#overlay,#userForm').addClass('dn');
		},1010);
	});
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

function showVendorDetails(){
	
	var params = 'action=ajx&case=vendor&productid='+pid;
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
					setTimeout(function () {
						initMap(dt.latitude*1,dt.longitude*1,dt.fulladdress);
					},1000);
				}
				j++;
			});
		}
	});
    var pos=$('.prdInfo').offset().top-100;
    setTimeout(function(){
        $('#vDetails').removeClass('vTransit');
		$('#vDetails').removeClass('dn');
        $('body').animate({scrollTop: pos}, 300);
    },50);
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