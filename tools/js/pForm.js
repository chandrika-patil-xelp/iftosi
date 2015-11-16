
 
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
   //console.log(y);
    $(y).each(function(i,val){
       // console.log(val.name);
       // console.log(val.value);
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
    console.log(data);
    //var JsonString = JSON.stringify(values);
    if((pid!==null)&&(pid!==undefined)&&(pid!==''))
    {
    var params = 'action=addNewproduct&category_id='+catid+'&dt='+data;
    }
    else
    {
    var params = 'action=addNewproduct&category_id='+catid+'&dt='+data+'&prdid='+pid;
    }
    var URL = DOMAIN+"apis/index.php";

    $.getJSON(URL, params, function(data) {
    console.log(data);    
    });
    
}

 function validateJForm(){
            var metal = $("input[name='metal']:checked").val();
            var color =  $('input[name=color]:checked').val();
            var certificate =  $('input[name=Certficate]:checked').val();
            var clarity = $("input[name='clarity']:checked").val();
            var subcat = $("input[name='subcat_type']").is(':checked');
            var purity = $('#goldpurity').val();
            var dweight = $('#diamondweight').val();
            var goldweight=$('#goldweight').val();
            var no_diamonds=$('#no_diamonds').val();
            var gemweight=$('#gemweight').val();
            var prdprice=$('#prdprice').val();
            var shape=$('.jshapeComm');
            var str = '';
           
            if(!shape.hasClass('shapeSelected')) {
                str ='category is not Selected';
            }
            else if(subcat<=false){
                str ='Checkbox button is empty';                
            }
            else if((certificate=='')||(certificate==null)||(certificate==undefined)) {
                str ='Certificate field is Empty';
            }
            else if((metal=='')||(metal==null)||(metal==undefined)) {
                str ='Metal field is Empty';
            }
            else if((color=='')||(color==null)||(color==undefined)) {
                str ='Color field is Empty';
            }
            else if((clarity=='')||(clarity==null)||(clarity==undefined)) {
                str ='Clarity field is Empty';
            }
            else if(purity=='') {
                str ='Gold Purity field is Empty';
                 $('#goldpurity').focus();
            }

            else if(goldweight=='') {
                str ='Gold weight is Required';
                $('#goldweight').focus();
            }
            else if(no_diamonds=='') {
                str ='Number of diamonds have to be Selected';
                 $('#no_diamonds').focus();
            }
            else if(dweight=='') {
                str ='Diamond weight is Required';
                $('#diamondweight').focus();
            }
            else if(gemweight=='') {
                str ='Gemstone weight is important to fill';
                 $('#gemweight').focus();
            }
            else if(prdprice=='') {
                str ='Product Price is important to fill';
                $('#prdprice').focus();
            }
            else {
               
                submitForm('jwAddForm');
                return  true;
            }
            common.toast(0,str);
            return false;
}

 function validateDForm(){
            var shape=$('.shapeComm');
            var metal = $("input[name='metal']:checked").val();
            var color = $('input[name=color]:checked').val();
            var cut = $('input[name=cut]:checked').val();
            var symmetry = $('input[name=symmetry]:checked').val();
            var polish = $('input[name=polish]:checked').val();
            var clarity = $("input[name='clarity']:checked").val();
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
            else if(carat=='') {
                str ='Carat Weight field is Empty';
                 $('#caratweight').focus();
            }
            else if(measure1=='') {
                str ='Measurement column1 is Required';
                $('#measure1').focus();
            }
            else if(measure2=='') {
                str ='Measurement column2 is Required';
                $('#measure2').focus();
            }
            else if(measure3=='') {
                str ='Measurement column3 is Required';
                $('#measure3').focus();
            }
            else if(table=='') {
                str ='Table field is Required';
                $('#table').focus();
            }
            else if(crown=='') {
                str ='Crown field is Required';
                $('#crownangle').focus();
            }
            else if(girdle=='') {
                str ='Girdle field is Required';
                $('#girdle').focus();
            }
            else if(barcode=='') {
                str ='Barcode field is Required';
                $('#barcode').focus();
            }
            else if(lotnumber=='') {
            str ='Lot number field is Required';
            $('#lotnumber').focus();
            }
            else if(lotreference=='') {
            str ='Lot reference field is Required';
                $('#lotreference').focus();
            }
            else if(baseprice=='') {
            str ='Baseprice field is Required';
                $('#baseprice').focus();
            }
            else if(discount=='') {
            str ='Discount field is Required';
                $('#discount').focus();
            }
            else if(prdprice=='') {
            str ='Price field is Required';
                $('#prdprice').focus();
            }
            else {
                submitForm('dAddForm');
                return  true;
            }
            common.toast(0,str);
            return false;
}

 function validateBForm(){
            var purity = $('#goldpurity').val();
            var spurity = $('#silverpurity').val();
            var goldweight = $('#goldweight').val();
            var sweight= $('#silverweight').val();
            var prprice = $('#prprice').val();
            var shape = $('.jshapeComm');
            var a = $('shapeSelected').attr('id');
            var str = '';

         
            if(!shape.hasClass('shapeSelected')) {
                str ='category is not Selected';
            }
            else if(a=='gCoin')
            {
                 if(purity=='') {
                    str ='Gold Purity field is Empty';
                     $('#goldpurity').focus();
                }
                else if(goldweight=='') {
                    str ='Gold weight is Required';
                    $('#goldweight').focus();
                }
            }
            else if(a=='sBar')
            {
                if(spurity=='') {
                    str ='Silver Purity field is Empty';
                    $('#silverpurity').focus();
                }
                else if(sweight=='') {
                    str ='Silver Weight field is Empty';
                     $('#silverweight').focus();
                }
            }
            else if(prprice=='') {
                str ='Product Price is important to fill';
                $('#prprice').focus();
            }
            else {
                
                submitForm('bAddForm');
                return  true;
            }
            common.toast(0,str);
            return false;
}