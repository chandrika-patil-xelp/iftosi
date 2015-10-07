var min_price = "700.00";
var max_price = "9999.99";


$(document).ready(function(){
    
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

        }
    });


    $("#caratsRange").ionRangeSlider({
        type: "double",
        grid: true,
        min: 0.3,
        max: 20.11,
        from: 05,
        drag_interval: true,
        prefix: "carat: ",
        postfix: " ",
        decorate_both: false,
        force_edges: true,
        step: 0.01,
        onFinish: function(data) {

        }
    });
    
    /*Mobile Filter Btns*/
    $('#mfFooter').bind('click', function() {
        if (!isMobile) {
            alert('reset filter');
        } else {
            alert('apply filter');
        }
    });

    
});


function searchArea(val) {
    $('#asug').removeClass('dn');
}

function setData(obj) {
    var text = $(obj).text();
    $('#txtArea').val(text);
    $('#asug').addClass('dn');

}

var switchFlag = true;
function showSideNav(flag) {
    if (flag) {
        $('#filters').removeClass('transit-100X');
        switchFlag = false;
    } else {
        $('#filters').addClass('transit-100X');
        switchFlag = true;
    }
}


function swichFilter() {
    showSideNav(switchFlag);
}


var rings=new Array("Office Wear Rings","Daily Wear Rings","Party Wear Rings","Band Rings","Only Diamonds Rings","Cocktails Rings","White Gold Rings","Men's Rings");
var earrings=new Array("Office Wear Earrings","Daily Wear Earrings","Party Wear Earrings","Only Diamonds Earrings","Gemstone Earrings","White Gold Earrings");
var pendant=new Array("Office Wear Pendants","Daily Wear Pendants","Party Wear Pendants","Only Diamonds Pendants","Gemstone Pendants","White Gold Pendants");
var necklace=new Array("Office Wear Necklace","Party Wear Necklace");
var bracelet=new Array("Office Wear","Party Wear","Party Wear","Only Diamonds","Gemstone","White Gold");

var addedFilters=new Array();
function addFiltters(type){
    var name="";
    var useArray;
    switch(type){
        case 'Rings':
            name='Rings';
            useArray=rings;
            addedFilters.push('Rings');
        break;
        case 'Earrings':
            name='Earrings';
            useArray=earrings;
            addedFilters.push('Earrings');
        break;
        case 'Pendant':
            name='Pendant';
            useArray=pendant;
            addedFilters.push('Pendant');
        break;
        case 'Necklace':
            name='Necklace';
            useArray=necklace;
            addedFilters.push('Necklace');
        break;
        case 'Bracelet':
            name='Bracelet';
            useArray=bracelet;
            addedFilters.push('Bracelet');
        break;
    }
    
    
    
    var len=useArray.length;
    var str="";
    str+="<div id='"+name+"_Filters' class='filterCont fLeft'>";
    str+="<div class='fLeft optionTitle fmOpenR'>"+name+"</div>";
    str+="<div id='cut' class='filterOptions fLeft fmOpenR'>";            

    for(var i=0;i<len;i++){
        str+="<div class='checkDiv fLeft'>";
        str+="<input type='checkbox' class='filled-in' id='"+useArray[i].split(' ').join('_')+"'/>";
        str+="<label for='"+useArray[i].split(' ').join('_')+"'>"+useArray[i]+"</label>";
        str+="</div>";
    }
    str+="</div>";
    str+="</div>";
    
    $('#filters').prepend(str);
    var ht=$('#filters').height()+25;
    $('.results_Filter').height(ht+"px");
}       


function removeFilters(id){
    $('#'+id).remove();
    $('#filters,.results_Filter').css({height:''});
    var ht=$('#filters').height()+25;
    $('.results_Filter').height(ht+"px");
}