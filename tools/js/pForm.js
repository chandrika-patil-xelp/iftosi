function changeGemstoneType(obj)
{
	var gemType = $(obj).val();

	if(gemType !== undefined && gemType !== null && gemType !== '' && typeof gemType !== 'undefined')
	{
		$('.gemstoneProp').removeClass('dn');
	}
	else
	{
		$('.gemstoneProp').addClass('dn');
	}
}


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
    /*else if(formid=='jwAddForm'){
        y=$('#jwAddForm').serializeArray();
    }*/
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
        if(formid=='dAddForm'){
            uploadFile();
        }
        window.location.href = IMGUPLOAD+'pid-'+data.results.pid+'&c='+catid;
    });
    
}

function validateJForm()
{
	var shape=$('.jshapeComm');
	var certificate =  $('input[name=Certficate]:checked').val();
	var metal = $("input[name='metal']:checked").val();
	var diamondShape=$('.shapeComm');
	var color =  $('input[name=color]:checked').val();
	var clarity = $("input[name='clarity']:checked").val();
	var dweight = $('#diamondweight').val();
	var no_diamonds=$('#no_diamonds').val();
	var gemstone_type=$('#gemstone_type').val();
	var gemcolour = $("input[name='gemstone_color']:checked").val();
	var gemweight=$('#gemweight').val();
	var num_gemstones = $('#num_gemstones').val();
	var purity = $('#goldpurity').val();
	var goldweight=$('#goldweight').val();
	var barcode=$('#barcode').val();
	var prdprice=$('#prdprice').val();
	var othercert=$('#other_cerificate').val();
	var subcat = '';
	var isValid = true;

	$("input[name='subcat_type']:checked").each(function() {
		if(subcat !== '')
		{
			subcat += "," + $(this).val();
		}
		else
		{
			subcat = $(this).val();
		}
	})
	// Validations Start

	if(!shape.hasClass('shapeSelected'))
	{
		str ='Please select category';
		isValid = false;
	}

	if(isValid && subcat == '')
	{
		str ='Please select sub category';
		isValid = false;
	}

	if(isValid && (certificate === undefined || certificate === null || certificate === ''))
	{
		str ='Please select certificate';
		isValid = false;
	}
	else if (isValid)
	{
		if(isValid && certificate.toLowerCase() === 'other')
		{
			if(isValid && (othercert === undefined || othercert === null || othercert === ''))
			{
				isValid = false;
				str = 'Please enter certificate';
			}
		}
	}

	if(isValid && (metal === undefined || metal === null || metal === ''))
	{
		str = 'Please select metal type';
		isValid = false;
	}

	if(isValid && diamondShape.hasClass('shapeSelected'))
	{
		if(color === undefined || color === null || color === '')
		{
			str = 'Please select diamond color';
			isValid = false;
		}

		if(isValid && (clarity === undefined || clarity === null || clarity === ''))
		{
			str = 'Please enter diamond quality';
			isValid = false;
		}

		if(isValid && (dweight === undefined || dweight === null || dweight === ''))
		{
			str = 'Please enter diamond weight in carates';
			isValid = false;
		}

		if(isValid && (no_diamonds === undefined || no_diamonds === null || no_diamonds === ''))
		{
			str = 'Please select number of diamonds';
			isValid = false;
		}
	}
	else
	{
		diamondShape = color = clarity = dweight = no_diamonds = '';
	}

	if(isValid && (gemstone_type !== undefined && gemstone_type !== null && gemstone_type !== ''))
	{
		if(isValid && (gemcolour === undefined || gemcolour === null || gemcolour === ''))
		{
			str = 'Please select gemstone color';
			isValid = false;
		}

		if(isValid && (gemweight === undefined || gemweight === null || gemweight === ''))
		{
			str = 'Please enter gemstone weight';
			isValid = false;
		}

		if(isValid && (num_gemstones === undefined || num_gemstones === null || num_gemstones === ''))
		{
			str = 'Please enter number of gemstones';
			isValid = false;
		}
	}
	else
	{
		gemstone_type = gemcolour = gemweight = num_gemstones = '';
	}

	if(isValid && (purity === undefined || purity === null || purity === '' || isNaN(purity)))
	{
		str = 'Please enter purity';
		isValid = false;
	}

	if(isValid && (goldweight === undefined || goldweight === null || goldweight === '' || isNaN(goldweight)))
	{
		str = 'Please enter weight';
		isValid = false;
	}

	if(isValid && (barcode === undefined || barcode === null || barcode === ''))
	{
		str = 'Please enter design number';
		isValid = false;
	}

	if(isValid && (prdprice === undefined || prdprice === null || prdprice === '' || isNaN(prdprice)))
	{
		str = 'Please enter product price';
		isValid = false;
	}

	// Validations End

	if(isValid === false)
	{
		common.toast(0, str);
		return false;
	}
	else
	{
		// Submit form
		var URL = DOMAIN+"/apis/index.php?action=addNewproduct";
		var params
		if((pid !== null)&&(pid !== undefined)&&(pid !== '') && pid !== 'undefined' && typeof pid !== 'undefined')
		{
			var params = 'action=addNewproduct&category_id='+catid+'&prdid='+pid+'&vid='+uid;
		}
		else
		{
			var params = 'action=addNewproduct&category_id='+catid+'&vid='+uid;
		}

		var dt = '';

		var shapeVal = '';
		
		shape.each(function() {
			if($(this).hasClass('shapeSelected'))
			{
				shapeVal = $(this).attr('id');
			}
		});

		var diamondShapeVal = '';
		
		diamondShape.each(function() {
			if($(this).hasClass('shapeSelected'))
			{
				diamondShapeVal = $(this).attr('id');
			}
		});

		var values = new Array();
		values[0] = "shape|@|"+encodeURIComponent(shapeVal);
		values[1] = "subcatid|@|"+encodeURIComponent(subcat);
		values[2] = "Certficate|@|"+encodeURIComponent(certificate);
		values[3] = "metal|@|"+encodeURIComponent(metal);
		values[4] = "diamondShape|@|"+encodeURIComponent(diamondShapeVal);
		values[5] = "color|@|"+encodeURIComponent(color);
		values[6] = "clarity|@|"+encodeURIComponent(clarity);
		values[7] = "diamonds_weight|@|"+encodeURIComponent(dweight);
		values[8] = "no_diamonds|@|"+encodeURIComponent(no_diamonds);
		values[9] = "gemstone_type|@|"+encodeURIComponent(gemstone_type);
		values[10] = "gemstone_color|@|"+encodeURIComponent(gemcolour);
		values[11] = "gemstone_weight|@|"+encodeURIComponent(gemweight);
		values[12] = "num_gemstones|@|"+encodeURIComponent(num_gemstones);
		values[13] = "gold_purity|@|"+encodeURIComponent(purity);
		values[14] = "gold_weight|@|"+encodeURIComponent(goldweight);
		values[15] = "barcode|@|"+encodeURIComponent(barcode);
		values[16] = "price|@|"+encodeURIComponent(prdprice);

		dt = values.join('|~|');

		params += "&dt="+dt;

		$.getJSON(URL, params, function(data) {
			if(data !== undefined && data !== null && data !== '' && typeof data !== 'undefined')
			{
				if(data.error !== undefined && data.error !== null && data.error !== '' && typeof data.error !== 'undefined')
				{
					if(data.error.code !== undefined && data.error.code !== null && data.error.code !== '' && typeof data.error.code !== 'undefined' && data.error.code == 0)
					{
						window.location.href = IMGUPLOAD+'pid-'+data.results.pid+'&c='+catid;
					}
					else
					{
						common.toast(0, 'Error adding / updating information');
					}
				}
				else
				{
					common.toast(0, 'Error adding / updating information');
				}
			}
			else
			{
				common.toast(0, 'Error adding / updating information');
			}
		});
	}
}


/*
function validateJForm(){
            var metal = $("input[name='metal']:checked").val();
            var color =  $('input[name=color]:checked').val();
            var certificate =  $('input[name=Certficate]:checked').val();
            var clarity = $("input[name='clarity']:checked").val();
            var gemcolour = $("input[name='gemstone_color']:checked").val();
            var subcat = $("input[type='checkbox']").is(':checked');
            var purity = $('#goldpurity').val();
            var dweight = $('#diamondweight').val();
            var goldweight=$('#goldweight').val();
            var barcode=$('#barcode').val();
            var no_diamonds=$('#no_diamonds').val();
            var num_gemstones = $('#num_gemstones').val();
            var gemstone_type = $('#gemstone_type').val();
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
                if(isValid && (gemcolour=='' || gemcolour==null || gemcolour==undefined || gemcolour == 'undefiend')) 
                {
					str = 'Gemstone colour field is Empty';
					isValid = false;
                }

				if(isValid && (num_gemstones == undefined || num_gemstones == null || num_gemstones == '' || typeof num_gemstones == 'undefined'))
				{
					str = 'Number of Gemstone is Empty';
					isValid = false;
				}

				if(isValid && (gemstone_type == undefined || gemstone_type == null || gemstone_type == '' || typeof gemstone_type == 'undefined'))
				{
					str = 'Gemstone Type is Empty';
					isValid = false;
				}
            }

			if(isValid && num_gemstones !== undefined && num_gemstones !== null && num_gemstones !== '' && typeof num_gemstones !== 'undefined')
			{
				if(isValid && (gemcolour=='' || gemcolour==null || gemcolour==undefined || gemcolour == 'undefiend')) 
                {
					str = 'Gemstone colour field is Empty';
					isValid = false;
                }

				if(isValid && (gemweight == undefined || gemweight == 'undefined' || gemweight == 'null' || gemweight == '' || isNaN(gemweight) == true))
                {
                    str = 'Gemstone weight is important to fill';
                    isValid = false;
                    $('#gemweight').focus();
                }

				if(isValid && (gemstone_type == undefined || gemstone_type == null || gemstone_type == '' || typeof gemstone_type == 'undefined'))
				{
					str = 'Gemstone Type is Empty';
					isValid = false;
				}
			}

            if(isValid && (gemcolour !== undefined && gemcolour !== 'undefined' && gemcolour !== 'null' && gemcolour !== null && gemcolour !== ''))
            {
                if(isValid && (gemweight == undefined || gemweight == 'undefined' || gemweight == 'null' || gemweight == '' || isNaN(gemweight) == true))
                {
                    str = 'Gemstone weight is important to fill';
                    isValid = false;
                    $('#gemweight').focus();
                }

				if(isValid && (num_gemstones == undefined || num_gemstones == null || num_gemstones == '' || typeof num_gemstones == 'undefined'))
				{
					str = 'Number of Gemstone is Empty';
					isValid = false;
				}

				if(isValid && (gemstone_type == undefined || gemstone_type == null || gemstone_type == '' || typeof gemstone_type == 'undefined'))
				{
					str = 'Gemstone Type is Empty';
					isValid = false;
				}
            }

            if(isValid == false && str != '')
            {
                common.toast(0,str);
            }
            else
			{
                isValid = submitJewelleryForm();
                return  isValid;
            }
            return false;
}*/

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
           var total = (baseprice-offerPrice).toFixed(0);
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
            var patt1 = /(^|[^-\d])(1|2|5|10|20|50|100|200|500|1000)\b/;
            var patt2 = /(^|[^-\d])(999|995)\b/;
            var otherdesign = $('#otherdesign').val();
            var isValid = true;
            console.log(a);
            if(!shape.hasClass('shapeSelected')) {
                str ='category is not Selected';
                isValid = false;
            }
            if(isValid && (design=='' || design==null || design==undefined)) {
                str ='Design field is Empty';
                isValid = false;
            }
            if(isValid && design === 'Other')
            {
                if(isValid && (otherdesign == undefined || otherdesign == 'undefined' || otherdesign == null || otherdesign == 'null' || otherdesign == '' )){
                    str ='Please mention the Bullion Design in other field';
                    isValid = false;
                    $('#otherdesign').focus();
                }
            }
//            else if(isValid && (barcode !== '' && barcode == undefined && barcode == null && barcode == 'null' && typeof(barcode) == undefined )) {
//                str ='Design Number field is Required';
//                var isValid = false;
//            }
            if(a === 'gCoins' || a === 'gBars')
            {
                if(isValid && (purity == '' || isNaN(purity) || !purity.match(patt2))){
                    str ='Gold Purity field is Invalid';
                    isValid = false;
                }
                else if(isValid &&(goldweight == undefined || goldweight == '' || /*!goldweight.match(patt1) ||*/ isNaN(purity) || goldweight == null)) {
                    str ='Gold weight field is Invalid';
                    isValid = false;
                }
            }
            if(a=='sBars' || a=='sCoins')
            {
                if(isValid && (spurity == '' || isNaN(spurity) || !spurity.match(patt2))) {
                    str ='Silver Purity field is Invalid';
                    isValid = false;
                }
                else if(isValid && (sweight == '' || isNaN(sweight) || !sweight.match(patt1))) {
                    str ='Silver Weight field is invalid';
                    isValid = false;
                }
            }
            if(str != '')
            {
                common.toast(0,str);
            }
            if(isValid == true)
            {
                submitForm('bAddForm');
                return  true;
            }
            return false;
}
function backbtn()
    { 
            if(history.length <= 0){
            window.history.back();
            return true;
            }
            else if(history.length || history.length == 'undefiend' || history.length == undefiend || history.length == null || history.length == 'null' || history.length >= 0)
            {
                window.close();
                window.reload();
            }
    }
function showJewelleryImps(tmpId) {
    
                $('.subCatType').addClass('dn');
                $('#' + tmpId + 'Type').removeClass('dn');
                $('#allcontent').removeClass('dn');
            if ((tmpId == 'gbars') || (tmpId == 'gcoins'))
            {
                $('.allprop').addClass('dn');
                $('.goldprop').removeClass('dn');
                $('#silverpurity').val('');
                $('#silverweight').val('');
                if (tmpId == 'gbars') {
                    $('#goldweight').attr('placeholder','eg. Kgs Or Gms');
                } else {
                    $('#goldweight').attr('placeholder','eg. Gms');
                }
            }
            else if ((tmpId == 'sbars') || (tmpId == 'scoins'))
            {
                $('.allprop').addClass('dn');
                $('.silverprop').removeClass('dn');
                $('#goldpurity').val('');
                $('#goldweight').val('');
                if (tmpId == 'sbars') {
                    $('#silverweight').attr('placeholder','eg. Kgs Or Gms');
                } else {
                    $('#silverweight').attr('placeholder','eg. Gms');
                }
            }
}


function uploadFile()
{
   
         
    
}
function ValidateFile() {
    var allowedFiles = ["pdf","png","jpeg"];
    var fileUpload = document.getElementById("certfile").value;
    var fileExt = fileUpload.split('.').pop();
    if (allowedFiles.indexOf(fileExt)!=-1) {
        return true;
    }
    return false;
}