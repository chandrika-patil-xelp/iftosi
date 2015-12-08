
 
function submitForm(formid)
{
    var optionValue=new Array();
    values= new Array();
    var shapearr = '';
    var attr = new Array();
        var reslt = new Array();
    if(typeof $('.shapeSelected').attr('id') !== 'undefined'){
        values[0]='shape|@|'+$('.shapeSelected').attr('id');
    }
    
    if(formid=='dAddForm'){
    y=$('#dAddForm').serializeArray();
    }
    else if(formid=='jwAddForm'){
        y=$('#jwAddForm').serializeArray();
    }
    else if(formid=='bAddForm'){
        y=$('#bAddForm').serializeArray();
    }
    $('input[type=checkbox]:checked').each(function(){
       optionValue.push(($(this).attr('value'))); 
    });
    
   // var i=1;
    $(y).each(function(i,val){
        if(val.value)
        {  
            if(val[(y[i].name)]!=='subcat_type')
            {  
               val[(y[i].name)]=(y[i].value);
               values[i+1] = val.name+'|@|'+val.value;
            }
                
        }
    });
    var cnt = values.length;
    values[cnt+1]='subcatid'+'|@|'+optionValue.toString();
    
    data = values.join('|~|');
    //var JsonString = JSON.stringify(values);
    if((pid !== null)&&(pid !== undefined)&&(pid !== '') && pid !== 'undefined' && typeof pid !== 'undefined')
    {
    var params = 'action=addNewproduct&category_id='+catid+'&dt='+data+'&prdid='+pid+'&vid='+uid;
    }
    else
    {
    var params = 'action=addNewproduct&category_id='+catid+'&dt='+data+'&vid='+uid;
    }
    var URL   = DOMAIN+"apis/index.php";
    $.getJSON(URL, params, function(data) {
        window.location.href = IMGUPLOAD+'pid-'+data.results.pid+'&c='+catid;
    });
    
}

 function validateJForm(){
            var metal = $("input[name='metal']:checked").val();
            var color =  $('input[name=color]:checked').val();
            var certificate =  $('input[name=Certficate]:checked').val();
            var clarity = $("input[name='clarity']:checked").val();
            var subcat = $("input[type='checkbox']").is(':checked');
            var purity = $('#goldpurity').val();
            var dweight = $('#diamondweight').val();
            var goldweight=$('#goldweight').val();
            var barcode=$('#barcode').val();
            var no_diamonds=$('#no_diamonds').val();
            var gemweight=$('#gemweight').val();
            var prdprice=$('#prdprice').val();
            var othercert=$('#other_cerificate').val();
            var shape=$('.jshapeComm');
            var str = '';
            var isValid = true;
            
           if(!shape.hasClass('shapeSelected')) {
                str ='category is not Selected';
                isValid = false;
            }
            if(isValid && subcat<=false){
                str ='Checkbox button is empty';
                isValid = false;
            }
            if((certificate=='' || certificate==null || certificate==undefined) && isValid) {
                str ='Certificate field is Empty';
                isValid = false;
            }
            if(certificate == 'Other' && isValid)
            {
                if(isValid && (othercert == undefined || othercert == 'undefined' || othercert == null || othercert == 'null' || othercert == '' ))
                {
                    str ='Please mention the certificate in other field';
                    isValid = false;
                    $('#other_cerificate').focus();
                }
            }
            if(isValid && (metal=='' || metal==null || metal==undefined)) {
                str ='Metal field is Empty';
                isValid = false;
            }
            
            if(isValid && (color !== undefined && color !== 'undefined' && color !== 'null' && color !== ''))
            {
                if(isValid && (color == undefined || color == 'undefined' || color == 'null' || color == ''))
                {
                    str ='Diamond Color field is Empty';
                    isValid = false;
                }
                else if(isValid && (clarity == undefined || clarity == 'undefined' || clarity == 'null' || clarity == ''))
                {
                    str ='Diamond Quality field is Empty';
                    isValid = false;
                }
                else if(isValid && (no_diamonds == undefined || no_diamonds == 'undefined' || no_diamonds == 'null' || no_diamonds == '' || isNaN(no_diamonds) == true))
                {
                    str ='Number of diamonds have to be Selected';
                    isValid = false;
                    $('#no_diamonds').focus();
                }
                else if(isValid && (dweight == undefined || dweight == 'undefined' || dweight == 'null' || dweight == '' || isNaN(dweight) == true))
                {
                    str ='Diamond weight is Required';
                    isValid = false;
                    $('#diamondweight').focus();
                }
            }
            if(isValid && (clarity !== undefined && clarity !== 'undefined' && clarity !== 'null' && clarity !== ''))
            {
                if(isValid && (color == undefined || color == 'undefined' || color == 'null' || color == ''))
                {
                    str ='Diamond Color field is Empty';
                    isValid = false;
                }
                else if(isValid && (clarity == undefined || clarity == 'undefined' || clarity == 'null' || clarity == ''))
                {
                    str ='Diamond Quality field is Empty';
                    isValid = false;
                }
                else if(isValid && (no_diamonds == undefined || no_diamonds == 'undefined' || no_diamonds == 'null' || no_diamonds == '' || isNaN(no_diamonds) == true))
                {
                    str ='Number of diamonds have to be Selected';
                    isValid = false;
                    $('#no_diamonds').focus();
                }
                else if(isValid && (dweight == undefined || dweight == 'undefined' || dweight == 'null' || dweight == '' || isNaN(dweight) == true))
                {
                    str ='Diamond weight is Required';
                    isValid = false;
                    $('#diamondweight').focus();
                }
            }
             if(isValid && (no_diamonds !== undefined && no_diamonds !== 'undefined' && no_diamonds !== 'null' && no_diamonds !== '' && isNaN(no_diamonds) == false))
            {
                if(isValid && (color == undefined || color == 'undefined' || color == 'null' || color == ''))
                {
                    str ='Diamond Color field is Empty';
                    isValid = false;
                }
                else if(isValid && (clarity == undefined || clarity == 'undefined' || clarity == 'null' || clarity == ''))
                {
                    str ='Diamond Quality field is Empty';
                    isValid = false;
                }
                else if(isValid && (no_diamonds == undefined || no_diamonds == 'undefined' || no_diamonds == 'null' || no_diamonds == '' || isNaN(no_diamonds) == true))
                {
                    str ='Number of diamonds have to be Selected';
                    isValid = false;
                    $('#no_diamonds').focus();
                }
                else if(isValid && (dweight == undefined || dweight == 'undefined' || dweight == 'null' || dweight == '' || isNaN(dweight) == true))
                {
                    str ='Diamond weight is Required';
                    isValid = false;
                    $('#diamondweight').focus();
                }
            }
            if(isValid && (dweight !== undefined && dweight !== 'undefined' && dweight !== 'null' && dweight !== '' && isNaN(dweight) == false))
            {
                
                if(isValid && (color == undefined || color == 'undefined' || color == 'null' || color == ''))
                {
                    str ='Diamond Color field is Empty';
                    isValid = false;
                }
                else if(isValid && (clarity == undefined || clarity == 'undefined' || clarity == 'null' || clarity == ''))
                {
                    str ='Diamond Quality field is Empty';
                    isValid = false;
                }
                else if(isValid && (no_diamonds == undefined || no_diamonds == 'undefined' || no_diamonds == 'null' || no_diamonds == '' || isNaN(no_diamonds) == true ))
                {
                    str ='Number of diamonds have to be Selected';
                    isValid = false;
                    $('#no_diamonds').focus();
                }
                else if(isValid && (dweight == undefined || dweight == 'undefined' || dweight == 'null' || dweight == '' || isNaN(dweight) == true))
                {
                    str ='Diamond weight is Required';
                    isValid = false;
                    $('#diamondweight').focus();
                }
            }
            
            if(isValid && (purity == '' || isNaN(purity) == true)) {
                str ='Purity field is Empty';
                isValid = false;
                 $('#goldpurity').focus();
            }
            if(isValid && (goldweight == '' || isNaN(goldweight) == true)) {
                str ='Gold weight is Required';
                isValid = false;
                $('#goldweight').focus();
            }
            if(isValid &&  barcode=='')
            {
                str ='Design Number field is Required';
                isValid = false;
                $('#barcode').focus();
            }
            if(isValid && (prdprice == '' || isNaN(prdprice) == true ))
            {
                str ='Product Price is important to fill';
                isValid = false;
                $('#prdprice').focus();
            }
            if(isValid && (gemweight !== undefined && gemweight !== 'undefined' && gemweight !== 'null' && gemweight !== null && gemweight !== '' && isNaN(gemweight) == false))
            {
                if(isValid && (gemweight == undefined || gemweight == 'undefined' || gemweight == 'null' || gemweight == '' || isNaN(gemweight) == true))
                {
                    str ='Gemstone weight is important to fill';
                    isValid = false;
                    $('#gemweight').focus();
                }
            }
            if(str != '')
            {
                common.toast(0,str);
            }
            else {
                
                submitForm('jwAddForm');
                return  true;
            }
            return false;
}
function calculatePrice()
{
   var baseprice=$('#baseprice').val();
   var discount=$('#discount').val();
   
   
   if(discount == null || discount == '' || discount == 'undefined' || discount == undefined){ 
   
        $('#prdprice').val(baseprice);
   }
   else 
   {
        if((isNaN(baseprice)) == false  || (isNaN(baseprice)) == false || baseprice == undefined  || baseprice == 'undefined' || discount == undefiend || discount == 'undefined')
        {
           var offerPrice =(parseFloat(baseprice)*(discount/100));
           var total = (baseprice-offerPrice).toFixed(2);
           if(total <= 0)
           {
               common.toast(0,'Price can not be in negative value');
               $('#prdprice').val('');
               $('#discount').val('');
           }
           else
           {
                $('#prdprice').val(total);
            }
        }
        
   }    
}
function validateNum(){
               var regex = /^\\d{2}(\\.\\d)?$/;
               var cert = $('#cert').val();
                if(cert.match(regex) == null || cert == undefined) {
                    common.toast(0,'valid');
                } else {
                    common.toast(0,'valid');
                }
            }
 function validateDForm(){
            var shape=$('.shapeComm');
            var metal = $("input[name='metal']:checked").val();
            var color = $("input[name='color']:checked").val();
            var cut = $("input[name='cut']:checked").val();
            var symmetry = $("input[name='symmetry']:checked").val();
            var polish = $("input[name='polish']:checked").val();
            var flourecence = $("input[name='flourecence']:checked").val();
            var certificate = $("input[name='Certficate']:checked").val();
            var clarity = $("input[name='clarity']:checked").val();
            var certno = $('#certno').val();
            var carat = $('#caratweight').val();
            var measure1 = $('#measure1').val();
            var measure2 = $('#measure2').val();
            var measure3 = $('#measure3').val();
            var table = $('#table').val();
            var crown = $('#crownangle').val();
            var girdle = $('#girdle').val();
            var barcode=$('#barcode').val();
            var lotnumber=$('#lotnumber').val();
            var lotreference=$('#lotreference').val();
            var baseprice=$('#baseprice').val();
            var discount=$('#discount').val();
            var prdprice=$('#prdprice').val();
            var str = '';
            carat = parseFloat(carat);
            crown = parseFloat(crown);
            if(!shape.hasClass('shapeSelected')) {
                str ='Shape is not Selected';
            }
            else if((color=='')||(color==null)||(color==undefined)) {
                str ='Color field is Empty';
            }
            else if((clarity=='')||(clarity==null)||(clarity==undefined)) {
                str ='Clarity field is Empty';
            }
            else if((cut=='')||(cut==null)||(cut==undefined)) {
                str ='Cut field is Empty';
            }
            else if((symmetry=='')||(symmetry==null)||(symmetry==undefined)) {
                str ='Symmetry field is Empty';
            }
            else if((polish=='')||(polish==null)||(polish==undefined)) {
                str ='Polish field is Empty';
            }
            else if((flourecence=='')||(flourecence==null)||(flourecence==undefined)) {
                str ='Flourecence field is Empty';
            }
            else if((certificate=='')||(certificate==null)||(certificate==undefined)) {
                str ='Certificate field is Empty';
            }
            else if((certno=='')) {
                str ='Certificate Number field is Empty';
            }
            else if(carat == '' || isNaN(carat)) {
                str ='Carat Weight field is Empty';
                 $('#caratweight').focus();
            }
            else if(measure1 == '' || isNaN(measure1)) {
                str ='Measurement column1 is Required';
                $('#measure1').focus();
            }
            else if(measure2 == '' || isNaN(measure2)) {
                str ='Measurement column2 is Required';
                $('#measure2').focus();
            }
            else if(measure3 == '' || isNaN(measure3)) {
                str ='Measurement column3 is Required';
                $('#measure3').focus();
            }
            else if(table == '' || isNaN(table)) {
                str ='Table field is Required';
                $('#table').focus();
            }
            else if(crown == '' || isNaN(crown)) {
                str ='Crown field is Required';
                $('#crownangle').focus();
            }
            else if(girdle == '' || isNaN(girdle)) {
                str ='Girdle field is Required';
                $('#girdle').focus();
            }
            else if(barcode=='') {
                str ='Barcode field is Required';
                $('#barcode').focus();
            }
            else if(lotnumber == '' || isNaN(lotnumber)) {
            str ='Lot number field is Required';
            $('#lotnumber').focus();
            }
            else if(lotreference=='') {
            str ='Lot reference field is Required';
                $('#lotreference').focus();
            }
            else if(baseprice == '' || isNaN(baseprice)) {
            str ='Baseprice field is Required';
                $('#baseprice').focus();
            }
            else if(prdprice == '' || isNaN(prdprice)) {
            str ='Price field is Required';
                $('#prdprice').focus();
            }
            if(str != '')
            {
                common.toast(0,str);
            }
            else {
                submitForm('dAddForm');
                return  true;
            }
            return false;
}

 function validateBForm(){
            var design =  $('input[name=design]:checked').val();
            var purity = $('#goldpurity').val();
            var barcode=$('#barcode').val();
            var spurity = $('#silverpurity').val();
            var goldweight = $('#goldweight').val();
            var sweight= $('#silverweight').val();
            var shape = $('.jshapeComm');
            var a = $('.jshapeComm.shapeSelected').attr('id');
            var str = '';
            var patt1 = /(^|[^-\d])(1|2|5|10|20|50|100|200|500|1000|)\b/;
            var patt2 = /(^|[^-\d])(999|995)\b/;
            if(!shape.hasClass('shapeSelected')) {
                str ='category is not Selected';
            }
            else if((design=='')||(design==null)||(design==undefined)) {
                str ='Design field is Empty';
            }
            else if(barcode=='') {
                str ='Design Number field is Required';
            }
            else if(a=='gCoins' || a=='gBars')
            {
                
                if(purity == '' || isNaN(purity) || !purity.match(patt2)){
                    str ='Gold Purity field is Invalid';
                }
                else if(goldweight=='' || !goldweight.match(patt1)) {
                    str ='Gold weight field is Invalid';
                }
            }
            else if(a=='sBars' || a=='sCoins')
            {
                if(spurity == '' || isNaN(spurity) || !spurity.match(patt2)) {
                    str ='Silver Purity field is Invalid';
                }
                else if(sweight == '' || isNaN(sweight) || !sweight.match(patt1)) {
                    str ='Silver Weight field is invalid';
                }
            }
            if(str != '')
            {
                common.toast(0,str);
            }
            else {
                submitForm('bAddForm');
                return  true;
            }
            return false;
}
function backbtn()
        {
            window.history.back();
            return true;
        }
        
        
function checkRates() {
    $.ajax({url: DOMAIN + "apis/index.php?action=getAllRatesByVID&vid="+uid, success: function(result) {
            var obj = jQuery.parseJSON(result);
            var goldRate = obj['results']['gold_rate'];
            var silverRate = obj['results']['silver_rate'];
            var dollarRate = obj['results']['dollar_rate'];
            if(catid==10002) {
                if(silverRate==0.00) {
                    showSilverRateForm();
                    $('#silverErr').removeClass('dn');
//                    customStorage.addToStorage('rateErr',1);
//                    window.location.assign(DOMAIN + "index.php?case=vendor_landing&catid=10002")
                } 
                if(goldRate==0.00) {
                    $('#goldErr').removeClass('dn');
                    showGoldRateForm();
//                    customStorage.addToStorage('rateErr',2);
//                    window.location.assign(DOMAIN + "index.php?case=vendor_landing&catid=10002")
                }
            } else if(catid==10000) {
                if(dollarRate==0.00) {
                    $('#dollarErr').removeClass('dn');
                    showDollarRateForm();
//                    customStorage.addToStorage('rateErr',3);
//                    window.location.assign(DOMAIN + "index.php?case=vendor_landing&catid=10000")
                }
            }
        }
    });
}
//checkRates();
