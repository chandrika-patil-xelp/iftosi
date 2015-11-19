var loadDiamont = false;
var loadJewel = false;
var loadBullion = false;

var diamondPage = 1;
var jewellPage = 1;
var bullionPage = 1;
//$.ajax({url: common.APIWebPath() + "index.php?action=viewAll&uid="+uid, success: function (result) {
//    var obj = jQuery.parseJSON(result);
//    var busiType = obj['results'][1]['business_type'];
    if (/1/.test(busiType)) {
        $('#dmdTab').removeClass('dn');
        loadDiamont = true;
    }
    if (/2/.test(busiType)) {
        $('#jewTab').removeClass('dn');
        loadJewel = true;
    }
    if (/3/.test(busiType)) {
        $('#bullTab').removeClass('dn');
        loadBullion = true;
    }
    if (/1/.test(busiType) && catid==10000) {
        $('#diamondPrds').removeClass('dn');
        loadDiamonds();
    }
    else if (/2/.test(busiType) && catid==10001) {
        loadJewels();
        $('#jewelleryPrds').removeClass('dn');
    }
    else if (/3/.test(busiType) && catid==10002) {
        $('#bullionPrds').removeClass('dn');
        loadBullions();
    }
//}});

$(window).scroll(function () {
    if ($(window).scrollTop() == ($(document).height() - $(window).height())) {
        if (catid == 10000 && loadDiamont) {
            loadDiamonds();
        }
        if (catid == 10001 && loadJewel) {
            loadJewels();
        }
        if (catid == 10002 && loadBullion) {
            loadBullions();
        }
    }
});
function loadDiamonds() {
    $.ajax({url: common.APIWebPath() + "index.php?action=getVproducts&vid=" + uid + "&page=" + diamondPage + "&limit=15&catid=10000", success: function (result) {
            loadDiamondCallback(result);
        }});
}
function loadDiamondCallback(res) {
    var obj = jQuery.parseJSON(res);
    if (obj['results'] != '') {
        var total = obj['results']['total_products'];
        $('#totalDiamonds').text(total);
        var total_pages = obj['results']['total_pages'];
        
        var str = '';
        if(total!=0) {
            if(total_pages==diamondPage) {
                loadDiamont = false;
            }
            var len = obj['results']['products'].length;
            var i = 0;
            while (i < len) {
                str += generateDiamondList(obj['results']['products'][i]);
                i++;
            }
            diamondPage++;
        } else {
            str = '<p class="noRecords"><span>Sorry! No Products Found!</span></p>';
            loadDiamont = false;
        }
        $('#DiamondsList').append(str);
    } else {
        loadDiamont = false;
    }
}
function generateDiamondList(obj) {
    var pro_name = obj['product_name'];
    if(pro_name == null) {
        pro_name = '';
    }
    var date = obj['update_time'].split(' ');
    var str = '<li>';
    str += '<div class="date fLeft"> ';
    str += '<span class="upSpan">' + date[0] + '</span>';
    str += '<span class="lwSpan">' + date[1] + '</span>';
    str += '</div>';
    str += '<div class="barcode fLeft">';
    str += '<span class="upSpan">' + obj['barcode'] + '</span>';
    str += '<span class="lwSpan"><a href="'+ DOMAIN + pro_name.replace(" ", "+").toLowerCase() +'/did-'+ obj['id'] +'">View Details</a></span>';
    str += '</div>';
    str += '<div class="shape fLeft">' + obj['shape'] + '</div>';
    str += '<div class="color fLeft">' + obj['color'] + '</div>';
    str += '<div class="carats fLeft">' + obj['carat'] + '</div>';
    str += '<div class="clarity fLeft">' + obj['clarity'] + '</div>';
    str += '<div class="cert fLeft">' + obj['cert'] + '</div>';
    str += '<div class="price fLeft fmOpenB">&#8377;' + obj['price'] + '</div>';
    str += '<div class="acct fLeft">';
    str += '<center>';
    str += '<div class="deltBtn poR ripplelink" onclick="deleteProduct(' + obj['id'] + ',this)"></div>';
    str += '<a href="'+ DOMAIN +'index.php?case=diamond_Form&catid=10000&prdid='+ obj['id'] +'"><div class="editBtn poR ripplelink"></div></a>';
    str += '<div class="soldBtn poR ripplelink fmOpenR">SOLD</div>';
    str += '</center>';
    str += '</div>';
    str += '</li>';
    str += '';
    return str;
}


function loadJewels() {
    $.ajax({url: common.APIWebPath() + "index.php?action=getVproducts&vid=" + uid + "&page=" + jewellPage + "&limit=15&catid=10001", success: function (result) {
            loadJewellCallback(result);
        }});
}
function loadJewellCallback(res) {
    var obj = jQuery.parseJSON(res);
    if (obj['results'] != '') {
        var total = obj['results']['total_products'];
        $('#totalJewells').text(total);
        var total_pages = obj['results']['total_pages'];
            
        var str = '';
        if(total!=0) {
            if(total_pages==jewellPage) {
                loadJewel = false;
            }
            var len = obj['results']['products'].length;
            var i = 0;
            while (i < len) {
                str += generateJewellList(obj['results']['products'][i]);
                i++;
            }
            jewellPage++;
        } else {
            str = '<p class="noRecords"><span>Sorry! No Products Found!</span></p>';
            loadJewel = false;
        }
        $('#JewellsList').append(str);
    } else {
        loadJewel = false;
    }
}
function generateJewellList(obj) {
    var pro_name = obj['product_name'];
    if(pro_name == null) {
        pro_name = '';
    }
    var date = obj['update_time'].split(' ');
    var str = '<li>';
    str += '<div class="date fLeft"> ';
    str += '<span class="upSpan">' + date[0] + '</span>';
    str += '<span class="lwSpan">'+ date[1] +'</span>';
    str += '</div>';
    str += '<div class="barcode fLeft">';
    str += '<span class="upSpan">' + obj['barcode'] + '</span>';
    str += '<span class="lwSpan"><a href="'+ DOMAIN + pro_name.replace(" ", "+").toLowerCase() +'/jid-'+ obj['id'] +'">View Details</a></span>';
    str += '</div>';
    str += '<div class="degno fLeft">' + obj['lotref'] + '</div>';
    str += '<div class="metal fLeft">' + obj['metal'].split('~')[0] + '</div>';
    str += '<div class="catg fLeft">' + obj['category'][1]['cat_name'] + '</div>';
    str += '<div class="subType fLeft">' + obj['category'][0]['cat_name'] + '</div>';
    str += '<div class="price fLeft fmOpenB">&#8377;' + obj['price'] + '</div>';
    str += '<div class="acct fLeft">';
    str += '<center>';
    str += '<div class="deltBtn poR ripplelink" onclick="deleteProduct(' + obj['id'] + ',this)"></div>';
    str += '<a href="'+ DOMAIN +'index.php?case=jewellery_Form&catid=10001&prdid='+ obj['id'] +'"><div class="editBtn poR ripplelink"></div></a>';
    str += '<div class="soldBtn poR ripplelink fmOpenR">SOLD</div>';
    str += '</center>';
    str += '</div>';
    str += '</li>';
    str += '';
    return str;
}

function loadBullions() {
    $.ajax({url: common.APIWebPath() + "index.php?action=getVproducts&vid=" + uid + "&page=" + bullionPage + "&limit=15&catid=10002", success: function (result) {
            loadBullionsCallback(result);
        }});
}
function loadBullionsCallback(res) {
    var obj = jQuery.parseJSON(res);
    if (obj['results'] != '') {
        var total = obj['results']['total_products'];
        $('#totalBullions').text(total);
        var total_pages = obj['results']['total_pages'];
        
        var str = '';
        if(total!=0) {
            if(total_pages==bullionPage) {
                loadBullion = false;
            }
            var len = obj['results']['products'].length;
            var i = 0;
            while (i < len) {
                str += generatBullionsList(obj['results']['products'][i]);
                i++;
            }
            bullionPage++;
        } else {
            str = '<p class="noRecords"><span>Sorry! No Products Found!</span></p>';
            loadBullion = false;
        }
        $('#BullionsList').append(str);
    } else {
        loadBullion = false;
    }
}
function generatBullionsList(obj) {
    var pro_name = obj['product_name'];
    if(pro_name == null) {
        pro_name = '';
    }
    var date = obj['update_time'].split(' ');
    var str = '<li>';
    str += '<div class="date fLeft"> ';
    str += '<span class="upSpan">' + date[0] + '</span>';
    str += '<span class="lwSpan">'+ date[1] +'</span>';
    str += '</div>';
    str += '<div class="barcode fLeft">';
    str += '<span class="upSpan">' + obj['barcode'] + '</span>';
    str += '<span class="lwSpan"><a href="'+ DOMAIN + pro_name.replace(" ", "+").toLowerCase() +'/bid-'+ obj['id'] +'">View Details</a></span>';
    str += '</div>';
    str += '<div class="btype fLeft">' + obj['type'] + '</div>';
    str += '<div class="metal fLeft">' + obj['metal'].split('~')[0] + '</div>';
    str += '<div class="purity fLeft">' + obj['gold_purity'] + '</div>';
    str += '<div class="weight fLeft">' + obj['gold_weight'] + '</div>';
    str += '<div class="price fLeft fmOpenB">&#8377;' + obj['price'] + '</div>';
    str += '<div class="acct fLeft">';
    str += '<center>';
    str += '<div class="deltBtn poR ripplelink" onclick="deleteProduct(' + obj['id'] + ',this)"></div>';
    str += '<a href="'+ DOMAIN +'index.php?case=bullion_Form&catid=10002&prdid='+ obj['id'] +'"><div class="editBtn poR ripplelink"></div></a>';
    str += '<div class="soldBtn poR ripplelink fmOpenR">SOLD</div>';
    str += '</center>';
    str += '</div>';
    str += '</li>';
    str += '';
    return str;
}
var catName='';
function deleteProduct(proId,ele) {
    $.ajax({url: common.APIWebPath() + "index.php?action=vDeletePrd&vid=" + uid + "&prdid=" + proId, success: function (result) {
        var obj = jQuery.parseJSON(result);
        if(obj['error']['Code']==0) {
            $(ele).parent().parent().parent().remove();
            if(catid==10000) {
                catName='Diamonds';
            } else if(catid==10001) {
                catName='Jewells';
            } else if(catid==10002) {
                catName='Bullions';
            }
            var total=$('#total'+catName).text();
            total--;
            $('#total'+catName).text(total);
            console.log(total);
            console.log(catName);
            if(total==0) {
                loadBullion = false;
            }
            var count = $("#"+catName+"List li").length;
            if(count<15) {
                $("#"+catName+"List").html();
                if(catid==10000) {
                    diamondPage = 1;
                    loadDiamonds();
                } else if(catid==10001) {
                    jewellPage = 1;
                    loadJewels();
                } else if(catid==10002) {
                    bullionPage = 1;
                    loadBullions();
                }
            }
            console.log("#"+catName+"List");
            common.toast(1,obj['error']['Msg']);
        } else {
            common.toast(0,obj['error']['Msg']);
        }
    }});
}