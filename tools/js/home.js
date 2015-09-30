var scene;
var parallax;
var lHt = $('#logoCont').height();
var tLFlag = true;
var doScroll = true;

function closeFilters() {
    $('#logoLoader').removeClass('dn');
    setTimeout(function() {
        $('#selFilters,#results').removeClass('filterTransit dn');
       // Materialize.showProducts('results');
        resultLoaded();
    }, 300);
}

function resultLoaded() {
    $('#logoLoader').addClass('dn');
}

function showLoader() {
    $('#dataLoader').removeClass('dn');
    scrollOPe(true);
}

function hideLoader() {
    $('#dataLoader').addClass('dn');
    scrollOPe(false);
}

function expandSelection(id) {
    if (pw < 960) {
        parallax.reset();
        var backFlag = $('#' + id + ' .backBtn').hasClass('dn');
        if (backFlag) {
            $('.whyCont,.wTabsContainer').addClass('dn');
            tLFlag = true;
            $('#logoCont,#scene').addClass('transition300');
            $('#scene .backBtn').removeClass('dn');
            $('#logo').addClass('h50');
            if (id === 'prdC1') {
                $('#prdC2,#prdC3').addClass('prdTransit');
                setTimeout(function() {
                    $('#prdC1').addClass('mSelected');
                    $('#scene').addClass('p0');
                }, 200);
            }
            else if (id === 'prdC2') {
                $('#prdC1,#prdC3').addClass('prdTransit');
                setTimeout(function() {
                    $('#prdC2').addClass('mSelected');
                    $('#scene').addClass('p0');
                }, 200);
            }
            else if (id === 'prdC3') {
                $('#prdC2,#prdC1').addClass('prdTransit');
                setTimeout(function() {
                    $('#prdC3').addClass('mSelected');
                    $('#scene').addClass('p0');
                }, 200);
            }
            $('#prdC4').removeClass('dn');
            setTimeout(function() {
                $('#prdC4').removeClass('op0');
                $('#scene .backBtn').removeClass('backTransit');
                $('#dDrop2').click();
            }, 500);
        } else {
        }

    } else {

        //tLFlag=false;
        $('#logoCont,#scene').addClass('transition300');
        $('.prdComm').addClass('bgNone');
        $('#scene').addClass('op0');
        $('#prdC4').removeClass('dn');
        setTimeout(function() {
            $('#scene').addClass('dn');
            $('#prdC4').removeClass('op0');
        }, 300);

        if (id === 'prdC1') {
            $('#selectedType').html('Diamonds');
        }
        else if (id === 'prdC2') {
            $('#selectedType').html('Jewellery');
        }
        else if (id === 'prdC3') {
            $('#selectedType').html('Bullion');
        }
        parallax.disable();
    }
}

function mback(obj, id) {
    var backFlag = $('.baseContainer').hasClass('baseTransit1');//After city selection back
    if (backFlag) {
        $('.logoTop').addClass('logoTransit1');
        // $('#mFilterBtn').addClass('dn');
        $('#logo').removeClass('h50');
        setTimeout(function() {
            $('.baseContainer').removeClass('baseTransit1');
            //$('.logoTop').addClass('dn');
            $('#logo').removeClass('op0');
            $('#logoCont').removeClass('pB0');
            tLFlag = true;
        }, 300);
        mbackComm(id);
    } else {//Normal Flow
        mbackComm(id);
    }
}


function mbackComm(id) {
    $('.forName').text('');
    $('#citySelect').val('');
    $('#dDrop2').text('Select A City');
    tLFlag = true;
    $('#scene .backBtn').addClass('backTransit');
    $('#scene').removeClass('p0');
    $('#logo').removeClass('h50');
    $('#prdC4').addClass('op0');
    setTimeout(function() {
        $('#' + id).removeClass('mSelected citySelected');
        setTimeout(function() {
            $('#scene .backBtn').addClass('dn ');
            $('#prdC1,#prdC2,#prdC3').removeClass('prdTransit');
            $('.whyCont,.wTabsContainer').removeClass('dn');
            $('#prdC4').addClass('dn');
        }, 150);
    }, 300);

}



function back() {
    var flag = $('#prdC4').hasClass('pTop75');
    if (!flag) {
        tLFlag = true;
        $('#prdC4').addClass('op0');
        $('.prdComm').removeClass('bgNone');

        setTimeout(function() {
            $('#scene').removeClass('dn');
            setTimeout(function() {
                $('#prdC4').addClass('dn');
                // $('#logoCont,#scene').removeClass('transition300');
                $('#scene').removeClass('op0');
            }, 100);
        }, 300);
    }
    else {
        $('#prdC4').addClass('op0');
        $('.prdComm').removeClass('bgNone');
        $('.baseContainer').removeClass('baseTransit');
        $('#logo').removeClass('h0');
        $('.logoTop').addClass('logoTransit1');

        setTimeout(function() {
            $('#scene').removeClass('dn');
            $('#prdC4').addClass('dn');
            $('#prdC4').removeClass('pTop75');
            $('#citySelect').val('');
            setTimeout(function() {
                $('#prdC4').addClass('dn');
                // $('#logoCont,#scene').removeClass('transition300');
                $('#scene').removeClass('op0');
                // $('.logoTop').addClass('dn');
            }, 100);
        }, 300);
        doScroll = true;
        tLFlag = true;
    }
    parallax.enable();
}

var pw = $(window).width();
var ph = $(window).height();
//$(document).ready(function() {
$(document).ready(function() {
    scroll0();
    $('#prdC4').focusin(function() {
        $('#cityCont').addClass('onFocus');
    });
    $('#prdC4').focusout(function() {
        $('#cityCont').removeClass('onFocus');
    });

    //$("#priceRange").ionRangeSlider({type: "double", grid: true, min: "35000", max: "3500000", from: "50000", to: "1200000", drag_interval: true, prefix: "INR: ", postfix: " ", decorate_both: false, prettify_separator: ",", values_separator: " to ", force_edges: true});
    //$("#carats").ionRangeSlider({grid: true, min: "0.1", max: "20", from: "1", to: "3", step: 0.01, drag_interval: true, prefix: "carat: ", postfix: " ", decorate_both: false, prettify_separator: ",", values_separator: " to ", force_edges: true});


    scene = document.getElementById('scene');
    parallax = new Parallax(scene);
    if (pw < 960) {
        //parallax.disable();
        //$('.logoTop').removeClass('dn logoTransit1');
    }


    $('#dDrop1').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrain_width: true, // Does not change width of dropdown to that of the activator
        //hover: true, // Activate on hover
        gutter: 0, // Spacing from edge
        belowOrigin: false // Displays dropdown below the button
    });

    $('#dDrop2').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrain_width: true, // Does not change width of dropdown to that of the activator
        //hover: true, // Activate on hover
        gutter: 0, // Spacing from edge
        belowOrigin: false // Displays dropdown below the button
    });



    var position = $(window).scrollTop();
    var upcount = 0;
    $(window).scroll(function() {
        removeStyle();
        var isTouch = isTouchDevice();
        if (!isTouch) {
            if (doScroll) {
                $('#logoCont,#scene').removeClass('transition300');
                var scroll = $(window).scrollTop();
                if (scroll > position) {
                    upcount -= 3;
                    if ((upcount >= -480) && (upcount <= 0)) {
                        $('#logoCont').velocity({translateY: "" + upcount + "px"}, {duration: 100, easing: "linear"});
                    }
                }
                else if (scroll < position) {
                    upcount += 3;
                    if (upcount <= 0) {
                        //console.log("up "+upcount);
                        $("#logoCont,#scene").velocity("stop", true);
                        $('#logoCont').velocity({translateY: "" + upcount + "px"}, {duration: 100, easing: "linear"});
                    }
                }
                if (scroll == 0) {
                    upcount = 0;
                    $("#logoCont,#scene").velocity("stop", true);
                    $('#logoCont').velocity({translateY: "" + upcount + "px"}, {duration: 100, easing: "linear"});
                }
                position = scroll;
            }
        }
        if (tLFlag) {
            var scroll = $(window).scrollTop();
            if (scroll >= (lHt - 100)) {
                //var lFlag= $('.logoTop').hasClass('dn');
                var lFlag = $('.logoTop').hasClass('logoTransit1');
                if (lFlag) {
                    $('.logoTop').removeClass('logoTransit1');
                    $('#logoCont').addClass('transition300');
                    $('#logoCont').addClass('op0');
                }
            } else if (scroll <= (lHt - 100)) {
                var lFlag = $('.logoTop').hasClass('logoTransit1');
                if (!lFlag) {
                    $('.logoTop').addClass('logoTransit1');
                    setTimeout(function() {
                        $('#logoCont').removeClass('op0');
                        setTimeout(function() {
                            $('#logoCont').removeClass('transition300');
                        }, 200);
                    }, 100);
                }
            }
        }
    });
    $(".button-collapse").sideNav();
    $(".side-nav").removeClass("dn");
    //load click

    $('.shpfltr').click(function(event) {
        var optnarr = '';
        $('.shpfltr').each(function() {
            //if($(this).attr('checked') == 'true')
            if ($(this).is(':checked'))
            {
                if (optnarr == '')
                {
                    optnarr += $(this).attr('value');
                }
                else
                {
                    optnarr += ',' + $(this).attr('value');
                }
            }
        });
    });


    $("#priceRange").ionRangeSlider({
        type: "double",
        grid: true,
        min: min_price,
        max: max_price,
        from: min_price,
        to: max_price,
        prefix: "INR: ",
        postfix: " ",
        decorate_both: false,
        prettify_separator: ",",
        force_edges: true,
        drag_interval: true,
        onFinish: function(data) {
            getData('price', min_carats, max_carats, data.from, data.to, cladata, coldata);
        }
    });

    $("#carats").ionRangeSlider({
        type: "single",
        grid: true,
        min: min_carats,
        max: max_carats,
        from: min_carats,
        drag_interval: true,
        prefix: "carat: ",
        postfix: " ",
        decorate_both: false,
        force_edges: true,
        step: 0.01,
        onFinish: function(data) {
            getData('carats', data.from, data.to, min_price, max_price, cladata, coldata);
        }
    });

    setTimeout(function() {
        removeStyle();
    }, 1500);

});

function isTouchDevice() {
    return typeof window.ontouchstart !== 'undefined';
}

function selectThis(obj, id) {
    var val = $(obj).text();
    //alert(val);
    $('#' + id).text(val);

    if (id == 'dDrop2') {
        getData();
        $('.logoTop').removeClass('dn');
        $('#logo').addClass('op0');
        $('#logoCont').addClass('pB0');
        $('.mSelected').addClass('citySelected');
        $('.mSelected .forName').text(' in ' + val.ucwords());
        $('#prdC4').addClass('dn');
        $('#content,#results').removeClass('dn');
        Materialize.showProducts('results');
        setTimeout(function() {
            $('.baseContainer').addClass('baseTransit1');
            $('#logo').addClass('h50');
            $('.logoTop').removeClass('logoTransit1');
            scroll0();
            tLFlag = false;
        }, 100);
    }
}

var styled = 0;
function removeStyle() {
    if (styled < 5) {
        $('#caratsDiv .irs-bar').css({background: 'none', border: 'none'});
        $('#caratsDiv .irs-bar-edge').css({background: 'none', border: 'none'});
        styled++;
    }

}

function scrollOPe(flag) {
    if (flag) {//open
        $('body').css({overflow: 'hidden'});
    } else {//close
        $('body').css({overflow: ''});
    }
}

function sortBy(type) {
    //alert(type);
}

function showFilters() {
    var showFlag = $('#fcCont').hasClass('h0');
    if (!showFlag) {
        var ht = $('#fcCont').height();
        $('#fcCont').height(ht + "px");
        setTimeout(function() {
            $('#fcCont').addClass('h0');
            scroll0();
        }, 10);
    } else {
        $('#fcCont').removeClass('h0');
        $('#results').addClass('dn');
    }
}

var cnt = 4;
function loadProducts() {
    var str = "<div id='" + cnt + "' class='temp fLeft' style='opacity:0'>asdfdsfsdfsd sdf sdf sdf sdf sdgrghetgh eg et eg erg erg egeasdfdsfsdfsd sdf sdf sdf sdf sdgrghetgh eg et eg erg erg egeasdfdsfsdfsd sdf sdf sdf sdf sdgrghetgh eg et eg erg erg ege</div>";
    $('#results').append(str);
    //Materialize.showProducts('results');
    setTimeout(function() {
        $('#' + cnt).velocity({opacity: "0", translateX: "150%"}, {duration: 10, delay: 0});
        setTimeout(function() {
            $('#' + cnt).velocity({opacity: "1", translateX: "0"}, {duration: 800, delay: 0, easing: [60, 10]});
            cnt++;
            console.log(cnt);
        }, 100);
    }, 100);

}
var int;
setTimeout(function() {
    //alert('Product Add Start');
    //int=setInterval(function(){loadProducts();},1200);
}, 10000);
String.prototype.ucwords = function() {
    str = this.toLowerCase();
    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
            function($1) {
                return $1.toUpperCase();
            });
};

function scroll0() {
    $('body').animate({scrollTop: 0}, 300, 'linear');
}

function stopScrollEffect() {
    tLFlag = false;
    parallax.reset();
    //activateMobileCity();
}

function activateMobileCity() {
    $('#mcityCont').addClass('cityActive');
    ph = ph + 40;
    $('#mcityCont').height(ph + 'px');
    $('#logoCont').css({zIndex: 100});
    //$('body').height(ph+'px');
    scrollOPe(true);
}

function refresh() {
    //alert('Refresh filters');
    showFilters();
    $('#results').removeClass('dn');

}